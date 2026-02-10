<?php
/* Inicio de sesion y carga de dependencias */
session_start();
require_once '../config/autoload.php';

/* Arreglo para almacenar errores de validacion */
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /* Carga de la conexion a la base de datos */
    require_once '../config/db.php';

    /* Obtiene y limpia los datos enviados por el formulario */
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    /* Valida campos obligatorios */
    if ($usuario === '' || $password === '') {
        $errores[] = __("error_todos_campos");
    }

    /* Evita que el usuario 'admin' sea registrado desde este formulario */
    if ($usuario === 'admin') {
        $errores[] = __("error_usuario_no_permitido");
    }

    /* Si no hay errores de validacion procede con la comprobacion en la base de datos */
    if (empty($errores)) {
        /* Prepara consulta para comprobar si el usuario ya existe */
        $stmt = $conn->prepare(
            "SELECT id FROM usuarios WHERE usuario = ?"
        );
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $stmt->store_result();

        /* Si la consulta devuelve filas significa que el usuario ya existe */
        if ($stmt->num_rows > 0) {
            $errores[] = __("error_usuario_existe");
        } else {
            /* Si el usuario no existe crea el hash de la password y asigna el rol por defecto */
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $rol = 'user';

            /* Prepara la insercion del nuevo usuario en la tabla usuarios */
            $stmt = $conn->prepare(
                "INSERT INTO usuarios (usuario, password, rol)
                 VALUES (?, ?, ?)"
            );
            $stmt->bind_param("sss", $usuario, $hash, $rol);
            $stmt->execute();

            /* Obtiene el id del nuevo registro insertado */
            $nuevo_id = $stmt->insert_id;

            /* Guarda en sesion los datos basicos del usuario autenticado */
            $_SESSION['id'] = $nuevo_id;
            $_SESSION['rol'] = $rol;
            $_SESSION['usuario'] = $usuario;

            /* Redirige al inicio y termina la ejecucion del script */
            header("Location: ../index.php");
            exit;
        }
    }
}

/* Obtiene el idioma actual y el tema seleccionado por cookie */
$idioma = getIdiomaActual();
$tema = $_COOKIE['tema'] ?? 'claro';
?>
<!DOCTYPE html>
<html lang="<?php echo $idioma; ?>">

<head>
    <title><?php echo __('registro_titulo'); ?></title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="../css/tema-<?php echo $tema; ?>.css>
</head>
<body class=" tema-<?php echo $tema; ?>">
    <div class="container">
        <div class="preferencias-container">
            <h2 class="text-center"><?php echo __('registro_titulo'); ?></h2>

            <?php if (!empty($errores)): ?>
                <div class="error-message">
                    <?php foreach ($errores as $error): ?>
                        <p><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <label for="usuario"><?php echo __('usuario'); ?>:</label>
                <input type="text" id="usuario" name="usuario" required>

                <label for="password"><?php echo __('password'); ?>:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit" class="w-100 mt-20">ϟɷ <?php echo __('registrarse'); ?></button>
            </form>

            <div class="text-center mt-20">
                <a href="login.php">⸮⋗ <?php echo __('volver_login'); ?></a>
            </div>

            <div class="text-center mt-10">
                <a href="../index_public.php">⸮⋗ <?php echo __('volver_tienda'); ?></a>
            </div>
        </div>
    </div>
    </body>

</html>