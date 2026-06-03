@extends('layout.user_app')

@section('css')
<link rel="stylesheet" href="{{asset('css/user/register.css')}}">
@endsection

@section('content')
<div class="register">
    <div class="register__group">
        <div class="register__heading">
            <h1 class="register__heading-title">会員登録</h1>
        </div>
        <form action="/register" method="post" class="register__form">
            @csrf
            <div class="register__form--item">
                <p class="register__form--title">名前</p>
                <input type="text" name="name" value="{{old('name')}}" class="register__form--input">
                <div class="register__form--error">
                    @if($errors->has('name'))
                        @foreach($errors->get('name') as $message)
                            <p>{{$message}}</p>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="register__form--item">
                <p class="register__form--title">メールアドレス</p>
                <input type="email" name="email" value="{{old('email')}}" class="register__form--input">
                <div class="register__form--error">
                    @if($errors->has('email'))
                        @foreach($errors->get('email') as $message)
                            <p>{{$message}}</p>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="register__form--item">
                <p class="register__form--title">パスワード</p>
                <input type="password" name="password" class="register__form--input">
                <div class="register__form--error">
                    @if($errors->has('password'))
                        @foreach($errors->get('password') as $message)
                            <p>{{$message}}</p>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="register__form--item">
                <p class="register__form--title">パスワード確認</p>
                <input type="password" name="password_confirmation" class="register__form--input">
                <div class="register__form--error">
                    @if($errors->has('password'))
                        @foreach($errors->get('password') as $message)
                            <p>{{$message}}</p>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="register__form--submit">
                <button type="submit" class="register__form--button">登録する</button>
            </div>
        </form>
        <div class="register__link">
            <a href="{{route('login')}}" class="register__link--login">ログインはこちら</a>
        </div>
    </div>
</div>
@endsection