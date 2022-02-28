<?php

namespace App\Http\Controllers;

use App\Enums\AccountType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientController extends Controller
{
    function index(): View {
        $patients = User::where('account_type', AccountType::PATIENT)->get();
        return view('pages.patients.all-patients', compact('patients'));
    }


    function create(Request $request): View {

        if ($request->isMethod('POST')){

            $rules = ['username' => ['required']];

            $data = validator($request->all(), $rules)->validate();



        }
        return view('pages.patients.new-patient');
    }
}
