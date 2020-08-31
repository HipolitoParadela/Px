<?php
// CABECERA
include "head.php";
// TOP
include "top.php";
include "menusidebar.php";
?>
<!-- PAGE CONTAINER-->
<div class="page-container" id="itemStock">
    <!-- HEADER DESKTOP-->
    <?PHP include "header_desktop.php"; ?>
    <!-- HEADER DESKTOP-->

    <!-- MAIN CONTENT-->
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-header">
                                <h4>Info</h4>
                            </div>
                            <div class="card-body">
                                <div class="user-photo m-b-30">
                                    <img class="img-fluid" v-if="itemDatos.Imagen != null" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+itemDatos.Imagen" alt="">
                                    <img class="img-fluid" v-else src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" alt="">
                                </div>
                                <h3 class="text-sm-center mt-2 mb-1">{{itemDatos.Nombre_item}}</h3>
                                <div class="location text-sm-center">
                                    {{itemDatos.Nombre_categoria}}
                                </div>
                                <div class="location text-sm-center">
                                    {{itemDatos.Descripcion}}
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="location text-sm-center">
                                    <b>Stock Actual</b>
                                    <h1 v-bind:class="classAlertaStock(itemDatos.Cant_actual, itemDatos.Cant_ideal)" aling="right">{{itemDatos.Cant_actual}}</h1>
                                    <em>Cantidad ideal: {{itemDatos.Cant_ideal}}</em>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="location text-sm-center">
                                    <b>Monto</b>
                                    <h2 class="text-info" aling="right">${{itemDatos.Cant_actual * itemDatos.Precio | Moneda}}</h2>
                                    <em>Precio unidad: ${{itemDatos.Precio | Moneda}}</em>
                                </div>
                            </div>
                        </div>
                        <!-- LISTADO DE PROVEEDORES -->


                        <div class="card">
                            <div class="card-header">
                                <strong>Proveedores</strong>
                            </div>
                            <div class="card-body">
                                <a v-for="proveedor in lista_proveedores_vinculados" v-bind:href="'../../proveedores/datos/?Id='+proveedor.Proveedor_id" class="btn btn-dark btn-outline m-b-10 m-l-5">
                                    {{proveedor.Nombre_proveedor}}
                                </a>
                                <hr>
                                <!-- <button class="au-btn au-btn-icon au-btn--green au-btn--small" data-toggle="modal" data-target="#proveedormodal" v-on:click="obtener_listado_de_proveedores_no_asignados()">
                                                    <i class="zmdi zmdi-plus"></i>Asignar proveedor
                                                </button> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">

                        <!-- MOVIMIENTOS DEL PRODUCTO -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Movimientos registrados del stock de este producto</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="bootstrap-data-table-panel col-lg-12">
                                            <div class="table-responsive">
                                                <table id="table2excel" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Movimiento</th>
                                                            <th>Cantidad</th>
                                                            <th>Descripcíon</th>
                                                            <th>Fecha</th>
                                                            <th>Registrado por</th>
                                                            <TH>
                                                                <form class="form-horizontal" action="<?php echo base_url(); ?>exportar" method="post" name="f1" id="f1">
                                                                    <input type="hidden" name="tabla" id="tabla">
                                                                    <button onclick="myFunction()" class="btn btn-outline-primary btn-sm" type="submit">
                                                                        <i class="fa fa-download"></i> Descargar excel
                                                                    </button>
                                                                </form>
                                                            </TH>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="movimiento in listaMovimientos">
                                                            <td align="center">
                                                                <span v-show="movimiento.Tipo_movimiento == '2'" v-bind:class="{'text-danger': movimiento.Tipo_movimiento == '2' }"> <i class="fa fa-chevron-circle-down"></i></span>
                                                                <span v-show="movimiento.Tipo_movimiento == '1'"><i class="fa fa-chevron-circle-up"></i></span>
                                                            </td>
                                                            <td>
                                                                <h4 v-bind:class="{'text-danger': movimiento.Tipo_movimiento == '2' }" align="center"> {{movimiento.Cantidad}} </h4>
                                                            </td>
                                                            <td>{{movimiento.Descripcion}}</td>
                                                            <td>{{formatoFecha_hora(movimiento.Fecha_hora)}}</td>
                                                            <td>{{movimiento.Nombre}}</td>
                                                            <td>
                                                                <button class="item" v-on:click="editarFormularioMovimiento(movimiento)" data-toggle="modal" data-target="#movimientomodal" data-placement="top" title="Edición rápida">
                                                                    <i class="zmdi zmdi-edit"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th>
                                                                <h4 align="center"> TOTAL ACTUAL:</h4>
                                                            </th>
                                                            <TH>
                                                                <h3 align="center" v-bind:class="classAlertaStock(itemDatos.Cant_actual, itemDatos.Cant_ideal)">{{itemDatos.Cant_actual}}</h3>
                                                            </TH>
                                                        </tr>
                                                    </tfoot>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                </DIV>
                            </DIV>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- modal proveedores -->
        <div class="modal fade" id="proveedormodal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="scrollmodalLabel">Asignar proveedor a este producto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="table-responsive table-responsive-data2">
                        <table class="table table-data2">
                            <tbody>
                                <tr class="tr-shadow" v-for="proveedorSin in lista_proveedores_no_vinculados">
                                    <td>
                                        <h4> <button class="btn btn-success" v-on:click="Vincular_producto_proveedor(proveedorSin.Id)">
                                                <i class="fa fa-plus"></i>
                                            </button> &nbsp;&nbsp; {{proveedorSin.Nombre_proveedor}}
                                        </h4>
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
        <!-- end modal proveedores -->
        <!-- modal movimientos -->
        <div class="modal fade" id="movimientomodal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="scrollmodalLabel">Editar este reporte</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form class="form-horizontal" action="post" v-on:submit.prevent="actualizarMovimiento()">
                        <div class="modal-body">
                            <div class="horizontal-form">
                                <div class="form-group">
                                    <label class=" form-control-label">Descripción</label>
                                    <textarea class="form-control" rows="5" v-model="movimientoDatos.Descripcion"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </div>
                    </form>

                    <!-- END DATA TABLE -->
                </div>
            </div>
        </div>
        <!-- end modal movimientos -->
        <!-- END MAIN CONTENT-->
        <!-- END PAGE CONTAINER-->

        <?php
        // CABECERA
        include "footer.php";
        ?>

        </body>

        </html>
        <!-- end document-->