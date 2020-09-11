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
                                <h1>Usuarios, <span>Listado</span></h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Sistema restaurant</a></li>
                                    <li class="breadcrumb-item active">Usuarios</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->
               
                <section id="main-content">
                     
                    <!-- /# row -->
                </section>
            </div>
            
        </div>
    <?php /// FOOTER
include "footer.php";
?>
    




