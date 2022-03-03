<?php

namespace App\Http\Controllers;

use App\Models\Patient;
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
        return view('pages.patients.index')
            ->with(['patients' => Patient::all()]);
    }

    function create(Request $request): View
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
            return view('pages.patients.index');
        }
        return view('pages.patients.create');
    }

    function show(Patient $patient)
    {
        return view('pages.patients.show')->with(['patient' => $patient->load('images')]);
    }

    function update(Patient $patient): View
    {

        $data = PatientValidator::validate(request()->all(), $patient);

        $patient->fill(Arr::except($data, ['images']))->save();

        return view('pages.patients.index');
    }

    function delete(Patient $patient): View
    {
        $patient->images()->each(function ($image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        });
        $patient->delete();
    }
}
