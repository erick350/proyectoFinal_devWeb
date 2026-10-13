@extends('layouts.app')
@section('title', 'Tabla de goleadores')
@section('section', 'Goleadores')
@section('content')
<div class="page-head"><div><div class="eyebrow">Reportes del torneo</div><h1>Tabla de goleadores</h1><p class="subtitle">Jugadores ordenados por la cantidad total de goles anotados.</p></div><div class="actions"><a class="button secondary" href="{{ route('reports.scorers.pdf') }}">Descargar PDF</a><button class="button secondary" onclick="window.print()">Imprimir</button></div></div>
<section class="card">
    <div class="card-title"><h2>Goles acumulados por jugador</h2><span class="badge">{{ $players->count() }} goleadores</span></div>
    @if($players->isEmpty())<div class="empty">Aún no hay goles registrados en el torneo.</div>@else
    <div class="table-wrap"><table><thead><tr><th>Posición</th><th>Jugador</th><th>Equipo</th><th>Total de goles</th></tr></thead><tbody>
    @foreach($players as $index => $player)<tr><td><span class="badge {{ $index < 3 ? 'green' : 'gray' }}">#{{ $index + 1 }}</span></td><td><a class="person" href="{{ route('players.show', $player) }}">@if($player->photo_path)<img class="avatar" src="{{ asset('storage/'.$player->photo_path) }}" alt="Foto de {{ $player->first_name }}">@else<span class="avatar placeholder">{{ mb_substr($player->first_name, 0, 1) }}</span>@endif{{ $player->first_name }} {{ $player->last_name }}</a></td><td>{{ $player->team->name }}</td><td><strong style="font-size:16px">{{ $player->goals_sum_quantity }}</strong></td></tr>@endforeach
    </tbody></table></div>@endif
</section>
<style>@media print{.sidebar,.topbar,.page-head .actions{display:none!important}.main{margin:0}.content{padding:0}.card{border:0}}</style>
@endsection
