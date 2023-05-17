<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title')</title>
        <link rel="stylesheet" href="assets/css/bootstrap/bootstrap.min.css">
    </head>
    <body>
        <nav class="navbar navbar-expand-sm navbar-light bg-light">
            <a class="navbar-brand">
                <img src="assets/img/logo.png" alt="" width="30" height="24">
                Ahorcado
            </a>
            <div class="container justify-content-around">
                <ul class="navbar-nav">
                    @yield('navbar')
                </ul>
            </div>
        </nav>
        @yield('mensaje')
        @yield('content')
        <!-- Scripts -->
        <script src="assets/js/bootstrap/bootstrap.min.js"></script>
        <script src="assets/js/jquery/jquery-3.6.0.min.js"></script>
        <script src="assets/js/pista.js"></script>
    </body>
</html>