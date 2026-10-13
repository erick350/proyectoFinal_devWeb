<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Incident;
use App\Models\Matchday;
use App\Models\Player;
use App\Models\Team;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TournamentController extends Controller
{
    public function dashboard(): View
    {
        return view('dashboard', [
            'teamCount' => Team::count(),
            'playerCount' => Player::count(),
            'goalCount' => Goal::sum('quantity'),
            'incidentCount' => Incident::count(),
            'topScorers' => Player::with('team')->withSum('goals', 'quantity')
                ->orderByDesc('goals_sum_quantity')->orderBy('last_name')->limit(5)->get(),
            'upcomingMatchdays' => Matchday::whereDate('game_date', '>=', today())
                ->orderBy('game_date')->limit(4)->get(),
            'recentIncidents' => Incident::with('player.team')->latest('incident_date')->limit(5)->get(),
        ]);
    }

    public function teams(): View
    {
        return view('teams.index', ['teams' => Team::withCount('players')->orderBy('name')->get()]);
    }

    public function storeTeam(Request $request): RedirectResponse
    {
        $validated = $request->validate(['name' => ['required', 'string', 'max:120', 'unique:teams,name']]);
        Team::create($validated);

        return back()->with('success', 'Equipo registrado correctamente.');
    }

    public function destroyTeam(Team $team): RedirectResponse
    {
        if ($team->players()->exists()) {
            return back()->with('error', 'No se puede eliminar un equipo que tiene jugadores registrados.');
        }

        $team->delete();

        return back()->with('success', 'Equipo eliminado.');
    }

    public function matchdays(): View
    {
        return view('matchdays.index', ['matchdays' => Matchday::withCount('goals')->orderBy('number')->get()]);
    }

    public function storeMatchday(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'number' => ['required', 'integer', 'min:1', 'max:999', 'unique:matchdays,number'],
            'game_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:180'],
        ]);
        Matchday::create($validated);

        return back()->with('success', 'Jornada habilitada correctamente.');
    }

    public function destroyMatchday(Matchday $matchday): RedirectResponse
    {
        if ($matchday->goals()->exists()) {
            return back()->with('error', 'No se puede eliminar una jornada que ya tiene goles registrados.');
        }

        $matchday->delete();

        return back()->with('success', 'Jornada eliminada.');
    }

    public function index(Request $request): View
    {
        $query = Player::with('team')->withSum('goals', 'quantity')->orderBy('last_name')->orderBy('first_name');
        if ($request->filled('team_id')) {
            $query->where('team_id', $request->integer('team_id'));
        }
        if ($request->filled('q')) {
            $search = $request->string('q')->trim()->toString();
            $query->where(fn ($players) => $players->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%"));
        }

        return view('players.index', ['players' => $query->paginate(12)->withQueryString(), 'teams' => Team::orderBy('name')->get()]);
    }

    public function create(): View
    {
        return view('players.form', ['player' => new Player, 'teams' => Team::orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePlayer($request);
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('players', 'public');
            if ($photoPath === false) {
                return back()->withErrors(['photo' => 'No se pudo guardar la fotografía. Inténtalo de nuevo.'])->withInput();
            }
            $validated['photo_path'] = $photoPath;
        }
        $player = Player::create($validated);

        return redirect()->route('players.show', $player)->with('success', 'Jugador registrado correctamente.');
    }

    public function show(Player $player): View
    {
        $player->load(['team', 'goals.matchday', 'incidents' => fn ($query) => $query->latest('incident_date')]);

        return view('players.show', ['player' => $player]);
    }

    public function edit(Player $player): View
    {
        return view('players.form', ['player' => $player, 'teams' => Team::orderBy('name')->get()]);
    }

    public function update(Request $request, Player $player): RedirectResponse
    {
        $validated = $this->validatePlayer($request);
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('players', 'public');
            if ($photoPath === false) {
                return back()->withErrors(['photo' => 'No se pudo guardar la fotografía. Inténtalo de nuevo.'])->withInput();
            }
            if ($player->photo_path) {
                Storage::disk('public')->delete($player->photo_path);
            }
            $validated['photo_path'] = $photoPath;
        }
        $player->update($validated);

        return redirect()->route('players.show', $player)->with('success', 'Datos del jugador actualizados.');
    }

    public function destroy(Player $player): RedirectResponse
    {
        if ($player->photo_path) {
            Storage::disk('public')->delete($player->photo_path);
        }
        $player->delete();

        return redirect()->route('players.index')->with('success', 'Jugador eliminado.');
    }

    private function validatePlayer(Request $request): array
    {
        return $request->validate([
            'team_id' => ['required', 'exists:teams,id'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:120'],
            'birth_date' => ['required', 'date', 'before:today'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);
    }

    public function goals(): View
    {
        return view('goals.index', [
            'goals' => Goal::with(['player.team', 'matchday'])->latest()->paginate(15),
        ]);
    }

    public function createGoal(): View
    {
        return view('goals.create', [
            'players' => Player::with('team')->orderBy('last_name')->get(),
            'matchdays' => Matchday::orderBy('number')->get(),
        ]);
    }

    public function storeGoal(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'player_id' => ['required', 'exists:players,id'],
            'matchday_id' => [
                'required',
                'exists:matchdays,id',
                Rule::unique('goals')->where(fn ($query) => $query->where('player_id', $request->input('player_id'))),
            ],
            'quantity' => ['required', 'integer', 'min:0', 'max:99'],
        ], ['matchday_id.unique' => 'Ya existe un registro de goles para este jugador en esa jornada.']);
        Goal::create($validated);

        return redirect()->route('goals.index')->with('success', 'Goles registrados correctamente.');
    }

    public function destroyGoal(Goal $goal): RedirectResponse
    {
        $goal->delete();

        return back()->with('success', 'Registro de goles eliminado.');
    }

    public function incidents(): View
    {
        return view('incidents.index', ['incidents' => Incident::with('player.team')->latest('incident_date')->paginate(15)]);
    }

    public function createIncident(): View
    {
        return view('incidents.create', ['players' => Player::with('team')->orderBy('last_name')->get()]);
    }

    public function storeIncident(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'player_id' => ['required', 'exists:players,id'],
            'description' => ['required', 'string', 'max:2000'],
            'card_type' => ['required', Rule::in(['amarilla', 'roja'])],
            'incident_date' => ['required', 'date'],
            'suspension_until' => [
                Rule::requiredIf($request->input('card_type') === 'roja'),
                'nullable',
                'date',
                'after_or_equal:incident_date',
            ],
        ], ['suspension_until.required' => 'Indica hasta qué fecha estará suspendido el jugador por la tarjeta roja.']);
        Incident::create($validated);

        return redirect()->route('incidents.index')->with('success', 'Incidencia registrada correctamente.');
    }

    public function destroyIncident(Incident $incident): RedirectResponse
    {
        $incident->delete();

        return back()->with('success', 'Incidencia eliminada.');
    }

    public function refereeReport(Request $request): View
    {
        return view('reports.referee', $this->refereeReportData($request));
    }

    public function downloadRefereeReport(Request $request): Response
    {
        $data = $this->refereeReportData($request);
        $matchday = $data['selectedMatchday'];
        $filename = 'lista-arbitral'.($matchday ? '-jornada-'.$matchday->number : '').'.pdf';

        return Pdf::loadView('reports.pdf.referee', $data)
            ->setPaper('a4', 'landscape')
            ->download($filename);
    }

    private function refereeReportData(Request $request): array
    {
        $matchday = $request->filled('matchday_id') ? Matchday::findOrFail($request->integer('matchday_id')) : null;
        $players = Player::with('team')->when($request->filled('team_id'), fn ($query) => $query->where('team_id', $request->integer('team_id')))
            ->orderBy('team_id')->orderBy('last_name')->get();

        $suspendedIds = $matchday
            ? Incident::where('card_type', 'roja')
                ->whereDate('incident_date', '<=', $matchday->game_date)
                ->whereDate('suspension_until', '>=', $matchday->game_date)
                ->pluck('player_id')->unique()->all()
            : [];

        return [
            'players' => $players,
            'teams' => Team::orderBy('name')->get(),
            'matchdays' => Matchday::orderBy('number')->get(),
            'selectedMatchday' => $matchday,
            'suspendedIds' => $suspendedIds,
            'selectedTeam' => $request->input('team_id'),
        ];
    }

    public function incidentReport(Request $request): View
    {
        return view('reports.incidents', $this->incidentReportData($request));
    }

    public function downloadIncidentReport(Request $request): Response
    {
        return Pdf::loadView('reports.pdf.incidents', $this->incidentReportData($request))
            ->setPaper('a4', 'landscape')
            ->download('reporte-incidencias.pdf');
    }

    private function incidentReportData(Request $request): array
    {
        $incidents = Incident::with('player.team')
            ->when($request->filled('team_id'), fn ($query) => $query->whereHas('player', fn ($players) => $players->where('team_id', $request->integer('team_id'))))
            ->when($request->filled('player_id'), fn ($query) => $query->where('player_id', $request->integer('player_id')))
            ->when($request->filled('card_type'), fn ($query) => $query->where('card_type', $request->input('card_type')))
            ->latest('incident_date')->get();

        return [
            'incidents' => $incidents,
            'teams' => Team::orderBy('name')->get(),
            'players' => Player::orderBy('last_name')->get(),
            'selectedTeam' => $request->input('team_id'),
            'selectedPlayer' => $request->input('player_id'),
            'selectedCardType' => $request->input('card_type'),
        ];
    }

    public function scorersReport(): View
    {
        return view('reports.scorers', ['players' => $this->scorersReportData()]);
    }

    public function downloadScorersReport(): Response
    {
        return Pdf::loadView('reports.pdf.scorers', ['players' => $this->scorersReportData()])
            ->setPaper('a4', 'landscape')
            ->download('tabla-de-goleadores.pdf');
    }

    private function scorersReportData(): Collection
    {
        return Player::with('team')->whereHas('goals')->withSum('goals', 'quantity')
            ->orderByDesc('goals_sum_quantity')->orderBy('last_name')->get();
    }
}
