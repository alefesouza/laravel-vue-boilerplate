@hasSection('title')
<title>@yield('title') - {{ config('app.name', 'Laravel') }}</title>
@else
<title>{{ config('app.name', 'Laravel') }}</title>
@endif

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<meta name="csrf-token" content="{{ csrf_token() }}">

<meta name="application-name" content="{{ config('app.name', 'Laravel') }}" />

<meta name="apple-mobile-web-app-title" content="{{ config('app.name', 'Laravel') }}" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="default" />

<link rel="shortcut icon" href="{{ url('favicon.ico') }}">

<link href="{{ css('app.css') }}" rel="stylesheet">

<base href="{{ url('/') }}">
@yield('styles')
