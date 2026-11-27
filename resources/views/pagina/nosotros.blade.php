@extends('layouts.app')

@section('title', 'Nosotros — LoncoProp Inmobiliaria')

@section('contenido')
<div class="container-fluid py-5 nosotros-wrapper">

    <!-- Título -->
    <section class="mb-5">
        <span class="text-uppercase fw-semibold small d-block mb-3 nosotros-eyebrow">
            Sobre LoncoProp
        </span>

        <h1 class="titulo-nosotros fw-bolder mb-4">
            Redefinimos la forma de encontrar tu próximo lugar.
        </h1>

        <p class="nosotros-lead mb-0">
            Combinamos el conocimiento local del mercado inmobiliario con herramientas digitales ágiles para que alquilar, comprar o tasar sea un proceso transparente y sin demoras.
        </p>
    </section>

    <!-- Banner -->
    <div class="nosotros-banner mb-5">
        <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1800&q=80" 
             alt="Oficina moderna minimalista">
    </div>

    <!-- Datos clave -->
    <section class="row g-4 py-4 my-5 nosotros-stats">
        <div class="col-12 col-md-4">
            <div class="stat-number fw-bolder">+450</div>
            <p class="small mb-0 nosotros-stat-desc">Propiedades gestionadas en toda la región.</p>
        </div>

        <div class="col-12 col-md-4">
            <div class="stat-number fw-bolder">24/7</div>
            <p class="small mb-0 nosotros-stat-desc">Atención inmediata mediante nuestro asistente virtual.</p>
        </div>

        <div class="col-12 col-md-4">
            <div class="stat-number fw-bolder">98%</div>
            <p class="small mb-0 nosotros-stat-desc">De clientes que concretan su búsqueda en menos de 15 días.</p>
        </div>
    </section>

    <!-- Cómo trabajamos -->
    <section class="row g-5 align-items-start my-5">
        <div class="col-12 col-lg-4">
            <h2 class="h4 fw-bold mb-3 nosotros-subtitulo">Cómo trabajamos</h2>
            <p class="small mb-0 nosotros-texto-suave" style="line-height: 1.6;">
                Eliminamos la burocracia innecesaria para conectar personas con espacios reales.
            </p>
        </div>

        <div class="col-12 col-lg-8">
            <div class="row g-4">
                <div class="col-12 col-md-6">
                    <article class="pillar-card h-100">
                        <h3 class="h6 fw-bold mb-2">Búsqueda Inteligente</h3>
                        <p class="small mb-0">
                            Nuestro chatbot filtra opciones según tu presupuesto, zona y cantidad de ambientes en segundos.
                        </p>
                    </article>
                </div>

                <div class="col-12 col-md-6">
                    <article class="pillar-card h-100">
                        <h3 class="h6 fw-bold mb-2">Tasaciones Reales</h3>
                        <p class="small mb-0">
                            Evaluamos cada propiedad con datos actualizados del mercado para asegurar valores justos y competitivos.
                        </p>
                    </article>
                </div>

                <div class="col-12 col-md-6">
                    <article class="pillar-card h-100">
                        <h3 class="h6 fw-bold mb-2">Visitas Coordinadas</h3>
                        <p class="small mb-0">
                            Agendá recorridos presenciales o virtuales directamente desde el chat sin esperas telefónicas.
                        </p>
                    </article>
                </div>

                <div class="col-12 col-md-6">
                    <article class="pillar-card h-100">
                        <h3 class="h6 fw-bold mb-2">Acompañamiento Legal</h3>
                        <p class="small mb-0">
                            Te asesoramos en contratos, garantías y documentación hasta la entrega de llaves.
                        </p>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <!-- Llamado a la acción -->
    <section class="nosotros-cta text-center text-white mt-5">
        <h2 class="fw-bold mb-3">¿Tenés dudas sobre cómo empezar?</h2>
        <p class="mx-auto mb-4">
            Probá nuestro asistente virtual en la esquina superior derecha o iniciá una consulta guiada ahora mismo.
        </p>
        <button type="button" onclick="toggleChatbot()" class="btn btn-light rounded-pill px-4 py-3 fw-semibold btn-nosotros-cta">
            Iniciar consulta en el chat
        </button>
    </section>

</div>
@endsection