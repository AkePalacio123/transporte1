<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIGCT')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 p-0">
            @include('partials.sidebar')
        </div>
        <!-- Contenido -->
        <div class="col-md-10 p-0 d-flex flex-column min-vh-100">
            @include('partials.navbar')
            <main class="flex-grow-1 p-4">
                @yield('content')
            </main>
            @include('partials.footer')
        </div>
    </div>
</div>
</body>
</html>