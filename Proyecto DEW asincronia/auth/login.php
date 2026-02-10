<?php
/* Iniciar sesion y cargar autocarga de clases 𝜗𝜚 */
/* Esta seccion mantiene el estado de sesion entre peticiones y asegura clases cargadas automaticamente */
session_start();
require_once '../config/autoload.php';

/* Variable para almacenar mensajes de error en el proceso de login */
$error = '';

/* Procesar el envio del formulario cuando la peticion HTTP es POST */
/* Aqui se valida el usuario y la password sin alterar la logica existente ࣪˖ */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /* Incluir configuracion de conexion a la base de datos */
    require_once '../config/db.php';

    /* Preparar consulta SQL segura para obtener id usuario password y rol por nombre de usuario */
    $stmt = $conn->prepare("SELECT id, usuario, password, rol FROM usuarios WHERE usuario=?");
    /* Vincular el parametro usuario desde los datos del formulario */
    $stmt->bind_param("s", $_POST['usuario']);
    /* Ejecutar la sentencia preparada */
    $stmt->execute();
    /* Obtener el resultado de la consulta como objeto mysqli_result */
    $res = $stmt->get_result();

    /* Comprobar si se encontro exactamente un registro para el nombre de usuario proporcionado */
    if ($res->num_rows === 1) {
        /* Obtener la fila resultante como arreglo asociativo con claves id usuario password rol */
        $u = $res->fetch_assoc();
        /* Verificar la password enviada contra el hash almacenado usando password_verify */
        if (password_verify($_POST['password'], $u['password'])) {
            /* Si la verificacion es correcta almacenar datos esenciales en la sesion */
            $_SESSION['id'] = $u['id'];
            $_SESSION['rol'] = $u['rol'];
            $_SESSION['usuario'] = $u['usuario'];
            /* Redirigir al index privado y terminar la ejecucion del script */
            header("Location: ../index.php");
            exit;
        } else {
            /* Password invalida asignar mensaje de error localizado usando la funcion de traduccion */
            $error = __("error_contrasena_incorrecta");
        }
    } else {
        /* Usuario no encontrado asignar mensaje de error localizado */
        $error = __("error_usuario_no_registrado");
    }
}

/* Obtener el idioma actual mediante la funcion de configuracion */
/* Obtener el tema visual desde cookie o usar tema claro por defecto ִ𐙚 */
$idioma = getIdiomaActual();
$tema = $_COOKIE['tema'] ?? 'claro';
?>
<!DOCTYPE html>
<html lang="<?php echo $idioma; ?>">

<head>
    <title>Login</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="../css/tema-<?php echo $tema; ?>.css">
</head>

<body class="tema-<?php echo $tema; ?>">
    <div class="container">
        <div class="preferencias-container">
            <h2 class="text-center">Login</h2>

            <?php if ($error): ?>
                <div class="error-message">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <label for="usuario"><?php echo __('usuario'); ?>:</label>
                <input type="text" id="usuario" name="usuario" required>

                <label for="password"><?php echo __('password'); ?>:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit" class="w-100 mt-20">𝜗𝜚 <?php echo __('entrar'); ?></button>
            </form>

            <div class="text-center mt-20">
                <a href="registro.php">࣪˖ <?php echo __('crear_cuenta'); ?></a>
            </div>

            <div class="text-center mt-10">
                <a href="../index_public.php">ִ𐙚 <?php echo __('volver_tienda'); ?></a>
            </div>
        </div>
    </div>
</body>

</html>