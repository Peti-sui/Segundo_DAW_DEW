<?php 
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $accion = $_POST['accion'] ?? '';

    // Vaciar lista completa
    if($accion === 'vaciar'){
        setcookie('listaDeseos', '', time() - 3600, '/');
    }

    // Eliminar un solo producto
    if($accion === 'eliminar'){
        $id = intval($_POST['id'] ?? 0);

        if(isset($_COOKIE['listaDeseos'])){
            $lista = json_decode($_COOKIE['listaDeseos'], true);

            // Filtrar el producto por ID
            $lista = array_filter($lista, function($item) use ($id){
                return $item['id'] != $id;
            });

            // Guardar de nuevo la cookie
            setcookie('listaDeseos', json_encode(array_values($lista)), time() + 3600*24*30, '/');
        }
    }
}

header('Location: listaDeseos.php');
