@extends('layouts.app')

@section('title', 'Inicio — LoncoProp Inmobiliaria')

@section('contenido')

  <!-- 1. SECCIÓN HERO CENTRADA -->
  <header class="hero d-flex align-items-center justify-content-center text-center">
    <div class="container hero-content">
      <h1 class="hero-title">LoncoProp</h1>
      <p class="hero-subtitle mx-auto">
        Comprar o alquilar tu próxima propiedad, de forma simple y segura.
      </p>

      <!-- Barra de filtros rápidos -->
      <form id="form-buscador" class="search-bar mx-auto" method="GET" action="{{ route('propiedades.filtrar') }}">
        <div class="search-field">
          <select name="operacion" aria-label="Operación">
            <option value="">Operación</option>
            <option value="alquiler">Alquilar</option>
            <option value="venta">Comprar</option>
          </select>
        </div>
        <div class="search-field">
          <select name="zona" aria-label="Zona">
            <option value="">Zona / Barrio</option>
            <option value="Centro">Centro</option>
            <option value="Zona Este">Zona Este</option>
            <option value="Alta Barda">Alta Barda</option>
          </select>
        </div>
        <div class="search-field">
          <select name="ambientes" aria-label="Ambientes">
            <option value="">Ambientes</option>
            <option value="1">1 Ambiente</option>
            <option value="2">2 Ambientes</option>
            <option value="3">3+ Ambientes</option>
          </select>
        </div>
        <div class="search-field">
          <select name="presupuesto" aria-label="Presupuesto">
            <option value="">Presupuesto</option>
            <option value="low">Hasta $450.000</option>
            <option value="mid">$450.000 - $800.000</option>
            <option value="high">Más de $800.000</option>
          </select>
        </div>
        <button type="submit" class="btn-search">Buscar ahora</button>
      </form>
    </div>
  </header>

  <!-- 2. SECCIÓN PROPUESTA Y DESTACADOS -->
  <section class="container py-5 showcase" id="propiedades" style="max-width: 1140px;">
    
    <div class="showcase-header d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-4 mb-5">
      <h2 class="showcase-statement mb-0">
        Nuestros asesores —y nuestro asistente virtual 24/7— te acompañan en cada paso para garantizar la mejor experiencia inmobiliaria de la ciudad.
      </h2>

      <button type="button" class="btn-bot-inicio" onclick="toggleChatbot()">
        Nuestro asistente virtual
      </button>
    </div>

    <div class="cards-grid" id="contenedor-tarjetas">
      @forelse (($propiedades ?? collect())->unique('id') as $propiedad)
        <article class="property-card" data-id="{{ $propiedad->id }}">
          <!-- Carrusel de imágenes -->
          <div class="card-img-wrapper card-carousel" data-index="0">
            <span class="badge-operacion">{{ ucfirst($propiedad->operacion) }}</span>
            <span class="badge-id">ID #{{ $propiedad->id }}</span>

            <div class="carousel-track">
              @foreach ($propiedad->imagenes_lista ?? ['img/sin-imagen.jpg'] as $idx => $rutaImg)
                <img
                  src="{{ Str::startsWith($rutaImg, ['http://', 'https://']) ? $rutaImg : asset(ltrim($rutaImg, '/')) }}"
                  alt="{{ $propiedad->titulo }} - Imagen {{ $idx + 1 }}"
                  class="carousel-slide {{ $idx === 0 ? 'active' : '' }}"
                >
              @endforeach
            </div>

            @if (count($propiedad->imagenes_lista ?? []) > 1)
              <button type="button" class="carousel-btn prev" aria-label="Imagen anterior">❮</button>
              <button type="button" class="carousel-btn next" aria-label="Imagen siguiente">❯</button>

              <div class="carousel-dots">
                @foreach ($propiedad->imagenes_lista as $idx => $rutaImg)
                  <button
                    type="button"
                    class="carousel-dot {{ $idx === 0 ? 'active' : '' }}"
                    data-slide="{{ $idx }}"
                    aria-label="Ir a imagen {{ $idx + 1 }}"
                  ></button>
                @endforeach
              </div>
            @endif
          </div>

          <!-- Atributos de la propiedad -->
          <div class="card-body">
            <div class="card-price-row">
              <span class="card-price">${{ number_format((float) $propiedad->precio, 0, ',', '.') }}</span>
              @if (!is_null($propiedad->expensas) && (float) $propiedad->expensas > 0)
                <span class="card-expensas">+ ${{ number_format((float) $propiedad->expensas, 0, ',', '.') }} expensas</span>
              @else
                <span class="card-expensas">Sin expensas</span>
              @endif
            </div>

            <h3 class="card-title">{{ $propiedad->titulo }}</h3>
            <p class="card-location">{{ $propiedad->direccion }} — {{ $propiedad->zona }}</p>

            <div class="card-specs">
              <span>{{ $propiedad->ambientes }} {{ $propiedad->ambientes == 1 ? 'ambiente' : 'ambientes' }}</span>
              <span>•</span>
              <span>
                @if (($propiedad->dormitorios ?? 0) == 0)
                  Monoambiente
                @else
                  {{ $propiedad->dormitorios }} {{ $propiedad->dormitorios == 1 ? 'dormitorio' : 'dormitorios' }}
                @endif
              </span>
              <span>•</span>
              <span>{{ $propiedad->metros_cuadrados }} m²</span>
            </div>
          </div>
        </article>
      @empty
        <p class="empty-properties">No hay propiedades disponibles por el momento.</p>
      @endforelse
    </div>

    <!-- Botón para volver a ver todas las propiedades cuando hay resultados filtrados -->
    <div id="accion-reset-filtros" class="text-center mt-4 d-none">
      <button type="button" id="btn-ver-todas" class="btn-search">
        Ver todas las propiedades
      </button>
    </div>
  </section>

  <!-- JSON INICIAL DE PROPIEDADES CON CARACTERÍSTICAS -->
  <script type="application/json" id="datos-propiedades-iniciales">
    @json(($propiedades ?? collect())->unique('id')->values())
  </script>

  <!-- FLOTANTE DETALLE COMPLETO DE PROPIEDAD -->
  <div id="modalPropiedad" class="lp-modal-backdrop d-none" aria-hidden="true">
    <div class="lp-modal-card lp-modal-propiedad" role="dialog" aria-modal="true" aria-labelledby="propModalTitulo">
      <div class="lp-modal-header">
        <div>
          <span id="propModalBadge" class="lp-prop-badge"></span>
          <h2 id="propModalTitulo" class="lp-modal-title mt-1"></h2>
        </div>
        <button type="button" class="lp-modal-close" onclick="cerrarModalPropiedad()" aria-label="Cerrar">×</button>
      </div>

      <div id="propModalGaleria" class="lp-prop-galeria mb-3"></div>

      <div class="lp-prop-precio-box mb-3">
        <span id="propModalPrecio" class="lp-prop-precio"></span>
        <span id="propModalExpensas" class="lp-prop-expensas"></span>
      </div>

      <div class="lp-prop-datos-grid mb-3">
        <div class="lp-dato-item">
          <span>Dirección</span>
          <strong id="propModalDireccion"></strong>
        </div>
        <div class="lp-dato-item">
          <span>Zona / Barrio</span>
          <strong id="propModalZona"></strong>
        </div>
        <div class="lp-dato-item">
          <span>Ambientes</span>
          <strong id="propModalAmbientes"></strong>
        </div>
        <div class="lp-dato-item">
          <span>Dormitorios</span>
          <strong id="propModalDormitorios"></strong>
        </div>
        <div class="lp-dato-item">
          <span>Superficie</span>
          <strong id="propModalMetros"></strong>
        </div>
        <div class="lp-dato-item">
          <span>Código de propiedad</span>
          <strong id="propModalId"></strong>
        </div>
      </div>

      <div class="lp-prop-seccion-caract">
        <span class="lp-prop-subtitulo">Características</span>
        <div id="propModalCaracteristicas" class="lp-prop-tags"></div>
      </div>
    </div>
  </div>

@endsection