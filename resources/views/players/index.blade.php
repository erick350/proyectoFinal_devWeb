@extends('layouts.app')
@section('title', 'Jugadores')
@section('section', 'Jugadores')
@section('content')
<div class="page-head"><div><div class="eyebrow">Registro de participantes</div><h1>Jugadores</h1><p class="subtitle">Consulta y administra los datos de cada jugador.</p></div><a class="button" href="{{ route('players.create') }}">＋ Nuevo jugador</a></div>
<section class="card">
    <form class="filter" method="GET" action="{{ route('players.index') }}">
        <div class="field grow"><label for="q">Buscar jugador</label><input id="q" name="q" value="{{ request('q') }}" placeholder="Nombre o apellido"></div>
        <div class="field"><label for="team_id">Equipo</label><select id="team_id" name="team_id"><option value="">Todos los equipos</option>@foreach($teams as $team)<option value="{{ $team->id }}" @selected(request('team_id') == $team->id)>{{ $team->name }}</option>@endforeach</select></div>
        <button type="submit">Filtrar</button><a class="button secondary" href="{{ route('players.index') }}">Limpiar</a>
    </form>
    @if($players->isEmpty())<div class="empty">No se encontraron jugadores para estos filtros.</div>@else
    <div class="table-wrap"><table><thead><tr><th>Jugador</th><th>Equipo</th><th>Fecha de nacimiento</th><th>Goles</th><th></th></tr></thead><tbody>
    @foreach($players as $player)<tr>
        <td><a class="person" href="{{ route('players.show', $player) }}">@if($player->photo_path)<img class="avatar" src="{{ asset('storage/'.$player->photo_path) }}" alt="Foto de {{ $player->first_name }}">@else<span class="avatar placeholder">{{ mb_substr($player->first_name, 0, 1) }}</span>@endif{{ $player->first_name }} {{ $player->last_name }}</a></td>
        <td>{{ $player->team->name }}</td><td>{{ $player->birth_date->format('d/m/Y') }}</td><td>{{ $player->goals_sum_quantity ?? 0 }}</td>
        <td><div class="actions"><a class="button secondary small" href="{{ route('players.show', $player) }}">Ver</a><a class="button secondary small" href="{{ route('players.edit', $player) }}">Editar</a><form method="POST" action="{{ route('players.destroy', $player) }}" data-confirm="¿Eliminar al jugador y sus registros de goles e incidencias?">@csrf @method('DELETE')<button class="button danger small" type="submit">Eliminar</button></form></div></td>
    </tr>@endforeach
    </tbody></table></div><div class="pagination">{{ $players->links() }}</div>@endif
</section>
@endsection
