<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Attendance;
use App\Models\AttendanceChangeRequest;

class AttendanceChangeRequestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::where('role', 'user')->get();
        $attendances = Attendance::whereIn('user_id', $users->pluck('id'))->get();

        foreach(range(1, 10) as $i){
            $attendance = $attendances->random();

            AttendanceChangeRequest::factory()->create([
                'user_id' => $attendance->user_id,
                'attendance_id' => $attendance->id,
            ]);
        }
    }
}
