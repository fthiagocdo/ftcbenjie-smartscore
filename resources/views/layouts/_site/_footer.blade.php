<!-- Footer section -->
<footer class="footer-section">
    <div class="container">
        <ul class="footer-menu">
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
        <p class="copyright"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
            Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made by <a href="https://colorlib.com" target="_blank">Colorlib</a>
            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
        </p>
    </div>
</footer>
<!-- Footer section end -->