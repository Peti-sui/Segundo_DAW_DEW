<?php
/* Inicia la sesion y permite acceder a variables de sesion 𝜗𝜚 */
/* Valida la existencia de una sesion activa y verifica que el rol sea admin en caso contrario detiene la ejecucion 𝜚࣪˖ */
session_start();

if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
    die(__('error_acceso_denegado'));
}

/* Carga el autoload y las dependencias necesarias del proyecto 𝜗 */
/* No se modifica la logica de carga solo se incluye el archivo de configuracion 𝜚 */
require_once '../config/autoload.php';

/* Obtiene el idioma actual mediante la funcion del sistema para renderizar la pagina en el idioma adecuado 𝜗 */
/* Se mantiene la funcion original sin cambios 𝜚 */
$idioma = getIdiomaActual();

/* Lee la preferencia de tema desde cookie y asigna el valor por defecto claro si no existe 𝜗 */
$tema = $_COOKIE['tema'] ?? 'claro';

/* Recupera todos los productos mediante el modelo Producto sin alterar datos ni estructura 𝜗 */
/* Esta variable se usa posteriormente en la parte de vista para listar productos 𝜚 */
$productos = Producto::getAll();
?>
<!DOCTYPE html>
<html lang="<?php echo $idioma; ?>">

<head>
    <title><?php echo __('panel_titulo'); ?></title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="../css/tema-<?php echo $tema; ?>.css">
</head>

<body class="tema-<?php echo $tema; ?>">
    <div class="container">
        <h1><?php echo __('panel_titulo'); ?></h1>

        <div class="admin-notice">
            <p>𝜗𝜚 <?php echo __('bienvenido_admin'); ?>,
                <strong><?= htmlspecialchars($_SESSION['usuario'] ?? 'Admin') ?></strong>
            </p>
        </div>

        <div class="nav-links">
            <a href="crear.php">࣪˖ <?php echo __('nuevo_producto'); ?></a>
            <a href="pedidos.php">𝜚𐙚 <?php echo __('gestion_pedidos'); ?></a>
            <a href="../index.php">⟡ <?php echo __('volver_tienda'); ?></a>
        </div>

        <?php if (empty($productos)): ?>
            <div class="info-box text-center">
                <p><?php echo __('no_productos'); ?></p>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th><?php echo __('nombre'); ?></th>
                        <th><?php echo __('precio'); ?></th>
                        <th><?php echo __('tipo'); ?></th>
                        <th><?php echo __('imagen'); ?></th>
                        <th><?php echo __('acciones'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?= htmlspecialchars($producto->getNombre()) ?></td>
                            <td><?= number_format($producto->getPrecio(), 2) ?> €</td>
                            <td><?= __($producto->getTipo()) ?></td>
                            <td>
                                <?php if ($producto->getImagen()): ?>
                                    <img src="../uploads/<?= htmlspecialchars($producto->getImagen()) ?>" width="50"
                                        class="img-thumbnail">
                                <?php else: ?>
                                    <span class="text-muted"><?php echo __('sin_imagen'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="d-flex gap-10">
                                <a href="editar.php?id=<?= $producto->getId() ?>" class="text-center">֍𝜗
                                    <?php echo __('editar'); ?></a>
                                <a href="eliminar.php?id=<?= $producto->getId() ?>"
                                    onclick="return confirm('<?php echo __('error_eliminar_producto'); ?>')"
                                    class="text-center">࿇𝜚 <?php echo __('eliminar'); ?></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>