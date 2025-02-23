@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Propriedades Disponíveis</h1>
    
    @if(Session::has('success'))
        <div class="alert success">{{ Session::get('success') }}</div>
    @endif
    
    @if(Session::has('error'))
        <div class="alert error">{{ Session::get('error') }}</div>
    @endif

    <div class="navigation">
        @if(Session::has('api_token'))
            <a href="{{ route('my-properties') }}" class="btn">Minhas Propriedades</a>
            <a href="{{ route('my-bookings') }}" class="btn">Minhas Reservas</a>
            <a href="{{ route('properties.create') }}" class="btn">Anunciar Casa</a>
        @endif
    </div>

    <div class="property-list">
        @forelse($properties as $property)
            <div class="property-card">
                @if(is_array($property) && isset($property['title']))
                    <h3>{{ $property['title'] ?? 'Sem título' }}</h3>
                    <p class="price">R$ {{ number_format($property['price_per_night'] ?? 0, 2, ',', '.') }}/noite</p>
                    <p class="location">{{ $property['location'] ?? 'Localização não informada' }}</p>
                    
                    @if(Session::has('api_token'))
                        @if(isset($property['host']) && isset($property['host']['id']) && $property['host']['id'] == Session::get('user_id'))
                            <div class="owner-actions">
                                <a href="{{ route('properties.edit', $property['id'] ?? 0) }}" class="btn edit">Editar</a>
                                <form action="{{ route('properties.destroy', $property['id'] ?? 0) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn delete" 
                                        onclick="return confirm('Tem certeza que deseja excluir esta propriedade?')">
                                        Excluir
                                    </button>
                                </form>
                            </div>
                        @else
                            <form method="POST" action="{{ route('book', $property['id'] ?? 0) }}">
                                @csrf
                                <div class="booking-form">
                                    <div class="date-input">
                                        <label>Check-in:</label>
                                        <input type="date" name="check_in" required 
                                            min="{{ date('Y-m-d') }}" 
                                            placeholder="Selecione a data">
                                    </div>
                                    <div class="date-input">
                                        <label>Check-out:</label>
                                        <input type="date" name="check_out" required 
                                            min="{{ date('Y-m-d', strtotime('+1 day')) }}" 
                                            placeholder="Selecione a data">
                                    </div>
                                    <button type="submit" class="btn book">Reservar</button>
                                </div>
                            </form>
                        @endif
                    @else
                        <p class="login-msg">Faça login para reservar</p>
                    @endif
                @else
                    <p class="invalid-property">Propriedade inválida ou mal formatada</p>
                @endif
            </div>
        @empty
            <div class="no-properties">
                <p>Nenhuma propriedade disponível no momento</p>
            </div>
        @endforelse
    </div>
</div>
@endsection