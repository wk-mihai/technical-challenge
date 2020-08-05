@extends('layouts.app')

@section('content')
    <div class="container auth-page">
        <div class="auth-wrap">
            @include('auth.partials.auth-logo')

            <div class="card">
                <div class="card-body">
                    <div class="text-center pt-0 pb-3 title">
                        <span>{{ __('Confirm Password') }}</span>
                    </div>

                    <div class="mb-3">
                        {{ __('Please confirm your password before continuing.') }}
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="input-group mb-3">
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="{{ __('Password') }}" aria-label="{{ __('Password') }}"
                                   autocomplete="current-password" autofocus>
                            <div class="input-group-append">
                                <span class="input-group-text"><span class="fas fa-lock"></span></span>
                            </div>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="row mb-0">
                            <div class="col-12 mb-2">
                                <button type="submit" class="btn btn-primary btn-block">
                                    {{ __('Confirm Password') }}
                                </button>
                            </div>
                            <div class="col-12">
                                <a class="btn btn-link text-decoration-none" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
