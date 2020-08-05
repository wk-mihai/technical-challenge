<nav class="main-header navbar navbar-expand-md navbar-light bg-light">
    <div class="container">
        <a href="{{ route('home') }}" class="navbar-brand">
            <img src="{{ asset('img/logo.png') }}" alt="logo" title="{{ config('app.name', 'Technical Challenge') }}">
        </a>
        <form class="form-inline mr-3 order-md-1">
            @csrf
            <div class="input-group input-group-sm">
                <input class="form-control form-control-navbar" type="search" placeholder="Search"
                       aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-navbar" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse mr-3 justify-content-between order-md-0" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ url()->current() === route('home') ? 'active' : '' }}"
                       href="{{ route('home') }}">Home</a>
                </li>
                @if(canViewTrainings())
                    <li class="nav-item">
                        <a class="nav-link {{ url()->current() === route('trainings') ? 'active' : '' }}"
                           href="{{ route('trainings') }}">Trainings</a>
                    </li>
                @endif
                @if(isAdmin())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownSettings" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                            Settings
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownSettings">
                            <a class="dropdown-item" href="#">Create / Update trainings</a>
                            <a class="dropdown-item" href="#">Trainings types</a>
                            <a class="dropdown-item" href="#">Users</a>
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
