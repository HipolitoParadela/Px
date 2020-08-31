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
                                <h1>Comandas, <span>Listado de comandas en proceso</span></h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Sistema restaurant</a></li>
                                    <li class="breadcrumb-item active">Comandas </li>
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
                            <a href="#" data-toggle="modal" class="btn btn-danger btn-flat btn-addon m-b-10 m-l-5" v-on:click="getListadoCocina()"><i class="ti-reload"></i> Actualizar</a>
                        </div>
                        <div class="col-lg-3">Comandas en proceso: <b>{{listaComandas.Cantidad}}</b></div>
                        <div class="col-lg-3">Deliverys en proceso: <b>{{listaDeliverys.Cantidad}}</b></div>
                    </div>

                    <div class="row">

                        <div v-if="listaComandas.Cantidad > 0" v-bind:class="classComandas">
                            <h4>Comandas</h4>
                            <div class="row">
                                <div class="col-lg-4" v-for="comanda in listaComandas.Datos">
                                        <div class="card">
                                            <div class="card-title">
                                                <h4>Mesa {{comanda.Info_comanda.Identificador}}</h4>
                                                <p style="font-size:12px"> Pedido realizado: {{ formatoHora(comanda.Info_comanda.Hora_llegada) }}</p>
                                            </div>
                                            <div class="todo-list">
                                                <div class="tdl-holder">
                                                    <div class="tdl-content">
                                                        <ul>
                                                            <li v-for="item in comanda.Datos_items">
                                                                <label><input type="checkbox"><i></i><span>{{item.Nombre}}</span><a href='#' class="ti-close"></a></label>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="progress">
                                                <div v-if="comanda.Alerta == 0" class="progress-bar progress-bar-success w-100" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                                <div v-if="comanda.Alerta == 1" class="progress-bar progress-bar-warning w-100" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                                <div v-if="comanda.Alerta == 2" class="progress-bar progress-bar-danger w-100" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                            
                        </div> <!-- /# columna editable de comandas-->
                        
                        <div v-if="listaDeliverys.Cantidad > 0" v-bind:class="classDeliverys">
                            <h4>Delivery</h4>
                            <div class="row">    
                                <div class="col-lg-4" v-for="delivery in listaDeliverys.Datos">
                                    <div class="card">
                                        <div class="card-title">
                                            <h4>Cliente: {{delivery.Info_delivery.Nombre_cliente}}</h4>
                                            <p style="font-size:12px"> Pedido realizado: {{ formatoFecha_hora(delivery.Info_delivery.FechaHora_pedido) }}</p>
                                        </div>
                                        <div class="todo-list">
                                            <div class="tdl-holder">
                                                <div class="tdl-content">
                                                        <ul>
                                                            <li v-for="item in delivery.Datos_items">
                                                                <label><input type="checkbox"><i></i><span>{{item.Nombre_item}}</span><a href='#' class="ti-close"></a></label>
                                                            </li>
                                                        </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="progress">
                                            <div v-if="delivery.Alerta == 0" class="progress-bar progress-bar-success w-100" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                            <div v-if="delivery.Alerta == 1" class="progress-bar progress-bar-warning w-100" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                            <div v-if="delivery.Alerta == 2" class="progress-bar progress-bar-danger w-100" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- /# columna editable de deliverys-->

                    
                    </div>    <!-- /# row --> 
                </section>
            </div>
        </div>
    <?php /// FOOTER
include "footer.php";
?>
    




