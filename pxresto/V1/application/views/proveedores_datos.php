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
    <div class="content-wrap" id="proveedores">
        <div class="main">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-lg-8 p-r-0 title-margin-right">
                        <div class="page-header">
                            <div class="page-title">
                                <h1>Proveedores, <span>Datos</span></h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="../">Proveedores</a></li>
                                    <li class="breadcrumb-item active">{{proveedoresDatos.Datos.Nombre_proveedor}}</li>
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

                                        <h4>Info</h4>
                                        <div class="card-body">
                                            <div class="user-photo m-b-30">
                                                <img class="img-fluid" v-if="proveedoresDatos.Datos.Imagen != null" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+proveedoresDatos.Datos.Imagen" alt="">
                                                <img class="img-fluid" v-else src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" alt="">
                                            </div>
                                            <h5 class="text-sm-center mt-2 mb-1">{{proveedoresDatos.Datos.Nombre_proveedor}}</h5>
                                            <div class="location text-sm-center">
                                                <i class="fa fa-map-marker"></i> {{proveedoresDatos.Datos.Direccion}}, {{proveedoresDatos.Datos.Localidad}}, {{proveedoresDatos.Datos.Provincia}}, {{proveedoresDatos.Datos.Pais}}
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <a target="_blank" v-bind:href="'https://api.whatsapp.com/send?phone='+proveedoresDatos.Datos.Telefono" class="btn btn-success btn-block">
                                            <i class="fab fa-whatsapp"></i> Enviar whatsapp
                                        </a>
                                        <hr>
                                        <a target="_blank" v-bind:href="'mailto:'+proveedoresDatos.Datos.Email" class="btn btn-info btn-block">
                                            <i class="fa fa-envelope"></i> Enviar email
                                        </a>

                                        <hr>
                                        <span v-if="proveedoresDatos.Datos.Web != null">
                                            <a target="_blank" v-bind:href="'http://'+proveedoresDatos.Datos.Web" class="btn btn-secondary btn-block">
                                                <i class="fa fa-share-square"></i> {{proveedoresDatos.Datos.Web}}
                                            </a>
                                        </span>
                                        <hr>
                                        <span v-if="proveedoresDatos.Datos.URL_facebook != null">
                                            <a target="_blank" v-bind:href="proveedoresDatos.Datos.URL_facebook" class="btn btn-secondary btn-block">
                                                <i class="fa fa-share-square"></i> Facebook de {{proveedoresDatos.Datos.Nombre_proveedor}}
                                            </a>
                                        </span>
                                    </div>
                                </div>
                                <!-- CUENTAS -->
                                <div class="col-lg-2">
                                    <div class="card">
                                        <p align="center">
                                            <strong>Saldo de cuenta</strong>
                                        </p>
                                        <div class="card-body">
                                            <h1 align="center" v-bind:class="[{ 'text-danger' : (proveedoresDatos.Saldo + Total_mov_generico) < 0}, { 'text-success' : (proveedoresDatos.Saldo + Total_mov_generico) > 0}]">$ {{ proveedoresDatos.Saldo + Total_mov_generico | Moneda }}</h1>
                                        </div>
                                        <hr>
                                        <p align="center">
                                            <strong>Total compras</strong>

                                        </p>

                                        <div class="card-body">
                                            <h3 align="center">$ {{ proveedoresDatos.Total_compras | Moneda }}</h3>
                                        </div>
                                        <hr>
                                        <p align="center">
                                            <strong>Total pagos</strong>
                                        </p>

                                        <div class="card-body">
                                            <h3 align="center">$ {{ proveedoresDatos.Total_pagos | Moneda }}</h3>
                                        </div>

                                        <hr>
                                        <p align="center">
                                            <strong>Total movimientos genéricos</strong>
                                        </p>

                                        <div class="card-body">
                                            <h3 align="center">$ {{ Total_mov_generico | Moneda }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <!-- SECCION FICHA PROVEEDOR -->
                                <div class="col-lg-8">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item">
                                            <a class="nav-link" v-bind:class="{ active: mostrar == 1 }" href="#" v-on:click="mostrar = 1">Ficha</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" v-bind:class="{ active: mostrar == 2 }" href="#" v-on:click="mostrar = 2">Seguimiento</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" v-bind:class="{ active: mostrar == 3 }" href="#" v-on:click="mostrar = 3">Productos</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" v-bind:class="{ active: mostrar == 4 }" href="#" v-on:click="mostrar = 4">Resumen de cuentas</a>
                                        </li>
                                    </ul>

                                    <!-- SECCION DATOS EDITABLES -->
                                    <div class="row" v-show="mostrar == '1'">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <strong>Ficha: {{proveedoresDatos.Datos.Nombre_proveedor}}</strong>
                                                </div>
                                                <div class="card-body">
                                                    <div class="horizontal-form">
                                                        <form class="form-horizontal" action="post" v-on:submit.prevent="crearProveedor()">
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label class=" form-control-label">Nombre</label>
                                                                        <input type="text" class="form-control" placeholder="" v-model="proveedoresDatos.Datos.Nombre_proveedor" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class=" form-control-label">CUIT/CUIL</label>
                                                                        <input type="text" class="form-control" placeholder="" v-model="proveedoresDatos.Datos.CUIT_CUIL" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class=" form-control-label">Producto/Servicio que brinda</label>
                                                                        <textarea class="form-control" rows="5" v-model="proveedoresDatos.Datos.Producto_servicio"></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class=" form-control-label">Teléfono whatsapp - numero entero de corrido</label>
                                                                        <input type="tel" class="form-control" placeholder="" v-model="proveedoresDatos.Datos.Telefono">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class=" form-control-label">Teléfono Fijo</label>
                                                                        <input type="tel" class="form-control" placeholder="" v-model="proveedoresDatos.Datos.Telefono_fijo">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class=" form-control-label">Dirección</label>
                                                                        <input type="text" class="form-control" placeholder="" v-model="proveedoresDatos.Datos.Direccion" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="control-label">Localidad</label>
                                                                        <input type="text" class="form-control" placeholder="" v-model="proveedoresDatos.Datos.Localidad" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="control-label">Provincia</label>
                                                                        <input type="text" class="form-control" placeholder="" v-model="proveedoresDatos.Datos.Provincia" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="control-label">Pais</label>
                                                                        <input type="text" class="form-control" placeholder="" v-model="proveedoresDatos.Datos.Pais" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label">Email</label>
                                                                        <input type="email" class="form-control" placeholder="" v-model="proveedoresDatos.Datos.Email">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="control-label">Web - Sin "http://"</label>
                                                                        <input type="text" class="form-control" placeholder="" v-model="proveedoresDatos.Datos.Web">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="control-label">URL de su fanpage</label>
                                                                        <input type="text" class="form-control" placeholder="" v-model="proveedoresDatos.Datos.URL_facebook">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="control-label">Nombre de persona de contacto en la empresa</label>
                                                                        <input type="text" class="form-control" placeholder="" v-model="proveedoresDatos.Datos.Nombre_persona_contacto">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class=" form-control-label">Información de la persona de contacto</label>
                                                                        <textarea class="form-control" rows="5" v-model="proveedoresDatos.Datos.Datos_persona_contacto"></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class=" form-control-label">Más información sobre el cliente</label>
                                                                        <textarea class="form-control" rows="5" v-model="proveedoresDatos.Datos.Mas_datos_cliente"></textarea>
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
                                    </div>

                                    <!-- SECCION DATOS DE SEGUIMIENTO -->
                                    <div class="row" v-show="mostrar == '2'">
                                        <div class="col-lg-12">
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
                                                                        <th>Proveedor</th>
                                                                        <th>Fecha</th>
                                                                        <th>Descripcion</th>
                                                                        <th>Archivo</th>
                                                                        <th>Usuario</th>
                                                                        <th><a href="#modalSeguimiento" data-toggle="modal" title="Nuevo item" class="btn btn-success btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="limpiarFormularioSeguimiento()">
                                                                                <i class="ti-plus"></i> Añadir reporte
                                                                            </a></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-for="seguimiento in listaSeguimiento">
                                                                        <td>{{seguimiento.Nombre_proveedor}}</td>
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
                                        </div>
                                    </div>

                                    <!-- SECCION CATEGORIA DE PRODUCTOS QUE OFRECE -->
                                    <div class="row" v-show="mostrar == '3'">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <strong>Categoría de productos que ofrece</strong>
                                                </div>
                                                <div class="card-body">


                                                </div>
                                                <div class="card-body">
                                                    <div class="bootstrap-data-table-panel col-lg-12">
                                                        <div class="table-responsive">
                                                            <table id="table2excel" class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Nombre</th>
                                                                        <th>Comentarios</th>
                                                                        <th>
                                                                            <a href="#categoria_stock_modal" data-toggle="modal" title="Nuevo item" class="btn btn-success" v-on:click="obtener_listado_de_categorias_no_asignados()">
                                                                                <i class="ti-plus"></i> Agregar Categoría
                                                                            </a>

                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <!-- <tr v-for="producto in listaProductos"> -->
                                                                    <tr v-for="categoria in lista_categoria_productos_vinculados">
                                                                        <td>{{categoria.Nombre_categoria}}</td>
                                                                        <td>{{categoria.Descripcion}}</td>
                                                                        <td>
                                                                            <button class="item" v-on:click="editarFormularioComentario(categoria)" data-toggle="modal" data-target="#movimientomodal" data-placement="top" title="Edición rápida">
                                                                                <i class="ti-pencil-alt"></i>
                                                                            </button>
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

                                    <!-- SECCION COMPRAS REALIZADAS A ESTE PROVEEDOR -->
                                    <div class="row" v-show="mostrar == '4'">
                                        <div class="col-lg-8">
                                            <div class="card">
                                                <div class="card-header">
                                                    <strong>Compras realizadas a este proveedor</strong>
                                                </div>
                                                <div class="card-body">
                                                    <div class="bootstrap-data-table-panel col-lg-12">
                                                        <div class="table-responsive">
                                                            <table id="table3excel" class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Factura</th>
                                                                        <th style="background-color:#F7F2E0">Monto</th>
                                                                        <th>Fecha</th>
                                                                        <th>Descripción</th>
                                                                        <th>Responsable</th>
                                                                        <th></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <!-- <tr v-for="producto in listaProductos"> -->
                                                                    <tr v-for="compra in listaComprasProveedor">
                                                                        <td>
                                                                            <a v-bind:href="'../../compras/datos/?Id='+compra.Datos.Id" class="btn btn-dark btn-outline m-b-10 m-l-5">
                                                                                {{compra.Datos.Factura_identificador}}
                                                                            </a>
                                                                        </td>
                                                                        <td style="background-color:#F7F2E0">
                                                                            <h4 align="right">
                                                                                $ {{sumarComrpra(compra.Datos.Neto, compra.Datos.No_gravado, compra.Datos.IVA) | Moneda}}
                                                                            </h4>
                                                                        </td>
                                                                        <!-- <td style="background-color:darkseagreen">
                                                                            <h4 align="right">
                                                                                {{compra.Total | Moneda}}
                                                                            </h4>
                                                                        </td>
                                                                        <td style="background-color:wheat">
                                                                            <h4 align="right">
                                                                                {{compra.Total - sumarComrpra(compra.Datos.Neto, compra.Datos.No_gravado, compra.Datos.IVA) | Moneda}}
                                                                            </h4>
                                                                        </td> -->
                                                                        <td>{{compra.Datos.Fecha_compra | Fecha}}</td>
                                                                        <td>
                                                                            {{compra.Datos.Descripcion | Recortar}}
                                                                            <button class="item" v-on:click="infoEtapa(compra.Datos.Descripcion)" data-toggle="modal" data-target="#modalObservaciones" data-placement="top" title="Ver observaciones">
                                                                                <i class="fa fa-exclamation-circle"></i>
                                                                            </button>
                                                                        </td>
                                                                        <td>{{compra.Datos.Nombre}}</td>
                                                                        <td>
                                                                            <a class="item" v-bind:href="'../../compras/datos/?Id='+compra.Datos.Id" title="Ver todos los datos">
                                                                                <i class="zmdi zmdi-mail-send"></i>
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
                                        <div class="col-lg-4">
                                            <div class="card">
                                                <div class="card-header">
                                                    <strong>Pagos genéricos</strong>
                                                </div>
                                                <div class="card-body">
                                                    <div class="bootstrap-data-table-panel col-lg-12">
                                                        <div class="table-responsive">
                                                            <button data-toggle="modal" data-target="#modalEfectivo" v-on:click="limpiarFormularioMovimiento()">
                                                                <i class="fa fa-plus-circle text-success"></i> Pagar
                                                            </button>
                                                            <button data-toggle="modal" data-target="#modalCheque" v-on:click="limpiarFormularioMovimiento()">
                                                                <i class="fa fa-plus-circle text-success"></i> Pagar con cheque
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
                                                                            <button class="item" v-on:click="infoEtapa(movimiento.Observaciones)" data-toggle="modal" data-target="#modalObservaciones" data-placement="top" title="Ver observaciones">
                                                                            <i class="fa fa-exclamation-circle"></i>
                                                                            </button>
                                                                            <!-- <button v-on:click="desactivarAlgo(movimiento.Id, 'tbl_dinero_efectivo')" class="item" data-toggle="tooltip" data-placement="top" title="Eliminar">
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
                <!-- modal proveedores -->
                <div class="modal fade" id="categoria_stock_modal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="scrollmodalLabel">Asignar categoría de productos que ofrece este proveedor</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="table-responsive table-responsive-data2">
                                <table class="table table-data2">
                                    <tbody>
                                        <tr class="tr-shadow" v-for="catAsign in lista_categoria_productos_no_vinculados">
                                            <td>
                                                <h4>
                                                    <button class="btn btn-success" v-on:click="Vincular_categoria_productos_proveedor(catAsign.Id)">
                                                        <i class="fa fa-plus"></i>
                                                    </button> &nbsp;&nbsp; {{catAsign.Nombre_categoria}}
                                                </h4>
                                            </td>
                                            <td></td>
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
                                    <form class="form-horizontal" enctype="multipart/form-data" action="post" v-on:submit.prevent="pagoConCheque(chequeSeleccionado)">
                                        <label class="col-sm-12 control-label">Seleccionar Cheque </label>
                                        <div class="form-group">
                                            <select class="form-control" v-model="chequeSeleccionado">
                                                <option v-for="cheque in listaCheques" v-bind:value="cheque">
                                                    ${{cheque.Monto_bruto}} | De {{cheque.Nombre_entrega}}, banco {{cheque.Banco}} | Vencimiento: {{cheque.Vencimiento | Fecha}}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Observaciones</label>
                                            <textarea class="form-control" rows="5" v-model="chequeData.Observaciones_cheque"></textarea>
                                        </div>

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
                <!-- modal DESCRIPCIÓN DEL VINCULO CON EL RUBRO DE PRODUCTOS -->
                <div class="modal fade" id="movimientomodal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="scrollmodalLabel">Editar comentario sobre el producto que vende este proveedor</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form class="form-horizontal" action="post" v-on:submit.prevent="actualizarComentario()">
                                <div class="modal-body">
                                    <div class="horizontal-form">
                                        <div class="form-group">
                                            <label class=" form-control-label">Descripción</label>
                                            <textarea class="form-control" rows="5" v-model="comentarioDatos.Descripcion"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Actualizar</button>
                                </div>
                            </form>
                            <!-- <pre>{{comentarioDatos}}</pre> -->
                            <!-- END DATA TABLE -->
                        </div>
                    </div>
                </div>
                <!-- end modal movimientos -->
                <!-- END MAIN CONTENT-->
                <!-- END PAGE CONTAINER-->
                <!-- /.modal -->
                <?php /// FOOTER
                include "footer.php";
                ?>