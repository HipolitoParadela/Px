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
                                <h1>Control de presencia, <span>Listado</span></h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Sistema restaurant</a></li>
                                    <li class="breadcrumb-item active">Control de presencia</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->
               
                <section id="main-content">
                     <div class="row">
                        <div class="col-lg-6">
                        <!-- /# card -->
                            <div class="card">
                                <div class="bootstrap-data-table-panel">
                                <h4>En jornada laboral</h4>
                                <p>Los usuarios de esta lista tienen permiso para acceder al sistema.</p>
                                    <div class="table-responsive">
                                        <div class="modal-body">
                                            <div class="horizontal-form">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th>Nombre</th>
                                                            <th>Rol</th>
                                                            <!-- <th>Hora Ingreso</th> -->
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="usuario in listaControlPresencia.Presentes">
                                                            <td>
                                                                <div class="round-img">
                                                                    <img v-if="usuario.Imagen != null"  v-bind:src="'<?php echo base_url(); ?>/pxresto/uploads/imagenes/'+usuario.Imagen" alt="">
                                                                    <!-- <img v-else  src="http://c1260237.ferozo.com/pxresto/uploads/imagenes/addimagen.jpg" alt=""> -->
                                                                </div>
                                                            </td>
                                                            <td>{{usuario.Nombre}}</td>
                                                            <td>{{usuario.Nombre_rol}}</td>
                                                            <!-- <td>{{usuario.Fecha_hora}}</td> -->
                                                            <td>
                                                                <a href="#" v-on:click="jornada(usuario.Id, 0)" tittle="Habilitar"><span class="badge badge-warning">Finalizar acceso al sistema para esta jornada</span></a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /# card -->
                        </div>
                        <!-- /# column -->
                        <div class="col-lg-6">
                        <!-- /# card -->
                            <div class="card">
                                <div class="bootstrap-data-table-panel">
                                    <h4>Fuera de jornada laboral</h4>
                                    <p>Los usuarios de esta lista, no tienen permiso para acceder al sistema.</p>
                                    <div class="table-responsive">
                                        <div class="modal-body">
                                            <div class="horizontal-form">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th></th>   
                                                            <th>Nombre</th>
                                                            <th>Rol</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="usuario in listaControlPresencia.NoPresentes">
                                                            <td>
                                                                <a href="#" v-on:click="jornada(usuario.Id, 1)" tittle="Desabilitar"><span class="badge badge-success">Permitir acceso al sistema para esta jornada</span></a>
                                                            </td>
                                                            <td>
                                                                <div class="round-img">
                                                                    <img v-if="usuario.Imagen != null"  v-bind:src="'<?php echo base_url(); ?>/pxresto/uploads/imagenes/'+usuario.Imagen" alt="">
                                                                    <!-- <img v-else  src="http://c1260237.ferozo.com/pxresto/uploads/imagenes/addimagen.jpg" alt=""> -->
                                                                </div>
                                                            </td>
                                                            <td>{{usuario.Nombre}}</td>
                                                            <td>{{usuario.Nombre_rol}}</td>
                                                            
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /# card -->
                        </div>
                        <!-- /# column -->
                    </div>
                    <!-- /# row -->
                </section>
            </div>
            
        </div>
    <?php /// CABECERA BODY
include "footer.php";
?>
    




