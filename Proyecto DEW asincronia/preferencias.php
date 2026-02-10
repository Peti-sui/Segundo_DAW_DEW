<?php
/* Inicio de sesion para mantener datos del usuario en la sesion */
/* Sesion requerida para gestionar estados entre peticiones */
session_start();

/* Carga del autoload para disponer de clases y funciones necesarias */
/* Se utiliza require_once para evitar cargas multiples */
require_once 'config/autoload.php';

/* Manejo de envio de formulario mediante metodo POST */
/* Se valida que la peticion sea POST para procesar cambios de preferencia */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /* Obtencion del idioma enviado o valor por defecto es */
    $idioma = $_POST['idioma'] ?? 'es';
    /* Obtencion del tema enviado o valor por defecto es claro */
    $tema = $_POST['tema'] ?? 'claro';

    /* Cambio de idioma mediante la funcion cambiarIdioma definida en el proyecto */
    cambiarIdioma($idioma);
    /* Guardado de la preferencia de tema en una cookie por 30 dias */
    setcookie("tema", $tema, time() + 2592000, "/");

    /* Redireccion para evitar reenvio del formulario y aplicar cambios imediatamente */
    header("Location: preferencias.php");
    exit;
}

/* Obtencion del idioma actual usando la funcion getIdiomaActual */
/* Esta variable se usa para establecer el lang del documento y seleccionar opciones */
$idioma_actual = getIdiomaActual();
/* Obtencion del tema actual desde cookie o valor por defecto claro */
$tema_actual = $_COOKIE['tema'] ?? 'claro';
?>
<!DOCTYPE html>
<html lang="<?php echo $idioma_actual; ?>">

<head>
    <title><?php echo __('preferencias_titulo'); ?></title>
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="css/tema-<?php echo $tema_actual; ?>.css">
</head>

<body class="tema-<?php echo $tema_actual; ?>">
    <div class="container">
        <div class="preferencias-container">
            <h2 class="text-center"><?php echo __('preferencias_titulo'); ?></h2>

            <div class="info-box">
                <p><strong><?php echo __('configuracion_actual'); ?>:</strong></p>
                <p><?php echo __('idioma'); ?>: <?php echo $idioma_actual == 'es' ? __('espanol') : __('ingles'); ?></p>
                <p><?php echo __('tema'); ?>: <?php echo $tema_actual == 'claro' ? __('claro') : __('oscuro'); ?></p>
            </div>

            <form method="post">
                <label for="idioma"><?php echo __('idioma'); ?>:</label>
                <select name="idioma" id="idioma">
                    <option value="es" <?php echo $idioma_actual == 'es' ? 'selected' : ''; ?>>
                        <?php echo __('espanol'); ?>
                    </option>
                    <option value="en" <?php echo $idioma_actual == 'en' ? 'selected' : ''; ?>><?php echo __('ingles'); ?>
                    </option>
                </select>

                <label for="tema"><?php echo __('tema'); ?>:</label>
                <select name="tema" id="tema">
                    <option value="claro" <?php echo $tema_actual == 'claro' ? 'selected' : ''; ?>>
                        <?php echo __('claro'); ?>
                    </option>
                    <option value="oscuro" <?php echo $tema_actual == 'oscuro' ? 'selected' : ''; ?>>
                        <?php echo __('oscuro'); ?>
                    </option>
                </select>

                <button type="submit" class="w-100 mt-20">҂⌖ <?php echo __('guardar_preferencias'); ?></button>
            </form>

            <div class="text-center mt-20">
                <a href="index.php">҂⟲ <?php echo __('volver_tienda'); ?></a>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('tema').addEventListener('change', function () {
            const temaActual = this.value;
            const linkTema = document.querySelector('link[href*="tema-"]');

            if (linkTema) {
                linkTema.href = 'css/tema-' + temaActual + '.css';
            }

            document.body.className = 'tema-' + temaActual;
        });

        document.getElementById('idioma').addEventListener('change', function () {
            document.documentElement.lang = this.value;
        });
    </script>
</body>

</html>