<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ALIMA GATEWAY</title>
    <style>
        body{margin:0;min-height:100vh;display:grid;place-items:center;font-family:Segoe UI,Tahoma,sans-serif;background:#edf4ff}
        .card{width:min(420px,92vw);background:#fff;border:1px solid #d7e5fa;border-radius:14px;padding:18px}
        .brand{display:flex;align-items:center;gap:10px;margin:0 0 14px}
        .brand img{width:42px;height:42px;border-radius:10px}
        label{display:block;font-size:13px;margin-bottom:6px;color:#4a6486;font-weight:600}
        input{width:100%;padding:10px;border:1px solid #bfd4f3;border-radius:10px}
        button{width:100%;margin-top:12px;border:0;padding:10px;border-radius:10px;background:#1d6ff2;color:#fff;font-weight:700;cursor:pointer}
        .err{background:#fff2f2;border:1px solid #ffd4d4;color:#a00000;padding:8px;border-radius:10px;margin-bottom:10px}
    </style>
</head>
<body>
<form class="card" method="post" action="{{ route('login.post') }}">
    @csrf
    <div class="brand">
        <img src="{{ asset('assets/alima-gateway-logo.svg') }}" alt="ALIMA GATEWAY">
        <h2 style="margin:0">ALIMA GATEWAY</h2>
    </div>
    @if($errors->any())
        <div class="err">{{ $errors->first() }}</div>
    @endif
    <label>Username</label>
    <input name="username" value="{{ old('username') }}" required>
    <div style="height:10px"></div>
    <label>Password</label>
    <input name="password" type="password" required>
    <button type="submit">Login</button>
</form>
</body>
</html>

