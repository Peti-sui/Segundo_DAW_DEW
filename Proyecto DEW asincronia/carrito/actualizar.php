<?php
/* iniciar sesion para mantener datos del usuario 𐑂ᛦ */
session_start();

/* incluir archivo de configuracion de la base de datos y obtener conexion ᗣ৸૱ */
require_once '../config/db.php';

/* obtener id del carrito y la accion enviadas por metodo POST 𐙚ᚖ */
$id = $_POST['carrito_id'];
$accion = $_POST['accion'];

/* ejecutar consulta para obtener la cantidad actual del item en el carrito 𐑂ᛦ */
/* nota no se realiza sanitizacion previa del id en el codigo original esto puede implicar riesgo de inyeccion ᛦᚖ */
$res = $conn->query("SELECT cantidad FROM carrito WHERE id=$id");
$r = $res->fetch_assoc();
$cantidad = $r['cantidad'];

/* modificar la cantidad segun la accion recibida sin cambiar la logica original ᗣ৸૱ */
if ($accion === 'mas') {
    $cantidad++;
} elseif ($accion === 'menos') {
    $cantidad--;
}

/* si la cantidad resultante es mayor que cero actualizar el registro en la base de datos 𐙚ᚖ */
/* se utiliza prepared statement para la actualizacion tal como en el codigo original 𐑂ᛦ */
if ($cantidad > 0) {
    $stmt = $conn->prepare("UPDATE carrito SET cantidad=? WHERE id=?");
    $stmt->bind_param("ii", $cantidad, $id);
    $stmt->execute();
} else {
    /* si la cantidad es cero o menor eliminar el item del carrito segun la logica original ᛦᚖ */
    $conn->query("DELETE FROM carrito WHERE id=$id");
}

/* redirigir al usuario a la pagina de visualizacion del carrito y terminar ejecucion 𐙚ᚖ */
header("Location: ver.php");
exit;
?>