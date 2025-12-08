<?php
/* Inicializa la sesion */
session_start();

/* Procesa el formulario de preferencias */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /* Obtiene el modo seleccionado; por defecto claro */
    $modo = $_POST['modo'] ?? 'claro';

    /* Guarda las preferencias en cookie por 30 días */
    $preferencias = ['modo' => $modo];
    setcookie('preferencias', json_encode($preferencias), time() + (60*60*24*30), "/");

    /* Redirige a preferencias.php pasando el modo por GET para que se aplique inmediatamente */
    header('Location: preferencias.php?modo=' . urlencode($modo));
    exit;
}
?>
