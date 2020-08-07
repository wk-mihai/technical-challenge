@extends('layouts.app')

@section('content')
    <div class="auth-wrap">
        @include('auth.partials.auth-logo')

        <div class="card">
            <div class="card-body">
                <div class="text-center pt-0 pb-3 title">
                    <span>{{ __('Login') }}</span>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="input-group mb-3">
                        <input type="text" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="{{ __('Email') }}" aria-label="{{ __('Email') }}"
                               value="{{ old('email') }}" autocomplete="email" autofocus>
                        <div class="input-group-append">
                            <span class="input-group-text"><span class="fas fa-envelope"></span></span>
                        </div>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="{{ __('Password') }}" aria-label="{{ __('Password') }}"
                               autocomplete="password">
                        <div class="input-group-append">
                            <span class="input-group-text"><span class="fas fa-lock"></span></span>
                        </div>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                    <div class="row align-items-center pt-2 pb-2">
                        <div class="col-7">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="remember" class="custom-control-input"
                                       id="remember-me" {{ old('remember') ? 'checked' : '' }}>
                                <label class="custom-control-label"
                                       for="remember-me">{{ __('Remember me') }}</label>
                            </div>
                        </div>
                        <div class="col-5">
                            <button type="submit" class="btn btn-primary btn-block">{{ __('Login') }}</button>
                        </div>
                        <a class="btn btn-link text-decoration-none" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
