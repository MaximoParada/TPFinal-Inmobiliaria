<header class="lp-navbar">
    <nav class="lp-navbar-container">
        <a href="{{ url('/') }}" class="lp-navbar-logo">LoncoProp</a>

        <ul class="lp-navbar-links">
            <li>
                <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Inicio</a>
            </li>
            <li>
                <a href="{{ url('/nosotros') }}" class="{{ request()->is('nosotros') ? 'active' : '' }}">Nosotros</a>
            </li>
            <li>
                <a href="{{ url('/tasaciones') }}" class="{{ request()->is('tasaciones') ? 'active' : '' }}">Tasaciones</a>
            </li>

            @auth
                @if (auth()->user()->rol === 'user')
                    <li>
                        <a href="{{ url('/turnero') }}" class="{{ request()->is('turnero') ? 'active' : '' }}">Turnero</a>
                    </li>
                @elseif (auth()->user()->rol === 'admin')
                    <li>
                        <a href="{{ url('/admin/propiedades') }}" class="{{ request()->is('admin/propiedades*') ? 'active' : '' }}">Propiedades</a>
                    </li>
                    <li>
                        <a href="{{ url('/admin/turnero') }}" class="{{ request()->is('admin/turnero*') ? 'active' : '' }}">Turnero Admin</a>
                    </li>
                @endif
            @endauth
        </ul>

        <div class="lp-navbar-acciones">
            @guest
                <button type="button" class="btn-auth-nav" onclick="abrirModalAuth('login')">
                    Iniciar sesión
                </button>
            @endguest

            @auth
                <button type="button" class="btn-auth-nav btn-auth-perfil" onclick="abrirModalPerfil()">
                    Mi perfil
                </button>
            @endauth

            <button type="button" class="btn-bot-navbar" onclick="toggleChatbot()">
                <span class="btn-bot-navbar-circulo">
                    <img src="{{ asset('img/lonco-boti.svg') }}" alt="LoncoBoti">
                </span>
                <span class="btn-bot-navbar-texto">Asistente virtual</span>
            </button>
        </div>
    </nav>
</header>