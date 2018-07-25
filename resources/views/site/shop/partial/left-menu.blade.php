<div class="col-lg-2 col-md-2 hidden-sm hidden-xs">
    @if (count($children))
        <ul class="left-nav">
            @foreach ($children as $child)
                <li class="left-nav__item{{$category->slug == $child->slug ? ' is-active' : ''}}">
                    <a class="left-nav__link" href="{{route('shop.slug', $child->url)}}" style="{{ count($child->selfChildren) ? 'display: inline-block;' : '' }}">{{$child->name}}</a>
                    @if(count($child->selfChildren))
                        <span class="collapseBtn" data-toggle="collapse" data-target="{{ '#collapseExample' . $child->id }}" style="cursor: pointer; display: inline;">
                                            <i class="fa fa-plus"></i>
                        </span>
                    @endif
                    @if(count($child->selfChildren))
                        <div class="collapse" id="{{ 'collapseExample' . $child->id }}">
                            <ul style="padding: 10px;">
                                @foreach ($child->selfChildren()->get() as $ch)
                                    <li class="left-nav__item{{$category->slug == $ch->slug ? ' is-active' : ''}}">
                                        <a class="left-nav__link" href="{{route('shop.slug', $child->url . '/' . $ch->slug)}}">{{$ch->name}}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
</div>