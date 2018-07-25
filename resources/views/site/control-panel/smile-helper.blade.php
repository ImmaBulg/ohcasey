<div class="helper left">
	<div class="helper-icon clearfix blue" data-hide="true"><span class="icon link blue">?</span></div>
	<div class="helper-content">
		<div class="up-pointer"></div>
		<p>В правом меню выберите смайлы или иконки, они отобразятся на чехле.</p>
		<p>Растягивайте/уменьшайте их, потянув за уголок. Перемещайте их по чехлу на свое усмотрение.</p>
		<div class="helper-description">
			<div class="icon icon-cursor"></div>
			<div class="title">Клик - выбрать объект</div>
		</div>
		<div class="helper-description">
			<div class="icon icon-rotate"></div>
			<div class="title">Поворачивайте</div>
		</div><div class="helper-description">
			<div class="icon icon-resize"></div>
			<div class="title">Изменяйте размер</div>
		</div>
		<div class="helper-description">
			<div class="icon icon-move"></div>
			<div class="title">Перемещайте объект</div>
		</div>
		<div class="helper-description">
			<div class="icon icon-remove"></div>
			<div class="title">Удалить</div>
		</div>
	</div>
	@if(!session('isAdminEdit', false))
		<div class="helper-content">
			@include('site.control-panel.share')
		</div>
	@endif
	<button class="helper-clear">ОЧИСТИТЬ МАКЕТ</button>
</div>

<div class="bottom">
	@include('site.control-panel._cost')
</div>

<div class="help">
	<div class="help-block help-smile-1">
		<div class="text-right">добавить смайлы?</div>
		<div class="text-right"><img width="40" src="{{ url('img/help-arrow-black.png') }}" /></div>
	</div>
</div>
