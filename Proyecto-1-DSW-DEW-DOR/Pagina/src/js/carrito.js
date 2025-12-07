/* Inicializa el carrito desde localStorage o como arreglo vacio */
let carrito = JSON.parse(localStorage.getItem("carrito")) || [];

/* Agrega un producto al carrito segun su identificador */
function agregarCarrito(id){
    /* Busca el producto por identificador en el arreglo productos */
    const producto = productos.find(p => p.id === id);
    /* Busca el item en el carrito para saber si ya existe */
    const item = carrito.find(i => i.id === id);
    /* Si el producto ya existe en el carrito, incrementa su cantidad */
    if(item){
        item.cantidad += 1;
    } else {
        /* Si es un producto nuevo, lo agrega con cantidad inicial uno */
        carrito.push({
        id: producto.id,
        nombre: producto.nombre,
        precio: producto.precioFinal ? producto.precioFinal() : producto.precio,
        cantidad: 1});
    }
    /* Actualiza el carrito en localStorage */
    localStorage.setItem("carrito", JSON.stringify(carrito));
    /* Actualiza la vista del carrito en el DOM */
    mostrarCarrito();
}

/* Renderiza el contenido del carrito y botones de manipulacion */
function mostrarCarrito(){
    /* Selecciona el contenedor asignado al resumen del carrito */
    const contenedor = document.querySelector(".resumenCarrito");
    if(carrito.length === 0){
        contenedor.innerHTML = "<p>El carrito esta vacio</p>";
        return;
    }
    let total = 0;
    let cantidadTotal = 0;
    let html = "<h3>Carrito</h3><ul>";
    carrito.forEach((item, idx) => {
        html += `<li>
            ${item.nombre} x 
            <input type="number" min="1" value="${item.cantidad}" style="width:40px;" onchange="cambiarCantidad(${item.id}, this.value)">
             = €${(item.precio * item.cantidad).toFixed(2)}
            <button onclick="eliminarDelCarrito(${item.id})">Eliminar</button>
        </li>`;
        total += item.precio * item.cantidad;
        cantidadTotal += item.cantidad;
    });
    html += `</ul>
        <p>Cantidad total: <b>${cantidadTotal}</b></p>
        <p>Total: €${total.toFixed(2)}</p>
        <form method="POST" action="checkout.php">
            <input type="hidden" name="carrito" value='${JSON.stringify(carrito).replace(/'/g, "")}'>
            <button type="submit">Finalizar compra</button>
        </form>
    `;
    contenedor.innerHTML = html;
}

/* Cambia la cantidad de un producto en el carrito */
window.cambiarCantidad = function(id, nuevaCantidad){
    nuevaCantidad = Math.max(1, parseInt(nuevaCantidad));
    const item = carrito.find(i => i.id === id);
    if(item){
        item.cantidad = nuevaCantidad;
        localStorage.setItem("carrito", JSON.stringify(carrito));
        mostrarCarrito();
    }
};

/* Elimina un producto del carrito segun su identificador */
window.eliminarDelCarrito = function(id){
    carrito = carrito.filter(i => i.id !== id);
    localStorage.setItem("carrito", JSON.stringify(carrito));
    mostrarCarrito();
};

/* Muestra el carrito al cargar la pagina */
mostrarCarrito();
