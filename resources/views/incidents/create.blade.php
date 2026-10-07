@extends('layouts.app')
@section('title', 'Nueva incidencia')
@section('section', 'Incidencias')
@section('content')
<div class="page-head"><div><div class="eyebrow">Disciplina</div><h1>Registrar incidencia</h1><p class="subtitle">Las suspensiones por tarjeta roja se reflejan en la lista arbitral según la fecha de juego.</p></div></div>
<section class="card" style="max-width:760px">
    @if($players->isEmpty())<div class="alert error">Primero debes registrar al menos un jugador.</div>@endif
    <form method="POST" action="{{ route('incidents.store') }}">@csrf
        <div class="form-grid">
            <div class="field full"><label for="player_id">Jugador</label><select id="player_id" name="player_id" required><option value="">Selecciona al jugador</option>@foreach($players as $player)<option value="{{ $player->id }}" @selected(old('player_id', request('player_id')) == $player->id)>{{ $player->last_name }}, {{ $player->first_name }} — {{ $player->team->name }}</option>@endforeach</select>@error('player_id')<span class="error">{{ $message }}</span>@enderror</div>
            <div class="field"><label for="card_type">Tipo de tarjeta</label><select id="card_type" name="card_type" data-card-type="#suspension-field" required><option value="amarilla" @selected(old('card_type') === 'amarilla')>Amarilla</option><option value="roja" @selected(old('card_type') === 'roja')>Roja</option></select>@error('card_type')<span class="error">{{ $message }}</span>@enderror</div>
            <div class="field"><label for="incident_date">Fecha de incidencia</label><input type="date" id="incident_date" name="incident_date" value="{{ old('incident_date', today()->toDateString()) }}" required>@error('incident_date')<span class="error">{{ $message }}</span>@enderror</div>
            <div class="field full" id="suspension-field" hidden><label for="suspension_until">Suspensión hasta</label><input type="date" id="suspension_until" name="suspension_until" value="{{ old('suspension_until') }}"><div class="hint">Último día que el jugador estará suspendido, inclusive.</div>@error('suspension_until')<span class="error">{{ $message }}</span>@enderror</div>
            <div class="field full"><label for="description">Descripción</label><textarea id="description" name="description" required maxlength="2000" placeholder="Describe brevemente la incidencia">{{ old('description') }}</textarea>@error('description')<span class="error">{{ $message }}</span>@enderror</div>
        </div>
        <div class="form-actions"><button type="submit" @disabled($players->isEmpty())>Guardar incidencia</button><a class="button secondary" href="{{ route('incidents.index') }}">Cancelar</a></div>
    </form>
</section>
@endsection
