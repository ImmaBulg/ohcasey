<ul class="breadcrumbs">
    <li class="breadcrumbs__item">
        <a class="breadcrumbs__link" href="{{url('')}}">Ohcasey</a>
    </li>
    @foreach($breadcrumbs as $b)
        <li class="breadcrumbs__item">
            @if(isset($b['href']))
                <a class="breadcrumbs__link" href="{{route('shop.slug', $b['href'])}}">{{$b['name']}}</a>
            @else
                <span class="breadcrumbs__text">{{$b['name']}}</span>
            @endif
        </li>
    @endforeach
</ul>