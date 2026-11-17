<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | Nois no Corre</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 24px; background: #eef3f0; color: #182820; font: 16px/1.5 system-ui, sans-serif; }
        main { width: min(100%, 420px); background: white; border-radius: 18px; padding: clamp(24px, 6vw, 40px); box-shadow: 0 16px 48px #152f201a; }
        h1 { margin: 4px 0 24px; font-size: 2rem; line-height: 1.2; }
        .brand { color: #296344; margin: 0; font-weight: 700; }
        label { display: block; margin: 16px 0 6px; font-weight: 600; }
        input, button { width: 100%; border-radius: 8px; padding: 11px 12px; font: inherit; }
        input { border: 1px solid #83988c; }
        button { margin-top: 24px; border: 0; background: #19563a; color: white; font-weight: 700; cursor: pointer; }
        :focus-visible { outline: 3px solid #378854; outline-offset: 3px; }
        .error { color: #a51d22; }
    </style>
</head>
<body>
    <main>
        <p class="brand">Nois no Corre</p>
        @yield('content')
    </main>
</body>
</html>
