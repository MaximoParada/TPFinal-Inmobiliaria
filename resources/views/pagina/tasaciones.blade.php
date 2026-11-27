@extends('layouts.app')

@section('title', 'Tasaciones — LoncoProp Inmobiliaria')

@section('contenido')
<div class="container-fluid py-5 tasaciones-wrapper">
    <div class="row g-5 align-items-start justify-content-between">

        <!-- Información y pasos -->
        <div class="col-12 col-lg-5">
            <span class="text-uppercase fw-semibold small d-block mb-2 tasaciones-eyebrow">
                Valuación Profesional
            </span>

            <h1 class="titulo-tasaciones fw-bolder mb-3">
                Conocé el valor real de tu propiedad.
            </h1>

            <p class="fs-6 mb-5 tasaciones-lead">
                Analizamos la ubicación, metros cuadrados y demanda actual de la zona para entregarte una tasación precisa en menos de 24 horas.
            </p>

            <div class="d-flex align-items-start gap-3 mb-4">
                <span class="step-circle">1</span>
                <div>
                    <h3 class="h6 fw-bold mb-1 tasaciones-step-title">Compartí los datos básicos</h3>
                    <p class="small mb-0 tasaciones-step-text">Indicá tipo de inmueble, barrio y superficie aproximada.</p>
                </div>
            </div>

            <div class="d-flex align-items-start gap-3 mb-4">
                <span class="step-circle">2</span>
                <div>
                    <h3 class="h6 fw-bold mb-1 tasaciones-step-title">Análisis comparativo</h3>
                    <p class="small mb-0 tasaciones-step-text">Comparamos valores reales de cierre en tu misma zona.</p>
                </div>
            </div>

            <div class="d-flex align-items-start gap-3 mb-5">
                <span class="step-circle">3</span>
                <div>
                    <h3 class="h6 fw-bold mb-1 tasaciones-step-title">Informe sin compromiso</h3>
                    <p class="small mb-0 tasaciones-step-text">Recibís el rango estimado para vender o alquilar rápido.</p>
                </div>
            </div>

            <div class="tasaciones-bot-box p-4 small" role="button" onclick="toggleChatbot()">
                <strong class="d-block mb-1">¿Preferís hacerlo más rápido?</strong>
                Abrí nuestro asistente virtual en la esquina superior derecha y scambeleccioná <em>"Quiero tasar mi propiedad"</em> para guiarte paso a paso.
            </div>
        </div>

        <!-- Formulario -->
        <div class="col-12 col-lg-6">
            <div class="form-card bg-white p-4 p-md-5">
                <h2 class="h4 fw-bold mb-1 tasaciones-form-title">Solicitar tasación</h2>
                <p class="small mb-4 tasaciones-step-text">Completá los datos y un asesor se comunicará con vos.</p>

                <form id="tasacionForm">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-sm-6">
                            <label for="operacion" class="form-label fw-semibold small">Objetivo</label>
                            <select id="operacion" name="operacion" class="form-select" required>
                                <option value="venta">Quiero Vender</option>
                                <option value="alquiler">Quiero Alquilar</option>
                            </select>
                        </div>

                        <div class="col-12 col-sm-6">
                            <label for="tipo" class="form-label fw-semibold small">Tipo de propiedad</label>
                            <select id="tipo" name="tipo" class="form-select" required>
                                <option value="departamento">Departamento</option>
                                <option value="casa">Casa</option>
                                <option value="terreno">Terreno / Lote</option>
                                <option value="local">Local Comercial</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-sm-6">
                            <label for="zona" class="form-label fw-semibold small">Zona o Barrio</label>
                            <select id="zona" name="zona" class="form-select" required>
                                <option value="">Seleccioná una zona</option>
                                <option value="Zona Este">Zona Este</option>
                                <option value="Zona Centro">Zona Centro</option>
                                <option value="Alta Barda">Alta Barda</option>
                            </select>
                        </div>

                        <div class="col-12 col-sm-6">
                            <label for="metros" class="form-label fw-semibold small">Superficie aprox. (m²)</label>
                            <input type="number" id="metros" name="metros" class="form-control" placeholder="Ej: 85" min="1" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-semibold small">Tu nombre</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Nombre y apellido" required>
                    </div>

                    <div class="mb-4">
                        <label for="contacto" class="form-label fw-semibold small">Teléfono o WhatsApp</label>
                        <input type="tel" id="contacto" name="contacto" class="form-control" placeholder="+54 299 ..." required>
                    </div>

                    <button type="submit" class="btn btn-tasacion-submit w-100 rounded-pill py-3 fw-semibold">
                        Solicitar valuación gratuita
                    </button>

                    <div class="alert alert-success mt-3 mb-0 text-center small d-none" id="successMsg" role="alert">
                        ¡Solicitud recibida! Un asesor analizará los datos de tu propiedad y te contactará a la brevedad.
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection