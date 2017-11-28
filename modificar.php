<?php session_start() ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <title>Modificar una pelicula</title>
    </head>
    <body>
        <?php
        require 'auxiliar.php';

        if(!comprobarLogeado()){
            return;
        }
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        try {
            $error = [];
            comprobarParametro($id,$error);
            $pdo = conectar();
            $fila = buscarPelicula($pdo,$id,$error);
            comprobarErrores($error);
            extract($fila);

            if (!empty($_POST)):
                $titulo = trim(filter_input(INPUT_POST,'titulo'));
                $anyo = trim(filter_input(INPUT_POST,'anyo'));
                $sinopsis = trim(filter_input(INPUT_POST,'sinopsis'));
                $duracion = trim(filter_input(INPUT_POST,'duracion'));
                $genero_id = trim(filter_input(INPUT_POST,'genero_id'));
                try {
                    $error = [];
                    comprobarTitulo($titulo, $error);
                    comprobarAnyo($anyo, $error);
                    comprobarDuracion($duracion, $error);
                    comprobarGenero($pdo, $genero_id, $error);
                    comprobarErrores($error);
                    $valores = compact(
                        'titulo',
                        'anyo',
                        'sinopsis',
                        'duracion',
                        'genero_id'
                    );
                    modificar($pdo,$id,$valores);
                    $_SESSION['mensaje'] = 'La pelicula se ha modificado correctamente';
                    header('Location: index.php');
                    return;
                } catch (Exception $e) {
                    mostrarErrores($error);
                }

            endif;
            formulario(compact(
                'titulo',
                'anyo',
                'sinopsis',
                'duracion',
                'genero_id'
            ), $id);
        }catch (Exception $e){
            mostrarError($error);
        }
        ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    </body>
</html>
