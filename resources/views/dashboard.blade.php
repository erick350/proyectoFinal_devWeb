@extends('layouts.app')
@section('title', 'Resumen')
@section('section', 'Resumen')
@section('content')
<div class="page-head">
    <div><div class="eyebrow">Temporada en curso</div><h1>Panel del torneo</h1><p class="subtitle">Control de jugadores, jornadas y actividad del campeonato.</p></div>
    <a href="{{ route('players.create') }}" class="button">＋ Registrar jugador</a>
</div>
<div class="grid stats">
    <div class="card stat-card"><div><div class="stat-label">Equipos inscritos</div><div class="stat-number">{{ $teamCount }}</div></div><div class="stat-icon">◈</div></div>
    <div class="card stat-card"><div><div class="stat-label">Jugadores registrados</div><div class="stat-number">{{ $playerCount }}</div></div><div class="stat-icon">♙</div></div>
    <div class="card stat-card"><div><div class="stat-label">Goles anotados</div><div class="stat-number">{{ $goalCount }}</div></div><div class="stat-icon">⚽</div></div>
    <div class="card stat-card"><div><div class="stat-label">Incidencias registradas</div><div class="stat-number">{{ $incidentCount }}</div></div><div class="stat-icon">⚑</div></div>
</div>
<div class="grid two-col">
    <section class="card">
        <div class="card-title"><h2>Tabla de goleadores</h2><a class="button secondary small" href="{{ route('reports.scorers') }}">Ver reporte</a></div>
        @if($topScorers->isEmpty())<div class="empty">Aún no hay goles registrados.</div>@else
        <div class="table-wrap"><table><thead><tr><th>Jugador</th><th>Equipo</th><th>Goles</th></tr></thead><tbody>
        @foreach($topScorers as $player)<tr><td><a class="person" href="{{ route('players.show', $player) }}">@if($player->photo_path)<img class="avatar" src="{{ Storage::disk('public')->url($player->photo_path) }}" alt="Foto de {{ $player->first_name }}">@else<span class="avatar placeholder">{{ mb_substr($player->first_name, 0, 1) }}</span>@endif{{ $player->first_name }} {{ $player->last_name }}</a></td><td>{{ $player->team->name }}</td><td><span class="badge green">{{ $player->goals_sum_quantity ?? 0 }}</span></td></tr>@endforeach
        </tbody></table></div>@endif
    </section>
    <section class="card">
        <div class="card-title"><h2>Próximas jornadas</h2><a class="button secondary small" href="{{ route('matchdays.index') }}">Calendario</a></div>
        @forelse($upcomingMatchdays as $matchday)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid var(--line)"><div><strong>Jornada {{ $matchday->number }}</strong><div class="muted" style="font-size:12px">{{ $matchday->description ?: 'Fecha programada' }}</div></div><span class="badge">{{ $matchday->game_date->format('d/m/Y') }}</span></div>
        @empty<div class="empty">No hay jornadas próximas. <a href="{{ route('matchdays.index') }}" style="color:var(--green)">Configurar calendario</a></div>@endforelse
        <div class="card-title" style="margin:23px 0 6px"><h2>Incidencias recientes</h2><a class="button secondary small" href="{{ route('incidents.index') }}">Ver todas</a></div>
        @forelse($recentIncidents as $incident)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid var(--line)"><div><strong>{{ $incident->player->first_name }} {{ $incident->player->last_name }}</strong><div class="muted" style="font-size:12px">{{ $incident->player->team->name }}</div></div><span class="badge {{ $incident->card_type === 'roja' ? 'red' : 'yellow' }}">{{ ucfirst($incident->card_type) }}</span></div>
        @empty<div class="empty">Sin incidencias registradas.</div>@endforelse
    </section>
</div>
@endsection
