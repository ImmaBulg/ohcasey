@extends('admin.layout.master')
@section('content')
	<div id="toolbar">
		<div class="oh-toolbar clearfix">
			<button id="btn-upload" type="button" class="btn btn-default"><span class="fa fa-upload"></span> Загрузить</button>
			<button id="btn-edit" type="button" disabled class="btn btn-default"><span class="fa fa-edit"></span> Изменить</button>
			<form id="btn-remove-form" action="{{ url('admin/font') }}" method="post">
				{{ csrf_field() }}
				{{ method_field('DELETE') }}
				<button id="btn-remove" type="submit" disabled class="btn btn-default"><span class="fa fa-remove fa-fw text-danger"></span> Удалить</button>
				<span class="selected"></span>
			</form>
		</div>
	</div>

	<div id="popup-upload" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<form action="{{ url('admin/font') }}" method="post" id="popup-upload-form" enctype="multipart/form-data">
				{{ csrf_field() }}
				<span class="selected"></span>
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Новый шрифт</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label class="col-xs-12">Файлы:</label>
							<div class="btn btn-default btn-file">
								<span class="fa fa-upload"></span> Загрузить
								<input id="ctl-upload" name="file" type="file">
							</div>
							{{--<div id="ctl-upload-count"></div>--}}
						</div>
						<div class="form-group">
							<label class="col-xs-12">Название шрифта</label>
							<input name="font_caption" type="text" class="form-control">
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
			<form action="{{ url('admin/font') }}" method="post" id="popup-update-form" enctype="multipart/form-data">
				{{ csrf_field() }}
				{{ method_field('PATCH') }}
				<span class="selected"></span>
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Изменить название</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label class="col-xs-12">Название шрифта:</label>
							<input name="font_caption" type="text" class="form-control">
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
		@forelse($fonts as $font)
			<label class="oh-img oh-font">
				<input type="radio" name="font" autocomplete="off" data-name="{{ $font->font_name }}" data-caption="{{ $font->font_caption }}">
				<span class="oh-img-body">
					<img src="{{ action('OhcaseyController@fontToImage', ['font' => $font->font_name, 'text' => $font->font_caption, 'color' => '#555']) }}" />
				</span>
			</label>
		@empty
			<p class="oh-info bg-info">Ничего нет</p>
		@endforelse
	</div>
@endsection
@section('scripts')
	<script type="text/javascript" src="{{ url('js/admin-font.js') }}"></script>
@endsection
