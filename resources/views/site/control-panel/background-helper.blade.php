<div class="left">
	<div class="helper-icon clearfix blue" data-hide="true"><span class="icon link blue">?</span></div>
	<div class="helper-content">
		<div class="up-pointer"></div>
		<p>В правом меню выберите фон чехла на свой вкус.</p>
		<p>Чтобы удалить фон, нажмите на крестик.</p>
		<p class="blue">Можно пропустить этот шаг, нажав кнопку “текст” или кнопку “заказать”.</p>
	</div>
	@if(!session('isAdminEdit', false))
		<div class="helper-content">
			@include('site.control-panel.share')
		</div>
	@endif
	<button class="helper-clear">ОЧИСТИТЬ МАКЕТ</button>
</div>

<div class="right">
	<div class="helper-icon clearfix blue pointer"><span class="icon link blue">&#x2715;</span></div>
	<div class="helper-content">
		<div class="up-pointer"></div>
		<p>Очистить фон</p>
	</div>
</div>

<div class="bottom">
	@include('site.control-panel._cost')
</div>

<div class="help">
	<div class="help-block help-bg-1">
		<div class="text-right">жми на категории выбери дизайн</div>
		<div class="text-right"><img class="help-img-t-r" width="40" src="{{ url('img/help-arrow-black.png') }}" /></div>
	</div>

	<div class="help-block help-bg-2">
		<div class="text-right">может еще текст? <img class="help-img-l-t" width="30" src="{{ url('img/help-arrow-black.png') }}" /></div>
	</div>
</div>
