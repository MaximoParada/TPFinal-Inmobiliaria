# LONCOPROP Sistema de Gestión Inmobiliaria - Trabajo Final Integrador (PWD 2026).

## Integrantes: 
- **Agustina Jamila Vega** `[FAI-4466]`
- **Mateo Iván Parada** `[FAI-5359]`
- **Máximo Juan Parada** `[FAI-4987]`

## Descripción de la Aplicación y Dominio
Aplicación web dinámica desarrollada con **Laravel** bajo el patrón de arquitectura **MVC**, diseñada para la gestión integral de propiedades en venta y alquiler, solicitudes de tasación, atención automatizada mediante *chatbot* y reserva de visitas mediante calendario interactivo.

### Problema que resuelve
En el sector inmobiliario, la gestión manual de consultas, tasaciones y coordinación de visitas suele generar demoras, superposición de turnos y falta de centralización de la información de los inmuebles.

### Solución:
Esta aplicación web centraliza la oferta inmobiliaria y automatiza la interacción inicial entre clientes, propietarios y agentes/administradores:
- Permite a los usuarios explorar propiedades filtrando por criterios específicos.
- Facilita el contacto rápido mediante un asistente virtual (*chatbot*).
- Digitaliza la carga y seguimiento de solicitudes de tasación.
- Integra un turnero interactivo asociado a cada propiedad para visualizar disponibilidad real y agendar visitas sin conflictos de horarios.

## Funcionalidades Implementadas:
- **Gestión de Propiedades (ABM / CRUD):** Alta, consulta, modificación y baja de inmuebles (departamentos, casas, etc.), incluyendo carga de imágenes, características y estado (*disponible, reservado, alquilado/vendido*).
- **Buscador Dinámico de Propiedades:** Filtrado de inmuebles por tipo de operación (*compra/alquiler*), tipo de propiedad, ubicación, cantidad de ambientes y rango de precios.
- **Chatbot Asistente:** Interfaz conversacional integrada que guía al usuario hacia la búsqueda de inmuebles, tasaciones o reserva de turnos consultando datos reales de la aplicación.
- **Gestión de Tasaciones:** Formulario validado para que los usuarios soliciten la tasación de un inmueble y puedan consultar su estado, mientras que los administradores gestionan y responden dichas solicitudes.
- **Turnero de Visitas (FullCalendar):** Búsqueda de departamento/propiedad por ID para visualizar fechas ocupadas y disponibles en un calendario interactivo y reservar turnos de visita.
- **Control de Acceso y Roles:** Registro de usuarios, inicio/cierre de sesión y vistas adaptadas según el perfil del usuario autenticado.

## Modelo de datos y relaciones entre entidades:

La base de datos relacional en **MySQL** se gestiona mediante *migrations*, *seeders* y modelos **Eloquent**, abarcando las tablas `users`, `propiedades`, `caracteristicas`, `caracteristica_propiedad`, `visita`, `referencia_tasaciones`, `palabras clave` y `respuestas_bot`.

- **Relación Uno a Muchos (1:N) — Propiedades y Visitas** (`propiedades` -> `visita`)
  - Una propiedad puede recibir múltiples visitas, mientras que cada registro en `visita` pertenece a una única propiedad (y se asocia al usuario correspondiente en `users`).
  - En Eloquent se define con `hasMany` en el modelo de propiedad y `belongsTo` en el modelo de visita, utilizando esta relación para consultar disponibilidad de fechas en el turnero y registrar nuevas reservas.
- **Relación Muchos a Muchos (N:M) — Propiedades y Características** (`propiedades` <-> `caracteristicas` vía `caracteristica_propiedad`)
  - Una propiedad puede tener varias características (por ejemplo, *cochera, balcón, patio, calefacción*) y una misma característica puede estar presente en muchas propiedades.
  - Se implementa mediante la tabla intermedia `caracteristica_propiedad` y la relación `belongsToMany` en los modelos Eloquent, permitiendo asociar y desasociar características desde la gestión de propiedades.
- **Otras relaciones del dominio** (`users`, `referencia_tasaciones`, `palabras clave` y `respuestas_bot`)
  - Se contemplan las relaciones correspondientes entre `users` y las operaciones/registros del sistema (como visitas o gestión según rol) y entre `referencia_tasaciones` y las propiedades/tasaciones.
  - Para el asistente virtual, las tablas `palabras clave` y `respuestas_bot` conectan los términos consultados por el usuario con las respuestas almacenadas en la base de datos.

## Perfiles de Usuario y Permisos
La aplicación cuenta con autenticación mediante **Laravel** y protección de rutas mediante *Middlewares* (`auth` y *middleware* personalizado de roles):

| Perfil / Rol | Permisos y Funcionalidades Habilitadas |
| :--- | :--- |
| **Visitante (No autenticado)** | Navegar por el catálogo, usar el buscador de propiedades, interactuar con el *chatbot* y registrarse/iniciar sesión. |
| **Cliente (Usuario registrado)** | Todo lo anterior + solicitar tasaciones, buscar propiedades por ID en el turnero, reservar turnos de visita y ver su historial. |
| **Administrador / Agente** | Gestión completa (ABM) de propiedades, asociar/desasociar características (N:M), administrar estados de tasaciones y visualizar/gestionar todos los turnos agendados. |

Las vistas adaptan dinámicamente los menús y botones de acción (`@auth`, `@guest` y verificación de rol) según el perfil activo.

## Funcionalidad AJAX y Comunicación Asíncrona
Se implementó comunicación asíncrona mediante `fetch()` en **JavaScript** para interactuar con los controladores de **Laravel** sin recargar la página completa:
- **Consulta de disponibilidad en el Turnero:** Al ingresar el ID de un departamento/propiedad, `fetch()` envía una petición `GET` a Laravel. El controlador consulta en MySQL los turnos asociados a esa propiedad y retorna una respuesta en formato `JSON`.
- **Reserva de turno / Interacción con Chatbot / Filtros:** Envío de datos de forma asíncrona con token CSRF (`X-CSRF-TOKEN`), actualización dinámica del DOM y manejo de errores (*validación de ID inexistente, horarios superpuestos o fallos de red* mediante bloques `try/catch` y códigos de estado HTTP).

## Librerías Externas Integradas
1. **FullCalendar (Gestión interactiva de turnos):**
   - **Integración:** Conectada mediante AJAX con el backend en Laravel para renderizar en tiempo real las fechas ocupadas y disponibles de cada propiedad a partir de su ID, permitiendo seleccionar fechas para agendar visitas.
   - **Justificación:** Brinda una interfaz visual clara e intuitiva para la gestión de disponibilidad por inmueble, evitando errores en la selección de fechas y mejorando la experiencia del usuario.
2. **Librería de Chatbot (BotUI / widget conversacional):**
   - **Integración:** Integrada en las vistas Blade y conectada con endpoints de Laravel para responder consultas con datos reales del sistema (*propiedades, tasaciones y turnos*).
   - **Justificación:** Agiliza la atención inicial del visitante, guiándolo hacia las funcionalidades principales sin necesidad de navegar manualmente por todo el sitio.
