// Obtener referencias a los elementos del DOM
const formCrearCuenta = document.getElementById("formCrearCuenta");
const inputNombre = document.getElementById("nombreTitular");
const inputApellidos = document.getElementById("apellidosTitular");
const inputSaldo = document.getElementById("saldoCuenta");
const btnCrearCuenta = document.getElementById("btnCrearCuenta");


formCrearCuenta.addEventListener("submit", function(event) {
  event.preventDefault();


  const nombre = inputNombre.value;
  const apellidos = inputApellidos.value;
  const saldo = parseFloat(inputSaldo.value);

  class CuentaBancaria {
    constructor(nombre, apellidos, saldo) {
      this.nombre = nombre;
      this.apellidos = apellidos;
      this.saldo = saldo;
    }

    mostrarDatos() {
     return `Titular: ${this.nombre} ${this.apellidos} - Saldo: ${this.saldo} €`;
    }
  }

    const cuenta = new CuentaBancaria(nombre, apellidos, saldo);
    const resultadoDiv = document.getElementById("resultado");
    resultadoDiv.textContent = cuenta.mostrarDatos();


});
