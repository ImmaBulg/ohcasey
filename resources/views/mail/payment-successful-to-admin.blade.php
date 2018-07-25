<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>OHCASEY: Успешная оплата #{{$order->order_id}}</title>
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
<h1>Заказ #<a href="{{route('admin.order.show', ['order' => $order])}}">{{ $order->order_id }}</a> оплачен на сумму {{$payment->amount}} руб.</h1>
</body>
</html>
