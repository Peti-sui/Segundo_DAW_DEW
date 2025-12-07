/* CLASE BASE QUE REPRESENTA UN PRODUCTO GENERICO */
class producto {
    /* CONSTRUCTOR QUE INICIALIZA LOS ATRIBUTOS PRINCIPALES DEL PRODUCTO */
    constructor(id, nombre, precio, img){
        this.id = id;
        this.nombre = nombre;
        this.precio = precio;
        this.img = img;
    }
}

/* CLASE QUE EXTIENDE A PRODUCTO E INCORPORA FUNCIONALIDAD DE DESCUENTO */
class productoConDescuento extends producto {
    /* CONSTRUCTOR QUE AGREGA EL ATRIBUTO DESCUENTO A LOS YA EXISTENTES */
    constructor(id, nombre, precio, descuento, img){
        super(id, nombre, precio, img);
        this.descuento = descuento;
    }

    /* METODO QUE CALCULA EL PRECIO FINAL TRAS APLICAR EL DESCUENTO */
    precioFinal(){
        return this.precio * (1 - this.descuento / 100);
    }

}

/* ARREGLO QUE ALMACENA INSTANCIAS DE PRODUCTO PARA SU POSTERIOR RENDERIZADO */
const productos = [
    new producto(1, "Exclusiva taza de la llamada Zona Gemelos", 18, "./src/IMG/llamada-gemelos.jpeg"),
    new producto(2, "Entretenimiento de evacuacion primal", 12, "./src/IMG/golf-caca.avif"),
    new producto(3, "Delantal de nuestro mini ser favorito", 15, "./src/IMG/delantal-bula.avif"),
    new producto(4, "Gato lamiendo sus atributos", 4, "./src/IMG/gato-huevos.avif"),
    new producto(5, "Taza conmemorativa de Mike", 8, "./src/IMG/taza-llamada.avif")
];

/* FUNCION QUE GENERA LA VISUALIZACION DE LOS PRODUCTOS EN EL DOM */
function mostrarProductos(){
    /* SELECCIONA EL CONTENEDOR DONDE SE INSERTARAN LOS PRODUCTOS */
    const contenedor = document.querySelector(".muestraProductos");
    contenedor.innerHTML = "";

    /* RECORRE EL ARREGLO DE PRODUCTOS Y GENERA SU ESTRUCTURA HTML */
    productos.forEach(
        prod => {
            const div = document.createElement("div");
            div.className = "Producto";

            /* SE CREA EL MARCADO HTML DE CADA PRODUCTO 
               SE UTILIZA PRECIOFINAL SI EXISTE (PARA PRODUCTOS CON DESCUENTO) */
            div.innerHTML = `
            <img src="${prod.img}" style="width:200px; height:auto; object-fit:cover;">
            <h3>${prod.nombre}</h3>
            <p>Precio: €${prod.precioFinal ? prod.precioFinal() : prod.precio}</p>
            <button onclick="agregarCarrito(${prod.id})">Agregar al carrito</button>
            <button onclick="agregarDeseos(${prod.id})">Anadir a la lista de deseos</button>
            `;
            
            /* AGREGA EL ELEMENTO GENERADO AL CONTENEDOR PRINCIPAL */
            contenedor.appendChild(div);
        }
    );
}

/* LLAMADA INICIAL PARA RENDERIZAR LOS PRODUCTOS AL CARGAR LA PAGINA */
mostrarProductos();
