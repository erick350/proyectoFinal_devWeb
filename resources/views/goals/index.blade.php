@extends('layouts.app')
@section('title', 'Goles')
@section('section', 'Goles')
@section('content')
<div class="page-head"><div><div class="eyebrow">Resultados</div><h1>Registro de goles</h1><p class="subtitle">Goles anotados por jugador en cada jornada.</p></div><a class="button" href="{{ route('goals.create') }}">＋ Registrar goles</a></div>
<section class="card">
    @if($goals->isEmpty())<div class="empty">Todavía no hay goles registrados. Registra goles cuando el calendario esté habilitado.</div>@else
    <div class="table-wrap"><table><thead><tr><th>Jugador</th><th>Equipo</th><th>Jornada</th><th>Fecha</th><th>Goles</th><th></th></tr></thead><tbody>
    @foreach($goals as $goal)<tr><td><a class="person" href="{{ route('players.show', $goal->player) }}">@if($goal->player->photo_path)<img class="avatar" src="{{ asset('storage/'.$goal->player->photo_path) }}" alt="">@else<span class="avatar placeholder">{{ mb_substr($goal->player->first_name, 0, 1) }}</span>@endif{{ $goal->player->first_name }} {{ $goal->player->last_name }}</a></td><td>{{ $goal->player->team->name }}</td><td>Jornada {{ $goal->matchday->number }}</td><td>{{ $goal->matchday->game_date->format('d/m/Y') }}</td><td><span class="badge green">{{ $goal->quantity }}</span></td><td><form method="POST" action="{{ route('goals.destroy', $goal) }}" data-confirm="¿Eliminar este registro de goles?">@csrf @method('DELETE')<button class="button danger small" type="submit">Eliminar</button></form></td></tr>@endforeach
    </tbody></table></div><div class="pagination">{{ $goals->links() }}</div>@endif
</section>
@endsection
