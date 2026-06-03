@extends('layout.admin_app')

@section('css')
<link rel="stylesheet" href="{{asset('css/admin/attendance/detail.css')}}">
@endsection

@section('content')
<div class="detail">
    <div class="detail__group">
        <div class="detail__heading">
            <h1 class="detail__title">勤怠詳細</h1>
        </div>
        <div class="detail__field">
            <form action="{{route('admin-attendance.update', $attendance->id)}}" method="post" class="detail__form">
                @csrf
                @method('PATCH')
                <div class="detail__form--group">
                    <div class="detail__form--row">
                        <label class="detail__form--label">名前</label>
                        <p class="detail__form--name">
                            {{$attendance->user->name}}
                        </p>
                    </div>
                    <div class="detail__form--row">
                        <label class="detail__form--label">日付</label>
                        <p class="detail__form--year">
                            {{$attendance->work_date->format('Y年')}}
                        </p>
                        <p class="detail__form--date">
                            {{$attendance->work_date->format('n月j日')}}
                        </p>
                    </div>
                    <div class="detail__form--row">
                        <label for="" class="detail__form--attendance-label">出勤・退勤</label>
                        <input type="datetime" name="clock_in_at" value="{{optional($attendance)->clock_in_at?->format('H:i')}}" class="detail__form--input">
                        <span class="input__modifier">〜</span>
                        <input type="datetime" name="clock_out_at" value="{{optional($attendance)->clock_out_at?->format('H:i')}}" class="detail__form--input">
                    </div>
                    @foreach($attendance->restBreaks as $index => $break)
                    <input type="hidden" name="break_id[]" value="{{$break->id}}">
                    <div class="detail__form--row">
                        <label for="" class="detail__form--label">休憩{{$index + 1}}</label>
                        <input type="datetime" name="break_in_at[]" value="{{$break->break_in_at?->format('H:i')}}" class="detail__form--input">
                        <span class="input__modifier">〜</span>
                        <input type="datetime" name="break_out_at[]" value="{{$break->break_out_at?->format('H:i')}}" class="detail__form--input">
                    </div>
                    @endforeach
                    <div class="detail__form--row">
                        <label for="" class="detail__form--label">休憩{{$attendance->restBreaks->count() + 1}}</label>
                        <input type="datetime" name="break_in_at[]" id="" class="detail__form--input">
                        <span class="input__modifier">〜</span>
                        <input type="datetime" name="break_out_at[]" id="" class="detail__form--input">
                    </div>
                    <div class="detail__form--row">
                        <label for="" class="detail__form--label">備考</label>
                        <textarea name="reason" id="" class="detail__form--text"></textarea>
                    </div>
                </div>
                @if($errors->any())
                <div class="form__error">
                    <ul class="form__error--list">
                        @foreach($errors->all() as $error)
                        <li class="form__error--item">
                            {{$error}}
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @if($changeRequest && $changeRequest->status === 'pending')
                <p  class="detail__form--message">*承認待ちのため修正はできません。</p>
                @else
                <div class="detail__form--submit">
                    <button type="submit" class="detail__form--button">修正</button>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection