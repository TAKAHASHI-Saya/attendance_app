<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AttendanceChangeRequest;
use App\Models\RestBreak;
use App\Models\BreakChangeRequest;

class BreakChangeRequestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attendanceIdsWithBreaks = RestBreak::pluck('attendance_id')->unique();

        $changeRequests = AttendanceChangeRequest::whereIn('attendance_id', $attendanceIdsWithBreaks)->get();

        $breaks = RestBreak::all()->groupBy('attendance_id');

        foreach(range(1, 10) as $i){
            $changeRequest = $changeRequests->random();

            $targetBreaks = $breaks[$changeRequest->attendance_id];

            $break = $targetBreaks->random();

            BreakChangeRequest::factory()->create([
                'attendance_change_request_id' => $changeRequest->id,
                'rest_break_id' => $break->id,
            ]);
        }
    }
}
