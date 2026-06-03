@extends('layout.admin_app')

@section('css')
<link rel="stylesheet" href="{{asset('css/admin/attendance/index.css')}}">
@endsection

@section('content')
<div class="attendance-list">
    <div class="attendance-list__group">
        <div class="attendance-list__heading">
            <h1 class="attendance-list__title">
                {{$currentDay->format('Y年m月d日')}}の勤怠
            </h1>
        </div>
        <div class="attendance-list__calendar">
            <div class="attendance-list__calendar--link">
                <a href="{{url('admin/attendance/list?date=' . $currentDay->copy()->subDay()->format('Y-m-d'))}}" class="attendance-list__calendar--yesterday">
                    <i class="fa-solid fa-arrow-left"></i>
                    前日
                </a>
            </div>
            <div class="attendance-list__calendar--today">
                <i class="fa-regular fa-calendar-days"></i>
                <h2 class="attendance-list__calendar--now">{{$currentDay->format('Y/m/d')}}</h2>
            </div>
            <div class="attendance-list__calendar--link">
                <a href="{{url('admin/attendance/list?date=' . $currentDay->copy()->addDay()->format('Y-m-d'))}}" class="attendance-list__calendar--tomorrow">
                    翌日
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
        <div class="attendance-list__record">
            <table class="attendance-list__table">
                <tr class="attendance-list__table--row">
                    <th class="attendance-list__table--header">名前</th>
                    <th class="attendance-list__table--header">出勤</th>
                    <th class="attendance-list__table--header">退勤</th>
                    <th class="attendance-list__table--header">休憩</th>
                    <th class="attendance-list__table--header">合計</th>
                    <th class="attendance-list__table--header">詳細</th>
                </tr>
                @foreach($attendances as $attendance)
                    @php
                    $totalBreakMinutes = $attendance->restBreaks->sum(function($break){
                        if(!$break->break_in_at || !$break->break_out_at){
                            return 0;
                        }
                        return Carbon\Carbon::parse($break->break_in_at)->diffInMinutes(Carbon\Carbon::parse($break->break_out_at));
                    });
                    if(!$attendance->clock_in_at || !$attendance->clock_out_at){
                        $workMinutes = 0;
                    }else{
                        $workMinutes = Carbon\Carbon::parse($attendance->clock_in_at)->diffInMinutes(Carbon\Carbon::parse($attendance->clock_out_at));
                    }
                    $workMinutes = $workMinutes - $totalBreakMinutes;
                    @endphp
                <tr class="attendance-list__table--row">
                    <td class="attendance-list__table--item">{{$attendance->user->name}}</td>
                    <td class="attendance-list__table--item">{{optional($attendance)->clock_in_at?->format('H:i')}}</td>
                    <td class="attendance-list__table--item">{{optional($attendance)->clock_out_at?->format('H:i')}}</td>
                    <td class="attendance-list__table--item">
                        @if($attendance && $totalBreakMinutes > 0)
                        {{intdiv($totalBreakMinutes, 60)}}:{{str_pad($totalBreakMinutes % 60, 2, '0', STR_PAD_LEFT)}}
                        @endif
                    </td>
                    <td class="attendance-list__table--item">
                        @if($attendance && $workMinutes > 0)
                        {{intdiv($workMinutes, 60)}}:{{str_pad($workMinutes % 60, 2, '0', STR_PAD_LEFT)}}
                        @endif
                    </td>
                    <td class="attendance-list__table--item">
                        <a href="{{route('admin-attendance.detail', $attendance->id)}}" class="attendance-list__table--detail">詳細</a>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
@endsection