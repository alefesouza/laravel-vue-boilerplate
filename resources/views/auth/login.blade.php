@extends('layouts.login')

@section('title', 'Login')

@section('content')
<form class="form-horizontal" method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}

    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <label for="email" class="control-label">@lang('strings.email')</label>

        <div>
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

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

    <div class="form-group" id="boxes">
        <div class="d-flex justify-content-between">
            <div class="checkbox">
                <label class="custom-control custom-checkbox">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} class="custom-control-input">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">@lang('login.keep_connected')</span>
                </label>
            </div>

            <a class="btn btn-link" href="{{ route('password.request') }}">
                <i class="fa fa-question-circle" aria-hidden="true"></i> @lang('login.forgot_password')
            </a>
        </div>
    </div>

    <div class="form-group">
        <div>
            <button type="submit" class="btn btn-primary">
                Login
            </button>
        </div>
    </div>
</form>
@endsection
