<?php
// CABECERA
include "head.php";
// TOP
include "top.php";
include "menusidebar.php";
?>
<!-- PAGE CONTAINER-->
<div class="page-container" id="fabricacion">
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
                                    <img v-if="productoDatos.Imagen != null" width="420px" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+productoDatos.Imagen" alt="">
                                    <img v-else src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" alt="">
                                </div>
                                <h5 class="text-sm-center mt-2 mb-1">{{productoDatos.Nombre_producto}}</h5>
                                <div class="location text-sm-center">
                                    {{productoDatos.Nombre_categoria}}
                                </div>
                            </div>
                        </div>
                    </div>




                    <!-- SECCION FICHA cliente -->
                    <div class="col-lg-10">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 1 }" href="#" v-on:click="mostrar = 1">Ficha</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 2 }" href="#" v-on:click="mostrar = 2">Archivos e historial</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 3 }" href="#" v-on:click="mostrar = 3">Ventas de este producto</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 4 }" href="#" v-on:click="mostrar = 4">Insumos requeridos p/ fabricación</a>
                            </li>
                        </ul>

                        <!-- SECCION DATOS EDITABLES DEL cliente -->
                        <div class="row" v-show="mostrar == '1'">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Ficha: {{productoDatos.Nombre_producto}}</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="horizontal-form">
                                            <form class="form-horizontal" action="post" v-on:submit.prevent="actualizarProducto()">
                                                <div class="form-group">
                                                    <label class=" form-control-label">Nombre</label>
                                                    <input type="text" class="form-control" placeholder="" v-model="productoDatos.Nombre_producto" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Empresa</label>
                                                    <select class="form-control" v-model="productoDatos.Empresa_id" required>
                                                        <option value="0">...</option>
                                                        <option v-for="empresas in listaEmpresas" v-bind:value="empresas.Id">{{empresas.Nombre_empresa}}</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Categoría</label>
                                                    <select class="form-control" v-model="productoDatos.Categoria_fabricacion_id" required>
                                                        <option value="0">...</option>
                                                        <option v-for="categoria in listaCategorias" v-bind:value="categoria.Id">{{categoria.Nombre_categoria}}</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class=" form-control-label">Código interno</label>
                                                    <input type="text" class="form-control" placeholder="" v-model="productoDatos.Codigo_interno">
                                                </div>
                                                <div class="form-group">
                                                    <label class=" form-control-label">Precio en Dolares</label>
                                                    <input type="number" class="form-control" placeholder="" v-model="productoDatos.Precio_USD">
                                                </div>
                                                <div class="form-group">
                                                    <label class=" form-control-label">Descripción Pública Corta</label>
                                                    <textarea class="form-control" rows="5" placeholder="" v-model="productoDatos.Descripcion_publica_corta"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label class=" form-control-label">Descripción Pública Larga (HTML)</label>
                                                    <textarea class="form-control" rows="5" placeholder="" v-model="productoDatos.Descripcion_publica_larga"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Descripción privada</label>
                                                    <textarea class="form-control" rows="5" placeholder="" v-model="productoDatos.Descripcion_tecnica_privada"></textarea>
                                                </div>
                                                <hr>
                                                <button type="submit" class="btn btn-success">Actualizar datos</button>
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
                                        <strong>Archivos e historial sobre {{productoDatos.Nombre_producto}}</strong>
                                    </div>
                                    <div class="card-body">
                                        <p align="right">
                                            <a href="#modalArchivos" data-toggle="modal" title="Nuevo item" class="btn btn-success" v-on:click="limpiarFormularioArchivo()">
                                                <i class="ti-plus"></i> Añadir Reporte/Archivo
                                            </a>
                                        </p>
                                    </div>
                                    <div class="card-body">
                                        <div class="bootstrap-data-table-panel col-lg-12">
                                            <div class="table-responsive">
                                                <table id="table2excel" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Archivo</th>
                                                            <th>Nombre</th>
                                                            <th>Fecha</th>
                                                            <th>Descripcion</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="archivo in listaArchivos">
                                                            <td><a v-if="archivo.Url_archivo != null" target="_blank" v-bind:href="'<?php echo base_url(); ?>uploads/imagenes/'+archivo.Url_archivo">Descargar</a></td>
                                                            <td>{{archivo.Nombre_archivo}}</td>
                                                            <td>{{formatoFecha_hora(archivo.Fecha_hora)}}</td>
                                                            <td>{{archivo.Descripcion}}</td>
                                                            <td>
                                                                <a href="#modalArchivos" data-toggle="modal" v-on:click="editarFormularioarchivo(archivo)">
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

                        <!-- SECCION VENTAS DE ESTE PRODUCTO -->
                        <div class="row" v-show="mostrar == '3'">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Ventas de {{productoDatos.Nombre_producto}}</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="bootstrap-data-table-panel col-lg-12">
                                            <div class="table-responsive">
                                                <table class="table table-data2">
                                                    <thead>
                                                        <tr>
                                                            <th>Cantidad</th>
                                                            <th>Id. Venta</th>
                                                            <th>Cliente</th>
                                                            <th>Vendedor</th>
                                                            <th>Fecha Iniciado</th>
                                                            <th>Fecha Terminado</th>
                                                            <th>Tiempo de fabricación</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="tr-shadow" v-for="venta in listaVentas">

                                                            <td>{{venta.Cantidad_vendida_producto}}</td>
                                                            <td>{{venta.Datos_venta.Identificador_venta}}</td>
                                                            <td>{{venta.Datos_venta.Nombre_cliente}}</td>
                                                            <td>{{venta.Datos_venta.Nombre_vendedor}}</td>
                                                            <td>{{formatoFecha(venta.Datos_venta.Fecha_venta)}}</td>
                                                            <td>{{formatoFecha(venta.Datos_venta.Fecha_finalizada)}}</td>
                                                            <td>{{diferenciasEntre_fechas(venta.Datos_venta.Fecha_venta, venta.Datos_venta.Fecha_finalizada)}}</td>
                                                            <td>
                                                                <div class="table-data-feature">
                                                                    <a class="item" v-bind:href="'../../ventas/datos/?Id='+venta.Datos_venta.Id" title="Ver todos los datos">
                                                                        <i class="zmdi zmdi-mail-send"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        <tr class="spacer"></tr>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </DIV>
                            </DIV>
                        </div><!-- -->

                        <!-- SECCION INSUMOS REQUERIDOS PARA ESTE PRODUCTO -->
                        <div class="row" v-show="mostrar == '4'">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Listado de insumos requeridos para la fabricación de este producto</strong>
                                    </div>
                                    <div class="card-body">
                                        <p align="right">
                                            <a href="#modalInsumos" data-toggle="modal" title="Nuevo item" class="btn btn-success" v-on:click="limpiarFormularioInsumo()">
                                                <i class="ti-plus"></i> Añadir insumo
                                            </a>
                                        </p>
                                    </div>
                                    <div class="card-body">
                                        <div class="bootstrap-data-table-panel col-lg-12">
                                            <div class="table-responsive">
                                                <table id="table2excel" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Insumo</th>
                                                            <th>Cantidad</th>
                                                            <th>Observaciones</th>
                                                            <th>Ult. actualización</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="insumo in listaInsumos">
                                                            <td><b>{{insumo.Nombre_item}}</b></td>
                                                            <td>
                                                                <h3>{{insumo.Cantidad}}</h3>
                                                            </td>
                                                            <td>{{insumo.Observaciones}}</td>
                                                            <td>{{formatoFecha_hora(insumo.Ultima_actualizacion)}}</td>
                                                            <td>
                                                                <a href="#modalInsumos" data-toggle="modal" v-on:click="editarFormularioInsumo(insumo)">
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
    <!-- Modal SEGUIMIENTO-->
    <div class="modal fade" id="modalArchivos" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
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
                        <form class="form-horizontal" enctype="multipart/form-data" action="post" v-on:submit.prevent="cargarArchivo()">
                            <!--   -->
                            <div class="form-group">
                                <label class=" form-control-label">Nombre del archivo</label>
                                <input type="text" class="form-control" placeholder="" v-model="archivoData.Nombre_archivo">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Descripción</label>
                                <textarea class="form-control" rows="5" placeholder="" v-model="archivoData.Descripcion"></textarea>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <input @change="archivoSeleccionado" type="file" class="form-control" name="Imagen">
                                </div>
                                <div class="col-sm-12" v-if="archivoData.Url_archivo != null">
                                    Archivo previamente cargado
                                    <a target="_blank" v-bind:href="'<?php echo base_url(); ?>uploads/imagenes/'+archivoData.Url_archivo"> Ver archivo</a>
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

    <!-- Modal INSUMOS-->
    <div class="modal fade" id="modalInsumos" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{texto_boton}} información sobre insumo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="horizontal-form">
                        <form class="form-horizontal" action="post" v-on:submit.prevent="cargarInsumo()">
                            <!--   -->
                            <div class="form-group" v-if="insumoDatos.Id == null">
                                <label class="control-label">Insumo del stock</label>
                                <select class="form-control" v-model="insumoDatos.Stock_id">
                                    <option value="0">Elegir un insumo</option>
                                    <option v-for="stock in listaStock" v-bind:value="stock.Id">{{stock.Nombre_item}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Cantidad necesaria</label>
                                <input type="number" class="form-control" placeholder="" v-model="insumoDatos.Cantidad">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Observaciones</label>
                                <textarea class="form-control" rows="5" placeholder="" v-model="insumoDatos.Observaciones"></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">{{texto_boton}}</button>
                            </div>
                        </form>
                        <!-- <pre>{{insumoDatos}}</pre> -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal -->

    <!-- end modal movimientos -->
    <!-- END MAIN CONTENT-->
    <!-- END PAGE CONTAINER-->

    <?php
    // FOOTER
    include "footer.php";
    ?>


    </body>

    </html>
    <!-- end document-->