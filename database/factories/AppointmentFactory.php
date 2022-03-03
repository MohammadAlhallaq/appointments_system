<?php

namespace Database\Factories;

use App\Models\Patient;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $datetime = $this->faker->dateTime;

        return [
            'start_date' => $datetime,
            'end_date' => CarbonImmutable::parse($datetime)->addMinutes(30),
            'patient_id' => Patient::factory(),
        ];
    }
}
