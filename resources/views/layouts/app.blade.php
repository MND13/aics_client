<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>AICS Online Application</title>

    <!-- Scripts -->

    <script src="{{ asset('js/app.js') }}" defer></script>

    {!! NoCaptcha::renderJs() !!}


    <!-- Fonts
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">-->

    <!-- Styles
    <style>
        @import url('node_modules/material-design-icons-iconfont/dist/material-design-icons.css');
      </style> -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">

    <!--<link href="https://cdn.jsdelivr.net/npm/mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">-->

    @routes()
</head>

<body>
    <div id="app" data-app>
        <v-app>

            <main>
                @yield('content')
            </main>
        </v-app>
    </div>
</body>

</html>
