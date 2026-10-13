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
    </style>
</head>
<body>
    <h1>Reporte de incidencias</h1>
    <p>Generado el {{ now()->format('d/m/Y H:i') }}</p>
    <table>
        <thead><tr><th>Fecha</th><th>Jugador</th><th>Equipo</th><th>Tarjeta</th><th>Descripción</th><th>Suspensión hasta</th></tr></thead>
        <tbody>
        @forelse($incidents as $incident)
            <tr>
                <td>{{ $incident->incident_date->format('d/m/Y') }}</td>
                <td>{{ $incident->player->first_name }} {{ $incident->player->last_name }}</td>
                <td>{{ $incident->player->team->name }}</td>
                <td>{{ ucfirst($incident->card_type) }}</td>
                <td>{{ $incident->description }}</td>
                <td>{{ $incident->suspension_until?->format('d/m/Y') ?? '—' }}</td>
            </tr>
        @empty
            <tr><td colspan="6">No hay incidencias para los filtros seleccionados.</td></tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>
