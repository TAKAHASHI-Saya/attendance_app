<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtech勤怠管理アプリ</title>
    <link rel="stylesheet" href="{{asset('css/layout/sanitize.css')}}">
    <link rel="stylesheet" href="{{asset('css/layout/user_common.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @yield('css')
</head>
<body>
    <header class="header">
        <div class="header__heading">
            <a href="" class="header__link">
                <img src="/images/header_logo.png" alt="タイトルロゴ" class="header__logo">
            </a>
        </div>
        @auth
        <div class="header__menu">
            <nav class="header__nav">
                <ul class="header__nav-list">
                    <li class="header__nav-item">
                        <a href="{{route('user-attendance.index')}}" class="header__nav-link">勤怠</a>
                    </li>
                    <li class="header__nav-item">
                        <a href="{{route('user-attendance.record')}}" class="header__nav-link">勤怠一覧</a>
                    </li>
                    <li class="header__nav-item">
                        <a href="{{route('stamp-correction-request.list')}}" class="header__nav-link">申請</a>
                    </li>
                    <li class="header__nav-item">
                        <form action="/logout" method="post" class="header__nav-link">
                            @csrf
                            <button type=submit  class="nav__button">ログアウト</button>
                        </form>
                    </li>
                </ul>
            </nav>
        </div>
        @endauth
    </header>
    <main>
        @yield('content')
    </main>
</body>
</html>