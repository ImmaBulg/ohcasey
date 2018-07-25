<div class="left">
	<div class="helper-icon clearfix blue" data-hide="true"><span class="icon link blue">?</span></div>
	<div class="helper-content">
		<div class="up-pointer"></div>
		<p>В правом меню отображены доступные для заказа чехлы.</p>
		<p>Чтобы выбрать чехол, просто кликните на необходимый Вам.</p>
		<p class="blue">Для прозрачных чехлов важно выбрать цвет самого устройства, чтобы не ошибиться с макетом.</p>
	</div>
	@if(!session('isAdminEdit', false))
		<div class="helper-content">
			@include('site.control-panel.share')
		</div>
	@endif
</div>

<div class="bottom control-panel-case-color">
	@foreach($colors as $i => $c)
		<div class="item link list-item" style="background-color: {{ $c }}" data-color="{{ $c }}" data-color-id="{{ $i }}"></div>
	@endforeach
</div>

<div class="help">
	<div class="help-block help-case-1">
		<div class="text-right">на каком чехле изготовить?</div>
		<div class="text-right"><img width="40" src="{{ url('img/help-arrow-black.png') }}" /></div>
	</div>

	<div class="help-block help-case-2">
		<div class="text-center"><img class="help-img-r-t" width="40" src="{{ url('img/help-arrow-black.png') }}" /></div>
		<div class="text-right">выбери цвет телефона</div>
	</div>
</div>
