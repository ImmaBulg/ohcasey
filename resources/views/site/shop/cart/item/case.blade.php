<div class="cart-list__item js-case-container">
    <div class="cart-list__img">
        <img src="{{route('cart.case.image', ['id' => $case->cart_set_id])}}" alt="">
    </div>
    <div class="cart-list__desc">
        {{--<div class="cart-list__title">Starbucks Fairy</div>--}}
        <div class="cart-list__model">Телефон: {{$case->device->device_caption}}</div>
        @if (isset($case->device->device_colors[$case->item_source['DEVICE']['color']]))
            <div class="cart-list__text">Цвет телефона: <div class="cart-list__color" style="background-color: {{$case->device->device_colors[$case->item_source['DEVICE']['color']]}}"></div></div>
        @endif
        @if ($case->item_source['DEVICE']['casey'] == 'silicone')
            <div class="cart-list__text">Материал: Силикон</div>
        @elseif ($case->item_source['DEVICE']['casey'] == 'plastic')
            <div class="cart-list__text">Материал: Пластик</div>
		@elseif ($case->item_source['DEVICE']['casey'] == 'glitter')	
			<div class="cart-list__text">Материал: Жидкий глиттер</div>
		@elseif ($case->item_source['DEVICE']['casey'] == 'glitter_1')	
			<div class="cart-list__text">Материал: Жидкий глиттер</div>
		@elseif ($case->item_source['DEVICE']['casey'] == 'glitter_2')	
			<div class="cart-list__text">Материал: Жидкий глиттер</div>
		@elseif ($case->item_source['DEVICE']['casey'] == 'glitter_3')	
			<div class="cart-list__text">Материал: Жидкий глиттер</div>
		@elseif ($case->item_source['DEVICE']['casey'] == 'glitter_4')	
			<div class="cart-list__text">Материал: Жидкий глиттер</div>
        @else
            <div class="cart-list__text">Материал: Soft Touch</div>
        @endif
    </div>
    <div class="cart-list__count">
        <div class="select">
            <select class="js-select js-count-select" data-update-count-url="{{route('shop.cart.update_count', $case)}}" data-select="counter">
                <option {{$case->item_count == '1' ? 'selected="selected"' : ''}} value="1">1</option>
                <option {{$case->item_count == '2' ? 'selected="selected"' : ''}} value="2">2</option>
                <option {{$case->item_count == '3' ? 'selected="selected"' : ''}} value="3">3</option>
                <option {{$case->item_count == '4' ? 'selected="selected"' : ''}} value="4">4</option>
                <option {{$case->item_count == '5' ? 'selected="selected"' : ''}} value="5">5</option>
            </select>
        </div>
        <a class="js-remove-case" data-remove-url="{{route('shop.cart.remove_case', $case)}}">Удалить</a>
		</div>
    <div class="cart-list__price"><span class="price">{{$case->item_cost}}</span></div>
	<input type="hidden" name="product-id" value="{{ $case->offer ? $case->offer->product->id : $case->cart_id }}">
	<input type="hidden" name="product-category" value="{{ $case->offer ? $case->offer->product->firstCategory()->name : $case->device->device_colors[$case->item_source['DEVICE']['color']] }}">
</div>