<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
    function create()
    {

        $rules = [
            'start_date' => ['date', 'required'],
            'patient_id' => ['required', Rule::exists('patients', 'id')]
        ];

        $data = validator(request()->all(), $rules)->validate();

        throw_if(Appointment::BetweenDates(CarbonImmutable::parse($data['start_date'])->toDateTime(), CarbonImmutable::parse($data['start_date'])->addMinutes(30)->toDateTime())->exists(), ValidationException::withMessages(['office_id' => 'Can\'t make appointment at this time']));


        Appointment::create([
            'start_date' => request()->date('start_date'),
            'end_date' => request()->date('start_date')->addMinutes(30)->toDateTimeString(),
            'patient_id' => $data['patient_id']
        ]);

    }


    function update(){
        $rules = [
            'start_date' => ['date', 'required'],
            'patient_id' => ['required', Rule::exists('patients', 'id')]
        ];
    }
}
