document.getElementById("btnAñadir").addEventListener("click", function() {

   const texto = document.getElementById('textoNuevo').value;

   if (texto === "") return;

   const p = document.createElement("p");
   p.textContent = texto;

   const li = document.createElement("li");
    li.appendChild(p);

    document.getElementById("lista").appendChild(li);

    document.getElementById("textoNuevo").value = "";

    actualizarContador();

});

function actualizarContador() {
  const total = document.getElementsByTagName("p").length;

  document.getElementById("contadorP").textContent = 
  "Numero de elementos <p>: " + total;
}

document.getElementById("btnTamaño").addEventListener("click", function () {
  const ancho = document.getElementById("ancho").value;
  const alto = document.getElementById("alto").value;

  const div = document.getElementById("datos");

  if (ancho > 0 && alto > 0) {
  div.style.width = ancho + "px";
  div.style.height = alto + "px";
}

});

document.getElementById("btnColor").addEventListener("click", function () {
  const div = document.getElementById("datos");

  div.classList.toggle("color2");
});

document.getElementById("btnConsultar").addEventListener("click", function () {
  const etiqueta = document.getElementById("etiqueta").value;

  if (etiqueta === "") return;

  const total = document.getElementsByTagName(etiqueta).length;

  document.getElementById("resultadoConsulta").textContent =
    "Número de elementos <" + etiqueta + ">: " + total;
});


