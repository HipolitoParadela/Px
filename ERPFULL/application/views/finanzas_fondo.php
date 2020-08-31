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
                    <div class="col-lg-2">
                        <div class="card">
                            <div class="card-header">
                                <h4>Fondo total</h4>
                            </div>
                            <div class="card-body">
                                <div class="location text-sm-center">
                                    <h2> {{listaMovimientosEfectivo.Total + listaMovimientosCheques.Total + listaMovimientosTransferencias.Total | Moneda}}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4>Efectivo disponible</h4>
                            </div>
                            <div class="card-body">
                                <div class="location text-sm-center">
                                    <h2> {{listaMovimientosEfectivo.Total | Moneda}}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4>Monto para transferencias</h4>
                            </div>
                            <div class="card-body">
                                <div class="location text-sm-center">
                                    <h2> {{listaMovimientosTransferencias.Total | Moneda}}</h2>
                                </div>
                            </div>

                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4>Monto en cheques disponible</h4>
                            </div>
                            <div class="card-body">
                                <div class="location text-sm-center">
                                    <h2> {{listaMovimientosCheques.Total | Moneda}}</h2>
                                </div>
                            </div>
                        </div>

                        <a href="#modalRegistrarCheque" data-toggle="modal" title="Nuevo item" class="btn btn-warning btn-block" v-on:click="limpiarFormularioCheques()">
                            <i class="ti-plus"></i> Registrar cheque
                        </a>
                    </div>

                    <!-- SECCION FICHA cliente -->
                    <div class="col-lg-10">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Fechas a consultar</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <button class="btn btn-secondary btn-block" v-on:click="consultarMovimientos(null, null, 0)"> Desde siempre</button>
                                            </div>
                                            <div class="col-lg-3">
                                                <select class="form-control" v-model="Filtro_periodo" v-on:change="consultarMovimientos(null, null, Filtro_periodo)">
                                                    <option value="0">Todos los periodos</option>
                                                    <option v-for="periodo in listaPeriodos.Datos" v-bind:value="periodo.Id">{{periodo.Identificador}}</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-2">
                                                <p align="right">Seleccionar fechas a consultar</p>
                                            </div>
                                            <div class="col-lg-2">
                                                <input type="date" class="form-control" v-model="Filtro_fecha_inicial">
                                            </div>
                                            <div class="col-lg-2">
                                                <input type="date" class="form-control" v-model="Filtro_fecha_final" :disabled="Filtro_fecha_inicial == null" v-on:change="consultarMovimientos(Filtro_fecha_inicial, Filtro_fecha_final, 0)">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 1 }" href="#" v-on:click.prevent="mostrar = 1">Movimientos efectivo</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 2 }" href="#" v-on:click.prevent="mostrar = 2">Movimientos en transferencias</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 3 }" href="#" v-on:click.prevent="mostrar = 3">Movimientos en cheques</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 4 }" href="#" v-on:click.prevent="mostrar = 4">Cheques en caja</a>
                            </li>
                        </ul>
                        <!-- SECCION MOVIMIENTOS EN EFECTIVO -->
                        <div class="row" v-show="mostrar == '1'">
                            <div class="col-lg-12">

                                <div class="card">
                                    <div class="card-header">
                                        <strong>Efectivo</strong>
                                    </div>
                                    <div class="card-body">
                                        <a href="#modalEfectivo" data-toggle="modal" title="Nuevo item" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="limpiarFormularioMovimiento()">
                                            <i class="ti-plus"></i> Registrar movimiento genérico en efectivo
                                        </a>
                                        <div class="bootstrap-data-table-panel col-lg-12">
                                            <div class="table-responsive">
                                                <table id="table2excel" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Origen</th>
                                                            <th style="background-color:darkseagreen">Ingreso</th>
                                                            <th style="background-color:lightpink">Egreso</th>
                                                            <th>Fecha</th>
                                                            <th>Observaciones</th>
                                                            <th>Usuario</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(movimiento, index) in listaMovimientosEfectivo.Datos" v-show="(pag_efectivo - 1) * NUM_RESULTS <= index  && pag_efectivo * NUM_RESULTS > index">
                                                            <td>{{movimiento.Origen_movimiento}}</td>
                                                            <td align="right" style="background-color:darkseagreen">
                                                                <span v-if="movimiento.Op == 1">
                                                                    <b>{{movimiento.Monto | Moneda}}</b>
                                                                </span>
                                                            </td>
                                                            <td align="right" style="background-color:lightpink">
                                                                <span v-if="movimiento.Op == 0">
                                                                    <b>{{movimiento.Monto | Moneda}}</b>
                                                                </span>
                                                            </td>
                                                            <td>{{movimiento.Fecha_ejecutado | Fecha}}</td>
                                                            <td>{{movimiento.Observaciones | Recortar}} -
                                                                <button class="item" v-on:click="infoEtapa(movimiento.Observaciones)" data-toggle="modal" data-target="#modalObservaciones" data-placement="top" title="Ver observaciones">
                                                                    <i class="fa fa-exclamation-circle"></i>
                                                                </button>
                                                            </td>
                                                            <td>{{movimiento.Nombre}}</td>
                                                            <td>
                                                                <button v-on:click="desactivarAlgo(movimiento.Id, 'tbl_dinero_efectivo')" class="item" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                                                    <i class="zmdi zmdi-delete"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th></th>
                                                            <th style="background-color:darkseagreen">
                                                                <h4 align="right"> {{listaMovimientosEfectivo.Entrante | Moneda}}</h4>
                                                            </th>
                                                            <th style="background-color:lightpink">
                                                                <h4 align="right"> {{listaMovimientosEfectivo.Saliente | Moneda}}</h4>
                                                            </th>
                                                            <th style="background-color:wheat">
                                                                <h3 align="right"> {{listaMovimientosEfectivo.Total | Moneda}}</h3>
                                                            </th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-8">
                                            </div>

                                            <div class="col-lg-2">
                                                <nav aria-label="Page navigation" class="text-center">
                                                    <hr>
                                                    <ul class="pagination text-center">
                                                        <li>
                                                            <a href="#" class="btn btn-secondary btn-sm" aria-label="Previous" v-show="pag_efectivo != 1" @click.prevent="pag_efectivo -= 1">
                                                                <span aria-hidden="true">Anterior</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="btn btn-secondary btn-sm" aria-label="Next" v-show="pag_efectivo * NUM_RESULTS / listaMovimientosEfectivo.Datos.length < 1" @click.prevent="pag_efectivo += 1">
                                                                <span aria-hidden="true">Siguiente</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </nav>
                                            </div>
                                            <div class="col-lg-2">
                                                <nav aria-label="Page navigation" class="text-center">
                                                    <hr>
                                                    <ul class="pagination text-center">
                                                        <li>
                                                            <select class="form-control" v-model="NUM_RESULTS">
                                                                <option value="10">10</option>
                                                                <option value="20">20</option>
                                                                <option value="50">50</option>
                                                                <option value="100">100</option>
                                                                <option value="500">500</option>
                                                            </select>
                                                        </li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECCION MOVIMIENTOS EN TRANSFERENCIAS-->
                        <div class="row" v-show="mostrar == '2'">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Transferencias</strong>
                                    </div>
                                    <div class="card-body">
                                        <a href="#modalTransferencia" data-toggle="modal" title="Nuevo item" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="limpiarFormularioMovimiento()">
                                            <i class="ti-plus"></i> Registrar movimiento genérico con transferencia
                                        </a>
                                        <div class="bootstrap-data-table-panel col-lg-12">
                                            <div class="table-responsive">
                                                <table id="table2excel" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Origen</th>
                                                            <th style="background-color:darkseagreen">Monto Ingreso</th>
                                                            <th style="background-color:lightpink">Monto Egreso</th>
                                                            <th style="background-color:lightpink">Ing.Brutos</th>
                                                            <th>Fecha</th>
                                                            <th>Observaciones</th>
                                                            <th>Usuario</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(mov_trans, index) in listaMovimientosTransferencias.Datos" v-show="(pag_trans - 1) * NUM_RESULTS <= index  && pag_trans * NUM_RESULTS > index">
                                                            <td>{{mov_trans.Origen_movimiento}}</td>
                                                            <td align="right" style="background-color:darkseagreen">
                                                                <span v-if="mov_trans.Op == 1">
                                                                    <b>{{mov_trans.Monto_bruto | Moneda}}</b>
                                                                </span>
                                                            </td>
                                                            <td align="right" style="background-color:lightpink">
                                                                <span v-if="mov_trans.Op == 0">
                                                                    <b>{{mov_trans.Monto_bruto | Moneda}}</b>
                                                                </span>
                                                            </td>
                                                            <td align="right" style="background-color:lightpink"><b>{{mov_trans.Retencion_ing_brutos | Moneda}}</b></td>
                                                            <td>{{mov_trans.Fecha_ejecutado | Fecha}}</td>
                                                            <td>{{mov_trans.Observaciones | Recortar}} -
                                                                <button class="item" v-on:click="infoEtapa(mov_trans.Observaciones)" data-toggle="modal" data-target="#modalObservaciones" data-placement="top" title="Ver observaciones">
                                                                    <i class="fa fa-exclamation-circle"></i>
                                                                </button>
                                                            </td>
                                                            <td>{{mov_trans.Nombre}}</td>
                                                            <td>
                                                                <button v-on:click="desactivarAlgo(mov_trans.Id, 'tbl_dinero_transferencias')" class="item" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                                                    <i class="zmdi zmdi-delete"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th></th>
                                                            <th style="background-color:darkseagreen">
                                                                <h4 align="right"> {{listaMovimientosTransferencias.Montos_entrantes | Moneda}}</h4>
                                                            </th>
                                                            <th style="background-color:lightpink">
                                                                <h4 align="right"> {{listaMovimientosTransferencias.Montos_salientes | Moneda}}</h4>
                                                            </th>
                                                            <th style="background-color:lightpink">
                                                                <h4 align="right"> {{listaMovimientosTransferencias.Ing_brutos | Moneda}}</h4>
                                                            </th>
                                                            <th style="background-color:wheat">
                                                                <h3 align="right"> {{listaMovimientosTransferencias.Total | Moneda}}</h3>
                                                            </th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                    </tfoot>
                                                </table>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-8">
                                            </div>

                                            <div class="col-lg-2">
                                                <nav aria-label="Page navigation" class="text-center">
                                                    <hr>
                                                    <ul class="pagination text-center">
                                                        <li>
                                                            <a href="#" class="btn btn-secondary btn-sm" aria-label="Previous" v-show="pag_trans != 1" @click.prevent="pag_trans -= 1">
                                                                <span aria-hidden="true">Anterior</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="btn btn-secondary btn-sm" aria-label="Next" v-show="pag_trans * NUM_RESULTS / listaMovimientosTransferencias.Datos.length < 1" @click.prevent="pag_trans += 1">
                                                                <span aria-hidden="true">Siguiente</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </nav>
                                            </div>
                                            <div class="col-lg-2">
                                                <nav aria-label="Page navigation" class="text-center">
                                                    <hr>
                                                    <ul class="pagination text-center">
                                                        <li>
                                                            <select class="form-control" v-model="NUM_RESULTS">
                                                                <option value="10">10</option>
                                                                <option value="20">20</option>
                                                                <option value="50">50</option>
                                                                <option value="100">100</option>
                                                                <option value="500">500</option>
                                                            </select>
                                                        </li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECCION MOVIMIENTOS EN CHEQUES -->
                        <div class="row" v-show="mostrar == '3'">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Movimientos en cheques</strong>
                                    </div>
                                    <div class="card-body">
                                        <a href="#modalCheques" data-toggle="modal" title="Nuevo item" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="limpiarFormularioMovimiento()">
                                            <i class="ti-plus"></i> Registrar movimiento genérico en cheques
                                        </a>
                                        <div class="bootstrap-data-table-panel col-lg-12">

                                            <!-- Crear enlace para ver información del cheque, o en lo posible traer más info de la operación -->

                                            <div class="table-responsive">
                                                <table id="table2excel" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Tipo</th>
                                                            <th>Origen</th>
                                                            <th style="background-color:darkseagreen">Ingreso</th>
                                                            <th style="background-color:lightpink">Egreso</th>
                                                            <th>Venc.</th>
                                                            <th>Entrego</th>
                                                            <th>N° cheque</th>
                                                            <th>Banco</th>
                                                            <th>Imagen</th>
                                                            <th>F. Operación</th>
                                                            <th>Observaciones</th>
                                                            <th>Usuario</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(mov_cheque, index) in listaMovimientosCheques.Datos" v-show="(pag_cheques - 1) * NUM_RESULTS <= index  && pag_cheques * NUM_RESULTS > index">
                                                            <td v-if="mov_cheque.Tipo == 1">Terceros</td>
                                                            <td v-if="mov_cheque.Tipo == 0">Propio</td>
                                                            <td>{{mov_cheque.Origen_movimiento}}</td>
                                                            <td align="right" style="background-color:darkseagreen">
                                                                <span v-if="mov_cheque.Op == 1">
                                                                    <b>{{mov_cheque.Monto | Moneda}}</b>
                                                                </span>
                                                            </td>
                                                            <td align="right" style="background-color:lightpink">
                                                                <span v-if="mov_cheque.Op == 0">
                                                                    <b>{{mov_cheque.Monto}}</b>
                                                                </span>
                                                            </td>
                                                            <td>{{mov_cheque.Vencimiento | Fecha}}</td>
                                                            <td>{{mov_cheque.Nombre_entrega}}</td>
                                                            <td>{{mov_cheque.Numero_cheque}}</td>
                                                            <td>{{mov_cheque.Banco}}</td>
                                                            <td><a v-if="mov_cheque.Imagen != null" target="_blank" v-bind:href="'<?php echo base_url(); ?>uploads/imagenes/'+mov_cheque.Imagen"> Ver imagen</a></td>
                                                            <td>{{mov_cheque.Fecha_ejecutado | Fecha}}</td>
                                                            <td>{{mov_cheque.Observaciones | Recortar}} -
                                                                <button class="item" v-on:click="infoEtapa(mov_cheque.Observaciones)" data-toggle="modal" data-target="#modalObservaciones" data-placement="top" title="Ver observaciones">
                                                                    <i class="fa fa-exclamation-circle"></i>
                                                                </button>
                                                            </td>
                                                            <td>{{mov_cheque.Nombre}}</td>
                                                            <td>
                                                                <button v-on:click="desactivarAlgo(mov_cheque.Movimiento_id, 'tbl_dinero_cheques')" class="item" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                                                    <i class="zmdi zmdi-delete"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                            <th style="background-color:darkseagreen">
                                                                <h4 align="right">{{listaMovimientosCheques.Entrante | Moneda}}</h4>
                                                            </th>
                                                            <th style="background-color:lightpink">
                                                                <h4 align="right">{{listaMovimientosCheques.Saliente | Moneda}}</h4>
                                                            </th>
                                                            <th style="background-color:wheat">
                                                                <h3 align="right">{{listaMovimientosCheques.Total | Moneda}}</h3>
                                                            </th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-8">
                                            </div>

                                            <div class="col-lg-2">
                                                <nav aria-label="Page navigation" class="text-center">
                                                    <hr>
                                                    <ul class="pagination text-center">
                                                        <li>
                                                            <a href="#" class="btn btn-secondary btn-sm" aria-label="Previous" v-show="pag_cheques != 1" @click.prevent="pag_cheques -= 1">
                                                                <span aria-hidden="true">Anterior</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="btn btn-secondary btn-sm" aria-label="Next" v-show="pag_cheques * NUM_RESULTS / listaMovimientosCheques.Datos.length < 1" @click.prevent="pag_cheques += 1">
                                                                <span aria-hidden="true">Siguiente</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </nav>
                                            </div>
                                            <div class="col-lg-2">
                                                <nav aria-label="Page navigation" class="text-center">
                                                    <hr>
                                                    <ul class="pagination text-center">
                                                        <li>
                                                            <select class="form-control" v-model="NUM_RESULTS">
                                                                <option value="10">10</option>
                                                                <option value="20">20</option>
                                                                <option value="50">50</option>
                                                                <option value="100">100</option>
                                                                <option value="500">500</option>
                                                            </select>
                                                        </li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECCION CHEQUES -->
                        <div class="row" v-show="mostrar == '4'">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Cheques en fecha de cobro</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="bootstrap-data-table-panel col-lg-12">
                                            <div class="table-responsive">
                                                <table id="table2excel" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Tipo</th>
                                                            <th style="background-color:darkseagreen">Monto</th>
                                                            <th style="background-color:lightpink">Vencido hace</th>
                                                            <th>F. Venc.</th>
                                                            <th>Entrego</th>
                                                            <th>Número de cheque</th>
                                                            <th>Banco</th>
                                                            <th>Imagen</th>
                                                            <th>F. cargado</th>
                                                            <th>Estado</th>
                                                            <!-- <th>Cargado por</th> -->
                                                            <th>Observaciones</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="cheque in listaChequesDiferenciados.Vencidos">
                                                            <td v-if="cheque.Tipo == 1">Terceros</td>
                                                            <td v-if="cheque.Tipo == 0">Propio</td>
                                                            <td style="background-color:darkseagreen">
                                                                <h4 align="center"> {{cheque.Monto | Moneda}}</h4>
                                                            </td>
                                                            <td align="center" style="background-color:lightpink"><b> {{diferenciasEntre_fechas(cheque.Vencimiento, null)}}</b></td>
                                                            <td>{{cheque.Vencimiento | Fecha}}</td>
                                                            <td>{{cheque.Nombre_entrega}}</td>

                                                            <td>{{cheque.Numero_cheque}}</td>
                                                            <td>{{cheque.Banco}}</td>
                                                            <td><a v-if="cheque.Imagen != null" target="_blank" v-bind:href="'<?php echo base_url(); ?>uploads/imagenes/'+cheque.Imagen"> Ver imagen</a></td>
                                                            <td>{{cheque.Fecha_cargado | Fecha}}</td>
                                                            <td v-if="cheque.Estado == 1">En caja</td>
                                                            <td v-if="cheque.Estado == 2">Entregado</td>
                                                            <!-- <td>{{cheque.Usuario_id}}</td> -->
                                                            <td>{{cheque.Observaciones}}</td>
                                                            <td>
                                                                <a href="#modalRegistrarCheque" data-toggle="modal" v-on:click="editarFormularioCheque(cheque)">
                                                                    <i class="fa fa-edit"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Cheques con fecha de cobro dentro de 30 días</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="bootstrap-data-table-panel col-lg-12">
                                            <div class="table-responsive">
                                                <table id="table2excel" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Tipo</th>
                                                            <th style="background-color:darkseagreen">Monto</th>
                                                            <th style="background-color:wheat">Vence en</th>
                                                            <th>F. Venc.</th>
                                                            <th>Entrego</th>
                                                            <th>Número de cheque</th>
                                                            <th>Banco</th>
                                                            <th>Imagen</th>
                                                            <th>F. cargado</th>
                                                            <th>Estado</th>
                                                            <!-- <th>Cargado por</th> -->
                                                            <th>Observaciones</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="cheque in listaChequesDiferenciados.Cerca">
                                                            <td v-if="cheque.Tipo == 1">Terceros</td>
                                                            <td v-if="cheque.Tipo == 0">Propio</td>
                                                            <td style="background-color:darkseagreen">
                                                                <h4 align="center"> {{cheque.Monto | Moneda}}</h4>
                                                            </td>
                                                            <td align="center" style="background-color:wheat"><b>{{diferenciasEntre_fechas(null, cheque.Vencimiento)}}</b></td>
                                                            <td>{{cheque.Vencimiento | Fecha}}</td>
                                                            <td>{{cheque.Nombre_entrega}}</td>
                                                            <td>{{cheque.Numero_cheque}}</td>
                                                            <td>{{cheque.Banco}}</td>
                                                            <td><a v-if="cheque.Imagen != null" target="_blank" v-bind:href="'<?php echo base_url(); ?>uploads/imagenes/'+cheque.Imagen"> Ver imagen</a></td>
                                                            <td>{{cheque.Fecha_cargado | Fecha}}</td>
                                                            <td v-if="cheque.Estado == 1">En caja</td>
                                                            <td v-if="cheque.Estado == 2">Entregado</td>
                                                            <!-- <td>{{cheque.Usuario_id}}</td> -->
                                                            <td>{{cheque.Observaciones}}</td>
                                                            <td>
                                                                <a href="#modalRegistrarCheque" data-toggle="modal" v-on:click="editarFormularioCheque(cheque)">
                                                                    <i class="fa fa-edit"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Cheques con fecha de cobro en más de 30 días</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="bootstrap-data-table-panel col-lg-12">
                                            <div class="table-responsive">
                                                <table id="table2excel" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Tipo</th>
                                                            <th style="background-color:darkseagreen">Monto</th>
                                                            <th style="background-color:wheat">Vence en</th>
                                                            <th>F. Venc</th>
                                                            <th>Entrego</th>
                                                            <th>Número de cheque</th>
                                                            <th>Banco</th>
                                                            <th>Imagen</th>
                                                            <th>F. cargado</th>
                                                            <th>Estado</th>
                                                            <!-- <th>Cargado por</th> -->
                                                            <th>Observaciones</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="cheque in listaChequesDiferenciados.Lejos">
                                                            <td v-if="cheque.Tipo == 1">Terceros</td>
                                                            <td v-if="cheque.Tipo == 0">Propio</td>
                                                            <td style="background-color:darkseagreen">
                                                                <h4 align="center"> {{cheque.Monto | Moneda}}</h4>
                                                            </td>
                                                            <td align="center" style="background-color:wheat">{{diferenciasEntre_fechas(null, cheque.Vencimiento)}}</td>
                                                            <td>{{cheque.Vencimiento | Fecha}}</td>
                                                            <td>{{cheque.Nombre_entrega}}</td>
                                                            <td>{{cheque.Numero_cheque}}</td>
                                                            <td>{{cheque.Banco}}</td>
                                                            <td><a v-if="cheque.Imagen != null" target="_blank" v-bind:href="'<?php echo base_url(); ?>uploads/imagenes/'+cheque.Imagen"> Ver imagen</a></td>
                                                            <td>{{cheque.Fecha_cargado | Fecha}}</td>
                                                            <td v-if="cheque.Estado == 1">En caja</td>
                                                            <td v-if="cheque.Estado == 2">Entregado</td>
                                                            <!-- <td>{{cheque.Usuario_id}}</td> -->
                                                            <td>{{cheque.Observaciones}}</td>
                                                            <td>
                                                                <a href="#modalRegistrarCheque" data-toggle="modal" v-on:click="editarFormularioCheque(cheque)">
                                                                    <i class="fa fa-edit"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal efectivo-->
    <div class="modal fade" id="modalEfectivo" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="1">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalItemsFoto">Ingresar movimiento de efectivo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="1">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="horizontal-form">
                        <form class="form-horizontal" action="post" v-on:submit.prevent="crear_movimiento_efectivo()">
                            <div class="form-group">
                                <label class="control-label">Seleccionar Periodo</label>
                                <select class="form-control" v-model="movimientoDatos.Periodo_id" required>
                                    <option v-for="periodo in listaPeriodos.Datos" v-bind:value="periodo.Id">{{periodo.Identificador}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Tipo de movimiento</label>
                                <select class="form-control" v-model="movimientoDatos.Op" required>
                                    <option value="0">Egreso</option>
                                    <option value="1">Ingreso</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Categoría de movimiento</label>
                                <select class="form-control" v-model="movimientoDatos.Origen_movimiento" required>
                                    <option value="S/C">Sin categoría</option>
                                    <option value="Prestamo">Prestamo</option>
                                    <option value="Sueldos">Sueldos</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Monto</label>
                                <input type="number" class="form-control" v-model="movimientoDatos.Monto" required>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Fecha en que se realizó el movimiento</label>
                                <input type="date" class="form-control" v-model="movimientoDatos.Fecha_ejecutado" required>
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Observaciones</label>
                                <textarea class="form-control" rows="5" v-model="movimientoDatos.Observaciones"></textarea>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-12">
                                    <button type="submit" class="btn btn-success">{{texto_boton}}</button>
                                </div>
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
    <!-- Modal transferencias-->
    <div class="modal fade" id="modalTransferencia" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="1">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalItemsFoto">Ingresar transferencia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="1">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="horizontal-form">
                        <form class="form-horizontal" action="post" v-on:submit.prevent="crear_movimiento_transferencias()">
                            <div class="form-group">
                                <label class="control-label">Seleccionar Periodo</label>
                                <select class="form-control" v-model="movimientoDatos.Periodo_id" required>
                                    <option v-for="periodo in listaPeriodos.Datos" v-bind:value="periodo.Id">{{periodo.Identificador}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Tipo de movimiento</label>
                                <select class="form-control" v-model="movimientoDatos.Op" required>
                                    <option value="0">Egreso</option>
                                    <option value="1">Ingreso</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Categoría de movimiento</label>
                                <select class="form-control" v-model="movimientoDatos.Origen_movimiento" required>
                                    <option value="S/C">Sin categoría</option>
                                    <option value="Prestamo">Prestamo</option>
                                    <option value="Sueldos">Sueldos</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label"> Monto transferido </label>
                                <input type="number" class="form-control" v-model="movimientoDatos.Monto_bruto" required><br>
                                <span class="text-danger" v-show="movimientoDatos.Op == 1"> | <b>Se descontarán automaticamente {{movimientoDatos.Monto_bruto * 0.03}}</b></span>
                                <hr>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Fecha en que se realizó el movimiento</label>
                                <input type="date" class="form-control" v-model="movimientoDatos.Fecha_ejecutado" required>
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Observaciones</label>
                                <textarea class="form-control" rows="5" v-model="movimientoDatos.Observaciones"></textarea>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-12">
                                    <button type="submit" class="btn btn-success">{{texto_boton}}</button>
                                </div>
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
    <!-- Modal cheques-->
    <div class="modal fade" id="modalCheques" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="1">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalItemsFoto">Ingresar movimiento de cheque</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="1">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="horizontal-form">
                        <form class="form-horizontal" action="post" v-on:submit.prevent="crear_movimiento_cheques()">
                            <div class="form-group">
                                <label class="control-label">Seleccionar Periodo</label>
                                <select class="form-control" v-model="movimientoDatos.Periodo_id" required>
                                    <option v-for="periodo in listaPeriodos.Datos" v-bind:value="periodo.Id">{{periodo.Identificador}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Tipo de movimiento</label>
                                <select class="form-control" v-model="movimientoDatos.Op" required>
                                    <option value="0">Egreso</option>
                                    <option value="1">Ingreso</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Categoría de movimiento</label>
                                <select class="form-control" v-model="movimientoDatos.Origen_movimiento" required>
                                    <option value="S/C">Sin categoría</option>
                                    <option value="Prestamo">Prestamo</option>
                                    <option value="Sueldos">Sueldos</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Seleccionar cheque</label>
                                <input list="listacheques" class="form-control" v-model="movimientoDatos.Cheque_id" required>
                                <datalist id="listacheques">
                                    <option v-for="cheq in listaCheques" v-bind:value="cheq.Id">De {{cheq.Nombre_entrega}} | {{cheq.Monto}} | Venc: {{cheq.Vencimiento | Fecha}}</option>
                                </datalist>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Fecha en que se realizó el movimiento</label>
                                <input type="date" class="form-control" v-model="movimientoDatos.Fecha_ejecutado" required>
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Observaciones</label>
                                <textarea class="form-control" rows="5" v-model="movimientoDatos.Observaciones"></textarea>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-12">
                                    <button type="submit" class="btn btn-success">{{texto_boton}}</button>
                                </div>
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
    <!-- Modal Registrar cheque-->
    <div class="modal fade" id="modalRegistrarCheque" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="1">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalItemsFoto">Registrar un nuevo cheque</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="1">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="horizontal-form">
                        <form class="form-horizontal" enctype="multipart/form-data" action="post" v-on:submit.prevent="crearCheque()">
                            <!--   -->
                            <div class="form-group">
                                <label class="control-label">Tipo </label>
                                <select class="form-control" v-model="chequeData.Tipo" required>
                                    <option value="0">Propio</option>
                                    <option value="1">Tercero</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Nombre persona que lo entrega</label>
                                <input type="text" class="form-control" v-model="chequeData.Nombre_entrega" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Monto</label>
                                <input type="number" class="form-control" v-model="chequeData.Monto" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Número de cheque</label>
                                <input type="number" class="form-control" v-model="chequeData.Numero_cheque" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Banco</label>
                                <input type="text" class="form-control" v-model="chequeData.Banco" required>
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Vencimiento</label>
                                <input type="date" class="form-control" v-model="chequeData.Vencimiento">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Observaciones</label>
                                <textarea class="form-control" rows="5" v-model="chequeData.Observaciones"></textarea>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <input @change="archivoSeleccionado" type="file" class="form-control" name="Imagen">
                                </div>
                                <div class="col-sm-12" v-if="chequeData.Imagen != null">
                                    Archivo previamente cargado
                                    <a target="_blank" v-bind:href="'<?php echo base_url(); ?>uploads/imagenes/'+chequeData.Imagen"> Ver archivo</a>
                                </div>
                            </div>
                            <div class="form-group" v-show="preloader == 1">
                                <p align="center">
                                    EL ARCHIVO SE ESTA CARGANDO. <br> No cerrar la ventana hasta finalizada la carga, dependiendo del peso del archivo puede demorar algunos minutos.
                                </p>
                                <p align="center">
                                    <img src="http://grupopignatta.com.ar/images/preloader.gif" alt="">
                                </p>
                            </DIV>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success" :disabled="preloader == 1">{{texto_boton}}</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" :disabled="preloader == 1">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal OBSERVACIONES DE LOS PAGOS-->
    <div class="modal fade" id="modalObservaciones" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Observaciones</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4 v-if="infoModal.Observaciones != null">{{infoModal.Observaciones}}</h4>
                    <h4 v-else><em>No se han registrado observaciones para este movimiento</em></h4>
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