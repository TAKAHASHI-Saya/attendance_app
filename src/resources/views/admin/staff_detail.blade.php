@extends('layout.admin_app')

@section('css')
<link rel="stylesheet" href="{{asset('css/admin/staff_detail.css')}}">
@endsection

@section('content')
<div class="staff-detail">
    <div class="staff-detail__group">
        <div class="staff-detail__heading">
            <h1 class="staff-detail__title">
                {{$staff->name}}さんの勤怠
            </h1>
        </div>
        <div class="staff-detail__calendar">
            <div class="staff-detail__calendar--link">
                <a href="{{route('admin-staff.attendance.show', ['id' => $staff->id, 'month' => $currentMonth->copy()->subMonth()->format('Y-m')])}}" class="staff-detail__calendar--previous-month">
                    <i class="fa-solid fa-arrow-left"></i>
                    前月
                </a>
            </div>
            <div class="staff-detail__calendar--this-month">
                <i class="fa-regular fa-calendar-days"></i>
                <h2 class="staff-detail__calendar--now">{{$currentMonth->format('Y/m')}}</h2>
            </div>
            <div class="staff-detail__calendar--link">
                <a href="{{route('admin-staff.attendance.show', ['id' => $staff->id, 'month' => $currentMonth->copy()->addMonth()->format('Y-m')])}}" class="staff-detail__calendar--next-month">
                    翌月
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
        <div class="staff-detail__list">
            <table class="staff-detail__table">
                <tr class="staff-detail__table--row">
                    <th class="staff-detail__table--header">日付</th>
                    <th class="staff-detail__table--header">出勤</th>
                    <th class="staff-detail__table--header">退勤</th>
                    <th class="staff-detail__table--header">休憩</th>
                    <th class="staff-detail__table--header">合計</th>
                    <th class="staff-detail__table--header">詳細</th>
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
                <tr class="staff-detail__table--row">
                    <td class="staff-detail__table--item">
                        {{$date->format('m/d')}}
                        ({{$date->isoFormat('ddd')}})
                    </td>
                    <td class="staff-detail__table--item">
                        {{optional($attendance)->clock_in_at?->format('H:i')}}
                    </td>
                    <td class="staff-detail__table--item">
                        {{optional($attendance)->clock_out_at?->format('H:i')}}
                    </td>
                    <td class="staff-detail__table--item">
                        @if($attendance && $totalBreakMinutes > 0)
                        {{sprintf('%d:%02d', $breakHours, $breakMinutes)}}
                        @endif
                    </td>
                    <td class="staff-detail__table--item">
                        @if($attendance && $workingMinutes > 0)
                        {{sprintf('%d:%02d',
                        $workingHours,
                        $workingRemainMinutes)}}
                        @endif
                    </td>
                    <td class="staff-detail__table--item">
                        @if($attendance)
                        <a href="{{route('admin-attendance.detail', $attendance->id)}}" class="staff-detail__table--detail">詳細</a>
                        @else
                        <span class="staff-detail__table--disabled">詳細</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
        <div class="staff-detail__csv">
            <a href="{{route('admin-staff.attendance.csv', [
            'id' => $staff->id,
            'month' => $currentMonth->format('Y-m')
            ])}}" class="staff-detail__csv--output">CSV出力</a>
        </div>
    </div>
</div>
@endsection