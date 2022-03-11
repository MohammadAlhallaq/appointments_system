<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AppointmentController extends Controller
{

    function index(): View
    {
        $appointments = Appointment::all();
        return view('pages.appointments.index')->with(['appointments' => $appointments]);
    }

    function create(): RedirectResponse
    {
        $rules = [
            'start_date' => ['date', 'required'],
            'end_date' => ['date', 'required'],
            'patient_id' => ['required', Rule::exists('patients', 'id')],
        ];

        $data = validator(request()->all(), $rules)->validate();

        throw_if(Appointment::BetweenDates(CarbonImmutable::parse($data['start_date'])->toDateTime(), CarbonImmutable::parse($data['start_date'])->toDateTime())->exists(), ValidationException::withMessages(['appointment' => 'Can\'t make appointment at this time']));

        Appointment::create([
            'start_date' => request()->date('start_date'),
            'end_date' => request()->date('end_date'),
            'patient_id' => $data['patient_id']
        ]);

        return Redirect::route('appointments');
    }


    function update(Patient $patient, Appointment $appointment): RedirectResponse
    {
        $rules = [
            'start_date' => ['date', 'required'],
        ];

        $data = validator(request()->all(), $rules)->validate();

        throw_if(Appointment::BetweenDates(CarbonImmutable::parse($data['start_date'])->toDateTime(), CarbonImmutable::parse($data['start_date'])->addMinutes(30)->toDateTime())->exists(), ValidationException::withMessages(['office_id' => 'Can\'t make appointment at this time']));

        $appointment->update([
            'start_date' => request()->date('start_date'),
            'end_date' => request()->date('start_date')->addMinutes('30')->toDateTimeString(),
        ]);

        return Redirect::route('appointments');
    }


    function delete(Appointment $appointment): RedirectResponse
    {
        $appointment->delete();
        return Redirect::route('appointments');
    }
}
