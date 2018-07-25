<form action="{{route('admin.payment.create_payment', ['order' => $order])}}" method="post">
    <div class="row" style="margin-left:0;margin-right:0">
        <div class="col-lg-5 col-md-5">
            <h3>Сумма заказа: {{$order->getTotalSum()}} <span class="icon-rouble"></span></h3>
        </div>
        <div class="col-md-4">
            <span class="mt20 payment-status {{ trans('order.payment_status.color.' . $order->getPaymentsStatus()) }}">{{ trans('order.payment_status.' . $order->getPaymentsStatus()) }}</span>
        </div>
    </div>
    <div class="row" style="margin-left:0;margin-right:0;margin-top:10px;">
        <div class="col-lg-5 col-md-5">
            <label class="email control-label" for="amount">
                Создать ссылку для оплаты
            </label>
        </div>
        <div class="col-md-2">
            <input class="string form-control" value="{{$order->getTotalSum()}}" id="amount" name="amount" placeholder="Введите сумму на оплату">
        </div>
        <div class="col-md-1">
            <input type="submit" value="Создать" class="btn btn-primary">
        </div>
    </div>
</form>