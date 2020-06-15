<?php

namespace App\Http\Controllers;

use App\Department;
use App\Patient;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $users = $users->map(function($user){
            $user['department'] = $user->department;
            $user['role'] = $user->roles;
           return $user;
        });
        return view('users.index')->with(['users' => $users]);
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $user = new User();
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = bcrypt($request->get('password'));

        $roleOperator = Role::where('name', '=', 'operator')->first();
        $user->save();
        $user->roles()->attach($roleOperator);

        unset($user['password']);
        return response()->json([
            'data' => $user,
            'status' =>true,
            'message' => 'OK'
        ], 200);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $user['department'] = $user->department;
        unset($user['password']);

        return view('users.edit')->with(['user' => $user]);
    }

    public function update($id, Request $request)
    {
        $user = User::findOrFail($id);

        $user->name = $request->get('name');
        $newPassword = $request->get('newPassword');
        if ($newPassword) {
            $user->password = bcrypt($newPassword);
        }

        $user->save();
        return response()->json([
            'data' => null,
            'status' => false,
            'message' => 'Cập nhật tài khoản thành công'
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'data' => null,
            'status' => true,
            'message' => 'Xóa tài khoản thành công'
        ]);
    }
    public function checkPassword(Request $request)
    {
        $email = $request->get('email');
        $user = User::where('email', '=',$email)->firstOrFail();
        if (!(Hash::check($request->get('oldPassword'), $user->password))) {
            // The passwords matches
            $msg = 'Không chính xác';
            return response()->json([
                'data' => null,
                'status' => false,
                'message' => $msg
            ]);
        }
        return response()->json([
            'data' => null,
            'status' => true,
            'message' => 'Chính xác'
        ]);
    }

    public function checkEmail(Request $request)
    {
        $email = $request->get('email');
        $userEmail = User::where('email', '=',$email)->first();
        if ($userEmail) {
            // The passwords matches
            $msg = 'Tên đăng nhập đã tồn tại';
            return response()->json([
                'data' => null,
                'status' => false,
                'message' => $msg
            ]);
        }
        return response()->json([
            'data' => null,
            'status' => true,
            'message' => 'OK'
        ]);
    }
}