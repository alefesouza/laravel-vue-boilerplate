<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @component('components.head')
    @endcomponent
</head>
<body>
    <div id="app">
        @lang('strings.loading')
    </div>

    @component('components.scripts')
    @endcomponent
</body>
</html>
