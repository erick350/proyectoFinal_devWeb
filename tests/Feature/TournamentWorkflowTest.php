<?php

namespace Tests\Feature;

use App\Models\Goal;
use App\Models\Incident;
use App\Models\Matchday;
use App\Models\Player;
use App\Models\Team;
use Database\Seeders\DemoTournamentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class TournamentWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_and_management_pages_render(): void
    {
        $this->get('/')->assertOk()->assertSee('Panel del torneo');
        $this->get(route('teams.index'))->assertOk()->assertSee('Equipos inscritos');
        $this->get(route('matchdays.index'))->assertOk()->assertSee('Jornadas del torneo');
        $this->get(route('players.index'))->assertOk()->assertSee('Jugadores');
        $this->get(route('players.create'))->assertOk()->assertSee('Registrar jugador');
        $this->get(route('goals.index'))->assertOk()->assertSee('Registro de goles');
        $this->get(route('goals.create'))->assertOk()->assertSee('Registrar goles');
        $this->get(route('incidents.index'))->assertOk()->assertSee('Incidencias de jugadores');
        $this->get(route('incidents.create'))->assertOk()->assertSee('Registrar incidencia');
        $this->get(route('reports.incidents'))->assertOk()->assertSee('Incidencias de jugadores');
        $this->get(route('reports.scorers'))->assertOk()->assertSee('Tabla de goleadores');
        $this->get(route('reports.referee'))->assertOk()->assertSee('Lista para el árbitro');
    }

    public function test_it_registers_a_player_goal_and_red_card_incident(): void
    {
        $team = Team::create(['name' => 'Halcones']);
        $matchday = Matchday::create(['number' => 1, 'game_date' => '2026-10-10']);

        $this->post(route('players.store'), [
            'team_id' => $team->id,
            'first_name' => 'Ana',
            'last_name' => 'López',
            'birth_date' => '2015-04-20',
        ])->assertRedirect();

        $player = Player::firstOrFail();
        $this->post(route('goals.store'), [
            'player_id' => $player->id,
            'matchday_id' => $matchday->id,
            'quantity' => 2,
        ])->assertRedirect(route('goals.index'));

        $this->post(route('incidents.store'), [
            'player_id' => $player->id,
            'description' => 'Conducta antideportiva',
            'card_type' => 'roja',
            'incident_date' => '2026-10-10',
            'suspension_until' => '2026-10-17',
        ])->assertRedirect(route('incidents.index'));

        $this->assertDatabaseHas('goals', ['player_id' => $player->id, 'matchday_id' => $matchday->id, 'quantity' => 2]);
        $this->assertSame('2026-10-17', Incident::firstOrFail()->suspension_until->toDateString());
        $this->get(route('players.show', $player))->assertOk()->assertSee('Ana López');
        $this->get(route('reports.scorers'))->assertOk()->assertSee('Halcones')->assertSee('2');
    }

    public function test_player_photo_url_uses_the_public_path_and_supports_application_subdirectories(): void
    {
        $team = Team::create(['name' => 'Halcones']);
        $player = Player::create([
            'team_id' => $team->id,
            'first_name' => 'Ana',
            'last_name' => 'López',
            'birth_date' => '2015-04-20',
            'photo_path' => 'players/foto.png',
        ]);
        $rootUrl = URL::to('/');
        $photoPath = 'storage/players/foto.png';

        try {
            $this->get("/jugadores/{$player->id}")
                ->assertOk()
                ->assertSee(asset($photoPath), false);

            URL::forceRootUrl('http://localhost/proyectoFinal_devWeb/public');

            $this->assertSame(
                'http://localhost/proyectoFinal_devWeb/public/storage/players/foto.png',
                asset($photoPath)
            );
        } finally {
            URL::forceRootUrl($rootUrl);
        }
    }

    public function test_referee_report_marks_players_suspended_for_the_matchday_date(): void
    {
        $team = Team::create(['name' => 'Halcones']);
        $matchday = Matchday::create(['number' => 2, 'game_date' => '2026-10-15']);
        $suspended = Player::create(['team_id' => $team->id, 'first_name' => 'Mario', 'last_name' => 'Pérez', 'birth_date' => '2014-03-12']);
        $eligible = Player::create(['team_id' => $team->id, 'first_name' => 'Luis', 'last_name' => 'Ruiz', 'birth_date' => '2014-09-21']);
        Incident::create([
            'player_id' => $suspended->id,
            'description' => 'Tarjeta roja',
            'card_type' => 'roja',
            'incident_date' => '2026-10-10',
            'suspension_until' => '2026-10-15',
        ]);

        $this->get(route('reports.referee', ['matchday_id' => $matchday->id]))
            ->assertOk()
            ->assertSee('Mario Pérez')
            ->assertSee('Suspendido')
            ->assertSee('Luis Ruiz')
            ->assertSee('Habilitado');
    }

    public function test_reports_can_be_downloaded_as_pdf_with_the_selected_filters(): void
    {
        $team = Team::create(['name' => 'Halcones']);
        $player = Player::create([
            'team_id' => $team->id,
            'first_name' => 'Ana',
            'last_name' => 'López',
            'birth_date' => '2015-04-20',
        ]);
        $matchday = Matchday::create(['number' => 1, 'game_date' => '2026-10-10']);
        Goal::create(['player_id' => $player->id, 'matchday_id' => $matchday->id, 'quantity' => 2]);
        Incident::create([
            'player_id' => $player->id,
            'description' => 'Falta de demostración',
            'card_type' => 'amarilla',
            'incident_date' => '2026-10-10',
        ]);

        foreach ([
            route('reports.referee.pdf', ['matchday_id' => $matchday->id, 'team_id' => $team->id]),
            route('reports.incidents.pdf', ['team_id' => $team->id, 'player_id' => $player->id, 'card_type' => 'amarilla']),
            route('reports.scorers.pdf'),
        ] as $url) {
            $response = $this->get($url);

            $response->assertOk()
                ->assertHeader('content-type', 'application/pdf')
                ->assertHeader('content-disposition');
            $this->assertStringStartsWith('%PDF-', $response->getContent());
        }
    }

    public function test_demo_seeder_creates_fictional_tournament_data_without_duplicates(): void
    {
        $this->seed(DemoTournamentSeeder::class);

        $this->assertDatabaseCount('teams', 4);
        $this->assertDatabaseCount('matchdays', 3);
        $this->assertDatabaseCount('players', 8);
        $this->assertDatabaseCount('goals', 8);
        $this->assertDatabaseCount('incidents', 2);

        $this->seed(DemoTournamentSeeder::class);

        $this->assertDatabaseCount('teams', 4);
        $this->assertDatabaseCount('matchdays', 3);
        $this->assertDatabaseCount('players', 8);
        $this->assertDatabaseCount('goals', 8);
        $this->assertDatabaseCount('incidents', 2);
    }

    public function test_player_can_only_have_one_goal_record_per_matchday(): void
    {
        $team = Team::create(['name' => 'Halcones']);
        $player = Player::create(['team_id' => $team->id, 'first_name' => 'Ana', 'last_name' => 'López', 'birth_date' => '2015-04-20']);
        $matchday = Matchday::create(['number' => 1, 'game_date' => '2026-10-10']);
        Goal::create(['player_id' => $player->id, 'matchday_id' => $matchday->id, 'quantity' => 1]);

        $this->from(route('goals.create'))->post(route('goals.store'), [
            'player_id' => $player->id,
            'matchday_id' => $matchday->id,
            'quantity' => 2,
        ])->assertSessionHasErrors('matchday_id');
    }
}
