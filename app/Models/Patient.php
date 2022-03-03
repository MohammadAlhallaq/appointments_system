<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory;

    protected $guarded = [];


    protected $casts = [
        'first_name' => 'string',
        'last_name' => 'string',
        'address' => 'string',
        'notes' => 'string',
        'phone_number' => 'integer',
    ];

    function images(): HasMany{
        return $this->hasMany(Image::class);
    }
}
