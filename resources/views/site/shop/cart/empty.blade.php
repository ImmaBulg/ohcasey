@extends('site.layouts.app')

@section('title', 'Корзина пуста')

@section('content')

    <div class="inner">
        <div class="inner__cart">
            <h1 class="h1">Корзина пуста</h1>

            <p class="text">Выберите интересующий вас товар в каталоге.</p>

            <a href="{{route('shop.index')}}" class="btn">ПЕРЕЙТИ В КАТАЛОГ</a>
        </div>
    </div>
@endsection