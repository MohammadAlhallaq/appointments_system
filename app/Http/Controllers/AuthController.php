<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    function index(Request $request){

        if ($request->isMethod("POST")){

            $rules = [
                'phone_number' => ['required', 'numeric'],
                'password' => ['required']
            ];

            $data = validator($request->all(), $rules)->validate();

        }
        return view("pages.auth.log-in");
    }
}
