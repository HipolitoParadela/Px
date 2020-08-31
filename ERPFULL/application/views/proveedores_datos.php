<?php
// CABECERA
include "head.php";
// TOP
include "top.php";
include "menusidebar.php";
?>
<!-- PAGE CONTAINER-->
<div class="page-container" id="proveedores">
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
                                <div class="user-photo m-b-30">
                                    <img v-if="proveedoresDatos.Imagen != null" width="420px" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+proveedoresDatos.Imagen" alt="">
                                    <img v-else src="<?php echo base_url(); ?>uploads/addimagen.jpg" alt="">
                                </div>
                                <h5 class="text-sm-center mt-2 mb-1">{{proveedoresDatos.Nombre_proveedor}}</h5>
                                <div class="location text-sm-center">
                                    <i class="fa fa-map-marker"></i> {{proveedoresDatos.Direccion}}, {{proveedoresDatos.Localidad}}, {{proveedoresDatos.Provincia}}, {{proveedoresDatos.Pais}}
                                </div>
                            </div>

                        </div>
                        <div class="card">
                            <div class="card-header">
                                <strong>Estado de cuenta</strong>
                            </div>
                            <div class="card-body">
                                <h1 align="center" v-bind:class="[{ 'text-danger' : cuentaProveedor(listaComprasProveedor) < 0}]">$ {{ cuentaProveedor(listaComprasProveedor) | Moneda }}</h1>
                            </div>
                        </div>
                        <div>
                            <a target="_blank" v-bind:href="'https://api.whatsapp.com/send?phone='+proveedoresDatos.Telefono" class="btn btn-success btn-block">
                                <i class="fab fa-whatsapp"></i> Enviar whatsapp
                            </a>
                            <hr>
                            <a target="_blank" v-bind:href="'mailto:'+proveedoresDatos.Email" class="btn btn-info btn-block">
                                <i class="fa fa-envelope"></i> Enviar email
                            </a>

                            <hr>
                            <span v-show="proveedoresDatos.Web != null">
                                <a target="_blank" v-bind:href="'http://'+proveedoresDatos.Web" class="btn btn-secondary btn-block">
                                    <i class="fa fa-share-square"></i> {{proveedoresDatos.Web}}
                                </a>
                            </span>
                            <hr>
                            <span v-show="proveedoresDatos.URL_facebook != null">
                                <a target="_blank" v-bind:href="proveedoresDatos.URL_facebook" class="btn btn-secondary btn-block">
                                    <i class="fa fa-share-square"></i> Facebook de {{proveedoresDatos.Nombre_proveedor}}
                                </a>
                            </span>
                        </div>
                    </div>

                    <!-- SECCION FICHA cliente -->
                    <div class="col-lg-10">
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

                        <!-- SECCION DATOS EDITABLES DEL cliente -->
                        <div class="row" v-show="mostrar == '1'">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Ficha: {{proveedoresDatos.Nombre_proveedor}}</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="horizontal-form">
                                            <form class="form-horizontal" action="post" v-on:submit.prevent="crearProveedor()">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class=" form-control-label">Nombre</label>
                                                            <input type="text" class="form-control" placeholder="" v-model="proveedoresDatos.Nombre_proveedor" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class=" form-control-label">CUIT/CUIL</label>
                                                            <input type="text" class="form-control" placeholder="" v-model="proveedoresDatos.CUIT_CUIL" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class=" form-control-label">Producto/Servicio que brinda</label>
                                                            <textarea class="form-control" rows="5" v-model="proveedoresDatos.Producto_servicio"></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class=" form-control-label">Teléfono whatsapp - numero entero de corrido</label>
                                                            <input type="tel" class="form-control" placeholder="" v-model="proveedoresDatos.Telefono">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class=" form-control-label">Teléfono Fijo</label>
                                                            <input type="tel" class="form-control" placeholder="" v-model="proveedoresDatos.Telefono_fijo">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class=" form-control-label">Dirección</label>
                                                            <input type="text" class="form-control" placeholder="" v-model="proveedoresDatos.Direccion" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Localidad</label>
                                                            <input type="text" class="form-control" placeholder="" v-model="proveedoresDatos.Localidad" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Provincia</label>
                                                            <input type="text" class="form-control" placeholder="" v-model="proveedoresDatos.Provincia" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Pais</label>
                                                            <input type="text" class="form-control" placeholder="" v-model="proveedoresDatos.Pais" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Email</label>
                                                            <input type="email" class="form-control" placeholder="" v-model="proveedoresDatos.Email">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Web - Sin "http://"</label>
                                                            <input type="text" class="form-control" placeholder="" v-model="proveedoresDatos.Web">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">URL de su fanpage</label>
                                                            <input type="text" class="form-control" placeholder="" v-model="proveedoresDatos.URL_facebook">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Nombre de persona de contacto en la empresa</label>
                                                            <input type="text" class="form-control" placeholder="" v-model="proveedoresDatos.Nombre_persona_contacto">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class=" form-control-label">Información de la persona de contacto</label>
                                                            <textarea class="form-control" rows="5" v-model="proveedoresDatos.Datos_persona_contacto"></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class=" form-control-label">Más información sobre el cliente</label>
                                                            <textarea class="form-control" rows="5" v-model="proveedoresDatos.Mas_datos_cliente"></textarea>
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
                                                            <th>Proveedor</th>
                                                            <th>Fecha</th>
                                                            <th>Descripcion</th>
                                                            <th>Archivo</th>
                                                            <th>Usuario</th>
                                                            <th></th>
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
                                        <p align="right">
                                            <a v-on:click="obtener_listado_de_categorias_no_asignados()" href="#categoria_stock_modal" data-toggle="modal" title="Añadir categoría" class="btn btn-success btn-flat btn-addon m-b-10 m-l-5 btn-sm">
                                                <i class="ti-plus"></i> Añadir categoría de producto
                                            </a>
                                        </p>
                                    </div>
                                    <div class="card-body">
                                        <div class="bootstrap-data-table-panel col-lg-12">
                                            <div class="table-responsive">
                                                <table id="table2excel" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Nombre</th>
                                                            <th>Comentarios</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- <tr v-for="producto in listaProductos"> -->
                                                        <tr v-for="categoria in lista_categoria_productos_vinculados">
                                                            <td>{{categoria.Nombre_categoria}}</td>
                                                            <td>{{categoria.Descripcion}}</td>
                                                            <td>
                                                                <button class="item" v-on:click="editarFormularioComentario(categoria)" data-toggle="modal" data-target="#movimientomodal" data-placement="top" title="Edición rápida">
                                                                    <i class="zmdi zmdi-edit"></i>
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
                            <div class="col-lg-12">
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
                                                            <th style="background-color:lightpink">Valor</th>
                                                            <th style="background-color:darkseagreen">Pag.</th>
                                                            <th style="background-color:wheat">Saldo</th>
                                                            <th>Fecha</th>
                                                            <th>Descripción</th>
                                                            <th>Responsable</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- <tr v-for="producto in listaProductos"> -->
                                                        <tr v-for="compra in listaComprasProveedor">
                                                            <td>{{compra.Datos.Factura_identificador}}</td>
                                                            <td style="background-color:lightpink">
                                                                <h4 align="right">
                                                                    {{sumarComrpra(compra.Datos.Neto, compra.Datos.No_gravado, compra.Datos.IVA) | Moneda}}
                                                                </h4>
                                                            </td>
                                                            <td style="background-color:darkseagreen">
                                                                <h4 align="right">
                                                                    {{compra.Total | Moneda}}
                                                                </h4>
                                                            </td>
                                                            <td style="background-color:wheat">
                                                                <h4 align="right">
                                                                    {{sumarComrpra(compra.Datos.Neto, compra.Datos.No_gravado, compra.Datos.IVA) - compra.Total | Moneda}}
                                                                </h4>
                                                            </td>
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
                        </div>
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
    <!-- END MAIN CONTENT-->
    <!-- END PAGE CONTAINER-->
    <!-- modal movimientos -->
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
                <pre>{{comentarioDatos}}</pre>
                <!-- END DATA TABLE -->
            </div>
        </div>
    </div>
    <!-- end modal movimientos -->

    <?php
    // CABECERA
    include "footer.php";
    ?>
    </body>

    </html>
    <!-- end document-->