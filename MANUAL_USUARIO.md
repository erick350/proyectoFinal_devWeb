# Manual de usuario — Cantera

Cantera administra los equipos, jugadores y resultados de un torneo infantil. El sistema permite consultar las fichas de los jugadores y generar reportes para el torneo.

## Antes de comenzar

Necesitas la dirección web que te proporcione quien administra el sistema. Si lo ejecutas en tu computadora, sigue primero la instalación del [README](./README.md) y abre `http://127.0.0.1:8000`.

> La aplicación todavía no tiene inicio de sesión. Si se publica en Internet, usa únicamente datos ficticios hasta que se proteja el acceso. Los nombres, fechas de nacimiento y fotografías de menores son datos personales.

## Recorrido recomendado

### 1. Registrar equipos

1. Abre **Equipos** desde el menú.
2. Escribe el nombre del equipo y selecciona **Agregar equipo**.
3. Los equipos se pueden borrar solo cuando todavía no tienen jugadores asociados.

### 2. Crear las jornadas

1. Abre **Jornadas**.
2. Indica el número de jornada, la fecha del partido y, si se necesita, una descripción.
3. Selecciona **Crear jornada**.
4. No se puede borrar una jornada que ya tenga goles registrados.

Conviene registrar las jornadas antes de capturar los goles o generar la lista arbitral.

### 3. Registrar y consultar jugadores

1. En **Jugadores**, selecciona **Registrar jugador**.
2. Completa nombres, apellidos, equipo y fecha de nacimiento.
3. Si tienes autorización para usarla, puedes cargar una fotografía JPG, JPEG, PNG o WEBP de hasta 5 MB. La fotografía es opcional en el sistema.
4. Selecciona **Guardar jugador**. La ficha muestra el equipo, la fecha de nacimiento, los goles y las incidencias.
5. Usa **Editar jugador** en la ficha para modificar la información o reemplazar la fotografía.

En la lista de jugadores puedes filtrar por equipo y buscar por nombre o apellido. Desde cada fila puedes abrir la ficha.

### 4. Registrar goles

1. Abre **Goles** y selecciona **Registrar goles**.
2. Elige el jugador y la jornada; indica la cantidad anotada (de 0 a 99).
3. Guarda el registro.

Solo se permite un registro de goles por jugador y jornada. Si necesitas corregirlo, elimina el registro existente y vuelve a capturarlo. La eliminación no se puede deshacer.

### 5. Registrar incidencias

1. Abre **Incidencias** y selecciona **Registrar incidencia**.
2. Elige el jugador, describe lo ocurrido, selecciona tarjeta amarilla o roja e indica la fecha.
3. Para una tarjeta roja, también debes indicar hasta qué fecha dura la suspensión. La fecha final debe ser igual o posterior a la incidencia.
4. Guarda el registro.

La lista arbitral considera al jugador suspendido desde la fecha de la incidencia hasta la fecha final, ambas inclusive.

## Consultar, imprimir y descargar reportes

En el menú **Reportes** están disponibles:

- **Lista arbitral:** elige una jornada para calcular quién está habilitado o suspendido. Puedes limitarla a un equipo. Incluye jugador, fecha de nacimiento, equipo y fotografía si se registró.
- **Reporte de incidencias:** filtra por equipo, jugador o tipo de tarjeta; selecciona **Filtrar** para actualizar los resultados.
- **Tabla de goleadores:** totaliza los goles y ordena a los jugadores de mayor a menor.

Una vez que veas el resultado correcto:

- Selecciona **Descargar PDF** para bajar un archivo PDF con los resultados filtrados.
- Selecciona **Imprimir** para abrir el diálogo de impresión del navegador. Desde ese diálogo también puedes elegir **Guardar como PDF**.

## Datos de demostración

Al instalar el proyecto, se puede cargar un torneo ficticio con cuatro equipos, jugadores, jornadas, goles e incidencias:

```powershell
php artisan db:seed --class=DemoTournamentSeeder
```

Los registros de ejemplo son inventados y no incluyen fotografías. Puedes cargar fotografías autorizadas desde la ficha de cada jugador antes de mostrar los reportes. El seeder evita duplicar sus propios equipos, jornadas, jugadores, goles e incidencias si lo ejecutas de nuevo.

## Recomendaciones

- Revisa dos veces las fechas y los filtros antes de imprimir o descargar un reporte.
- Usa únicamente fotografías para las que tengas autorización.
- Antes de eliminar jugadores, goles o incidencias, verifica que realmente deban quitarse: esas eliminaciones no se pueden deshacer desde la aplicación.
- Pide al administrador una copia de respaldo si necesitas conservar la información del torneo.
- No compartas públicamente datos reales de menores. El sistema no tiene cuentas de usuario ni permisos de acceso.
