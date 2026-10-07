@extends('layouts.app')
@section('title', $player->first_name.' '.$player->last_name)
@section('section', 'Ficha del jugador')
@section('content')
<div class="page-head"><div><div class="eyebrow">Ficha individual</div><h1>{{ $player->first_name }} {{ $player->last_name }}</h1><p class="subtitle">{{ $player->team->name }}</p></div><div class="actions"><a class="button secondary" href="{{ route('players.index') }}">Volver</a><a class="button" href="{{ route('players.edit', $player) }}">Editar jugador</a></div></div>
<section class="card profile">
    @if($player->photo_path)<img class="profile-photo" src="{{ Storage::disk('public')->url($player->photo_path) }}" alt="Fotografía de {{ $player->first_name }} {{ $player->last_name }}">@else<div class="profile-photo profile-placeholder">♙</div>@endif
    <div><div class="eyebrow">Información personal</div><div class="detail-grid">
        <div><div class="detail-label">Nombre completo</div><div class="detail-value">{{ $player->first_name }} {{ $player->last_name }}</div></div>
        <div><div class="detail-label">Equipo</div><div class="detail-value">{{ $player->team->name }}</div></div>
        <div><div class="detail-label">Fecha de nacimiento</div><div class="detail-value">{{ $player->birth_date->format('d/m/Y') }}</div></div>
        <div><div class="detail-label">Goles en el torneo</div><div class="detail-value">{{ $player->goals->sum('quantity') }}</div></div>
        <div><div class="detail-label">Incidencias</div><div class="detail-value">{{ $player->incidents->count() }}</div></div>
        <div><div class="detail-label">Registro creado</div><div class="detail-value">{{ $player->created_at->format('d/m/Y') }}</div></div>
    </div></div>
</section>
<div class="grid two-col" style="margin-top:18px">
    <section class="card"><div class="card-title"><h2>Goles por jornada</h2><a class="button secondary small" href="{{ route('goals.create') }}">＋ Registrar</a></div>
        @if($player->goals->isEmpty())<div class="empty">Este jugador aún no tiene goles registrados.</div>@else
        <div class="table-wrap"><table><thead><tr><th>Jornada</th><th>Fecha</th><th>Goles</th></tr></thead><tbody>@foreach($player->goals->sortBy(fn($goal) => $goal->matchday->number) as $goal)<tr><td>Jornada {{ $goal->matchday->number }}</td><td>{{ $goal->matchday->game_date->format('d/m/Y') }}</td><td><span class="badge green">{{ $goal->quantity }}</span></td></tr>@endforeach</tbody></table></div>@endif
    </section>
    <section class="card"><div class="card-title"><h2>Incidencias</h2><a class="button secondary small" href="{{ route('incidents.create') }}">＋ Registrar</a></div>
        @if($player->incidents->isEmpty())<div class="empty">Este jugador no tiene incidencias.</div>@else
        <div class="table-wrap"><table><thead><tr><th>Fecha</th><th>Tarjeta</th><th>Detalle / suspensión</th></tr></thead><tbody>@foreach($player->incidents as $incident)<tr><td>{{ $incident->incident_date->format('d/m/Y') }}</td><td><span class="badge {{ $incident->card_type === 'roja' ? 'red' : 'yellow' }}">{{ ucfirst($incident->card_type) }}</span></td><td>{{ $incident->description }}@if($incident->suspension_until)<div class="muted" style="font-size:11px">Suspendido hasta {{ $incident->suspension_until->format('d/m/Y') }}</div>@endif</td></tr>@endforeach</tbody></table></div>@endif
    </section>
</div>
@endsection
