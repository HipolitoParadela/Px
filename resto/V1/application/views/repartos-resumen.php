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
}elseif ($this->session->userdata('Rol_id') == 5) {
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
                                <h1>Repartos, <span>Resumen</span></h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Repartos</a></li>
                                    <li class="breadcrumb-item active">Resumen</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->
                <section id="main-content">
                    <h3 align="center">Resumen de Repartos</h3>
                    <div class="row">
                        <!-- <div class="col-lg-2">
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
                                            <input type='date' class="form-control" v-model="fecha_hasta" :disabled="fecha_desde == 0" v-on:change="obtener_listado_repartos()" v-bind:min="fecha_desde" />
                                        </div>
                                    </form>
                                    <div class="form-group">
                                        Filtrar por jornada
                                        <select class="form-control" v-model="filtro_jornada" v-on:change="obtener_listado_repartos()">
                                            <option value="0">Todas</option>
                                            <option v-for="jornada in listaJornadas" v-bind:value="jornada.Id">{{jornada.Descripcion}} | Del {{jornada.Fecha_inicio | FechaTimestampBaseDatos}} al {{jornada.Fecha_final | FechaTimestampBaseDatos}}</option>
                                        </select>
                                    </div>
                                    <br>
                                    <p v-show="fecha_hasta == 0">Por defecto se muestran los últimos 30 días</p>
                                </div>

                            </div>
                        </div> 
                        <div class="col-lg-10">-->
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="bootstrap-data-table-panel">
                                    <div class="table-responsive">
                                        <table id="table2excel" class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <td>Cliente</td>
                                                    <td>Dirección</td>
                                                    <td>Jornada</td>
                                                    <td align="center">Fecha</td>
                                                    <td align="right">Monto delivery</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="Delivery in listaRepartos">
                                                    <td>
                                                        {{Delivery.Nombre_cliente}}
                                                    </td>
                                                    <td>{{Delivery.Direccion}}</td>
                                                    <td>{{Delivery.Descripcion}}</td>
                                                    <td>{{Delivery.FechaHora_pedido | FechaTimestampBaseDatos}}</td>
                                                    <td align="right"><span class="text-success">${{Delivery.Valor_delivery}}</span></td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="5"></td>
                                                    <td align="right">
                                                        <h4><span class="text-info">${{sumarRepartos(listaRepartos)}}</span></h4>
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