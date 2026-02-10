<?php
/* Inicia sesion y establece contexto de usuario  ꕥ‿ꕥ */
session_start();

/* Comprueba si el usuario esta autenticado y redirige al login  ᚖᚗ */
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

/* Carga autoload y conexion a la base de datos  ‹•ᴗ•› */
require_once '../config/autoload.php';
require_once '../config/db.php';

/* Obtiene id de usuario desde la sesion  ᵔᴥᵔ */
$usuario_id = $_SESSION['id'];

/* Consulta pedidos del usuario con total de productos agrupados y ordenados por fecha  ɸ♪ */
$pedidosQuery = $conn->query(
    "SELECT p.*, COUNT(dp.id) as total_productos
     FROM pedidos p
     LEFT JOIN detalle_pedido dp ON p.id = dp.pedido_id
     WHERE p.usuario_id = $usuario_id
     GROUP BY p.id
     ORDER BY p.fecha DESC"
);

/* Determina idioma y tema de la interfaz  ʘᗜʘ */
$idioma = getIdiomaActual();
$tema = $_COOKIE['tema'] ?? 'claro';
?>

<!DOCTYPE html>
<html lang="<?php echo $idioma; ?>">

<head>
    <title><?php echo __('mis_pedidos'); ?></title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="../css/tema-<?php echo $tema; ?>.css">
</head>

<body class="tema-<?php echo $tema; ?>">
    <div class="container">
        <h1><?php echo __('mis_pedidos'); ?></h1>

        <div class="nav-links">
            <a href="../index.php">‹•ᴗ•› <?php echo __('volver_tienda'); ?></a>
            <a href="ver.php">ᚖᚗ <?php echo __('ver_carrito'); ?></a>
        </div>

        <?php /* Comprueba si hay pedidos para mostrar  ᚘᚙ */ ?>
        <?php if ($pedidosQuery->num_rows === 0): ?>
            <div class="info-box text-center">
                <h3><?php echo __('no_pedidos'); ?></h3>
                <a href="../index.php" class="mt-20">ᚖᚗ <?php echo __('hacer_primer_pedido'); ?></a>
            </div>
        <?php else: ?>
            <div class="productos-container mt-20">
                <?php /* Recorre cada pedido y muestra datos principales  ꒰՞‿՞꒱ */ ?>
                <?php while ($pedido = $pedidosQuery->fetch_assoc()): ?>
                    <div class="producto">
                        <h3><?php echo __('pedido'); ?> #<?= $pedido['id'] ?></h3>

                        <p><strong><?php echo __('fecha'); ?>:</strong> <?= date('d/m/Y H:i:s', strtotime($pedido['fecha'])) ?>
                        </p>
                        <p><strong><?php echo __('estado'); ?>:</strong>
                            <span class="badge 
                            <?= $pedido['estado'] == 'completado' ? 'badge-success' : '' ?>
                            <?= $pedido['estado'] == 'procesado' ? 'badge-info' : '' ?>
                            <?= $pedido['estado'] == 'pendiente' ? 'badge-warning' : '' ?>
                            <?= $pedido['estado'] == 'cancelado' ? 'badge-error' : '' ?>">
                                <?= ucfirst($pedido['estado']) ?>
                            </span>
                        </p>
                        <p><strong><?php echo __('total_productos'); ?>:</strong> <?= $pedido['total_productos'] ?></p>
                        <p class="precio"><strong><?php echo __('total'); ?>:</strong> <?= number_format($pedido['total'], 2) ?>
                            €</p>

                        <div class="mt-20">
                            <a href="detalle_pedido.php?id=<?= $pedido['id'] ?>" class="w-100 text-center">
                                ꕥ‿ꕥ <?php echo __('ver_detalles'); ?>
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>