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
                                <h1>Comandas, <span>Resumen</span></h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Comandas</a></li>
                                    <li class="breadcrumb-item active">Resumen</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->
                <section id="main-content">
                    <h3 align="center">Resumen de comandas</h3>
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="card">
                                <div class="row">
                                    <h4>Filtrar</h4>
                                    <form class="form-horizontal" action="post">
                                        <div class="form-group">
                                            <label for="desde">Desde</label>
                                            <input type='date' class="form-control" v-model="fecha_desde" />
                                        </div>
                                        <div class="form-group">
                                            <label for="desde">Hasta</label>
                                            <input type='date' class="form-control" v-model="fecha_hasta" :disabled="fecha_desde == 0" v-on:change="comandasEntreFechas()" v-bind:min="fecha_desde" />
                                        </div>
                                    </form>
                                    <div class="form-group">
                                        Filtrar por jornada
                                        <select class="form-control" v-model="filtro_jornada" v-on:change="comandasEntreFechas()">
                                            <option value="0">Todas</option>
                                            <option v-for="jornada in listaJornadas" v-bind:value="jornada.Id">{{jornada.Descripcion}} | Del {{jornada.Fecha_inicio | FechaTimestampBaseDatos}} al {{jornada.Fecha_final | FechaTimestampBaseDatos}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        Filtrar por mozo
                                        <select class="form-control" v-model="Filtro_mozo" v-on:change="comandasEntreFechas()">
                                            <option value="X">Todos</option>
                                            <option v-for="mozo in listaMozos" v-bind:value="mozo.Id">{{mozo.Nombre}}</option>
                                        </select>
                                    </div>
                                    <br>
                                    <p v-show="fecha_hasta == 0">Por defecto se muestran los últimos 30 días</p>
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
                                                    <td>Comanda</td>
                                                    <td>Mesa</td>

                                                    <td>Mozo</td>
                                                    <td align="center">Comensales</td>
                                                    <td align="right">Valor cuenta</td>
                                                    <td align="right">Valor descuento</td>
                                                    <td align="right">Valor final</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="comanda in listaComandasCerradas">
                                                    <td>
                                                        <a v-if="comanda.Id" v-bind:href="'comanda/?Id='+comanda.Id" class="text-info">{{formatoFecha(comanda.Fecha)}} {{formatoHora(comanda.Hora_llegada)}}</a>
                                                    </td>
                                                    <td>{{comanda.Identificador}}</td>
                                                    <td>{{comanda.Nombre_moso}}</td>
                                                    <td align="center">{{comanda.Cant_personas}}</td>

                                                    <td align="right"><span class="text-info">${{comanda.Valor_cuenta}}</span></td>
                                                    <td align="right"><span class="text-danger">${{comanda.Valor_descuento}}</span></td>
                                                    <td align="right"><span class="text-success">${{comanda.Valor_cuenta - comanda.Valor_descuento}}</span></td>
                                                </tr>

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3"></td>
                                                    <td align="center">
                                                        <h4>{{sumarComensales(listaComandasCerradas)}}</h4>
                                                    </td>
                                                    <td align="right">
                                                        <h4><span class="text-info">${{sumarCuenta(listaComandasCerradas)}}</span></h4>
                                                    </td>
                                                    <td align="right">
                                                        <h4><span class="text-danger">${{sumarDescuentos(listaComandasCerradas)}}</span></h4>
                                                    </td>
                                                    <td align="right">
                                                        <h4><span class="text-success">${{sumarCuenta(listaComandasCerradas) - sumarDescuentos(listaComandasCerradas)}}</span></h4>
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