<ul class="breadcrumbs">
    <li class="breadcrumbs__item">
        <a class="breadcrumbs__link" href="{{url('')}}">Ohcasey</a>
    </li>
    @foreach($breadcrumbs as $b)
        <li class="breadcrumbs__item">
            @if(isset($b['href']))
                @if ($b['name'] !== 'Коллекции' && $b['href'] !== 'glitter')
                    @php
                        $url = '?';
                        foreach ($_GET as $name => $value) {
                            $url .= $name . '=' . $value . '&';
                        }
                        $url = substr($url, 0, -1);
                    @endphp
                    <a class="breadcrumbs__link" href="{{route('shop.slug', $b['href']) . $url}}">{{$b['name']}}</a>
                @else
                    <a class="breadcrumbs__link" href="{{route('shop.slug', $b['href'])}}">{{$b['name']}}</a>
                @endif
            @else
                <span class="breadcrumbs__text">{{$b['name']}}</span>
            @endif
        </li>
    @endforeach
</ul>