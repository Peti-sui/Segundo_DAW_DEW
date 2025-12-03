let carrito = JSON.parse(localStorage.getItem("carrito")) || [];

function agregarCarrito(id){
    const producto = productos.find(p => p.id === id);
    const item = carrito.find(i => i.id === id);
    if(item){
        item.cantidad += 1;
    } else {
        carrito.push({
        id: producto.id,
        nombre: producto.nombre,
        precio: producto.precioFinal ? producto.precioFinal() : producto.precio,
        cantidad: 1})
    }
    localStorage.setItem("carrito", JSON.stringify(carrito));
    mostrarCarrito();
}


function mostrarCarrito(){
    const contenedor = document.querySelector(".resumenCarrito");
    
    if(carrito.length === 0){
        contenedor.innerHTML = "<p>El carrito está vacío</p>";
        return;
    }

    let total = 0;
    let html = "<h3>Carrito</h3><ul>";

    carrito.forEach(
        item => {
        html += `<li>${item.nombre} x ${item.cantidad} = €${item.precio * item.cantidad}</li>`;
        total += item.precio * item.cantidad;
    });
    html += `</ul><p>Total: €${total}</p>`;
    contenedor.innerHTML = html;
}

mostrarCarrito();