<?php
require_once 'Producto.php';

abstract class Paquete extends Producto
{
    public function __construct(string $nombre, float $precio, string $tipo = '')
    {
        parent::__construct($nombre, $precio, $tipo);
    }
}
?>