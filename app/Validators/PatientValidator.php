<?php

namespace App\Validators;

use App\Models\User;
use Illuminate\Validation\Rule;


class PatientValidator
{
    public static function validate(array $attributes, User $office): array
    {
        $rules = [
            'first_name' => ['required', 'string', 'max:25'],
            'last_name' => ['required', 'string', 'max:25'],
            'address' => ['required', 'string', 'max:200'],
            'notes' => ['required', 'string', 'max:200'],
            'phone_number' => ['required', 'numeric'],
            'images' => ['array'],
            'images.*' => ['image', 'mimes:jpeg,jpg,png|max:1000']
        ];
        return validator($attributes, $rules)->validate();
    }
}
