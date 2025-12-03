class producto {
    constructor(id, nombre, precio){
        this.id = id;
        this.nombre = nombre;
        this.precio = precio;
    }
}

class productoConDescuento extends producto {
    constructor(id, nombre, precio, descuento){
        super(id, nombre, precio);
        this.descuento = descuento;
    }

    precioFinal(){
        return this.precio * (1 - this.descuento / 100);
    }

}

