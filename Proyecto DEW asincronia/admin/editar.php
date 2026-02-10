<?php
/* Archivo para editar un producto en el panel admin */
/* Iniciar sesion y cargar dependencias */
session_start();
require_once '../config/autoload.php';

/* Verificar que el usuario sea admin y denegar acceso si no */
if ($_SESSION['rol'] !== 'admin')
    die(__('error_acceso_denegado'));

/* Obtener id desde GET y cargar producto desde modelo */
$id = $_GET['id'];
$producto = Producto::findById($id);

/* Si no existe el producto terminar con error traducido */
if (!$producto)
    die(__('error_producto_no_encontrado'));

/* Inicializar variable de error para mensajes */
$error = '';

/* Manejar envio del formulario para actualizar producto */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /* Normalizar y parsear datos enviados nombre precio y tipo */
    $nombre = trim($_POST['nombre']);
    $precio = floatval($_POST['precio']);
    $tipo = $_POST['tipo'];

    /* Verificar si existe otro producto con mismo nombre y tipo excluyendo el actual */
    if (Producto::nombreTipoExiste($nombre, $tipo, $id)) {
        $error = __("producto_duplicado") . ": " . $nombre . " (" . __($tipo) . ")";
    } else {
        /* Asignar valores al objeto producto segun datos del formulario */
        $producto->setNombre($nombre);
        $producto->setPrecio(floatval($precio));
        $producto->setTipo($tipo);

        /* Gestionar carga de imagen si se envio un archivo sin error */
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
            $imagen = time() . '_' . $_FILES['imagen']['name'];
            move_uploaded_file($_FILES['imagen']['tmp_name'], '../uploads/' . $imagen);
            $producto->setImagen($imagen);
        }

        /* Intentar guardar cambios y redirigir a index en caso de exito */
        try {
            if ($producto->save()) {
                header("Location: index.php");
                exit;
            } else {
                $error = "Error al actualizar el producto";
            }
        } catch (Exception $e) {
            /* Capturar excepcion y almacenar mensaje de error para mostrar */
            $error = $e->getMessage();
        }
    }
}

/* Obtener idioma actual y tema desde cookie por defecto claro */
$idioma = getIdiomaActual();
$tema = $_COOKIE['tema'] ?? 'claro';
?>
<!DOCTYPE html>
<html lang="<?php echo $idioma; ?>">

<head>
    <title><?php echo __('editar_producto'); ?></title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="../css/tema-<?php echo $tema; ?>.css">
</head>

<body class="tema-<?php echo $tema; ?>">
    <div class="container">
        <div class="preferencias-container">
            <h2 class="text-center"><?php echo __('editar_producto'); ?></h2>

            <?php if ($error): ?>
                <div class="error-message">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data">
                <label for="nombre"><?php echo __('nombre'); ?>:</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($producto->getNombre()) ?>"
                    required>

                <label for="precio"><?php echo __('precio'); ?> (€):</label>
                <input type="number" id="precio" name="precio" step="0.01" value="<?= $producto->getPrecio() ?>"
                    required>

                <label for="tipo"><?php echo __('tipo'); ?>:</label>
                <select id="tipo" name="tipo">
                    <option value="llaves" <?= $producto->getTipo() == 'llaves' ? 'selected' : '' ?>>
                        <?php echo __('llaves'); ?>
                    </option>
                    <option value="bolso" <?= $producto->getTipo() == 'bolso' ? 'selected' : '' ?>>
                        <?php echo __('bolso'); ?>
                    </option>
                    <option value="mochila" <?= $producto->getTipo() == 'mochila' ? 'selected' : '' ?>>
                        <?php echo __('mochila'); ?>
                    </option>
                </select>

                <?php if ($producto->getImagen()): ?>
                    <div class="mt-20">
                        <label><?php echo __('imagen_actual'); ?>:</label>
                        <img src="../uploads/<?= $producto->getImagen() ?>" width="150" class="img-thumbnail">
                    </div>
                <?php endif; ?>

                <label for="imagen"><?php echo __('nueva_imagen'); ?>:</label>
                <input type="file" id="imagen" name="imagen" accept="image/*">

                <div class="d-flex gap-10 mt-20">
                    <button type="submit" class="w-100">ᕦ•ᴥ•ᕤ <?php echo __('actualizar'); ?></button>
                    <a href="index.php" class="w-100 text-center">ᗜʘᗜ <?php echo __('cancelar'); ?></a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>