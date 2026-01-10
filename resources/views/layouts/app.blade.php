<div>
    <!DOCTYPE html>
    <html>
    <head>
        <title>@yield('title', 'Sistem Toko')</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
    @include('partials.nav')

    <div class="container mt-4">
        @yield('content')
    </div>

    </body>
    </html>

</div>
