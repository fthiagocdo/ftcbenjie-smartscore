<!-- Header section -->
<header class="header-section">
    <div class="container">
        <!-- logo -->
        <a class="site-logo primary-color" href="index.html">
            {{ Session::has('competition_name') ? Session::get('competition_name') : 'Nome da Etapa' }}
        </a>
        <!-- responsive -->
        <div class="nav-switch">
            <i class="fa fa-bars primary-color"></i>
        </div>
        <!-- site menu -->
        <nav class="main-menu">
            <ul>
                @if(Session::get('page') == 'home')
                <li class="active">
                @else
                <li>
                @endif
                    <a href="{{ url('/') }}">Home</a>
                </li>

                @if(Auth::check() && Auth::user()->hasAdmin())
                    @if(Session::get('page') == 'competition')
                    <li class="active">
                    @else
                    <li>
                    @endif
                        <a href="{{ url('/competition') }}">Campeonato</a>
                    </li>
                @endif

                @if(Auth::check() && Auth::user()->hasAdmin())
                    @if(Session::get('page') == 'competitors')
                    <li class="active">
                    @else
                    <li>
                    @endif
                        <a href="{{ url('/competitors') }}">Competidores</a>
                    </li>
                @endif

                @if(Auth::check() && Auth::user()->hasAdmin())
                    @if(Session::get('page') == 'announcers')
                    <li class="active">
                    @else
                    <li>
                    @endif
                        <a href="{{ url('/announcers') }}">Locutores</a>
                    </li>
                @endif

                @if(Auth::check() && Auth::user()->hasAdmin())
                    @if(Session::get('page') == 'judges')
                    <li class="active">
                    @else
                    <li>
                    @endif
                        <a href="{{ url('/judges') }}">Juízes</a>
                    </li>
                @endif

                @if(Auth::check() && Auth::user()->hasRole('JUDGE'))
                    @if(Session::get('page') == 'score')
                    <li class="active">
                    @else
                    <li>
                    @endif
                        <a href="{{ url('/score') }}">Pontuação</a>
                    </li>
                @endif
                
                @if(Auth::guest())
                    @if(Session::get('page') == 'login')
                    <li class="active">
                    @else
                    <li>
                    @endif
                        <a href="{{ url('/login') }}">Login</a>
                    </li>
                @endif

                @if(Auth::check())
                    <li>
                        <a href="{{ url('/logout') }}">Logout</a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</header>
<!-- Header section end -->