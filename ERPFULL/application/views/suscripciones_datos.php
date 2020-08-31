<?php
// CABECERA
include "head.php";
// TOP
include "top.php";
include "menusidebar.php";
?>
<!-- PAGE CONTAINER-->
<div class="page-container" id="suscripciones">
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
                                <h4>Info</h4>
                            </div>
                            <div class="card-body">
                                <div class="user-photo m-b-30  text-center">
                                    <img class="img-thumbnail" v-if="suscripcionesDatos.Imagen_suscripcion != null" width="420px" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+suscripcionesDatos.Imagen_suscripcion" alt="">
                                    <img class="img-thumbnail" v-else src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" alt="">
                                </div>
                                <h4 class="text-sm-center mt-2 mb-1">{{suscripcionesDatos.Titulo_suscripcion}}</h4>
                                <p class="text-sm-center mt-2 mb-1">{{suscripcionesDatos.Nombre_cliente}}</p>
                                <div class="location text-sm-center">
                                    <i class="fa fa-map-marker"></i> {{suscripcionesDatos.Direccion}}, {{suscripcionesDatos.Localidad}}, {{suscripcionesDatos.Provincia}}, {{suscripcionesDatos.Pais}}</div>
                            </div>
                        </div>
                        <div class="d-print-none">
                            <a target="_blank" v-bind:href="'https://api.whatsapp.com/send?phone='+suscripcionesDatos.Telefono" class="btn btn-success btn-block">
                                <i class="fab fa-whatsapp"></i> Enviar whatsapp
                            </a>
                            <hr>
                            <a target="_blank" v-bind:href="'mailto:'+suscripcionesDatos.Email" class="btn btn-info btn-block">
                                <i class="fa fa-envelope"></i> Enviar email
                            </a>
                        </div>
                    </div>

                    <!-- SECCION FICHA cliente -->
                    <div class="col-lg-10">

                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 1 }" href="#" v-on:click="mostrar = 1">Ficha y Seguimiento</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 3 }" href="#" v-on:click="getListaPeriodos()">Historial de cobros</a>
                            </li>
                        </ul>

                        <!-- SECCION DATOS EDITABLES DEL cliente -->
                        <div class="row" v-show="mostrar == '1'">
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Ficha</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="horizontal-form">
                                            <form class="form-horizontal" action="post" v-on:submit.prevent="crearSuscripcion()">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="horizontal-form">
                                                            <div class="form-group">
                                                                <label class=" form-control-label">Título Suscripción</label>
                                                                <input type="text" class="form-control" placeholder="" v-model="suscripcionesDatos.Titulo_suscripcion" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-control-label">Cliente</label>
                                                                <select class="form-control-sm form-control" v-model="suscripcionesDatos.Cliente_id">
                                                                    <option selected="selected" v-bind:value="0">Empresas</option>
                                                                    <option v-for="cliente in listaClientes" v-bind:value="cliente.Id">{{cliente.Nombre_cliente}}</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class=" form-control-label">Categoría</label>
                                                                <select class="form-control-sm form-control" v-model="suscripcionesDatos.Categoria_id">
                                                                    <option selected="selected" v-bind:value="0">Empresas</option>
                                                                    <option v-for="categoria in listaCategorias" v-bind:value="categoria.Id">{{categoria.Nombre_categoria}}</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Monto fijo a la fecha</label>
                                                                <input type="number" class="form-control" placeholder="" v-model="suscripcionesDatos.Monto">
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class=" form-control-label">Fecha inicio del servicio</label>
                                                                <input type="date" class="form-control" v-model="suscripcionesDatos.Fecha_inicio_servicio" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class=" form-control-label">sFecha finalización del servicio.</label>
                                                                <input type="date" class="form-control" v-model="suscripcionesDatos.Fecha_finalizacion_servicio" >
                                                            </div>
                                                            <div class="form-group">
                                                                <label class=" form-control-label">Información de la persona a cargo</label>
                                                                <textarea class="form-control" rows="5" v-model="suscripcionesDatos.Datos_persona_contacto"></textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class=" form-control-label">Más información</label>
                                                                <textarea class="form-control" rows="5" v-model="suscripcionesDatos.Observaciones"></textarea>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <hr>
                                                        <button type="submit" class="btn btn-success">Actualizar datos</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Seguimiento</strong>
                                    </div>
                                    <div class="card-body">
                                        <p align="right">
                                            <a href="#modalSeguimiento" data-toggle="modal" title="Nuevo item" class="btn btn-success btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="limpiarFormularioSeguimiento()">
                                                <i class="ti-plus"></i> Añadir reporte
                                            </a>
                                        </p>
                                    </div>
                                    <div class="card-body">
                                        <div class="bootstrap-data-table-panel col-lg-12">
                                            <div class="table-responsive">
                                                <table id="table2excel" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <!-- <th>Suscripci</th> -->
                                                            <th>Fecha</th>
                                                            <th>Descripcion</th>
                                                            <th>Usuario</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="seguimiento in listaSeguimiento">
                                                            <!-- <td>{{seguimiento.Titulo_suscripcion}}</td> -->
                                                            <td>{{seguimiento.Fecha | Fecha}}</td>
                                                            <td>{{seguimiento.Descripcion}}</td>
                                                            <td>{{seguimiento.Nombre}}</td>
                                                            <td>
                                                                <a href="#modalSeguimiento" data-toggle="modal" v-on:click="editarFormularioSeguimiento(seguimiento)">
                                                                    <i class="fa fa-edit"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </DIV>
                            </DIV>
                        </div>
                        <!-- SECCION SUSCRIPCIONES -->
                        <div class="row" v-show="mostrar == '3'">
                            <div class="col-lg-7">
                                <div class="card" style="max-height: 1000px; overflow-y: scroll;">
                                    <!--  -->
                                    <div class="card-header">
                                        <strong>Períodos</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="bootstrap-data-table-panel col-lg-12">

                                        <div class="table-responsive">
                                                <table id="table2excel" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Período</th>
                                                            <th style="background-color:wheat">Monto</th>
                                                            <th style="background-color:wheat">Intereses</th>
                                                            <th style="background-color:lightsteelblue">Abonado</th>
                                                            <th>Est.</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="cobro in listaPeriodos">
                                                            <td><h4> {{cobro.Datos_periodo.Identificador }}</h4></td>
                                                            <td style="background-color:wheat" v-if="cobro.Estado == 0">
                                                                ...
                                                            </td>
                                                            <td style="background-color:wheat" align="right" v-if="cobro.Estado > 0">
                                                                <b>$ {{cobro.Datos_cobro_suscripcion[0].Valor_cobro | Moneda}}</b>
                                                            </td>
                                                            <td style="background-color:wheat" v-if="cobro.Estado == 0">
                                                                ...
                                                            </td>
                                                            <td style="background-color:wheat" align="right" v-if="cobro.Estado > 0">
                                                                <b>$ {{cobro.Datos_cobro_suscripcion[0].Valor_interes | Moneda}} </b>
                                                            </td>
                                                            <td style="background-color:lightsteelblue" align="right"> <b> $ {{cobro.Total_abonado}}</b></td>
                                                            <td align="center" v-if="cobro.Estado == 0"><span class="text-dark"> <i class="fa fa-circle"></i></span></td>
                                                            <td align="center" v-if="cobro.Estado == 1"><span class="text-danger"> <i class="fa fa-circle"></i></span></td>
                                                            <td align="center" v-if="cobro.Estado == 2"><span class="text-warning"> <i class="fa fa-circle"></i></span> </td>
                                                            <td align="center" v-if="cobro.Estado == 3"><span class="text-success"> <i class="fa fa-circle"></i></span></td>
                                                            <td>
                                                                <div class="table-data-feature">
                                                                    <button class="item" v-on:click="periodoSeleccionado(cobro)" title="Gestionar Cobro">
                                                                        <i class="zmdi zmdi-edit"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <!-- <pre>
                                                {{listaSuscripciones}}
                                                </pre> -->
                                            </div>
                                        </div>
                                    </div>
                                </DIV>
                            </DIV>
                            <div class="col-lg-5 d-print-none">
                                <div style="position: fixed; overflow-y:auto; overflow-x:hidden; max-height: 900px;">
                                    <div class="card">
                                        <div class="card-header">
                                            <strong>Cobro de {{Datos_periodo.Titulo_suscripcion}}</strong>
                                        </div>
                                        <div class="card-body">
                                            <div class="horizontal-form">
                                                
                                                    <div class="horizontal-form">
                                                        <!--  <pre>
                                                            {{pagosDatos}}
                                                        </pre> -->
                                                        <div class="row">
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label class=" form-control-label">Fecha de pago</label>
                                                                    <input type="date" class="form-control" v-model="cobroSuscripcionesDatos.Fecha_pago" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label class=" form-control-label">Monto definido</label>
                                                                    <input type="number" class="form-control" v-model="cobroSuscripcionesDatos.Valor_cobro" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label class=" form-control-label">Interes</label>
                                                                    <input type="number" class="form-control" v-model="cobroSuscripcionesDatos.Valor_interes" disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card" v-if="cobroSuscripcionesDatos.Id > 0">
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
                                                                        $ {{ calcularTotalCobros(cobroSuscripcionesDatos.Valor_cobro, cobroSuscripcionesDatos.Valor_interes) | Moneda }}
                                                                    </h3>
                                                                </td>
                                                                <td>
                                                                    <h3>
                                                                        $ {{ Total_cheques + Total_efectivo + Total_transferencias | Moneda }}
                                                                    </h3>
                                                                </td>
                                                                <td>
                                                                    <h3>
                                                                        $ {{ calcularSaldoCobro(cobroSuscripcionesDatos.Valor_cobro, cobroSuscripcionesDatos.Valor_interes, Total_cheques, Total_efectivo, Total_transferencias) | Moneda }}
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
                                            <div class="card" v-if="cobroSuscripcionesDatos.Id > 0">
                                                <div class="card-header">
                                                    <strong>Pagos en efectivo</strong>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table id="table2excel" class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>
                                                                        <!-- <button data-toggle="modal" data-target="#modalEfectivo" v-on:click="limpiarFormularioMovimiento()">
                                                                            <i class="fa fa-plus-circle text-success"></i>
                                                                        </button> -->
                                                                    </th>
                                                                    <th>Monto</th>
                                                                    <th>Fecha</th>

                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr v-for="mov_efectivo in listaMovimientosEfectivo">
                                                                    <td>
                                                                        <!-- <button v-on:click="desactivarAlgo(mov_efectivo.Id, 'tbl_dinero_efectivo')" class="item" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                                                            <i class="zmdi zmdi-delete"></i>
                                                                        </button> -->
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
                                            <div class="card" v-if="cobroSuscripcionesDatos.Id > 0">
                                                <div class="card-header">
                                                    <strong>Pagos con transferencia</strong>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table id="table2excel" class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>
                                                                        <!-- <button data-toggle="modal" data-target="#modalTransferencia" v-on:click="limpiarFormularioMovimiento()">
                                                                            <i class="fa fa-plus-circle text-success"></i>
                                                                        </button> -->
                                                                    </th>
                                                                    <th>Monto</th>
                                                                    <th>Fecha</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr v-for="mov_trans in listaMovimientosTransferencia">
                                                                    <td>
                                                                        <!-- <button v-on:click="desactivarAlgo(mov_trans.Id, 'tbl_dinero_transferencias')" class="item" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                                                            <i class="zmdi zmdi-delete"></i>
                                                                        </button> -->
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


                                    <div class="card" v-if="cobroSuscripcionesDatos.Id > 0">
                                        <div class="card-header">
                                            <strong>Pagos con cheque</strong>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="table2excel" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                <!-- <button data-toggle="modal" data-target="#modalCheque" v-on:click="limpiarFormularioMovimiento()">
                                                                    <i class="fa fa-plus-circle text-success"></i>
                                                                </button> -->
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
                                                                <!-- <button v-on:click="desactivarAlgo(mov_cheques.Id, 'tbl_dinero_cheques')" class="item" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                                                    <i class="zmdi zmdi-delete"></i>
                                                                </button> -->
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
    <!-- Modal SEGUIMIENTO-->
    <div class="modal fade" id="modalSeguimiento" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{texto_boton}} reporte de seguimiento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="horizontal-form">
                        <form class="form-horizontal" action="post" v-on:submit.prevent="crearSeguimiento()">
                            <div class="form-group">
                                <label class=" form-control-label">Fecha del reporte</label>
                                <input type="date" class="form-control" placeholder="" v-model="seguimientoData.Fecha">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Datos del seguimiento</label>
                                <textarea class="form-control" rows="5" placeholder="" v-model="seguimientoData.Descripcion"></textarea>
                            </div>
                            <div class="form-group">
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