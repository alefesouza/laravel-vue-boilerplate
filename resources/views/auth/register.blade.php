@extends('layouts.login')

@section('title', __('login.register'))

@section('content')
<form class="form-horizontal no-margin" style="margin-top: 30px;" method="POST" action="{{ route('register') }}">
    {{ csrf_field() }}

    <div class="title">@lang('login.register')</div>

    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
        <label for="name" class="control-label">@lang('strings.name')</label>

        <div>
            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

            @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <label for="email" class="control-label">@lang('strings.email')</label>

        <div>
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        <label for="password" class="control-label">@lang('strings.password')</label>

        <div>
            <input id="password" type="password" class="form-control" name="password" required>

            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group">
        <label for="password-confirm" class="control-label">@lang('settings.password_confirmation')</label>

        <div>
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
        </div>
    </div>

    <div class="form-group">
        <div>
            <button type="submit" class="btn btn-primary">
                @lang('login.register')
            </button>
        </div>
    </div>
</form>
@endsection
