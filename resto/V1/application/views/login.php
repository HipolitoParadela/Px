<?php
// CABECERA
include "header.php"; ?>
<script src="https://www.google.com/recaptcha/api.js"></script>

<body class="bg-primary" style="background-size: 100%; background-image: url('<?php echo base_url(); ?>uploads/fondo.jpg');">

    <div class="unix-login">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="login-content">
                        <!-- <div class="login-logo">
                            <a href="#">
                                <img src="<?php echo base_url(); ?>uploads/logo.jpeg" alt="">
                               <span>Demo</span> PX Resto
                            </a>

                        </div> -->
                        <p align="center">
                            <img src="http://pxsistemas.com/resto/logo_fondos/logo_pxresto.png" width="300"><br>
                            <a href="http://pxsistemas.com">Un software en la nube de www.pxsistemas.com</a>
                        </p>
                        <div class="login-form">
                            <!--<p align="center"><!-- <img src="<?php echo base_url(); ?>uploads/logo.jpeg" width="250">  DEMO</p>-->
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
                            <form action="login/iniciar_session" method="post" role="form" id="login">
                                <div class="form-group">
                                    <label>DNI </label>
                                    <input id="dni" name="dni" type="number" class="form-control" placeholder="" required><br>

                                </div>
                                <div class="form-group">
                                    <label>Contraseña</label>
                                    <input id="Pass" name="Pass" type="password" class="form-control" placeholder="" required><br>

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
                                <div id='recaptcha' class="g-recaptcha" data-sitekey="6LfYusgZAAAAAMryqRouLLBIREvqqM9S0ILRHZzs" data-callback="onCompleted" data-size="invisible"></div>

                                <script>
                                    $('#myForm').submit(function(event) {
                                        console.log('validation completed.');

                                        event.preventDefault(); //prevent form submit before captcha is completed
                                        grecaptcha.execute();
                                    });

                                    onCompleted = function() {
                                        document.getElementById("myForm").submit();
                                        console.log('captcha completed.');
                                    }
                                </script>
                            </form>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

</body>

</html>