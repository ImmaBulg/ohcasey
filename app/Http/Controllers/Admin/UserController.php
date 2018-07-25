<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 12.07.2018
 * Time: 9:32
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('admin.user.list', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        $options = [
            ['name' => 'Менеджер', 'value' => 'manager'],
            ['name' => 'Администратор', 'value' => 'admin'],
        ];

        return view('admin.user.form', ['options' => $options]);
    }

    public function store(Request $request)
    {
        $user = new User();

        $user->name = $request->input('name');
        $user->login = $request->input('login');
        $user->password = bcrypt($request->input('password'));
        if ($request->input('superadmin'))
            $user->superadmin = $request->input('superadmin');
        else
            $user->superadmin = false;
        $user->role = $request->input('role');
        $user->save();

        return redirect(route('admin.users'))->with('success', ['Пользователь успешно добавлен']);
    }

    public function show(User $user)
    {
        $options = [
            ['name' => 'Менеджер', 'value' => 'manager'],
            ['name' => 'Администратор', 'value' => 'admin'],
        ];

        return view('admin.user.form', ['user' => $user, 'options' => $options]);
    }

    public function update(User $user, Request $request)
    {
        $user->name = $request->input('name');
        $user->login = $request->input('login');
        if ($request->input('editPassword'))
            $user->password = bcrypt($request->input('password'));
        if ($request->input('superadmin'))
            $user->superadmin = $request->input('superadmin');
        else
            $user->superadmin = false;
        $user->role = $request->input('role');
        $user->save();

        return redirect(route('admin.users'))->with('success', ['Пользователь успешно изменен']);
    }

    public function delete(User $user)
    {
        $user->delete();

        return redirect(route('admin.users'))->with('success', ['Пользователь успешно удален']);
    }
}