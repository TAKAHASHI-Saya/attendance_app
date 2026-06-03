<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Carbon\Carbon;

class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $clockInAt = Carbon::createFromTime(9, rand(0, 59));
        $clockOutAt = (clone $clockInAt)->addHours(9)->addMinutes(rand(0, 59));

        return [
            'user_id' => null,
            'work_date' => $this->faker->date(),
            'clock_in_at' => $clockInAt->format('H:i:s'),
            'clock_out_at' => $clockOutAt->format('H:i:s'),
        ];
    }
}
