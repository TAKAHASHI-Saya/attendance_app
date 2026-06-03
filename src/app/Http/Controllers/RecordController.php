<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\RestBreak;
use App\Models\AttendanceChangeRequest;
use App\Models\BreakChangeRequest;
use App\Http\Requests\ChangeRequest;

class RecordController extends Controller
{
    public function index(Request $request)
    {
        $currentMonth = Carbon::parse(
            $request->month ?? now()
        );

        $attendances = Attendance::with('restBreaks')
        ->where('user_id', auth()->id())
        ->whereYear('work_date', $currentMonth->year)
        ->whereMonth('work_date', $currentMonth->month)
        ->orderBy('work_date')
        ->get();

        $startOfMonth = $currentMonth->copy()->startOfMonth();

        $endOfMonth = $currentMonth->copy()->endOfMonth();

        $dates = [];

        for(
            $date = $startOfMonth->copy();
            $date->lte($endOfMonth);
            $date->addDay()
        ){
            $dates[] = $date->copy();
        }

        return view('user.attendance.record', compact('currentMonth', 'attendances', 'dates'));
    }

    public function showDetail($id)
    {
        $attendance = Attendance::with('user','restBreaks')
        ->findOrFail($id);

        $changeRequest = AttendanceChangeRequest::where('attendance_id', $attendance->id)
        ->latest()
        ->first();

        return view('user.attendance.detail', compact('attendance', 'changeRequest'));
    }

    public function storeChangeRequest(ChangeRequest $request, $id)
    {
        $attendance = Attendance::with('restBreaks')
        ->findOrFail($id);

        $changeRequest = DB::transaction(function() use ($request, $attendance){
            $changeRequest = AttendanceChangeRequest::create([
                'user_id' => Auth::id(),
                'attendance_id' => $attendance->id,
                'after_clock_in_at' => $request->after_clock_in_at,
                'after_clock_out_at' => $request->after_clock_out_at,
                'reason' => $request->reason,
                'status' => 'pending',
            ]);

            if($request->after_break_in_at && $request->after_break_out_at){
                foreach($request->after_break_in_at as $index => $breakIn){
                    BreakChangeRequest::create([
                        'attendance_change_request_id' => $changeRequest->id,
                        'after_break_in_at' => $breakIn,
                        'after_break_out_at' => $request->after_break_out_at[$index] ?? null,
                    ]);
                }
            }
            return $changeRequest;
        });

        return back();
    }
}
