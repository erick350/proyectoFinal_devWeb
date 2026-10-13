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
    </style>
</head>
<body>
    <h1>Tabla de goleadores</h1>
    <p>Generado el {{ now()->format('d/m/Y H:i') }}</p>
    <table>
        <thead><tr><th>Posición</th><th>Foto</th><th>Jugador</th><th>Equipo</th><th>Total de goles</th></tr></thead>
        <tbody>
        @forelse($players as $index => $player)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    @if($player->photo_path && Storage::disk('public')->exists($player->photo_path))
                        <img src="{{ Storage::disk('public')->path($player->photo_path) }}" alt="">
                    @endif
                </td>
                <td>{{ $player->first_name }} {{ $player->last_name }}</td>
                <td>{{ $player->team->name }}</td>
                <td>{{ $player->goals_sum_quantity }}</td>
            </tr>
        @empty
            <tr><td colspan="5">Aún no hay goles registrados en el torneo.</td></tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>
