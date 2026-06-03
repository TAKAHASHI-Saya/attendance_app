<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance;

class AttendanceChangeRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $afterClockInAt = Carbon::createFromTime(rand(8, 10), rand(0, 59));
        $afterClockOutAt = (clone $afterClockInAt)->addHours(rand(7, 9))->addMinutes(rand(0, 59));

        return [
            'user_id' => null,
            'attendance_id' => null,
            'after_clock_in_at' => $afterClockInAt->format('H:i:s'),
            'after_clock_out_at' => $afterClockOutAt->format('H:i:s'),
            'reason' => $this->faker->randomElement([
                '打刻漏れのため',
                '電車遅延のため',
                '体調不良のため',
                '誤入力のため',
            ]),
            'status' => $this->faker->randomElement([
                'pending',
                'approved',
            ]),
        ];
    }
}
