@extends('admin.layout.master')
@section('content')

	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Категории</h1>
		</div>
	</div>

	<div class="col-lg-12">
		<div class="row">
			<a href="{{route('admin.category.create')}}" class="btn btn-success">Создать категорию</a>
			<hr/>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading clearfix">
					<i class="fa fa-table fa-fw"></i> Список категорий
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover table-condensed">
							<thead>
								<th>#</th>
								<th>Путь</th>
								<th>Порядок сортировки</th>
								<th class="text-center"><i class="fa fa-pencil"></i></th>
								<th class="text-center"><i class="fa fa-trash-o"></i></th>
								<th>Опубликовано</th>
							</thead>
							<tbody>
								@forelse($categories as $c)
									<tr>
										<td>{{$c->id}}</td>
										<td>{{$c->path}}</td>
										<td>{{$c->order}}</td>
										<td class="text-center" style="width: 70px;">
											<a href="{{route('admin.category.edit', $c->id)}}" class="btn btn-primary btn-sm">
												<i class="fa fa-pencil"></i>
											</a>
										</td>
										<td class="text-center" style="width: 70px;">
											<form action="{{route('admin.category.destroy', $c->id)}}" method="POST">
												{{ csrf_field() }}
												<input name="_method" type="hidden" value="DELETE">
												<button class="btn btn-danger btn-sm" type="submit">
													<i class="fa fa-trash-o"></i>
												</button>
											</form>
										</td>
										<td>{{$c->published_at ? $c->published_at->format('d-m-Y') : 'Нет'}}</td>
									</tr>
								@empty
									<p class="oh-info bg-info">Нет категорий</p>
								@endforelse
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
@endsection