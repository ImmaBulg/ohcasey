<ul class="main-nav">
@foreach ($menu as $first)
    @if (!$first->information_type)
        <li class="main-nav__item js-nav-item menu-item-display-type @if ($first->display_type == 1) only-mobile @elseif ($first->display_type == 2) only-desktop @endif">
            @if ($first->url)
                <a href="{{$first->url}}" class="main-nav__link {{$first->children->count() ? 'main-nav__link--has-drop js-nav-link' : 'menu-nav_link--without-drop'}}">
                    {{$first->name}}
                </a>
            @else
                <span class="main-nav__link {{$first->children->count() ? 'main-nav__link--has-drop js-nav-link' : 'menu-nav_link--without-drop'}}">
                    {{$first->name}}
                </span>
            @endif

            @if ($first->children->count())

                <?php
                    // если у второго уровня нет детей - отображать второй уровень как 3ий
                    $someSecondHasThird = (bool) $first->children->first(function ($index, $second) {
                        return ($second->children->count() > 0);
                    }, false);
                ?>
                <div class="drop-nav js-nav-drop">
                    @if (!$someSecondHasThird)
                        <ul class="drop-nav__list">
                            @foreach ($first->children as $second)
                                <li class="drop-nav__item menu-item-display-type @if ($second->display_type == 1) only-mobile @elseif ($second->display_type == 2) only-desktop @endif" data-menu-id="menu-{{$second->id}}">
                                    @if ($second->url)
                                        <a href="{{$second->url}}" class="{{$someSecondHasThird ? 'drop-nav__section-title' : 'drop-nav__link'}}">{{$second->name}}</a>
                                    @else
                                        <span class="{{$someSecondHasThird ? 'drop-nav__section-title' : 'drop-nav__link'}}">{{$second->name}}</span>
                                    @endif
                                </li>
                                @foreach ($second->children as $third)
                                    <li class="drop-nav__item menu-item-display-type @if ($third->display_type == 1) only-mobile @elseif ($third->display_type == 2) only-desktop @endif" data-parent-id="menu-{{$third->parent_id}}">
                                        @if ($third->url)
                                            <a href="{{$third->url}}" class="drop-nav__link">{{$third->name}}</a>
                                        @else
                                            <span class="drop-nav__link">{{$third->name}}</span>
                                        @endif
                                    </li>
                                @endforeach
                            @endforeach
                        </ul>
                    @else
                        @foreach ($first->children as $second)
                            <ul class="drop-nav__list @if ($second->display_type == 1) only-mobile @elseif ($second->display_type == 2) only-desktop @endif">
                                    <li class="drop-nav__item menu-item-display-type @if ($second->display_type == 1) only-mobile @elseif ($second->display_type == 2) only-desktop @endif" data-menu-id="menu-{{$second->id}}">
                                        @if ($second->url)
                                            <a href="{{$second->url}}" class="{{$someSecondHasThird ? 'drop-nav__section-title' : 'drop-nav__link'}}">{{$second->name}}</a>
                                        @else
                                            <span class="{{$someSecondHasThird ? 'drop-nav__section-title' : 'drop-nav__link'}}">{{$second->name}}</span>
                                        @endif
                                    </li>
                                    @foreach ($second->children as $third)
                                        <li class="drop-nav__item menu-item-display-type @if ($third->display_type == 1) only-mobile @elseif ($third->display_type == 2) only-desktop @endif" data-parent-id="menu-{{$third->parent_id}}">
                                            @if ($third->url)
                                                <a href="{{$third->url}}" class="drop-nav__link">{{$third->name}}</a>
                                            @else
                                                <span class="drop-nav__link">{{$third->name}}</span>
                                            @endif
                                        </li>
                                    @endforeach
                            </ul>
                        @endforeach
                    @endif
                </div>
            @endif
        </li>
    @endif
@endforeach

        <li class="main-nav__item js-nav-item space maxwidth991">
        </li>

@foreach ($menu as $first)
    @if ($first->information_type)
        <li class="main-nav__item js-nav-item menu-item-display-type @if ($first->display_type == 1) only-mobile @elseif ($first->display_type == 2) only-desktop @endif">
            <a class="main-nav__link" href="{{$first->url}}">{{$first->name}}</a>
        </li>
    @endif
@endforeach
</ul>