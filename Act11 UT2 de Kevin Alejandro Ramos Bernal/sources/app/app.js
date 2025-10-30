/* ================================
   FORMULARIO DE REGISTRO CON VALIDACION EN TIEMPO REAL
   SIN USO DE OBJETOS
   ================================ */

/* Referencias a los elementos del formulario */
const form = document.getElementById('formRegistro');
const nombre = document.getElementById('nombre');
const email = document.getElementById('email');
const password = document.getElementById('password');
const telefono = document.getElementById('telefono');
const fechaNacimiento = document.getElementById('fechaNacimiento');
const btnEnviar = document.getElementById('btnEnviar');
const resultado = document.getElementById('resultado');

/* Referencias a los mensajes de error */
const errorNombre = document.getElementById('errorNombre');
const errorEmail = document.getElementById('errorEmail');
const errorPassword = document.getElementById('errorPassword');
const errorTelefono = document.getElementById('errorTelefono');
const errorFecha = document.getElementById('errorFecha');

/* Expresiones regulares de validacion */
const regNombre = /^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]{3,}$/;
const regEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const regPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;
const regTelefono = /^\d{9}$/;

/* Funcion para verificar si el usuario es mayor de edad */
function esMayorDeEdad(fechaStr) {
  const fecha = new Date(fechaStr);
  const hoy = new Date();
  const edad = hoy.getFullYear() - fecha.getFullYear();
  const mes = hoy.getMonth() - fecha.getMonth();
  return edad > 18 || (edad === 18 && mes >= 0);
}

/* Funciones de validacion */

/* Validar campo Nombre */
function validarNombre() {
  const valor = nombre.value.trim();
  if (regNombre.test(valor)) {
    nombre.classList.add('valido');
    nombre.classList.remove('invalido');
    errorNombre.textContent = '';
    return true;
  } else {
    nombre.classList.add('invalido');
    nombre.classList.remove('valido');
    errorNombre.textContent = 'Solo letras y espacios (minimo 3)';
    return false;
  }
}

/* Validar campo Email */
function validarEmail() {
  const valor = email.value.trim();
  if (regEmail.test(valor)) {
    email.classList.add('valido');
    email.classList.remove('invalido');
    errorEmail.textContent = '';
    return true;
  } else {
    email.classList.add('invalido');
    email.classList.remove('valido');
    errorEmail.textContent = 'Formato de correo invalido';
    return false;
  }
}

/* Validar campo Password */
function validarPassword() {
  const valor = password.value.trim();
  if (regPassword.test(valor)) {
    password.classList.add('valido');
    password.classList.remove('invalido');
    errorPassword.textContent = '';
    return true;
  } else {
    password.classList.add('invalido');
    password.classList.remove('valido');
    errorPassword.textContent = 'Debe tener 8 caracteres, mayuscula, minuscula y numero';
    return false;
  }
}

/* Validar campo Telefono */
function validarTelefono() {
  const valor = telefono.value.trim();
  if (regTelefono.test(valor)) {
    telefono.classList.add('valido');
    telefono.classList.remove('invalido');
    errorTelefono.textContent = '';
    return true;
  } else {
    telefono.classList.add('invalido');
    telefono.classList.remove('valido');
    errorTelefono.textContent = 'Debe tener 9 digitos numericos';
    return false;
  }
}

/* Validar campo Fecha de nacimiento */
function validarFecha() {
  const valor = fechaNacimiento.value.trim();
  if (esMayorDeEdad(valor)) {
    fechaNacimiento.classList.add('valido');
    fechaNacimiento.classList.remove('invalido');
    errorFecha.textContent = '';
    return true;
  } else {
    fechaNacimiento.classList.add('invalido');
    fechaNacimiento.classList.remove('valido');
    errorFecha.textContent = 'Debes tener al menos 18 años';
    return false;
  }
}

/* Validar el formulario completo */
function validarFormulario() {
  const valido =
    validarNombre() &&
    validarEmail() &&
    validarPassword() &&
    validarTelefono() &&
    validarFecha();
  btnEnviar.disabled = !valido;
}

/* ============================
   EVENTOS EN TIEMPO REAL
   ============================ */

/* Se validan los campos a medida que el usuario escribe (keyup o input) */
nombre.addEventListener('keyup', validarFormulario);
email.addEventListener('keyup', validarFormulario);
password.addEventListener('keyup', validarFormulario);
telefono.addEventListener('keyup', validarFormulario);
fechaNacimiento.addEventListener('input', validarFormulario);

/* ============================
   EVENTO DE ENVIO DE FORMULARIO
   ============================ */

form.addEventListener('submit', function (e) {
  e.preventDefault();

  if (btnEnviar.disabled) return;

  /* Se obtienen los valores ingresados */
  const nombreVal = nombre.value.trim();
  const emailVal = email.value.trim();
  const telefonoVal = telefono.value.trim();
  const fechaVal = fechaNacimiento.value.trim();

  /* Se guardan los datos en localStorage (sin objetos) */
  localStorage.setItem('ultimoNombre', nombreVal);
  localStorage.setItem('ultimoEmail', emailVal);
  localStorage.setItem('ultimoTelefono', telefonoVal);
  localStorage.setItem('ultimaFecha', fechaVal);

  /* Se muestra el resumen en pantalla */
  resultado.innerHTML = `
    ✅ <strong>Registro exitoso</strong><br>
    <p>Nombre: ${nombreVal}</p>
    <p>Correo: ${emailVal}</p>
    <p>Telefono: ${telefonoVal}</p>
    <p>Fecha de nacimiento: ${fechaVal}</p>
  `;

  /* Se limpia el formulario */
  form.reset();
  btnEnviar.disabled = true;

  nombre.classList.remove('valido');
  email.classList.remove('valido');
  password.classList.remove('valido');
  telefono.classList.remove('valido');
  fechaNacimiento.classList.remove('valido');
});

/* Cargar ultimo usuario guardado */

window.addEventListener('load', function () {
  const nombreGuardado = localStorage.getItem('ultimoNombre');
  const emailGuardado = localStorage.getItem('ultimoEmail');

  if (nombreGuardado && emailGuardado) {
    resultado.innerHTML = `
      🧾 <strong>Ultimo usuario registrado:</strong><br>
      ${nombreGuardado} (${emailGuardado})
    `;
  }
});
