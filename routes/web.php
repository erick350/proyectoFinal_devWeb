<?php

use App\Http\Controllers\TournamentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TournamentController::class, 'dashboard'])->name('dashboard');

Route::get('/equipos', [TournamentController::class, 'teams'])->name('teams.index');
Route::post('/equipos', [TournamentController::class, 'storeTeam'])->name('teams.store');
Route::delete('/equipos/{team}', [TournamentController::class, 'destroyTeam'])->name('teams.destroy');

Route::get('/jornadas', [TournamentController::class, 'matchdays'])->name('matchdays.index');
Route::post('/jornadas', [TournamentController::class, 'storeMatchday'])->name('matchdays.store');
Route::delete('/jornadas/{matchday}', [TournamentController::class, 'destroyMatchday'])->name('matchdays.destroy');

Route::get('/jugadores/crear', [TournamentController::class, 'create'])->name('players.create');
Route::resource('jugadores', TournamentController::class)->parameters(['jugadores' => 'player'])
    ->only(['index', 'store', 'show', 'edit', 'update', 'destroy'])
    ->names('players');

Route::get('/goles', [TournamentController::class, 'goals'])->name('goals.index');
Route::get('/goles/crear', [TournamentController::class, 'createGoal'])->name('goals.create');
Route::post('/goles', [TournamentController::class, 'storeGoal'])->name('goals.store');
Route::delete('/goles/{goal}', [TournamentController::class, 'destroyGoal'])->name('goals.destroy');

Route::get('/incidencias', [TournamentController::class, 'incidents'])->name('incidents.index');
Route::get('/incidencias/crear', [TournamentController::class, 'createIncident'])->name('incidents.create');
Route::post('/incidencias', [TournamentController::class, 'storeIncident'])->name('incidents.store');
Route::delete('/incidencias/{incident}', [TournamentController::class, 'destroyIncident'])->name('incidents.destroy');

Route::get('/reportes/arbitro', [TournamentController::class, 'refereeReport'])->name('reports.referee');
Route::get('/reportes/arbitro/pdf', [TournamentController::class, 'downloadRefereeReport'])->name('reports.referee.pdf');
Route::get('/reportes/incidencias', [TournamentController::class, 'incidentReport'])->name('reports.incidents');
Route::get('/reportes/incidencias/pdf', [TournamentController::class, 'downloadIncidentReport'])->name('reports.incidents.pdf');
Route::get('/reportes/goleadores', [TournamentController::class, 'scorersReport'])->name('reports.scorers');
Route::get('/reportes/goleadores/pdf', [TournamentController::class, 'downloadScorersReport'])->name('reports.scorers.pdf');
