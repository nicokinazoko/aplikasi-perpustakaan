<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
</head>

<body>
    <h2>Login</h2>

    @if ($errors->any())
        <div style="color:red">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route("login.submit") }}">
        @csrf
        <div>
            <label>Username</label>
            <input type="text" name="username" value="{{ old("username") }}" required autofocus>
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
</body>

</html>
