<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @component('components.head')
    @endcomponent
</head>
<body>
    <div id="app">
        <div id="vue-loading">@lang('strings.loading')...</div>
    </div>

    @component('components.scripts')
    @endcomponent
</body>
</html>
