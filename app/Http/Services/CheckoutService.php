<?php

namespace App\Http\Services;

use App\Http\Services\Payment\Payment;
use App\Models\Bank;
use App\Models\ClientInvoice;
use App\Models\ClientOrderInformation;
use App\Models\Coupon;
use App\Models\Currency;
use App\Models\FileManager;
use App\Models\Gateway;
use App\Models\GatewayCurrency;
use App\Models\OrderForm;
use App\Models\OrderFormService;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Service;
use App\Models\User;
use App\Models\UserPackage;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CheckoutService
{
    use ResponseTrait;

    public function getCurrencyByGatewayId($id)
    {
        $currencies = GatewayCurrency::where(['gateway_id' => $id])->get();
        foreach ($currencies as $currency) {
            /** @var TYPE_NAME $currency */
            $currency->symbol =  $currency->symbol;
        }
        return $currencies?->makeHidden(['created_at', 'updated_at', 'deleted_at', 'gateway_id']);
    }

    public function checkoutOrder($request)
    {
        DB::beginTransaction();
        try {
            $checkout_details = decrypt($request->checkout_details);
            $userId = $checkout_details['user_id'];
            $gatewayUser = User::find($userId);
            $tenantId = $checkout_details['tenant_id'];

            $amount = 0.00;
            $discount = 0;
            $discount_type = DISCOUNT_TYPE_FLAT;
            $platform_charge = 0.00;
            $order_create_type = 0;

            if ($checkout_details['type'] == CHECKOUT_TYPE_ORDER_FORM) {
                $oderFormId = $checkout_details['id'];
                $orderForm =  OrderForm::where('tenant_id', $gatewayUser->tenant_id)->findOrFail($oderFormId);
                $orderFormServices = OrderFormService::where('order_form_id', $orderForm->id)->get();
                $amount = $orderForm->total;
            } elseif ($checkout_details['type'] == CHECKOUT_TYPE_QUOTATION) {
                $quotation = Quotation::where(['tenant_id' => $gatewayUser->tenant_id])->find($checkout_details['id']);
                $discount = $quotation->discount;
                $orderFormServices = QuotationItem::where('quotation_id', $quotation->id)->get();
                $amount = $quotation->total; //$orderFormServices->sum('total');
            } else {
                return back()->with('error', __(SOMETHING_WENT_WRONG));
            }

            $gateway = Gateway::where(['slug' => $request->gateway, 'tenant_id' => $gatewayUser->tenant_id, 'status' => ACTIVE])->firstOrFail();
            $gatewayCurrency = GatewayCurrency::where(['gateway_id' => $gateway->id, 'currency' => $request->currency])->firstOrFail();
            $data['gateway'] = $request->gateway;
            $data['checkout_type'] = $checkout_details['type'];

            $orderFormServiceIds = $orderFormServices->pluck('service_id');
            $coupon = Coupon::query()
                ->where('tenant_id', $gatewayUser->tenant_id)
                ->where('code', $request->coupon_code)
                ->where(function ($q) use ($orderFormServiceIds) {
                    foreach ($orderFormServiceIds as $id) {
                        $q->orWhereJsonContains('service_ids', (string) $id);
                    }
                })
                ->where('status', STATUS_ACTIVE)
                ->select('service_ids', 'discount_type', 'discount_amount', 'valid_date')
                ->first();

            $userClient = $this->clientCreate($request, $userId, $tenantId);

            if (!is_null($coupon)) {
                if ($coupon->valid_date >= date('Y-m-d')) {
                    if ($coupon->discount_type == DISCOUNT_TYPE_FLAT) {
                        $discount = $coupon->discount_amount;
                    } else {
                        $discount = $amount * $coupon->discount_amount * 0.01;
                        $discount_type = DISCOUNT_TYPE_PERCENT;
                    }
                }
            }

            $orderData = [
                'amount' => $amount,
                'discount' => $discount,
                'discount_type' => $discount_type,
                'platform_charge' => $platform_charge,
                'order_create_type' => $order_create_type,
                'orderItems' => $orderFormServices,
                'recurring_type' => RECURRING_EVERY_MONTH,
                'recurring_payment_type' => PAYMENT_TYPE_ONETIME,
            ];

            if ($checkout_details['type'] == CHECKOUT_TYPE_ORDER_FORM) {
                $orderData = array_merge($orderData, ['order_form_id' => $orderForm->id]);
            } elseif ($checkout_details['type'] == CHECKOUT_TYPE_QUOTATION) {
                $orderData = array_merge($orderData, ['quotation_id' => $quotation->id]);
            }

            $clientOrder = makeClientOrder($orderData, $userClient, $userId, $tenantId);
            if ($clientOrder['success'] != true) {
                throw new Exception(__(SOMETHING_WENT_WRONG));
            }

            $invoiceData = [
                'order' => $clientOrder['data'],
                'gateway' => $gateway,
                'gateway_currency' => $gatewayCurrency,
                'platform_charge' => 0.00
            ];

            $clientInvoice = makeClientInvoice($invoiceData, $userClient->id, $userId, $tenantId);
            if ($clientInvoice['success'] != true) {
                throw new Exception(__(SOMETHING_WENT_WRONG));
            }

            orderMailNotify($clientInvoice['data']->id, INVOICE_MAIL_TYPE_UNPAID);

            if ($gateway->slug == 'bank') {
                $bank = Bank::where(['gateway_id' => $gateway->id])->find($request->bank_id);
                if (is_null($bank)) {
                    throw new Exception(__('The bank not found'));
                }

                $bank_id = $bank->id;
                $deposit_by = $userClient->name;
                $deposit_slip_id = null;
                if ($request->hasFile('bank_slip')) {
                    $newFile = new FileManager();
                    $uploaded = $newFile->upload('ClientInvoice', $request->bank_slip);
                    if ($uploaded) {
                        $deposit_slip_id = $uploaded->id;
                    }
                } else {
                    throw new Exception(__('The bank slip is required'));
                }

                $clientInvoice['data']->bank_id = $bank_id;
                $clientInvoice['data']->bank_deposit_by = $deposit_by;
                $clientInvoice['data']->bank_deposit_slip_id = $deposit_slip_id;
                $clientInvoice['data']->payment_id = uniqid('BNK');
                $clientInvoice['data']->save();
                DB::commit();
                $message = __('Bank Details Sent Successfully! Wait for approval');
                return $this->success($data, $message);
            } elseif ($gateway->slug == 'cash') {
                $clientInvoice['data']->payment_id = uniqid('CAS');
                $clientInvoice['data']->save();
                DB::commit();
                $message = __('Cash Payment Request Sent Successfully! Wait for approval');
                return $this->success($data, $message);
            } else {
                $object = [
                    'id' => $clientInvoice['data']->id,
                    'callback_url' => route('payment.verify'),
                    'currency' => $gatewayCurrency->currency,
                    'tenant_id' => $tenantId,
                    'cancel_url' => route('failed')
                ];

                $payment = new Payment($gateway->slug, $object);
                $responseData = $payment->makePayment($clientOrder['data']->total);
                if ($responseData['success']) {
                    $clientInvoice['data']->payment_id = $responseData['payment_id'];
                    $clientInvoice['data']->save();
                    $data['redirect_url'] = $responseData['redirect_url'];
                    DB::commit();
                    return $this->success($data);
                } else {
                    throw new Exception($responseData['message']);
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function clientCreate($request, $userId, $tenantId)
    {
        if(isAddonInstalled('ENCYSAAS')) {
            $userPackage = UserPackage::query()
                ->where(['status' => ACTIVE, 'tenant_id' => $tenantId])
                ->whereDate('end_date', '>=', now()->toDateTimeString())
                ->first();

            $totalClient = User::where(['role' => USER_ROLE_CLIENT, 'tenant_id' => $tenantId])->count();

            if (is_null($userPackage) || ($userPackage->number_of_client != -1 && $userPackage->number_of_client <= $totalClient)) {
                throw new Exception(__("Something went wrong, Contact with admin"));
            }
        }

        $user = User::where('email', $request->email)->first();
        if (is_null($user)) {
            $password = $request->password ?? rand(111111, 999999);
            $user =  new User();
            $user->name = $request->name;
            $user->mobile = $request->phone;
            $user->email = $request->email;
            $user->password = Hash::make($password);
            $user->created_by = $userId;
            $user->role = USER_ROLE_CLIENT;
            $user->tenant_id = $tenantId;
            $user->save();
        }

        if ($request->custom_fields) {
            $clientOrderInformation = new ClientOrderInformation();
            $clientOrderInformation->user_id = $user->id;
            $clientOrderInformation->custom_fields = $request->custom_fields;
            $clientOrderInformation->save();
        }

        return $user;
    }

    public function userCheckoutOrder($request)
    {
        Log::debug("user checkout order started");
        DB::beginTransaction();
        try {
            if ($request['checkout_type'] == CHECKOUT_TYPE_USER_INVOICE) {

                Log::debug("user invoice checkout started");

              $clientInvoice = ClientInvoice::where('tenant_id', auth()->user()->tenant_id)->findOrFail($request->invoice_id);
                $gateway = Gateway::where(['slug' => $request->gateway, 'tenant_id' => auth()->user()->tenant_id, 'status' => ACTIVE])->firstOrFail();
                $gatewayCurrency = GatewayCurrency::where(['gateway_id' => $gateway->id, 'currency' => $request->currency])->firstOrFail();

                $clientInvoice->gateway_id = $gateway->id;
                $clientInvoice->gateway_currency = $gatewayCurrency->currency;
                $clientInvoice->conversion_rate = $gatewayCurrency->conversion_rate;
                $clientInvoice->transaction_amount = $clientInvoice->total * $gatewayCurrency->conversion_rate;
                $clientInvoice->system_currency = Currency::where('current_currency', 'on')
                    ->orWhere(['current_currency' => 1])->first()->currency_code;
                $clientInvoice->save();
            } elseif ($request['checkout_type'] == CHECKOUT_TYPE_USER_SERVICE) {
                Log::debug("user service checkout started");
                $service = Service::where('tenant_id', auth()->user()->tenant_id)->findOrFail($request->item_id);
                $gateway = Gateway::where(['id' => $request->gateway_id, 'tenant_id' => auth()->user()->tenant_id, 'status' => ACTIVE])->firstOrFail();
                $gatewayCurrency = GatewayCurrency::where(['gateway_id' => $gateway->id, 'id' => $request->currency])->firstOrFail();

                $userClient = auth()->user();
                $userId = $service->user_id;
                $gatewayUser = User::find($userId);
                $tenantId = auth()->user()->tenant_id;
                $coupon = Coupon::query()
                    ->where('tenant_id', $gatewayUser->tenant_id)
                    ->where('code', $request->coupon)
                    ->where(function ($q) use ($service) {
                        $q->orWhereJsonContains('service_ids', (string) $service->id);
                    })
                    ->where('status', STATUS_ACTIVE)
                    ->select('service_ids', 'discount_type', 'discount_amount', 'valid_date')
                    ->first();
                $amount = $service->price;
                $discount = 0;
                $discount_type = DISCOUNT_TYPE_FLAT;
                $platform_charge = 0.00;
                $order_create_type = 0;

                if (!is_null($coupon)) {
                    if ($coupon->valid_date >= date('Y-m-d')) {
                        if ($coupon->discount_type == DISCOUNT_TYPE_FLAT) {
                            $discount = $coupon->discount_amount;
                        } else {
                            $discount = $amount * $coupon->discount_amount * 0.01;
                            $discount_type = DISCOUNT_TYPE_PERCENT;
                        }
                    }
                }

                $orderItems = [
                    (object) [
                        'service_id' => $service->id,
                        'price' => $service->price,
                        'quantity' => 1,
                    ]
                ];

                $orderData = [
                    'amount' => $amount,
                    'discount' => $discount,
                    'discount_type' => $discount_type,
                    'platform_charge' => $platform_charge,
                    'order_create_type' => $order_create_type,
                    'orderItems' => (object) ($orderItems),
                    'recurring_type' => $service->recurring_type,
                    'recurring_payment_type' => $service->payment_type,
                ];
                $clientOrder = makeClientOrder($orderData, $userClient, $userId, $tenantId);

                if ($clientOrder['success'] != true) {
                    throw new Exception($clientOrder['message']);
                }

                $invoiceData = [
                    'order' => $clientOrder['data'],
                    'gateway' => $gateway,
                    'gateway_currency' => $gatewayCurrency,
                    'platform_charge' => 0.00
                ];
                
                

                $clientInvoice = makeClientInvoice($invoiceData, $userClient->id, $userId, $tenantId);
                if ($clientInvoice['success'] != true) {
                    
                    throw new Exception($clientInvoice['message']);
                }
                
                $clientInvoice = $clientInvoice['data'];
            } else {
                return back()->with('error', __(SOMETHING_WENT_WRONG));
            }

            $data['gateway'] = $request->gateway;
            $data['checkout_type'] = $request['checkout_type'];

            orderMailNotify($clientInvoice->id, INVOICE_MAIL_TYPE_UNPAID);

            if ($gateway->slug == 'bank') {
                $bank = Bank::where(['gateway_id' => $gateway->id])->find($request->bank_id);
                if (is_null($bank)) {
                    throw new Exception(__('The bank not found'));
                }

                $bank_id = $bank->id;
                $deposit_by = auth()->user()->name;
                $deposit_slip_id = null;
                if ($request->hasFile('bank_slip')) {
                    $newFile = new FileManager();
                    $uploaded = $newFile->upload('ClientInvoice', $request->bank_slip);
                    if ($uploaded) {
                        $deposit_slip_id = $uploaded->id;
                    }
                } else {
                    throw new Exception(__('The bank slip is required'));
                }

                $clientInvoice->bank_id = $bank_id;
                $clientInvoice->bank_deposit_by = $deposit_by;
                $clientInvoice->bank_deposit_slip_id = $deposit_slip_id;
                $clientInvoice->payment_id = uniqid('BNK');
                $clientInvoice->save();
                DB::commit();
                $message = __('Bank Details Sent Successfully! Wait for approval');
                return $this->success($data, $message);
            } elseif ($gateway->slug == 'cash') {
                $clientInvoice->payment_id = uniqid('CAS');
                $clientInvoice->save();
                DB::commit();
                $message = __('Cash Payment Request Sent Successfully! Wait for approval');
                return $this->success($data, $message);
            } else {
                $object = [
                    'id' => $clientInvoice->id,
                    'callback_url' => route('payment.verify'),
                    'currency' => $gatewayCurrency->currency,
                    'tenant_id' => auth()->user()->tenant_id,
                    'cancel_url' => route('failed'),
                ];
                $payment = new Payment($gateway->slug, $object);
                $responseData = $payment->makePayment($clientInvoice->total);
                if ($responseData['success']) {
                    $clientInvoice->payment_id = $responseData['payment_id'];
                    $clientInvoice->save();
                    $data['redirect_url'] = $responseData['redirect_url'];
                    DB::commit();
                    return $this->success($data);
                } else {
                    Log::debug("stripe error: " . $responseData['message']);
                    throw new Exception($responseData['message']);
                }
            }
        } catch (Exception $e) {
            Log::debug($e);
            DB::rollBack();
            $message = getErrorMessage($e);
            return $this->error([], $message);
        }
    }
    
    
    
    public function usercheckoutOrderWithoutpaiement($request)
    {
        DB::beginTransaction();
        try {
            $service = Service::where('tenant_id', auth()->user()->tenant_id)->findOrFail($request->id);

            $userClient = auth()->user();
            $userId = $service->user_id;
            $tenantId = auth()->user()->tenant_id;

            $amount = $service->price;
            $discount = 0;
            $discount_type = DISCOUNT_TYPE_FLAT;
            $platform_charge = 0.00;
            $order_create_type = 0;

            $orderItems = [
                (object) [
                    'service_id' => $service->id,
                    'price' => $service->price,
                    'quantity' => 1,
                ]
            ];

            $orderData = [
                'amount' => $amount,
                'discount' => $discount,
                'discount_type' => $discount_type,
                'platform_charge' => $platform_charge,
                'order_create_type' => $order_create_type,
                'orderItems' => (object) ($orderItems),
                'recurring_type' => $service->recurring_type,
                'recurring_payment_type' => $service->payment_type,
            ];
            $clientOrder = makeClientOrder($orderData, $userClient, $userId, $tenantId);

            if ($clientOrder['success'] != true) {
                throw new Exception($clientOrder['message']);
            }

            DB::commit();

            return redirect()->route('user.orders.task-board.index', $clientOrder['data']->id);

        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e);
            return $this->error([], $message);
        }
    }
}
