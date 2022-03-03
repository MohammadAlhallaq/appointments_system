<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Appointment extends Model
{
    use HasFactory;


    protected $guarded = [];


    protected function startDate(): Attribute
    {
        return Attribute::make(
            get: fn($value) => date('h:i a m/d/Y', strtotime($value)),
        );
    }

    function ScopeBetweenDates(Builder $query, DateTime $start_date, DateTime $end_date)
    {
        $query->where(function (Builder $query) use ($start_date, $end_date) {

            $query->whereBetween('start_date', [$start_date, $end_date])

                ->orWhereBetween('end_date', [$start_date, $end_date])

                ->orWhere(function ($query) use ($start_date, $end_date) {
                    $query
                        ->where([['start_date', '<', $start_date], ['end_date', '>', $end_date]]);
                });;
        });
    }
}
