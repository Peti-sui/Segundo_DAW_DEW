/* AGREGA UN PRODUCTO A LA LISTA DE DESEOS UTILIZANDO COOKIES PARA SU PERSISTENCIA */
window.agregarDeseos = function(id){
    /* BUSCA EL PRODUCTO POR ID EN LA COLECCION DE PRODUCTOS */
    const producto = productos.find(p => p.id === id);

    /* SI NO EXISTE EL PRODUCTO, SE DETIENE EL PROCESO */
    if(!producto){
        return;
    }

    /* NOMBRE DE LA COOKIE DONDE SE ALMACENA LA LISTA DE DESEOS */
    const cookieNombre = "listaDeseos";

    /* OBTIENE LA COOKIE SI EXISTE */
    const raw = document.cookie.split("; ").find(r => r.startsWith(cookieNombre + "="));
    let lista = [];

    /* INTENTA PARSEAR EL CONTENIDO DE LA COOKIE, SI FALLA SE USA UNA LISTA VACIA */
    if(raw){
        try{
            lista = JSON.parse(decodeURIComponent(raw.split("=")[1]));
        } catch(e){
            lista = [];
        }
    }

    /* EVITA DUPLICADOS EN LA LISTA ANTES DE AGREGAR EL PRODUCTO */
    if(!lista.find(i => i.id === producto.id)){
        lista.push({
            id: producto.id,
            nombre: producto.nombre,
            precio: producto.precioFinal ? producto.precioFinal() : producto.precio
        });
    }

    /* ACTUALIZA LA COOKIE CON LA LISTA DESEOS SERIALIZADA */
    const valor = encodeURIComponent(JSON.stringify(lista));
    document.cookie = cookieNombre + "=" + valor + "; path=/; max-age=" + (60*60*24*30);

    /* ACTUALIZA EL RESUMEN VISUAL DE LA LISTA DE DESEOS */
    mostrarResumenDeseos();
};

/* OBTIENE LA LISTA DE DESEOS DESDE LA COOKIE Y LA PARSEA A OBJETO */
function getListaDeseosFromCookie(){
    const cookieNombre = "listaDeseos";
    const raw = document.cookie.split("; ").find(r => r.startsWith(cookieNombre + "="));

    /* SI NO EXISTE LA COOKIE SE RETORNA UNA LISTA VACIA */
    if(!raw) return [];

    try{
        return JSON.parse(decodeURIComponent(raw.split("=")[1]));
    } catch(e){
        return [];
    }
}

/* GENERA O ACTUALIZA EL RESUMEN DE LA LISTA DE DESEOS EN EL DOM */
function mostrarResumenDeseos(){
    let cont = document.querySelector(".resumenDeseos");

    /* SI NO EXISTE EL CONTENEDOR, SE CREA Y SE INSERTA EN EL DOM */
    if(!cont){
        cont = document.createElement("div");
        cont.className = "resumenDeseos";
        const target = document.querySelector(".barraArribaDerecha") || document.body;
        target.parentNode.insertBefore(cont, target.nextSibling);
    }

    /* OBTIENE LA LISTA ACTUAL DE LA COOKIE */
    const lista = getListaDeseosFromCookie();

    /* SI LA LISTA ESTA VACIA, MUESTRA UN MENSAJE */
    if(lista.length === 0){
        cont.innerHTML = "<p>Lista de deseos vacia</p>";
        return;
    }

    /* GENERA LISTADO Y CALCULA EL TOTAL */
    let total = 0;
    let html = "<h4>Lista de Deseos</h4><ul>";
    lista.forEach(item => {
        total += Number(item.precio);
        html += `<li>${item.nombre} - €${item.precio}</li>`;
    });

    /* AGREGA INFORMACION DEL TOTAL Y FORMULARIO PARA VACIAR LA LISTA */
    html += `</ul><p>Total: €${total.toFixed(2)}</p>`;
    html += `<form method="post" action="procesarDeseos.php"><button type="submit" name="accion" value="vaciar">Vaciar lista</button></form>`;

    /* MUESTRA EL CONTENIDO GENERADO */
    cont.innerHTML = html;
}

/* INICIALIZA EL RESUMEN CUANDO EL DOCUMENTO HA CARGADO */
document.addEventListener("DOMContentLoaded", function(){
    mostrarResumenDeseos();
});
