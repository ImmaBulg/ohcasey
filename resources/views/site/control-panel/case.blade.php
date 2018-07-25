@if (empty($device))
	<h1 class="center"><a href="#/device/">Сначала выберите устройство</a></h1>
@else
	@extends('site.control-panel._base')
	@section('cp-title', 'Выберите чехол')
	@section('cp-content')
		<div class="control-panel-case">
			<?php
				$item = \App\Models\Item::find(\App\Ohcasey\Ohcasey::SKU_DEVICE);
			?>
			<?php
				$sort_cases = array();
				foreach($cases as $case){
					if($case['name'] == 'silicone') $sort_cases[0] = $case;
					if($case['name'] == 'plastic') $sort_cases[1] = $case;
					if($case['name'] == 'softtouch') $sort_cases[2] = $case;
					if($case['name'] == 'glitter') $sort_cases[3] = $case;
					if($case['name'] == 'glitter_1') $sort_cases[4] = $case;
					if($case['name'] == 'glitter_2') $sort_cases[5] = $case;
					if($case['name'] == 'glitter_3') $sort_cases[6] = $case;
					if($case['name'] == 'glitter_4') $sort_cases[7] = $case;
				}
				ksort($sort_cases);
				$cases = $sort_cases;
			?>
			@foreach ($cases as $case)
				<div class="case list-item" data-case="{{$case['name']}}" data-color="{{ implode(',', $case['colors']) }}" data-cost="{{ $item->item_cost }}">
					<div class="icon"><img class="icon" src="{{ url('storage/device/'.$device.'/case/'.$case['name'].'.png') }}"></div>
					<div class="description">
						@if ($case['name'] == 'silicone')
							<div class="header">Силикон</div>
							<div class="description-1">Полностью прозрачный</div>
							<div class="description-2">Оптимальная толщина. Отличная защита устройства при падениях. Не скользит в руке</div>
						@elseif ($case['name'] == 'plastic')
							<div class="header">Пластик</div>
							<div class="description-1">Матовый полупрозрачный</div>
							<div class="description-2">Тонкий, но прочный, бархатистый на ощупь. Не скользит в руке</div>
						@elseif ($case['name'] == 'softtouch')
							<div class="header">Пластик</div>
							<div class="description-1">Матовый чёрный</div>
							<div class="description-2">Бархатистый, приятный на ощупь чехол. Не скользит в руке</div>
						@elseif ($case['name'] == 'glitter')
							<div class="header">Жидкий глиттер</div>
							<div class="description-1">Цвет розово-голубой</div>
							<div class="description-2">Блёстки переливаются. Пластиковая основа. Силиконовые бортики</div>
						@elseif ($case['name'] == 'glitter_1')
							<div class="header">Жидкий глиттер</div>
							<div class="description-1">Цвет розово-золотый</div>
							<div class="description-2">Блёстки переливаются. Пластиковая основа. Силиконовые бортики</div>
						@elseif ($case['name'] == 'glitter_2')
							<div class="header">Жидкий глиттер</div>
							<div class="description-1">Цвет перламутровый</div>
							<div class="description-2">Блёстки переливаются. Пластиковая основа. Силиконовые бортики</div>
						@elseif ($case['name'] == 'glitter_3')
							<div class="header">Жидкий глиттер</div>
							<div class="description-1">Цвет фиолетовый</div>
							<div class="description-2">Блёстки переливаются. Пластиковая основа. Силиконовые бортики</div>
						@elseif ($case['name'] == 'glitter_4')
							<div class="header">Жидкий глиттер</div>
							<div class="description-1">Цвет голубой</div>
							<div class="description-2">Блёстки переливаются. Пластиковая основа. Силиконовые бортики</div>
						@else
							<div class="header">Классный чехол</div>
						@endif
					</div>
				</div>
			@endforeach
		</div>
	@endsection
@endif
