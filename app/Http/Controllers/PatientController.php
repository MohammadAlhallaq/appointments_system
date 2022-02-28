<?php

namespace App\Http\Controllers;

use App\Enums\AccountType;
use App\Models\User;
use App\Validators\PatientValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PatientController extends Controller
{
    function index(): View
    {
        return view('pages.patients.all-patients')
            ->with(['patients' => User::where('account_type', AccountType::PATIENT)->get()]);
    }


    function create(Request $request): View
    {
        if ($request->isMethod('POST')) {

            $data = PatientValidator::validate($request->all(), $patient = new User());

            DB::transaction(function () use ($data, $patient) {

                $patient->fill(Arr::except($data, 'images'))->save();

                if (isset($data['images'])) {

                    foreach ($data['images'] as $image) {

                        $path = Storage::disk('public')->put('images', $image);

                        $patient->images()->create([
                            'path' => $path
                        ]);
                    }
                }
                return $patient;
            });
            return view('pages.patients.all-patients');
        }
        return view('pages.patients.new-patient');
    }
}
