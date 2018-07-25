@extends('admin.layout.master')
@section('content')
	<div id="toolbar">
		<div class="oh-toolbar clearfix">
			<button id="btn-upload" type="button" class="btn btn-default"><span class="fa fa-upload"></span> Загрузить</button>
			<button id="btn-select-all" type="button" class="btn btn-default"><span class="fa fa-check-square-o"></span> Выбрать все</button>
			<button id="btn-unselect-all" type="button" class="btn btn-default"><span class="fa fa-square-o"></span> Снять выделение</button>
			<button id="btn-edit" type="button" disabled class="btn btn-default"><span class="fa fa-edit"></span> Изменить</button>
			<form id="btn-remove-form" action="{{ url('admin/bg') }}" method="post">
				{{ csrf_field() }}
				{{ method_field('DELETE') }}
				<button id="btn-remove" type="submit" disabled class="btn btn-default"><span class="fa fa-remove fa-fw text-danger"></span> Удалить</button>
				<span class="selected"></span>
			</form>
			<form id="btn-save-order-form" action="{{ url('admin/bgSaveOrder') }}" method="post">
				{{ csrf_field() }}
				{{ method_field('POST') }}
				<button disabled id="btn-save-order" type="submit" class="btn btn-default"><span class="fa fa-save"></span> Сохранить сортировку</button>
				<span class="selected"></span>
			</form>
		</div>

		<form action="{{ url('admin/bg') }}" method="get">
			<div class="oh-toolbar clearfix">
				<div class="btn-group" data-toggle="buttons">
					<label class="btn btn-default {{ in_array('-', request('cat', [])) ? 'active' : '' }}">
						<input name="cat[]" value="-" type="checkbox" autocomplete="off" {{ in_array('-', request('cat', [])) ? 'checked' : '' }}> Без категории
					</label>

					@foreach($categories as $category)
						<label class="btn btn-default {{ in_array($category->name, request('cat', [])) ? 'active' : '' }}">
							<input name="cat[]" value="{{ $category->name }}" type="checkbox" autocomplete="off" {{ in_array($category->name, request('cat', [])) ? 'checked' : '' }}> {{ $category->name }}
						</label>
					@endforeach
				</div>
				<button id="btn-search" type="submit" class="btn btn-default"><span class="fa fa-search fa-fw text-danger"></span> Показать выбранные категории</button>
			</div>
		</form>
	</div>

	<div id="popup-upload" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<form action="{{ url('admin/bg') }}" method="post" id="popup-upload-form" enctype="multipart/form-data">
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
									@foreach($categories as $category)
										<label class="btn btn-default {{ in_array($category->name, request('cat', [])) ? 'active' : '' }}">
											<input name="cat[]" value="{{ $category->name }}" type="checkbox" autocomplete="off" {{ in_array($category->name, request('cat', [])) ? 'checked' : '' }}> {{ $category->name }}
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
			<form action="{{ url('admin/bg') }}" method="post" id="popup-update-form" enctype="multipart/form-data">
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
									@foreach($categories as $category)
										<label class="btn btn-default {{ in_array($category->name, request('cat', [])) ? 'active' : '' }}">
											<input name="cat[]" value="{{ $category->name }}" type="checkbox" autocomplete="off" {{ in_array($category->name, request('cat', [])) ? 'checked' : '' }}> {{ $category->name }}
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
		@forelse($backgrounds as $background)
			<div class="bgCell">
				<div class="moveArrow">
					<i class="fa fa-arrow-left order-move-left"></i>
					<i class="fa fa-arrow-right order-move-right"></i>
				</div>
				<label class="oh-img oh-bg" data-toggle="popover" data-content="{{ implode(", ", ($background->backgroundGroups->count() ? $background->backgroundGroups->pluck('name')->toArray() : [])) }}" data-trigger="hover" data-placement="top" data-delay="400">
					<input type="checkbox" autocomplete="off" data-id="{{ $background->id }}" data-name="{{ $background->name }}" data-group="{{ json_encode($background->backgroundGroups->pluck('name')->toArray()) }}">
					<span class="oh-img-body">
						<img src="{{ url('storage/sz/bg/150/' . $background->name) }}" />
					</span>
				</label>
				<input hidden id="order-value" class="notEditOrder" data-id="{{ $background->id }}" data-order="{{$background->order}}">
			</div>
		@empty
			<p class="oh-info bg-info">Ничего нет</p>
		@endforelse
	</div>
	{{ $backgrounds->appends(request()->all())->links() }}
@endsection
@section('scripts')
	<script type="text/javascript" src="{{ url('js/admin-list.js') }}"></script>
@endsection
