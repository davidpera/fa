<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Modificar una pelicula</title>
    </head>
    <body>
        <?php
        require 'auxiliar.php';

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
                    $valores = array_filter(compact(
                        'titulo',
                        'anyo',
                        'sinopsis',
                        'duracion',
                        'genero_id'
                    ));
                    modificar($pdo,$id,$valores);
                    ?>
                    <h3>La pelicula se ha modificado correctamente</h3>
                    <?php
                    volver();
                } catch (Exception $e) {
                    mostrarError($error);
                }

            endif;

            if(empty($_POST) || (!empty($_POST) && !empty($error))):
            ?>
                <form action="modificar.php?id=<?= $id ?>" method="post">
                    <label for="titulo">Titulo*:</label>
                    <input type="text" id="titulo" name="titulo"
                        value="<?= h($titulo) ?>"/><br/>
                    <label for="anyo">Año:</label>
                    <input type="text" id="anyo" name="anyo"
                        value="<?= h($anyo) ?>"/><br/>
                    <label for="sinopsis">Sinopsis:</label>
                    <textarea id="sinopsis"
                            name="sinopsis"
                            rows="8"
                            cols="70"><?= h($sinopsis) ?></textarea><br/>
                    <label for="duracion">Duracion:</label>
                    <input type="text" id="duracion" name="duracion"
                        value="<?= h($duracion) ?>"/><br/>
                    <label for="genero_id">Genero*:</label>
                    <input type="text" id="genero_id" name="genero_id"
                        value="<?= h($genero_id) ?>"/><br/>
                    <input type="submit" value="Modificar"/>
                    <a href="index.php">Cancelar</a>

                </form>
            <?php
            endif;
        }catch (Exception $e){
            mostrarError($error);
        }
        ?>
    </body>
</html>
