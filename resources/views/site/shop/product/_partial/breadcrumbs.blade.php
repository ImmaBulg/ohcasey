<ul class="breadcrumbs">
    <li class="breadcrumbs__item">
        <a class="breadcrumbs__link" href="{{route('shop.slug', 'collections')}}">Коллекции</a>
    </li>
    @if(!empty($c = $product->firstCollection()))
        <li class="breadcrumbs__item">
            <a class="breadcrumbs__link" href="{{route('shop.slug', $c->url)}}">{{$c->name}}</a>
        </li>
    @endif
    <li class="breadcrumbs__item">
        <span class="breadcrumbs__text">{{$product->name}}</span>
    </li>
</ul>