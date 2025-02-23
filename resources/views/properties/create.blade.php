@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Anunciar Nova Propriedade</h1>
    
    @if ($errors->any())
        <div class="alert error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('properties.store') }}">
        @csrf
        
        <div class="form-group">
            <label>Título:</label>
            <input type="text" name="title" value="{{ old('title') }}" required>
        </div>
        
        <div class="form-group">
            <label>Descrição:</label>
            <textarea name="description" required>{{ old('description') }}</textarea>
        </div>
        
        <div class="form-group">
            <label>Preço por Noite (R$):</label>
            <input type="number" name="price" value="{{ old('price') }}" step="0.01" required>
        </div>
        
        <div class="form-group">
            <label>Número de Quartos:</label>
            <input type="number" name="bedrooms" value="{{ old('bedrooms') }}" required>
        </div>
        
        <div class="form-group">
            <label>Localização:</label>
            <input type="text" name="location" value="{{ old('location') }}" required>
        </div>
        
        <button type="submit" class="btn">Publicar Propriedade</button>
        <a href="{{ route('home') }}" class="btn cancel">Cancelar</a>
    </form>
</div>
@endsection