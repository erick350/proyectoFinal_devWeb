@extends('layouts.app')
@section('title', 'Lista arbitral')
@section('section', 'Lista arbitral')
@section('content')
<div class="page-head"><div><div class="eyebrow">Reportes del torneo</div><h1>Lista para el árbitro</h1><p class="subtitle">Jugadores por equipo, fotografía y elegibilidad para la jornada elegida.</p></div><div class="actions"><a class="button secondary" href="{{ route('reports.referee.pdf', request()->query()) }}">Descargar PDF</a><button class="button secondary" onclick="window.print()">Imprimir</button></div></div>
<section class="card">
    <form class="filter" method="GET" action="{{ route('reports.referee') }}">
        <div class="field"><label for="matchday_id">Jornada / fecha</label><select id="matchday_id" name="matchday_id"><option value="">Seleccionar fecha</option>@foreach($matchdays as $matchday)<option value="{{ $matchday->id }}" @selected(request('matchday_id') == $matchday->id)>Jornada {{ $matchday->number }} — {{ $matchday->game_date->format('d/m/Y') }}</option>@endforeach</select></div>
        <div class="field"><label for="team_id">Equipo</label><select id="team_id" name="team_id"><option value="">Todos los equipos</option>@foreach($teams as $team)<option value="{{ $team->id }}" @selected($selectedTeam == $team->id)>{{ $team->name }}</option>@endforeach</select></div>
        <button type="submit">Generar lista</button><a class="button secondary" href="{{ route('reports.referee') }}">Limpiar</a>
    </form>
    @if($selectedMatchday)<div class="alert success">Jornada {{ $selectedMatchday->number }} · {{ $selectedMatchday->game_date->format('d/m/Y') }} — {{ count($suspendedIds) }} jugador(es) suspendido(s).</div>@else<div class="alert" style="background:#fff7e5;color:#855b10">Selecciona una jornada para calcular el estado de suspensión de cada jugador.</div>@endif
    @if($players->isEmpty())<div class="empty">No hay jugadores inscritos para este filtro.</div>@else
    <div class="table-wrap"><table><thead><tr><th>Jugador</th><th>Fecha de nacimiento</th><th>Equipo</th><th>Estado para la jornada</th></tr></thead><tbody>
    @foreach($players as $player)@php($suspended = in_array($player->id, $suspendedIds))<tr>
        <td><div class="person">@if($player->photo_path)<img class="avatar" src="{{ asset('storage/'.$player->photo_path) }}" alt="Foto de {{ $player->first_name }}">@else<span class="avatar placeholder">{{ mb_substr($player->first_name, 0, 1) }}</span>@endif{{ $player->first_name }} {{ $player->last_name }}</div></td>
        <td>{{ $player->birth_date->format('d/m/Y') }}</td><td>{{ $player->team->name }}</td><td>@if(!$selectedMatchday)<span class="badge gray">Sin fecha seleccionada</span>@elseif($suspended)<span class="badge red">Suspendido</span>@else<span class="badge green">Habilitado</span>@endif</td>
    </tr>@endforeach
    </tbody></table></div>@endif
</section>
<style>@media print{.sidebar,.topbar,.page-head .actions,.filter,.button{display:none!important}.main{margin:0}.content{padding:0}.card{border:0}.alert{border:1px solid #ccc}}</style>
@endsection
