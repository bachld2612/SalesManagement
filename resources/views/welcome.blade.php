<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <a href="{{route('login')}}">Log in</a>
    <a href="{{route('register')}}">Register</a>
    <form method="post" action="{{route('logout')}}">
        @csrf
        @if (auth()->check())
            <button type="submit">Logout</button>
        
        @endif
    </form>
</body>
</html>