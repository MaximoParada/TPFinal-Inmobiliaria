<!-- FLOTANTE LOGIN / CREAR CUENTA -->
<div id="modalAuth" class="lp-modal-backdrop d-none" aria-hidden="true">
    <div class="lp-modal-card" role="dialog" aria-modal="true" aria-labelledby="tituloModalAuth">
        <div class="lp-modal-header">
            <h2 id="tituloModalAuth" class="lp-modal-title">Iniciar sesión</h2>
            <button type="button" class="lp-modal-close" onclick="cerrarModalAuth()" aria-label="Cerrar">×</button>
        </div>

        <div id="alertaAuth" class="lp-modal-alert d-none"></div>

        <!-- Formulario Login (usuario y admin) -->
        <form id="formLoginFlotante" action="{{ route('login.post') }}" method="POST" class="lp-modal-form" autocomplete="off">
            @csrf
            <div class="lp-campo">
                <label for="login_email">Correo electrónico</label>
                <input type="email" id="login_email" name="email" required autocomplete="off" placeholder="tu@correo.com">
            </div>

            <div class="lp-campo">
                <label for="login_password">Contraseña</label>
                <input type="password" id="login_password" name="password" required autocomplete="new-password" placeholder="Tu contraseña">
            </div>

            <button type="submit" class="btn-auth-submit">Iniciar sesión</button>

            <p class="lp-modal-switch">
                ¿No tenés cuenta?
                <button type="button" class="lp-link-btn" onclick="cambiarVistaAuth('register')">Creá una</button>
            </p>
        </form>

        <!-- Formulario Registro (solo usuarios) -->
        <form id="formRegistroFlotante" action="{{ route('registro.post') }}" method="POST" class="lp-modal-form d-none" autocomplete="off">
            @csrf
            <div class="lp-fila-doble">
                <div class="lp-campo">
                    <label for="reg_name">Nombre</label>
                    <input type="text" id="reg_name" name="name" required autocomplete="off" placeholder="Nombre">
                </div>
                <div class="lp-campo">
                    <label for="reg_apellido">Apellido</label>
                    <input type="text" id="reg_apellido" name="apellido" required autocomplete="off" placeholder="Apellido">
                </div>
            </div>

            <div class="lp-fila-doble">
                <div class="lp-campo">
                    <label for="reg_telefono">Teléfono</label>
                    <input type="text" id="reg_telefono" name="telefono" required autocomplete="off" placeholder="Ej: 299 555-1234">
                </div>
                <div class="lp-campo">
                    <label for="reg_email">Correo electrónico</label>
                    <input type="email" id="reg_email" name="email" required autocomplete="off" placeholder="correo@ejemplo.com">
                </div>
            </div>

            <div class="lp-campo">
                <label for="reg_password">Contraseña</label>
                <input type="password" id="reg_password" name="password" required autocomplete="new-password" placeholder="Creá una contraseña">
                <ul class="lp-pass-reglas" id="reglasPassword">
                    <li id="reglaMayuscula" class="regla-pendiente">Al menos una letra mayúscula</li>
                    <li id="reglaNumero" class="regla-pendiente">Al menos un número</li>
                </ul>
            </div>

            <button type="submit" id="btnSubmitRegistro" class="btn-auth-submit">Crear cuenta</button>

            <p class="lp-modal-switch">
                ¿Ya tenés cuenta?
                <button type="button" class="lp-link-btn" onclick="cambiarVistaAuth('login')">Iniciá sesión</button>
            </p>
        </form>
    </div>
</div>

<!-- FLOTANTE MI PERFIL -->
@auth
<div id="modalPerfil" class="lp-modal-backdrop d-none" aria-hidden="true">
    <div class="lp-modal-card" role="dialog" aria-modal="true" aria-labelledby="tituloModalPerfil">
        <div class="lp-modal-header">
            <h2 id="tituloModalPerfil" class="lp-modal-title">Mi perfil</h2>
            <button type="button" class="lp-modal-close" onclick="cerrarModalPerfil()" aria-label="Cerrar">×</button>
        </div>

        <div id="alertaPerfil" class="lp-modal-alert d-none"></div>
        <div id="exitoPerfil" class="lp-modal-success d-none"></div>

        <!-- VISTA LECTURA DEL PERFIL -->
        <div id="vistaLecturaPerfil">
            <div class="lp-perfil-datos">
                @if (auth()->user()->rol === 'admin')
                    <div class="lp-admin-id-box">
                        <span class="lp-admin-id-label">Identificador de Administrador</span>
                        <strong class="lp-admin-id-valor">ID Admin #{{ auth()->user()->id }}</strong>
                    </div>
                @endif

                <div class="lp-dato-item">
                    <span>Nombre y apellido</span>
                    <strong id="perfilTextoNombre">{{ auth()->user()->name }} {{ auth()->user()->apellido }}</strong>
                </div>
                <div class="lp-dato-item">
                    <span>Correo electrónico</span>
                    <strong id="perfilTextoEmail">{{ auth()->user()->email }}</strong>
                </div>
                <div class="lp-dato-item">
                    <span>Teléfono</span>
                    <strong id="perfilTextoTelefono">{{ auth()->user()->telefono ?: 'No especificado' }}</strong>
                </div>
            </div>

            <div class="lp-perfil-footer-doble">
                <form id="formLogoutFlotante" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-auth-logout">Cerrar sesión</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endauth