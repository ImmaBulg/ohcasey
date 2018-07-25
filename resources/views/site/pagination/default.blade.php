<?php $link_limit = 11; ?>

@if ($paginator->lastPage() > 1)
<ul class="pager-nav">
    @if(($paginator->currentPage() != 1))
        <li class="pager-nav__item">
            <a class="pager-nav__link pager-nav__link--arrow" href="{{ $paginator->url($paginator->currentPage()-1) }}">&lt;</a>
        </li>
    @endif
    @for ($i = 1; $i <= $paginator->lastPage(); $i++)
        <?php
            $half_total_links = floor($link_limit / 2);
            $from = $paginator->currentPage() - $half_total_links;
            $to = $paginator->currentPage() + $half_total_links;
            if($paginator->currentPage() < $half_total_links) {
               $to += $half_total_links - $paginator->currentPage();
            }
            if($paginator->lastPage() - $paginator->currentPage() < $half_total_links) {
                $from -= $half_total_links - ($paginator->lastPage() - $paginator->currentPage()) - 1;
            }
        ?>
        @if($from < $i && $i < $to)
            <li class="pager-nav__item{{ ($paginator->currentPage() == $i) ? ' is-active' : '' }}">
                <a class="pager-nav__link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
            </li>
        @endif
    @endfor
    @if($paginator->currentPage() != $paginator->lastPage())
        <li class="pager-nav__item">
            <a class="pager-nav__link pager-nav__link--arrow" href="{{ $paginator->url($paginator->currentPage()+1) }}" >&gt;</a>
        </li>
    @endif
</ul>
@endif