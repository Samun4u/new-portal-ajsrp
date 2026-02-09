<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ClientInvoice;
use App\Models\ClientOrder;
use App\Models\User;
use App\Traits\ResponseTrait;

class InvoiceController extends Controller
{
    use ResponseTrait;

    public function recurringInvoiceMaker()
    {
        $currentDate = date('Y-m-d');
        $clientOrders = ClientOrder::query()
            ->where('payment_status', PAYMENT_STATUS_PAID)
            ->where('recurring_payment_type', PAYMENT_TYPE_RECURRING)
            ->get();

        foreach ($clientOrders as $order) {
            $invoiceData = [
                'order' => $order,
                'due_date' => now()->addDays(5),
                'payable_amount' => $order->total,
                'is_recurring' => ACTIVE,
            ];
            $clientExist = User::where('status', ACTIVE)->find($order->client_id);
            if ($clientExist) {
                if ($order->recurring_type == RECURRING_EVERY_DAY) {
                    $clientInvoiceExist = ClientInvoice::query()
                        ->where('order_id', $order->id)
                        ->whereDate('created_at', $currentDate)
                        ->first();

                    if (!$clientInvoiceExist) {
                        makeClientInvoice($invoiceData, $order->client_id, $order->user_id, $order->tenant_id);
                    }
                } elseif ($order->recurring_type == RECURRING_EVERY_MONTH) {
                    $clientInvoiceExist = ClientInvoice::query()
                        ->where('order_id', $order->id)
                        ->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->first();

                    if (!$clientInvoiceExist) {
                        makeClientInvoice($invoiceData, $order->client_id, $order->user_id, $order->tenant_id);
                    }
                } elseif ($order->recurring_type == RECURRING_EVERY_YEAR) {
                    $clientInvoiceExist = ClientInvoice::query()
                        ->where('order_id', $order->id)
                        ->whereYear('created_at', now()->year)
                        ->first();
                    if (!$clientInvoiceExist) {
                        makeClientInvoice($invoiceData, $order->client_id, $order->user_id, $order->tenant_id);
                    }
                }
            }
        }
    }

}
