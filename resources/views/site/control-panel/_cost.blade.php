<?php
	$item = \App\Models\Item::find(\App\Ohcasey\Ohcasey::SKU_DEVICE);
?>
<div class="clearfix">
	{{--<div class="pull-left">--}}
		{{--<div class="case-cost">--}}
			{{--<div class="up-pointer"></div>--}}
			{{--<strong>{{ $item->item_cost }} <span class="icon-rouble"></span></strong>--}}
			{{--<div>Стоимость любого чехла</div>--}}
		{{--</div>--}}
	{{--</div>--}}
	{{--<div class="pull-left">--}}
		<div id="make-order" class="oh-button">
			@if(session('isAdminEdit', false))
				Обновить в заказе
			@else
				<span class="icon-cart" data-cost="{{ $item->item_cost }}"></span> КУПИТЬ ЗА {{ $item->item_cost }}<span class="icon-rouble"></span>
			@endif
		</div>
	{{--</div>--}}
</div>
