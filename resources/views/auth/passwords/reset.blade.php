@extends('layouts.login')

@section('title', __('login.reset_password'))

@section('content')

<form class="form-horizontal" method="POST" action="{{ route('password.request') }}">
    {{ csrf_field() }}

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <input type="hidden" name="token" value="{{ $token }}">

    <div class="reset-title">@lang('login.reset_password')</div>

    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <label for="email" class="control-label">@lang('strings.email')</label>

        <div>
            <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus>

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

    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
        <label for="password-confirm" class="control-label">@lang('login.confirm_password')</label>
        <div>
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

            @if ($errors->has('password_confirmation'))
                <span class="help-block">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group">
        <div>
            <button type="submit" class="btn btn-primary">
                @lang('login.reset_password')
            </button>
        </div>
    </div>
</form>
@endsection
