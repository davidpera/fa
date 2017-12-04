<?php session_start() ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Confirmacion de borrado</title>
    </head>
    <body>
        <?php
        require 'auxiliar.php';

        if(!comprobarLogeado()){
            return;
        }

        /*try {
            if (!empty($_POST)){
                $id = filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT) ?? false;
            }else{
                $id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT) ?? false;
            }
            $error = [];
            comprobarParametro($id);
            $pdo = conectar();

            borrarPelicula($pdo,$id,$error);
            comprobarErrores($error);
            $_SESSION['mensaje'] = 'La pelicula se ha borrado correctamente';
            header('Location: index.php');
        } catch (Exception $e) {
            mostrarErrores($error);
        }*/


        if (!empty($_POST)){
            $id = filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT) ?? false;
            try {
                $error = [];
                comprobarParametro($id,$error);
                $pdo = conectar();
                borrarPelicula($pdo,$id,$error);
                comprobarErrores($error);
                $_SESSION['mensaje'] = 'La pelicula se ha borrado correctamente';
                header('Location: index.php');
            } catch (Exception $e) {
                mostrarErrores($error);
            }

        }else{
            $id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT) ?? false;
            try {
                $error = [];
                comprobarParametro($id,$error);
                $pdo = conectar();
                $fila = buscarPelicula($pdo,$id,$error);
                ?>
                <h3>
                    ¿Seguro que desea borrar la pelicula <?= $fila['titulo'] ?>?
                </h3>
                <form action="borrar.php" method="post">
                    <input type="hidden" name="id" value="<?= $id ?>"/>
                    <input type="submit" value="Si"/>
                    <input type="submit" value="No"
                        formaction="index.php" formmethod="get"/>
                </form>
                <?php
            }catch (Exception $e){
                mostrarErrores($error);
            }
        }
        ?>
    </body>
</html>
