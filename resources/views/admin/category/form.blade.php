<form action="{{$action}}" method="POST" enctype="multipart/form-data">
	{{ csrf_field() }}
	@if($method == 'PUT')
		<input name="_method" type="hidden" value="PUT">
	@endif
	<div class="form-group{{$errors->has('name') ? ' has-error' : ''}}">
		<label for="name">Название *</label>
		<input class="form-control" name="name" type="text" value="{{ $category->name or old('name') }}" placeholder="Название категории">
	</div>
	<div class="form-group{{$errors->has('slug') ? ' has-error' : ''}}">
		<label for="slug">URI *</label>
		<input class="form-control" name="slug" type="text" value="{{ $category->slug or old('slug') }}" placeholder="URI">
	</div>
	<div class="form-group{{$errors->has('h1') ? ' has-error' : ''}}">
		<label for="keywords">H1</label>
		<input class="form-control" name="h1" type="text" value="{{$category->h1 or old('h1')}}" placeholder="Headline">
	</div>
	<div class="form-group{{$errors->has('h2') ? ' has-error' : ''}}">
		<label for="keywords">Подзаголовок баннера</label>
		<input class="form-control" name="h2" type="text" value="{{$category->h2 or old('h2')}}" placeholder="Headline">
	</div>
	<div class="form-group{{$errors->has('title') ? ' has-error' : ''}}">
		<label for="title">Meta title *</label>
		<input class="form-control" name="title" type="text" value="{{$category->title or old('title')}}" placeholder="Meta title">
	</div>
	<div class="form-group{{$errors->has('keywords') ? ' has-error' : ''}}">
		<label for="keywords">Meta keywords</label>
		<input class="form-control" name="keywords" type="text" value="{{$category->keywords or old('keywords')}}" placeholder="Meta keywords">
	</div>
	<div class="form-group{{$errors->has('description') ? ' has-error' : ''}}">
		<label for="description">Описание</label>
		<textarea class="form-control" name="description" rows="3" placeholder="Описание">{{$category->description or old('description')}}</textarea>
	</div>
	<div class="form-group{{$errors->has('text_top') ? ' has-error' : ''}}">
		<label for="text_top">Верхний текст</label>
		<textarea class="form-control" name="text_top" rows="3" placeholder="Верхний текст">{{$category->text_top or old('text_top')}}</textarea>
	</div>
	<div class="form-group{{$errors->has('text_bottom') ? ' has-error' : ''}}">
		<label for="text_bottom">Нижний текст</label>
		<textarea class="form-control" name="text_bottom" rows="3" placeholder="Нижний текст">{{$category->text_bottom or old('text_bottom')}}</textarea>
	</div>
	<div class="form-group{{$errors->has('parent') ? ' has-error' : ''}}">
		<label for="parent">Родительская категория</label>
		<select class="form-control" name="parent" value="{{$category->parent or '0'}}">
			<option value="0">---</option>
			@foreach(\App\Models\Shop\Category::orderBy('order')->get() as $p)
				<option
					value="{{$p->id}}"
					@if(!empty($category) && $p->id == $category->id) disabled="disabled" @endif
					@if(!empty($category) && $p->id == $category->parent) selected="selected" @endif
				>
					{{$p->path}}
				</option>
			@endforeach
		</select>
	</div>
	<div class="form-group{{$errors->has('order') ? ' has-error' : ''}}">
		<label for="order">Порядок сортировки</label>
		<input class="form-control" name="order" type="text" value="{{$category->order or '0'}}" placeholder="Порядок сортировки">
	</div>
	<div class="form-group">
		@if(!empty($category) && $category->image)
			<img src="{{$category->image}}" alt="Изображение категории" width="100px" height="100px">
		@endif
	</div>
	<div class="form-group">
		<label for="image">Изображение</label>
		<input type="file" name="image">
		<p class="help-block">Формат jpeg, png, gif. Максимальный размер 10Мб</p>
	</div>
	<div class="form-group">
		@if(!empty($category) && $category->banner_image)
			<img src="{{$category->banner_image}}" alt="Изображение категории" width="100px">
		@endif
	</div>
	<div class="form-group">
		<label for="image">Баннер</label>
		<input type="file" name="banner_image">
		<p class="help-block">Формат jpeg, png, gif. Максимальный размер 10Мб</p>
	</div>
	<div class="form-group">
		<div class="checkbox">
			<label>
				<input type="checkbox" name="catalog_display_type" @if(!empty($category) && $category->catalog_display_type) checked @endif"> Показывать баннеры категорий
			</label>
		</div>
	</div>
	<div class="form-group{{$errors->has('large_photos') ? ' has-error' : ''}}">
		<div class="checkbox">
            <label>
                <input type="checkbox" name="large_photos" @if(!empty($category) && $category->large_photos || old('large_photos')) checked @endif"> Выводить увеличенные фото
            </label>
        </div>
	</div>
	<div class="form-group">
		<div class="checkbox">
			<label>
				<input type="checkbox" name="display_price" @if(!empty($category) && $category->display_price) checked @endif"> Показывать цену
			</label>
		</div>
	</div>
	<div class="form-group">
		<div class="checkbox">
            <label>
                <input type="checkbox" name="active" @if(!empty($category) && $category->active) checked @endif">
                Активна
            </label>
        </div>
	</div>
	<button type="submit" class="btn btn-primary">{{$button}}</button>
	<a href="{{route('admin.category.index')}}" class="btn btn-default">Отмена</a>
</form>

@section('scripts')
<script type="text/javascript">
    $(function () {
    	console.log($('#datetimepicker-published-at'));
        $('#datetimepicker-published-at').datepicker({
        	format: "dd-mm-yyyy"
        });
    });
</script>
@endsection