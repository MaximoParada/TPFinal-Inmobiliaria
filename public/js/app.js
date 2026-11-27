//-------------------------------------DETALLE DE PROPIEDAD------------------------------------//
const propiedadesPorId = new Map();

function copiarIdPropiedad(event, id, botonEl) {
  if (event) event.stopPropagation(); // Evita abrir el modal si se toca desde la tarjeta
  const textoId = String(id);

  navigator.clipboard.writeText(textoId).then(() => {
    if (!botonEl) return;
    const textoOriginal = botonEl.textContent;
    botonEl.textContent = '¡Copiado!';
    setTimeout(() => {
      botonEl.textContent = textoOriginal;
    }, 1500);
  });
}

function registrarPropiedadesIniciales() {
  const scriptEl = document.getElementById('datos-propiedades-iniciales');
  if (!scriptEl) return;

  try {
    const lista = JSON.parse(scriptEl.textContent || '[]');
    if (Array.isArray(lista)) {
      propiedadesPorId.clear();
      lista.forEach((prop) => {
        if (prop && prop.id !== undefined) {
          propiedadesPorId.set(String(prop.id), prop);
        }
      });
    }
  } catch (err) {
    console.warn('No se pudieron leer las propiedades iniciales:', err);
  }
}

function normalizarImagenesPropiedad(prop) {
  let imagenes = prop.imagenes_lista || prop.imagenes || ['img/sin-imagen.jpg'];
  if (typeof imagenes === 'string') {
    try { imagenes = JSON.parse(imagenes); } catch { imagenes = ['img/sin-imagen.jpg']; }
  }
  if (!Array.isArray(imagenes) || !imagenes.length) {
    imagenes = ['img/sin-imagen.jpg'];
  }
  return imagenes.map((ruta) =>
    /^https?:\/\//i.test(ruta) ? ruta : `/${String(ruta).replace(/^\/+/, '')}`
  );
}

function abrirModalPropiedad(prop) {
  const modal = document.getElementById('modalPropiedad');
  if (!modal || !prop) return;

  const opTexto = prop.operacion ? prop.operacion.charAt(0).toUpperCase() + prop.operacion.slice(1) : 'Propiedad';
  const dormNum = Number(prop.dormitorios ?? 0);
  const dormTexto = dormNum === 0
    ? 'Monoambiente'
    : `${dormNum} ${dormNum === 1 ? 'dormitorio' : 'dormitorios'}`;
  const ambNum = Number(prop.ambientes ?? 0);
  const ambTexto = `${ambNum} ${ambNum === 1 ? 'ambiente' : 'ambientes'}`;

  document.getElementById('propModalBadge').textContent = opTexto;
  document.getElementById('propModalTitulo').textContent = prop.titulo || '';
  document.getElementById('propModalPrecio').textContent = `$${Number(prop.precio || 0).toLocaleString('es-AR')}`;
  document.getElementById('propModalExpensas').textContent =
    prop.expensas && Number(prop.expensas) > 0
      ? `+ $${Number(prop.expensas).toLocaleString('es-AR')} expensas`
      : 'Sin expensas';

  document.getElementById('propModalDireccion').textContent = prop.direccion || 'No especificada';
  document.getElementById('propModalZona').textContent = prop.zona || 'No especificada';
  document.getElementById('propModalAmbientes').textContent = ambTexto;
  document.getElementById('propModalDormitorios').textContent = dormTexto;
  document.getElementById('propModalMetros').textContent = `${prop.metros_cuadrados ?? 0} m²`;
  document.getElementById('propModalId').textContent = `#${prop.id}`;

  const btnCopiarModal = document.getElementById('btnCopiarIdModal');
  if (btnCopiarModal) {
    btnCopiarModal.textContent = 'Copiar ID';
    btnCopiarModal.onclick = function (e) {
      copiarIdPropiedad(e, prop.id, btnCopiarModal);
    };
  }

  const galeria = document.getElementById('propModalGaleria');
  const imgs = normalizarImagenesPropiedad(prop);
  galeria.innerHTML = imgs
    .map((src, i) => `<img src="${src}" alt="${prop.titulo || 'Propiedad'} - foto ${i + 1}">`)
    .join('');

  const contCaract = document.getElementById('propModalCaracteristicas');
  const listaCaract = Array.isArray(prop.caracteristicas) ? prop.caracteristicas : [];
  if (listaCaract.length > 0) {
    contCaract.innerHTML = listaCaract
      .map((c) => {
        const nombre = typeof c === 'string'
          ? c
          : (c.nombre || c.titulo || c.descripcion || '');
        return nombre ? `<span class="lp-prop-tag">${nombre}</span>` : '';
      })
      .join('');
  } else {
    contCaract.innerHTML = '<span class="lp-prop-tag-vacio">Sin características adicionales cargadas.</span>';
  }

  modal.classList.remove('d-none');
  modal.style.display = 'flex';
}

function cerrarModalPropiedad() {
  const modal = document.getElementById('modalPropiedad');
  if (modal) {
    modal.classList.add('d-none');
    modal.style.display = '';
  }
}
//-------------------------------------AUTH FLOTANTE Y PERFIL------------------------------------//
function abrirModalAuth(vista = 'login') {
  const modal = document.getElementById('modalAuth');
  if (!modal) {
    console.warn('No se encontró #modalAuth en el DOM.');
    return;
  }
  limpiarCamposAuth();
  modal.classList.remove('d-none');
  modal.style.display = 'flex';
  cambiarVistaAuth(vista);
}

function cerrarModalAuth() {
  const modal = document.getElementById('modalAuth');
  if (modal) {
    modal.classList.add('d-none');
    modal.style.display = '';
  }
  limpiarCamposAuth();
}

function cambiarVistaAuth(vista) {
  const formLogin = document.getElementById('formLoginFlotante');
  const formRegistro = document.getElementById('formRegistroFlotante');
  const titulo = document.getElementById('tituloModalAuth');

  ocultarAlertaAuth();

  if (vista === 'register') {
    formLogin?.classList.add('d-none');
    formRegistro?.classList.remove('d-none');
    if (titulo) titulo.textContent = 'Crear cuenta';
  } else {
    formRegistro?.classList.add('d-none');
    formLogin?.classList.remove('d-none');
    if (titulo) titulo.textContent = 'Iniciar sesión';
  }
}

function abrirModalPerfil() {
  const modal = document.getElementById('modalPerfil');
  if (!modal) return;
  resetearVistasPerfil();
  modal.classList.remove('d-none');
  modal.style.display = 'flex';
}

function cerrarModalPerfil() {
  const modal = document.getElementById('modalPerfil');
  if (modal) {
    modal.classList.add('d-none');
    modal.style.display = '';
  }
  resetearVistasPerfil();
}

function ocultarMensajesPerfil() {
  const alerta = document.getElementById('alertaPerfil');
  const exito = document.getElementById('exitoPerfil');
  if (alerta) {
    alerta.innerHTML = '';
    alerta.classList.add('d-none');
  }
  if (exito) {
    exito.textContent = '';
    exito.classList.add('d-none');
  }
}

function mostrarAlertaPerfil(mensajes) {
  const alerta = document.getElementById('alertaPerfil');
  if (!alerta) return;
  const lista = Array.isArray(mensajes) ? mensajes : [mensajes];
  alerta.innerHTML = lista.map((m) => `<div>${m}</div>`).join('');
  alerta.classList.remove('d-none');
}

