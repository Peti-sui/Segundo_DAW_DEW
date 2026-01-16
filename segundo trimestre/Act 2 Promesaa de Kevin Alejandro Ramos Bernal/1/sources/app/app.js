function jugar(numeroUsuario) {
  return new Promise((resolve, reject) => {
    const numeroAleatorio = Math.floor(Math.random() * 6) + 1;
    const diferencia = Math.abs(numeroUsuario - numeroAleatorio);

    if (numeroUsuario === numeroAleatorio) {
      resolve(2);
    } else if (diferencia === 1) {
      resolve(1);
    } else {
      resolve(0);
    }
  });
}

function continuarJugando() {
  return new Promise(resolve => {
    const respuesta = confirm("¿Quieres seguir jugando?");
    resolve(respuesta);
  });
}

function gestionJuego() {
  const numeroUsuario = parseInt(prompt("Introduce un número del 1 al 6"));

  jugar(numeroUsuario)
    .then(puntos => {
      alert("Has conseguido " + puntos + " puntos");
      return continuarJugando();
    })
    .then(continuar => {
      if (continuar) {
        gestionJuego();
      } else {
        alert("Juego finalizado");
      }
    });
}

document.getElementById("btnJugar").addEventListener("click", gestionJuego);
