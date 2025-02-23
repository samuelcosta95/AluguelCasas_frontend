@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Minhas Propriedades</h1>
    
    @if(Session::has('success'))
        <div class="alert success">{{ Session::get('success') }}</div>
    @endif
    
    @if(Session::has('error'))
        <div class="alert error">{{ Session::get('error') }}</div>
    @endif

    <div class="property-actions">
        <a href="{{ route('properties.create') }}" class="btn">Adicionar Nova Propriedade</a>
        <a href="{{ route('home') }}" class="btn">Voltar para Home</a>
    </div>

    <div class="property-list">
        @forelse($properties as $property)
            <div class="property-card">
                @if(is_array($property) && isset($property['title']))
                    <h3>{{ $property['title'] ?? 'Sem título' }}</h3>
                    <p class="price">R$ {{ number_format($property['price_per_night'] ?? 0, 2, ',', '.') }}/noite</p>
                    <p class="location">{{ $property['location'] ?? 'Localização não informada' }}</p>
                    
                    <div class="property-meta">
                        <span>Quartos: {{ $property['bedrooms'] ?? 0 }}</span>
                        <span>Reservas: {{ count($property['bookings'] ?? []) }}</span>
                    </div>

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
                    <p class="invalid-property">Propriedade inválida ou mal formatada</p>
                @endif
            </div>
        @empty
            <div class="no-properties">
                <p>Você ainda não possui propriedades cadastradas</p>
            </div>
        @endforelse
    </div>
</div>
@endsection