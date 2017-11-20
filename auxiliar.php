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
 * @return array         La fila con los datos de la pelicula
 * @throws Exception     Si la pelicula no existe
 */
function buscarPelicula(PDO $pdo,int $id): array
{
    $sent = $pdo -> prepare("  SELECT *
                                FROM peliculas
                                WHERE id = :id");
    $sent -> execute([':id'=>$id]);
    $fila = $sent->fetch();

    if (empty($fila)) {
        throw new Exception('La pelicula no existe');
    }
    return $fila;
}

/**
 * Borra una pelicula a partir de su id
 * @param  PDO      $pdo Conexion a la base de datos
 * @param  int      $id  Id de la pelicula
 * @throws Exception     Si ha habido algun problema al borrar la pelicula
 */
function borrarPelicula(PDO $pdo,int $id): void
{
    $sent = $pdo -> prepare("DELETE FROM peliculas
                                    WHERE id = :id");
    $sent -> execute([':id' => $id]);
    if ($sent->rowCount() !== 1) {
        throw new Exception("Ha ocurrido un error al eliminar la pelicula");
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
 * @throws Exception         Si el parametro no es correcto
 */
function comprobarParametro($param): void
{
    if ($param === false) {
        throw new Exception("Parametro incorrecto");
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

function comprobaErrores(array $error):void
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
 * Muestra en pantalla el mensaje asociado a la excepcion capturada
 * @param Exception $e La excepcion capturada
 */
function mostrarError(Exception $e):void
{
    ?>
    <h3>Error: <?= $e->getMessage() ?></h3>
    <?php
    volver();
}
