@extends('layouts.app')

@section('title', 'Turnero de Visitas — LoncoProp Inmobiliaria')

@section('contenido')
<div id="turneroApp"
     class="container-fluid py-5 turnero-wrapper"
     data-es-admin="{{ $esAdmin ? '1' : '0' }}"
     data-csrf="{{ csrf_token() }}"
     data-url-habilitar="{{ route('turnero.habilitar') }}"
     data-url-reservar="{{ route('turnero.reservar') }}">

    <div class="row g-5 align-items-start justify-content-between">

        <!-- COLUMNA IZQUIERDA: Título, pasos verticales y código de propiedad -->
        <div class="col-12 col-lg-5">
            

            <h1 class="titulo-tasaciones fw-bolder mb-3">
                {{ $esAdmin ? 'Administrá los turnos de cada propiedad.' : 'Reservá tu visita con nosotros.' }}
            </h1>

            <p class="fs-6 mb-5 turnero-lead">
                @if($esAdmin)
                    Ingresá el código de una propiedad para habilitar nuevas fechas y horarios de visita, o revisá los turnos ya confirmados.
                @else
                    Ingresá o pegá el código de la propiedad que te interesa para ver las fechas y horarios habilitados. Al confirmar, el horario desaparece para el resto de los usuarios.
                @endif
            </p>

        
            @if($esAdmin)
                <div class="d-flex align-items-start gap-3 mb-4">
                    <span class="step-circle">1</span>
                    <div>
                        <h2 class="h6 fw-bold mb-1 turnero-step-title">Ingresá el código</h2>
                        <p class="small mb-0 turnero-step-text">Escribí o pegá el ID del inmueble al que querés cargarle disponibilidad.</p>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 mb-4">
                    <span class="step-circle">2</span>
                    <div>
                        <h2 class="h6 fw-bold mb-1 turnero-step-title">Tocá el día y horarios</h2>
                        <p class="small mb-0 turnero-step-text">Hacé clic en un día del calendario, marcá los horarios y guardá.</p>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 mb-5">
                    <span class="step-circle">3</span>
                    <div>
                        <h2 class="h6 fw-bold mb-1 turnero-step-title">Controlá la agenda</h2>
                        <p class="small mb-0 turnero-step-text">En verde ves los turnos libres (podés quitarlos) y en rojo los ya reservados.</p>
                    </div>
                </div>
            @else
                <div class="d-flex align-items-start gap-3 mb-4">
                    <span class="step-circle">1</span>
                    <div>
                        <h2 class="h6 fw-bold mb-1 turnero-step-title">Ingresá el código</h2>
                        <p class="small mb-0 turnero-step-text">Ingresá el ID de la propiedad (ej: 102) para cargar sus fechas libres.</p>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 mb-4">
                    <span class="step-circle">2</span>
                    <div>
                        <h2 class="h6 fw-bold mb-1 turnero-step-title">Tocá un día u horario</h2>
                        <p class="small mb-0 turnero-step-text">Seleccioná directamente un día disponible o un horario en el calendario.</p>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 mb-5">
                    <span class="step-circle">3</span>
                    <div>
                        <h2 class="h6 fw-bold mb-1 turnero-step-title">Confirmá tu visita</h2>
                        <p class="small mb-0 turnero-step-text">Verificá el resumen de tu turno y reservá al instante.</p>
                    </div>
                </div>
            @endif

            <!-- Buscador por código de propiedad -->
            <div class="pt-4 turnero-codigo-seccion">
                <label for="inputCodigoPropiedad" class="form-label fw-bold small text-uppercase turnero-eyebrow">
                    Código de propiedad
                </label>
                <div class="turnero-codigo-box d-flex align-items-center gap-2">
                    <input type="text"
                           id="inputCodigoPropiedad"
                           class="form-control turnero-codigo-input"
                           placeholder="Ej: 102 o #102"
                           autocomplete="off">
                    <button type="button" id="btnBuscarCodigoPropiedad" class="btn-turnero-buscar">
                        Buscar
                    </button>
                </div>
                <div id="infoPropiedadElegida" class="turnero-prop-elegida d-none mt-3"></div>
                <div id="errorCodigoPropiedad" class="text-danger small fw-semibold d-none mt-2">
                    No encontramos ninguna propiedad con ese código. Verificá el número e intentá de nuevo.
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: Calendario y formulario de acción -->
        <div class="col-12 col-lg-7">
            <div id="avisoSeleccion" class="turnero-aviso py-5 px-4 text-center small mb-4">
                Ingresá el código de la propiedad a la izquierda para desplegar su calendario de turnos.
            </div>

            <div id="contenedorCalendario" class="d-none mb-4">
                @if($esAdmin)
                    <div class="d-flex gap-3 mb-3 small turnero-step-text">
                        <span><span class="badge rounded-pill" style="background-color:#16a34a;">&nbsp;</span> Disponible</span>
                        <span><span class="badge rounded-pill bg-danger">&nbsp;</span> Reservado por cliente</span>
                    </div>
                @endif
                <div id="calendar"></div>
            </div>

            @if($esAdmin)
                <div class="form-card bg-white p-4 p-md-5">
                    <h2 class="h4 fw-bold mb-1 turnero-step-title">Habilitar horarios</h2>
                    <p class="small mb-4 turnero-step-text">Tocá un día en el calendario o elegí la fecha abajo para cargar disponibilidad.</p>

                    <form id="formAdminHabilitar">
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-sm-6">
                                <label for="adminFecha" class="form-label fw-semibold small">Fecha</label>
                                <input type="date" id="adminFecha" class="form-control" min="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label for="horaExtra" class="form-label fw-semibold small">Otro horario puntual (opcional)</label>
                                <input type="time" id="horaExtra" class="form-control">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold small d-block">Horarios sugeridos</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach(['09:00', '10:00', '11:00', '14:30', '15:30', '16:30', '17:30'] as $h)
                                    <div>
                                        <input type="checkbox" class="btn-check check-hora" id="h_{{ str_replace(':', '', $h) }}" value="{{ $h }}">
                                        <label class="hora-chip" for="h_{{ str_replace(':', '', $h) }}">{{ $h }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" id="btnHabilitar" class="btn btn-tasacion-submit w-100 rounded-pill py-3 fw-semibold" disabled>
                            Habilitar turnos seleccionados
                        </button>

                        <div id="alertaAdmin" class="alert mt-3 mb-0 text-center small d-none" role="alert"></div>
                    </form>

                    <div id="detalleTurnoAdmin" class="mt-4 pt-4 border-top d-none">
                        <h3 class="h6 fw-bold mb-2 turnero-step-title">Detalle del turno seleccionado</h3>
                        <p id="textoDetalleAdmin" class="small turnero-step-text mb-3"></p>
                        <button type="button" id="btnEliminarTurno" class="btn btn-outline-danger btn-sm rounded-pill px-3 d-none">
                            Eliminar horario disponible
                        </button>
                    </div>
                </div>
            @else
                <div class="form-card bg-white p-4 p-md-5">
                    <h2 class="h4 fw-bold mb-1 turnero-step-title">Confirmar visita</h2>
                    <p class="small mb-4 turnero-step-text">
                        Tocá un día disponible u horario libre en el calendario para reservarlo a nombre de <strong>{{ Auth::user()->name }}</strong>.
                    </p>

                    <form id="formUsuarioReservar">
                        <input type="hidden" id="inputTurnoId">

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-sm-6">
                                <label class="form-label fw-semibold small">Propiedad</label>
                                <input type="text" id="resumenPropiedad" class="form-control" placeholder="Ingresá un código de propiedad" readonly>
                            </div>

                            <div class="col-12 col-sm-6">
                                <label class="form-label fw-semibold small">Fecha y horario</label>
                                <input type="text" id="resumenFecha" class="form-control fw-semibold" placeholder="Tocá un día u horario" readonly>
                            </div>
                        </div>

                        <button type="submit" id="btnConfirmarReserva" class="btn btn-tasacion-submit w-100 rounded-pill py-3 fw-semibold" disabled>
                            Confirmar reserva de visita
                        </button>

                        <div id="alertaUsuario" class="alert mt-3 mb-0 text-center small d-none" role="alert"></div>
                    </form>
                </div>
            @endif
        </div>

    </div>
</div>

<script type="application/json" id="datos-propiedades-turnero">
    @json($propiedades)
</script>
@endsection