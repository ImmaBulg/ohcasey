@extends('admin.layout.master')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h3>Создание категории</h3>
        </div>
    </div>

    <div class="row">
		<div class="col-lg-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					Создание категории
				</div>
				<div class="panel-body">
		    		@include('admin.category.form', [
		    			'action' => route('admin.category.store'),
		    			'method' => 'POST',
		    			'button' => 'Создать'
		    		])
		    	</div>
    		</div>
	    </div>
    </div>
@endsection