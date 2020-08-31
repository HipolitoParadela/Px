<?php
// CABECERA
include "head.php";
// TOP
include "top.php";
include "menusidebar.php";
?>
<!-- PAGE CONTAINER-->
<div class="page-container" id="app">
    <!-- HEADER DESKTOP-->
    <?PHP include "header_desktop.php"; ?>
    <!-- HEADER DESKTOP-->

    <!-- MAIN CONTENT-->
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!-- DATA TABLE -->
                        <h3 class="title-5 m-b-35">
                            Gestión de Periodos
                        </h3>
                        <div class="table-data__tool">
                            <div class="table-data__tool-left">

                                <!-- <div class="rs-select2--light">
                                    <select class="form-control form-control" v-model="filtro_estado" v-on:change="getListadoOrdenes(filtro_usuario, filtro_producto,filtro_cliente,filtro_estado)">
                                        <option selected="selected" v-bind:value="0">Ordenes en fabricación</option>
                                        <option selected="selected" v-bind:value="1">Ordenes finalizadas</option>
                                        <option selected="selected" v-bind:value="2">Ordenes despachadas</option>
                                    </select>
                                    <div class="dropDownSelect2"></div>
                                </div> -->
                            </div>
                            <div class="table-data__tool-right">
                                <button class="au-btn au-btn-icon au-btn--green au-btn--small" data-toggle="modal" data-target="#ordenmodal" v-on:click="limpiarFormularioPeriodo()">
                                    <i class="zmdi zmdi-plus"></i>Nueva periodo
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive table-responsive-data2">
                            <table class="table table-data2">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Fecha Inicio</th>
                                        <th>Fecha Fin</th>
                                        <th>Observaciones</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="tr-shadow" v-for="periodo in listaPeriodos">
                                        <td>
                                            <!-- <a v-bind:href="'ordentrabajo/datos/?Id='+periodo.Id" class="btn btn-dark btn-outline m-b-10 m-l-5"> -->
                                            {{periodo.Nombre_periodo}}
                                            <!-- </a> -->
                                        </td>
                                        <td>{{periodo.Fecha_inicio | Fecha}}</td>
                                        <td>{{periodo.Fecha_fin | Fecha}}</td>
                                        <td>{{periodo.Observaciones}}</td>
                                        <td>
                                            <div class="table-data-feature">

                                                <!-- <a class="item" v-bind:href="'ordentrabajo/datos/?Id='+periodo.Id" title="Ver todos los datos">
                                                    <i class="zmdi zmdi-mail-send"></i>
                                                </a> -->
                                                <button class="item" v-on:click="editarFormularioPeriodo(periodo)" data-toggle="modal" data-target="#ordenmodal" data-placement="top" title="Edición rápida">
                                                    <i class="zmdi zmdi-edit"></i>
                                                </button>

                                                <button v-on:click="eliminar(periodo.Id, tbl_periodos)" class="item" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                                    <i class="zmdi zmdi-delete"></i>
                                                </button>

                                            </div>
                                        </td>
                                    <tr class="spacer"></tr>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <!-- END DATA TABLE -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- modal ordens -->
    <div class="modal fade" id="ordenmodal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollmodalLabel">Formulario de orden de trabajo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal" action="post" v-on:submit.prevent="crearPeriodo()">
                    <div class="modal-body">
                        <div class="horizontal-form">
                            <div class="form-group">
                                <label class=" form-control-label">Nombre del período</label>
                                <input type="text" class="form-control" v-model="periodoDatos.Nombre_periodo" required>
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Fecha inicio de fabricación</label>
                                <input type="date" class="form-control" v-model="periodoDatos.Fecha_inicio" required>
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Fecha estimada para finalizar fabricación</label>
                                <input type="date" class="form-control" v-model="periodoDatos.Fecha_fin" required>
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Observaciones</label>
                                <textarea class="form-control" rows="5" v-model="periodoDatos.Observaciones"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">{{texto_boton}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal ordens -->
    <!-- END MAIN CONTENT-->
    <!-- END PAGE CONTAINER-->
    <?php
    // CABECERA
    include "footer.php";
    ?>
    </body>

    </html>
    <!-- end document-->