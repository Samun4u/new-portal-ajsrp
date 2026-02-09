<?php

namespace App\Http\Controllers;

use App\Http\Services\Payment\Payment;
use App\Models\ClientInvoice;
use App\Models\ClientOrder;
use App\Models\ClientOrderSubmission;
use App\Models\Gateway;
use App\Models\PrimaryCertificate;
use App\Models\Quotation;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    use ResponseTrait;

    public function verify(Request $request)
    {
        $invoice_id = $request->get('id', '');
        $payerId = $request->get('PayerID', NULL);
        $payment_id = $request->get('payment_id', NULL);
        $clientInvoice = ClientInvoice::findOrFail($invoice_id);
        $clientOrder = ClientOrder::findOrFail($clientInvoice->order_id);
        if ($clientInvoice->payment_status == PAYMENT_STATUS_PAID) {
            return redirect()->route('thankyou');
        }

        $gateway = Gateway::find($clientInvoice->gateway_id);
        DB::beginTransaction();
        try {
            if ($clientInvoice->gateway_id == $gateway->id && $gateway->slug == MERCADOPAGO) {
                $clientInvoice->payment_id = $payment_id;
                $clientInvoice->save();
            }

            $gatewayBasePayment = new Payment($gateway->slug, ['currency' => $clientInvoice->gateway_currency, 'tenant_id' => $clientInvoice->tenant_id]);

            $payment_data = $gatewayBasePayment->paymentConfirmation($clientInvoice->payment_id, $payerId);
            if ($payment_data['success']) {
                if ($payment_data['data']['payment_status'] == 'success') {
                    // invoice update
                    $clientInvoice->payment_status = PAYMENT_STATUS_PAID;
                    $clientInvoice->transaction_id = uniqid();
                    $clientInvoice->save();

                    $clientOrder->working_status = WORKING_STATUS_WORKING;
                    $clientOrder->increment('transaction_amount', $clientInvoice->total);
                    if ($clientOrder->transaction_amount >= $clientOrder->total) {
                        $clientOrder->payment_status = PAYMENT_STATUS_PAID;

                        //check client submission order and update approval status start =================================
                        $clientOrderSubmission = ClientOrderSubmission::with('journal')->where('client_order_id', $clientOrder->order_id)->first();
                        if (!is_null($clientOrderSubmission)) {
                            $clientOrderSubmission->approval_status = SUBMISSION_ORDER_STATUS_UNDER_PEER_REVIEW;
                            $clientOrderSubmission->save();

                            //create or get and send primary certificate start =============================================================
                            $primaryCertificate = PrimaryCertificate::where('client_order_id', $clientOrderSubmission->client_order_id)->first();
                            if(!$primaryCertificate){
                                $authors = $clientOrderSubmission->authors;

                                // $affiliations = [];
                                // foreach($authors as $author){
                                //     $affiliations = array_merge($affiliations, json_decode($author->affiliation, true));
                                // }
                                // $affiliations = array_unique($affiliations);
                                // $affiliations = implode(', ', $affiliations);

                                $affiliationGroups = [];

                                foreach ($authors as $author) {
                                    $decoded = json_decode($author->affiliation, true);

                                    if (is_array($decoded)) {
                                        if (isset($decoded[0]) && is_string($decoded[0])) {
                                            $affiliationGroups[] = implode(', ', $decoded);
                                        }
                                        elseif (isset($decoded[0]) && is_array($decoded[0])) {
                                            foreach ($decoded as $item) {
                                                $affiliationGroups[] = implode(', ', array_values($item));
                                            }
                                        }
                                    }
                                }


                                if (count($affiliationGroups) > 1) {
                                    $affiliations = implode(' | ', $affiliationGroups);
                                } else {
                                    $affiliations = implode('', $affiliationGroups);
                                }


                                $authors = $authors->map(function ($author) {
                                    return $author->first_name . ' ' . $author->last_name;
                                });

                                $authors = implode(', ', $authors->toArray());

                                //journal title
                                $journal = $clientOrderSubmission->journal;
                                $journal = $journal->title;

                                $paper_title = $clientOrderSubmission->article_title;

                                $primaryCertificate = new PrimaryCertificate();
                                $primaryCertificate->client_order_id = $clientOrderSubmission->client_order_id;
                                $primaryCertificate->author_names = $authors;
                                $primaryCertificate->author_affiliations = $affiliations;
                                $primaryCertificate->journal_name = $journal;
                                $primaryCertificate->paper_title = $paper_title;
                                $primaryCertificate->save();
                            }

                            // DISABLED: Certificates will be sent only after user submits authors-form and admin approves
                            // primaryCertificateEmailNotifyForCustomer($primaryCertificate->client_order_id);
                            // PrimaryCertificateNotifyForCustomer($primaryCertificate->client_order_id);

                            // Notify user to submit the authors-form
                            notifyUserToSubmitAuthorsForm($clientOrder->client_id, $primaryCertificate->client_order_id);

                            //create or get and send primary certificate end =============================================================
                        }
                        //check client submission order and update approval status end =================================
                    }
                    $clientOrder->save();
                    if (!is_null($clientOrder->quotation_id)) {
                        $quotation = Quotation::find($clientOrder->quotation_id);
                        if (!is_null($quotation)) {
                            $quotation->status = QUOTATION_STATUS_PAID;
                            $quotation->save();
                        }
                    }
                    DB::commit();

                    //notification call start
                    setCommonNotification($clientInvoice->client_id, __('Have a new checkout'), __('Invoice Id: ') . $clientInvoice->invoice_id, '');
                    // send success mail
                    orderMailNotify($clientInvoice->id, INVOICE_MAIL_TYPE_PAID);

                    return redirect()->route('thankyou');
                }
            } else {
                return redirect()->route('failed');
            }
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('failed');
        }
    }

    public function thankyou()
    {
        return view('frontend.thankyou');
    }

    public function waiting()
    {
        return view('frontend.waiting');
    }

    public function failed()
    {
        return view('frontend.failed');
    }
}
