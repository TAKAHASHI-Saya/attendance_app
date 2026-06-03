<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\AttendanceChangeRequest;
use App\Models\RestBreak;
use App\Http\Requests\AdminAttendanceRequest;

class AdminAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $currentDay = $request->date ? Carbon::parse($request->date) : Carbon::today();

        $attendances = Attendance::with(['user', 'restBreaks'])
        ->whereDate('work_date', $currentDay)
        ->get();

        return view('admin.attendance.index', compact('currentDay', 'attendances'));
    }

    public function showDetail($id)
    {
        $attendance = Attendance::with([
            'user',
            'restBreaks'
        ])->findOrFail($id);

        $changeRequest = AttendanceChangeRequest::where(
            'attendance_id',
            $attendance->id
        )->latest()
        ->first();

        return view('admin.attendance.detail', compact('attendance', 'changeRequest'));
    }

    public function update(AdminAttendanceRequest $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        $attendance->update([
            'clock_in_at' => $request->clock_in_at,
            'clock_out_at' => $request->clock_out_at,
        ]);

        $breakIds = $request->break_id ?? [];
        $breakInTimes = $request->break_in_at ?? [];
        $breakOutTimes = $request->break_out_at ?? [];

        foreach($breakInTimes as $index => $breakInAt){
            $breakOutAt = $breakOutTimes[$index] ?? null;

            if(empty($breakInAt) || empty($breakOutAt)){
                continue;
            }

            if(isset($breakIds[$index])){
                $restBreak = RestBreak::find($breakIds[$index]);

                if($restBreak){
                    $restBreak->update([
                        'break_in_at' => $breakInAt,
                        'break_out_at' => $breakOutAt,
                    ]);
                }
            }else{
                RestBreak::create([
                    'attendance_id' => $attendance->id,
                    'break_in_at' => $breakInAt,
                    'break_out_at' => $breakOutAt,
                ]);
            }
        }

        return back();
    }
}
