<?php
/* Iniciar sesion para mantener el estado del usuario */
session_start();

/* Cargar autoload y configuracion de base de datos */
require_once '../config/autoload.php';
require_once '../config/db.php';

/* Verificar que el usuario este autenticado en la sesion */
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

/* Obtener id de usuario desde la sesion y id del deseo enviado por POST */
$usuario_id = $_SESSION['id'];
$deseos_id = $_POST['deseos_id'];

/* Preparar consulta para verificar que el item pertenece al usuario */
/* Esto evita que un usuario elimine items de otros usuarios */
$checkStmt = $conn->prepare(
    "SELECT id FROM lista_deseos WHERE id = ? AND usuario_id = ?"
);
$checkStmt->bind_param("ii", $deseos_id, $usuario_id);

/* Ejecutar la consulta de verificacion y obtener el resultado */
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

/* Comprobar si la consulta no encontro el registro correspondiente */
/* En ese caso redirigir indicando acceso denegado y terminar ejecucion */
if ($checkResult->num_rows === 0) {
    header("Location: ver.php?error=acceso_denegado");
    exit;
}

/* Preparar la sentencia para eliminar el item de la lista de deseos */
/* Se usa una sentencia preparada para prevenir inyeccion SQL */
$deleteStmt = $conn->prepare(
    "DELETE FROM lista_deseos WHERE id = ?"
);
$deleteStmt->bind_param("i", $deseos_id);

/* Ejecutar la eliminacion del registro */
$deleteStmt->execute();

/* Redirigir a la pagina de visualizacion con indicador de exito */
header("Location: ver.php?success=eliminado");
exit;
?>