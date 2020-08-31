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
                                <h1>Delivery, <span>Resumen</span></h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Delivery</a></li>
                                    <li class="breadcrumb-item active">Resumen</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->
                <section id="main-content">
                    <h3 align="center">Resumen de Delivery</h3>
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="card">
                                <div class="row">
                                    <h4>Filtrar</h4>
                                </div>
                                <div class="row">
                                    <form class="form-horizontal" action="post">
                                        <div class="form-group">
                                            <label for="desde">Desde</label>
                                            <input type='date' class="form-control" v-model="fecha_desde" v-on:change="fecha_hasta = null" />
                                        </div>
                                        <div class="form-group">
                                            <label for="desde">Hasta</label>
                                            <input type='date' class="form-control" v-model="fecha_hasta" :disabled="fecha_desde == 0" v-on:change="deliveryEntreFechas()" v-bind:min="fecha_desde" />
                                        </div>
                                    </form>
                                    <div class="form-group">
                                        Filtrar por jornada
                                        <select class="form-control" v-model="filtro_jornada" v-on:change="deliveryEntreFechas()">
                                            <option value="0">Todas</option>
                                            <option v-for="jornada in listaJornadas" v-bind:value="jornada.Id">{{jornada.Descripcion}} | Del {{jornada.Fecha_inicio | FechaTimestampBaseDatos}} al {{jornada.Fecha_final | FechaTimestampBaseDatos}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        Filtrar por repartidor
                                        <select class="form-control" v-model="Filtro_repartidor" v-on:change="deliveryEntreFechas()">
                                            <option value="X">Todas</option>
                                            <option value="0">Retirado por cliente</option>
                                            <option v-for="repartidor in listaUsuariosRepartidores" v-bind:value="repartidor.Id">{{repartidor.Nombre}}</option>
                                        </select>
                                    </div>
                                    <br>
                                    <p v-show="fecha_hasta == 0">Por defecto se muestran los últimos 7 días</p>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="card">
                                <div class="bootstrap-data-table-panel">
                                    <div class="table-responsive">
                                        <table id="table2excel" class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <td>Cliente</td>
                                                    <td>Teléfono</td>
                                                    <td>Repartidor</td>
                                                    <td>Jornada</td>
                                                    <td align="center">Fecha</td>
                                                    <td align="right">Valor cuenta</td>
                                                    <td align="right">Valor delivery</td>
                                                    <td align="right">Valor descuento</td>
                                                    
                                                    <td align="right">Valor final</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="Delivery in listaDeliverysCerrados">
                                                    <td>
                                                        <a v-if="Delivery.Id" v-bind:href="'delivery/?Id='+Delivery.Id" class="text-info">{{Delivery.Nombre_cliente}}</a>
                                                    </td>
                                                    <td>{{Delivery.Telefono}}</td>
                                                    <td v-if="Delivery.Nombre_repartidor == null">Retiró Cliente</td>
                                                    <td v-else>{{Delivery.Nombre_repartidor}}</td>
                                                    <td>{{Delivery.Descripcion}}</td>
                                                    <td>{{Delivery.FechaHora_pedido | FechaTimestampBaseDatos}}</td>
                                                    <td align="right"><span class="text-success">${{Delivery.Valor_cuenta}}</span></td>
                                                    <td align="right"><span class="text-info">${{Delivery.Valor_delivery}}</span></td>
                                                    <td align="right"><span class="text-danger">${{Delivery.Valor_descuento}}</span></td>
                                                    <td align="right"><span class="text-success">${{sumarMontos(Delivery.Valor_cuenta, Delivery.Valor_delivery, Delivery.Valor_descuento)}}</span></td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="5"></td>
                                                    <td align="right">
                                                        <h4><span class="text-success">${{sumarCuenta(listaDeliverysCerrados)}}</span></h4>
                                                    </td>
                                                    <td align="right">
                                                        <h4><span class="text-info">${{sumarDeliverys(listaDeliverysCerrados)}}</span></h4>
                                                    </td>
                                                    <td align="right">
                                                        <h4><span class="text-danger">${{sumarDescuentos(listaDeliverysCerrados)}}</span></h4>
                                                    </td>
                                                    <td align="right">
                                                        <h4><span class="text-success">${{sumarCuenta(listaDeliverysCerrados) + sumarDeliverys(listaDeliverysCerrados) - sumarDescuentos(listaDeliverysCerrados)}}</span></h4>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
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

            <?php /// CABECERA BODY
            include "footer.php";
            ?>