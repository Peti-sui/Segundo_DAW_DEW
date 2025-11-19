/* Objeto Date */
function ejercicioFecha() {
  let ahora = new Date();
  let finAno = new Date(ahora.getFullYear(), 11, 31, 23, 59, 59, 999);
  let diff = finAno - ahora;

  let dias = Math.floor(diff / (1000 * 60 * 60 * 24));
  let horas = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  let minutos = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
  let segundos = Math.floor((diff % (1000 * 60)) / 1000);

  document.getElementById("resultadoFecha").textContent =
    "Faltan " +
    dias +
    " dias, " +
    horas +
    " horas, " +
    minutos +
    " minutos y " +
    segundos +
    " segundos.";
}

/* Objeto Math */
function calcularArea() {
  let radio = parseFloat(document.getElementById("radioInput").value);
  if (isNaN(radio) || radio <= 0) {
    document.getElementById("resultadoArea").textContent =
      "Introduce un radio valido.";
    return;
  }
  let area = Math.PI * radio * radio;
  document.getElementById("resultadoArea").textContent =
    "El area del circulo es: " + area.toFixed(2);
}

/* Objeto Number */
function mostrarPi() {
  document.getElementById("resultadoPi").textContent =
    "PI con 4 decimales: " + Math.PI.toFixed(4);
}

/* Objeto String */
function procesarCadena() {
  let cadena = document.getElementById("cadenaInput").value;
  if (cadena.trim() === "") {
    document.getElementById("resultadoCadena").textContent =
      "Introduce texto valido.";
    return;
  }

  let out = "Caracteres: " + cadena.length + "<br>";
  out += "<i>" + cadena + "</i><br>";
  out += "<s>" + cadena + "</s>";

  document.getElementById("resultadoCadena").innerHTML = out;
}

/* Objeto Array */
let numeros = [];

function mostrarArray() {
  document.getElementById("arrayMostrar").textContent =
    "[ " + numeros.join(", ") + " ]";
}

function insertarFinal() {
  numeros.push(Math.floor(Math.random() * 1000) + 1);
  mostrarArray();
}

function insertarPrincipio() {
  numeros.unshift(Math.floor(Math.random() * 1000) + 1);
  mostrarArray();
}

function borrarPrimero() {
  numeros.shift();
  mostrarArray();
}

function borrarUltimo() {
  numeros.pop();
  mostrarArray();
}

function ordenarAsc() {
  numeros.sort((a, b) => a - b);
  mostrarArray();
}

function ordenarDesc() {
  numeros.sort((a, b) => b - a);
  mostrarArray();
}
