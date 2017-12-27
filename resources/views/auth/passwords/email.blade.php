@extends('layouts.login')

@section('title', __('login.reset_password'))

@section('content')

<form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
    {{ csrf_field() }}

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="reset-title">@lang('login.reset_password')</div>

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

    <div class="form-group">
        <div>
            <button type="submit" class="btn btn-primary">
                @lang('login.send_reset_link')
            </button>
        </div>
    </div>
</form>
@endsection
