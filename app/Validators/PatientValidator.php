<?php

namespace App\Validators;

use App\Models\Patient;
use Illuminate\Validation\Rule;


class PatientValidator
{
    public static function validate(array $attributes, Patient $patient): array
    {
        $rules = [
            'first_name' => [Rule::when($patient->exists, 'sometimes'), 'required', 'string', 'max:25'],
            'last_name' => [Rule::when($patient->exists, 'sometimes'), 'required', 'string', 'max:25'],
            'address' => [Rule::when($patient->exists, 'sometimes'), 'required', 'string', 'max:200'],
            'notes' => [Rule::when($patient->exists, 'sometimes'), 'required', 'string', 'max:200'],
            'phone_number' => [Rule::when($patient->exists, 'sometimes'), 'required', 'numeric'],
            'images' => ['array'],
            'images.*' => ['sometimes', 'image', 'mimes:jpeg,jpg,png|max:1000'],
        ];
        return validator($attributes, $rules)->validate();
    }
}
