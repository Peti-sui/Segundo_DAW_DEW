<?php
/* Archivo de vista lista de deseos para la aplicacion */
/* Este archivo muestra los productos guardados en la lista de deseos del usuario */

/* Iniciar sesion y cargar dependencias */
session_start();
require_once '../config/autoload.php';
require_once '../config/db.php';

/* Verificar que el usuario este autenticado y redirigir a login si no */
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

/* Asignar id de usuario desde session */
$usuario_id = $_SESSION['id'];

/* Preparar y ejecutar consulta para obtener productos de la lista de deseos
   Se seleccionan campos necesarios y se ordena por fecha agregado descendente */
$deseosQuery = $conn->query(
    "SELECT ld.id as deseos_id, p.id as producto_id, p.nombre, p.precio, p.tipo, p.imagen, ld.fecha_agregado
     FROM lista_deseos ld
     JOIN productos p ON ld.producto_id = p.id
     WHERE ld.usuario_id = $usuario_id
     ORDER BY ld.fecha_agregado DESC"
);

/* Recopilar resultados en array items para su uso en la plantilla */
$items = [];
while ($item = $deseosQuery->fetch_assoc()) {
    $items[] = $item;
}

/* Obtener idioma actual y tema preferido del usuario
   El tema se obtiene desde cookie por defecto claro */
$idioma = getIdiomaActual();
$tema = $_COOKIE['tema'] ?? 'claro';

/* Recoger mensajes de error y exito desde query string */
$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';

/* Fin de la logica php y comienzo de plantilla html */
?>

<!DOCTYPE html>
<html lang="<?php echo $idioma; ?>">

<head>
    <title><?php echo __('lista_deseos'); ?></title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="../css/tema-<?php echo $tema; ?>.css">
</head>

<body class="tema-<?php echo $tema; ?>">
    <div class="container">
        <h1>𐌢𐌣 <?php echo __('lista_deseos'); ?></h1>

        <div class="nav-links">
            <a href="../index.php">← <?php echo __('volver_tienda'); ?></a>
            <a href="../carrito/ver.php">⟆⟈ <?php echo __('ver_carrito'); ?></a>
        </div>

        <?php if ($error === 'ya_en_deseos'): ?>
            <div class="error-message">
                <p>⪧⪦ <?php echo __('producto_ya_en_deseos'); ?></p>
            </div>
        <?php endif; ?>

        <?php if ($success === 'añadido'): ?>
            <div class="success-message">
                <p>ᚄᚅ <?php echo __('producto_añadido_deseos'); ?></p>
            </div>
        <?php endif; ?>

        <?php if (empty($items)): ?>
            <div class="info-box text-center">
                <h3><?php echo __('deseos_vacio'); ?></h3>
                <p><?php echo __('agrega_productos_deseos'); ?></p>
                <a href="../index.php" class="mt-20">ᖙᖚ <?php echo __('seguir_comprando'); ?></a>
            </div>
        <?php else: ?>
            <div class="productos-container mt-20">
                <?php foreach ($items as $item):
                    $producto = Producto::crearDesdeArray($item);
                    ?>
                    <div class="producto">
                        <?php if ($item['imagen']): ?>
                            <img src="../uploads/<?= $item['imagen'] ?>" width="100" class="img-thumbnail">
                        <?php endif; ?>

                        <h3><?= htmlspecialchars($item['nombre']) ?></h3>
                        <p><strong><?php echo __('tipo'); ?>:</strong> <?= __($item['tipo']) ?></p>
                        <p><strong><?php echo __('precio'); ?>:</strong> <?= number_format($item['precio'], 2) ?> €</p>
                        <p><small><?php echo __('agregado_el'); ?>:
                                <?= date('d/m/Y', strtotime($item['fecha_agregado'])) ?></small></p>

                        <div class="d-flex gap-10 mt-20">
                            <!-- Mover al carrito -->
                            <form action="mover_carrito.php" method="post" class="w-50">
                                <input type="hidden" name="deseos_id" value="<?= $item['deseos_id'] ?>">
                                <input type="hidden" name="producto_id" value="<?= $item['producto_id'] ?>">
                                <button type="submit" class="w-100">⟆⟈ <?php echo __('mover_carrito'); ?></button>
                            </form>

                            <!-- Eliminar de deseos -->
                            <form action="eliminar.php" method="post" class="w-50">
                                <input type="hidden" name="deseos_id" value="<?= $item['deseos_id'] ?>">
                                <button type="submit" class="w-100"
                                    onclick="return confirm('<?php echo __('eliminar_deseos_confirm'); ?>')">
                                    乂◜◬◝乂 <?php echo __('eliminar'); ?>
                                </button>
                            </form>
                        </div>

                        <?php if ($item['tipo'] == 'bolso'): ?>
                            <div class="info-box mt-10">
                                <strong>𐑒𐑓 <?php echo __('oferta_especial'); ?>!</strong><br>
                                <?php echo __('precio_fijo_desc'); ?>
                            </div>
                        <?php elseif ($item['tipo'] == 'mochila'): ?>
                            <div class="info-box mt-10">
                                <strong>⪨⪩ <?php echo __('producto_premium'); ?></strong><br>
                                <?php echo __('precio_doble_desc'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="d-flex gap-10 mt-30">
                <form action="vaciar.php" method="post" class="w-100">
                    <button type="submit" class="w-100"
                        onclick="return confirm('<?php echo __('vaciar_deseos_confirm'); ?>')">
                        ⧉⧈ <?php echo __('vaciar_deseos'); ?>
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($error === 'ya_en_deseos'): ?>
        <script>
            setTimeout(function () {
                alert('<?php echo __('producto_ya_en_deseos_alerta'); ?>');
            }, 500);
        </script>
    <?php endif; ?>
</body>

</html>