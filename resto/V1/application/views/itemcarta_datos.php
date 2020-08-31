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
    <div class="content-wrap" id="itemcarta">
        <div class="main">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-lg-8 p-r-0 title-margin-right">
                        <div class="page-header">
                            <div class="page-title">
                                <h1>Receta, <span>Datos</span></h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="../">Carta</a></li>
                                    <li class="breadcrumb-item active">{{itemDatos.Nombre_item}}</li>
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
                                        <div class="card-body">
                                            <div class="user-photo m-b-30">
                                                <img class="img-fluid" v-if="itemDatos.Imagen != null" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+itemDatos.Imagen" alt="">
                                                <img class="img-fluid" v-else src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" alt="">
                                            </div>
                                            <h3 class="text-sm-center mt-2 mb-1">{{itemDatos.Nombre_item}}</h3>
                                            <div class="location text-sm-center">
                                                {{itemDatos.Nombre_categoria}}
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
                                            <a class="nav-link" v-bind:class="{ active: mostrar == 4 }" href="#" v-on:click="mostrar = 4">Receta</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" v-bind:class="{ active: mostrar == 3 }" href="#" v-on:click="mostrar = 3">Estadísticas</a>
                                        </li>
                                        
                                    </ul>

                                    <!-- SECCION DATOS EDITABLES DEL cliente -->
                                    <div class="row" v-show="mostrar == '1'">
                                        <div class="col-lg-5">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="horizontal-form">
                                                        <form class="form-horizontal" action="post" v-on:submit.prevent="actualizarProducto()">
                                                            <div class="form-group">
                                                                <label class=" form-control-label">Nombre</label>
                                                                <input type="text" class="form-control" placeholder="" v-model="itemDatos.Nombre_item" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Categoría</label>
                                                                <select class="form-control" v-model="itemDatos.Categoria_id" required>
                                                                    <option value="0">...</option>
                                                                    <option v-for="categoria in listaCategorias" v-bind:value="categoria.Id">{{categoria.Nombre_categoria}}</option>
                                                                </select>
                                                            </div>
                                                            <label class="col-sm-12 control-label">Descripción</label>
                                                            <div class="col-sm-12">
                                                                <textarea class="form-control" rows="5" v-model="itemDatos.Descripcion"></textarea>
                                                            </div>


                                                            <label class="col-sm-12 control-label">Precio Venta </label>
                                                            <div class="col-sm-12">
                                                                <input type="number" class="form-control" v-model="itemDatos.Precio_venta" required>
                                                            </div>
                                                            <label class="col-sm-12 control-label">Precio Costo</label>
                                                            <div class="col-sm-12">
                                                                <input type="number" class="form-control" v-model="itemDatos.Precio_costo" required>
                                                            </div>


                                                            <label class="col-sm-12 control-label">Apto delivery (Requerido)</label>
                                                            <div class="col-sm-12">
                                                                Si <input type="radio" name="Apto_delivery" value="1" v-model="itemDatos.Apto_delivery" required>
                                                                No <input type="radio" name="Apto_delivery" value="0" v-model="itemDatos.Apto_delivery" required>
                                                            </div>
                                                            <hr>
                                                            <button type="submit" class="btn btn-success">Actualizar datos</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="card">
                                                <div class="card-header">
                                                    <strong>Archivos e historial sobre {{itemDatos.Nombre_item}}</strong>
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
                                                                        <th>
                                                                            <a href="#modalArchivos" data-toggle="modal" title="Nuevo item" class="btn btn-success" v-on:click="limpiarFormularioArchivo()">
                                                                                <i class="ti-plus"></i> Añadir Reporte/Archivo
                                                                            </a>
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-for="archivo in listaArchivos">
                                                                        <td>
                                                                            <a v-if="archivo.Url_archivo != null" target="_blank" v-bind:href="'<?php echo base_url(); ?>uploads/imagenes/'+archivo.Url_archivo">Descargar</a>
                                                                        </td>
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

                                    <!-- SECCION ESTADISTICAS -->
                                    <div class="row" v-show="mostrar == '3'">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="bootstrap-data-table-panel col-lg-12">
                                                        <div class="table-responsive">
                                                            Ventas totales, ventas delivery, ventas local, monto bruto al momento. 
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
                                                    <div class="bootstrap-data-table-panel col-lg-12">
                                                        <div class="table-responsive">
                                                            <table id="table2excel" class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Insumo</th>
                                                                        <th>Cantidad</th>
                                                                        <th>Observaciones</th>
                                                                        <th>Ult. actualización</th>
                                                                        <th><a href="#modalInsumos" data-toggle="modal" title="Nuevo item" class="btn btn-success" v-on:click="limpiarFormularioInsumo()">
                                                            <i class="ti-plus"></i> Añadir insumo
                                                        </a></th>
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

                </section>
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
                <?php /// FOOTER
                include "footer.php";
                ?>