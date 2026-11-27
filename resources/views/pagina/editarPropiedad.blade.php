@extends('layouts.app')
@section('title', 'EditarPropiedad - LoncoProp Inmobiliaria')
@section('contenido')

 <div class="container">
    <h2>Editar Propiedad: {{ $propiedad->titulo }}</h2>

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

        <div>
            <label>Título:</label>
            <input type="text" name="titulo" value="{{ old('titulo', $propiedad->titulo) }}" required>
        </div>

        <div>
            <label>Operación:</label>
            <select name="operacion" required>
                <option value="venta" {{ old('operacion', $propiedad->operacion) == 'venta' ? 'selected' : '' }}>Venta</option>
                <option value="alquiler" {{ old('operacion', $propiedad->operacion) == 'alquiler' ? 'selected' : '' }}>Alquiler</option>
            </select>
        </div>

        <div>
            <label>Dirección:</label>
            <input type="text" name="direccion" value="{{ old('direccion', $propiedad->direccion) }}" required>
        </div>

        <div>
            <label>Zona / Barrio:</label>
            <input type="text" name="zona" value="{{ old('zona', $propiedad->zona) }}" required>
        </div>

        <div>
            <label>Ambientes:</label>
            <input type="number" name="ambientes" min="1" value="{{ old('ambientes', $propiedad->ambientes) }}" required>
        </div>

        <div>
            <label>Dormitorios:</label>
            <input type="number" name="dormitorios" min="0" value="{{ old('dormitorios', $propiedad->dormitorios) }}" required>
        </div>

        <div>
            <label>Metros cuadrados (m²):</label>
            <input type="number" name="metros_cuadrados" min="1" value="{{ old('metros_cuadrados', $propiedad->metros_cuadrados) }}" required>
        </div>

        <div>
            <label>Precio:</label>
            <input type="number" step="0.01" name="precio" min="0" value="{{ old('precio', $propiedad->precio) }}" required>
        </div>

        <div>
            <label>Expensas:</label>
            <input type="number" step="0.01" name="expensas" min="0" value="{{ old('expensas', $propiedad->expensas) }}">
        </div>

        <div>
            <label>Reemplazar imágenes (opcional):</label>
            <input type="file" name="imagenes[]" multiple accept="image/*">
        </div>

        <fieldset>
            <legend>Características (Asociar / Desasociar)</legend>
            @foreach ($caracteristicas as $carac)
                <label style="margin-right: 12px;">
                    <input type="checkbox" name="caracteristicas[]" value="{{ $carac->id }}"
                        {{ in_array($carac->id, old('caracteristicas', $seleccionadas)) ? 'checked' : '' }}>
                    {{ $carac->nombre }}
                </label>
            @endforeach
        </fieldset>

        <button type="submit">Actualizar Propiedad</button>
        <a href="{{ url('/') }}">Cancelar</a>
    </form>
  </div>
@endsection