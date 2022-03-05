<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @throws \Throwable
     * @throws ValidationException
     */
    function login(Request $request)
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
        return view('pages.auth.log-in');
    }
}
