<?php

namespace Database\Seeders;

use App\Models\Goal;
use App\Models\Incident;
use App\Models\Matchday;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Database\Seeder;

class DemoTournamentSeeder extends Seeder
{
    public function run(): void
    {
        $teams = collect([
            'Halcones',
            'Jaguares',
            'Estrellas',
            'Titanes',
        ])->mapWithKeys(fn (string $name) => [$name => Team::firstOrCreate(['name' => $name])]);

        $matchdays = collect(range(1, 3))->mapWithKeys(function (int $number) {
            $matchday = Matchday::firstOrCreate(
                ['number' => $number],
                ['game_date' => today()->addDays(2 + (($number - 1) * 7)), 'description' => 'Jornada de demostración']
            );

            return [$number => $matchday];
        });

        $players = collect([
            ['Halcones', 'Andrea', 'Luna', '2014-03-12'],
            ['Halcones', 'Daniel', 'Solís', '2013-08-22'],
            ['Jaguares', 'Camila', 'Ríos', '2014-11-03'],
            ['Jaguares', 'Mateo', 'Cruz', '2013-05-19'],
            ['Estrellas', 'Valeria', 'Paz', '2014-01-27'],
            ['Estrellas', 'Samuel', 'Reyes', '2013-10-14'],
            ['Titanes', 'Lucía', 'Méndez', '2014-06-08'],
            ['Titanes', 'Diego', 'Vega', '2013-12-01'],
        ])->map(function (array $player) use ($teams) {
            [$team, $firstName, $lastName, $birthDate] = $player;

            return Player::firstOrCreate(
                ['team_id' => $teams[$team]->id, 'first_name' => $firstName, 'last_name' => $lastName],
                ['birth_date' => $birthDate]
            );
        });

        foreach ($players as $index => $player) {
            $goals = ($index % 3) + 1;
            Goal::firstOrCreate(
                ['player_id' => $player->id, 'matchday_id' => $matchdays[1]->id],
                ['quantity' => $goals]
            );
        }

        Incident::firstOrCreate(
            ['player_id' => $players[0]->id, 'incident_date' => today()->subDay(), 'card_type' => 'roja'],
            ['description' => 'Incidencia de demostración', 'suspension_until' => today()->addDays(4)]
        );
        Incident::firstOrCreate(
            ['player_id' => $players[2]->id, 'incident_date' => today(), 'card_type' => 'amarilla'],
            ['description' => 'Incidencia de demostración', 'suspension_until' => null]
        );
    }
}
