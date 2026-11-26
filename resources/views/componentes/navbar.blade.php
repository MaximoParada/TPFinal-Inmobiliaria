<header class="lp-navbar">
    <nav class="lp-navbar-container">
        <a href="{{ url('/') }}" class="lp-navbar-logo">LoncoProp</a>

        <ul class="lp-navbar-links">
            <li>
                <a href="{{ url('/') }}" class="lp-nav-link {{ request()->is('/') ? 'active' : '' }}">Inicio</a>
            </li>
            <li>
                <a href="{{ url('/turnero') }}" class="{{ request()->is('turnero') ? 'active' : '' }}">Turnero</a>
            </li>

            @guest
                <li>
                    <a href="{{ url('/nosotros') }}" class="lp-nav-link {{ request()->is('nosotros') ? 'active' : '' }}">Nosotros</a>
                </li>
                <li>
                    <a href="{{ url('/#tasaciones') }}" class="lp-nav-link">Tasaciones</a>
                </li>
            @else
                @if (auth()->user()->rol === 'admin')
                    <li>
                        <a href="{{ url('/admin/turnero') }}" class="lp-nav-link {{ request()->is('admin/turnero*') ? 'active' : '' }}">Calendario</a>
                    </li>
                @else
                    <li>
                        <a href="{{ url('/nosotros') }}" class="lp-nav-link {{ request()->is('nosotros') ? 'active' : '' }}">Nosotros</a>
                    </li>
                    <li>
                        <a href="{{ url('/#tasaciones') }}" class="lp-nav-link">Tasaciones</a>
                    </li>
                    <li>
                        <a href="{{ url('/turnero') }}" class="lp-nav-link {{ request()->is('turnero*') ? 'active' : '' }}">Calendario</a>
                    </li>
                @endif
            @endauth
        </ul>

        <div class="lp-navbar-acciones">
            @auth
                <button type="button" class="btn-auth-nav btn-auth-perfil" onclick="abrirModalPerfil()">
                    Mi perfil
                </button>
            @else
                <button type="button" class="btn-auth-nav" onclick="abrirModalAuth('login')">
                    Iniciar sesión
                </button>
            @endauth

            <button type="button" class="btn-bot-navbar" onclick="toggleChatbot()" aria-label="Abrir asistente virtual">
                <img src="{{ asset('img/lonco-boti.svg') }}" alt="Asistente virtual" width="28" height="28">
                <span>Asistente virtual</span>
            </button>
        </div>
    </nav>
</header>