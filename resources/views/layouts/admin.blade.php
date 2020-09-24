<!DOCTYPE html>
<html lang="ru">
<head>
    <title>@yield('title', 'VSTU')</title>

    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link type="text/css" rel="stylesheet" href="{{ mix('/css/app.css') }}">
</head>
<body>

@yield('content')

</body>
</html>
