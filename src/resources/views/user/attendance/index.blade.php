@extends('layout.user_app')

@section('css')
<link rel="stylesheet" href="{{asset('css/user/attendance/index.css')}}">
@endsection

@section('content')
<div class="attendance">
    <div class="attendance__group">
        <div class="attendance__status">
            <p class="attendance__status--label">
                {{$attendance?->status ?? '勤務外'}}
            </p>
        </div>
        <div class="attendance__date">
            <p class="attendance__date--today">
                {{now()->isoFormat('YYYY年M月D日(ddd)')}}
            </p>
        </div>
        <div class="attendance__time">
            <p class="attendance__time--now">
                {{now()->format('H:i')}}
            </p>
        </div>
        <div class="attendance__form">
            @if(($attendance?->status ?? '勤務外') === '勤務外')
            <form action="/clock-in" method="post" class="attendance__time-card">
                @csrf
                <button type="submit" class="attendance__clock-in">出勤</button>
            </form>
            @elseif($attendance->status === '出勤中')
            <form action="/clock-out" method="post" class="attendance__time-card">
                @csrf
                <button type="submit" class="attendance__clock-out">退勤</button>
            </form>
            <form action="/break-in" method="post" class="attendance__time-card">
                @csrf
                <button type="submit" class="attendance__break-in">休憩入</button>
            </form>
            @elseif($attendance->status === '休憩中')
            <form action="/break-out" method="post" class="attendance__time-card">
                @csrf
                <button type="submit" class="attendance__break-out">休憩戻</button>
            </form>
            @elseif($attendance->status === '退勤済')
            <div class="attendance__message">
                <p class="attendance__message--finish">お疲れ様でした。</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection