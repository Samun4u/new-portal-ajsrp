<div class="modal-body p-sm-25 p-15">
    <div
        class="d-flex justify-content-between align-items-center g-10 pb-20 mb-17 bd-b-one bd-c-stroke">
        <h4 class="fs-18 fw-600 lh-22 text-title-black">{{ __('Transaction Details') }}</h4>
        <button type="button"
                class="bd-one bd-c-stroke rounded-circle w-24 h-24 bg-transparent text-para-text fs-13"
                data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
    </div>

    <div class="transaction-table-part">
        <div class="table-responsive">
            <table class="table zTable zTable-last-item-right">
                <thead>
                <tr>
                    <th class="invoice-heading-color"><div class="text-nowrap">{{ __('Date') }}</div></th>
                    <th class="invoice-heading-color"><div class="text-nowrap">{{ __('Gateway') }}</div></th>
                    <th class="invoice-heading-color"><div class="text-nowrap">{{ __('Transaction ID') }}</div></th>
                    <th class="invoice-tbl-last-field invoice-heading-color"><div class="text-nowrap">{{ __('Amount') }}</div></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="orderDate">{{$order->created_at}}</td>
                    <td class="orderPaymentTitle">{{$order->gateway->title}}</td>
                    <td class="orderPaymentId">{{$order->transaction_id}}</td>
                    <td class="orderTotal invoice-tbl-last-field">{{$order->amount}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
