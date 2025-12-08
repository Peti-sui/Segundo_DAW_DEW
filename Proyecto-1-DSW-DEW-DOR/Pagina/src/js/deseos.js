/* Agrega un producto a la lista de deseos utilizando cookies para su persistencia */
window.agregarDeseos = function(id){
    /* Busca el producto por identificador en el arreglo de productos */
    const producto = productos.find(p => p.id === id);
    /* Si el producto no existe, detiene el proceso */
    if(!producto){
        return;
    }
    /* Define el nombre de la cookie para la lista de deseos */
    const cookieNombre = "listaDeseos";
    /* Obtiene la cookie si existe y la procesa */
    const raw = document.cookie.split("; ").find(r => r.startsWith(cookieNombre + "="));
    let lista = [];
    /* Intenta parsear el contenido de la cookie; si falla usa arreglo vacio */
    if(raw){
        try{
            lista = JSON.parse(decodeURIComponent(raw.split("=")[1]));
        } catch(e){
            lista = [];
        }
    }
    /* Evita duplicados en la lista antes de agregar el producto */
    if(!lista.find(i => i.id === producto.id)){
        lista.push({
            id: producto.id,
            nombre: producto.nombre,
            precio: producto.precioFinal ? producto.precioFinal() : producto.precio
        });
    }
    /* Actualiza la cookie con la lista serializada */
    const valor = encodeURIComponent(JSON.stringify(lista));
    document.cookie = cookieNombre + "=" + valor + "; path=/; max-age=" + (60*60*24*30);
    /* Actualiza el resumen visual de la lista de deseos */
    mostrarResumenDeseos();
};

/* Obtiene la lista de deseos desde la cookie y la retorna como arreglo */
function getListaDeseosFromCookie(){
    const cookieNombre = "listaDeseos";
    const raw = document.cookie.split("; ").find(r => r.startsWith(cookieNombre + "="));
    /* Si no existe la cookie retorna arreglo vacio */
    if(!raw) return [];
    try{
        return JSON.parse(decodeURIComponent(raw.split("=")[1]));
    } catch(e){
        return [];
    }
}

/* NUEVO → función para eliminar un ítem específico */
window.eliminarDeseo = function(id){
    const cookieNombre = "listaDeseos";
    const lista = getListaDeseosFromCookie();
    /* Filtra la lista quitando el producto con ese ID */
    const nuevaLista = lista.filter(item => item.id !== id);
    /* Actualiza cookie */
    document.cookie = cookieNombre + "=" +
        encodeURIComponent(JSON.stringify(nuevaLista)) +
        "; path=/; max-age=" + (60*60*24*30);
    /* Actualiza la vista */
    mostrarResumenDeseos();
};

/* Genera o actualiza el resumen de deseos en el DOM */
function mostrarResumenDeseos(){
    let cont = document.querySelector(".resumenDeseos");
    /* Si no existe el contenedor, lo crea y lo inserta en el DOM */
    if(!cont){
        cont = document.createElement("div");
        cont.className = "resumenDeseos";
        const target = document.querySelector(".barraArribaDerecha") || document.body;
        target.parentNode.insertBefore(cont, target.nextSibling);
    }
    /* Obtiene la lista actual desde la cookie */
    const lista = getListaDeseosFromCookie();
    /* Si la lista esta vacia, muestra mensaje */
    if(lista.length === 0){
        cont.innerHTML = "<p>Lista de deseos vacia</p>";
        return;
    }
    /* Genera listado y calcula el total */
    let total = 0;
    let html = "<h4>Lista de Deseos</h4><ul>";
    lista.forEach(item => {
        total += Number(item.precio);

        /* NUEVO → botón de eliminar individual */
        html += `
            <li>
               <snap class="produc"> ${item.nombre} - €${item.precio}</snap>
                <button onclick="eliminarDeseo(${item.id})" class="btn-mini" style="margin-left:8px;">Eliminar</button>
            </li>`;
    });
    /* Agrega informacion del total y formulario para vaciar la lista */
    html += `</ul><p>Total: €${total.toFixed(2)}</p>`;
    html += `<form method="post" action="procesarDeseos.php"><button class="vaciar-lista" type="submit" name="accion" value="vaciar">Vaciar lista</button></form>`;
    /* Muestra el contenido generado */
    cont.innerHTML = html;
}

/* Inicializa el resumen de deseos al cargar el documento */
document.addEventListener("DOMContentLoaded", function(){
    mostrarResumenDeseos();
});
