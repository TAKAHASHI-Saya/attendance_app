@extends('layout.admin_app')

@section('css')
<link rel="stylesheet" href="{{asset('css/admin/login.css')}}">
@endsection

@section('content')
<div class="login">
    <div class="login__group">
        <div class="login__heading">
            <h1 class="login__heading-title">管理者ログイン</h1>
        </div>
        <form action="/login" method="post" class="login__form">
            @csrf
            <div class="login__form--item">
                <p class="login__form--title">メールアドレス</p>
                <input type="email" name="email" value="{{old('email')}}" class="login__form--input">
                <div class="login__form--error">
                    @if($errors->has('email'))
                        @foreach($errors->get('email') as $message)
                            <p>{{$message}}</p>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="login__form--item">
                <p class="login__form--title">パスワード</p>
                <input type="password" name="password" class="login__form--input">
                <div class="login__form--error">
                    @if($errors->has('password'))
                        @foreach($errors->get('password') as $message)
                            <p>{{$message}}</p>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="login__form--submit">
                <button type="submit" class="login__form--button">ログインする</button>
            </div>
        </form>
    </div>
</div>
@endsection