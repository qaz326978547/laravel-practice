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
    <p>您的驗證碼是
        <span>
            {{ $code }}
        </span>
    </p>
</body>



</html>