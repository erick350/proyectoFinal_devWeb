<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel') · Cantera</title>
    <style>
        :root{--ink:#17211c;--muted:#718078;--green:#16794a;--green-dark:#105d39;--mint:#e7f4ec;--paper:#f5f7f5;--line:#e5ebe6;--white:#fff;--red:#c83939;--amber:#a66c0c}
        *{box-sizing:border-box}body{margin:0;background:var(--paper);color:var(--ink);font:14px/1.5 Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",sans-serif}
        a{color:inherit;text-decoration:none}button,input,select,textarea{font:inherit}button,.button{border:0;border-radius:9px;background:var(--green);color:white;padding:10px 15px;font-weight:650;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;gap:7px}.button:hover,button:hover{background:var(--green-dark)}.button.secondary{background:#edf2ee;color:#304239}.button.danger{background:#fff0ef;color:#ad302b}.button.small,button.small{padding:6px 10px;font-size:12px}.button.ghost{background:transparent;color:var(--muted);padding:6px}
        .shell{min-height:100vh}.sidebar{position:fixed;inset:0 auto 0 0;width:238px;background:#10281b;color:#d9e7de;padding:24px 16px;display:flex;flex-direction:column;z-index:3}.brand{padding:5px 10px 26px;display:flex;align-items:center;gap:11px;color:#fff;font-weight:750;font-size:18px;letter-spacing:-.4px}.brand-mark{width:35px;height:35px;background:#38a96e;border-radius:11px;display:grid;place-items:center;font-size:19px}.nav-label{margin:16px 10px 7px;text-transform:uppercase;letter-spacing:1.1px;font-size:10px;font-weight:700;color:#779181}.nav-link{color:#c7d7cc;padding:10px 11px;border-radius:8px;margin:2px 0;display:flex;gap:10px;align-items:center;font-weight:550}.nav-link span:first-child{width:19px;text-align:center;color:#a6c4b0}.nav-link:hover,.nav-link.active{background:#214532;color:white}.nav-link.active span:first-child{color:#72d49e}.sidebar-foot{margin-top:auto;border-top:1px solid #284435;padding:14px 10px 0;color:#9ab0a1;font-size:12px}
        .main{margin-left:238px;min-height:100vh}.topbar{height:72px;background:#fff;border-bottom:1px solid var(--line);display:flex;justify-content:space-between;align-items:center;padding:0 34px}.crumb{font-size:13px;color:var(--muted)}.crumb strong{color:var(--ink)}.top-user{display:flex;align-items:center;gap:10px;font-size:12px;color:var(--muted)}.user-badge{width:32px;height:32px;border-radius:50%;background:var(--mint);display:grid;place-items:center;color:var(--green);font-weight:700}.content{padding:30px 34px 50px;max-width:1440px;margin:0 auto}.page-head{display:flex;justify-content:space-between;align-items:flex-start;gap:15px;margin-bottom:25px}.eyebrow{text-transform:uppercase;letter-spacing:1.3px;color:var(--green);font-weight:750;font-size:10px;margin-bottom:5px}.page-head h1{font-size:26px;letter-spacing:-.8px;margin:0 0 4px}.subtitle{margin:0;color:var(--muted);font-size:13px}
        .grid{display:grid;gap:17px}.stats{grid-template-columns:repeat(4,minmax(0,1fr));margin-bottom:22px}.two-col{grid-template-columns:1.2fr .8fr}.card{background:#fff;border:1px solid var(--line);border-radius:13px;padding:20px}.card-title{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px}.card-title h2{font-size:15px;margin:0;letter-spacing:-.2px}.stat-card{padding:18px 20px;display:flex;align-items:center;justify-content:space-between}.stat-label{color:var(--muted);font-size:12px}.stat-number{font-size:28px;font-weight:760;letter-spacing:-1px;margin-top:2px}.stat-icon{width:40px;height:40px;background:var(--mint);color:var(--green);display:grid;place-items:center;border-radius:11px;font-size:19px}
        .table-wrap{overflow:auto}table{width:100%;border-collapse:collapse;text-align:left}th{font-size:10px;text-transform:uppercase;letter-spacing:.8px;color:#829087;font-weight:700;padding:10px 12px;border-bottom:1px solid var(--line);white-space:nowrap}td{padding:12px;border-bottom:1px solid #f0f3f0;vertical-align:middle}tbody tr:last-child td{border-bottom:0}.person{display:flex;align-items:center;gap:10px;font-weight:650}.avatar{width:36px;height:36px;object-fit:cover;border-radius:50%;background:#e9f1eb}.avatar.placeholder{display:grid;place-items:center;font-weight:700;color:var(--green)}.muted{color:var(--muted)}.badge{display:inline-flex;border-radius:20px;padding:4px 9px;font-size:11px;font-weight:700;background:#edf2ee;color:#4b5b51;white-space:nowrap}.badge.red{background:#fff0ef;color:#ad302b}.badge.yellow{background:#fff5db;color:#93600a}.badge.green{background:#e7f4ec;color:#16794a}.badge.gray{background:#f0f2f0;color:#68756d}.actions{display:flex;gap:6px;align-items:center}.actions form{display:inline}.empty{text-align:center;color:var(--muted);padding:28px 12px}
        .form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}.field{display:flex;flex-direction:column;gap:6px}.field.full{grid-column:1/-1}.field label{font-size:12px;font-weight:700;color:#39473f}.field input,.field select,.field textarea,.filter input,.filter select{width:100%;border:1px solid #dfe7e1;background:#fff;border-radius:8px;padding:10px 11px;color:var(--ink);outline:none}.field input:focus,.field select:focus,.field textarea:focus,.filter input:focus,.filter select:focus{border-color:#58a77a;box-shadow:0 0 0 3px #e5f2e9}.field textarea{min-height:105px;resize:vertical}.hint{font-size:11px;color:var(--muted)}.error{font-size:11px;color:#b42318}.form-actions{display:flex;gap:9px;margin-top:20px}.filter{display:flex;gap:9px;align-items:end;flex-wrap:wrap;margin-bottom:17px}.filter .field{min-width:160px}.filter .field.grow{flex:1}.alert{padding:12px 14px;border-radius:9px;margin-bottom:17px;font-weight:600}.alert.success{background:#e7f4ec;color:#176b42}.alert.error{background:#fff0ef;color:#ad302b}.pagination{margin-top:18px}.pagination nav{display:flex;justify-content:space-between}.pagination svg{width:16px}
        .profile{display:grid;grid-template-columns:190px 1fr;gap:22px}.profile-photo{width:170px;height:195px;object-fit:cover;border-radius:12px;background:#e9f1eb}.profile-placeholder{display:grid;place-items:center;color:var(--green);font-size:48px}.detail-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}.detail-label{font-size:10px;text-transform:uppercase;letter-spacing:.7px;color:var(--muted)}.detail-value{font-size:14px;font-weight:650;margin-top:3px}.mobile-menu{display:none}
        @media(max-width:1000px){.sidebar{width:205px}.main{margin-left:205px}.content{padding:26px 22px}.topbar{padding:0 22px}.stats{grid-template-columns:repeat(2,1fr)}}
        @media(max-width:700px){.sidebar{position:relative;width:auto;height:auto;padding:10px 13px;flex-direction:row;align-items:center;overflow:auto}.brand{padding:4px 8px;white-space:nowrap}.brand-mark{width:30px;height:30px}.nav-label,.sidebar-foot{display:none}.nav-link{padding:8px;white-space:nowrap;font-size:12px}.nav-link span:first-child{width:auto}.main{margin:0}.topbar{height:55px;padding:0 15px}.content{padding:22px 14px}.page-head{align-items:center}.page-head h1{font-size:22px}.stats{gap:10px}.stat-card{padding:13px}.stat-number{font-size:23px}.two-col{grid-template-columns:1fr}.profile{grid-template-columns:1fr}.profile-photo{width:130px;height:145px}.detail-grid{grid-template-columns:repeat(2,1fr)}.form-grid{grid-template-columns:1fr}.field.full{grid-column:auto}.top-user .user-label{display:none}}
    </style>
</head>
<body>
@php($currentRoute = request()->route()?->getName())
<div class="shell">
    <aside class="sidebar">
        <a class="brand" href="{{ route('dashboard') }}"><span class="brand-mark">⚽</span><span>Cantera<span style="color:#76d59e">.</span></span></a>
        <div class="nav-label">General</div>
        <a class="nav-link {{ $currentRoute === 'dashboard' ? 'active' : '' }}" href="{{ route('dashboard') }}"><span>⌂</span>Resumen</a>
        <div class="nav-label">Torneo</div>
        <a class="nav-link {{ str_starts_with($currentRoute ?? '', 'teams.') ? 'active' : '' }}" href="{{ route('teams.index') }}"><span>◈</span>Equipos</a>
        <a class="nav-link {{ str_starts_with($currentRoute ?? '', 'players.') ? 'active' : '' }}" href="{{ route('players.index') }}"><span>♙</span>Jugadores</a>
        <a class="nav-link {{ str_starts_with($currentRoute ?? '', 'matchdays.') ? 'active' : '' }}" href="{{ route('matchdays.index') }}"><span>▦</span>Jornadas</a>
        <a class="nav-link {{ str_starts_with($currentRoute ?? '', 'goals.') ? 'active' : '' }}" href="{{ route('goals.index') }}"><span>⚽</span>Goles</a>
        <a class="nav-link {{ str_starts_with($currentRoute ?? '', 'incidents.') ? 'active' : '' }}" href="{{ route('incidents.index') }}"><span>⚑</span>Incidencias</a>
        <div class="nav-label">Reportes</div>
        <a class="nav-link {{ $currentRoute === 'reports.referee' ? 'active' : '' }}" href="{{ route('reports.referee') }}"><span>▤</span>Lista arbitral</a>
        <a class="nav-link {{ $currentRoute === 'reports.incidents' ? 'active' : '' }}" href="{{ route('reports.incidents') }}"><span>⚑</span>Reporte incidencias</a>
        <a class="nav-link {{ $currentRoute === 'reports.scorers' ? 'active' : '' }}" href="{{ route('reports.scorers') }}"><span>↗</span>Goleadores</a>
        <div class="sidebar-foot">Administración del torneo<br>Fútbol infantil</div>
    </aside>
    <main class="main">
        <header class="topbar"><div class="crumb">Cantera <span style="padding:0 6px">/</span> <strong>@yield('section', 'Resumen')</strong></div><div class="top-user"><span class="user-badge">A</span><span class="user-label">Administración</span></div></header>
        <div class="content">
            @if(session('success'))<div class="alert success">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="alert error">{{ session('error') }}</div>@endif
            @if($errors->any())<div class="alert error">Revisa los datos: {{ $errors->first() }}</div>@endif
            @yield('content')
        </div>
    </main>
</div>
<script>
document.querySelectorAll('[data-confirm]').forEach(form=>form.addEventListener('submit',event=>{if(!confirm(form.dataset.confirm))event.preventDefault()}));
document.querySelectorAll('[data-card-type]').forEach(select=>{const target=document.querySelector(select.dataset.cardType);const update=()=>{if(target){target.hidden=select.value!=='roja';const input=target.querySelector('input');if(input)input.required=select.value==='roja'}};select.addEventListener('change',update);update()});
</script>
</body>
</html>
