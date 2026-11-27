@extends('layouts.app')
@section('title', 'Crear Propiedad - LoncoProp Inmobiliaria')
@section('contenido')
  <div class="container py-5 lp-prop-form-section">
    <div class="lp-prop-form-card">
      <div class="lp-prop-form-header">
        <h2>Agregar Nueva Propiedad</h2>
        <p>Completá los datos para publicar una propiedad.</p>
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

      <form action="{{ route('propiedades.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="lp-prop-form-grid">
          <div class="lp-prop-field">
            <label>Título:</label>
            <input type="text" name="titulo" value="{{ old('titulo') }}" required>
          </div>

          <div class="lp-prop-field">
            <label>Operación:</label>
            <select name="operacion" required>
              <option value="venta" {{ old('operacion') == 'venta' ? 'selected' : '' }}>Venta</option>
              <option value="alquiler" {{ old('operacion') == 'alquiler' ? 'selected' : '' }}>Alquiler</option>
            </select>
          </div>

          <div class="lp-prop-field">
            <label>Dirección:</label>
            <input type="text" name="direccion" value="{{ old('direccion') }}" required>
          </div>

          <div class="lp-prop-field">
            <label>Zona / Barrio:</label>
            <input type="text" name="zona" value="{{ old('zona') }}" required>
          </div>

          <div class="lp-prop-field">
            <label>Ambientes:</label>
            <input type="number" name="ambientes" min="1" value="{{ old('ambientes') }}" required>
          </div>

          <div class="lp-prop-field">
            <label>Dormitorios:</label>
            <input type="number" name="dormitorios" min="0" value="{{ old('dormitorios') }}" required>
          </div>

          <div class="lp-prop-field">
            <label>Metros cuadrados (m²):</label>
            <input type="number" name="metros_cuadrados" min="1" value="{{ old('metros_cuadrados') }}" required>
          </div>

          <div class="lp-prop-field">
            <label>Precio:</label>
            <input type="number" step="0.01" name="precio" min="0" value="{{ old('precio') }}" required>
          </div>

          <div class="lp-prop-field">
            <label>Expensas:</label>
            <input type="number" step="0.01" name="expensas" min="0" value="{{ old('expensas', 0) }}">
          </div>

          <div class="lp-prop-field">
            <label>Imágenes (podés seleccionar varias):</label>
            <input type="file" name="imagenes[]" multiple accept="image/*">
          </div>
        </div>

        <fieldset class="lp-prop-fieldset">
          <legend>Características (Relación N:M)</legend>
          <div class="lp-prop-checks">
            @foreach ($caracteristicas as $carac)
              <label class="lp-prop-check-item">
                <input type="checkbox" name="caracteristicas[]" value="{{ $carac->id }}"
                  {{ in_array($carac->id, old('caracteristicas', [])) ? 'checked' : '' }}>
                {{ $carac->nombre }}
              </label>
            @endforeach
          </div>
        </fieldset>

        <div class="lp-prop-form-actions">
          <a href="{{ url('/') }}" class="lp-btn-form-cancelar">Cancelar</a>
          <button type="submit" class="lp-btn-form-guardar">Guardar Propiedad</button>
        </div>
      </form>
    </div>
  </div>
@endsection