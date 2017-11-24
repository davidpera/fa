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
 * @param  array    $error El array de errores
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
 * @param  array    $error El array de errores
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
    $filtro=filter_var($anyo, FILTER_VALIDATE_INT, [
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
    $filtro=filter_var($duracion, FILTER_VALIDATE_INT, [
        'options' => [
            'min_range' => 0,
            'max_range' => 32767,
        ],
    ]);
    if ($filtro === false) {
        $error[] = 'No es una duracion valida';
    }
}

function comprobarGenero(PDO $pdo,$genero_id, array &$error):void
{
    if ($genero_id === ''){
        $error[] = 'El genero es obligatorio';
        return;
    }
    $filtro=filter_var($genero_id, FILTER_VALIDATE_INT);
    if ($filtro === false) {
        $error[] = 'El genenro debe ser un numero entero';
        return;
    }
    $sent = $pdo -> prepare('SELECT COUNT(*)
                                FROM generos
                                where id = :genero_id');
    $sent->execute([':genero_id' => $genero_id]);
    if ($sent->fetchColumn() === 0) {
        $error[] = 'El genero no existe';
    }
}

function comprobarErrores(array &$error):void
{
    if (!empty($error)) {
        throw new Exception;
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
function h(?string $cadena):string
{
    return htmlspecialchars($cadena, ENT_QUOTES | ENT_SUBSTITUTE);
}

/**
 * Muestra en pantalla los mensajes de error capturados hasta el momento
 * @param array $error Los mensajes capturados
 */
function mostrarError(array &$error):void
{
    foreach ($error as $v) {
        ?>
        <div class="row">
            <div class="alert alert-danger" role="alert">
                <?= h($v) ?>
            </div>
        </div>
        <?php
    }

    header('Location: index.php');
}

function insertar(PDO $pdo,array $valores):void
{
    $cols = array_keys($valores);
    $vals = array_fill(0, count($valores),'?');
    $sql = 'INSERT INTO peliculas ('.implode(', ',$cols).')'
                       .'VALUES ('. implode(', ', $vals).')';
    $sent = $pdo -> prepare($sql);
    $sent -> execute(array_values($valores));
}

function comp($valor)
 {
     return $valor !== '';
 }


function modificar(PDO $pdo,int $id,array $valores): void
{
    $sets = [];
    foreach ($valores as $k => $v) {
        $sets[] = $v === '' ? "$k = DEFAULT" : "$k = ?";
    }

    $set = implode(', ',$sets);
    $sql = "UPDATE peliculas
            SET $set
            WHERE id = ?";
    $exec = array_values(array_filter($valores, 'comp'));
    $exec[] = $id;
    $sent = $pdo -> prepare($sql);
    $sent -> execute($exec);
}

function formulario(array $datos, ?int $id): void
 {
     if ($id === null) {
         $destino = 'insertar.php';
         $boton = 'Insertar';
     } else {
         $destino = "modificar.php?id=$id";
         $boton = 'Modificar';
     }
     extract($datos);
     ?>
     <form action="<?= $destino ?>" method="post">
         <label for="titulo">Título*:</label>
         <input id="titulo" type="text" name="titulo"
             value="<?= h($titulo) ?>"><br>
         <label for="anyo">Año:</label>
         <input id="anyo" type="text" name="anyo"
             value="<?= h($anyo) ?>"><br>
         <label for="sinopsis">Sinopsis:</label>
         <textarea
             id="sinopsis"
             name="sinopsis"
             rows="8"
             cols="70"><?= h($sinopsis) ?></textarea><br>
         <label for="duracion">Duración:</label>
         <input id="duracion" type="text" name="duracion"
             value="<?= h($duracion) ?>"><br>
         <label for="genero_id">Género*:</label>
         <input id="genero_id" type="text" name="genero_id"
             value="<?= h($genero_id) ?>"><br>
         <input type="submit" value="<?= $boton ?>">
         <a href="index.php">Cancelar</a>
     </form>
     <?php
 }

 function recogerParametros()
 {
     global $titulo, $anyo, $sinopsis, $duracion, $genero_id;

     $titulo    = trim(filter_input(INPUT_POST, 'titulo'));
     $anyo      = trim(filter_input(INPUT_POST, 'anyo'));
     $sinopsis  = trim(filter_input(INPUT_POST, 'sinopsis'));
     $duracion  = trim(filter_input(INPUT_POST, 'duracion'));
     $genero_id = trim(filter_input(INPUT_POST, 'genero_id'));
 }

 function comprobarUsuario(string $usuario, array $error):void
 {
     if($usuario === ''){
         $error[] = 'El usuario es obligatorio';
         return;
     }
     if(mb_strlen($usuario) > 255){
         $error[] = 'El usuario es demasiado largo';
     }
     if(mb_strpos($usuario,' ') !== false){
         $error[] = 'El usuario no puede contener espacios';
     }
 }

 function comprobarPassword(string $password, array $error):void
 {
     if ($paspassword==='') {
         $error[] = 'La contraseña es obligatoria';
     }
 }

 function buscarUsuario(string $usuario,string $password,array $error):array
 {
     $pdo = conectar();
     $sent = $pdo -> prepare('SELECT *
                                FROM usuarios
                                WHERE usuario = :usuario');
    $sent->execute([':usuario' => $usuario]);
    $fila = $sent->fetch();

    if (empty($fila)) {
        $error = 'El usuario no existe';
        throw new Exception;

    }
    if(!password_verify($pasword,$fila['password'])){
        $error[] = 'La contraseña no coincide';
        throw new Exception;
    }
    return $fila;

 }
