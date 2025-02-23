@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Minhas Reservas</h1>
    
    @if(Session::has('success'))
        <div class="alert success">{{ Session::get('success') }}</div>
    @endif
    
    @if(Session::has('error'))
        <div class="alert error">{{ Session::get('error') }}</div>
    @endif

    @forelse($bookings as $booking)
        @php
            // Convert para array se for objeto
            $booking = (array)$booking;
            $property = Http::get(config('services.api.url') . 'properties/' . $booking['property'])->json();
        @endphp
        
        <div class="property-card">
            @if(is_array($property) && isset($property['title']))
                <h2>{{ $property['title'] ?? 'Propriedade Desconhecida' }}</h2>
                <p class="price">Preço Diário: R$ {{ number_format($property['price_per_night'] ?? 0, 2, ',', '.') }}</p>
            @else
                <p class="error">Erro ao carregar dados da propriedade</p>
            @endif
            
            <div class="booking-details">
                <p><strong>Check-in:</strong> {{ \Carbon\Carbon::parse($booking['check_in'])->format('d/m/Y') }}</p>
                <p><strong>Check-out:</strong> {{ \Carbon\Carbon::parse($booking['check_out'])->format('d/m/Y') }}</p>
                <p><strong>Total:</strong> R$ {{ number_format($booking['total_price'] ?? 0, 2, ',', '.') }}</p>
            </div>
        </div>
    @empty
        <div class="no-bookings">
            <p>Você não tem reservas ativas</p>
        </div>
    @endforelse
</div>
@endsection