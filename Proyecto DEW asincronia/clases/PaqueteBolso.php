<?php
require_once 'Paquete.php';

class PaqueteBolso extends Paquete
{
    public function __construct(string $nombre, float $precio)
    {
        parent::__construct($nombre, $precio, 'bolso');
    }

    public function calcularPrecio(int $cantidad): float
    {
        return $this->precio;
    }
}
?>