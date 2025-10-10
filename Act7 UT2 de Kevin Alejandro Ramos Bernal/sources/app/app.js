const labelTexto = document.getElementById("texto_numero");
const inputNumero = document.getElementById("NumeroInput");
const botonEnviar = document.getElementById("enviar");
const formulario = document.getElementById("formulario");
let resultado1 = document.getElementById("resultado1");
let resultado2 = document.getElementById("resultado2");


botonEnviar.addEventListener("click", function(event)  {

    if(!formulario.checkValidity()){
        return;
    }
        event.preventDefault();

    const valor = Number(inputNumero.value);

        resultadin1 = factorialIterativo(valor);
        resultado2 = factorialRecursivo(valor);

        resultado1.innerHTML = `Factorial (iterativo) de ${valor} : ${resultadin1}`;
        resultado2.innerHTML = `El resultado iterativo es : ${resultadin2}`;

 
});


function factorialIterativo(numero){

    if(numero < 0 ){
        alert("El numero no puede ser negativo");
    }else{
        
let resultado = 1;

    for(let i = 1; i <= numero; i++){
        resultado = resultado * i;
    }
    return resultado;
    
}
}



function factorialRecursivo(){


}