function mostrarExitoPerfil(mensaje) {
  const exito = document.getElementById('exitoPerfil');
  if (!exito) return;
  exito.textContent = mensaje;
  exito.classList.remove('d-none');
}

function resetearVistasPerfil() {
  const lectura = document.getElementById('vistaLecturaPerfil');
  const edicion = document.getElementById('formEditarPerfil');
  const confirmar = document.getElementById('vistaConfirmarEliminar');
  const titulo = document.getElementById('tituloModalPerfil');

  lectura?.classList.remove('d-none');
  edicion?.classList.add('d-none');
  confirmar?.classList.add('d-none');
  if (titulo) titulo.textContent = 'Mi perfil';
  ocultarMensajesPerfil();
}

function mostrarEdicionPerfil() {
  const lectura = document.getElementById('vistaLecturaPerfil');
  const edicion = document.getElementById('formEditarPerfil');
  const confirmar = document.getElementById('vistaConfirmarEliminar');
  const titulo = document.getElementById('tituloModalPerfil');

  ocultarMensajesPerfil();
  lectura?.classList.add('d-none');
  confirmar?.classList.add('d-none');
  edicion?.classList.remove('d-none');
  if (titulo) titulo.textContent = 'Modificar datos';
}

function cancelarEdicionPerfil() {
  resetearVistasPerfil();
}

function mostrarConfirmacionEliminar() {
  const lectura = document.getElementById('vistaLecturaPerfil');
  const edicion = document.getElementById('formEditarPerfil');
  const confirmar = document.getElementById('vistaConfirmarEliminar');
  const titulo = document.getElementById('tituloModalPerfil');

  ocultarMensajesPerfil();
  lectura?.classList.add('d-none');
  edicion?.classList.add('d-none');
  confirmar?.classList.remove('d-none');
  if (titulo) titulo.textContent = 'Eliminar cuenta';
}

function cancelarConfirmacionEliminar() {
  resetearVistasPerfil();
}

function mostrarAlertaAuth(mensajes) {
  const alerta = document.getElementById('alertaAuth');
  if (!alerta) return;
  const lista = Array.isArray(mensajes) ? mensajes : [mensajes];
  alerta.innerHTML = lista.map((m) => `<div>${m}</div>`).join('');
  alerta.classList.remove('d-none');
}

function ocultarAlertaAuth() {
  const alerta = document.getElementById('alertaAuth');
  if (!alerta) return;
  alerta.innerHTML = '';
  alerta.classList.add('d-none');
}

function limpiarCamposAuth() {
  const formLogin = document.getElementById('formLoginFlotante');
  const formRegistro = document.getElementById('formRegistroFlotante');

  if (formLogin) {
    formLogin.reset();
    const loginEmail = document.getElementById('login_email');
    const loginPass = document.getElementById('login_password');
    if (loginEmail) loginEmail.value = '';
    if (loginPass) loginPass.value = '';
  }

  if (formRegistro) {
    formRegistro.reset();
    const regPass = document.getElementById('reg_password');
    if (regPass) regPass.value = '';
    document.getElementById('reglaMayuscula')?.classList.replace('regla-ok', 'regla-pendiente');
    document.getElementById('reglaNumero')?.classList.replace('regla-ok', 'regla-pendiente');
  }

  ocultarAlertaAuth();
}

