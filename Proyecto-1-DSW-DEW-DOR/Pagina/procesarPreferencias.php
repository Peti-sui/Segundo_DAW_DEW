<?php
/* Inicializa la sesion */
session_start();
/* Guarda preferencias en cookie al recibir el formulario */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $modo = $_POST['modo'] ?? 'claro';
    $preferencias = [
        'modo' => $modo
    ];
    setcookie('preferencias', json_encode($preferencias), time() + (60 * 60 * 24 * 30), "/");
}
/* Redirige al panel de preferencias */
header('Location: preferencias.php');
exit;
?>
