@extends('layouts.app')
@section('title', 'Registrar goles')
@section('section', 'Goles')
@section('content')
<div class="page-head"><div><div class="eyebrow">Resultados</div><h1>Registrar goles</h1><p class="subtitle">Indica el jugador, la jornada y la cantidad anotada.</p></div></div>
<section class="card" style="max-width:720px">
    @if($players->isEmpty() || $matchdays->isEmpty())<div class="alert error">Para registrar goles necesitas al menos un jugador y una jornada habilitada.</div>@endif
    <form method="POST" action="{{ route('goals.store') }}">@csrf
        <div class="form-grid">
            <div class="field full"><label for="player_id">Jugador</label><select id="player_id" name="player_id" required><option value="">Selecciona al jugador</option>@foreach($players as $player)<option value="{{ $player->id }}" @selected(old('player_id', request('player_id')) == $player->id)>{{ $player->last_name }}, {{ $player->first_name }} — {{ $player->team->name }}</option>@endforeach</select>@error('player_id')<span class="error">{{ $message }}</span>@enderror</div>
            <div class="field"><label for="matchday_id">Jornada</label><select id="matchday_id" name="matchday_id" required><option value="">Selecciona la jornada</option>@foreach($matchdays as $matchday)<option value="{{ $matchday->id }}" @selected(old('matchday_id') == $matchday->id)>Jornada {{ $matchday->number }} — {{ $matchday->game_date->format('d/m/Y') }}</option>@endforeach</select>@error('matchday_id')<span class="error">{{ $message }}</span>@enderror</div>
            <div class="field"><label for="quantity">Cantidad de goles</label><input type="number" id="quantity" name="quantity" min="0" max="99" value="{{ old('quantity', 1) }}" required>@error('quantity')<span class="error">{{ $message }}</span>@enderror</div>
        </div>
        <div class="form-actions"><button type="submit" @disabled($players->isEmpty() || $matchdays->isEmpty())>Guardar goles</button><a class="button secondary" href="{{ route('goals.index') }}">Cancelar</a></div>
    </form>
</section>
@endsection
