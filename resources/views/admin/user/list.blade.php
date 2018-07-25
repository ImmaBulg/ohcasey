@extends('admin.layout.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1>Пользователи</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">Список пользователей</div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <th>ID</th>
                                <th>Имя</th>
                                <th>Логин</th>
                                <th>Пароль</th>
                                <th>SuperAdmin</th>
                                <th>Роль</th>
                                <th class="text-center"><span class="fa fa-pencil"></span></th>
                                <th class="text-center"><span class="fa fa-trash"></span></th>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{$user->id}}</td>
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->login}}</td>
                                    <td>{{$user->password}}</td>
                                    <td>{{$user->superadmin}}</td>
                                    <td>{{$user->role}}</td>
                                    <th class="text-center">
                                        <a href="{{ route('admin.users.show', $user) }}"><span class="fa fa-pencil"></span></a>
                                    </th>
                                    <th class="text-center">
                                        <a href="{{ route('admin.users.delete', $user) }}"><span class="fa fa-trash"></span></a>
                                    </th>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel-footer">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-success">Добавить пользователя</a>
                </div>
            </div>
        </div>
    </div>
@endsection