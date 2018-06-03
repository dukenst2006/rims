<nav class="navbar navbar-expand-md navbar-light navbar-laravel">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <!-- Jobs -->
                <li class="nav-item">
                    <a class="nav-link{{ return_if(on_page('jobs.index'), ' active') }}"
                       href="{{ route('jobs.index', $area) }}">
                        Jobs
                    </a>
                </li>

                @auth
                    <li class="nav-item dropdown"><!-- My Companies -->
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            My Companies <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            @if($user_companies->count())
                                @foreach($user_companies as $company)
                                    <a class="dropdown-item" href="{{ route('tenant.switch', $company) }}">
                                        {{ $company->name }}
                                    </a>
                                @endforeach
                            @else
                                <span class="dropdown-item">
                              No companies found.
                            </span>
                            @endif
                            <div class="dropdown-divider"></div>
                            <!-- Create New Company Link -->
                            <a class="dropdown-item" href="{{ route('account.companies.create') }}">
                                New company
                            </a>

                            <!-- View All Link -->
                            <a class="dropdown-item" href="{{ route('account.companies.index') }}">
                                View all
                            </a>
                        </div>
                    </li>

                    <!-- Projects -->
                    <li class="nav-item">
                        <a class="nav-link{{ return_if(on_page('tenant.projects.index'), ' active') }}"
                           href="{{ route('tenant.projects.index') }}">
                            Projects
                        </a>
                    </li>
                @endauth

                @notsubscribed
                <li class="nav-item">
                    <a class="nav-link{{ return_if(on_page('plans.index'), ' active') }}"
                       href="{{ route('plans.index') }}">
                        Pricing
                    </a>
                </li>
                @endnotsubscribed
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><!-- Location -->
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="icon-location-pin"></i> {{ $area->name }}
                    </a>
                </li>

                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link{{ return_if(on_page('login'), ' active') }}" href="{{ route('login') }}">
                            Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link{{ return_if(on_page('register'), ' active') }}"
                           href="{{ route('register') }}">
                            Sign Up
                        </a>
                    </li>
                @else
                    <li class="nav-item"><!-- My Portfolios -->
                        <a class="nav-link" href="{{ route('account.portfolios.index') }}">
                            <i class="icon-layers"></i> My Portfolios
                        </a>
                    </li>
                    <li class="nav-item"><!-- My Dashboard -->
                        <a class="nav-link{{ return_if(on_page('account.dashboard'), ' active') }}"
                           href="{{ route('account.dashboard') }}" title="My Dashboard">
                            <i class="icon-speedometer"></i>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                           title="{{ Auth::user()->name }}">
                            <i class="icon icon-user"></i>
                            <div class="d-inline d-sm-none">{{ Auth::user()->name }}</div>
                            <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <!-- Impersonating -->
                            @impersonating
                            <a class="dropdown-item" href="#"
                               onclick="event.preventDefault(); document.getElementById('impersonate-form').submit();">
                                Stop Impersonating
                            </a>
                            <form id="impersonate-form" action="{{ route('admin.users.impersonate.destroy') }}"
                                  method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                            @endimpersonating

                            <!-- Admin Panel Link -->
                            @role('admin')
                            <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                Admin Panel
                            </a>
                            @endrole

                            <!-- User Account Link -->
                            <a class="dropdown-item" href="{{ route('account.index') }}">
                                My Account
                            </a>

                            <!-- Developer Link -->
                            <a class="dropdown-item" href="{{ route('developer.index') }}">
                                Developer Panel
                            </a>
                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                Logout
                            </a>

                            @include('layouts.partials.forms._logout')
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
