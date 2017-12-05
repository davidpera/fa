
        <?php
        require 'auxiliar.php';

        cabecera('login');

        $usuario = trim(filter_input(INPUT_POST,'usuario'));
        $password = trim(filter_input(INPUT_POST,'password'));
        if (!empty($_POST)) {
            $error = [];
            try {
                comprobarUsuario($usuario,$error);
                comprobarPassword($password,$error);
                $fila = buscarUsuario($usuario,$password,$error);
                comprobarErrores($error);
                $_SESSION['usuario']= ["id" => $fila['id'],
                                        'nombre' => $fila['usuario'],];
                header('location: index.php');
            } catch (Exception $e) {
                mostrarErrores($error);
            }

        }
        ?>

            <div class="row">
                <div class="col-md-offset-4 col-md-4">
                    <form action="login.php" method="post">
                      <div class="form-group">
                        <label for="usuario">Usuario*</label>
                        <input type="text" class="form-control" id="usuario"
                        placeholder="Nombre de usuario" name="usuario" value="<?= h($usuario) ?>">
                      </div>
                      <div class="form-group">
                        <label for="password">Password*</label>
                        <input type="password" class="form-control" id="password"
                        placeholder="Contraseña" name="password">
                      </div>
                      <div class="checkbox">
                        <label>
                          <input type="checkbox">
                          Recuerdame
                        </label>
                      </div>
                      <button type="submit" class="btn btn-default">Login</button>
                    </form>

                </div>
            </div>
        <?php pie();
