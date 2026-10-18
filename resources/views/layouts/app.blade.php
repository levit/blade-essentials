<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Select Components')</title>
    @stack('styles')
</head>
<body>
    <div id="app">
        @yield('content')
    </div>
    @stack('scripts')
</body>
</html>

