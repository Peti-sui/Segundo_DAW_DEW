function jugar(numeroUsuario) {
  return new Promise(resolve => {
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

async function gestionJuegoAsync() {
  const numeroUsuario = parseInt(prompt("Introduce un número del 1 al 6"));

  const puntos = await jugar(numeroUsuario);
  alert("Has conseguido " + puntos + " puntos");

  const continuar = await continuarJugando();

  if (continuar) {
    gestionJuegoAsync();
  } else {
    alert("Juego finalizado");
  }
}

document.getElementById("btnJugar").addEventListener("click", gestionJuegoAsync);
