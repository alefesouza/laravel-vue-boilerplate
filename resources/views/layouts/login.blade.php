<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="login">
<head>
    @component('components.head')
    @endcomponent
</head>
<body>
    <div id="login-screen">
        <div class="row" id="login-row">
            <div class="col-12 col-md-6 col-sm-12 col-lg-4" id="login-col">
                <a href="{{ route('home') }}">
                    <div id="logo">
                        <img src="{{ image('logo.png') }}" height="36" class="d-inline-block align-top" alt="Logo">
                    </div>
                </a>
                @yield('content')
            </div>
            <div class="col-12 col-md-6 col-sm-12 col-lg-8" id="login-background" style="background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ image('background-login.jpg') }}') no-repeat center / cover;">
                <div id="welcome">
                    <h1>@lang('strings.welcome')!</h1>
                    <div class="sub-text">@lang('login.description')</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
