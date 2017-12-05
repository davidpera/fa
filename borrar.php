
        <?php
        require 'auxiliar.php';

        cabecera('borrar pelicula');

        if(!comprobarLogeado()){
            return;
        }

        try {
            $id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT) ?? false;
            $error = [];
            comprobarParametro($id,$error);
            $pdo = conectar();
            $fila = buscarPelicula($pdo, $id, $error);
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                borrarPelicula($pdo,$id,$error);
                comprobarErrores($error);
                $_SESSION['mensaje'] = 'La pelicula se ha borrado correctamente';
                header('Location: index.php');
                return;
            }
            ?>
            <div class="row">
                <div class="col-md-offset-3 col-md-6">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            ¿Seguro que desea borrar la pelicula <?= $fila['titulo'] ?>?
                        </div>
                        <div class="panel-body">
                            <form action="borrar.php?id=<?= $id ?>" method="post">
                                <input type="hidden" name="id" value="<?= $id ?>"/>
                                <input class="btn btn-success" type="submit" value="Si"/>
                                <a class="btn btn-default" href="index.php">No</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } catch (Exception $e) {
            mostrarErrores($error);
        }




        /*if (!empty($_POST)){
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
                <form action="borrar.php?id=<?= $id ?>" method="post">
                    <input type="hidden" name="id" value="<?= $id ?>"/>
                    <input lass="btn btn-succes" type="submit" value="Si"/>
                    <a class="btn btn-default" href="index.php">No</a>
                </form>
                <?php
            }catch (Exception $e){
                mostrarErrores($error);
            }
        }*/
        pie();
