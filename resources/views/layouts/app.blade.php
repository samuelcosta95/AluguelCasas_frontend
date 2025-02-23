<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Aluguel</title>
    <style>
    .date-input {
        margin-bottom: 10px;
    }

    .date-input label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .invalid-property {
        color: #dc3545;
        font-style: italic;
    }

    .no-properties {
        text-align: center;
        padding: 20px;
        font-size: 1.2em;
        color: #6c757d;
    }

    button[type="submit"] {
        width: 100%;
        margin-top: 10px;
    }
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .navigation {
        margin: 20px 0;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
    }

    .btn.edit {
        background: #ffc107;
        color: #000;
    }

    .btn.delete {
        background: #dc3545;
        color: white;
    }

    .btn.book {
        background: #28a745;
        color: white;
    }

    .property-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }

    .property-card {
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .owner-actions {
        margin-top: 15px;
        display: flex;
        gap: 10px;
    }

    .booking-form {
        margin-top: 15px;
        display: grid;
        gap: 10px;
    }

    input[type="date"] {
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        width: 100%;
    }

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 4px;
    }

    .alert.success {
        background: #d4edda;
        color: #155724;
    }

    .alert.error {
        background: #f8d7da;
        color: #721c24;
    }
    .property-card {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        background: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .booking-details {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #eee;
    }

    .price {
        color: #2c3e50;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .no-bookings {
        text-align: center;
        padding: 40px;
        background: #f8f9fa;
        border-radius: 8px;
        color: #6c757d;
    }

    .alert.error {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 4px;
    }
</style>
</head>
<body>
    <nav>
        @if(Session::has('api_token'))
            {{-- Usuário logado --}}
            <span>Bem-vindo, {{ Session::get('username') }}!</span>
            <a href="{{ route('home') }}" class="back-button">Home</a>
            <a href="{{ route('properties.create') }}">Anunciar Casa</a>
            <a href="{{ route('my-bookings') }}">Reservas</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">Sair</button>
            </form>
        @else
            {{-- Visitante --}}
            <a href="{{ route('login') }}">Entrar</a>
            <a href="{{ route('register') }}">Cadastrar</a>
        @endif
    </nav>

    <div class="container">
        @if(session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif
        
        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        @yield('content')
    </div>
</body>
</html>