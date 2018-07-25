<div class="helper left">
	<div class="helper-icon clearfix blue" data-hide="true"><span class="icon link blue">?</span></div>
	<div class="helper-content">
		<div class="up-pointer"></div>
		<p>Нажмите в правом боковом меню на устройство, для которого хотите создать чехол.</p>
	</div>
	@if(!session('isAdminEdit', false))
		<div class="helper-content">
			@include('site.control-panel.share')
		</div>
	@endif
</div>

<div class="bottom">
	@include('site.control-panel._cost')
</div>

<div class="help">
	<div class="help-block help-device-1">
		<div class="text-right">выбери свой телефон</div>
		<div class="text-right"><img width="40" src="{{ url('img/help-arrow-black.png') }}" /></div>
	</div>
</div>
