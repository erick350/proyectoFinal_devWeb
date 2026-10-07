# Cantera · Administración de torneo infantil

Aplicación Laravel 12 para administrar jugadores, equipos, jornadas, goles, incidencias disciplinarias y reportes de un torneo infantil de fútbol.

## Requisitos

- PHP 8.2 o superior con extensiones PDO MySQL, fileinfo y GD.
- Composer.
- MySQL 8 o MariaDB compatible.

## Instalación

1. Crea una base de datos vacía, por ejemplo `torneo_infantil`.
2. Copia `.env.example` a `.env` (o ajusta las variables de conexión que ya tengas):

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=torneo_infantil
   DB_USERNAME=root
   DB_PASSWORD=
   ```

3. Instala dependencias, genera la llave, aplica las migraciones y prepara el almacenamiento público:

   ```sh
   composer install
   php artisan key:generate
   php artisan migrate
   php artisan storage:link
   php artisan serve
   ```

4. Abre la URL indicada por `artisan serve`. Registra primero los equipos y las jornadas para habilitar la inscripción de jugadores y la captura de goles.

## Funcionalidades

- Catálogo de equipos y calendario de jornadas.
- Registro, consulta y edición de jugadores, incluyendo fotografía.
- Captura de goles por jugador y jornada; un registro por jugador en cada jornada.
- Registro de tarjetas amarillas y rojas. Las rojas requieren una fecha final de suspensión.
- Lista arbitral por jornada, con fotografías y estado de suspensión calculado para la fecha seleccionada.
- Reporte filtrable de incidencias por equipo, jugador y tipo de tarjeta.
- Tabla de goleadores descendente con fotografía y equipo.
- Ficha individual del jugador con datos personales, goles e incidencias.

Las fotografías se guardan en `storage/app/public/players`. Un jugador con registros asociados no puede eliminarse de su equipo; las jornadas con goles tampoco pueden borrarse para preservar la integridad del torneo.

## Pruebas

```sh
php artisan test
```
