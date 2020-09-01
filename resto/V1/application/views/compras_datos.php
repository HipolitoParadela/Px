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
    <div class="content-wrap" id="compras">
        <div class="main">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-lg-8 p-r-0 title-margin-right">
                        <div class="page-header">
                            <div class="page-title">
                                <h1>Compras, <span>Datos</span></h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="../">Compras</a></li>
                                    <li class="breadcrumb-item active">{{compraDatos.Factura_identificador}}</li>
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


                                <!-- SECCION FICHA compra -->
                                <div class="col-lg-12">

                                    <ul class="nav nav-tabs">
                                        <li class="nav-item">
                                            <a class="nav-link" v-bind:class="{ active: mostrar == 1 }" href="#" v-on:click="mostrar = 1">Ficha</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" v-bind:class="{ active: mostrar == 2 }" href="#" v-on:click="getListaProductosProveedor()">Productos comprados</a>
                                        </li>
                                    </ul>

                                    <!-- SECCION DATOS EDITABLES DEL compra -->
                                    <div class="row" v-show="mostrar == '1'">
                                        <div class="col-lg-2">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="user-photo m-b-30">
                                                        <img v-if="compraDatos.Imagen != null" class="img-fluid" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+compraDatos.Imagen" alt="">
                                                        <img class="img-fluid" v-else src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" alt="">
                                                    </div>
                                                    <h5 class="text-sm-center mt-2 mb-1">{{compraDatos.Factura_identificador}}</h5>
                                                    <div class="location text-sm-center">
                                                        Proveedor: {{compraDatos.Nombre_proveedor}}
                                                    </div>
                                                    <div class="location text-sm-center">
                                                        {{compraDatos.Fecha_compra | Fecha}}
                                                    </div>
                                                    <div v-show="compraDatos.Imagen != null" class="location text-sm-center">
                                                        <a target="_blank" v-bind:href="'<?php echo base_url(); ?>uploads/imagenes/'+compraDatos.Imagen" class="btn btn-secondary-sm btn-block">
                                                            <i class="fa fa-share-square"></i> VER FACTURA
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <a target="_blank" v-bind:href="'https://api.whatsapp.com/send?phone='+compraDatos.Telefono" class="btn btn-success btn-block">
                                                    <i class="fab fa-whatsapp"></i> Enviar whatsapp
                                                </a>
                                                <hr>
                                                <a target="_blank" v-bind:href="'mailto:'+compraDatos.Email" class="btn btn-info btn-block">
                                                    <i class="fa fa-envelope"></i> Enviar email
                                                </a>
                                            </div>
                                            <hr>
                                            <span v-show="compraDatos.Web != null">
                                                <a target="_blank" v-bind:href="'http://'+compraDatos.Web" class="btn btn-secondary btn-block">
                                                    <i class="fa fa-share-square"></i> {{compraDatos.Web}}
                                                </a>
                                            </span>
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="horizontal-form">
                                                        <form class="form-horizontal" action="post" v-on:submit.prevent="crearcompra()">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <h4>Datos compra</h4>
                                                                    <hr>
                                                                    <div class="form-group">
                                                                        <label class=" form-control-label">Número factura o Identificador de la compra</label>
                                                                        <input type="text" class="form-control" placeholder="" v-model="compraDatos.Factura_identificador" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class=" form-control-label">Proveedor</label>
                                                                        <select class="form-control" v-model="compraDatos.Proveedor_id" required>
                                                                            <option value="0">No asignar proveedor</option>
                                                                            <option v-for="proveedor in listaProveedores_select" v-bind:value="proveedor.Id">{{proveedor.Nombre_proveedor}}</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class=" form-control-label">Fecha en que se realizó la compra</label>
                                                                        <input type="date" class="form-control" placeholder="" v-model="compraDatos.Fecha_compra" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="control-label">Valor neto de la compra</label>
                                                                        <input type="number" class="form-control" placeholder="" v-model="compraDatos.Neto" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="control-label">No gravado</label>
                                                                        <input type="number" class="form-control" placeholder="" v-model="compraDatos.No_gravado" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="control-label">Valor IVA</label>
                                                                        <input type="number" class="form-control" placeholder="" v-model="compraDatos.IVA" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class=" form-control-label">Descripción de la compra</label>
                                                                        <textarea class="form-control" rows="5" v-model="compraDatos.Descripcion"></textarea>
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
                                                <p align="center">
                                                    <strong>Seguimiento</strong>
                                                </p>
                                                <div class="card-body">
                                                    <div class="bootstrap-data-table-panel col-lg-12">
                                                        <div class="table-responsive">
                                                            <table id="table2excel" class="table table-striped">
                                                                <thead>
                                                                    <tr>

                                                                        <th>Fecha</th>
                                                                        <th>Descripcion</th>
                                                                        <th>Archivo</th>
                                                                        <th>Usuario</th>
                                                                        <th>
                                                                            <a href="#modalSeguimiento" data-toggle="modal" title="Nuevo item" class="btn btn-success btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="limpiarFormularioSeguimiento()">
                                                                                <i class="ti-plus"></i> Añadir reporte
                                                                            </a>
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-for="seguimiento in listaSeguimiento">
                                                                        <td>{{seguimiento.Fecha | Fecha}}</td>
                                                                        <td>{{seguimiento.Descripcion}}</td>
                                                                        <td><a v-if="seguimiento.Url_archivo != null" target="_blank" v-bind:href="'<?php echo base_url(); ?>uploads/imagenes/'+seguimiento.Url_archivo"> Ver archivo</a></td>
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

                                            <!-- FINANZAS -->
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <div class="card p-0">
                                                        <div class="stat-widget-three">
                                                            <div class="stat-icon bg-warning">
                                                                <i class="ti-money"></i>
                                                            </div>
                                                            <div class="stat-content">
                                                                <div class="stat-digit">$ {{sumarComrpra(compraDatos.Neto, compraDatos.No_gravado, compraDatos.IVA) | Moneda}}</div>
                                                                <div class="stat-text">Monto total</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="card p-0">
                                                        <div class="stat-widget-three">
                                                            <div class="stat-icon bg-success">
                                                                <i class="ti-money"></i>
                                                            </div>
                                                            <div class="stat-content">
                                                                <div class="stat-digit">$ {{Total_pagado | Moneda}}</div>
                                                                <div class="stat-text">Monto Abonado</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="card p-0">
                                                        <div class="stat-widget-three">
                                                            <div class="stat-icon bg-info">
                                                                <i class="ti-money"></i>
                                                            </div>
                                                            <div class="stat-content">
                                                                <div class="stat-digit">$ {{ sumarComrpra(compraDatos.Neto, compraDatos.No_gravado, compraDatos.IVA) - Total_pagado | Moneda}}</div>
                                                                <div class="stat-text">Saldo</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-9">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <strong>Pagos</strong>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <table id="table2excel" class="table table-striped">
                                                                    <thead>
                                                                        <tr>

                                                                            <th>Método</th>
                                                                            <th>Monto</th>
                                                                            <th>Fecha</th>
                                                                            <th>
                                                                                <button data-toggle="modal" data-target="#modalEfectivo" v-on:click="limpiarFormularioMovimiento()">
                                                                                    <i class="fa fa-plus-circle text-success"></i> Pagar
                                                                                </button>
                                                                                <button data-toggle="modal" data-target="#modalCheque" v-on:click="limpiarFormularioMovimiento()">
                                                                                    <i class="fa fa-plus-circle text-success"></i> Pagar con cheque
                                                                                </button> Modificar esto para mostrar listado de cheques. 
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
                                                </div>
                                            </div>
                                            <!-- fin FINANZAS -->

                                        </div>
                                    </div>

                                    <!-- SECCION DATOS DE PRODUCTOS -->
                                    <div class="row" v-show="mostrar == '2'">
                                        <div class="col-lg-5">
                                            <div class="card">
                                                <div class="card-header">
                                                    <strong>Productos comprados</strong>
                                                </div>
                                                <div class="card-body">
                                                    <div class="bootstrap-data-table-panel col-lg-12">

                                                        <div class="table-responsive">
                                                            <table id="table2excel" class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Producto</th>
                                                                        <th>Cantidad</th>
                                                                        <th>Monto</th>
                                                                        <th>Descripción</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-for="productoComprado in listaComprados">
                                                                        <td>{{productoComprado.Nombre_item}}</td>
                                                                        <td>{{productoComprado.Cantidad}}</td>
                                                                        <td>${{productoComprado.Precio_costo * productoComprado.Cantidad | Moneda}}</td>
                                                                        <td>{{productoComprado.Descripcion}}</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </DIV>
                                        </DIV>
                                        <div class="col-lg-7">
                                            <div class="card">
                                                <div class="card-header">
                                                    <strong>Productos de este proveedor</strong>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-data__tool-right">
                                                        <div class="rs-select2--light ">
                                                            <input type="text" class="form-control form-control" placeholder="Buscar producto" v-model="buscar">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="bootstrap-data-table-panel col-lg-12">
                                                        <div class="table-responsive">
                                                            <table id="table2excel" class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Producto</th>
                                                                        <th>Cantidad</th>
                                                                        <th>Precio Un.</th>
                                                                        <th>Descripción</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-for="(producto, index) in buscarProducto">
                                                                        <td>{{producto.Nombre_item}}</td>
                                                                        <td><input size="6" type="number" class="form-control" v-model="cantMovimientoStock[index]"></td>
                                                                        <td><input size="6" type="number" class="form-control" v-model="producto.Precio_costo"></td>
                                                                        <td>
                                                                            <div class="input-group">
                                                                                <input type="text" placeholder="Descripción" class="form-control" v-model="descripcionMovimiento[index]" :disabled="cantMovimientoStock[index] == null">
                                                                                <div class="input-group-btn">
                                                                                    <button class="btn btn-warning" v-on:click="movimientoStock(producto.Id, cantMovimientoStock[index], descripcionMovimiento[index], producto.Precio_costo)" :disabled="cantMovimientoStock[index] == null">
                                                                                        <i class="fa fa-save"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
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
                <!-- /.modal -->
                <!-- Modal pagos - No cheques-->
                <div class="modal fade" id="modalEfectivo" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="true">
                    <div class="modal-dialog  modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalItemsFoto">Ingresar pago</h5>
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
                                        <label class="control-label">Modalidad de pago</label>
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
                <!-- /.modal -->
                <!-- Modal cheques-->
                <div class="modal fade" id="modalCheque" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="true">
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
                <!-- /.modal -->
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
                <?php /// FOOTER
                include "footer.php";
                ?>