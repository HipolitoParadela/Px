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
    <div class="content-wrap" id="clientes">
        <div class="main">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-lg-8 p-r-0 title-margin-right">
                        <div class="page-header">
                            <div class="page-title">
                                <h1>Clientes, <span>Datos</span></h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="../">Clientes</a></li>
                                    <li class="breadcrumb-item active">{{clienteDatos.Datos.Nombre}}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->

                <section id="main-content">
                    <div class="section__content section__content--p30">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-2">
                                    <div class="card">
                                        <p align="center">
                                            <strong>Info</strong>
                                        </p>
                                        <div class="card-body">
                                            <!-- <div class="user-photo m-b-30">
                                                <img class="img-fluid" v-if="clienteDatos.Datos.Imagen != null" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+clienteDatos.Datos.Imagen" alt="">
                                                <img class="img-fluid" v-else src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" alt="">
                                            </div> -->
                                            <h2 class="text-sm-center mt-2 mb-1">{{clienteDatos.Datos.Nombre}}</h2>
                                            <div class="location text-sm-center">
                                                <i class="fa fa-phone"></i> {{clienteDatos.Datos.Telefono}}
                                            </div>
                                            <div class="location text-sm-center">
                                                <i class="fa fa-map-marker"></i> {{clienteDatos.Datos.Direccion}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <p align="center">
                                            <strong>Saldo de cuenta</strong>
                                        </p>
                                        <div class="card-body">
                                            <h1 align="center" v-bind:class="[{ 'text-danger' : (clienteDatos.Saldo  + Total_mov_generico) < 0}, { 'text-success' : (clienteDatos.Saldo  + Total_mov_generico) > 0}]">$ {{ clienteDatos.Saldo + Total_mov_generico | Moneda }}</h1>
                                        </div>
                                        <hr>
                                        <p align="center">
                                            <strong>Total compras</strong>

                                        </p>

                                        <div class="card-body">
                                            <h3 align="center">$ {{ clienteDatos.Total_compras | Moneda }}</h3>
                                        </div>
                                        <hr>
                                        <p align="center">
                                            <strong>Total pagos</strong>
                                        </p>

                                        <div class="card-body">
                                            <h3 align="center">$ {{ clienteDatos.Total_pagos | Moneda }}</h3>
                                        </div>

                                        <hr>
                                        <p align="center">
                                            <strong>Total movimientos genéricos</strong>
                                        </p>

                                        <div class="card-body">
                                            <h3 align="center">$ {{ Total_mov_generico | Moneda }}</h3>
                                        </div>
                                    </div>
                                    <hr>
                                    <div>
                                        <a target="_blank" v-bind:href="'https://api.whatsapp.com/send?phone='+clienteDatos.Datos.Telefono" class="btn btn-success btn-block">
                                            <i class="fab fa-whatsapp"></i> Enviar whatsapp
                                        </a>
                                        <hr>
                                        <a target="_blank" v-bind:href="'mailto:'+clienteDatos.Datos.Email" class="btn btn-info btn-block">
                                            <i class="fa fa-envelope"></i> Enviar email
                                        </a>
                                    </div>
                                </div>
                                <!-- SECCION FICHA PROVEEDOR -->
                                <div class="col-lg-10">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item">
                                            <a class="nav-link" v-bind:class="{ active: mostrar == 1 }" href="#" v-on:click="mostrar = 1">Ficha</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" v-bind:class="{ active: mostrar == 3 }" href="#" v-on:click="mostrar = 3">Resumen de cuenta</a>
                                        </li>
                                    </ul>

                                    <!-- SECCION DATOS EDITABLES -->
                                    <div class="row" v-show="mostrar == '1'">
                                        <div class="col-lg-5">
                                            <div class="card">
                                                <div class="card-header">
                                                    <strong>Ficha: {{clienteDatos.Datos.Nombre}}</strong>
                                                </div>
                                                <div class="card-body">
                                                    <div class="horizontal-form">
                                                        <form class="form-horizontal" action="post" v-on:submit.prevent="crearCliente()">
                                                            <div class="form-group">
                                                                <div class="col-sm-12">
                                                                    <label class="control-label">Nombre</label> <input type="text" class="form-control" placeholder="" v-model="clienteDatos.Datos.Nombre">
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <label class="control-label">Telefono</label>
                                                                        <input type="text" class="form-control" placeholder="" v-model="clienteDatos.Datos.Telefono">
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <label class="control-label">Teléfono secundario</label>
                                                                        <input type="text" class="form-control" placeholder="" v-model="clienteDatos.Datos.Telefono_secundario">
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <label class="control-label">Dirección</label>
                                                                        <input type="text" class="form-control" placeholder="" v-model="clienteDatos.Datos.Direccion">
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <label class="control-label">Email</label>
                                                                        <input type="text" class="form-control" placeholder="" v-model="clienteDatos.Datos.Email">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12">
                                                                    <label class="control-label">Observaciones</label>
                                                                    <textarea class="form-control" rows="5" placeholder="" v-model="clienteDatos.Datos.Observaciones"></textarea>
                                                                </div>

                                                                <div class="col-sm-offset-2 col-sm-12">
                                                                    <button type="submit" class="btn btn-success">{{texto_boton}}</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="card">
                                                <div class="card-header">
                                                    <strong>Seguimiento</strong>
                                                </div>
                                                <div class="card-body">
                                                    <div class="bootstrap-data-table-panel col-lg-12">
                                                        <div class="table-responsive">
                                                            <table id="table2excel" class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Cliente</th>
                                                                        <th>Fecha</th>
                                                                        <th>Descripcion</th>
                                                                        <th>Usuario</th>
                                                                        <th><a href="#modalSeguimiento" data-toggle="modal" title="Nuevo item" class="btn btn-success btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="limpiarFormularioSeguimiento()">
                                                                                <i class="ti-plus"></i> Añadir reporte
                                                                            </a></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-for="seguimiento in listaSeguimiento">
                                                                        <td>{{seguimiento.Nombre_cliente}}</td>
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
                                            </div>
                                        </div>
                                    </div>

                                    <!-- SECCION COMRPAS DE ESTE CLIENTE -->
                                    <div class="row" v-show="mostrar == '3'">
                                        <div class="col-lg-4">
                                            <div class="card">
                                                <div class="card-header">
                                                    <strong>Comandas</strong>
                                                </div>
                                                <div class="card-body">
                                                    <div class="bootstrap-data-table-panel col-lg-12">
                                                        <div class="table-responsive">
                                                            <table id="table3excel" class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Fecha</th>
                                                                        <th>Cant. Personas</th>
                                                                        <th>Mozo</th>
                                                                        <th>Valor</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <!-- <tr v-for="producto in listaProductos"> -->
                                                                    <tr v-for="compra in clienteDatos.Datos_comandas">
                                                                        <td>
                                                                            <a v-bind:href="'../../restaurant/comanda/?Id='+compra.Id" class="btn btn-dark btn-outline m-b-10 m-l-5">
                                                                                {{compra.Fecha}}
                                                                            </a>
                                                                        </td>
                                                                        <td>{{compra.Cant_personas}}</td>
                                                                        <td>{{compra.Nombre_mozo}}</td>
                                                                        <td>
                                                                            <h4 align="right">
                                                                                $ {{ compra.Valor_cuenta - compra.Valor_descuento | Moneda}}
                                                                            </h4>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                                <tfoot>
                                                                    <th></th>
                                                                    <th></th>
                                                                    <th></th>
                                                                    <th>
                                                                        <h3 align="right">
                                                                            $ {{ clienteDatos.Total_compras_comandas | Moneda}}
                                                                        </h3>
                                                                    </th>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </DIV>
                                        </DIV>
                                        <div class="col-lg-4">
                                            <div class="card">
                                                <div class="card-header">
                                                    <strong>Delivery</strong>
                                                </div>
                                                <div class="card-body">
                                                    <div class="bootstrap-data-table-panel col-lg-12">
                                                        <div class="table-responsive">
                                                            <table id="table3excel" class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Fecha</th>
                                                                        <th>Repartidor</th>
                                                                        <th>Valor</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <!-- <tr v-for="producto in listaProductos"> -->
                                                                    <tr v-for="compra in clienteDatos.Datos_delivery">
                                                                        <td>
                                                                            <a v-bind:href="'../../restaurant/delivery/?Id='+compra.Id" class="btn btn-dark btn-outline m-b-10 m-l-5">
                                                                                {{compra.FechaHora_pedido}}
                                                                            </a>
                                                                        </td>
                                                                        <td>{{compra.Nombre_repartidor}}</td>
                                                                        <td>
                                                                            <h4 align="right">
                                                                                {{ montoDelivery(compra.Valor_cuenta, compra.Valor_delivery, compra.Valor_descuento) | Moneda}}
                                                                            </h4>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                                <tfoot>
                                                                    <th></th>
                                                                    <th></th>
                                                                    <th>
                                                                        <h3 align="right">
                                                                            $ {{ clienteDatos.Total_compras_delivery | Moneda}}
                                                                        </h3>
                                                                    </th>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </DIV>
                                        </DIV>
                                        <div class="col-lg-4">
                                            <div class="card">
                                                <div class="card-header">
                                                    <strong>Pagos genéricos</strong>
                                                </div>
                                                <div class="card-body">
                                                    <div class="bootstrap-data-table-panel col-lg-12">
                                                        <div class="table-responsive">
                                                            <button data-toggle="modal" data-target="#modalEfectivo" v-on:click="limpiarFormularioMovimiento()">
                                                                <i class="fa fa-plus-circle text-success"></i> Cobrar
                                                            </button>
                                                            <button data-toggle="modal" data-target="#modalRegistrarCheque" v-on:click="limpiarFormularioMovimiento()">
                                                                <i class="fa fa-plus-circle text-success"></i> Cobrar con cheque
                                                            </button>
                                                            <table id="table2excel" class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Método</th>
                                                                        <th>Monto</th>
                                                                        <th>Fecha</th>
                                                                        <th>

                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-for="movimiento in listaMovimientos">
                                                                        <td v-if="movimiento.Tipo_movimiento == 1">Efectivo</td>
                                                                        <td v-if="movimiento.Tipo_movimiento == 2">Tarjeta/Banco</td>
                                                                        <td v-if="movimiento.Tipo_movimiento == 3">Cheque</td>
                                                                        <td v-if="movimiento.Tipo_movimiento == 4">Mercado Pago</td>

                                                                        <td>$ {{movimiento.Monto_bruto | Moneda}}</td>
                                                                        <td>{{movimiento.Fecha_ejecutado | Fecha}}</td>
                                                                        <td>
                                                                            {{movimiento.Observaciones}}
                                                                            <!--<button class="item" v-on:click="infoEtapa(movimiento.Observaciones)" data-toggle="modal" data-target="#modalObservaciones" data-placement="top" title="Ver observaciones">
                                                                    <i class="fa fa-exclamation-circle"></i>
                                                                </button>
                                                                 <button v-on:click="desactivarAlgo(movimiento.Id, 'tbl_dinero_efectivo')" class="item" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                                                    <i class="zmdi zmdi-delete"></i>
                                                                </button> -->
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
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
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
                                <!-- <pre>{{seguimientoData}}</pre> -->
                                <div class="horizontal-form">
                                    <form class="form-horizontal" enctype="multipart/form-data" action="post" v-on:submit.prevent="crearSeguimiento()">
                                        <!--   -->
                                        <div class="form-group">
                                            <label class=" form-control-label">Fecha del reporte</label>
                                            <input type="date" class="form-control" placeholder="" v-model="seguimientoData.Fecha">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Datos del seguimiento</label>
                                            <textarea class="form-control" rows="5" placeholder="" v-model="seguimientoData.Descripcion"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <input @change="archivoSeleccionado" type="file" class="form-control" name="Imagen">
                                            </div>
                                            <div class="col-sm-12" v-if="seguimientoData.Url_archivo != null">
                                                Archivo previamente cargado
                                                <a target="_blank" v-bind:href="'<?php echo base_url(); ?>uploads/imagenes/'+seguimientoData.Url_archivo"> Ver archivo</a>
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
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.modal -->
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
                                <h4 v-if="infoModal != null">{{infoModal}}</h4>
                                <h4 v-else><em>No se han registrado observaciones</em></h4>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.modal -->
                <!-- Modal pagos - No cheques-->
                <div class="modal fade" id="modalEfectivo" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="true">
                    <div class="modal-dialog  modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalItemsFoto">Registrar movimiento</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="horizontal-form">
                                    <form class="form-horizontal" action="post" v-on:submit.prevent="crear_movimiento()">
                                        <div class="form-group">
                                            <label class="control-label">Monto</label>
                                            <input type="number" class="form-control" v-model="movimientoDatos.Monto_bruto" required>
                                        </div>

                                        <label class="control-label">Tipo de movimiento</label>
                                        <select class="form-control" v-model="movimientoDatos.Op">
                                            <option value="1">Cobro / Ingreso</option>
                                            <option value="0">Pago / Egreso</option>
                                        </select>

                                        <label class="control-label">Modalidad</label>
                                        <select class="form-control" v-model="movimientoDatos.Tipo_movimiento">
                                            <option value="1">Efectivo</option>
                                            <option value="2">Tarjeta / Transferencia</option>
                                            <option value="4">Mercado pago</option>
                                        </select>

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
                <!-- Modal registrar cheque-->
                <div class="modal fade" id="modalRegistrarCheque" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="true">
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
                                        <input type="hidden" v-model="chequeData.Tipo" v-value="1">
                                        <div class="form-group">
                                            <label class="control-label">Cheque a nombre de</label>
                                            <input type="text" class="form-control" v-model="chequeData.Nombre_entrega" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Monto</label>
                                            <input type="number" class="form-control" v-model="chequeData.Monto_bruto" required>
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
                <!-- /.modal -->
                <!-- END MAIN CONTENT-->
                <!-- END PAGE CONTAINER-->
                <!-- /.modal -->
                <?php /// FOOTER
                include "footer.php";
                ?>