<?php
/* Archivo que procesa el pago y crea el pedido  ᗣᘛ 𐑂✶ */
session_start();
/* Cargar autoload de clases y conexion a la base de datos  ᗣᘛ */
require_once '../config/autoload.php';
require_once '../config/db.php';

/* Verificar que el usuario este autenticado y redirigir a login si no  𐑂✶ */
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

/* Guardar id de usuario autenticado en variable local  ᗣᘛ */
$usuario_id = $_SESSION['id'];

/* Obtener los productos del carrito del usuario
   Se usa join con la tabla productos para obtener datos necesarios para el pedido  𐑂✶ */
$carritoQuery = $conn->query(
    "SELECT c.id AS carrito_id, p.id AS producto_id, p.nombre, p.precio, p.tipo, c.cantidad
     FROM carrito c
     JOIN productos p ON c.producto_id = p.id
     WHERE c.usuario_id = $usuario_id"
);

/* Si no hay items en el carrito redirigir a la vista del carrito con error  ᗣᘛ */
if ($carritoQuery->num_rows === 0) {
    header("Location: ver.php?error=carrito_vacio");
    exit;
}

/* Calcular el total del pedido y preparar un arreglo de productos con subtotales
   Se transforma cada fila a la entidad Producto y se calcula el subtotal por cantidad  𐑂✶ */
$total = 0;
$productos = [];
while ($item = $carritoQuery->fetch_assoc()) {
    $producto = Producto::crearDesdeArray($item);
    $subtotal = $producto->calcularPrecio($item['cantidad']);
    $total += $subtotal;

    $productos[] = [
        'producto_id' => $item['producto_id'],
        'nombre' => $item['nombre'],
        'tipo' => $item['tipo'],
        'precio_unitario' => $item['precio'],
        'cantidad' => $item['cantidad'],
        'subtotal' => $subtotal
    ];
}

/* Iniciar una transaccion para asegurar la consistencia de las operaciones que siguen
   Esto permite confirmar todas las operaciones juntas o revertir en caso de error  ᗣᘛ */
$conn->begin_transaction();

try {
    /* Insertar la fila principal en la tabla pedidos y obtener el id generado
       El estado se establece en pendiente  𐑂✶ */
    $stmtPedido = $conn->prepare(
        "INSERT INTO pedidos (usuario_id, total, estado) VALUES (?, ?, 'pendiente')"
    );
    $stmtPedido->bind_param("id", $usuario_id, $total);
    $stmtPedido->execute();
    $pedido_id = $stmtPedido->insert_id;

    /* Preparar la sentencia para insertar cada detalle de pedido
       Los campos incluyen referencia al pedido producto cantidad precio unitario y subtotal  ᗣᘛ */
    $stmtDetalle = $conn->prepare(
        "INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio_unitario, subtotal) 
         VALUES (?, ?, ?, ?, ?)"
    );

    /* Iterar la lista de productos y ejecutar la insercion de detalle para cada producto  𐑂✶ */
    foreach ($productos as $producto) {
        $stmtDetalle->bind_param(
            "iiidd",
            $pedido_id,
            $producto['producto_id'],
            $producto['cantidad'],
            $producto['precio_unitario'],
            $producto['subtotal']
        );
        $stmtDetalle->execute();
    }

    /* Vaciar el carrito del usuario eliminando todas las filas asociadas al usuario  ᗣᘛ */
    $conn->query("DELETE FROM carrito WHERE usuario_id = $usuario_id");

    /* Confirmar la transaccion para persistir todos los cambios  𐑂✶ */
    $conn->commit();

    /* Redirigir a la pagina de confirmacion de pago pasando el id del pedido  ᗣᘛ */
    header("Location: confirmacion_pago.php?id=" . $pedido_id);
    exit;

} catch (Exception $e) {
    /* En caso de error revertir la transaccion para restaurar el estado anterior
       y redirigir a la vista del carrito con un error generico  𐑂✶ */
    $conn->rollback();
    header("Location: ver.php?error=proceso_fallido");
    exit;
}
?>