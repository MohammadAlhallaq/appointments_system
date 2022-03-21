<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Validators\PatientValidator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Response;

class PatientController extends Controller
{
    function index(): Response
    {
        return inertia('patients/index',
            [
                'patients' => Patient::all()
            ]);
    }

    function create(Request $request): Response
    {
        if ($request->isMethod('POST')) {

            $data = PatientValidator::validate($request->all(), $patient = new Patient());

            DB::transaction(function () use ($data, $patient) {

                $patient->fill(Arr::except($data, 'images'))->save();

                if (isset($data['images'])) {
                    collect($data['images'])->each(function ($image) use ($patient) {
                        $path = Storage::disk('public')->put('images', $image);
                        $patient->images()->create([
                            'path' => $path
                        ]);
                    });
                }
                return $patient;
            });
            return inertia('patients/index');
        }
        return inertia('patients/create');
    }

    function show(Patient $patient): Response
    {
        return inertia('patients/show',
        [
            'patient' => $patient->load('images')
        ]);
    }

    function update(Patient $patient): RedirectResponse
    {

        $data = PatientValidator::validate(request()->all(), $patient);

        $patient->fill(Arr::except($data, ['images']))->save();

        return Redirect::route('patients');
    }


    function delete(Patient $patient): void
    {
        $patient->images()->each(function ($image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        });
        $patient->delete();
    }
}
