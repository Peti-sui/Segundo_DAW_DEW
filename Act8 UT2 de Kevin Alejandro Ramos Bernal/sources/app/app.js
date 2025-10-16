function reemplazooouuu(){
    let texto = (document.getElementById("texto_entrada").value);
    let letraAntiguilla = (document.getElementById("texto_entrada2").value);
    let letraNuevilla = (document.getElementById("texto_entrada3").value);


    const textoConverso = texto.replaceAll(letraAntiguilla, letraNuevilla);

    document.getElementById("resultado").innerHTML = `
    <p>\n ${textoConverso}</p>
    `
}

