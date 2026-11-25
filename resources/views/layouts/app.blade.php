<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Loncoprop - Servicios Inmobiliarias</title>   
    
        <!-- Bootstrap 5 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- Estilos base de BotUI -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/botui/build/botui.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/botui/build/botui-theme-default.css">

        <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">

        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body>

        @include('componentes.navbar')

        <main>
            @yield('contenido')
        </main>

        @include('componentes.footer')

        @include('componentes.modales-auth')

        <!-- VENTANA DEL CHATBOT (Empieza oculta gracias a d-none) -->
        <div id="ventanaChatbot" class="ventana-bot shadow-lg d-none">
            <!-- Encabezado del chat -->
            <div class="ventana-bot-header d-flex align-items-center justify-content-between px-3 py-2">
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset('img/lonco-boti.svg') }}" alt="LoncoBoti" width="28" height="28">
                    <div>
                        <strong class="d-block lh-1" style="font-size: 0.9rem;">LoncoBoti</strong>
                        <small class="text-white-50" style="font-size: 0.72rem;">Asistente virtual 24/7</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white btn-sm" onclick="toggleChatbot()" aria-label="Cerrar"></button>
            </div>

            <!-- Acá adentro BotUI dibuja la conversación -->
            <div id="lonco-botui" class="ventana-bot-body">
                <bot-ui></bot-ui>
            </div>
        </div>

        <!-- SCRIPTS (El orden importa: primero Vue, después BotUI y al final tu app.js) -->
        <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/botui/build/botui.min.js"></script>
        <script src="{{ asset('js/app.js') }}"></script>

    </body>
</html>