// TURNERO (fullcalendar, turnocontroller y ajax)
document.addEventListener('DOMContentLoaded', function () {
    const appTurnero = document.getElementById('turneroApp');
    if (!appTurnero) return;

    const esAdmin      = appTurnero.dataset.esAdmin === '1';
    const csrfToken    = appTurnero.dataset.csrf;
    const urlHabilitar = appTurnero.dataset.urlHabilitar;
    const urlReservar  = appTurnero.dataset.urlReservar;

    const inputCodigo          = document.getElementById('inputCodigoPropiedad');
    const btnBuscarCodigo      = document.getElementById('btnBuscarCodigoPropiedad');
    const infoPropiedadEl      = document.getElementById('infoPropiedadElegida');
    const errorCodigoEl        = document.getElementById('errorCodigoPropiedad');
    const avisoSeleccion       = document.getElementById('avisoSeleccion');
    const contenedorCalendario = document.getElementById('contenedorCalendario');
    const calendarEl           = document.getElementById('calendar');

    let propiedadActualId        = null;
    let eventoSeleccionadoEl     = null;
    let diaSeleccionadoEl        = null;
    let turnoAdminSeleccionadoId = null;

    // Mapa rápido de propiedades cargadas por ID
    const mapaPropiedadesTurnero = new Map();
    const scriptProps = document.getElementById('datos-propiedades-turnero');
    if (scriptProps) {
        try {
            const lista = JSON.parse(scriptProps.textContent || '[]');
            lista.forEach(p => mapaPropiedadesTurnero.set(String(p.id), p));
        } catch (e) {
            console.warn('No se pudieron cargar las propiedades del turnero:', e);
        }
    }

    function seleccionarTurnoUsuario(turnoId, fechaLinda, elVisual) {
        if (eventoSeleccionadoEl) {
            eventoSeleccionadoEl.classList.remove('fc-event-seleccionado');
        }
        if (elVisual) {
            elVisual.classList.add('fc-event-seleccionado');
            eventoSeleccionadoEl = elVisual;
        }
        document.getElementById('inputTurnoId').value = turnoId;
        document.getElementById('resumenFecha').value = fechaLinda;
        document.getElementById('btnConfirmarReserva').disabled = false;
        document.getElementById('alertaUsuario').classList.add('d-none');
    }

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        height: 'auto',
        selectable: true,
        displayEventTime: false,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listWeek'
        },
        buttonText: { today: 'Hoy', month: 'Mes', list: 'Lista' },

        events: function (info, successCallback, failureCallback) {
            if (!propiedadActualId) return successCallback([]);

            fetch(`/turnero/eventos/${propiedadActualId}`)
                .then(res => res.json())
                .then(data => successCallback(data))
                .catch(err => failureCallback(err));
        },

        eventClassNames: function (arg) {
            return arg.event.extendedProps.estado === 'confirmada'
                ? ['fc-event-ocupado']
                : ['fc-event-libre'];
        },

        // Clic directo en cualquier día de la grilla
        dateClick: function (info) {
            if (diaSeleccionadoEl) {
                diaSeleccionadoEl.classList.remove('fc-dia-seleccionado');
            }
            if (info.dayEl) {
                info.dayEl.classList.add('fc-dia-seleccionado');
                diaSeleccionadoEl = info.dayEl;
            }

            if (esAdmin) {
                const inputFecha = document.getElementById('adminFecha');
                if (inputFecha) {
                    if (!inputFecha.min || info.dateStr >= inputFecha.min) {
                        inputFecha.value = info.dateStr;
                    }
                }
                return;
            }

            // Si es usuario: buscamos los turnos libres de ese día
            const eventosDelDia = calendar.getEvents().filter(ev =>
                ev.startStr && ev.startStr.startsWith(info.dateStr) && ev.extendedProps.estado === 'disponible'
            );

            if (eventosDelDia.length > 0) {
                const primero = eventosDelDia[0];
                seleccionarTurnoUsuario(primero.extendedProps.turno_id, primero.extendedProps.fecha_linda, null);
            }
        },

        eventClick: function (info) {
            const props = info.event.extendedProps;

            if (esAdmin) {
                const box    = document.getElementById('detalleTurnoAdmin');
                const txt    = document.getElementById('textoDetalleAdmin');
                const btnDel = document.getElementById('btnEliminarTurno');

                box.classList.remove('d-none');
                if (props.estado === 'disponible') {
                    turnoAdminSeleccionadoId = props.turno_id;
                    txt.innerHTML = `<strong>${props.fecha_linda}</strong><br>Estado: <span class="text-success fw-semibold">Disponible</span>`;
                    btnDel.classList.remove('d-none');
                } else {
                    turnoAdminSeleccionadoId = null;
                    txt.innerHTML = `<strong>${props.fecha_linda}</strong><br>Reservado por: <strong>${props.cliente}</strong> (${props.telefono})`;
                    btnDel.classList.add('d-none');
                }
                return;
            }

            seleccionarTurnoUsuario(props.turno_id, props.fecha_linda, info.el);
        }
    });

    calendar.render();

    // Buscar y activar propiedad por código (#102 o 102)
    function buscarPropiedadPorCodigo() {
        const limpio = (inputCodigo.value || '').trim().replace(/^#/, '');

        if (!limpio) {
            propiedadActualId = null;
            infoPropiedadEl.classList.add('d-none');
            errorCodigoEl.classList.add('d-none');
            avisoSeleccion.classList.remove('d-none');
            contenedorCalendario.classList.add('d-none');
            if (esAdmin) {
                document.getElementById('btnHabilitar').disabled = true;
                document.getElementById('detalleTurnoAdmin').classList.add('d-none');
            } else {
                document.getElementById('resumenPropiedad').value = '';
                document.getElementById('resumenFecha').value = '';
                document.getElementById('inputTurnoId').value = '';
                document.getElementById('btnConfirmarReserva').disabled = true;
            }
            return;
        }

        const prop = mapaPropiedadesTurnero.get(limpio);

        if (!prop) {
            propiedadActualId = null;
            infoPropiedadEl.classList.add('d-none');
            errorCodigoEl.classList.remove('d-none');
            avisoSeleccion.classList.remove('d-none');
            contenedorCalendario.classList.add('d-none');
            if (esAdmin) {
                document.getElementById('btnHabilitar').disabled = true;
            } else {
                document.getElementById('resumenPropiedad').value = '';
                document.getElementById('resumenFecha').value = '';
                document.getElementById('inputTurnoId').value = '';
                document.getElementById('btnConfirmarReserva').disabled = true;
            }
            return;
        }

        propiedadActualId = prop.id;
        const textoProp = `#${prop.id} — ${prop.titulo} (${prop.direccion})`;

        errorCodigoEl.classList.add('d-none');
        infoPropiedadEl.textContent = textoProp;
        infoPropiedadEl.classList.remove('d-none');

        avisoSeleccion.classList.add('d-none');
        contenedorCalendario.classList.remove('d-none');

        if (esAdmin) {
            document.getElementById('btnHabilitar').disabled = false;
            document.getElementById('detalleTurnoAdmin').classList.add('d-none');
        } else {
            document.getElementById('resumenPropiedad').value = textoProp;
            document.getElementById('resumenFecha').value = '';
            document.getElementById('inputTurnoId').value = '';
            document.getElementById('btnConfirmarReserva').disabled = true;
        }

        calendar.updateSize();
        calendar.refetchEvents();
    }

    btnBuscarCodigo.addEventListener('click', buscarPropiedadPorCodigo);
    inputCodigo.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            buscarPropiedadPorCodigo();
        }
    });
    inputCodigo.addEventListener('paste', function () {
        setTimeout(buscarPropiedadPorCodigo, 50);
    });

    // Lógica Admin
    if (esAdmin) {
        const formAdmin   = document.getElementById('formAdminHabilitar');
        const alertaAdmin = document.getElementById('alertaAdmin');

        formAdmin.addEventListener('submit', function (e) {
            e.preventDefault();

            const horas = Array.from(document.querySelectorAll('.check-hora:checked')).map(c => c.value);
            const extra = document.getElementById('horaExtra').value;
            if (extra && !horas.includes(extra)) horas.push(extra);

            if (horas.length === 0) {
                alertaAdmin.classList.remove('d-none', 'alert-success');
                alertaAdmin.classList.add('alert-danger');
                alertaAdmin.textContent = 'Seleccioná al menos un horario.';
                return;
            }

            fetch(urlHabilitar, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    propiedad_id: propiedadActualId,
                    fecha: document.getElementById('adminFecha').value,
                    horas: horas
                })
            })
            .then(async res => {
                const data = await res.json();
                alertaAdmin.classList.remove('d-none', 'alert-success', 'alert-danger');
                alertaAdmin.classList.add(res.ok && data.ok ? 'alert-success' : 'alert-danger');
                alertaAdmin.textContent = data.mensaje || 'Error al habilitar horarios.';

                if (res.ok && data.ok) {
                    document.querySelectorAll('.check-hora').forEach(c => c.checked = false);
                    document.getElementById('horaExtra').value = '';
                    calendar.refetchEvents();
                }
            });
        });

        document.getElementById('btnEliminarTurno').addEventListener('click', function () {
            if (!turnoAdminSeleccionadoId) return;

            fetch(`/turnero/eliminar/${turnoAdminSeleccionadoId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('detalleTurnoAdmin').classList.add('d-none');
                alertaAdmin.classList.remove('d-none', 'alert-danger');
                alertaAdmin.classList.add('alert-success');
                alertaAdmin.textContent = data.mensaje;
                calendar.refetchEvents();
            });
        });
    }

    // Lógica Usuario
    if (!esAdmin) {
        const formUsuario   = document.getElementById('formUsuarioReservar');
        const alertaUsuario = document.getElementById('alertaUsuario');
        const btnConfirmar  = document.getElementById('btnConfirmarReserva');

        formUsuario.addEventListener('submit', function (e) {
            e.preventDefault();
            btnConfirmar.disabled = true;

            fetch(urlReservar, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    turno_id: document.getElementById('inputTurnoId').value
                })
            })
            .then(async res => {
                const data = await res.json();
                alertaUsuario.classList.remove('d-none', 'alert-success', 'alert-danger');
                alertaUsuario.classList.add(res.ok && data.ok ? 'alert-success' : 'alert-danger');
                alertaUsuario.textContent = data.mensaje || 'No se pudo confirmar.';

                document.getElementById('inputTurnoId').value = '';
                document.getElementById('resumenFecha').value = '';
                eventoSeleccionadoEl = null;
                calendar.refetchEvents();
            });
        });
    }
});

//-----------------------------------------INICIO & TASACIONES-----------------------------------------------//

document.addEventListener('DOMContentLoaded', () => {
  registrarPropiedadesIniciales();

  // GATE DE LOGIN PARA TURNERO EN VISITANTES
  document.addEventListener('click', (e) => {
    const triggerTurnero = e.target.closest('[data-requiere-login="1"], .js-turnero-auth');
    if (!triggerTurnero) return;

    e.preventDefault();
    if (typeof abrirModalAuth === 'function') {
      abrirModalAuth('login');
    } else {
      window.location.href = '/turnero';
    }
  });

  // 0. EVENTOS DE AUTH FLOTANTE Y PERFIL
  limpiarCamposAuth();

  const inputPass = document.getElementById('reg_password');
  const reglaMayus = document.getElementById('reglaMayuscula');
  const reglaNum = document.getElementById('reglaNumero');

  const validarPasswordEnVivo = () => {
    if (!inputPass) return false;
    const val = inputPass.value;
    const tieneMayus = /[A-Z]/.test(val);
    const tieneNum = /\d/.test(val);

    if (reglaMayus) {
      reglaMayus.classList.toggle('regla-ok', tieneMayus);
      reglaMayus.classList.toggle('regla-pendiente', !tieneMayus);
    }
    if (reglaNum) {
      reglaNum.classList.toggle('regla-ok', tieneNum);
      reglaNum.classList.toggle('regla-pendiente', !tieneNum);
    }

    return tieneMayus && tieneNum;
  };

  if (inputPass) {
    inputPass.addEventListener('input', validarPasswordEnVivo);
  }

  const enviarFormAuth = async (form, esRegistro = false) => {
    ocultarAlertaAuth();

    if (esRegistro && !validarPasswordEnVivo()) {
      mostrarAlertaAuth('La contraseña debe tener al menos una mayúscula y un número.');
      return;
    }

    const formData = new FormData(form);
    const payload = Object.fromEntries(formData.entries());
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    try {
      const resp = await fetch(form.action, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrf,
        },
        body: JSON.stringify(payload),
      });

      const data = await resp.json();

      if (resp.ok && data.ok) {
        limpiarCamposAuth();
        window.location.href = data.redirect || '/';
        return;
      }

      if (data.errors) {
        const erroresPlanos = Object.values(data.errors).flat();
        mostrarAlertaAuth(erroresPlanos);
      } else {
        mostrarAlertaAuth(data.mensaje || 'No se pudo completar la operación.');
      }
    } catch (err) {
      mostrarAlertaAuth('Error de conexión al intentar procesar la solicitud.');
    }
  };

  const formLogin = document.getElementById('formLoginFlotante');
  if (formLogin) {
    formLogin.addEventListener('submit', (e) => {
      e.preventDefault();
      enviarFormAuth(formLogin, false);
    });
  }

  const formRegistro = document.getElementById('formRegistroFlotante');
  if (formRegistro) {
    formRegistro.addEventListener('submit', (e) => {
      e.preventDefault();
      enviarFormAuth(formRegistro, true);
    });
  }

  const formEditarPerfil = document.getElementById('formEditarPerfil');
  if (formEditarPerfil) {
    formEditarPerfil.addEventListener('submit', async (e) => {
      e.preventDefault();
      ocultarMensajesPerfil();

      const formData = new FormData(formEditarPerfil);
      const payload = Object.fromEntries(formData.entries());
      const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

      try {
        const resp = await fetch(formEditarPerfil.action, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrf,
          },
          body: JSON.stringify(payload),
        });

        const data = await resp.json();

        if (resp.ok && data.ok) {
          const u = data.usuario;
          if (u) {
            const txtNombre = document.getElementById('perfilTextoNombre');
            const txtEmail = document.getElementById('perfilTextoEmail');
            const txtTel = document.getElementById('perfilTextoTelefono');
            const txtDir = document.getElementById('perfilTextoDireccion');
            if (txtNombre) txtNombre.textContent = `${u.name} ${u.apellido}`;
            if (txtEmail) txtEmail.textContent = u.email;
            if (txtTel) txtTel.textContent = u.telefono || 'No especificado';
            if (txtDir) txtDir.textContent = u.direccion || 'No especificada';
          }
          resetearVistasPerfil();
          mostrarExitoPerfil(data.mensaje || 'Datos actualizados correctamente.');
          return;
        }

        if (data.errors) {
          mostrarAlertaPerfil(Object.values(data.errors).flat());
        } else {
          mostrarAlertaPerfil(data.mensaje || 'No se pudieron guardar los cambios.');
        }
      } catch (err) {
        mostrarAlertaPerfil('Error de conexión al intentar actualizar tus datos.');
      }
    });
  }

  const formEliminarCuenta = document.getElementById('formEliminarCuenta');
  if (formEliminarCuenta) {
    formEliminarCuenta.addEventListener('submit', async (e) => {
      e.preventDefault();
      ocultarMensajesPerfil();

      const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

      try {
        const resp = await fetch(formEliminarCuenta.action, {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrf,
          },
        });

        const data = await resp.json();

        if (resp.ok && data.ok) {
          limpiarCamposAuth();
          cerrarModalPerfil();
          window.location.href = data.redirect || '/';
          return;
        }

        mostrarAlertaPerfil(data.mensaje || 'No se pudo eliminar la cuenta.');
      } catch (err) {
        mostrarAlertaPerfil('Error de conexión al intentar eliminar la cuenta.');
      }
    });
  }

  const formLogout = document.getElementById('formLogoutFlotante');
  if (formLogout) {
    formLogout.addEventListener('submit', async (e) => {
      e.preventDefault();
      const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

      try {
        await fetch(formLogout.action, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrf,
          },
        });
      } catch (err) {
        console.warn('Error al cerrar sesión por fetch:', err);
      }

      limpiarCamposAuth();
      cerrarModalPerfil();
      window.location.href = '/';
    });
  }

  document.getElementById('modalAuth')?.addEventListener('click', (e) => {
    if (e.target.id === 'modalAuth') cerrarModalAuth();
  });

  document.getElementById('modalPerfil')?.addEventListener('click', (e) => {
    if (e.target.id === 'modalPerfil') cerrarModalPerfil();
  });

  document.getElementById('modalPropiedad')?.addEventListener('click', (e) => {
    if (e.target.id === 'modalPropiedad') cerrarModalPropiedad();
  });

  // 1. BUSCADOR DE PROPIEDADES EN INICIO
  const formBuscador = document.getElementById('form-buscador');
  const contenedorTarjetas = document.getElementById('contenedor-tarjetas');
  const accionReset = document.getElementById('accion-reset-filtros');
  const btnVerTodas = document.getElementById('btn-ver-todas');
  const seccionAncla = document.getElementById('propiedades');

  inicializarCarruseles(document);
  vincularClickTarjetas(document);

  if (formBuscador && contenedorTarjetas) {
    const hayFiltrosActivos = () => {
      const formData = new FormData(formBuscador);
      for (const [, valor] of formData.entries()) {
        if (valor.trim() !== '') return true;
      }
      return false;
    };

    const actualizarBotonVerTodas = (cantidadResultados) => {
      if (!accionReset) return;
      if (hayFiltrosActivos() && cantidadResultados > 0) {
        accionReset.classList.remove('d-none');
      } else {
        accionReset.classList.add('d-none');
      }
    };

    const limpiarFiltrosYRecargar = () => {
      formBuscador.reset();
      if (accionReset) accionReset.classList.add('d-none');
      ejecutarBusqueda('');
    };

    if (btnVerTodas) {
      btnVerTodas.addEventListener('click', limpiarFiltrosYRecargar);
    }

    const ejecutarBusqueda = async (paramsString = '') => {
      const url = `${formBuscador.action}${paramsString ? '?' + paramsString : ''}`;
      const btnBuscar = formBuscador.querySelector('.btn-search');
      const textoOriginal = btnBuscar ? btnBuscar.textContent : 'Buscar ahora';

      if (btnBuscar) {
        btnBuscar.disabled = true;
        btnBuscar.textContent = 'Buscando...';
      }

      try {
        const response = await fetch(url, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
          },
        });

        if (!response.ok) throw new Error('Error al filtrar propiedades');

        const data = await response.json();

        if (Array.isArray(data)) {
          contenedorTarjetas.innerHTML = renderizarTarjetasDesdeJson(data);
          actualizarBotonVerTodas(data.length);
        } else if (typeof data.html === 'string') {
          contenedorTarjetas.innerHTML = data.html;
          const cantCards = contenedorTarjetas.querySelectorAll('.property-card').length;
          actualizarBotonVerTodas(cantCards);
        }

        inicializarCarruseles(contenedorTarjetas);
        vincularClickTarjetas(contenedorTarjetas);

        const btnLimpiarVacio = document.getElementById('btn-limpiar-filtros');
        if (btnLimpiarVacio) {
          btnLimpiarVacio.addEventListener('click', limpiarFiltrosYRecargar);
        }

        if (seccionAncla) {
          seccionAncla.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      } catch (error) {
        console.error('Error al filtrar propiedades:', error);
      } finally {
        if (btnBuscar) {
          btnBuscar.disabled = false;
          btnBuscar.textContent = textoOriginal;
        }
      }
    };

    formBuscador.addEventListener('submit', (e) => {
      e.preventDefault();

      const formData = new FormData(formBuscador);
      const params = new URLSearchParams();

      for (const [clave, valor] of formData.entries()) {
        if (valor.trim() !== '') {
          params.append(clave, valor);
        }
      }

      ejecutarBusqueda(params.toString());
    });
  }

  // 2. CONEXIÓN DEL FORMULARIO DE TASACIONES CON LONCOBOTI
  const formularioTasacion = document.getElementById('tasacionForm');
  if (formularioTasacion) {
    formularioTasacion.addEventListener('submit', async function (evento) {
      evento.preventDefault();

      const formData = new FormData(formularioTasacion);
      const nombre = (formData.get('nombre') || document.getElementById('nombre')?.value || '').trim();
      const telefono = (formData.get('telefono') || formData.get('contacto') || document.getElementById('telefono')?.value || document.getElementById('contacto')?.value || '').trim();
      const operacion = (formData.get('operacion') || document.getElementById('operacion')?.value || 'venta').trim();
      const tipoPropiedad = (formData.get('tipo_propiedad') || formData.get('tipo') || document.getElementById('tipo')?.value || 'Departamento').trim();
      const zona = (formData.get('zona') || document.getElementById('zona')?.value || 'General').trim();
      const metrosRaw = formData.get('metros_cuadrados')
        ?? formData.get('superficie')
        ?? formData.get('metros')
        ?? document.getElementById('metros')?.value
        ?? document.getElementById('superficie')?.value
        ?? 0;
      const metros = parseFloat(metrosRaw) || 0;

      const successMsg = document.getElementById('successMsg');
      if (successMsg) {
        successMsg.classList.remove('d-none');
      }

      const ventana = document.getElementById('ventanaChatbot');
      if (ventana && ventana.classList.contains('d-none')) {
        ventana.classList.remove('d-none');
      }

      if (botui && !yaSaludo) {
        yaSaludo = true;
        await botui.message.add({
          delay: 300,
          loading: true,
          content: '¡Hola! Soy LoncoBoti, el asistente virtual de LoncoProp.'
        });
      }

      const datos = {
        nombre: nombre,
        telefono: telefono,
        zona: zona,
        tipo_propiedad: tipoPropiedad,
        operacion: operacion,
        metros_cuadrados: metros
      };

      try {
        const respuesta = await fetch('/chatbot/tasacion', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify(datos)
        });

        const resultado = respuesta.ok ? await respuesta.json() : null;

        if (resultado && resultado.ok && botui) {
          await botui.message.add({
            type: 'html',
            delay: 400,
            loading: true,
            content: (nombre ? '¡Hola ' + nombre + '! ' : '') +
                     '<strong>Tasación estimada (' + resultado.zona + ')</strong><br>' +
                     'Tipo: ' + (resultado.tipo_propiedad || tipoPropiedad) + '<br>' +
                     'Operación: ' + resultado.operacion + ' (' + resultado.metros_cuadrados + ' m²)<br>' +
                     'Valor aproximado: <strong>' + resultado.tasacion_formateada + '</strong>'
          });
          formularioTasacion.reset();
          mostrarMenu('¿Te puedo ayudar en algo más?');
          return;
        }
      } catch (err) {
        console.warn('No se pudo consultar /chatbot/tasacion, usando cálculo local:', err);
      }

      const valorM2 = calcularValorM2Referencia(zona, tipoPropiedad, operacion);
      const estimado = Math.round(metros * valorM2);
      const estimadoFormateado = '$' + estimado.toLocaleString('es-AR');

      if (botui) {
        await botui.message.add({
          type: 'html',
          delay: 400,
          loading: true,
          content: (nombre ? '¡Hola ' + nombre + '! ' : '') +
                   '<strong>Tasación estimada (' + zona + ')</strong><br>' +
                   'Tipo: ' + tipoPropiedad + ' — Operación: ' + operacion + ' (' + metros + ' m²)<br>' +
                   'Valor aproximado: <strong>' + estimadoFormateado + '</strong><br>' +
                   '<small>Un asesor de LoncoProp revisará los datos para afinar el informe.</small>'
        });
      }

      formularioTasacion.reset();
      mostrarMenu('¿Te puedo ayudar en algo más?');
    });
  }
});

function vincularClickTarjetas(scope) {
  const tarjetas = scope.querySelectorAll('.property-card');

  tarjetas.forEach((tarjeta) => {
    tarjeta.addEventListener('click', (e) => {
      if (e.target.closest('.carousel-btn') || e.target.closest('.carousel-dot')) {
        return;
      }
      const id = String(tarjeta.dataset.id || '');
      const prop = propiedadesPorId.get(id);
      if (prop) {
        abrirModalPropiedad(prop);
      }
    });
  });
}

function inicializarCarruseles(scope) {
  const carousels = scope.querySelectorAll('.card-carousel');

  carousels.forEach((carousel) => {
    const slides = carousel.querySelectorAll('.carousel-slide');
    const dots = carousel.querySelectorAll('.carousel-dot');
    const prevBtn = carousel.querySelector('.carousel-btn.prev');
    const nextBtn = carousel.querySelector('.carousel-btn.next');

    if (slides.length <= 1) return;

    let currentIndex = 0;

    const showSlide = (index) => {
      currentIndex = (index + slides.length) % slides.length;
      slides.forEach((slide, i) => slide.classList.toggle('active', i === currentIndex));
      dots.forEach((dot, i) => dot.classList.toggle('active', i === currentIndex));
      carousel.dataset.index = String(currentIndex);
    };

    if (prevBtn) {
      prevBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        showSlide(currentIndex - 1);
      });
    }

    if (nextBtn) {
      nextBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        showSlide(currentIndex + 1);
      });
    }

    dots.forEach((dot, i) => {
      dot.addEventListener('click', (e) => {
        e.stopPropagation();
        showSlide(i);
      });
    });
  });
}

function renderizarTarjetasDesdeJson(propiedades) {
  propiedadesPorId.clear();

  if (!Array.isArray(propiedades) || !propiedades.length) {
    return `
      <div class="empty-properties">
        <p>No encontramos propiedades que coincidan con los filtros seleccionados.</p>
        <button type="button" id="btn-limpiar-filtros" class="btn-search mt-3">
          Ver todas las propiedades
        </button>
      </div>
    `;
  }

  return propiedades.map((prop) => {
    propiedadesPorId.set(String(prop.id), prop);
    const imagenes = normalizarImagenesPropiedad(prop);

    const slidesHtml = imagenes.map((src, idx) => {
      return `<img src="${src}" alt="${prop.titulo} - Imagen ${idx + 1}" class="carousel-slide ${idx === 0 ? 'active' : ''}">`;
    }).join('');

    const controlesHtml = imagenes.length > 1 ? `
      <button type="button" class="carousel-btn prev" aria-label="Imagen anterior">❮</button>
      <button type="button" class="carousel-btn next" aria-label="Imagen siguiente">❯</button>
      <div class="carousel-dots">
        ${imagenes.map((_, idx) => `<button type="button" class="carousel-dot ${idx === 0 ? 'active' : ''}" data-slide="${idx}" aria-label="Ir a imagen ${idx + 1}"></button>`).join('')}
      </div>
    ` : '';

    const precioFormateado = Number(prop.precio || 0).toLocaleString('es-AR');
    const expensasHtml = prop.expensas && Number(prop.expensas) > 0
      ? `<span class="card-expensas">+ $${Number(prop.expensas).toLocaleString('es-AR')} expensas</span>`
      : `<span class="card-expensas">Sin expensas</span>`;

    const opTexto = prop.operacion ? prop.operacion.charAt(0).toUpperCase() + prop.operacion.slice(1) : '';
    const ambTexto = `${prop.ambientes} ${Number(prop.ambientes) === 1 ? 'ambiente' : 'ambientes'}`;
    const dormTexto = Number(prop.dormitorios || 0) === 0
      ? 'Monoambiente'
      : `${prop.dormitorios} ${Number(prop.dormitorios) === 1 ? 'dormitorio' : 'dormitorios'}`;

    return `
      <article class="property-card" data-id="${prop.id}">
        <div class="card-img-wrapper card-carousel" data-index="0">
          <span class="badge-operacion">${opTexto}</span>
          <span class="badge-id">ID #${prop.id}</span>
          <div class="carousel-track">${slidesHtml}</div>
          ${controlesHtml}
        </div>
        <div class="card-body">
          <div class="card-price-row">
            <span class="card-price">$${precioFormateado}</span>
            ${expensasHtml}
          </div>
          <h3 class="card-title">${prop.titulo}</h3>
          <p class="card-location">${prop.direccion} — ${prop.zona}</p>
          <div class="card-specs">
            <span>${ambTexto}</span>
            <span>•</span>
            <span>${dormTexto}</span>
            <span>•</span>
            <span>${prop.metros_cuadrados} m²</span>
          </div>
        </div>
      </article>
    `;
  }).join('');
}
//-------------------------------------CHATBOT CON BOTUI------------------------------------//

const botui = document.getElementById('lonco-botui') ? new BotUI('lonco-botui') : null;
let yaSaludo = false;
let idBusquedaActual = 0;

function toggleChatbot() {
  const ventana = document.getElementById('ventanaChatbot');
  ventana.classList.toggle('d-none');

  if (!ventana.classList.contains('d-none') && !yaSaludo && botui) {
    yaSaludo = true;
    iniciarConversacion();
  }
}

async function enviarPost(url, datos) {
  const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  const respuesta = await fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': token
    },
    body: JSON.stringify(datos)
  });

  return await respuesta.json();
}

async function iniciarConversacion() {
  if (!botui) return;
  await botui.message.add({
    delay: 400,
    loading: true,
    content: '¡Hola! Soy LoncoBoti, el asistente virtual de LoncoProp.'
  });

  mostrarMenu('¿En qué te puedo ayudar?');
}

async function mostrarMenu(pregunta) {
  if (!botui) return;
  limpiarExtrasInput();

  await botui.message.add({
    delay: 400,
    loading: true,
    content: pregunta
  });

  const opcion = await botui.action.button({
    action: [
      { text: 'Quiero alquilar', value: 'alquiler_pasos' },
      { text: 'Requisitos alquiler', value: 'alquiler_requisitos' },
      { text: 'Quiero comprar', value: 'compra_pasos' },
      { text: 'Requisitos comprar', value: 'compra_requisitos' },
      { text: 'Horarios y teléfono', value: 'contacto_info' },
      { text: 'Agendar visita', value: 'agendar_visita' },
      { text: 'Otra pregunta', value: 'otra_pregunta' },
      { text: 'Salir del chat', value: 'salir' }
    ]
  });

  if (opcion.value === 'salir') {
    salirDelChat();
  } else if (opcion.value === 'otra_pregunta') {
    flujoOtraPregunta();
  } else if (opcion.value === 'agendar_visita') {
    flujoVisita();
  } else {
    verRespuesta(opcion.value);
  }
}

function salirDelChat() {
  idBusquedaActual++;
  limpiarExtrasInput();
  document.getElementById('ventanaChatbot').classList.add('d-none');
  if (botui) botui.message.removeAll();
  yaSaludo = false;
}

function limpiarExtrasInput() {
  const viejos = document.querySelectorAll('.sugerencias-chat, .contenedor-volver-chat');
  for (let i = 0; i < viejos.length; i++) {
    viejos[i].remove();
  }
}

async function verRespuesta(clave) {
  if (!botui) return;
  const respuesta = await fetch('/chatbot/respuesta/' + clave);
  const datos = await respuesta.json();

  if (datos.ok) {
    const textoOrdenado = datos.contenido.replace(/\n/g, '<br>');
    await botui.message.add({ type: 'html', delay: 400, loading: true, content: textoOrdenado });
  } else {
    await botui.message.add({ delay: 300, content: datos.mensaje });
  }

  mostrarMenu('¿Te puedo ayudar en algo más?');
}

async function flujoOtraPregunta() {
  if (!botui) return;
  let yaRespondio = false;
  limpiarExtrasInput();

  await botui.message.add({
    delay: 300,
    content: 'Escribí tu consulta (o tocá una sugerencia mientras escribís):'
  });

  const promesaEscritura = botui.action.text({
    action: { placeholder: 'Ej: alquiler, comprar, horarios...' }
  });

  const intervalo = setInterval(function () {
    const inputBot = document.querySelector('.botui-actions-text-input');
    const contenedorAcciones = document.querySelector('.botui-actions-container');
    if (!inputBot || !contenedorAcciones) return;

    clearInterval(intervalo);
    limpiarExtrasInput();
    inputBot.focus();

    const cajaSugerencias = document.createElement('div');
    cajaSugerencias.className = 'sugerencias-chat';
    contenedorAcciones.prepend(cajaSugerencias);

    const cajaVolver = document.createElement('div');
    cajaVolver.className = 'contenedor-volver-chat';
    const btnVolver = document.createElement('button');
    btnVolver.type = 'button';
    btnVolver.className = 'btn-volver-chat';
    btnVolver.textContent = '← Volver al menú';

    btnVolver.onclick = async function () {
      yaRespondio = true;
      idBusquedaActual++;
      limpiarExtrasInput();
      botui.action.hide();
      await botui.message.add({ human: true, content: 'Volver al menú' });
      mostrarMenu('¿En qué te puedo ayudar?');
    };

    cajaVolver.appendChild(btnVolver);
    contenedorAcciones.appendChild(cajaVolver);

    inputBot.addEventListener('input', async function () {
      const texto = inputBot.value.trim();
      const estaBusqueda = ++idBusquedaActual;

      if (texto.length < 2) {
        cajaSugerencias.innerHTML = '';
        return;
      }

      const datos = await enviarPost('/chatbot/buscar-palabra', { texto: texto });

      if (estaBusqueda !== idBusquedaActual || yaRespondio) return;

      cajaSugerencias.innerHTML = '';
      if (!datos.ok) return;

      if (datos.tipo === 'sugerencias') {
        const mostradas = [];
        for (let i = 0; i < datos.sugerencias.length; i++) {
          const item = datos.sugerencias[i];
          const textoBoton = item.palabra || item.titulo;

          if (mostradas.includes(textoBoton)) continue;
          mostradas.push(textoBoton);

          const boton = document.createElement('button');
          boton.type = 'button';
          boton.className = 'btn-sugerencia-chat';
          boton.textContent = textoBoton;

          boton.onclick = async function () {
            yaRespondio = true;
            idBusquedaActual++;
            limpiarExtrasInput();
            botui.action.hide();
            await botui.message.add({ human: true, content: textoBoton });
            verRespuesta(item.clave);
          };

          cajaSugerencias.appendChild(boton);
        }
      }

      if (datos.tipo === 'respuesta_unica') {
        const textoBoton = datos.palabra || datos.titulo;
        const boton = document.createElement('button');
        boton.type = 'button';
        boton.className = 'btn-sugerencia-chat';
        boton.textContent = textoBoton;

        boton.onclick = async function () {
          yaRespondio = true;
          idBusquedaActual++;
          limpiarExtrasInput();
          botui.action.hide();
          await botui.message.add({ human: true, content: textoBoton });
          const textoOrdenado = datos.contenido.replace(/\n/g, '<br>');
          await botui.message.add({ type: 'html', delay: 400, loading: true, content: textoOrdenado });
          mostrarMenu('¿Te puedo ayudar en algo más?');
        };

        cajaSugerencias.appendChild(boton);
      }
    });
  }, 50);

  const escritura = await promesaEscritura;
  if (yaRespondio) return;

  yaRespondio = true;
  idBusquedaActual++;
  limpiarExtrasInput();
  procesarTextoEscrito(escritura.value);
}

async function procesarTextoEscrito(texto) {
  if (!botui) return;
  const datos = await enviarPost('/chatbot/buscar-palabra', { texto: texto });

  if (!datos.ok) {
    await botui.message.add({
      type: 'html',
      delay: 400,
      loading: true,
      content: 'No entendí tu consulta o no tengo una respuesta para eso. ' +
               'Podés comunicarte con nosotros al <strong>+54 299 555-0148</strong> ' +
               'o por mail a <strong>contacto@loncoprop.com.ar</strong>.'
    });
    mostrarMenu('¿Te puedo ayudar en algo más?');
    return;
  }

  if (datos.tipo === 'respuesta_unica') {
    const textoOrdenado = datos.contenido.replace(/\n/g, '<br>');
    await botui.message.add({ type: 'html', delay: 400, loading: true, content: textoOrdenado });
    mostrarMenu('¿Te puedo ayudar en algo más?');
    return;
  }

  const botones = [];
  for (let i = 0; i < datos.sugerencias.length; i++) {
    botones.push({
      text: datos.sugerencias[i].palabra || datos.sugerencias[i].titulo,
      value: datos.sugerencias[i].clave
    });
  }

  await botui.message.add({ delay: 300, content: 'Encontré estas opciones relacionadas:' });
  const elegida = await botui.action.button({ action: botones });
  verRespuesta(elegida.value);
}

async function flujoVisita() {
  if (!botui) return;

  await botui.message.add({
    type: 'html',
    delay: 400,
    loading: true,
    content:
      'Podés agendar tu visita directamente desde nuestro <strong>Turnero online</strong> siguiendo estos pasos:<br><br>' +
      '<strong>1.</strong> Iniciá sesión o registrate con tu cuenta en la web.<br>' +
      '<strong>2.</strong> Copiá el código de la propiedad que te interesa (ej: <em>#102</em>).<br>' +
      '<strong>3.</strong> Entrá al turnero, pegá el código y elegí el día y horario disponible.<br><br>' +
      '<a href="/turnero" class="btn-bot-turnero-link">Ir al Turnero de Visitas →</a>'
  });

  mostrarMenu('¿Te puedo ayudar en algo más?');
}

function calcularValorM2Referencia(zona, tipo, operacion) {
  const z = (zona || '').toLowerCase();
  let baseM2 = 1200;

  if (z.includes('centro')) baseM2 = 1450;
  else if (z.includes('alta barda')) baseM2 = 1350;
  else if (z.includes('este')) baseM2 = 1150;

  if ((tipo || '').toLowerCase().includes('casa')) baseM2 *= 1.1;
  if ((operacion || '').toLowerCase().includes('alquil')) baseM2 = Math.round(baseM2 * 0.0045);

  return baseM2;
}

// TURNERO (fullcalendar, turnocontroller y ajax)
document.addEventListener('DOMContentLoaded', function () {
    const appTurnero = document.getElementById('turneroApp');
    // Si no estamos en la vista del turnero, cortamos acá para no ejecutar código innecesario
    if (!appTurnero) return;

    // Leemos los datos que dejamos en los atributos data-* del HTML
    const esAdmin      = appTurnero.dataset.esAdmin === '1';
    const csrfToken    = appTurnero.dataset.csrf;
    const urlHabilitar = appTurnero.dataset.urlHabilitar;
    const urlReservar  = appTurnero.dataset.urlReservar;

    const selectPropiedad      = document.getElementById('selectPropiedad');
    const avisoSeleccion       = document.getElementById('avisoSeleccion');
    const contenedorCalendario = document.getElementById('contenedorCalendario');
    const calendarEl           = document.getElementById('calendar');

    let eventoSeleccionadoEl     = null;
    let turnoAdminSeleccionadoId = null;

    // 1. Se inicializa la librería FullCalendar llamando a new FullCalendar.Calendar()
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth', // Vista mensual por defecto
        locale: 'es',                // Idioma español
        height: 'auto',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listWeek'
        },
        buttonText: { today: 'Hoy', month: 'Mes', list: 'Lista' },

        // La librería llama a esta función cada vez que necesita cargar o refrescar los turnos
        events: function (info, successCallback, failureCallback) {
            const propId = selectPropiedad.value;
            if (!propId) return successCallback([]);

            // Pedimos los turnos en JSON a nuestro TurnoController
            fetch(`/turnero/eventos/${propId}`)
                .then(res => res.json())
                .then(data => successCallback(data))
                .catch(err => failureCallback(err));
        },

        // Asignamos clase CSS según si el turno está libre (negro) o reservado (rojo)
        eventClassNames: function (arg) {
            return arg.event.extendedProps.estado === 'confirmada'
                ? ['fc-event-ocupado']
                : ['fc-event-libre'];
        },

        // Callback de FullCalendar cuando el usuario toca un día vacío en la grilla
        dateClick: function (info) {
            if (!esAdmin) return;
            const inputFecha = document.getElementById('adminFecha');
            if (inputFecha && info.dateStr >= inputFecha.min) {
                inputFecha.value = info.dateStr;
            }
        },

        // Callback de FullCalendar cuando el usuario toca un turno cargado en el calendario
        eventClick: function (info) {
            const props = info.event.extendedProps;

            // --- Si es ADMIN: mostramos detalle del turno (libre o reservado) ---
            if (esAdmin) {
                const box    = document.getElementById('detalleTurnoAdmin');
                const txt    = document.getElementById('textoDetalleAdmin');
                const btnDel = document.getElementById('btnEliminarTurno');

                box.classList.remove('d-none');
                if (props.estado === 'disponible') {
                    turnoAdminSeleccionadoId = props.turno_id;
                    txt.innerHTML = `<strong>${props.fecha_linda}</strong><br>Estado: <span class="text-success fw-semibold">Disponible</span>`;
                    btnDel.classList.remove('d-none');
                } else {
                    turnoAdminSeleccionadoId = null;
                    txt.innerHTML = `<strong>${props.fecha_linda}</strong><br>Reservado por: <strong>${props.cliente}</strong> (${props.telefono})`;
                    btnDel.classList.add('d-none');
                }
                return;
            }

            // --- Si es USUARIO: marcamos en verde el horario elegido y habilitamos el botón ---
            if (eventoSeleccionadoEl) {
                eventoSeleccionadoEl.classList.remove('fc-event-seleccionado');
            }
            info.el.classList.add('fc-event-seleccionado');
            eventoSeleccionadoEl = info.el;

            document.getElementById('inputTurnoId').value = props.turno_id;
            document.getElementById('resumenFecha').value = props.fecha_linda;
            document.getElementById('btnConfirmarReserva').disabled = false;
            document.getElementById('alertaUsuario').classList.add('d-none');
        }
    });

    // ponemos el calendario en pantalla
    calendar.render();

    // 2. Cuando cambia la propiedad seleccionada en el <select>
    selectPropiedad.addEventListener('change', function () {
        const propId = this.value;

        if (!propId) {
            avisoSeleccion.classList.remove('d-none');
            contenedorCalendario.classList.add('d-none');
            if (esAdmin) {
                document.getElementById('btnHabilitar').disabled = true;
                document.getElementById('detalleTurnoAdmin').classList.add('d-none');
            } else {
                document.getElementById('resumenPropiedad').value = '';
                document.getElementById('resumenFecha').value = '';
                document.getElementById('inputTurnoId').value = '';
                document.getElementById('btnConfirmarReserva').disabled = true;
            }
            return;
        }

        avisoSeleccion.classList.add('d-none');
        contenedorCalendario.classList.remove('d-none');

        if (esAdmin) {
            document.getElementById('btnHabilitar').disabled = false;
            document.getElementById('detalleTurnoAdmin').classList.add('d-none');
        } else {
            document.getElementById('resumenPropiedad').value = this.options[this.selectedIndex].text.trim();
            document.getElementById('resumenFecha').value = '';
            document.getElementById('inputTurnoId').value = '';
            document.getElementById('btnConfirmarReserva').disabled = true;
        }

        // Ajustamos el tamaño de FullCalendar y pedimos los eventos de la propiedad elegida
        calendar.updateSize();
        calendar.refetchEvents();
    });

    // 3. LÓGICA DEL ADMIN: Habilitar horarios y eliminar horarios libres
    if (esAdmin) {
        const formAdmin   = document.getElementById('formAdminHabilitar');
        const alertaAdmin = document.getElementById('alertaAdmin');

        formAdmin.addEventListener('submit', function (e) {
            e.preventDefault();

            // Juntamos los chips marcados + el horario puntual opcional
            const horas = Array.from(document.querySelectorAll('.check-hora:checked')).map(c => c.value);
            const extra = document.getElementById('horaExtra').value;
            if (extra && !horas.includes(extra)) horas.push(extra);

            if (horas.length === 0) {
                alertaAdmin.classList.remove('d-none', 'alert-success');
                alertaAdmin.classList.add('alert-danger');
                alertaAdmin.textContent = 'Seleccioná al menos un horario.';
                return;
            }

            // Enviamos los datos al controlador por POST
            fetch(urlHabilitar, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    propiedad_id: selectPropiedad.value,
                    fecha: document.getElementById('adminFecha').value,
                    horas: horas
                })
            })
            .then(async res => {
                const data = await res.json();
                alertaAdmin.classList.remove('d-none', 'alert-success', 'alert-danger');
                alertaAdmin.classList.add(res.ok && data.ok ? 'alert-success' : 'alert-danger');
                alertaAdmin.textContent = data.mensaje || 'Error al habilitar horarios.';

                if (res.ok && data.ok) {
                    document.querySelectorAll('.check-hora').forEach(c => c.checked = false);
                    document.getElementById('horaExtra').value = '';
                    // Refrescamos FullCalendar para que aparezcan los horarios recién creados
                    calendar.refetchEvents();
                }
            });
        });

        // Botón para eliminar un horario libre que todavía nadie reservó
        document.getElementById('btnEliminarTurno').addEventListener('click', function () {
            if (!turnoAdminSeleccionadoId) return;

            fetch(`/turnero/eliminar/${turnoAdminSeleccionadoId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('detalleTurnoAdmin').classList.add('d-none');
                alertaAdmin.classList.remove('d-none', 'alert-danger');
                alertaAdmin.classList.add('alert-success');
                alertaAdmin.textContent = data.mensaje;
                calendar.refetchEvents();
            });
        });
    }

    // 4. LÓGICA DEL USUARIO: Confirmar la reserva del turno seleccionado
    if (!esAdmin) {
        const formUsuario   = document.getElementById('formUsuarioReservar');
        const alertaUsuario = document.getElementById('alertaUsuario');
        const btnConfirmar  = document.getElementById('btnConfirmarReserva');

        formUsuario.addEventListener('submit', function (e) {
            e.preventDefault();
            btnConfirmar.disabled = true;

            // Enviamos únicamente el ID del turno elegido; el servidor toma los datos de Auth::user()
            fetch(urlReservar, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    turno_id: document.getElementById('inputTurnoId').value
                })
            })
            .then(async res => {
                const data = await res.json();
                alertaUsuario.classList.remove('d-none', 'alert-success', 'alert-danger');
                alertaUsuario.classList.add(res.ok && data.ok ? 'alert-success' : 'alert-danger');
                alertaUsuario.textContent = data.mensaje || 'No se pudo confirmar.';

                // Limpiamos la selección y recargamos el calendario (el turno reservado ya no aparece)
                document.getElementById('inputTurnoId').value = '';
                document.getElementById('resumenFecha').value = '';
                eventoSeleccionadoEl = null;
                calendar.refetchEvents();
            });
        });
    }
});
