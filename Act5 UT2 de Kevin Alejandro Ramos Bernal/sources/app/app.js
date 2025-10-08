const numero = document.getElementById("numeroInput").value;
let decenas_res = document.getElementById("resultado-decenas");
let unidades_res = document.getElementById("resultado-unidades");


let decenas = numero / 10;

let unidades = numero % 10;

decenas_res.innerHTML = `Decenas:  ${decenas}`;
unidades_res_res.innerHTML = `Unidades:  ${unidades}`;







