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
    <div class="content-wrap" id="analiticas">
        <div class="main">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-lg-8 p-r-0 title-margin-right">
                        <div class="page-header">
                            <div class="page-title">
                                <h1>Carta, <span>ventas de items</span></h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Sistema restaurant</a></li>
                                    <li class="breadcrumb-item active">Ventas de items</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->

                <section id="main-content">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="card">
                                <h4>Filtrar</h4>

                                <div class="form-group">
                                    <label for="desde">Desde</label>
                                    <input type='date' class="form-control" v-model="fecha_desde" />
                                </div>
                                <div class="form-group">
                                    <label for="desde">Hasta</label>
                                    <input type='date' class="form-control" v-model="fecha_hasta" :disabled="fecha_desde == 0" v-on:change="obtenerAnalisisVentas()" v-bind:min="fecha_desde" />
                                </div>
                                <div class="form-group">
                                    Filtrar por categorías
                                    <select class="form-control" v-model="filtro_categoria" v-on:change="obtenerAnalisisVentas()">
                                        <option value="0">Todas</option>
                                        <option v-for="categoria in categoriasCarta" v-bind:value="categoria.Id">{{categoria.Nombre_categoria}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    Filtrar por jornada
                                    <select class="form-control" v-model="filtro_jornada" v-on:change="obtenerAnalisisVentas()">
                                        <option value="0">Todas</option>
                                        <option v-for="jornada in listaJornadas" v-bind:value="jornada.Id">{{jornada.Descripcion}} | Del {{jornada.Fecha_inicio | FechaTimestampBaseDatos}} al {{jornada.Fecha_final | FechaTimestampBaseDatos}}</option>
                                    </select>
                                </div>
                                <br>
                                <p v-show="fecha_hasta == 0">Por defecto se contabilizan las ventas desde los inicios</p>
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class="card">
                                <div class="bootstrap-data-table-panel">
                                    <div class="table-responsive">
                                        <table id="table2excel" class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>V. Totales</th>
                                                    <th>Nombre</th>
                                                    <th>Categoría</th>
                                                    <th>V. Comandas</th>
                                                    <th>V. Delivery</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="item in listaVentas">
                                                    <!-- <td>
                                                        <a v-if="item.Id" v-bind:href="'item/?Id='+item.Id" class="text-info">{{formatoFecha(item.Fecha)}} {{formatoHora(item.Hora_llegada)}}</a>
                                                    </td> -->
                                                    <td>
                                                        <h4>{{item.Total_ventas}}</h4>
                                                    </td>
                                                    <td><b>{{item.Nombre_item}}</b></td>
                                                    <td>{{item.Categoria}}</td>
                                                    <td>{{item.Ventas_comanda}}</td>
                                                    <td>{{item.Ventas_delivery}}</td>

                                                </tr>

                                            </tbody>
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

        </div>

        <?php /// CABECERA BODY
        include "footer.php";
        ?>