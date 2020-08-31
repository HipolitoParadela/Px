<?php
// CABECERA
include "head.php";
// TOP
include "top.php";
include "menusidebar.php";
?>
<!-- PAGE CONTAINER-->
<div class="page-container" id="periodos">
    <!-- HEADER DESKTOP-->
    <?PHP include "header_desktop.php"; ?>
    <!-- HEADER DESKTOP-->

    <!-- MAIN CONTENT-->
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="row">


                    <!-- SECCION -->
                    <div class="col-lg-12">

                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 1 }" href="#" v-on:click="mostrar = 1">Periodo</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 2 }" href="#" v-on:click="getListaSuscripciones()">Cobros periodo {{periodoDatos.Identificador}}</a>
                            </li>
                        </ul>

                        <!-- SECCION DATOS PREVISION -->
                        <div class="row" v-show="mostrar == '1'">
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Info del periodo</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="horizontal-form">
                                            <form class="form-horizontal" action="post" v-on:submit.prevent="crearcompra()">
                                                <div class="horizontal-form">
                                                    <div class="form-group">
                                                        <label class=" form-control-label">Identificador</label>
                                                        <input type="text" class="form-control" v-model="periodoDatos.Identificador" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class=" form-control-label">Fecha en que inicia</label>
                                                        <input type="date" class="form-control" v-model="periodoDatos.Fecha_inicio" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class=" form-control-label">Fecha en que finaliza </label>
                                                        <input type="date" class="form-control" v-model="periodoDatos.Fecha_cierre" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class=" form-control-label">Observaciones iniciales</label>
                                                        <textarea class="form-control" rows="5" v-model="periodoDatos.Observaciones_iniciales"></textarea>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">{{texto_boton}}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Saldo a cobrar</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="location text-sm-center">
                                            <h2>
                                                $ {{ totalCobrosSuscripcionPeriodo - sumarMontosSuscripciones(listaSuscripciones) | Moneda}}
                                            </h2>
                                            <em></em>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Ingresos a la fecha</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="location text-sm-center">
                                            <h2>$ {{totalCobrosSuscripcionPeriodo | Moneda}}</h2>
                                            <em>Ingresos por suscripciones en este periodo</em>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Ingresos estimados</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="location text-sm-center">
                                            <h2>{{sumarMontosSuscripciones(listaSuscripciones) | Moneda}}</h2>
                                            <em>Ingresos totales estimados para este periodo</em>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- SECCION COBROS -->
                        <div class="row" v-show="mostrar == '2'">
                            <div class="col-lg-7">
                                <div class="card">
                                    <!-- style="max-height: 1000px; overflow-y: scroll;" -->

                                    <div class="card-header">
                                        <strong>Suscripciones</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="bootstrap-data-table-panel col-lg-12">

                                            <div class="table-responsive">
                                                <table id="table2excel" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Servicio</th>
                                                            <th>Cliente</th>
                                                            <th style="background-color:wheat">Monto</th>
                                                            <th style="background-color:wheat">Int</th>
                                                            <th style="background-color:lightsteelblue">Abonado</th>
                                                            <th>Est.</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="cobro in listaSuscripciones">
                                                            <td>
                                                                <h4> {{cobro.Datos_suscripcion.Titulo_suscripcion }}</h4>
                                                            </td>
                                                            <td>{{cobro.Datos_suscripcion.Nombre_cliente}}</td>
                                                            <td style="background-color:wheat" v-if="cobro.Estado < 1">
                                                                ...
                                                            </td>
                                                            <td style="background-color:wheat" align="right" v-if="cobro.Estado > 0">
                                                                <b>$ {{cobro.Datos_cobros[0].Valor_cobro | Moneda}}</b>
                                                            </td>
                                                            <td style="background-color:wheat" v-if="cobro.Estado < 1">
                                                                ...
                                                            </td>
                                                            <td style="background-color:wheat" align="right" v-if="cobro.Estado > 0">
                                                                <b>$ {{cobro.Datos_cobros[0].Valor_interes | Moneda}} </b>
                                                            </td>
                                                            <td style="background-color:lightsteelblue" align="right"> <b> $ {{cobro.Total_abonado}}</b></td>
                                                            <td align="center" v-if="cobro.Estado == 0"><span class="text-dark"> <i class="fa fa-circle"></i></span></td>
                                                            <td align="center" v-if="cobro.Estado == 1"><span class="text-danger"> <i class="fa fa-circle"></i></span></td>
                                                            <td align="center" v-if="cobro.Estado == 2"><span class="text-warning"> <i class="fa fa-circle"></i></span> </td>
                                                            <td align="center" v-if="cobro.Estado == 3"><span class="text-success"> <i class="fa fa-circle"></i></span></td>
                                                            <td>
                                                                <div class="table-data-feature">
                                                                    <button class="item" v-on:click="cobroSeleccionado(cobro)" title="Gestionar Cobro">
                                                                        <i class="zmdi zmdi-edit"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th></th>
                                                            <!-- <th>Manzana</th> -->
                                                            <th></th>
                                                            <th style="background-color:wheat" colspan="2">
                                                                <h4 align="center">{{sumarMontosSuscripciones(listaSuscripciones) | Moneda}}</h4>
                                                            </th>
                                                            <th style="background-color:lightsteelblue">
                                                                <h4 align="center">{{sumarPagos(listaSuscripciones) | Moneda}}</h4>
                                                            </th>
                                                            <!-- <th style="background-color:lightsteelblue">Saldo</th> -->
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                                <!-- <pre>
                                                {{listaSuscripciones}}
                                                </pre> -->
                                            </div>
                                        </div>
                                    </div>
                                </DIV>
                            </DIV>
                            <div class="col-lg-5">
                                <div style="position: fixed; overflow-y: auto; overflow-x:hidden; max-height: 900px;">
                                    <div class="card">
                                        <div class="card-header">
                                            <strong>Pago de {{suscripcionDatos.Titulo_suscripcion}}</strong>
                                        </div>
                                        <div class="card-body">
                                            <div class="horizontal-form">
                                                <form class="form-horizontal" action="post" v-on:submit.prevent="crearCobro()">
                                                    <div class="horizontal-form">

                                                        <div class="row">
                                                            <div class="col-lg-3">
                                                                <div class="form-group">
                                                                    <label class=" form-control-label">Fecha de pago</label>
                                                                    <input type="date" class="form-control" v-model="cobrosSuscripcionDatos.Fecha_pago" require :disabled="cobrosSuscripcionDatos.Id == null">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <div class="form-group">
                                                                    <label class=" form-control-label">Monto</label>
                                                                    <input type="number" class="form-control" v-model="cobrosSuscripcionDatos.Valor_cobro" :disabled="cobrosSuscripcionDatos.Id == null" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <div class="form-group">
                                                                    <label class=" form-control-label">Monto intereses</label>
                                                                    <input type="number" class="form-control" v-model="cobrosSuscripcionDatos.Valor_interes" :disabled="cobrosSuscripcionDatos.Id == null" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <label class="form-control-label">Estado del pago</label>
                                                                <select class="form-control-sm form-control" v-model="cobrosSuscripcionDatos.Estado">

                                                                    <option value="1">Sin Pagos</option>
                                                                    <option value="2">Pago parcial</option>
                                                                    <option value="3">Pago Completo</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-offset-2 col-sm-12">
                                                                <button v-if="cobrosSuscripcionDatos.Id == null" type="submit" class="btn btn-success">Generar cobro</button>
                                                                <button v-else type="submit" class="btn btn-success">{{texto_boton}}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card" v-if="cobrosSuscripcionDatos.Id > 0">
                                        <div class="card-header">
                                            <strong>Saldo</strong>
                                        </div>
                                        <div class="card-body">
                                            <div class="bootstrap-data-table-panel col-lg-12">
                                                <div class="table-responsive">
                                                    <table id="table2excel" class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Monto Cobro</th>
                                                                <th>Monto abonado</th>
                                                                <th>Saldo</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <h3>
                                                                        $ {{ calcularTotalCobros(cobrosSuscripcionDatos.Valor_cobro, cobrosSuscripcionDatos.Valor_interes) | Moneda }}
                                                                    </h3>
                                                                </td>
                                                                <td>
                                                                    <h3>
                                                                        $ {{ Total_cheques + Total_efectivo + Total_transferencias | Moneda }}
                                                                    </h3>
                                                                </td>
                                                                <td>
                                                                    <h3>
                                                                        $ {{ calcularSaldoCobro(cobrosSuscripcionDatos.Valor_cobro, cobrosSuscripcionDatos.Valor_interes, Total_cheques, Total_efectivo, Total_transferencias) | Moneda }}
                                                                    </h3>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="card" v-if="cobrosSuscripcionDatos.Id > 0">
                                                <div class="card-header">
                                                    <strong>Pagos en efectivo</strong>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table id="table2excel" class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>
                                                                        <button data-toggle="modal" data-target="#modalEfectivo" v-on:click="limpiarFormularioMovimiento()">
                                                                            <i class="fa fa-plus-circle text-success"></i>
                                                                        </button>
                                                                    </th>
                                                                    <th>Monto</th>
                                                                    <th>Fecha</th>

                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr v-for="mov_efectivo in listaMovimientosEfectivo">
                                                                    <td>
                                                                        <button v-on:click="desactivarAlgo(mov_efectivo.Id, 'tbl_dinero_efectivo')" class="item" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                                                            <i class="zmdi zmdi-delete"></i>
                                                                        </button>
                                                                    </td>
                                                                    <td>$ {{mov_efectivo.Monto | Moneda}}</td>
                                                                    <td>{{mov_efectivo.Fecha_ejecutado | Fecha}}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="card" v-if="cobrosSuscripcionDatos.Id > 0">
                                                <div class="card-header">
                                                    <strong>Pagos con transferencia</strong>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table id="table2excel" class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>
                                                                        <button data-toggle="modal" data-target="#modalTransferencia" v-on:click="limpiarFormularioMovimiento()">
                                                                            <i class="fa fa-plus-circle text-success"></i>
                                                                        </button>
                                                                    </th>
                                                                    <th>Monto</th>
                                                                    <th>Fecha</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr v-for="mov_trans in listaMovimientosTransferencia">
                                                                    <td>
                                                                        <button v-on:click="desactivarAlgo(mov_trans.Id, 'tbl_dinero_transferencias')" class="item" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                                                            <i class="zmdi zmdi-delete"></i>
                                                                        </button>
                                                                    </td>
                                                                    <td>$ {{mov_trans.Monto_bruto | Moneda}}</td>
                                                                    <td>{{mov_trans.Fecha_ejecutado | Fecha}}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="card" v-if="cobrosSuscripcionDatos.Id > 0">
                                        <div class="card-header">
                                            <strong>Pagos con cheque</strong>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="table2excel" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                <button data-toggle="modal" data-target="#modalCheque" v-on:click="limpiarFormularioMovimiento()">
                                                                    <i class="fa fa-plus-circle text-success"></i>
                                                                </button>
                                                            </th>
                                                            <th>Monto</th>
                                                            <th>Entregado</th>
                                                            <th>Vencimiento</th>
                                                            <th>Información cheque</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="mov_cheques in listaMovimientosCheques">
                                                            <td>
                                                                <button v-on:click="desactivarAlgo(mov_cheques.Id, 'tbl_dinero_cheques')" class="item" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                                                    <i class="zmdi zmdi-delete"></i>
                                                                </button>
                                                            </td>
                                                            <td>${{mov_cheques.Monto | Moneda}}</td>
                                                            <td>{{mov_cheques.Fecha_ejecutado | Fecha}}</td>
                                                            <td>{{mov_cheques.Vencimiento | Fecha}}</td>
                                                            <td>
                                                                <a href="#" target="_blank">
                                                                    <span v-if="mov_cheques.Tipo == 0"> Tercero</span> <span v-if="mov_cheques.Tipo == 1">Propio</span> |
                                                                    {{mov_cheques.Nombre_entrega}} |
                                                                    {{mov_cheques.Numero_cheque}}
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
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

    <!-- END MAIN CONTENT-->
    <!-- END PAGE CONTAINER-->
    <!-- Modal efectivo-->
    <div class="modal fade" id="modalEfectivo" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalItemsFoto">Ingresar pago en efectivo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="horizontal-form">
                        <form class="form-horizontal" action="post" v-on:submit.prevent="crear_movimiento_efectivo()">
                            <div class="form-group">
                                <label class="control-label">Monto</label>
                                <input type="number" class="form-control" v-model="movimientoDatos.Monto" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Fecha en que se realizó el pago</label>
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
    <div class="modal fade" id="modalTransferencia" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalItemsFoto">Ingresar pago por transferencia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="horizontal-form">
                        <form class="form-horizontal" action="post" v-on:submit.prevent="crear_movimiento_transferencias()">
                            <div class="form-group">
                                <label class="control-label">Monto transferido</label>
                                <input type="number" class="form-control" v-model="movimientoDatos.Monto_bruto" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Fecha en que se realizó el pago</label>
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
    <div class="modal fade" id="modalCheque" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalItemsFoto">Ingresar pago en cheques</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="horizontal-form">
                        <form class="form-horizontal" action="post" v-on:submit.prevent="crear_movimiento_cheques()">
                            <div class="form-group">
                                <label class="control-label">Seleccionar cheque</label>
                                <input list="listacheques" class="form-control" v-model="movimientoDatos.Cheque_id" required>

                                <datalist id="listacheques">
                                    <option v-for="cheq in listaCheques" v-bind:value="cheq.Id">De {{cheq.Nombre_entrega}} | ${{cheq.Monto}} | Vencimiento: {{cheq.Vencimiento | Fecha}}</option>
                                </datalist>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Fecha en que se realizó el pago</label>
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


    <?php
    // CABECERA
    include "footer.php";
    ?>
    </body>

    </html>
    <!-- end document-->