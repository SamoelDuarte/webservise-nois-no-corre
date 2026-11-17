@extends('admin.layout')

@section('title', 'Login administrativo')

@section('content')
    <h1>Login administrativo</h1>
    <form method="post" action="{{ route('login') }}">
        @csrf
        @if ($errors->any())
            <p class="error" role="alert">{{ $errors->first() }}</p>
        @endif
        <label for="email">E-mail</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required>

        <label for="password">Senha</label>
        <input id="password" name="password" type="password" autocomplete="current-password" required>

        <button type="submit">Entrar</button>
    </form>
@endsection
