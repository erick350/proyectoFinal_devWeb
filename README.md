# Cantera · Administración de torneo infantil

Aplicación web desarrollada con Laravel 12 para administrar equipos, jugadores, jornadas, goles, incidencias disciplinarias y reportes de un torneo infantil de fútbol.

## Requisitos

- Windows 10/11 (las instrucciones siguientes usan XAMPP y PowerShell).
- XAMPP con Apache, MySQL/MariaDB y PHP 8.2 o superior.
- Extensiones PHP habilitadas: `pdo_mysql`, `fileinfo` y `gd`. Para las pruebas también se necesita `pdo_sqlite`.
- Composer 2.
- Navegador web.

El proyecto utiliza Laravel y no necesita Node.js/npm para ejecutar estas pantallas.

## 1. Iniciar MySQL y crear la base de datos

1. Abre el **Panel de control de XAMPP** e inicia **MySQL**. Apache no es necesario si utilizarás `php artisan serve`.
2. Abre <http://localhost/phpmyadmin>.
3. Crea una base de datos llamada `torneo_infantil`, con cotejamiento `utf8mb4_unicode_ci`.

También puedes crearla desde la consola de MySQL:

```sql
CREATE DATABASE torneo_infantil
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
```

## 2. Abrir el proyecto y preparar `.env`

Abre PowerShell y ve a la carpeta del proyecto:

```powershell
cd C:\xampp\htdocs\proyectoFinal_devWeb
```

Si todavía no existe `.env`, créalo a partir del archivo de ejemplo:

```powershell
Copy-Item .env.example .env
```

Si `.env` ya existe, **no lo sobrescribas**. Ábrelo y configura sus valores de base de datos:

```env
APP_NAME=Cantera
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=torneo_infantil
DB_USERNAME=root
DB_PASSWORD=
```

En la instalación predeterminada de XAMPP, el usuario suele ser `root` y la contraseña está vacía. Si configuraste una contraseña, colócala en `DB_PASSWORD`. Asegúrate de cambiar `DB_CONNECTION=sqlite` a `DB_CONNECTION=mysql`.

## 3. Instalar dependencias y preparar Laravel

Ejecuta estos comandos desde la carpeta del proyecto:

```powershell
composer install
php artisan key:generate
php artisan config:clear
php artisan migrate
php artisan storage:link
```

- `composer install` instala las dependencias PHP incluidas en `composer.lock`.
- `key:generate` crea la llave de cifrado de la aplicación.
- `migrate` crea las tablas del torneo en la base MySQL configurada.
- `storage:link` permite mostrar las fotografías guardadas en el almacenamiento público.

Las migraciones agregan las tablas necesarias sin borrar tablas existentes. **No ejecutes `php artisan migrate:fresh` en una base de datos con información que quieras conservar**, porque elimina todas las tablas.

### Si PowerShell no encuentra `php` o `composer`

Usa la ruta completa de PHP de XAMPP y asegúrate de tener Composer instalado:

```powershell
C:\xampp\php\php.exe artisan --version
C:\xampp\php\php.exe artisan migrate
```

Si Composer no está disponible en la terminal, instala Composer para Windows y selecciona `C:\xampp\php\php.exe` durante la instalación. Después abre una terminal nueva.

## 4. Ejecutar la aplicación

Inicia el servidor de desarrollo:

```powershell
php artisan serve
```

Mantén abierta esa ventana de PowerShell mientras uses la aplicación. Visita la dirección mostrada en la terminal, normalmente:

<http://127.0.0.1:8000>

Para detener el servidor, vuelve a la terminal y presiona `Ctrl+C`.

## 5. Primeros pasos dentro del sistema

Sigue este orden para habilitar las funciones:

1. En **Equipos**, registra los equipos participantes.
2. En **Jornadas**, crea las jornadas con sus fechas de juego.
3. En **Jugadores**, registra a cada participante y sube su fotografía.
4. En **Goles**, captura la cantidad anotada por jugador y jornada.
5. En **Incidencias**, registra tarjetas. Para una tarjeta roja, indica también la fecha hasta la que estará suspendido el jugador.
6. Consulta los reportes de **Lista arbitral**, **Incidencias** y **Goleadores**, o abre la ficha individual desde Jugadores.

La suspensión se cuenta desde la fecha de la incidencia hasta la fecha final, ambas inclusive. Las fotografías se almacenan en `storage/app/public/players`. No se puede borrar un equipo que tenga jugadores asociados ni una jornada que ya tenga goles.

## Pruebas automatizadas

Ejecuta:

```powershell
php artisan test
```

Las pruebas usan SQLite en memoria (configurado en `phpunit.xml`), por lo que no requieren MySQL y no modifican la base de datos del torneo. Comprueban las pantallas principales, registro de jugadores, goles e incidencias, unicidad de goles por jornada y cálculo de suspensiones en la lista arbitral.

Si falta la extensión SQLite en PHP, habilita `pdo_sqlite` en `C:\xampp\php\php.ini` y reinicia la terminal.

## Solución de problemas

- **`could not find driver` al migrar:** revisa que `pdo_mysql` esté habilitada en el `php.ini` que utiliza PHP. En XAMPP es normalmente `C:\xampp\php\php.ini`. Reinicia PowerShell y vuelve a probar.
- **`Connection refused` o error de conexión:** confirma que MySQL esté iniciado en XAMPP y que `DB_HOST`, `DB_PORT`, usuario y contraseña coincidan con tu instalación.
- **`Unknown database 'torneo_infantil'`:** crea la base de datos en phpMyAdmin y verifica `DB_DATABASE` en `.env`.
- **Laravel sigue usando SQLite u otra configuración guardada:** ejecuta `php artisan config:clear` y vuelve a ejecutar `php artisan migrate`.
- **No se muestran las fotos:** ejecuta `php artisan storage:link` desde la raíz del proyecto y confirma que existe el directorio `storage/app/public`.
- **Error de llave de aplicación:** ejecuta `php artisan key:generate`. No borres una llave existente en un entorno con datos cifrados.
- **Puerto 8000 ocupado:** inicia Laravel en otro puerto con `php artisan serve --port=8001` y abre <http://127.0.0.1:8001>.

## Tecnologías

- Laravel 12 y PHP 8.2+.
- MySQL o MariaDB.
- Blade, HTML y CSS.
