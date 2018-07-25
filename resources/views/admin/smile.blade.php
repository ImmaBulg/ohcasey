@extends('admin.layout.master')
@section('content')
	<div id="toolbar">
		<div class="oh-toolbar clearfix">
			<button id="btn-upload" type="button" class="btn btn-default"><span class="fa fa-upload"></span> Загрузить</button>
			<button id="btn-select-all" type="button" class="btn btn-default"><span class="fa fa-check-square-o"></span> Выбрать все</button>
			<button id="btn-unselect-all" type="button" class="btn btn-default"><span class="fa fa-square-o"></span> Снять выделение</button>
			<button id="btn-edit" type="button" disabled class="btn btn-default"><span class="fa fa-edit"></span> Изменить</button>
			<form id="btn-remove-form" action="{{ url('admin/smile') }}" method="post">
				{{ csrf_field() }}
				{{ method_field('DELETE') }}
				<button id="btn-remove" type="submit" disabled class="btn btn-default"><span class="fa fa-remove fa-fw text-danger"></span> Удалить</button>
				<span class="selected"></span>
			</form>
		</div>

		<form action="{{ url('admin/smile') }}" method="get">
			<div class="oh-toolbar clearfix">
				<div class="btn-group" data-toggle="buttons">
					<label class="btn btn-default {{ in_array('-', request('cat', [])) ? 'active' : '' }}">
						<input name="cat[]" value="-" type="checkbox" autocomplete="off" {{ in_array('-', request('cat', [])) ? 'checked' : '' }}> Без категории
					</label>

					@foreach($categories as $cat)
						<label class="btn btn-default {{ in_array($cat->smile_group_name, request('cat', [])) ? 'active' : '' }}">
							<input name="cat[]" value="{{ $cat->smile_group_name }}" type="checkbox" autocomplete="off" {{ in_array($cat->smile_group_name, request('cat', [])) ? 'checked' : '' }}> {{ $cat->smile_group_name }}
						</label>
					@endforeach
				</div>
				<button id="btn-search" type="submit" class="btn btn-default"><span class="fa fa-search fa-fw text-danger"></span> Показать выбранные категории</button>
			</div>
		</form>
	</div>

	<div id="popup-upload" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<form action="{{ url('admin/smile') }}" method="post" id="popup-upload-form" enctype="multipart/form-data">
				{{ csrf_field() }}
				<span class="selected"></span>
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Новый фон</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label class="col-xs-12">Файлы:</label>
							<div class="btn btn-default btn-file">
								<span class="fa fa-upload"></span> Загрузить
								<input id="ctl-upload" multiple name="files[]" type="file">
							</div>
							<div id="ctl-upload-count"></div>
						</div>
						<div class="form-group">
							<label class="col-xs-12">Существующие категории:</label>
							<div class="oh-toolbar btn-toolbar">
								<div class="btn-group" data-toggle="buttons">
									@foreach($categories as $cat)
										<label class="btn btn-default {{ in_array($cat->smile_group_name, request('cat', [])) ? 'active' : '' }}">
											<input name="cat[]" value="{{ $cat->smile_group_name }}" type="checkbox" autocomplete="off" {{ in_array($cat->smile_group_name, request('cat', [])) ? 'checked' : '' }}> {{ $cat->smile_group_name }}
										</label>
									@endforeach
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-12">Или создать новую:</label>
							<input name="cat_name" type="text" class="form-control">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
						<button type="submit" class="btn btn-primary">Загрузить</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div id="popup-update" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<form action="{{ url('admin/smile') }}" method="post" id="popup-update-form" enctype="multipart/form-data">
				{{ csrf_field() }}
				{{ method_field('PATCH') }}
				<span class="selected"></span>
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Изменить категорию</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label class="col-xs-12">Существующие категории:</label>
							<div class="oh-toolbar btn-toolbar">
								<div class="btn-group" data-toggle="buttons">
									@foreach($categories as $cat)
										<label class="btn btn-default {{ in_array($cat->smile_group_name, request('cat', [])) ? 'active' : '' }}">
											<input name="cat[]" value="{{ $cat->smile_group_name }}" type="checkbox" autocomplete="off" {{ in_array($cat->smile_group_name, request('cat', [])) ? 'checked' : '' }}> {{ $cat->smile_group_name }}
										</label>
									@endforeach
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-12">Или создать новую:</label>
							<input name="cat_name" type="text" class="form-control">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
						<button type="submit" class="btn btn-primary">Обновить</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="clearfix oh-img-list">
		@forelse($smiles as $smile)
			<label class="oh-img oh-smile" data-toggle="popover" data-content="{{ implode(", ", $smile->smile_group ?: []) }}" data-trigger="hover" data-placement="top" data-delay="400">
				<input type="checkbox" autocomplete="off" data-name="{{ $smile->smile_name }}" data-group="{{ json_encode($smile->smile_group) }}">
				<span class="oh-img-body">
					<img src="{{ url('storage/sz/smile/60/'.$smile->smile_name) }}" />
				</span>
			</label>
		@empty
			<p class="oh-info bg-info">Ничего нет</p>
		@endforelse
	</div>
	{{ $smiles->appends(request()->all())->links() }}
@endsection
@section('scripts')
	<script type="text/javascript" src="{{ url('js/admin-list.js') }}"></script>
@endsection
