<?php
require_once 'Paquete.php';

class PaqueteMochila extends Paquete
{
    public function __construct(string $nombre, float $precio)
    {
        parent::__construct($nombre, $precio, 'mochila');
    }

    public function calcularPrecio(int $cantidad): float
    {
        return ($this->precio * $cantidad) * 2;
    }
}
?>