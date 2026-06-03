<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\RestBreak;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendance = Attendance::where('user_id', auth()->id())
        ->whereDate('work_date', today())
        ->first();

        return view('user.attendance.index', compact('attendance'));
    }

    public function clockIn()
    {
        Attendance::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'work_date' => today(),
            ],
            [
                'clock_in_at' => now(),
            ]
        );
        return redirect('/attendance');
    }

    public function breakIn()
    {
        $attendance = Attendance::where('user_id', auth()->id())
        ->whereDate('work_date', today())
        ->first();

        RestBreak::create([
            'attendance_id' => $attendance->id,
            'break_in_at' => now(),
        ]);

        return redirect('/attendance');
    }

    public function breakOut()
    {
        $attendance = Attendance::where('user_id', auth()->id())
        ->whereDate('work_date', today())
        ->first();

        $break = RestBreak::where('attendance_id', $attendance->id)
        ->latest()
        ->first();

        $break->update([
            'break_out_at' => now(),
        ]);

        return redirect('/attendance');
    }

    public function clockOut()
    {
        $attendance = Attendance::where('user_id', auth()->id())
        ->whereDate('work_date', today())
        ->first();

        $attendance->update([
            'clock_out_at' => now(),
        ]);

        return redirect('/attendance');
    }
}
