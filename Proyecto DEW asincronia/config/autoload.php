<?php
/* Archivo de inicializacion que configura el autoload y carga dependencias */
/* Registro del autoloader de clases
   Esto registra una funcion anonima que recibe el nombre de la clase y busca el archivo correspondiente en la carpeta clases
   La funcion usa el patron de nombre de archivo basado en el nombre de la clase mas la extension php */
spl_autoload_register(function ($className) {
    $file = __DIR__ . '/../clases/' . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
require_once 'db.php';
require_once 'i18n.php';
?>