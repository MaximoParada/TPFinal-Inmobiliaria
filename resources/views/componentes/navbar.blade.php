<header class="lp-navbar">
    <nav class="lp-navbar-container">
        <ul class="lp-navbar-links">
            <li>
                <a href="{{ route('inicio') }}" class="lp-nav-link {{ request()->routeIs('inicio') ? 'active' : '' }}">Inicio</a>
            </li>

            @guest
                <li>
                    <a href="{{ route('nosotros') }}" class="lp-nav-link {{ request()->routeIs('nosotros') ? 'active' : '' }}">Nosotros</a>
                </li>
                <li>
                    <a href="{{ route('tasaciones') }}" class="lp-nav-link {{ request()->routeIs('tasaciones') ? 'active' : '' }}">Tasaciones</a>
                </li>
                <li>
                    <a
                        href="{{ route('turnero') }}"
                        class="lp-nav-link js-turnero-auth {{ request()->routeIs('turnero') ? 'active' : '' }}"
                        data-requiere-login="1"
                    >Turnero</a>
                </li>
            @else
                @if (auth()->user()->rol === 'admin')
                    <li>
                        <a href="{{ route('turnero') }}" class="lp-nav-link {{ request()->routeIs('turnero') ? 'active' : '' }}">Turnero</a>
                    </li>
                @else
                    <li>
                        <a href="{{ route('nosotros') }}" class="lp-nav-link {{ request()->routeIs('nosotros') ? 'active' : '' }}">Nosotros</a>
                    </li>
                    <li>
                        <a href="{{ route('tasaciones') }}" class="lp-nav-link {{ request()->routeIs('tasaciones') ? 'active' : '' }}">Tasaciones</a>
                    </li>
                    <li>
                        <a href="{{ route('turnero') }}" class="lp-nav-link {{ request()->routeIs('turnero') ? 'active' : '' }}">Turnero</a>
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
                <span class="btn-bot-navbar-circulo">
                    <img src="{{ asset('img/lonco-boti.svg') }}" alt="Asistente virtual" width="36" height="36">
                </span>
                <span class="btn-bot-navbar-texto">Asistente virtual</span>
            </button>
        </div>
    </nav>
</header>