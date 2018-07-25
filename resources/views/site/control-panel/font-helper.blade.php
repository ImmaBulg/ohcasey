<div class="left helper-font" xmlns="http://www.w3.org/1999/html">
	<div class="helper-icon clearfix blue" data-hide="true"><span class="icon link blue">?</span></div>
	<div class="helper-content">
		<div class="up-pointer"></div>
		<p>Вы можете написать текст как вдоль чехла, так и поперек.</p>
		<p>Впишите ваш текст  после чего выберите шрифт в правом меню (не все шрифты подходят для кириллицы) </p>
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

<div class="right helper-font">
	<div class="helper-icon clearfix blue" data-hide="true"><span class="icon link blue">?</span></div>
	<div class="helper-content">
		<div class="up-pointer"></div>
		<p>Нажмите на шрифт справа, чтобы добавить текст или на кнопку:</p>
		<button type="button" class="oh-button" id="font-add">Добавить еще текст</button>
		<p>Выберите объект, чтобы изменить текст, размер, цвет или шрифт.</p>
		<div class="helper-description font-text" style="display: none">
			<label for="font-text"><strong>Введите свой текст:</strong></label>
			<input id="font-text" type="text">
		</div>
		<div class="helper-description font-color" style="display: none">
			<label for="font-text">Измените цвет</label>
			<div class="center">
				@foreach($colors as $c)
					<div class="color link" style="background-color: {{ $c }}" data-color="{{ $c }}"></div>
				@endforeach
			</div>
		</div>
	</div>
</div>

<div class="bottom">
	@include('site.control-panel._cost')
</div>


<div class="help">
	<div class="help-block help-font-1">
		<div class="text-right">хочешь написать текст?</div>
		<div class="text-right"><img class="help-img-t-r" width="40" src="{{ url('img/help-arrow-black.png') }}" /></div>
	</div>

	<div class="help-block help-font-2">
		<div class="text-right">может еще смайлы? <img class="help-img-l-t" width="30" src="{{ url('img/help-arrow-black.png') }}" /></div>
	</div>
</div>
