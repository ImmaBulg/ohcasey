<div class="cart-list__item js-case-container">
    <div class="cart-list__img">
        <img src="{{$product->offer->product->mainPhoto()}}" alt="">
    </div>
    <div class="cart-list__desc">
        <div class="cart-list__model">{{$product->offer->product->name}}</div>
        <div class="cart-list__text">{{$product->offer->optionValues->implode(', ')}}</div>
    </div>
    <div class="cart-list__count">
        <div class="select">
            <select class="js-select js-count-select" data-update-count-url="{{route('shop.cart.update_count_product', $product)}}" data-select="counter">
                <option {{$product->item_count == '1' ? 'selected="selected"' : ''}} value="1">1</option>
                <option {{$product->item_count == '2' ? 'selected="selected"' : ''}} value="2">2</option>
                <option {{$product->item_count == '3' ? 'selected="selected"' : ''}} value="3">3</option>
                <option {{$product->item_count == '4' ? 'selected="selected"' : ''}} value="4">4</option>
                <option {{$product->item_count == '5' ? 'selected="selected"' : ''}} value="5">5</option>
            </select>
        </div>
        <a class="js-remove-product" data-remove-url="{{route('shop.cart.remove_product', $product)}}">Удалить</a>
    </div>
    <div class="cart-list__price"><span class="price">{{$product->item_cost}}</span></div>
	<input type="hidden" name="product-id" value="{{ $product->offer->product->id }}">
	<input type="hidden" name="product-category" value="{{ $product->offer->product->firstCategory()->name }}"> 
</div>