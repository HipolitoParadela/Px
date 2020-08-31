<?php
// CABECERA
include "head.php";
// TOP
include "top.php";
include "menusidebar.php";
?>
<!-- PAGE CONTAINER-->
<div class="page-container" id="ventas">
    <!-- HEADER DESKTOP-->
    <?PHP include "header_desktop.php"; ?>
    <!-- HEADER DESKTOP-->

    <!-- MAIN CONTENT-->
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="row">

                    <!-- SECCION FICHA cliente -->
                    <div class="col-lg-12">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 1 }" href="#" v-on:click="mostrar = 1">Control Stock</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 2 }" href="#" v-on:click="mostrar = 2">Proceso materiales</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 3 }" href="#" v-on:click="mostrar = 3">Soldadura</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 4 }" href="#" v-on:click="mostrar = 4">Pintura</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 5 }" href="#" v-on:click="mostrar = 5">Rotulación</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 6 }" href="#" v-on:click="mostrar = 6">Empaque</a>
                            </li>
                        </ul>

                        <!-- SECCION LISTO -->
                        <div class="row" v-show="mostrar == '1'">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Listado de productos en etapa de CONTROL DE STOCK</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="bootstrap-data-table-panel col-lg-12">
                                            <div class="table-responsive">
                                                <div class="table-responsive">
                                                    <table id="table2excel" class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>Cantidad</th>
                                                                <th>Entrega <br> estimada en</th>
                                                                <th>Tipo</th>
                                                                <th>Producto</th>
                                                                <th>Venta</th>
                                                                <th>Cliente</th>
                                                                <th>Requerimentos</th>
                                                                <th>Ingreso<br>a esta etapa</th>
                                                                <th>Egreso<br>de esta etapa</th>
                                                                <th>Tiempo <br>en esta etapa</th>
                                                                <th>Observaciones</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="producto_1 in listaEtapa_1">

                                                                <!-- ESTO NO DEBERIA IR, CREO -->
                                                                <td>
                                                                    <div v-if="Usuario_id == 3 || producto_1.Responsable_id_planif_inicial == Usuario_id || Rol_acceso > 1">
                                                                        <a v-if="producto_1.Estado == 1" href="#modalPasoapaso" data-toggle="modal" class="btn btn-primary btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(producto_1.Id, 2)" v-bind:class="[{ 'btn-danger' : diferenciasEntre_fechas(null, producto_1.Fecha_estimada_entrega) < 0}, {'btn-warning' : diferenciasEntre_fechas(null, producto_1.Fecha_estimada_entrega) < 7}]">
                                                                            <i class="ti-plus"></i> LISTO >>
                                                                        </a>
                                                                    </div>
                                                                    <span v-if="producto_1.Estado > 1"> {{producto_1.S_1_Fecha_finalizado | Fecha}}</span>
                                                                    <span v-if="producto_1.Estado < 1"> En etapa previa</span>
                                                                </td>
                                                                <td>
                                                                    <h4 align="center">{{producto_1.Cantidad}}</h4>
                                                                </td>
                                                                <td v-if="diferenciasEntre_fechas(null, producto_1.Fecha_estimada_entrega) < 0">
                                                                    {{ diferenciasEntre_fechas(null, producto_1.Fecha_estimada_entrega) * (-1) }} días de atrazo
                                                                </td>
                                                                <td v-else> {{ diferenciasEntre_fechas(null, producto_1.Fecha_estimada_entrega) }} días</td>
                                                                <td>
                                                                    <span v-if="producto_1.Tipo_produccion == 1" class="text-secondary"> <i class="fa fa-circle"></i></span>
                                                                    <span v-if="producto_1.Tipo_produccion == 2" class="text-warning"> <i class="fa fa-circle"></i></span>
                                                                    <span v-if="producto_1.Tipo_produccion == 3" class="text-info"> <i class="fa fa-circle"></i></span>
                                                                </td>
                                                                <td>
                                                                    <h4> {{producto_1.Nombre_producto}}</h4>
                                                                </td>
                                                                <td> {{producto_1.Identificador_venta}} </td>
                                                                <td> {{producto_1.Nombre_cliente}} </td>
                                                                <td> {{producto_1.S_1_Requerimientos}} </td>
                                                                <td> {{producto_1.Fecha_venta | Fecha}} </td>
                                                                <td> {{producto_1.S_1_Fecha_finalizado | Fecha}} </td>
                                                                <td> {{diferenciasEntre_fechas(producto_1.Fecha_venta, producto_1.S_1_Fecha_finalizado)}} días </td>
                                                                <td> {{producto_1.S_1_Observaciones}} </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p align="center">
                                    <span class="text-secondary"><i class="fa fa-circle"></i> Normal</span>
                                    <span class="text-warning"><i class="fa fa-circle"></i> Reclamo</span>
                                    <span class="text-info"><i class="fa fa-circle"></i> Muestra</span>
                                </p>
                            </div>
                        </div>
                        <!-- SECCION PROCESO MATERIALES -->
                        <div class="row" v-show="mostrar == '2'">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Listado de productos en etapa de PROCESO DE MATERIALES</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="bootstrap-data-table-panel col-lg-12">
                                            <div class="table-responsive">
                                                <div class="table-responsive">
                                                    <table id="table2excel" class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>Cantidad</th>
                                                                <th>Entrega <br> estimada en</th>
                                                                <th>Tipo</th>
                                                                <th>Producto</th>
                                                                <th>Venta</th>
                                                                <th>Cliente</th>
                                                                <th>Requerimentos</th>
                                                                <th>Ingreso<br>a esta etapa</th>
                                                                <th>Egreso<br>de esta etapa</th>
                                                                <th>Transcurrido <br>en esta etapa</th>
                                                                <th>Observaciones</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="producto_2 in listaEtapa_2">

                                                                <!-- ESTO NO DEBERIA IR, CREO -->
                                                                <td>
                                                                    <div v-if="producto_2.Responsable_id_planif_inicial == Usuario_id || Rol_acceso > 1 || Usuario_id == '3'">
                                                                        <a v-if="producto_2.Estado == 2" href="#modalPasoapaso" data-toggle="modal" class="btn btn-primary btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(producto_2.Id, 3)" v-bind:class="[{ 'btn-danger' : diferenciasEntre_fechas(null, producto_2.Fecha_estimada_entrega) < 0}, {'btn-warning' : diferenciasEntre_fechas(null, producto_2.Fecha_estimada_entrega) < 7}]">
                                                                            <i class="ti-plus"></i> LISTO >>
                                                                        </a>
                                                                    </div>
                                                                    <span v-if="producto_2.Estado > 2"> {{producto_2.S_2_Fecha_finalizado | Fecha}}</span>
                                                                    <span v-if="producto_2.Estado < 2"> En etapa previa</span>

                                                                </td>
                                                                <td>
                                                                    <h4 align="center">{{producto_2.Cantidad}}</h4>
                                                                </td>
                                                                <td v-if="diferenciasEntre_fechas(null, producto_2.Fecha_estimada_entrega) < 0">
                                                                    {{ diferenciasEntre_fechas(null, producto_2.Fecha_estimada_entrega) * (-1) }} días de atrazo
                                                                </td>
                                                                <td v-else> {{ diferenciasEntre_fechas(null, producto_2.Fecha_estimada_entrega) }} días</td>
                                                                <td>
                                                                    <span v-if="producto_2.Tipo_produccion == 1" class="text-secondary"> <i class="fa fa-circle"></i></span>
                                                                    <span v-if="producto_2.Tipo_produccion == 2" class="text-warning"> <i class="fa fa-circle"></i></span>
                                                                    <span v-if="producto_2.Tipo_produccion == 3" class="text-info"> <i class="fa fa-circle"></i></span>
                                                                </td>
                                                                <td>
                                                                    <h4> {{producto_2.Nombre_producto}}</h4>
                                                                </td>
                                                                <td> {{producto_2.Identificador_venta}} </td>
                                                                <td> {{producto_2.Nombre_cliente}} </td>
                                                                <td> {{producto_2.S_2_Requerimientos}} </td>
                                                                <td> {{producto_2.S_1_Fecha_finalizado | Fecha}} </td>
                                                                <td> {{producto_2.S_2_Fecha_finalizado | Fecha}} </td>
                                                                <td> {{diferenciasEntre_fechas(producto_2.S_1_Fecha_finalizado, producto_2.S_2_Fecha_finalizado)}} días </td>
                                                                <td> {{producto_2.S_2_Observaciones}} </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p align="center">
                                    <span class="text-secondary"><i class="fa fa-circle"></i> Normal</span>
                                    <span class="text-warning"><i class="fa fa-circle"></i> Reclamo</span>
                                    <span class="text-info"><i class="fa fa-circle"></i> Muestra</span>
                                </p>
                            </div>
                        </div>
                        <!-- SECCION SOLDADURA -->
                        <div class="row" v-show="mostrar == '3'">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Listado de productos en etapa de SOLDADURA</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="bootstrap-data-table-panel col-lg-12">
                                            <div class="table-responsive">
                                                <div class="table-responsive">
                                                    <table id="table2excel" class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>Cantidad</th>
                                                                <th>Entrega <br> estimada en</th>
                                                                <th>Tipo</th>
                                                                <th>Producto</th>
                                                                <th>Venta</th>
                                                                <th>Cliente</th>
                                                                <th>Requerimentos</th>
                                                                <th>Ingreso<br>a esta etapa</th>
                                                                <th>Egreso<br>de esta etapa</th>
                                                                <th>Transcurrido <br>en esta etapa</th>
                                                                <th>Observaciones</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="producto_3 in listaEtapa_3">

                                                                <!-- ESTO NO DEBERIA IR, CREO -->
                                                                <td>
                                                                    <div v-if="producto_3.Responsable_id_planif_inicial == Usuario_id || Rol_acceso > 1 || Usuario_id == '3'">
                                                                        <a v-if="producto_3.Estado == 3" href="#modalPasoapaso" data-toggle="modal" class="btn btn-primary btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(producto_3.Id, 4)" v-bind:class="[{ 'btn-danger' : diferenciasEntre_fechas(null, producto_3.Fecha_estimada_entrega) < 0}, {'btn-warning' : diferenciasEntre_fechas(null, producto_3.Fecha_estimada_entrega) < 7}]">
                                                                            <i class="ti-plus"></i> LISTO >>
                                                                        </a>
                                                                    </div>
                                                                    <span v-if="producto_3.Estado > 3"> {{producto_3.S_3_Fecha_finalizado | Fecha}}</span>
                                                                    <span v-if="producto_3.Estado < 3"> En etapa previa</span>

                                                                </td>
                                                                <td>
                                                                    <h4 align="center">{{producto_3.Cantidad}}</h4>
                                                                </td>

                                                                <td v-if="diferenciasEntre_fechas(null, producto_3.Fecha_estimada_entrega) < 0">
                                                                    {{ diferenciasEntre_fechas(null, producto_3.Fecha_estimada_entrega) * (-1) }} días de atrazo
                                                                </td>
                                                                <td v-else> {{ diferenciasEntre_fechas(null, producto_3.Fecha_estimada_entrega) }} días</td>
                                                                <td>
                                                                    <span v-if="producto_3.Tipo_produccion == 1" class="text-secondary"> <i class="fa fa-circle"></i></span>
                                                                    <span v-if="producto_3.Tipo_produccion == 2" class="text-warning"> <i class="fa fa-circle"></i></span>
                                                                    <span v-if="producto_3.Tipo_produccion == 3" class="text-info"> <i class="fa fa-circle"></i></span>
                                                                </td>
                                                                <td>
                                                                    <h4> {{producto_3.Nombre_producto}}</h4>
                                                                </td>
                                                                <td> {{producto_3.Identificador_venta}} </td>
                                                                <td> {{producto_3.Nombre_cliente}} </td>
                                                                <td> {{producto_3.S_3_Requerimientos}} </td>
                                                                <td> {{producto_3.S_2_Fecha_finalizado | Fecha}} </td>
                                                                <td> {{producto_3.S_3_Fecha_finalizado | Fecha}} </td>
                                                                <td> {{diferenciasEntre_fechas(producto_3.S_2_Fecha_finalizado, producto_3.S_3_Fecha_finalizado)}} días </td>
                                                                <td> {{producto_3.S_3_Observaciones}} </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p align="center">
                                    <span class="text-secondary"><i class="fa fa-circle"></i> Normal</span>
                                    <span class="text-warning"><i class="fa fa-circle"></i> Reclamo</span>
                                    <span class="text-info"><i class="fa fa-circle"></i> Muestra</span>
                                </p>
                            </div>
                        </div>
                        <!-- SECCION PINTURA -->
                        <div class="row" v-show="mostrar == '4'">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Listado de productos en etapa de PINTURA</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="bootstrap-data-table-panel col-lg-12">
                                            <div class="table-responsive">
                                                <div class="table-responsive">
                                                    <table id="table2excel" class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>Cantidad</th>
                                                                <th>Entrega <br> estimada en</th>
                                                                <th>Tipo</th>
                                                                <th>Producto</th>
                                                                <th>Venta</th>
                                                                <th>Cliente</th>
                                                                <th>Requerimentos</th>
                                                                <th>Ingreso<br>a esta etapa</th>
                                                                <th>Egreso<br>de esta etapa</th>
                                                                <th>Transcurrido <br>en esta etapa</th>
                                                                <th>Observaciones</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="producto_4 in listaEtapa_4">

                                                                <!-- ESTO NO DEBERIA IR, CREO -->
                                                                <td>
                                                                    <div v-if="producto_4.Responsable_id_planif_final == Usuario_id || Rol_acceso > 1 || Usuario_id == '3'">
                                                                        <a v-if="producto_4.Estado == 4" href="#modalPasoapaso" data-toggle="modal" class="btn btn-primary btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(producto_4.Id, 5)" v-bind:class="[{ 'btn-danger' : diferenciasEntre_fechas(null, producto_4.Fecha_estimada_entrega) < 0}, {'btn-warning' : diferenciasEntre_fechas(null, producto_4.Fecha_estimada_entrega) < 7}]">
                                                                            <i class="ti-plus"></i> LISTO >>
                                                                        </a>
                                                                    </div>
                                                                    <span v-if="producto_4.Estado > 4"> {{producto_4.S_4_Fecha_finalizado | Fecha}}</span>
                                                                    <span v-if="producto_4.Estado < 4"> En etapa previa</span>

                                                                </td>
                                                                <td>
                                                                    <h4 align="center">{{producto_4.Cantidad}}</h4>
                                                                </td>

                                                                <td v-if="diferenciasEntre_fechas(null, producto_4.Fecha_estimada_entrega) < 0">
                                                                    {{ diferenciasEntre_fechas(null, producto_4.Fecha_estimada_entrega) * (-1) }} días de atrazo
                                                                </td>
                                                                <td v-else> {{ diferenciasEntre_fechas(null, producto_4.Fecha_estimada_entrega) }} días</td>
                                                                <td>
                                                                    <span v-if="producto_4.Tipo_produccion == 1" class="text-secondary"> <i class="fa fa-circle"></i></span>
                                                                    <span v-if="producto_4.Tipo_produccion == 2" class="text-warning"> <i class="fa fa-circle"></i></span>
                                                                    <span v-if="producto_4.Tipo_produccion == 3" class="text-info"> <i class="fa fa-circle"></i></span>
                                                                </td>
                                                                <td>
                                                                    <h4> {{producto_4.Nombre_producto}}</h4>
                                                                </td>
                                                                <td> {{producto_4.Identificador_venta}} </td>
                                                                <td> {{producto_4.Nombre_cliente}} </td>
                                                                <td> {{producto_4.S_4_Requerimientos}} </td>
                                                                <td> {{producto_4.S_3_Fecha_finalizado | Fecha}} </td>
                                                                <td> {{producto_4.S_4_Fecha_finalizado | Fecha}} </td>
                                                                <td> {{diferenciasEntre_fechas(producto_4.S_3_Fecha_finalizado, producto_4.S_4_Fecha_finalizado)}} días </td>
                                                                <td> {{producto_4.S_4_Observaciones}} </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p align="center">
                                    <span class="text-secondary"><i class="fa fa-circle"></i> Normal</span>
                                    <span class="text-warning"><i class="fa fa-circle"></i> Reclamo</span>
                                    <span class="text-info"><i class="fa fa-circle"></i> Muestra</span>
                                </p>
                            </div>
                        </div>
                        <!-- SECCION ROTULACIÓN -->
                        <div class="row" v-show="mostrar == '5'">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Listado de productos en etapa de ROTULACIÓN</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="bootstrap-data-table-panel col-lg-12">
                                            <div class="table-responsive">
                                                <div class="table-responsive">
                                                    <table id="table2excel" class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>Cantidad</th>
                                                                <th>Entrega <br> estimada en</th>
                                                                <th>Tipo</th>
                                                                <th>Producto</th>
                                                                <th>Venta</th>
                                                                <th>Cliente</th>
                                                                <th>Requerimentos</th>
                                                                <th>Ingreso<br>a esta etapa</th>
                                                                <th>Egreso<br>de esta etapa</th>
                                                                <th>Transcurrido <br>en esta etapa</th>
                                                                <th>Observaciones</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="producto_5 in listaEtapa_5">

                                                                <!-- ESTO NO DEBERIA IR, CREO -->
                                                                <td>
                                                                    <div v-if="producto_5.Responsable_id_planif_final == Usuario_id || Rol_acceso > 1 || Usuario_id == '3'">
                                                                        <a v-if="producto_5.Estado == 5" href="#modalPasoapaso" data-toggle="modal" class="btn btn-primary btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(producto_5.Id, 6)" v-bind:class="[{ 'btn-danger' : diferenciasEntre_fechas(null, producto_5.Fecha_estimada_entrega) < 0}, {'btn-warning' : diferenciasEntre_fechas(null, producto_5.Fecha_estimada_entrega) < 7}]">
                                                                            <i class="ti-plus"></i> LISTO >>
                                                                        </a>
                                                                    </div>
                                                                    <span v-if="producto_5.Estado > 5"> {{producto_5.S_5_Fecha_finalizado | Fecha}}</span>
                                                                    <span v-if="producto_5.Estado < 5"> En etapa previa</span>

                                                                </td>
                                                                <td>
                                                                    <h4 align="center">{{producto_5.Cantidad}}</h4>
                                                                </td>
                                                                <td v-if="diferenciasEntre_fechas(null, producto_5.Fecha_estimada_entrega) < 0">
                                                                    {{ diferenciasEntre_fechas(null, producto_5.Fecha_estimada_entrega) * (-1) }} días de atrazo
                                                                </td>
                                                                <td v-else> {{ diferenciasEntre_fechas(null, producto_5.Fecha_estimada_entrega) }} días</td>
                                                                <td>
                                                                    <span v-if="producto_5.Tipo_produccion == 1" class="text-secondary"> <i class="fa fa-circle"></i></span>
                                                                    <span v-if="producto_5.Tipo_produccion == 2" class="text-warning"> <i class="fa fa-circle"></i></span>
                                                                    <span v-if="producto_5.Tipo_produccion == 3" class="text-info"> <i class="fa fa-circle"></i></span>
                                                                </td>
                                                                <td>
                                                                    <h4> {{producto_5.Nombre_producto}}</h4>
                                                                </td>
                                                                <td> {{producto_5.Identificador_venta}} </td>
                                                                <td> {{producto_5.Nombre_cliente}} </td>
                                                                <td> {{producto_5.S_5_Requerimientos}} </td>
                                                                <td> {{producto_5.S_4_Fecha_finalizado | Fecha}} </td>
                                                                <td> {{producto_5.S_5_Fecha_finalizado | Fecha}} </td>
                                                                <td> {{diferenciasEntre_fechas(producto_5.S_4_Fecha_finalizado, producto_5.S_5_Fecha_finalizado)}} días </td>
                                                                <td> {{producto_5.S_5_Observaciones}} </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p align="center">
                                    <span class="text-secondary"><i class="fa fa-circle"></i> Normal</span>
                                    <span class="text-warning"><i class="fa fa-circle"></i> Reclamo</span>
                                    <span class="text-info"><i class="fa fa-circle"></i> Muestra</span>
                                </p>
                            </div>
                        </div>
                        <!-- SECCION EMPAQUE -->
                        <div class="row" v-show="mostrar == '6'">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Listado de productos en etapa de EMPAQUE</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="bootstrap-data-table-panel col-lg-12">
                                            <div class="table-responsive">
                                                <div class="table-responsive">
                                                    <table id="table2excel" class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>Cantidad</th>
                                                                <th>Entrega <br> estimada en</th>
                                                                <th>Tipo</th>
                                                                <th>Producto</th>
                                                                <th>Venta</th>
                                                                <th>Cliente</th>
                                                                <th>Requerimentos</th>
                                                                <th>Ingreso<br>a esta etapa</th>
                                                                <th>Egreso<br>de esta etapa</th>
                                                                <th>Transcurrido <br>en esta etapa</th>
                                                                <th>Observaciones</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="producto_6 in listaEtapa_6">

                                                                <!-- ESTO NO DEBERIA IR, CREO -->
                                                                <td>
                                                                    <div v-if="producto_6.Responsable_id_planif_final == Usuario_id || Rol_acceso > 1 || Usuario_id == '3'">
                                                                        <a v-if="producto_6.Estado == 6" href="#modalPasoapaso" data-toggle="modal" class="btn btn-primary btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(producto_6.Id, 7)" v-bind:class="[{ 'btn-danger' : diferenciasEntre_fechas(null, producto_6.Fecha_estimada_entrega) < 0}, {'btn-warning' : diferenciasEntre_fechas(null, producto_6.Fecha_estimada_entrega) < 7}]">
                                                                            <i class="ti-plus"></i> LISTO >>
                                                                        </a>
                                                                    </div>
                                                                    <span v-if="producto_6.Estado > 6"> {{producto_6.S_6_Fecha_finalizado | Fecha}}</span>
                                                                    <span v-if="producto_6.Estado < 6"> En etapa previa</span>

                                                                </td>
                                                                <td>
                                                                    <h4 align="center">{{producto_6.Cantidad}}</h4>
                                                                </td>
                                                                <td v-if="diferenciasEntre_fechas(null, producto_6.Fecha_estimada_entrega) < 0">
                                                                    {{ diferenciasEntre_fechas(null, producto_6.Fecha_estimada_entrega) * (-1) }} días de atrazo
                                                                </td>
                                                                <td v-else> {{ diferenciasEntre_fechas(null, producto_6.Fecha_estimada_entrega) }} días</td>
                                                                <td>
                                                                    <span v-if="producto_6.Tipo_produccion == 1" class="text-secondary"> <i class="fa fa-circle"></i></span>
                                                                    <span v-if="producto_6.Tipo_produccion == 2" class="text-warning"> <i class="fa fa-circle"></i></span>
                                                                    <span v-if="producto_6.Tipo_produccion == 3" class="text-info"> <i class="fa fa-circle"></i></span>
                                                                </td>
                                                                <td>
                                                                    <h4> {{producto_6.Nombre_producto}}</h4>
                                                                </td>
                                                                <td> {{producto_6.Identificador_venta}} </td>
                                                                <td> {{producto_6.Nombre_cliente}} </td>
                                                                <td> {{producto_6.S_6_Requerimientos}} </td>
                                                                <td> {{producto_6.S_5_Fecha_finalizado | Fecha}} </td>
                                                                <td> {{producto_6.S_6_Fecha_finalizado | Fecha}} </td>
                                                                <td> {{diferenciasEntre_fechas(producto_6.S_5_Fecha_finalizado, producto_6.S_6_Fecha_finalizado)}} días </td>
                                                                <td> {{producto_6.S_6_Observaciones}} </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p align="center">
                                    <span class="text-secondary"><i class="fa fa-circle"></i> Normal</span>
                                    <span class="text-warning"><i class="fa fa-circle"></i> Reclamo</span>
                                    <span class="text-info"><i class="fa fa-circle"></i> Muestra</span>
                                </p>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
    >
    <!-- Modal PASO A PASO PRODUCTO-->
    <div class="modal fade" id="modalPasoapaso" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Avanzar producto a siguiente etapa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="horizontal-form">
                        <form class="form-horizontal" action="post" v-on:submit.prevent="cambiarEstadoProducto()">
                            <!--  
                            <pre>{{productoPasoData}}</pre> -->
                            <div class="form-group">
                                <label class=" form-control-label">Fecha del movimiento</label>
                                <input type="date" class="form-control" v-model="productoPasoData.Fecha" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Comentarios de esta etapa</label>
                                <textarea class="form-control" rows="5" placeholder="" v-model="productoPasoData.Comentarios"></textarea>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Avanzar a siguiente etapa</button>
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
    <!-- END MAIN CONTENT-->
    <!-- END PAGE CONTAINER-->
    <?php
    // CABECERA
    include "footer.php";
    ?>
    </body>

    </html>
    <!-- end document-->