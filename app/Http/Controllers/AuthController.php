<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Response;

class AuthController extends Controller
{
    function login(Request $request): Response|RedirectResponse
    {
        if ($request->isMethod("POST")) {

            $rules = [
                'username' => ['required', 'string'],
                'password' => ['required'],
            ];

            $data = validator($request->all(), $rules)->validate();

            $credentials = ['username' => $data['username'], 'password' => $data['password']];

            throw_unless(Auth::attempt($credentials, $request->boolean('rememberMe')), ValidationException::withMessages(['credentials' => 'invalid username or password']));

            return redirect()->route('home');
        }
        return inertia('auth/login');
    }
}
