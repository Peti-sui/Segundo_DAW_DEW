/* OBTIENE EL CARRITO DESDE LOCALSTORAGE O INICIALIZA UN ARREGLO VACIO */
let carrito = JSON.parse(localStorage.getItem("carrito")) || [];

/* AGREGA UN PRODUCTO AL CARRITO SEGUN SU ID */
function agregarCarrito(id){
    /* BUSCA EL PRODUCTO CORRESPONDIENTE EN LA LISTA DE PRODUCTOS */
    const producto = productos.find(p => p.id === id);

    /* VERIFICA SI EL PRODUCTO YA EXISTE EN EL CARRITO */
    const item = carrito.find(i => i.id === id);

    /* SI EL PRODUCTO YA ESTA EN EL CARRITO, AUMENTA SU CANTIDAD */
    if(item){
        item.cantidad += 1;
    } else {
        /* SI NO EXISTE, LO AGREGA CON CANTIDAD INICIAL IGUAL A 1 */
        carrito.push({
        id: producto.id,
        nombre: producto.nombre,
        precio: producto.precioFinal ? producto.precioFinal() : producto.precio,
        cantidad: 1})
    }

    /* ACTUALIZA EL CARRITO EN LOCALSTORAGE */
    localStorage.setItem("carrito", JSON.stringify(carrito));

    /* ACTUALIZA LA VISTA DEL CARRITO */
    mostrarCarrito();
}

/* RENDERIZA EL CONTENIDO DEL CARRITO EN EL DOM */
function mostrarCarrito(){
    /* SELECCIONA EL CONTENEDOR DESTINADO AL RESUMEN DEL CARRITO */
    const contenedor = document.querySelector(".resumenCarrito");
    
    /* SI EL CARRITO ESTA VACIO, MUESTRA UN MENSAJE Y FINALIZA */
    if(carrito.length === 0){
        contenedor.innerHTML = "<p>El carrito esta vacio</p>";
        return;
    }

    /* INICIALIZA VARIABLES PARA EL TOTAL Y LA ESTRUCTURA HTML */
    let total = 0;
    let html = "<h3>Carrito</h3><ul>";

    /* RECORRE LOS ITEMS DEL CARRITO Y CONSTRUYE LA LISTA */
    carrito.forEach(
        item => {
        html += `<li>${item.nombre} x ${item.cantidad} = €${item.precio * item.cantidad}</li>`;
        total += item.precio * item.cantidad;
    });

    /* AGREGA EL TOTAL AL HTML Y ACTUALIZA EL CONTENIDO DEL CONTENEDOR */
    html += `</ul><p>Total: €${total}</p>`;
    contenedor.innerHTML = html;
}

/* MUESTRA EL CARRITO AL CARGAR LA PAGINA */
mostrarCarrito();
