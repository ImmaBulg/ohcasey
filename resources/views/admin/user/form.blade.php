@extends('admin.layout.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1>Пользователи</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-7">
            <form action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST">
                <div class="panel panel-default">
                    <div class="panel-body">
                            <div class="form-group">
                                <label for="name">Имя</label>
                                <input type="text" class="form-control string" name="name" value="{{ isset($user) ? $user->name : old('name') }}">
                            </div>
                            <div class="form-group">
                                <label for="login">Логин</label>
                                <input type="text" class="form-control string" name="login" value="{{ isset($user) ? $user->login : old('login') }}">
                            </div>
                            <div class="form-group">
                                @if (isset($user))
                                    <label for="">Изменить пароль</label>
                                    <input type="checkbox" id="editPassword" name="editPassword"> <br>
                                    <label for="password">Пароль</label>
                                    <input disabled type="text" class="form-control string" name="password" id="password">
                                @else
                                    <label for="password">Пароль</label>
                                    <input type="text" class="form-control string" name="password" id="password">
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="superadmin">SuperAdmin</label>
                                @if(isset($user))
                                    <input {{ ($user->superadmin) ? 'checked' : '' }} type="checkbox" name="superadmin">
                                @else
                                    <input type="checkbox"  name="superadmin">
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="role">Роль</label>
                                <select name="role" id="role" class="form-control">
                                    @foreach($options as $option)
                                        @if (isset($user))
                                            <option {{ ($user->role == $option['value']) ? 'selected' : '' }} value="{{ $option['value'] }}">{{ $option['name'] }}</option>
                                        @else
                                            <option value="{{ $option['value'] }}">{{ $option['name'] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                    </div>
                    <div class="panel-footer">
                        <button class="btn btn-success" type="submit">{{ isset($user) ? 'Изменить' : 'Добавить' }}</button>
                        <a href="{{ route('admin.users') }}" class="btn btn-danger">Назад</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection