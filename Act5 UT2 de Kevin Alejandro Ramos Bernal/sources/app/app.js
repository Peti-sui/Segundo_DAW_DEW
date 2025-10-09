
/* declaracion del DOM */
const numero = document.getElementById("numeroInput").value;
let decenas_res = document.getElementById("resultado-decenas");
let unidades_res = document.getElementById("resultado-unidades");

/* inicializaciones para el tratado de las mismas*/
let decenas = 0;

let unidades = 0;


numeroInput.addEventListener('keydown', function(event) {
    if (event.key === "Enter") {
        event.preventDefault();
        mostrar_resultados();
    }
});

/* metodo o funcion para mostrar los resultados */
function mostrar_resultados() {
    const numero = parseInt(numeroInput.value);

    /* valida el rango de numeros para asegurarse por segunda vez */
    if (numero < 10 || numero > 99 || isNaN(numero)) {
        decenas_res.innerHTML = "Por favor introduce un numero entre 10 y 99";
        unidades_res.innerHTML = "";
        /* si no se cumple se retorna y sale para no hacer el calculo */
        return;
    }
/* se realiza el calculo*/
    const decenas = parseInt(numero / 10);
    const unidades = numero % 10;
/* se muestran los resultados mediante innerHTML */
    decenas_res.innerHTML = `Decenas: ${decenas}`;
    unidades_res.innerHTML = `Unidades: ${unidades}`;
}










