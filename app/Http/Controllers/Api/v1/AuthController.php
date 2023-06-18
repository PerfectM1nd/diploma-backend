<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\User;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        if ($this->isLoginOccupied($request['login'])) {
            return response()->json([
                'message' => 'Логин занят'
            ], 400);
        }
        $user = new User();
        $user->login = $request['login'];
        $user->password = bcrypt($request['password']);
        $user->save();
        return response()->json([
            'message' => 'Пользователь успешно зарегистрирован',
            'token' => auth()->attempt(['login' => $request['login'], 'password' => $request['password']])
        ]);
    }

    public function login(LoginUserRequest $request)
    {
        $token = auth()->attempt(['login' => $request['login'], 'password' => $request['password']]);
        if (!$token) return response()->json([
            'message' => 'Неверный логин или пароль',
        ], 400);
        return response()->json([
            'message' => 'Успешный вход в систему',
            'token' => $token
        ]);
    }

    public function fetchMe()
    {
        return response()->json([
            'user' => auth()->user()
        ]);
    }

    private function isLoginOccupied(string $login)
    {
        return !!User::where('login', $login)->first();
    }
}
