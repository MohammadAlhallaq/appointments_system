<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    function create(Patient $patient): RedirectResponse
    {

        $rules = [
            'images' => ['array'],
            'images.*' => ['image', 'mimes:jpeg,jpg,png|max:1000'],
        ];

        $data = validator(request()->all(), $rules)->validate();

        collect($data['images'])->each(function ($image) use ($patient) {
            $path = Storage::disk('public')->put('images', $image);
            $patient->images()->create([
                'path' => $path
            ]);
        });

        return redirect()->route('patients');
    }
}
