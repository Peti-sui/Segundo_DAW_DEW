document.addEventListener("DOMContentLoaded", function() {
  const inputNumeros = document.getElementById("numeros");
  const boton = document.getElementById("btnCalcular");
  const resultado = document.getElementById("resultado");

  boton.addEventListener("click", multiplicarArray);

  function multiplicarArray() {
    const texto = inputNumeros.value;

    if (texto.trim() === "") {
      resultado.textContent = "Por favor, escribe algunos numeros separados por comas";
      return;
    }

    const arrayNumeros = texto.split(",").map(Number);

    if (arrayNumeros.some(isNaN)) {
      resultado.textContent = "Asegurate de que todos los valores sean numeros validos";
      return;
    }

    const numeroMenor = Math.min(...arrayNumeros);
    const numeroMayor = Math.max(...arrayNumeros);
    const multiplicacion = numeroMenor * numeroMayor;

    resultado.textContent = `El numero menor es ${numeroMenor}, el mayor
    es ${numeroMayor}, y su multiplicacion es ${multiplicacion}.`;
  }
});
