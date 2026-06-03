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

        foreach($request->break_id as $index => $breakId)
            {
                
                $restBreak = RestBreak::find($breakId);

                $restBreak->update([
                    'break_in_at' => $request->break_in_at[$index],
                    'break_out_at' => $request->break_out_at[$index],
                ]);
            }
        
        $existingBreakCount = count($request->break_id);
        if(
            !empty($request->break_in_at[$existingBreakCount]) &&
            !empty($request->break_out_at[$existingBreakCount]))
            {
                RestBreak::create([
                    'attendance_id' => $attendance->id,
                    'break_in_at' => $request->break_in_at[$existingBreakCount],
                    'break_out_at' => $request->break_out_at[$existingBreakCount],
                ]);
            }

        return back();
    }
}
