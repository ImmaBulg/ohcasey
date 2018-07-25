<table class="table">
    <thead>
    <tr>
        <th>Ссылка оплаты</th>
        <th>Сумма</th>
        <th>Оплачен</th>
        <th>Отправить</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($order->payments as $payment)
        <tr class="js-payment-container">
            <td>{{ route('payment.do_pay', ['paymentHash' => $payment->hash]) }}</td>
            <td>{{ $payment->amount }}  <span class="icon-rouble"></span></td>
            <td>{{ $payment->isPaid() ? 'Да' : 'Нет'}}</td>
            <td>
                @if (data_get($payment, 'order.client_phone', null))
                    <a class="btn btn-xs btn-primary" href="{{route('admin.payment.sms_send', ['payment' => $payment])}}">
                        по sms
                    </a>
                @endif
                @if (data_get($payment, 'order.client_email', null))
                    <a class="btn btn-xs btn-primary" href="{{route('admin.payment.email_send', ['payment' => $payment])}}">
                        на email
                    </a>
                @endif
                @if (!$payment->isPaid())
                    <a class="js-payment-delete" href="{{route('admin.payment.ajax_delete', $payment)}}">
                        Удалить
                    </a>
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="3">-</td>
        </tr>
    @endforelse
    </tbody>
</table>