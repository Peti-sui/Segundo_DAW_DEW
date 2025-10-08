let comensales = document.getElementById("comensales");

/*para una tortilla de papas por persona se necesita lo siguiente*/
const papasPorPersona = 0.2; // kilos
const huevosPorPersona = 1; // unidades
const cebollaPorPersona = 0.06; // kilos


function mostrarMensaje() {

if (comensales.value > 0) {
    let papasTotales = (comensales.value * papasPorPersona).toFixed(2);
    let huevosTotales = comensales.value * huevosPorPersona;
    let cebollaTotales = (comensales.value * cebollaPorPersona).toFixed(2);

    document.getElementById("mensaje").innerHTML = `Para ${comensales.value} comensales, se necesitan: <br>
    - ${papasTotales} kilos de papas <br>
    - ${huevosTotales} huevos <br>
    - ${cebollaTotales} kilos de cebolla.`;
} else {
    document.getElementById("mensaje").innerHTML = "Por favor, ingrese un número válido de comensales.";
}

}

