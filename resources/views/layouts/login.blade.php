<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="login">
<head>
    @component('components.head')
    @endcomponent
</head>
<body>
    <div id="login-screen" class="container-fluid">
        <div class="row" id="login-row">
            <div class="col-12 col-md-6 col-sm-12 col-lg-4" id="login-col">
                <a href="{{ url('/') }}">
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
    <a href="https://github.com/alefesouza/laravel-vue-boilerplate" target="_blank">
        <img style="position: absolute; top: 0; right: 0; border: 0;" src="https://camo.githubusercontent.com/38ef81f8aca64bb9a64448d0d70f1308ef5341ab/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f6461726b626c75655f3132313632312e706e67" alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_right_darkblue_121621.png">
    </a>
</body>
</html>
