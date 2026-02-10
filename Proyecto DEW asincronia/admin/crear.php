<?php
/* Archivo que muestra formulario para crear nuevo producto y procesa el envio de datos
   Mantener logica original del archivo
   Agregar comentarios explicativos sin tildes y sin puntos
   Emoticonos en el HTML reemplazan emojis originales
*/

session_start();
/* Cargar el autoload para usar clases y funciones de la aplicacion */
require_once '../config/autoload.php';

/* Verificar que el usuario tenga rol admin y denegar acceso en caso contrario */
if ($_SESSION['rol'] !== 'admin')
    die(__('error_acceso_denegado'));

$error = '';

/* Procesar formulario cuando el metodo HTTP es POST */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /* Obtener y sanitizar campos recibidos nombre precio y tipo e inicializar imagen */
    $nombre = trim($_POST['nombre']);
    $precio = floatval($_POST['precio']);
    $tipo = $_POST['tipo'];
    $imagen = '';

    /* Verificar si ya existe un producto con el mismo nombre y tipo usando la clase Producto */
    if (Producto::nombreTipoExiste($nombre, $tipo)) {
        /* Asignar mensaje de error para producto duplicado */
        $error = __("producto_duplicado") . ": " . $nombre . " (" . __($tipo) . ")";
    } else {
        /* Manejo de subida de archivo si se envio una imagen sin errores */
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
            /* Generar nombre unico para la imagen y mover el archivo a la carpeta uploads */
            $imagen = time() . '_' . $_FILES['imagen']['name'];
            move_uploaded_file($_FILES['imagen']['tmp_name'], '../uploads/' . $imagen);
        }

        /* Preparar array con los datos del producto para crear la entidad */
        $datosProducto = [
            'nombre' => $nombre,
            'precio' => $precio,
            'tipo' => $tipo,
            'imagen' => $imagen
        ];

        try {
            /* Crear instancia de Producto desde el array y guardar en la persistencia */
            $producto = Producto::crearDesdeArray($datosProducto);

            if ($producto->save()) {
                /* Redirigir a la lista principal al guardar correctamente */
                header("Location: index.php");
                exit;
            } else {
                /* Asignar mensaje de error si la operacion de guardado fallo */
                $error = "Error al guardar el producto";
            }
        } catch (Exception $e) {
            /* Capturar cualquier excepcion y asignar su mensaje a la variable de error */
            $error = $e->getMessage();
        }
    }
}

/* Obtener configuracion de idioma y tema para la vista */
$idioma = getIdiomaActual();
$tema = $_COOKIE['tema'] ?? 'claro';
?>

<!DOCTYPE html>
<html lang="<?php echo $idioma; ?>">

<head>
    <title><?php echo __('nuevo_producto'); ?></title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="../css/tema-<?php echo $tema; ?>.css">
</head>

<body class="tema-<?php echo $tema; ?>">
    <div class="container">
        <div class="preferencias-container">
            <h2 class="text-center"><?php echo __('nuevo_producto'); ?></h2>

            <?php if ($error): ?>
                <div class="error-message">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data">
                <label for="nombre"><?php echo __('nombre'); ?>:</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="precio"><?php echo __('precio'); ?> (€):</label>
                <input type="number" id="precio" name="precio" step="0.01" required>

                <label for="tipo"><?php echo __('tipo'); ?>:</label>
                <select id="tipo" name="tipo">
                    <option value="llaves"><?php echo __('llaves'); ?></option>
                    <option value="bolso"><?php echo __('bolso'); ?></option>
                    <option value="mochila"><?php echo __('mochila'); ?></option>
                </select>

                <label for="imagen"><?php echo __('imagen'); ?>:</label>
                <input type="file" id="imagen" name="imagen" accept="image/*">

                <div class="d-flex gap-10 mt-20">
                    <button type="submit" class="w-100">𝜗𝜚 <?php echo __('guardar'); ?></button>
                    <a href="index.php" class="w-100 text-center">࣪˖ <?php echo __('cancelar'); ?></a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>