<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>信箱驗證</title>
    <style>
        span {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <p>Hello! {{ $name }}</p>
    <form method="POST" action="http://127.0.0.1:9001/api/v2/auth/email/verify-verification-code?user_id={{$userId}}&code={{$code}}">
        @csrf
        <input type="hidden" name="user_id" value="{{$userId}}">
        <input type="hidden" name="code" value="{{$code}}">
        <button type="submit">驗證</button>
    </form>
    <p>請點選驗證按鈕完成驗證</p>
    <p>並在 10 分鐘內完成驗證</p>
    <p>若非本人操作，請忽略此信</p>
    <p>沒收到驗證碼?</p>
    <p>請點選以下連結重新發送驗證碼</p>
    <form method="POST" action="http://127.0.0.1:9001/api/v2/auth/email/resend-verification-email?email={{$email}}">
        @csrf
        <input type="hidden" name="email" value="{{$email}}">
        <button type="submit">重新發送驗證碼</button>
    </form>    
</body>



</html>