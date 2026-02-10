<?php
require_once 'Paquete.php';

class PaqueteLlaves extends Paquete
{
    public function __construct(string $nombre, float $precio)
    {
        parent::__construct($nombre, $precio, 'llaves');
    }


    public function calcularPrecio(int $cantidad): float
    {
        return $this->precio * $cantidad;
    }
}
?>