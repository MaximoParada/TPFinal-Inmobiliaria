@extends('layouts.app')
@section('title', 'Editar Propiedad - LoncoProp Inmobiliaria')
@section('contenido')

  <div class="container py-5 lp-prop-form-section">
    <div class="lp-prop-form-card">
      <div class="lp-prop-form-header">
        <h2>Editar Propiedad: {{ $propiedad->titulo }}</h2>
        <p>Modificá los datos de la publicación.</p>
      </div>

      @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('propiedades.update', $propiedad) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="lp-prop-form-grid">
          <div class="lp-prop-field">
            <label>Título:</label>
            <input type="text" name="titulo" value="{{ old('titulo', $propiedad->titulo) }}" required>
          </div>

          <div class="lp-prop-field">
            <label>Operación:</label>
            <select name="operacion" required>
              <option value="venta" {{ old('operacion', $propiedad->operacion) == 'venta' ? 'selected' : '' }}>Venta</option>
              <option value="alquiler" {{ old('operacion', $propiedad->operacion) == 'alquiler' ? 'selected' : '' }}>Alquiler</option>
            </select>
          </div>

          <div class="lp-prop-field">
            <label>Dirección:</label>
            <input type="text" name="direccion" value="{{ old('direccion', $propiedad->direccion) }}" required>
          </div>

          <div class="lp-prop-field">
            <label>Zona / Barrio:</label>
            <input type="text" name="zona" value="{{ old('zona', $propiedad->zona) }}" required>
          </div>

          <div class="lp-prop-field">
            <label>Ambientes:</label>
            <input type="number" name="ambientes" min="1" value="{{ old('ambientes', $propiedad->ambientes) }}" required>
          </div>

          <div class="lp-prop-field">
            <label>Dormitorios:</label>
            <input type="number" name="dormitorios" min="0" value="{{ old('dormitorios', $propiedad->dormitorios) }}" required>
          </div>

          <div class="lp-prop-field">
            <label>Metros cuadrados (m²):</label>
            <input type="number" name="metros_cuadrados" min="1" value="{{ old('metros_cuadrados', $propiedad->metros_cuadrados) }}" required>
          </div>

          <div class="lp-prop-field">
            <label>Precio:</label>
            <input type="number" step="0.01" name="precio" min="0" value="{{ old('precio', $propiedad->precio) }}" required>
          </div>

          <div class="lp-prop-field">
            <label>Expensas:</label>
            <input type="number" step="0.01" name="expensas" min="0" value="{{ old('expensas', $propiedad->expensas) }}">
          </div>

          <div class="lp-prop-field">
            <label>Reemplazar imágenes (opcional):</label>
            <input type="file" name="imagenes[]" multiple accept="image/*">
          </div>
        </div>

        <fieldset class="lp-prop-fieldset">
          <legend>Características</legend>
          <div class="lp-prop-checks">
            @foreach ($caracteristicas as $carac)
              <label class="lp-prop-check-item">
                <input type="checkbox" name="caracteristicas[]" value="{{ $carac->id }}"
                  {{ in_array($carac->id, old('caracteristicas', $seleccionadas)) ? 'checked' : '' }}>
                {{ $carac->nombre }}
              </label>
            @endforeach
          </div>
        </fieldset>

        <div class="lp-prop-form-actions">
          <a href="{{ url('/') }}" class="lp-btn-form-cancelar">Cancelar</a>
          <button type="submit" class="lp-btn-form-guardar">Actualizar Propiedad</button>
        </div>
      </form>
    </div>
  </div>
@endsection