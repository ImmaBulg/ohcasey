<html>
<head>
	<title>OHCASEY administration</title>
	<script src="{{ url('js/jquery.js') }}"></script>
	<script src="{{ url('js/bootstrap.js') }}"></script>
	<link href="{{ url('css/bootstrap.css') }}" rel="stylesheet">
	<link href="{{ url('css/font-awesome.css') }}" rel="stylesheet">
	<style type="text/css">
		body {
			width: 100%;
			height: 100%;
			background: #e8e8e8;
		}
		body > div {
			position:absolute; height:100%; width:100%;
			display: table;
		}
		body > div > div {
			display: table-cell;
			vertical-align: middle;
			text-align:center;
		}
	</style>
</head>
<body>
	<div><div>
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
					<div class="panel-heading">Панель администрирования</div>
					<div class="panel-body">
						<form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
							{!! csrf_field() !!}

							<div class="form-group{{ $errors->has('login') ? ' has-error' : '' }}">
								<label class="col-md-4 control-label">Логин</label>

								<div class="col-md-6">
									<input type="text" class="form-control" name="login" value="{{ old('login') }}">

									@if ($errors->has('login'))
										<span class="help-block">
											<strong>{{ $errors->first('login') }}</strong>
										</span>
									@endif
								</div>
							</div>

							<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
								<label class="col-md-4 control-label">Пароль</label>

								<div class="col-md-6">
									<input type="password" class="form-control" name="password">

									@if ($errors->has('password'))
										<span class="help-block">
											<strong>{{ $errors->first('password') }}</strong>
										</span>
									@endif
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-6 col-md-offset-4">
									<div class="checkbox">
										<label>
											<input type="checkbox" name="remember"> Запомнить
										</label>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-6 col-md-offset-4">
									<button type="submit" class="btn btn-primary">
										<i class="fa fa-btn fa-sign-in"></i> Войти
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div></div>
</body>
</html>
