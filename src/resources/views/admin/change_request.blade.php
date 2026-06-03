@extends('layout.admin_app')

@section('css')
<link rel="stylesheet" href="{{asset('css/admin/change_request.css')}}">
@endsection

@section('content')
<div class="change-request">
    <div class="change-request__group">
        <div class="change-request__heading">
            <h1 class="change-request__title">申請一覧</h1>
        </div>

        <!-- 切り替えタブ -->
        <div class="change-request__tab">
            <a href="{{route('stamp-correction-request.list', ['tab' => 'pending'])}}" class="change-request__tab--label {{$activeTab === 'pending' ? 'is-active' : ''}}">承認待ち</a>
            <a href="{{route('stamp-correction-request.list', ['tab' => 'approved'])}}" class="change-request__tab--label {{$activeTab === 'approved' ? 'is-active' : ''}}">承認済み</a>
        </div>

        <!-- 承認待ちの表示 -->
         @if($activeTab === 'pending')
        <div class="change-request__list">
            <table class="change-request__table">
                <tr class="change-request__table--row">
                    <th class="change-request__table--header">状態</th>
                    <th class="change-request__table--header">名前</th>
                    <th class="change-request__table--header">対象日時</th>
                    <th class="change-request__table--header">申請理由</th>
                    <th class="change-request__table--header">申請日時</th>
                    <th class="change-request__table--header">詳細</th>
                </tr>
                @foreach($changeRequests as $changeRequest)
                <tr class="change-request__table--row">
                    <td class="change-request__table--item">{{$changeRequest->status_label}}</td>
                    <td class="change-request__table--item">{{$changeRequest->attendance->user->name}}</td>
                    <td class="change-request__table--item">{{$changeRequest->attendance->work_date->format('Y/m/d')}}</td>
                    <td class="change-request__table--item">{{$changeRequest->reason}}</td>
                    <td class="change-request__table--item">{{$changeRequest->created_at->format('Y/m/d')}}</td>
                    <td class="change-request__table--item">
                        <a href="{{route('admin-staff.request-approval.show', $changeRequest->id)}}" class="change-request__table--detail">詳細</a>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
        @endif

        <!-- 承認済みの表示 -->
        @if($activeTab === 'approved')
        <div class="change-request__list">
            <table class="change-request__table">
                <tr class="change-request__table--row">
                    <th class="change-request__table--header">状態</th>
                    <th class="change-request__table--header">名前</th>
                    <th class="change-request__table--header">対象日時</th>
                    <th class="change-request__table--header">申請理由</th>
                    <th class="change-request__table--header">申請日時</th>
                    <th class="change-request__table--header">詳細</th>
                </tr>
                @foreach($changeRequests as $changeRequest)
                <tr class="change-request__table--row">
                    <td class="change-request__table--item">{{$changeRequest->status_label}}</td>
                    <td class="change-request__table--item">{{$changeRequest->attendance->user->name}}</td>
                    <td class="change-request__table--item">{{$changeRequest->attendance->work_date->format('Y/m/d')}}</td>
                    <td class="change-request__table--item">{{$changeRequest->reason}}</td>
                    <td class="change-request__table--item">{{$changeRequest->created_at->format('Y/m/d')}}</td>
                    <td class="change-request__table--item">
                        <a href="{{route('admin-staff.request-approval.show', $changeRequest->id)}}" class="change-request__table--detail">詳細</a>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
        @endif
    </div>
</div>
@endsection