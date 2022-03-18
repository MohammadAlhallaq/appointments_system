<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Response;

class AppointmentController extends Controller
{

    function index(): Response
    {
        return inertia('appointments/index',
            [
                'appointments' => Appointment::all()
            ]);
    }

    function create(): RedirectResponse
    {
        $rules = [
            'start_date' => ['date', 'required'],
            'end_date' => ['date', 'required', 'after:start_date'],
            'patient_id' => ['required', 'numeric', Rule::exists('patients', 'id')],
        ];

        $data = validator(request()->all(), $rules)->validate();

        throw_if(Appointment::BetweenDates(Carbon::parseToDatetime($data['start_date']), Carbon::parseToDatetime($data['end_date']))->exists(), ValidationException::withMessages(['appointment' => 'Can\'t make appointment at this time']));

        Appointment::create([
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'patient_id' => $data['patient_id']
        ]);

        return Redirect::route('appointments');
    }

    function update(Patient $patient, Appointment $appointment): RedirectResponse
    {
        $rules = [
            'start_date' => ['date', 'required'],
            'end_date' => ['date', 'required', 'after:start_date'],
        ];

        $data = validator(request()->all(), $rules)->validate();

        throw_if(Appointment::BetweenDates(Carbon::parseToDatetime($data['start_date']), Carbon::parseToDatetime($data['end_date']))->exists(), ValidationException::withMessages(['appointment' => 'Can\'t make appointment at this time']));

        $appointment->update([
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
        ]);

        return Redirect::route('appointments');
    }

    function delete(Appointment $appointment): RedirectResponse
    {
        $appointment->delete();
        return Redirect::route('appointments');
    }
}
