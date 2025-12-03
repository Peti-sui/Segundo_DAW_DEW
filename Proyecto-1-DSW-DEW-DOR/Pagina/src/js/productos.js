class producto {
    constructor(id, nombre, precio, img){
        this.id = id;
        this.nombre = nombre;
        this.precio = precio;
        this.img = img;
    }
}

class productoConDescuento extends producto {
    constructor(id, nombre, precio, descuento, img){
        super(id, nombre, precio, img);
        this.descuento = descuento;
    }

    precioFinal(){
        return this.precio * (1 - this.descuento / 100);
    }

}

const productos = [
    new producto(1, "Exclusiva taza de la llamada Zona Gemelos", 18, "./src/IMG/llamada-gemelos.jpeg"),
    new producto(2, "Entretenimiento de evacuacion primal", 12, "./src/IMG/golf-caca.avif"),
    new producto(3, "Delantal de nuestro mini ser favorito", 15, "./src/IMG/delantal-bula.avif"),
    new producto(4, "Gato lamiendo sus atributos", 4, "./src/IMG/gato-huevos.avif"),
    new producto(5, "Taza conmemorativa de Mike", 8, "./src/IMG/taza-llamada.avif")
];

function mostrarProductos(){
    const contenedor = document.querySelector(".muestraProductos");
    contenedor.innerHTML = "";
    productos.forEach(
        prod => {
            const div = document.createElement("div");
            div.className = "Producto";
            div.innerHTML = `
            <img src="${prod.img}" style="width:200px; height:auto; object-fit:cover;">
            <h3>${prod.nombre}</h3>
            <p>Precio: €${prod.precioFinal ? prod.precioFinal() : prod.precio}</p>
            <button onclick="agregarCarrito(${prod.id})">Agregar al carrito</button>
            <button onclick="agregarDeseos(${prod.id})">Añadir a la lista de deseos</button>
            `;
            contenedor.appendChild(div);
        }
    );
}

mostrarProductos();



