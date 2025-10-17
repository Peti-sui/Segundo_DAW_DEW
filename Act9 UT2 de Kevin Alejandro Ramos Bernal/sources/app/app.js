/* obtener referencias a los elementos del documento html */
let cantidad_numeros = document.getElementById('cantidad_generar');
let boton = document.getElementById('crear_array');
let resul = document.getElementById('resultado');

/* funcion que valida si el numero ingresado es un entero positivo mayor que cero */
function validarNumero(n) {
    return Number.isInteger(n) && n > 0;
}

/* funcion que genera un numero aleatorio unico dentro de un rango especificado */
function generarNumeroUnico(array, min = 1, max = 100) {
    /* se genera un numero aleatorio entre los limites dados */
    let num = Math.floor(Math.random() * (max - min + 1)) + min;

    /* si el numero ya existe en el array, se llama nuevamente a la funcion (recursion) */
    return array.includes(num) ? generarNumeroUnico(array, min, max) : num;
}

/* funcion recursiva que llena el array con numeros unicos hasta alcanzar la cantidad requerida */
function llenarArrayRecursivamente(array, cantidad, min = 1, max = 100) {
    /* caso base: si el array ya tiene la cantidad deseada, se devuelve */
    if (array.length === cantidad) return array;

    /* se genera un nuevo numero unico y se agrega al array */
    let nuevo = generarNumeroUnico(array, min, max);
    array.push(nuevo);

    /* llamada recursiva para continuar llenando el array */
    return llenarArrayRecursivamente(array, cantidad, min, max);
}

/* evento principal que se ejecuta al hacer clic en el boton */
boton.addEventListener("click", function () {
    /* se obtiene y convierte el valor ingresado a entero */
    let cantidad = parseInt(cantidad_numeros.value);

    /* se eliminan clases y textos anteriores del resultado */
    resul.className = ""; 
    resul.textContent = "";

    /* validacion: el numero debe ser un entero positivo */
    if (!validarNumero(cantidad)) {
        resul.classList.add("error");
        resul.textContent = "por favor, ingresa un numero entero positivo mayor que 0.";
        return;
    }

    /* validacion: no se permiten cantidades mayores a 100 por el rango definido */
    if (cantidad > 100) {
        resul.classList.add("error");
        resul.textContent = "la cantidad no puede ser mayor que 100 (rango permitido: 1–100).";
        return;
    }

    /* se genera el array de numeros unicos utilizando recursion */
    let arrayGenerado = llenarArrayRecursivamente([], cantidad);

    /* se aplica estilo de exito y se muestra el resultado en pantalla */
    resul.classList.add("exito");
    resul.innerHTML = "numeros generados:<br>";

    /* se muestran los numeros generados uno por uno en el area de resultados */
    arrayGenerado.forEach(num => {
        resul.innerHTML += num + "<br>";
    });
});
