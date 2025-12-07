<?php 
/* Procesa las operaciones sobre la lista de deseos */
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $accion = $_POST['accion'] ?? '';
    if($accion === 'vaciar'){
        setcookie('listaDeseos', '', time() - 3600, '/');
    }
    if($accion === 'eliminar'){
        $id = intval($_POST['id'] ?? 0);
        if(isset($_COOKIE['listaDeseos'])){
            $lista = json_decode($_COOKIE['listaDeseos'], true);
            $lista = array_filter($lista, function($item) use ($id){
                return $item['id'] != $id;
            });
            setcookie('listaDeseos', json_encode(array_values($lista)), time() + 3600*24*30, '/');
        }
    }
}
/* Redirige a la pagina lista de deseos */
header('Location: listaDeseos.php');
?>
