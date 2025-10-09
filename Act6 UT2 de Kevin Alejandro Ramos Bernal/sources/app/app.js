/* funcion auxiliar para mover letras con desplazamiento circular */
function moverLetra(letra, desplazamiento) {
    const codigoA = 'a'.charCodeAt(0)
    return String.fromCharCode(((letra.charCodeAt(0) - codigoA + desplazamiento + 26) % 26) + codigoA)
}

/* funcion principal que realiza la encriptacion */
function encriptarMensaje(mensaje) {

    /* validacion basica: no vacio, solo letras minusculas */
    if (!mensaje || !/^[a-z]+$/.test(mensaje)) {
        return 'mensaje invalido: usa solo letras minusculas sin espacios'
    }

    let resultado = ''

    /* recorrer cada letra del mensaje */
    for (let letra of mensaje) {

        let original = letra
        let nueva = letra

        /* aplicar excepciones especiales primero */
        if (letra === 'c') nueva = moverLetra(letra, -1)
        else if (letra === 'o') nueva = moverLetra(letra, -1)
        else if (letra === 'd') nueva = moverLetra(letra, -3)
        else if (letra === 'e') nueva = moverLetra(letra, -4)

        /* definir si la letra es vocal o consonante */
        const esVocal = ['a', 'e', 'i', 'o', 'u'].includes(nueva)

        /* aplicar desplazamiento principal */
        if (esVocal) nueva = moverLetra(nueva, -5)
        else nueva = moverLetra(nueva, 9)

        /* regla de revertir: si termina siendo c, o, d o e se revierte */
        if (['c', 'o', 'd', 'e'].includes(nueva)) nueva = original

        /* agregar la letra final al resultado */
        resultado += nueva
    }

    return resultado
}

/* capturar elementos del html */
const boton = document.getElementById('encriptar')
const input = document.getElementById('mensaje')
const resultado = document.getElementById('resultado')

/* evento al hacer clic en el boton */
boton.addEventListener('click', () => {
    const mensaje = input.value.trim()
    const salida = encriptarMensaje(mensaje)
    resultado.textContent = salida
})
