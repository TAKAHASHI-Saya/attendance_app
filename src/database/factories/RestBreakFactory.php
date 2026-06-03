<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Attendance;
use Carbon\Carbon;

class RestBreakFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $breakInAt = Carbon::createFromTime(rand(10, 15), rand(0, 59));
        $breakOutAt = (clone $breakInAt)->addMinutes(rand(15, 60));

        return [
            'attendance_id' => null,
            'break_in_at' => $breakInAt->format('H:i:s'),
            'break_out_at' => $breakOutAt->format('H:i:s'),
        ];
    }
}
