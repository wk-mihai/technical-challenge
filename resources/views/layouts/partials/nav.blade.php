<nav class="main-header navbar navbar-expand-md navbar-light bg-light">
    <div class="container">
        <a href="{{ route('home') }}" class="navbar-brand">
            <img src="{{ asset('img/logo.png') }}" alt="logo" title="{{ config('app.name', 'Technical Challenge') }}">
        </a>
        {!! Form::open(['route' => 'trainings.index', 'method' => 'get', 'class' => 'form-inline mr-3 order-md-1']) !!}
        <div class="input-group input-group-sm">
            {!! Form::search('search', request()->input('search'), ['placeholder' => __('Search'), 'class' => 'form-control form-control-navbar']) !!}
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        {!! Form::close() !!}
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse mr-3 justify-content-between order-md-0" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ url()->current() === route('home') ? 'active' : '' }}"
                       href="{{ route('home') }}">{{ __('Home') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ url()->current() === route('trainings.index') ? 'active' : '' }}"
                       href="{{ route('trainings.index') }}">{{ __('Trainings') }}</a>
                </li>
                @if(isAdmin())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownSettings" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                            {{ __('Settings') }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownSettings">
                            <a class="dropdown-item {{ url()->current() === route('admin.trainings.index') ? 'active' : '' }}"
                               href="{{ route('admin.trainings.index') }}">{{ __('Create / Update trainings') }}</a>
                            <a class="dropdown-item {{ url()->current() === route('admin.types.index') ? 'active' : '' }}"
                               href="{{ route('admin.types.index') }}">{{ __('Trainings types') }}</a>
                            <a class="dropdown-item {{ url()->current() === route('admin.users.index') ? 'active' : '' }}"
                               href="{{ route('admin.users.index') }}">{{ __('Users') }}</a>
                            <a class="dropdown-item {{ url()->current() === route('admin.roles.index') ? 'active' : '' }}"
                               href="{{ route('admin.roles.index') }}">{{ __('Roles') }}</a>
                        </div>
                    </li>
                @endif
                <li class="nav-item d-block d-md-none">
                    <a class="nav-link" href="{{ route('logout') }}">
                        <i class="fas fa-sign-out-alt"></i>
                        {{ __('Logout') }}
                    </a>
                </li>
            </ul>
        </div>
        <a class="logout d-none d-md-block order-md-2" href="{{ route('logout') }}">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
</nav>
