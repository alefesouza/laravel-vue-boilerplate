
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>{{ config('app.name', 'Laravel') }}</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}" />

    <meta name="application-name" content="{{ config('app.name', 'Laravel') }}">

    <meta name="theme-color" content="#132a97">

    <meta name="apple-mobile-web-app-title" content="{{ config('app.name', 'Laravel') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <link rel="apple-touch-icon-precomposed" sizes="60x60" href="{{ asset('assets/icons/icon-60x60.png') }}" />
    <link rel="apple-touch-icon-precomposed" sizes="76x76" href="{{ asset('assets/icons/icon-76x76.png') }}" />
    <link rel="apple-touch-icon-precomposed" sizes="120x120" href="{{ asset('assets/icons/icon-120x120.png') }}" />
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="{{ asset('assets/icons/icon-152x152.png') }}" />

    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/icons/icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('assets/icons/icon-96x96.png') }}">

    <link rel="shortcut icon" href="{{ url('favicon.ico') }}">

    <base href="{{ url('/') }}">
</head>
<body>
    <div id="app">
        <div id="vue-loading">{{ __('strings.loading') }}...</div>
    </div>

    <script>
      let authenticated = {{ Auth::guest() ? 'false' : 'true' }};
    </script>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
