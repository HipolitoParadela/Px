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
                            <img src="http://pxsistemas.com/pxresto/logo_fondos/logo_pxresto.png" width="300">
                        </p>
                        
                        <div class="login-form">
                            <!--<p align="center"><!-- <img src="<?php echo base_url(); ?>uploads/logo.jpeg" width="250">  DEMO</p>--> <br>
                            <h2>Completa el formulario para comenzar a utilizar PX Resto Gratis</h2>
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
                            </p><br>
                            <form action="registro" method="post" role="form" id="login">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Nombre del local</label>
                                            <input id="Nombre_negocio" name="Nombre_negocio" type="text" class="form-control" placeholder="" required><br>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Tipo de local gastrónomico</label>
                                            <input id="Tipo_negocio" name="Tipo_negocio" type="text" class="form-control" placeholder="" required><br>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Teléfono suyo o del local</label>
                                            <input id="Telefono_1" name="Telefono_1" type="tel" class="form-control" placeholder="" required><br>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Dirección, localidad y provincia</label>
                                            <input id="Direccion" name="Direccion" type="text" class="form-control" placeholder="" required><br>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input id="Email" name="Email" type="email" class="form-control" placeholder="" required><br>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <p>Enviaremos sus datos de acceso al email proporcionado.</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Su nombre y apellido</label>
                                            <input id="Nombre_responsable" name="Nombre_responsable" type="text" class="form-control" placeholder="" required><br>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Su DNI </label>
                                            <input id="dni" name="dni" type="number" class="form-control" placeholder="" required><br>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Contraseña</label>
                                            <input id="Pass" name="Pass" type="password" class="form-control" placeholder="" required><br>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Repetir contraseña contraseña</label>
                                            <input id="confirm_Pass" name="confirm_Pass" type="password" class="form-control" placeholder="" required><br>
                                        </div>
                                        <script>
                                            var password = document.getElementById("Pass"),
                                                confirm_password = document.getElementById("confirm_Pass");

                                            function validatePassword() {
                                                if (password.value != confirm_password.value) {
                                                    confirm_password.setCustomValidity("Las contraseñas no coinciden");
                                                } else {
                                                    confirm_password.setCustomValidity('');
                                                }
                                            }

                                            password.onchange = validatePassword;
                                            confirm_password.onkeyup = validatePassword;
                                        </script>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30">Registrarme</button>
                                <!-- <div class="checkbox">
                                    <label>
										<input type="checkbox"> Remember Me
									</label>
                                    <label class="pull-right">
										<a href="#">Forgotten Password?</a>
									</label>
                                </div> -->


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
                            <p align="right">
                            <a href="http://pxsistemas.com">Un software en la nube de www.pxsistemas.com</a>
                        </p>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

</body>

</html>