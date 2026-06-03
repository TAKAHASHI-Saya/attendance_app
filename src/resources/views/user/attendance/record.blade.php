@extends('layout.user_app')

@section('css')
<link rel="stylesheet" href="{{asset('css/user/attendance/record.css')}}">
@endsection

@section('content')
<div class="record">
    <div class="record__group">
        <div class="record__heading">
            <h1 class="record__title">勤怠一覧</h1>
        </div>
        <div class="record__calendar">
            <div class="record__calendar--link">
                <a href="{{url('/attendance/list?month=' . $currentMonth->copy()->subMonth()->format('Y-m'))}}" class="record__calendar--previous-month">
                    <i class="fa-solid fa-arrow-left"></i>
                    前月
                </a>
            </div>
            <div class="record__calendar--this-month">
                <i class="fa-regular fa-calendar-days"></i>
                <h2 class="record__calendar--now">{{$currentMonth->format('Y/m')}}</h2>
            </div>
            <div class="record__calendar--link">
                <a href="{{url('/attendance/list?month=' . $currentMonth->copy()->addMonth()->format('Y-m'))}}" class="record__calendar--next-month">
                    翌月
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
        <div class="record__list">
            <table class="record__table">
                <tr class="record__table--row">
                    <th class="record__table--header">日付</th>
                    <th class="record__table--header">出勤</th>
                    <th class="record__table--header">退勤</th>
                    <th class="record__table--header">休憩</th>
                    <th class="record__table--header">合計</th>
                    <th class="record__table--header">詳細</th>
                </tr>
                @foreach($dates as $date)
                    @php
                        $attendance = $attendances->first(function($item) use ($date){
                            return $item->work_date->format('Y-m-d') === $date->format('Y-m-d');
                        });
                        $totalBreakMinutes = 0;
                        if($attendance){
                            foreach($attendance->restBreaks as $break){
                                if($break->break_in_at && $break->break_out_at){
                                    $totalBreakMinutes +=
                                        $break->break_out_at->diffInMinutes($break->break_in_at);
                                }
                            }
                        }
                        $breakHours = floor($totalBreakMinutes / 60);
                        $breakMinutes = $totalBreakMinutes % 60;
                        $workingMinutes = 0;
                        if(
                            $attendance &&
                            $attendance->clock_in_at &&
                            $attendance->clock_out_at
                        ){
                            $workingMinutes = $attendance->clock_out_at->diffInMinutes($attendance->clock_in_at);
                            $workingMinutes -= $totalBreakMinutes;
                        }
                        $workingHours = floor($workingMinutes / 60);
                        $workingRemainMinutes = $workingMinutes % 60;
                    @endphp
                <tr class="record__table--row">
                    <td class="record__table--item">
                        {{$date->format('m/d')}}
                        ({{$date->isoFormat('ddd')}})
                    </td>
                    <td class="record__table--item">
                        {{optional($attendance)->clock_in_at?->format('H:i')}}
                    </td>
                    <td class="record__table--item">
                        {{optional($attendance)->clock_out_at?->format('H:i')}}
                    </td>
                    <td class="record__table--item">
                        @if($attendance && $totalBreakMinutes > 0)
                        {{sprintf('%d:%02d', $breakHours, $breakMinutes)}}
                        @endif
                    </td>
                    <td class="record__table--item">
                        @if($attendance && $workingMinutes > 0)
                        {{sprintf('%d:%02d',
                        $workingHours,
                        $workingRemainMinutes)}}
                        @endif
                    </td>
                    <td class="record__table--item">
                        @if($attendance)
                        <a href="{{route('user-attendance.detail', ['id' => $attendance->id])}}" class="record__table--detail">詳細</a>
                        @else
                        <span class="record__table--disabled">詳細</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
@endsection