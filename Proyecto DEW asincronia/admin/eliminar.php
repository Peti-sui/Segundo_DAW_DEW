<?php
/* Iniciar la sesion PHP para acceder a variables de sesion */

/* Incluir el autoload para cargar clases y dependencias del proyecto */
session_start();
require_once '../config/autoload.php';

/* Comprobar que el usuario tiene el rol admin y terminar la ejecucion si no tiene acceso */
if ($_SESSION['rol'] !== 'admin')
    die(__('error_acceso_denegado'));

/* Obtener el identificador del producto enviado por GET sin alterar su valor */
$id = $_GET['id'];
/* Recuperar la entidad producto desde la capa de modelo usando su id */
$producto = Producto::findById($id);

/* Si se encontro el producto entonces proceder con la limpieza de recursos asociados */
if ($producto) {
    /* Comprobar si el producto tiene una imagen asociada y si el fichero existe en uploads */
    if ($producto->getImagen() && file_exists('../uploads/' . $producto->getImagen())) {
        /* Eliminar el fichero de imagen del sistema de ficheros */
        unlink('../uploads/' . $producto->getImagen());
    }

    /* Eliminar el registro del producto usando el metodo delete del objeto */
    $producto->delete();
}

/* Redirigir al listado de productos y terminar la ejecucion del script */
header("Location: index.php");
exit;
?>