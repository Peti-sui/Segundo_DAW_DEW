<?php
require_once 'Producto.php';

class Llavero extends Producto
{
    public function __construct(string $nombre, float $precio, string $tipo = '')
    {
        parent::__construct($nombre, $precio, $tipo);
    }

    public function calcularPrecio(int $cantidad): float
    {
        return $this->precio * $cantidad;
    }
}
?>