@foreach ($backgrounds as $background)
	<img src="{{ url('storage/sz/bg/150/' . $background->name) }}" class="bg list-item link" data-bg="{{ $background->name }}">
@endforeach
