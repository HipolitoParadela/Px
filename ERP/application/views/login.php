<?php
// CABECERA
include "head.php";
?>

<body class="animsition">
    <div class="page-wrapper">
        <div class="page-content--bge5" style="background-image: url('http://pxsistemas.com/ERP/uploads/imagenes/fondo_login.jpeg'); background-size: cover;">
            <div class="container">
                <div class="login-wrap">
                    <div class="login-content">
                        <div class="login-logo">
                            <a href="#">
                                <h1 align="center"> DEMO </h1>
                            </a>
                        </div>
                        <div class="login-form">
                            <form action="login/iniciar_session" method="post" role="form">
                                <p align="center" class="text-danger">
                                    <?php
                                        if(isset($_GET["Error"]))
                                        {
                                            if($_GET["Error"] == 1) { echo "Contraseña erronea.";}
                                            elseif($_GET["Error"] == 2) { echo "Usuario no registrado.";}
                                            elseif($_GET["Error"] == 3) { echo "Solicite permiso al administrador para poder acceder.";}
                                        }
                                        
                                    ?>
                                </p>
                                <div class="form-group">
                                    <label>DNI <em>(12345678) </em></label>
                                    <input id="dni" name="dni" type="number" value="" class="form-control" placeholder="" required>
                                </div>
                                <div class="form-group">
                                    <label>Contraseña  <em>(123456)</em></label>
                                    <input id="Pass" name="Pass" type="password" value="" class="form-control" placeholder="" required>
                                </div>
                                <!-- <div class="login-checkbox">
                                    <label>
                                        <input type="checkbox" name="remember">Remember Me
                                    </label>
                                    <label>
                                        <a href="#">Forgotten Password?</a>
                                    </label>
                                </div> -->
                                <button class="au-btn au-btn--block au-btn--green m-b-20" type="submit">Ingresar</button>
                                <!--<div class="social-login-content">
                                    <div class="social-button">
                                        <button class="au-btn au-btn--block au-btn--blue m-b-20">sign in with facebook</button>
                                        <button class="au-btn au-btn--block au-btn--blue2">sign in with twitter</button>
                                    </div>
                                </div>-->
                            </form>
                            <!--<div class="register-link">
                                <p>
                                    Don't you have account?
                                    <a href="#">Sign Up Here</a>
                                </p>
                            </div>-->
                        </div>
                        <hr>
                        <p align="center"><img src="https://grupopignatta.com.ar/ERP/uploads/logoazul.png" width="150px"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
// CABECERA
include "footer.php"; ?>
</body>
</html>
<!-- end document-->