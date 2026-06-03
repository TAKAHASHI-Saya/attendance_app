@extends('layout.admin_app')

@section('css')
<link rel="stylesheet" href="{{asset('css/admin/staff_list.css')}}">
@endsection

@section('content')
<div class="staff-list">
    <div class="staff-list__group">
        <div class="staff-list__heading">
            <h1 class="staff-list__title">スタッフ一覧</h1>
        </div>
        <div class="staff-list__field">
            <table class="staff-list__table">
                <tr class="staff-list__table--row">
                    <th class="staff-list__table--header">名前</th>
                    <th class="staff-list__table--header">メールアドレス</th>
                    <th class="staff-list__table--header">月次勤怠</th>
                </tr>
                @foreach($staffs as $staff)
                <tr class="staff-list__table--row">
                    <td class="staff-list__table--item">
                        {{$staff->name}}
                    </td>
                    <td class="staff-list__table--item">
                        {{$staff->email}}
                    </td>
                    <td class="staff-list__table--item">
                        <a href="{{route('admin-staff.attendance.show', $staff->id)}}" class="staff-list__table--detail">詳細</a>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
@endsection