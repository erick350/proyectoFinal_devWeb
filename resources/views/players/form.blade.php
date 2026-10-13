@extends('layouts.app')
@section('title', $player->exists ? 'Editar jugador' : 'Nuevo jugador')
@section('section', 'Jugadores')
@section('content')
<div class="page-head"><div><div class="eyebrow">Registro de participantes</div><h1>{{ $player->exists ? 'Editar jugador' : 'Registrar jugador' }}</h1><p class="subtitle">Completa los datos personales y selecciona su equipo.</p></div></div>
<section class="card" style="max-width:800px">
    @if($teams->isEmpty())<div class="alert error">Primero debes <a href="{{ route('teams.index') }}" style="text-decoration:underline;margin:0 4px">registrar un equipo</a> para poder inscribir jugadores.</div>@endif
    <form method="POST" action="{{ $player->exists ? route('players.update', $player) : route('players.store') }}" enctype="multipart/form-data">
        @csrf @if($player->exists)@method('PUT')@endif
        <div class="form-grid">
            <div class="field"><label for="first_name">Nombres</label><input id="first_name" name="first_name" value="{{ old('first_name', $player->first_name) }}" required maxlength="100" autocomplete="given-name">@error('first_name')<span class="error">{{ $message }}</span>@enderror</div>
            <div class="field"><label for="last_name">Apellidos</label><input id="last_name" name="last_name" value="{{ old('last_name', $player->last_name) }}" required maxlength="120" autocomplete="family-name">@error('last_name')<span class="error">{{ $message }}</span>@enderror</div>
            <div class="field"><label for="birth_date">Fecha de nacimiento</label><input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date', $player->birth_date?->format('Y-m-d')) }}" max="{{ today()->toDateString() }}" required>@error('birth_date')<span class="error">{{ $message }}</span>@enderror</div>
            <div class="field"><label for="team_id">Equipo</label><select id="team_id" name="team_id" required><option value="">Seleccionar equipo</option>@foreach($teams as $team)<option value="{{ $team->id }}" @selected(old('team_id', $player->team_id) == $team->id)>{{ $team->name }}</option>@endforeach</select>@error('team_id')<span class="error">{{ $message }}</span>@enderror</div>
            <div class="field full"><label for="photo">Fotografía <span class="muted">(JPG, PNG o WEBP; máximo 5 MB)</span></label><input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp">@error('photo')<span class="error">{{ $message }}</span>@enderror
                @if($player->photo_path)<div class="person" style="margin-top:7px"><img class="avatar" src="{{ asset('storage/'.$player->photo_path) }}" alt="Fotografía actual">Fotografía actual; subir otra la reemplazará.</div>@endif
            </div>
        </div>
        <div class="form-actions"><button type="submit" @disabled($teams->isEmpty())>{{ $player->exists ? 'Guardar cambios' : 'Registrar jugador' }}</button><a class="button secondary" href="{{ $player->exists ? route('players.show', $player) : route('players.index') }}">Cancelar</a></div>
    </form>
</section>
@endsection
