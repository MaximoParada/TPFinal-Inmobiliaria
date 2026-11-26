@extends('layouts.app')

@section('title', 'Turnero de Visitas — LoncoProp Inmobiliaria')

@section('contenido')
{{-- Contenedor principal: pasa datos de Blade al archivo app.js mediante atributos data-* --}}
<div id="turneroApp"
     class="container py-5"
     data-es-admin="{{ $esAdmin ? '1' : '0' }}"
     data-csrf="{{ csrf_token() }}"
     data-url-habilitar="{{ route('turnero.habilitar') }}"
     data-url-reservar="{{ route('turnero.reservar') }}">

    {{-- encabezado sewgun rol usuario o admin--}}
    <div class="row mb-4">
        <div class="col-12 col-lg-8">
            
            <h1 class="titulo-tasaciones fw-bolder mb-3">
                {{ $esAdmin ? 'Administrá los turnos de cada propiedad.' : 'Reservá tu visita en pocos clics.' }}
            </h1>
            <p class="text-muted fs-6 mb-0">
                @if($esAdmin)
                    Seleccioná una propiedad para habilitar nuevas fechas y horarios de visita, o revisá los turnos ya confirmados por clientes.
                @else
                    Elegí la propiedad que querés conocer para ver las fechas y horarios habilitados. Al confirmar, el horario desaparece para el resto de los usuarios.
                @endif
            </p>
        </div>
    </div>


    <div class="row g-3 mb-5">
        @if($esAdmin)
            <div class="col-12 col-md-4">
                <div class="d-flex align-items-start gap-3 p-3 rounded-4 border bg-white h-100">
                    <span class="step-circle">1</span>
                    <div>
                        <h2 class="h6 fw-bold mb-1">Elegí la propiedad</h2>
                        <p class="text-muted small mb-0">Seleccioná el inmueble al que querés cargarle disponibilidad.</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="d-flex align-items-start gap-3 p-3 rounded-4 border bg-white h-100">
                    <span class="step-circle">2</span>
                    <div>
                        <h2 class="h6 fw-bold mb-1">Definí fecha y horarios</h2>
                        <p class="text-muted small mb-0">Marcá uno o varios horarios (o agregá uno puntual) y guardá.</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="d-flex align-items-start gap-3 p-3 rounded-4 border bg-white h-100">
                    <span class="step-circle">3</span>
                    <div>
                        <h2 class="h6 fw-bold mb-1">Controlá la agenda</h2>
                        <p class="text-muted small mb-0">En negro ves los turnos libres (podés quitarlos) y en rojo los ya reservados.</p>
                    </div>
                </div>
            </div>
        @else
            <div class="col-12 col-md-4">
                <div class="d-flex align-items-start gap-3 p-3 rounded-4 border bg-white h-100">
                    <span class="step-circle">1</span>
                    <div>
                        <h2 class="h6 fw-bold mb-1">Elegí la propiedad</h2>
                        <p class="text-muted small mb-0">Seleccioná el departamento o casa para cargar sus fechas libres.</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="d-flex align-items-start gap-3 p-3 rounded-4 border bg-white h-100">
                    <span class="step-circle">2</span>
                    <div>
                        <h2 class="h6 fw-bold mb-1">Tocá un horario libre</h2>
                        <p class="text-muted small mb-0">Hacé clic sobre un horario disponible dentro del calendario.</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="d-flex align-items-start gap-3 p-3 rounded-4 border bg-white h-100">
                    <span class="step-circle">3</span>
                    <div>
                        <h2 class="h6 fw-bold mb-1">Confirmá tu visita</h2>
                        <p class="text-muted small mb-0">Verificá el resumen de tu turno y reservá al instante.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="row g-4 align-items-start">

        <div class="col-12 col-lg-7">
            <div class="bg-white border rounded-4 p-4">
                <div class="mb-4">
                    <label for="selectPropiedad" class="form-label fw-bold small text-uppercase text-muted">
                        1. Propiedad seleccionada
                    </label>
                    <select id="selectPropiedad" class="form-select form-select-lg rounded-3 fs-6 fw-semibold">
                        <option value="">-- Seleccioná una propiedad --</option>
                        @foreach ($propiedades as $prop)
                            <option value="{{ $prop->id }}">
                                #{{ $prop->id }} — {{ $prop->titulo }} ({{ $prop->direccion }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Mensaje inicial antes de elegir propiedad --}}
                <div id="avisoSeleccion" class="text-center py-5 px-3 bg-light rounded-4 text-muted small">
                    Elegí una propiedad arriba para ver su calendario de turnos.
                </div>

                {{-- Acá adentro FullCalendar va a dibujar el calendario --}}
                <div id="contenedorCalendario" class="d-none">
                    @if($esAdmin)
                        <div class="d-flex gap-3 mb-3 small text-muted">
                            <span><span class="badge rounded-pill bg-dark">&nbsp;</span> Disponible</span>
                            <span><span class="badge rounded-pill bg-danger">&nbsp;</span> Reservado por cliente</span>
                        </div>
                    @endif
                    <div id="calendar"></div>
                </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA --}}
        <div class="col-12 col-lg-5">
            @if($esAdmin)
                {{-- PANEL DEL ADMINISTRADOR --}}
                <div class="form-card bg-white border rounded-4 p-4 p-md-5">
                    <h2 class="h4 fw-bold mb-1">Habilitar horarios</h2>
                    <p class="text-muted small mb-4">Cargá disponibilidad para la propiedad elegida (o hacé clic en un día del calendario).</p>

                    <form id="formAdminHabilitar">
                        <div class="mb-3">
                            <label for="adminFecha" class="form-label fw-semibold small">Fecha</label>
                            <input type="date" id="adminFecha" class="form-control" min="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="mb-3">
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

                        <div class="mb-4">
                            <label for="horaExtra" class="form-label fw-semibold small">Otro horario puntual (opcional)</label>
                            <input type="time" id="horaExtra" class="form-control">
                        </div>

                        <button type="submit" id="btnHabilitar" class="btn btn-dark w-100 rounded-pill py-3 fw-semibold" disabled>
                            Habilitar turnos seleccionados
                        </button>

                        <div id="alertaAdmin" class="alert mt-3 mb-0 text-center small d-none" role="alert"></div>
                    </form>

                    {{-- Detalle al tocar un evento en el calendario --}}
                    <div id="detalleTurnoAdmin" class="mt-4 pt-4 border-top d-none">
                        <h3 class="h6 fw-bold mb-2">Detalle del turno seleccionado</h3>
                        <p id="textoDetalleAdmin" class="small text-muted mb-3"></p>
                        <button type="button" id="btnEliminarTurno" class="btn btn-outline-danger btn-sm rounded-pill px-3 d-none">
                            Eliminar horario disponible
                        </button>
                    </div>
                </div>
            @else
                {{-- PANEL DEL USUARIO / CLIENTE --}}
                <div class="form-card bg-white border rounded-4 p-4 p-md-5">
                    <h2 class="h4 fw-bold mb-1">Confirmar visita</h2>
                    <p class="text-muted small mb-4">
                        Tocá un horario libre en el calendario para reservarlo a nombre de <strong>{{ Auth::user()->name }}</strong>.
                    </p>

                    <form id="formUsuarioReservar">
                        <input type="hidden" id="inputTurnoId">

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Propiedad</label>
                            <input type="text" id="resumenPropiedad" class="form-control bg-light" placeholder="Seleccioná una propiedad" readonly>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold small">Fecha y horario</label>
                            <input type="text" id="resumenFecha" class="form-control bg-light fw-semibold" placeholder="Elegí un horario en el calendario" readonly>
                        </div>

                        <button type="submit" id="btnConfirmarReserva" class="btn btn-dark w-100 rounded-pill py-3 fw-semibold" disabled>
                            Confirmar reserva de visita
                        </button>

                        <div id="alertaUsuario" class="alert mt-3 mb-0 text-center small d-none" role="alert"></div>
                    </form>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection