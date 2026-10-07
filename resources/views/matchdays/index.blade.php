@extends('layouts.app')
@section('title', 'Jornadas')
@section('section', 'Jornadas')
@section('content')
<div class="page-head"><div><div class="eyebrow">Calendario</div><h1>Jornadas del torneo</h1><p class="subtitle">Habilita las fechas antes de registrar los resultados.</p></div></div>
<div class="grid two-col" style="grid-template-columns:.75fr 1.25fr">
    <section class="card"><div class="card-title"><h2>Programar jornada</h2></div>
        <form method="POST" action="{{ route('matchdays.store') }}">@csrf
            <div class="form-grid">
                <div class="field"><label for="number">Número de jornada</label><input type="number" min="1" max="999" id="number" name="number" value="{{ old('number') }}" required placeholder="1">@error('number')<span class="error">{{ $message }}</span>@enderror</div>
                <div class="field"><label for="game_date">Fecha de juego</label><input type="date" id="game_date" name="game_date" value="{{ old('game_date') }}" required>@error('game_date')<span class="error">{{ $message }}</span>@enderror</div>
                <div class="field full"><label for="description">Descripción <span class="muted">(opcional)</span></label><input id="description" name="description" value="{{ old('description') }}" maxlength="180" placeholder="Ej. Fecha inaugural"></div>
            </div>
            <div class="form-actions"><button type="submit">＋ Habilitar jornada</button></div>
        </form>
    </section>
    <section class="card"><div class="card-title"><h2>Calendario habilitado</h2><span class="badge">{{ $matchdays->count() }} jornadas</span></div>
        @if($matchdays->isEmpty())<div class="empty">No hay jornadas. Agrégalas aquí para habilitar el registro de goles y los reportes arbitrales.</div>@else
        <div class="table-wrap"><table><thead><tr><th>Jornada</th><th>Fecha de juego</th><th>Descripción</th><th>Goles</th><th></th></tr></thead><tbody>
        @foreach($matchdays as $matchday)<tr><td><strong>Jornada {{ $matchday->number }}</strong></td><td>{{ $matchday->game_date->format('d/m/Y') }}</td><td>{{ $matchday->description ?: '—' }}</td><td>{{ $matchday->goals_count }}</td><td><form method="POST" action="{{ route('matchdays.destroy', $matchday) }}" data-confirm="¿Eliminar esta jornada?">@csrf @method('DELETE')<button class="button danger small" type="submit">Eliminar</button></form></td></tr>@endforeach
        </tbody></table></div>@endif
    </section>
</div>
@endsection
