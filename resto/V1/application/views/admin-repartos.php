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
} elseif ($this->session->userdata('Rol_id') == 5) {
    include "navegadores/nav-bar-rol-delivery.php";
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
                                <h1>Delivery, <span>Listado de repartos a realizar</span></h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Repartos</a></li>
                                    <li class="breadcrumb-item active">Delivery</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->

                <section id="main-content" v-if="Tipo_suscripcion > 1">
                    <div class="col-lg-6">
                        <a href="#" data-toggle="modal" class="btn btn-danger btn-flat btn-addon m-b-10 m-l-5" v-on:click="obtener_repartos()"><i class="ti-reload"></i> Actualizar</a>
                    </div>
                    <h4>Comandas a entregar a cliente ({{listaRepartos.Deliverys_tomados.length}})</h4>
                    <div class="row">

                        <div class="col-lg-3" v-for="reparto in listaRepartos.Deliverys_tomados">
                            <div class="card">
                                <div class="stat-widget-two">
                                    <div class="stat-content">
                                        <div class="stat-digit">
                                            <!-- <i class="ti-user"></i> --><br> {{reparto.Datos_reparto.Nombre_cliente}}</div>
                                        <h5>
                                            <!-- <i class="fas fa-location-arrow"></i><span class="text-success"> --> {{reparto.Datos_reparto.Direccion}}<!-- </span> --></br>
                                            <!-- <i class="fas fa-phone"></i>--> <span class="text-success"> {{reparto.Datos_reparto.Telefono}} </span> </h5>
                                        <p class="text-info"><b>
                                                {{reparto.Datos_reparto.Observaciones_delivery}}</b>
                                        </p>
                                        <p> <i class="ti-alarm-clock"></i> {{formatoFecha_hora(reparto.Datos_reparto.FechaHora_pedido)}}</p>
                                        <p> <i class="ti-car"></i> {{reparto.Datos_reparto.Nombre_repartidor}}</p>

                                        <hr>

                                        <div class="stat-text text-danger">Items pedidos</div>
                                        <ul>
                                            <li v-for="item in reparto.Datos_items">{{item.Nombre_item}} - ${{item.Precio_venta}}</li>
                                        </ul>
                                        <hr>
                                        <p class="text-info"><b>
                                                Productos: <b>${{reparto.Datos_reparto.Valor_cuenta}} </b><br>

                                                <span v-if="reparto.Datos_reparto.Valor_delivery > 0">Delivery: <b>${{reparto.Datos_reparto.Valor_delivery}} </b></span><br>
                                                <span v-if="reparto.Datos_reparto.Valor_descuento > 0"> Descuentos: <b>${{reparto.Datos_reparto.Valor_descuento}} </b></span>
                                        </p>
                                        <h5 class="text-danger">Total: <b>${{sumarMontos( reparto.Datos_reparto.Valor_cuenta, reparto.Datos_reparto.Valor_delivery, reparto.Datos_reparto.Valor_descuento) }}</b></h5>

                                    </div>
                                    <div v-if="reparto.Datos_reparto.FechaHora_entregaCliente == null" class="stat-digit">
                                        <button v-on:click="reportarEntrega(reparto.Datos_reparto.Id)" type="submit" class="btn btn-warning">ENTREGADO A CLIENTE</button>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="progress">
                                <div v-if="reparto.Alerta == 0" class="progress-bar progress-bar-success w-100" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                <div v-if="reparto.Alerta == 1" class="progress-bar progress-bar-warning w-100" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                <div v-if="reparto.Alerta == 2" class="progress-bar progress-bar-danger w-100" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>

                        </div>
                    </div>
                    <hr>
                    <h4>Comandas a retirar en cocina ({{listaRepartos.Deliverys_a_tomar.length}})</h4>
                    <div class="row">

                        <div class="col-lg-3" v-for="reparto in listaRepartos.Deliverys_a_tomar">
                            <div class="card">

                                <div class="stat-widget-two">
                                    <div class="stat-content">
                                        <div class="stat-digit">
                                            <!-- <i class="ti-user"></i> --><br> {{reparto.Datos_reparto.Nombre_cliente}}</div>
                                        <h5>
                                            <!-- <i class="fas fa-location-arrow"></i><span class="text-success"> --> {{reparto.Datos_reparto.Direccion}}<!-- </span> --></br>
                                            <!-- <i class="fas fa-phone"></i>--> <span class="text-success"> {{reparto.Datos_reparto.Telefono}}</span></h5>
                                        <p class="text-info"><b>
                                                {{reparto.Datos_reparto.Observaciones_delivery}}</b>
                                        </p>
                                        <p> <i class="ti-alarm-clock"></i> {{formatoFecha_hora(reparto.Datos_reparto.FechaHora_pedido)}}</p>
                                        <p> <i class="ti-car"></i> {{reparto.Datos_reparto.Nombre_repartidor}}</p>

                                        <hr>

                                        <div class="stat-text text-danger">Items pedidos</div>
                                        <ul>
                                            <li v-for="item in reparto.Datos_items">{{item.Nombre_item}} - ${{item.Precio_venta}}</li>
                                        </ul>
                                        <hr>
                                        <p class="text-info"><b>
                                                Productos: <b>${{reparto.Datos_reparto.Valor_cuenta}} </b><br>

                                                <span v-if="reparto.Datos_reparto.Valor_delivery > 0">Delivery: <b>${{reparto.Datos_reparto.Valor_delivery}} </b></span><br>
                                                <span v-if="reparto.Datos_reparto.Valor_descuento > 0"> Descuentos: <b>${{reparto.Datos_reparto.Valor_descuento}} </b></span>
                                        </p>
                                        <h5 class="text-danger">Total: <b>${{sumarMontos( reparto.Datos_reparto.Valor_cuenta, reparto.Datos_reparto.Valor_delivery, reparto.Datos_reparto.Valor_descuento) }}</b></h5>

                                    </div>
                                    <div v-if="reparto.Datos_reparto.FechaHora_entregaCliente == null" class="stat-digit">
                                        <button v-on:click="reportarTomado(reparto.Datos_reparto.Id)" type="submit" class="btn btn-warning">TOMADO DE COCINA</button>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="progress">
                                <div v-if="reparto.Alerta == 0" class="progress-bar progress-bar-success w-100" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                <div v-if="reparto.Alerta == 1" class="progress-bar progress-bar-warning w-100" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                <div v-if="reparto.Alerta == 2" class="progress-bar progress-bar-danger w-100" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>

                        </div>
                    </div>
                </section>
                <section id="main-content" v-if="Tipo_suscripcion == 1">
                    <div class="row">

                        <div class="col-sm-3">
                            Visualización en celular
                        </div>
                        <div class="col-sm-9">
                            Vizualización en pc
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <?php /// FOOTER
        include "footer.php";
        ?>