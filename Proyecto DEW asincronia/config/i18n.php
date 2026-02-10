<?php
/* 𝜗𝜚 Libreria de gestion de idiomas
   Contiene funciones para cargar archivos de idioma traducir obtener y cambiar idioma
*/

/* ᛣ Cargador de idiomas
   Esta funcion intenta cargar un archivo de idioma segun el codigo pasado
   Si el archivo existe se requiere y se retorna su contenido
   Si no existe se requiere el archivo por defecto es en español
*/
function cargarIdioma($idioma = 'es') {
    /* 𐙚 Construccion de la ruta del archivo de idioma relativa a este directorio */
    $archivoIdioma = __DIR__ . '/../idiomas/' . $idioma . '.php';
    
    /* 𝜗 Comprobacion de existencia del archivo y carga si existe */
    if (file_exists($archivoIdioma)) {
        return require $archivoIdioma;
    }
    
    /* ᚖ Si no existe el archivo solicitado se carga el archivo por defecto es.php */
    return require __DIR__ . '/../idiomas/es.php';
}

/* 𐙚 Funcion de traduccion
   Esta funcion devuelve la cadena traducida para una clave dada
   Implementa cache estatico para evitar recargas repetidas de los archivos de idioma
   El parametro idioma no se utiliza actualmente la funcion toma el idioma de la cookie
*/
function __($key, $idioma = null) {
    /* 𝜗 Cache estatico de traducciones inicializado a null */
    static $translations = null;
    
    /* ᛣ Inicializacion del cache en la primera llamada
       Se lee la cookie idioma si existe sino se usa es por defecto
       Se carga el archivo de idioma mediante cargarIdioma
    */
    if ($translations === null) {
        $idiomaCookie = $_COOKIE['idioma'] ?? 'es';
        $translations = cargarIdioma($idiomaCookie);
    }
    
    /* 𐙚 Devolucion de la traduccion si existe sino devolvemos la clave original */
    return $translations[$key] ?? $key;
}

/* 𝜚 Funcion para obtener el idioma actual
   Lee la cookie idioma y devuelve su valor si existe sino devuelve es
*/
function getIdiomaActual() {
    return $_COOKIE['idioma'] ?? 'es';
}

/* ᚖ Funcion para cambiar el idioma del usuario
   Establece una cookie llamada idioma con el nuevo codigo y expiracion de 30 dias
   El path se establece en la raiz para que la cookie sea accesible en todo el sitio
*/
function cambiarIdioma($nuevoIdioma) {
    setcookie("idioma", $nuevoIdioma, time() + 2592000, "/");
}
?>