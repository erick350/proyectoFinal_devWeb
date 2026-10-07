@extends('layouts.app')
@section('title', 'Incidencias')
@section('section', 'Incidencias')
@section('content')
<div class="page-head"><div><div class="eyebrow">Disciplina</div><h1>Incidencias de jugadores</h1><p class="subtitle">Tarjetas y períodos de suspensión registrados durante el torneo.</p></div><a class="button" href="{{ route('incidents.create') }}">＋ Nueva incidencia</a></div>
<section class="card">
    @if($incidents->isEmpty())<div class="empty">No hay incidencias registradas.</div>@else
    <div class="table-wrap"><table><thead><tr><th>Jugador</th><th>Equipo</th><th>Tarjeta</th><th>Fecha</th><th>Descripción</th><th>Suspensión hasta</th><th></th></tr></thead><tbody>
    @foreach($incidents as $incident)<tr><td><a href="{{ route('players.show', $incident->player) }}" class="person">{{ $incident->player->first_name }} {{ $incident->player->last_name }}</a></td><td>{{ $incident->player->team->name }}</td><td><span class="badge {{ $incident->card_type === 'roja' ? 'red' : 'yellow' }}">{{ ucfirst($incident->card_type) }}</span></td><td>{{ $incident->incident_date->format('d/m/Y') }}</td><td>{{ $incident->description }}</td><td>{{ $incident->suspension_until?->format('d/m/Y') ?? '—' }}</td><td><form method="POST" action="{{ route('incidents.destroy', $incident) }}" data-confirm="¿Eliminar esta incidencia?">@csrf @method('DELETE')<button class="button danger small" type="submit">Eliminar</button></form></td></tr>@endforeach
    </tbody></table></div><div class="pagination">{{ $incidents->links() }}</div>@endif
</section>
@endsection
