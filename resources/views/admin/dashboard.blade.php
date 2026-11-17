@extends('admin.layout')

@section('title', 'Painel administrativo')

@section('content')
    <h1>Painel administrativo</h1>
    <p>Olá, {{ auth()->user()->name }}.</p>
    <form method="post" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Sair</button>
    </form>
@endsection
