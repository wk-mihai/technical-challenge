@extends('layouts.app')

@section('content')
    <div class="container auth-page">
        <div class="auth-wrap">
            @include('auth.partials.auth-logo')

            <div class="card">
                <div class="card-body">
                    <div class="text-center pt-0 pb-3 title">
                        <span>{{ __('Reset Password') }}</span>
                    </div>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="input-group mb-3">
                            <input type="text" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   placeholder="{{ __('Email') }}" aria-label="{{ __('Email') }}"
                                   value="{{ $email ?? old('email') }}" autocomplete="email" autofocus>
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
                                   autocomplete="new-password">
                            <div class="input-group-append">
                                <span class="input-group-text"><span class="fas fa-lock"></span></span>
                            </div>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" name="password_confirmation"
                                   class="form-control @error('password_confirmation') is-invalid @enderror"
                                   placeholder="{{ __('Confirm Password') }}" aria-label="{{ __('Confirm Password') }}"
                                   autocomplete="new-password">
                            <div class="input-group-append">
                                <span class="input-group-text"><span class="fas fa-lock"></span></span>
                            </div>
                        </div>
                        <div class="row mb-0">
                            <div class="col-12 mb-2">
                                <button type="submit" class="btn btn-primary btn-block">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                            <div class="col-12">
                                <a class="btn btn-link text-decoration-none" href="{{ route('login') }}">
                                    {{ __('Login') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
