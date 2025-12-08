/* Clase base que representa un producto generico */
class producto {
    /* Constructor que inicializa los atributos principales del producto */
    constructor(id, nombre, precio, img){
        this.id = id;
        this.nombre = nombre;
        this.precio = precio;
        this.img = img;
    }
}

/* Clase que extiende a producto e incorpora funcionalidad de descuento */
class productoConDescuento extends producto {
    /* Constructor que agrega el atributo descuento a los ya existentes */
    constructor(id, nombre, precio, descuento, img){
        super(id, nombre, precio, img);
        this.descuento = descuento;
    }
    /* Metodo que calcula el precio final tras aplicar el descuento */
    precioFinal(){
        return this.precio * (1 - this.descuento / 100);
    }
}

/* Arreglo que almacena instancias de producto */
const productos = [
    new producto(1, "Exclusiva taza de la llamada Zona Gemelos", 18, "./src/IMG/llamada-gemelos.jpeg"),
    new producto(2, "Entretenimiento de evacuacion primal", 12, "./src/IMG/golf-caca.avif"),
    new producto(3, "Delantal de nuestro mini ser favorito", 15, "./src/IMG/delantal-bula.avif"),
    new producto(4, "Gato lamiendo sus atributos apoteosicos", 4, "./src/IMG/gato-huevos.avif"),
    new producto(5, "Taza conmemorativa de Mike", 8, "./src/IMG/taza-llamada.avif")
];

/* Funcion que genera la visualizacion de los productos en el DOM */
function mostrarProductos(){
    /* Selecciona el contenedor donde se insertan los productos */
    const contenedor = document.querySelector(".muestraProductos");
    contenedor.innerHTML = "";
    /* Recorre productos y genera HTML individual */
    productos.forEach(
        prod => {
            const div = document.createElement("div");
            div.className = "Producto";
            div.innerHTML = `
    <img src="${prod.img}" style="width:270px; height:250px; object-fit:cover;">
    
    <div class="contenido-producto">
        <h3>${prod.nombre}</h3>
        <p>Precio: €${prod.precioFinal ? prod.precioFinal() : prod.precio}</p>
    </div>

    <button class="btn-carrito" onclick="agregarCarrito(${prod.id})">Agregar al carrito</button>
    <button class="btn-deseos" onclick="agregarDeseos(${prod.id})">Anadir a la lista de deseos</button>
`;

            contenedor.appendChild(div);
        }
    );
}
/* Renderiza los productos al cargar la pagina */
mostrarProductos();
