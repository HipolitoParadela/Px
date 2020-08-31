<?php
// CABECERA
include "header.php";
/// NAVEGADOR SIDEBAR
if ($this->session->userdata('Rol_id') == 4) {
    include "navegadores/nav-bar-rol-4.php";
} elseif ($this->session->userdata('Rol_id') == 3) {
    include "navegadores/nav-bar-rol-3.php";
} elseif ($this->session->userdata('Rol_id') == 2) {
    include "navegadores/nav-bar-rol-2.php";
}
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
                                <h1>Delivery, <span>Listado de Deliverys en proceso</span></h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Sistema restaurant</a></li>
                                    <li class="breadcrumb-item active">Delivery</li>
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
                            <a href="#modalUsuarios" data-toggle="modal" title="Nuevo item" class="btn btn-dark btn-flat btn-addon m-b-10 m-l-5" v-on:click="limpiarFormularioUsuarios()"><i class="ti-plus"></i> Nuevo repartidor</a>
                            <a href="#modalNuevoDelivery" data-toggle="modal" title="Nuevo delivery" class="btn btn-success btn-flat btn-addon m-b-10 m-l-5" v-on:click="limpiarFormularioDelivery()"><i class="ti-plus"></i> Nuevo pedido</a>

                            <!-- <a href="#" data-toggle="modal" class="btn btn-danger btn-flat btn-addon m-b-10 m-l-5" v-on:click="actualizarListadoDelivery()"><i class="ti-reload"></i> Actualizar</a> -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3" v-for="delivery in listaDeliverys">
                            <div class="card">
                                <a v-bind:href="'delivery/?Id='+delivery.Datos_delivery.Id">
                                    <div class="stat-widget-two">
                                        <div class="stat-content">
                                            <div class="stat-digit"><i class="ti-user"></i><br> {{delivery.Datos_delivery.Nombre_cliente}}</div>
                                            <p class="text-primary" align="center">Dirección: {{delivery.Datos_delivery.Direccion}}</br>
                                                Teléfono: {{delivery.Datos_delivery.Telefono}}</p>
                                            <p align="center"> <i class="ti-alarm-clock"></i> {{formatoFecha_hora(delivery.Datos_delivery.FechaHora_pedido)}}</p>
                                            <p align="center"> <i class="ti-car"></i> {{delivery.Datos_delivery.Nombre_repartidor}}</p>

                                            <div class="stat-text text-danger">Items pedidos</div>
                                            <ul>
                                                <li v-for="item in delivery.Datos_items">{{item.Nombre_item}}</li>
                                            </ul>
                                        </div>
                                        <div class="progress">
                                            <div v-if="delivery.Alerta == 0" class="progress-bar progress-bar-success w-100" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                            <div v-if="delivery.Alerta == 1" class="progress-bar progress-bar-warning w-100" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                            <div v-if="delivery.Alerta == 2" class="progress-bar progress-bar-danger w-100" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <p align="center">
                                <a href="#modalNuevoDelivery" data-toggle="modal" title="Nuevo delivery" v-on:click="editDelivery(delivery.Datos_delivery)"><i class="ti-pencil-alt"></i></a>
                            </p>
                        </div>
                    </div>
                    <!-- /# row -->
                    <div class="row">
                        <div class="col-lg-12">
                            <p align="center"><a class="btn btn-info" href="../restaurant/resumendelivery"> Ver Deliverys cerrados</a></p>
                            <!-- /# column -->
                        </div>
                        <!-- /# row -->
                </section>
            </div>
            <!-- Modal ITEMS CARTA -->
            <div class="modal fade" id="modalNuevoDelivery" tabindex="-1" role="dialog" aria-labelledby="modalItemsCartaTitle" aria-hidden="true">
                <div class="modal-dialog  modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalItemsCartaTitle">{{texto_boton}} Delivery</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="horizontal-form">
                                <form class="form-horizontal" action="post" v-on:submit.prevent="crearDelivery()">
                                    <!-- <div class="form-group">
                                        <label class="col-sm-2 control-label">Delivery *</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" v-model="delivery.Repartidor_id">
                                                <option value="0">Retira cliente</option>
                                                <option v-for="repartidor in listaUsuariosRepartidores" v-bind:value="repartidor.Id">{{repartidor.Nombre}}</option>
                                            </select>
                                        </div>
                                    </div> -->
                                    <div class="form-group">
                                        <label class="col-sm-12 control-label">Nombre del cliente *<span style="font-size:12px"></span></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" placeholder="" v-model="delivery.Nombre_cliente" :disabled="delivery.Repartidor_id == null">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Dirección y localidad</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" placeholder="" v-model="delivery.Direccion" :disabled="delivery.Nombre_cliente == null">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Teléfono</label>
                                        <div class="col-sm-10">
                                            <input type="number" class="form-control" placeholder="" v-model="delivery.Telefono" :disabled="delivery.Nombre_cliente == null">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-4 col-sm-10">
                                            <a v-if="delivery.Id" v-bind:href="'delivery/?Id='+delivery.Id" class="btn btn-info">Administrar Delivery</a>
                                            <button type="submit" class="btn btn-success" :disabled="delivery.Nombre_cliente == null">{{texto_boton}}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal -->
            <!-- Modal Usuarios-->
            <div class="modal fade" id="modalUsuarios" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="true">
                <div class="modal-dialog  modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalItemsCartaTitle">Cargar nuevo repartidor</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="horizontal-form">
                                <form class="form-horizontal" action="post" v-on:submit.prevent="crearUsuarios()">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label class="control-label">Nombre del repartidor *</label> <input type="text" class="form-control" v-model="usuario.Nombre">
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label class="control-label">DNI</label>
                                                <input type="number" class="form-control" placeholder="" v-model="usuario.DNI">
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="control-label">Telefono</label>
                                                <input type="text" class="form-control" placeholder="" v-model="usuario.Telefono">
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="col-sm-6">
                                                <label class="control-label">Fecha ingreso a la empresa</label>
                                                <input type="date" class="form-control" placeholder="" v-model="usuario.Fecha_alta">
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="control-label">Rol *</label>
                                                <select class="form-control" v-model="usuario.Rol_id" :disabled="usuario.Nombre == null">
                                                    <option value="5" selected>Delivery</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <label class="control-label">Observaciones</label>
                                                <textarea class="form-control" rows="5" placeholder="" v-model="usuario.Observaciones"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-offset-2 col-sm-12">
                                            <button type="submit" class="btn btn-success" :disabled="usuario.Rol_id == null">{{texto_boton}}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal -->
        </div>
        <?php /// FOOTER
        include "footer.php";
        ?>