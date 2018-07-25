@extends('site.layouts.app')

@section('title', '404 - страница не найдена')

@section('content')

    <style>
        
        .inner__error {
            width: 911px;
            margin: 0 auto
        }
        @media screen and (max-width: 991px) {
            .inner__error {
                width: 100%;
                padding: 0 33px
            }

            .inner__error .h1 {
                margin-bottom: 40px
            }
        }

        @media screen and (max-width: 680px) {
            .inner__error {
                padding:0 18px
            }

            .inner__error .h1 {
                font-size: 32px;
                margin-bottom: 20px
            }
        }
        
    </style>

    <div class="inner">
        <div class="inner__error">
            <h1 class="h1">404 - страница не найдена</h1>
            <p class="text">К сожалению мы не нашли страницу. Посмотрите наш каталог, и вы обязательно найдете то, что вам понравится!</p>
            <a href="{{route('shop.index')}}" class="btn">ПЕРЕЙТИ В КАТАЛОГ</a>
        </div>
    </div>
	
	<script>
	dataLayer.push({
		'event':'constructor',
			'eventCategory':'error', 
			'eventAction':'404', 
	});
	</script>

    
@endsection