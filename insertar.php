<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Insertar una nueva pelicula</title>
    </head>
    <body>
        <?php
        $titulo = trim(filter_input(INPUT_POST,'titulo')) ?? '';
        $anyo = trim(filter_input(INPUT_POST,'anyo')) ?? '';
        $sinopsis = trim(filter_input(INPUT_POST,'sinopsis')) ?? '';
        $duracion = trim(filter_input(INPUT_POST,'duracion')) ?? '';
        $genero_id = trim(filter_input(INPUT_POST,'genero_id')) ?? '';
        if (!empty($_POST)):
            try {
                $error = [];
                comprobarTitulo($titulo, $error);
                comprobarAnyo($anyo, $error);
                comprobarDuracion($diracion, $error);
                comprobarGenero($genero_id, $error);
                comprobarErrores($error);
            } catch (Exception $e) {
                mostrarError($e);
            }

        endif
        ?>
        <form action="insertar.php" method="post">
            <label for="titulo">Titulo*:</label>
            <input type="text" id="titulo" name="titulo"
                value="<?= htmlspecialchars($titulo) ?>"/><br/>
            <label for="anyo">Año:</label>
            <input type="text" id="anyo" name="anyo"
                value="<?= htmlspecialchars($anyo) ?>"/><br/>
            <label for="sinopsis">Sinopsis:</label>
            <textarea id="sinopsis"
                    name="sinopsis"
                    rows="8"
                    cols="70"><?= htmlspecialchars($sinopsis) ?></textarea><br/>
            <label for="duracion">Duracion:</label>
            <input type="text" id="duracion" name="duracion"
                value="<?= htmlspecialchars($duracion) ?>"/><br/>
            <label for="genero_id">Genero*:</label>
            <input type="text" id="genero_id" name="genero_id"
                value="<?= htmlspecialchars($genero_id) ?>"/><br/>
            <input type="submit" value="Insertar"/>
            <input type="submit" value="Cancelar"
                formaction="index.php" formmethod="get"/>

        </form>
    </body>
</html>
