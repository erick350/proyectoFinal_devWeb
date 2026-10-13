<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 24px; }
        body { color: #17211c; font: 11px DejaVu Sans, sans-serif; }
        h1 { margin: 0 0 5px; font-size: 20px; }
        p { margin: 0 0 14px; color: #526158; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 7px; border: 1px solid #d8e0da; text-align: left; }
        th { background: #e7f4ec; }
        img { width: 36px; height: 36px; object-fit: cover; }
        .status { font-weight: bold; }
    </style>
</head>
<body>
    <h1>Lista para el árbitro</h1>
    <p>
        @if($selectedMatchday)
            Jornada {{ $selectedMatchday->number }} · {{ $selectedMatchday->game_date->format('d/m/Y') }}
        @else
            Jornada sin seleccionar
        @endif
        · Generado el {{ now()->format('d/m/Y H:i') }}
    </p>
    <table>
        <thead><tr><th>Foto</th><th>Jugador</th><th>Fecha de nacimiento</th><th>Equipo</th><th>Estado</th></tr></thead>
        <tbody>
        @forelse($players as $player)
            @php($suspended = in_array($player->id, $suspendedIds))
            <tr>
                <td>
                    @if($player->photo_path && Storage::disk('public')->exists($player->photo_path))
                        <img src="{{ Storage::disk('public')->path($player->photo_path) }}" alt="">
                    @endif
                </td>
                <td>{{ $player->first_name }} {{ $player->last_name }}</td>
                <td>{{ $player->birth_date->format('d/m/Y') }}</td>
                <td>{{ $player->team->name }}</td>
                <td class="status">
                    @if(!$selectedMatchday) Sin fecha seleccionada
                    @elseif($suspended) Suspendido
                    @else Habilitado
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="5">No hay jugadores para los filtros seleccionados.</td></tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>
