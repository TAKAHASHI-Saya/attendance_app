<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class AdminStaffController extends Controller
{
    public function showStaffList()
    {
        $staffs = User::where('role', 'user')->get();

        return view('admin.staff_list', compact('staffs'));
    }

    public function showStaffAttendance(Request $request, $id)
    {
        $staff = User::findOrFail($id);

        $currentMonth = Carbon::parse(
            $request->month ?? now()
        );

        $attendances = Attendance::with('restBreaks')
        ->where('user_id', $staff->id)
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

        return view('admin.staff_detail', compact('staff', 'currentMonth', 'attendances', 'dates'));
    }

    public function exportCsv(Request $request, $id)
    {
        $staff = User::findOrFail($id);

        $currentMonth = Carbon::parse(
            $request->month ?? now()
        );

        $attendances = Attendance::with('restBreaks')
        ->where('user_id', $staff->id)
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

        $fileName = $staff->name . '_' . $currentMonth->format('Y_m') . '_attendance.csv';

        return response()->streamDownload(
            function() use($staff, $dates, $attendances){
                $handle = fopen('php://output', 'w');

                fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

                fputcsv($handle, [
                    $staff->name . 'さんの勤怠'
                ]);

                fputcsv($handle, []);

                fputcsv($handle, [
                    '日付',
                    '出勤',
                    '退勤',
                    '休憩',
                    '合計'
                ]);

                foreach($dates as $date){
                    $attendance = $attendances->first(
                        function($item) use ($date){
                            return $item->work_date->format('Y-m-d') === $date->format('Y-m-d');
                        }
                    );

                    $totalBreakMinutes = 0;

                    if($attendance){
                        foreach(
                            $attendance->restBreaks as $break
                        ){
                            if($break->break_in_at && $break->break_out_at){
                                $totalBreakMinutes += $break->break_out_at->diffInMinutes($break->break_in_at);
                            }
                        }
                    }

                    $workingMinutes = 0;

                    if($attendance && $attendance->clock_in_at && $attendance->clock_out_at){
                        $workingMinutes = $attendance->clock_out_at->diffInMinutes(
                            $attendance->clock_in_at
                        );

                        $workingMinutes -= $totalBreakMinutes;
                    }

                    $breakTime = $totalBreakMinutes > 0 ? sprintf('%d:%02d',
                    floor($totalBreakMinutes / 60),
                    $totalBreakMinutes % 60) : '';

                    $workTime = $workingMinutes > 0 ? sprintf('%d:%02d',
                    floor($workingMinutes / 60),
                    $workingMinutes % 60) : '';

                    fputcsv($handle, [
                        $date->format('m/d')
                        . '('
                        . $date->isoFormat('ddd')
                        . ')',

                        optional($attendance)->clock_in_at?->format('H:i'),

                        optional($attendance)->clock_out_at?->format('H:i'),

                        $breakTime,
                        $workTime,
                    ]);
                }
                fclose($handle);
            },
            $fileName,
            [
                'content-Type' => 'text/csv',
            ]
        );
    }
}
