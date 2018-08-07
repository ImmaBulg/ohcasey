@extends('admin.layout.master')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h3>Редактирование категории "{{ $category->name }}"</h3>
        </div>
    </div>

    <div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					Редактирование категории
				</div>
				<div class="panel-body">
		    		@include('admin.category.form', [
		    			'action' => route('admin.category.update', $category->id),
		    			'method' => 'PUT',
		    			'button' => 'Сохранить'
		    		])
		    	</div>
    		</div>
	    </div>
    </div>
@endsection