@extends('layouts.app')
@section('title', 'Reporte de incidencias')
@section('section', 'Reporte incidencias')
@section('content')
<div class="page-head"><div><div class="eyebrow">Reportes del torneo</div><h1>Incidencias de jugadores</h1><p class="subtitle">Consulta las tarjetas registradas por equipo, jugador o tipo.</p></div><button class="button secondary" onclick="window.print()">Imprimir reporte</button></div>
<section class="card">
    <form class="filter" method="GET" action="{{ route('reports.incidents') }}">
        <div class="field"><label for="team_id">Equipo</label><select id="team_id" name="team_id"><option value="">Todos</option>@foreach($teams as $team)<option value="{{ $team->id }}" @selected(request('team_id') == $team->id)>{{ $team->name }}</option>@endforeach</select></div>
        <div class="field"><label for="player_id">Jugador</label><select id="player_id" name="player_id"><option value="">Todos</option>@foreach($players as $player)<option value="{{ $player->id }}" @selected(request('player_id') == $player->id)>{{ $player->last_name }}, {{ $player->first_name }}</option>@endforeach</select></div>
        <div class="field"><label for="card_type">Tarjeta</label><select id="card_type" name="card_type"><option value="">Todas</option><option value="amarilla" @selected(request('card_type') === 'amarilla')>Amarilla</option><option value="roja" @selected(request('card_type') === 'roja')>Roja</option></select></div>
        <button type="submit">Filtrar</button><a class="button secondary" href="{{ route('reports.incidents') }}">Limpiar</a>
    </form>
    <div class="card-title"><h2>Resultado</h2><span class="badge">{{ $incidents->count() }} incidencias</span></div>
    @if($incidents->isEmpty())<div class="empty">No se encontraron incidencias para estos filtros.</div>@else
    <div class="table-wrap"><table><thead><tr><th>Fecha</th><th>Jugador</th><th>Equipo</th><th>Tarjeta</th><th>Descripción</th><th>Suspensión hasta</th></tr></thead><tbody>
    @foreach($incidents as $incident)<tr><td>{{ $incident->incident_date->format('d/m/Y') }}</td><td><a href="{{ route('players.show', $incident->player) }}">{{ $incident->player->first_name }} {{ $incident->player->last_name }}</a></td><td>{{ $incident->player->team->name }}</td><td><span class="badge {{ $incident->card_type === 'roja' ? 'red' : 'yellow' }}">{{ ucfirst($incident->card_type) }}</span></td><td>{{ $incident->description }}</td><td>{{ $incident->suspension_until?->format('d/m/Y') ?? '—' }}</td></tr>@endforeach
    </tbody></table></div>@endif
</section>
@endsection
