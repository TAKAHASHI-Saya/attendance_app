<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use App\Models\AttendanceChangeRequest;

class BreakChangeRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $afterBreakInAt = Carbon::createFromTime(rand(11, 15), rand(0, 59));
        $afterBreakOutAt = (clone $afterBreakInAt)->addMinutes(rand(15, 60));

        return [
            'attendance_change_request_id' => null,
            'rest_break_id' => null,
            'after_break_in_at' => $afterBreakInAt->format('H:i:s'),
            'after_break_out_at' => $afterBreakOutAt->format('H:i:s'),
        ];
    }
}
