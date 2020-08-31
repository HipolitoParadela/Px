<?php 
// CABECERA
include "header.php";
/// NAVEGADOR SIDEBAR
if($this->session->userdata('Rol_id') == 4) { include "navegadores/nav-bar-rol-4.php"; }
elseif($this->session->userdata('Rol_id') == 3) { include "navegadores/nav-bar-rol-3.php"; }
elseif($this->session->userdata('Rol_id') == 2) { include "navegadores/nav-bar-rol-2.php"; }
/// CABECERA BODY
include "header-body.php";
?>

<body>
     <div class="content-wrap" id="app">
        <div class="main">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-lg-8 p-r-0 title-margin-right">
                        <div class="page-header">
                            <div class="page-title">
                                <h1>Px Resto, <span>Acceder al plan siguiente</span></h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Px Resto</a></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->
               
                <section id="main-content">
                     <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <h1>Esta función pertenece al Plan Medio</h1>
                                <p>Para poder aproverchar estas funciones deberás suscribirte al plan Medio</p>
                                <p>
                                    <a mp-mode="dftl" href="https://www.mercadopago.com/mla/checkout/start?pref_id=46657315-12fc58f8-c4fc-4a7a-89b6-f85b414572d6" name="MP-payButton" class='orange-ar-l-sq-arall'>Contratar Plan Medio</a>
            <script type="text/javascript">
            (function(){function $MPC_load(){window.$MPC_loaded !== true && (function(){var s = document.createElement("script");s.type = "text/javascript";s.async = true;s.src = document.location.protocol+"//secure.mlstatic.com/mptools/render.js";var x = document.getElementsByTagName('script')[0];x.parentNode.insertBefore(s, x);window.$MPC_loaded = true;})();}window.$MPC_loaded !== true ? (window.attachEvent ?window.attachEvent('onload', $MPC_load) : window.addEventListener('load', $MPC_load, false)) : null;})();
            </script>
                                </p>
                                <h4>Una vez suscripto obtendras las siguientes funciones</h4>
                                <ul>
                                    <li>Ficha completa con datos de cada uno de tus empleados</li>
                                    <li>Control de sueldos</li>
                                    <li>Formaciones académicas y técnicas de cada empleado</li>
                                    <li>Un calendario de avanzada mantener un control de la agenda de tu local</li>
                                </ul>
                                <!-- /# row -->
                            </div>
                        </div>
                </section>
            </div>
            
        </div>
    <?php /// FOOTER
include "footer.php";
?>
    




