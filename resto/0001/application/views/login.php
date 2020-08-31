<?php
// CABECERA
include "header.php"; ?>

<body class="bg-primary" style="background-image: url('http://pxsistemas.com/resto/logo_fondos/maxresdefault.jpg');">

    <div class="unix-login">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="login-content">
                        <div class="login-logo">
                            <a href="#">
                                <img src="http://pxsistemas.com/resto/logo_fondos/ParrillaAcaNomasLogo.png" alt=""><br>
                                <span>Acá Nomás</span> Parrilla Resto
                            </a>

                        </div>
                        <div class="login-form">
                            <h4>Inicio de sesión</h4>
                            <p align="center" class="text-danger">
                                <?php
                                if (isset($_GET["Error"])) {
                                    if ($_GET["Error"] == 1) {
                                        echo "Contraseña erronea.";
                                    } elseif ($_GET["Error"] == 2) {
                                        echo "Usuario no registrado.";
                                    } elseif ($_GET["Error"] == 3) {
                                        echo "Solicite permiso al administrador para poder acceder.";
                                    }
                                }

                                ?>
                            </p>
                            <form action="login/iniciar_session" method="post" role="form">
                                <div class="form-group">
                                    <label>DNI </label>
                                    <input id="dni" name="dni" type="number" class="form-control" placeholder="" required>
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input id="Pass" name="Pass" type="password" class="form-control" placeholder="" required>
                                </div>
                                <!-- <div class="checkbox">
                                    <label>
										<input type="checkbox"> Remember Me
									</label>
                                    <label class="pull-right">
										<a href="#">Forgotten Password?</a>
									</label>
                                </div> -->

                                <button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30">Iniciar sesión</button>
                                <!-- <div class="social-login-content">
                                    <div class="social-button">
                                        <button type="button" class="btn btn-primary bg-facebook btn-flat btn-addon m-b-10"><i class="ti-facebook"></i>Sign in with facebook</button>
                                        <button type="button" class="btn btn-primary bg-twitter btn-flat btn-addon m-t-10"><i class="ti-twitter"></i>Sign in with twitter</button>
                                    </div>
                                </div>
                                <div class="register-link m-t-15 text-center">
                                    <p>Don't have account ? <a href="#"> Sign Up Here</a></p>
                                </div>-->
                            </form>

                        </div>
                    </div>
                    <p align="center">
                        <img src="http://pxsistemas.com/resto/logo_fondos/logo_pxresto.png" alt="">
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>

</html>