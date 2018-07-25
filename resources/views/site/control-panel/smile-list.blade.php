@foreach($smiles as $smile)
	<div class="smile list-item link" data-smile="{{ $smile->smile_name }}">
		<img src="{{ url('storage/sz/smile/60/'.$smile->smile_name) }}">
	</div>
@endforeach
