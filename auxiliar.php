<?php

/**
 * Crea una conexion a la base de datos y la devuelve
 * @return PDO          La instancia de la clase pdo que representa la conexion
 * @throws PDOException Si se produce algun error que impide la conexion
 */
function conectar(): PDO
{
    try {
        return new PDO('pgsql:host=localhost;dbname=fa','fa','fa');
    } catch (Exception $e) {
        ?>
        <h1>Error catastrofico de base de datos: no se puede continuar</h1>
        <?php
        throw $e;
    }


}

/**
 * Busca una pelicula a partir de su id
 * @param  PDO      $pdo Conexion a la base de datos
 * @param  int      $id  Id de la pelicula
 * @param  array    $error El array de errores
 * @return array         La fila con los datos de la pelicula
 * @throws Exception     Si la pelicula no existe
 */
function buscarPelicula(PDO $pdo,int $id,array &$error): array
{
    $sent = $pdo -> prepare("  SELECT *
                                FROM peliculas
                                WHERE id = :id");
    $sent -> execute([':id'=>$id]);
    $fila = $sent->fetch();

    if (empty($fila)) {
        $error = 'La pelicula no existe';
        throw new Exception;

    }
    return $fila;
}

/**
 * Borra una pelicula a partir de su id
 * @param  PDO      $pdo Conexion a la base de datos
 * @param  int      $id  Id de la pelicula
 * @throws Exception     Si ha habido algun problema al borrar la pelicula
 */
function borrarPelicula(PDO $pdo,int $id,array &$error): void
{
    $sent = $pdo -> prepare("DELETE FROM peliculas
                                    WHERE id = :id");
    $sent -> execute([':id' => $id]);
    if ($sent->rowCount() !== 1) {
        $error = "Ha ocurrido un error al eliminar la pelicula";
        throw new Exception;
    }
}

/**
 * Comprueba si un parametro es correcto
 *
 * Un parametro se considera correcto si ha superado los filtros de
 * validacion de filter_imput(). Si el parametro no existe entendemos que
 * su valor tambien es falso por lo cual solo tenemos que comprobar si el
 * valor no es falso
 * @param  mixed      $param El parametro a comprobar
 * @throws ErrorException   Si el parametro no es correcto
 */
function comprobarParametro($param,array &$error): void
{
    if ($param === false) {
        $error = "Parametro incorrecto";
        throw new Exception;
    }
}

function comprobarTitulo(string $titulo,array &$error):void
{
    if ($titulo === '') {
        $error[] = "El titulo es obligatorio";
        return;
    }
    if (mb_strlen($titulo)>255) {
        $error[] = "El titulo es demasiado largo";
    }
}

function comprobarAnyo(string $anyo,array &$error):void
{
    if ($anyo === '') {
        return;
    }
    $filtro=filter_var($anyo, FLTER_VALIDATE_INT, [
        'options' => [
            'min_range' => 0,
            'max_range' => 9999,
        ],
    ]);
    if ($filtro === false) {
        $error[] = 'No es un año valido';
    }
}

function comprobarDuracion(string $duracion,array &$error):void
{
    if ($duracion === '') {
        return;
    }
    $filtro=filter_var($duracion, FLTER_VALIDATE_INT, [
        'options' => [
            'min_range' => 0,
            'max_range' => 32767,
        ],
    ]);
    if ($filtro === false) {
        $error[] = 'No es una duracion valida';
    }
}

function comprobarErrores(array &$error):void
{
    if (!empty($error)) {
        throw new Exception($error);
    }
}

/**
 * Muestra un enlace a la pagina principal index.php con el texto 'volver'
 */
function volver():void
{
    ?>
    <a href="index.php">Volver</a>
    <?php
}

/**
 * Escapa una cadena correctamente
 * @param  string $cadena La cadena a escapar
 * @return string         La cadena escapada
 */
function h(string $cadena):string
{
    return htmlspecialchars($cadena, ENT_QUOTES | ENT_SUBSTITUTE);
}

/**
 * Muestra en pantalla los mensajes de error capturados hasta el momento
 * @param array $e Los mensajes capturados
 */
function mostrarError(array &$error):void
{
    foreach ($error as $v) {
        ?>
        <h3>Error: <?= h($v) ?></h3>
        <?php
    }

    volver();
}
