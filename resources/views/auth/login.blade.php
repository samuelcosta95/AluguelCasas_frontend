@extends('layouts.app')

@section('content')
    <h1>Login</h1>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <input type="text" name="username" placeholder="Nome de usuário" required>
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="Senha" required>
        </div>
        <button type="submit">Entrar</button>
    </form>
@endsection