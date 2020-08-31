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
                                <h1>Planificaciones, <span>Calendario</span></h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Sistema restaurant</a></li>
                                    <li class="breadcrumb-item active">Planificaciones</li>
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
                        <div class="card-title">
                            <h4>Calendario</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                            <!--<div class="col-lg-3">
                                <a href="#" data-toggle="modal" data-target="#add-category" class="btn btn-lg btn-success btn-block waves-effect waves-light">
                                                        <i class="fa fa-plus"></i> Crear nuevo
                                                    </a>
                                <div id="external-events" class="m-t-20">
                                <br>
                                <h4>Eventos predeterminados</h4>
                                <p>Toma y suelta sobre la fecha</p>
                                <div class="external-event bg-primary" data-class="bg-primary">
                                    <i class="fa fa-move"></i>New Theme Release
                                </div>
                                <div class="external-event bg-pink" data-class="bg-warning">
                                    <i class="fa fa-move"></i>My Event
                                </div>
                                <div class="external-event bg-warning" data-class="bg-danger">
                                    <i class="fa fa-move"></i>Día no laborable
                                </div>
                                <div class="external-event bg-dark" data-class="bg-dark">
                                    <i class="fa fa-move"></i>Create New theme
                                </div>
                                </div>

                                
                                <div class="checkbox m-t-40">
                                <input id="drop-remove" type="checkbox">
                                <label for="drop-remove">
                                                            Quitar luego de mover
                                                        </label>
                                </div>

                            </div> -->
                            <div class="col-md-12">
                                <div class="card-box">
                                    <div class="calendario" id="calendar"></div>
                                </div>
                            </div>
                            <!-- end col -->
                            <!-- BEGIN MODAL -->
                            <div class="modal fade none-border" id="event-modal">
                                <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title"><strong>Añadir nuevo asunto o evento</strong></h4>
                                    </div>
                                    <div class="modal-body"></div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                                    <button type="button" class="btn btn-success save-event waves-effect waves-light">Agendar</button>
                                    <button type="button" class="btn btn-danger delete-event waves-effect waves-light" data-dismiss="modal">Borrar</button>
                                    </div>
                                </div>
                                </div>
                            </div>

                            <!-- Modal Add Category -->
                            <div class="modal fade none-border" id="add-category">
                                <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title"><strong>Add a category </strong></h4>
                                    </div>
                                    <div class="modal-body">
                                    <form>
                                        <div class="row">
                                        <div class="col-md-6">
                                            <label class="control-label">Category Name</label>
                                            <input class="form-control form-white" placeholder="Enter name" type="text" name="category-name" />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label">Choose Category Color</label>
                                            <select class="form-control form-white" data-placeholder="Choose a color..." name="category-color">
                                                                        <option value="success">Success</option>
                                                                        <option value="danger">Danger</option>
                                                                        <option value="info">Info</option>
                                                                        <option value="pink">Pink</option>
                                                                        <option value="primary">Primary</option>
                                                                        <option value="warning">Warning</option>
                                                                    </select>
                                        </div>
                                        </div>
                                    </form>
                                    </div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-danger waves-effect waves-light save-category" data-dismiss="modal">Save</button>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <!-- END MODAL -->
                            </div>
                        </div>
                        </div>
                        <!-- /# card -->
                    </div>
                    <!-- /# column -->
                    

            </div>
        </div>
        
    <?php /// FOOTER
include "footer.php";
?>
    




