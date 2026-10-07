@extends('layouts.app')
@section('title', 'Equipos')
@section('section', 'Equipos')
@section('content')
<div class="page-head"><div><div class="eyebrow">Catálogos</div><h1>Equipos</h1><p class="subtitle">Administra los equipos participantes del torneo.</p></div></div>
<div class="grid two-col" style="grid-template-columns:.75fr 1.25fr">
    <section class="card"><div class="card-title"><h2>Nuevo equipo</h2></div>
        <form method="POST" action="{{ route('teams.store') }}">@csrf
            <div class="field"><label for="name">Nombre del equipo</label><input id="name" name="name" value="{{ old('name') }}" required maxlength="120" placeholder="Ej. Tigres FC"><div class="hint">El nombre debe ser único dentro del torneo.</div>@error('name')<span class="error">{{ $message }}</span>@enderror</div>
            <div class="form-actions"><button type="submit">Guardar equipo</button></div>
        </form>
    </section>
    <section class="card"><div class="card-title"><h2>Equipos inscritos</h2><span class="badge">{{ $teams->count() }} equipos</span></div>
        @if($teams->isEmpty())<div class="empty">Aún no se han registrado equipos.</div>@else
        <div class="table-wrap"><table><thead><tr><th>Equipo</th><th>Jugadores</th><th></th></tr></thead><tbody>
        @foreach($teams as $team)<tr><td><div class="person"><span class="avatar placeholder">◈</span>{{ $team->name }}</div></td><td>{{ $team->players_count }}</td><td><form method="POST" action="{{ route('teams.destroy', $team) }}" data-confirm="¿Eliminar este equipo?">@csrf @method('DELETE')<button class="button danger small" type="submit">Eliminar</button></form></td></tr>@endforeach
        </tbody></table></div>@endif
    </section>
</div>
@endsection
