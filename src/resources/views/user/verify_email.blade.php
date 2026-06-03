<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メール認証</title>
    <link rel="stylesheet" href="{{asset('css/layout/sanitize.css')}}">
    <link rel="stylesheet" href="{{asset('css/user/verify_email.css')}}">
</head>
<body>
    <div class="content">
        <div class="content__inner">
            <h1 class="content__title">メール認証を行ってください</h1>
            <p class="content__message">
                登録したメールアドレスに認証メールを送信しました。<br>
                メール内のリンクをクリックして認証を完了してください。
            </p>
            <form action="{{route('verification.send')}}" method="post" class="content__form">
                @csrf
                <button type="submit" class="content__button">認証メールを再送する</button>  
            </form>
        </div>
    </div>
</body>
</html>