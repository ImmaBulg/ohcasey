<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ohcasey - администратор</title>

    <script type="text/javascript">
        var BASE_URL = '{{ url('admin') }}';
        var LAST_ORDER_ID = {{ \App\Models\Order::where('order_ts', '>', '2017-12-22')->max('order_id') ?: 0 }};
    </script>

    <!-- Bootstrap Core CSS -->
    <link href="{{ url('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ url('css/bootstrap.min.css') }}" rel="stylesheet">


    <!-- Font awesome -->
    <link href="{{ url('css/font-awesome.css') }}" rel="stylesheet">

    <!-- Select2 -->
    <link href="{{ url('css/select2.css') }}" rel="stylesheet">

    <!-- Datepicker -->
    <link href="{{ url('css/bootstrap-datepicker3.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ url('css/admin.css') }}" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    @yield('styles')
</head>

<body>

<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="oh-brand" href="{{ url('/') }}">
                <img height="40" src="{{ url('img/logo.png') }}"> - администратор
            </a>
        </div>
        <!-- /.navbar-header -->

        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <ul class="nav" id="side-menu">
                    @if (\Auth::user()->role == 'admin')
                        <li>
                            <a href="{{ route('admin.dashboards') }}"><i class="fa fa-bar-chart fa-fw"></i> Dashboard</a>
                        </li>
                    @endif

                    <li>
                        <a href="#" onclick="return false;"><i class="fa fa-th-list"></i> Продажи<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level collapse">
                            <li>
                                <a href="{{ url('admin') }}"><i class="fa fa-dashboard fa-fw"></i> Заказы</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.payment.payment_list') }}"><i class="fa fa-money fa-fw"></i> Оплаты</a>
                            </li>
                            <li>
                                <a href="{{ url('admin/cart') }}"><i class="fa fa-shopping-cart fa-fw"></i> Корзины</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.order_statuses.index') }}"><i class="fa fa-font fa-code-fork"></i> Статусы заказов</a>
                            </li>
                            <li>
                                <a href="{{ url('admin/promotion') }}"><i class="fa fa-font fa-percent fa-fw"></i> Промокоды</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.sms_templates.index') }}"><i class="fa fa-font fa-send"></i>СМС</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="#" onclick="return false;"><i class="fa fa-th-list"></i> Магазин<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level collapse">
                            <li>
                                <a href="{{ route('admin.category.index') }}"><i class="fa fa-th-list"></i> Категории</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.ecommerce.tag.index') }}"><i class="fa fa-th-list"></i> Теги</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.ecommerce.setting.index') }}"><i class="fa fa-th-list"></i> Настройки</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.ecommerce.product.index') }}"><i class="fa fa-th-list"></i> Товары</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.ecommerce.option.index') }}"><i class="fa fa-th-list"></i> Опции</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.ecommerce.option_value.index') }}"><i class="fa fa-th-list"></i> Значения опций</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.ecommerce.generator.index') }}"><i class="fa fa-th-list"></i> Генерация предложений</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.ecommerce.option_group.index') }}"><i class="fa fa-th-list"></i> Типы товаров (SKU)</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.menu_links.index') }}"><i class="fa fa-th-list"></i> Меню</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.ecommerce.page.index') }}"><i class="fa fa-th-list"></i> Страницы</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="#" onclick="return false;"><i class="fa fa-th-list"></i> Конструктор<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level collapse">
                            <li>
                                <a href="{{ url('admin/bg') }}"><i class="fa fa-picture-o fa-fw"></i> Фоны</a>
                            </li>
                            <li>
                                <a href="{{ url('admin/bgstat') }}"><i class="fa fa-pie-chart fa-fw"></i> Фоны - статистика</a>
                            </li>
                            <li>
                                <a href="{{ url('admin/smile') }}"><i class="fa fa-smile-o fa-fw"></i> Смайлы</a>
                            </li>
                            <li>
                                <a href="{{ url('admin/font') }}"><i class="fa fa-font fa-fw"></i> Шрифты</a>
                            </li>
                        </ul>
                    </li>

                    @if(\Auth::user()->superadmin)
                        <li>
                            <a href="{{ route('admin.economic.index') }}"><i class="fa fa-th-list"></i> Экономика<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse">
                                <li>
                                    <a href="{{ route('admin.economic.transaction_category.index') }}">Типы статей</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.economic.transaction_type.index') }}">Статьи</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.economic.transaction.index') }}">Транзакции</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.economic.transaction.report') }}">Движение денежных средств</a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    <li>
						<a href="{{ route('admin.cdekForm') }}">Доставки</a>
					</li>
                    @if (\Auth::user()->role == 'admin')
                        <li>
                            <a href="{{ route('admin.users') }}">Пользователи</a>
                        </li>
                    @endif
                    <li>
                        <a href="{{ url('logout') }}"><i class="fa fa-sign-out fa-fw"></i> Выйти</a>
                    </li>
                </ul>
            </div>
            <!-- /.sidebar-collapse -->
        </div>
        <!-- /.navbar-static-side -->
    </nav>

    <div id="page-wrapper">
        @if (session('success'))
            <div style="height:20px;"></div>
            <div class="alert alert-success">
                <ul>
                    @foreach (session('success') as $msg)
                        <li> {{ $msg }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if ($errors->count())
            <div style="height:20px;"></div>
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </div>
    <!-- /#page-wrapper -->

    <a href="{{ url('admin') }}" id="admin-new-order" class="bg-danger hidden"></a>
    <!-- /#admin-new-order -->

</div>
<!-- /#wrapper -->

<!-- jQuery -->
<script src="{{ url('js/jquery.js') }}"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

<!-- Bootstrap Core JavaScript -->
<script src="{{ url('js/bootstrap.js') }}"></script>
<script src="{{ url('js/bootstrap-confirmation.js') }}"></script>

<!-- Custom Theme JavaScript -->
<script src="{{ url('js/admin.js') }}"></script>

<!-- Sticky -->
<script src="{{ url('js/jquery.sticky.js') }}"></script>

<!-- Select2 -->
<script src="{{ url('js/select2.js') }}"></script>
<script src="{{ url('js/select2.ru.js') }}"></script>

<!-- Datepicker -->
<script src="{{ url('js/bootstrap-datepicker.js') }}"></script>
<script src="{{ url('js/bootstrap-datepicker.ru.min.js') }}"></script>

@yield('scripts')

<script type="text/javascript">
    $( document ).ajaxError(function( event, jqxhr, settings, thrownError ) {
        if (jqxhr.readyState == 4) {
            alert(jqxhr.responseJSON && Array.isArray(jqxhr.responseJSON) ? jqxhr.responseJSON.join("\n") : jqxhr.statusText);
        } else {
            console.log('Ajax failure:');
            console.log(jqxhr);
        }
    });
</script>
</body>

</html>
