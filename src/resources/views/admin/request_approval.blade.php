@extends('layout.admin_app')

@section('css')
<link rel="stylesheet" href="{{asset('css/admin/request_approval.css')}}">
@endsection

@section('content')
<div class="request-approval">
    <div class="request-approval__group">
        <div class="request-approval__heading">
            <h1 class="request-approval__title">勤怠詳細</h1>
        </div>
        <div class="request-approval__field">
            <table class="request-approval__table">
                <tr class="request-approval__table--row">
                    <th class="request-approval__table--header">名前</th>
                    <td class="request-approval__table--item">
                        {{$changeRequest->attendance->user->name}}
                    </td>
                </tr>
                <tr class="request-approval__table--row">
                    <th class="request-approval__table--header">日付</th>
                    <td class="request-approval__table--item">
                        <p class="request-approval__table--text">
                            {{$changeRequest->attendance->work_date->format('Y年')}}
                        </p>
                        <p class="request-approval__table--text">
                            {{$changeRequest->attendance->work_date->format('n月j日')}}
                        </p>
                    </td>
                </tr>
                <tr class="request-approval__table--row">
                    <th class="request-approval__table--header">出勤・退勤</th>
                    <td class="request-approval__table--item">
                        <p class="request-approval__table--text">
                            {{$changeRequest->after_clock_in_at->format('H:i')}}
                        </p>
                        <span>〜</span>
                        <p class="request-approval__table--text">
                            {{$changeRequest->after_clock_out_at->format('H:i')}}
                        </p>
                    </td>
                </tr>
                @foreach($changeRequest->breakChangeRequests as $index => $break)
                <tr class="request-approval__table--row">
                    <th class="request-approval__table--header">休憩{{$index + 1}}</th>
                    <td class="request-approval__table--item">
                        <p class="request-approval__table--text">
                            {{$break->after_break_in_at?->format('H:i')}}
                        </p>
                        <span>〜</span>
                        <p class="request-approval__table--text">
                            {{$break->after_break_out_at?->format('H:i')}}
                        </p>
                    </td>
                </tr>
                @endforeach
                <tr class="request-approval__table--row">
                    <th class="request-approval__table--header">備考</th>
                    <td class="request-approval__table--item">
                        {{$changeRequest->reason}}
                    </td>
                </tr>
            </table>
        </div>
        @if($changeRequest->status === 'approved')
        <div class="request-approval__form">
            <button type="button" class="request-approval__button--accept" disabled>承認済み</button>
        </div>
        @else
        <form action="{{route('admin-staff.request-approval.update', $changeRequest->id)}}" method="post" class="request-approval__form">
            @csrf
            @method('PATCH')
            <button type="submit" class="request-approval__button">承認</button>
        </form>
        @endif
    </div>
</div>
@endsection