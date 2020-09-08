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
                                <h1>jornadas, <span>Resumen</span></h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">jornadas</a></li>
                                    <li class="breadcrumb-item active">Resumen</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->
                <section id="main-content">

                    <div class="row">
                        <div class="col-lg-2">
                            <div class="card">
                                <div class="row">
                                    <h4>Filtrar informes</h4>
                                </div>
                                <div class="row">
                                    <form class="form-horizontal" action="post">

                                        <label for="desde">Desde</label>
                                        <input type='date' class="form-control" v-model="fecha_desde" :disabled="Tipo_suscripcion == 1"/>

                                        <label for="desde">Hasta</label>
                                        <input type='date' class="form-control" v-model="fecha_hasta" :disabled="fecha_desde == 0" v-on:change="getJornadasResumen(fecha_desde,fecha_hasta)" v-bind:min="fecha_desde" />

                                    </form>
                                </div>
                                <br>
                                <p v-show="fecha_hasta == 0">Por defecto se muestran las jornadas de los últimos 28 días</p>

                            </div>
                            <div class="card" v-show="Tipo_suscripcion == 1">
                                <h5 class="text-success">
                                    <b>Adquiera PX Resto PRO</b> para activar los filtros. </h5>
                                    Filtrar los datos le permitirá analizar los resultados en periodos más extensos y así tomar mejores decisiones con respecto a la gestión comercial de su negocio.
                                    <a href="http://pxsistemas.com/px-resto-software-para-administrar-restaurantes-y-delivery/">Me interesa</a>
                            </div>
                        </div>
                        <div class="col-lg-10">

                            <div class="card">
                                <h3 align="center">Resumen de jornadas</h3>
                                <div class="bootstrap-data-table-panel">
                                    <div class="table-responsive">
                                        <table id="table2excel" class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <td class="text-info">Jornada</td>
                                                    <td class="text-info">Fecha</td>
                                                    <td class="text-info" align="center">Delivery</td>
                                                    <td class="text-info" align="center">Comandas</td>
                                                    <td class="text-info" align="center">Caja</td>
                                                    <td class="text-info" align="center">Res. Jornada</td>
                                                    <td class="text-info" align="right">Administrador</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="jornada in listaResumenJornadas">
                                                    <td>
                                                        <a v-bind:href="'jornada/?Id='+jornada.Datos_jornada.Id" class="text-info">
                                                            <b>
                                                                <span class="small">
                                                                    {{jornada.Datos_jornada.Descripcion}}
                                                                </span>
                                                            </b>
                                                        </a>
                                                    </td>
                                                    <td><span class="small">{{jornada.Datos_jornada.Fecha_inicio | FechaTimestampBaseDatos}} <br>{{jornada.Datos_jornada.Fecha_final | FechaTimestampBaseDatos}}</span></td>

                                                    <td align="center">{{jornada.Cant_delivery}}<br> ${{jornada.Ing_delivery}}</td>
                                                    <td align="center">{{jornada.Cant_comandas}}<br> ${{jornada.Ing_comadas}}</td>

                                                    <td align="center">${{jornada.Result_caja}}</td>
                                                    <td align="center">${{jornada.Resultado_joranda}}</td>
                                                    <td><span class="small">{{jornada.Datos_jornada.Nombre}}</span></td>
                                                </tr>

                                            </tbody>
                                            <!-- <tfoot>
                                                <tr>
                                                    <td colspan="3"></td>
                                                    <td align="center"><h4>{{sumarComensales(listajornadasCerradas)}}</h4></td>
                                                    <td align="right"><h4><span class="text-info">${{sumarCuenta(listajornadasCerradas)}}</span></h4></td>
                                                    <td align="right"><h4><span class="text-danger">${{sumarDescuentos(listajornadasCerradas)}}</span></h4></td>
                                                    <td align="right"><h4><span class="text-success">${{sumarCuenta(listajornadasCerradas) - sumarDescuentos(listajornadasCerradas)}}</span></h4></td>
                                                </tr>
                                            </tfoot> -->
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