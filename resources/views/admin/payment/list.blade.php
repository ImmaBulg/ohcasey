<table class="table">
    <thead>
        <tr>
            <th>Дата создания</th>
            <th>Ссылка оплаты</th>
            <th>Сумма</th>
            <th>Оплачен</th>
            <th>Дата оплаты</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @forelse ($order->payments as $payment)
            <tr class="js-payment-container">
                <td>{{ $payment->created_at->format('H:i d.m.Y') }}</td>
                <td>{{ route('payment.do_pay', ['paymentHash' => $payment->hash]) }}</td>
                <td>{{ $payment->amount }}</td>
                <td>{{ $payment->isPaid() ? 'Да' : 'Нет'}}</td>
                <td>{{ $payment->isPaid() ? $payment->paid_date->format('H:i d.m.Y') : ''}}</td>
                <td>
                    <a href="{{route('admin.payment.email_send', ['payment' => $payment])}}">
                        Отправить ссылку на почту {{data_get($payment, 'order.client_email', 'email не указан')}}
                    </a>
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