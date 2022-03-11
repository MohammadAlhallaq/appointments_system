<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class AuthController extends Controller
{
    /**
     * @throws \Throwable
     * @throws ValidationException
     */
    function login(Request $request): Response
    {
        if ($request->isMethod("POST")) {

            $rules = [
                'username' => ['required', 'string'],
                'password' => ['required'],
            ];

            $data = validator($request->all(), $rules)->validate();

            $credentials = ['username' => $data['username'], 'password' => $data['password']];

            throw_unless(Auth::attempt($credentials), ValidationException::withMessages(['credentials' => 'invalid username or password']));

            return view('pages.home');
        }
        return Inertia::render('login');
    }
}
