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

                    <div class="col-md-12">
                        <!-- DATA TABLE -->
                        <h3 class="title-5 m-b-35">Máquinas y productos de fabricació propia</h3>



                        <div class="table-data__tool">
                            <div class="table-data__tool-left">
                                <div class="rs-select2--light">
                                    <select class="form-control-sm form-control" v-model="filtro_categoria" v-on:change="getListadoProductos(filtro_categoria)">
                                        <option selected="selected" v-bind:value="0">Todas las categorías</option>
                                        <option v-for="categoriaSeleccionada in listaCategorias" v-bind:value="categoriaSeleccionada.Id">{{categoriaSeleccionada.Nombre_categoria}}</option>
                                    </select>
                                    <div class="dropDownSelect2"></div>
                                </div>
                                <div class="rs-select2--light ">
                                    <input type="text" class="form-control-sm form-control" placeholder="Buscar producto" v-model="buscar">
                                </div>
                            </div>
                            <div class="table-data__tool-right">
                                <button class="au-btn au-btn-icon au-btn--green au-btn--small" data-toggle="modal" data-target="#productomodal" v-on:click="limpiarFormularioProveedor()">
                                    <i class="zmdi zmdi-plus"></i>Nuevo producto
                                </button>
                                <button class="au-btn au-btn-icon au-btn--blue au-btn--small" data-toggle="modal" data-target="#categoriaModal" v-on:click="limpiarFormularioCategoriaProductos()">
                                    <!--<i class="zmdi zmdi-plus"></i>-->Categorias
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive table-responsive-data2">
                            <table class="table table-data2">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Nombre</th>
                                        <th>Código interno</th>
                                        <th>Categoría </th>
                                        <th>Descripción</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="tr-shadow" v-for="producto in buscarProducto">
                                        <td>
                                            <div class="round-img">
                                                <a href="#modalproductosFoto" data-toggle="modal" v-on:click="editarFormularioProductoFoto(producto)">
                                                    <img v-if="producto.Imagen != null" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+producto.Imagen" width="60px">
                                                    <img v-else src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" width="50px" alt="">
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <a v-bind:href="'fabricacion/datos/?Id='+producto.Id" class="btn btn-dark btn-outline m-b-10 m-l-5">
                                                {{producto.Nombre_producto}}
                                            </a>
                                        </td>
                                        <td>{{producto.Codigo_interno}}</td>
                                        <td><span class="block-email">{{producto.Nombre_categoria}}</span></td>
                                        <td>{{producto.Descripcion_publica_corta}}</td>
                                        <td>
                                            <div class="table-data-feature">

                                                <a class="item" v-bind:href="'fabricacion/datos/?Id='+producto.Id" title="Ver todos los datos">
                                                    <i class="zmdi zmdi-mail-send"></i>
                                                </a>
                                                <button class="item" v-on:click="editarFormularioProducto(producto)" data-toggle="modal" data-target="#productomodal" data-placement="top" title="Edición rápida">
                                                    <i class="zmdi zmdi-edit"></i>
                                                </button>

                                                <button v-on:click="desactivarProducto(producto.Id)" class="item" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                                    <i class="zmdi zmdi-delete"></i>
                                                </button>

                                            </div>
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
        </div>
    </div>
    <!-- modal productos -->
    <div class="modal fade" id="productomodal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollmodalLabel">Productos de fabricación propia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal" action="post" v-on:submit.prevent="crearProducto()">
                    <div class="modal-body">
                        <div class="horizontal-form">
                            <div class="form-group">
                                <label class=" form-control-label">Nombre</label>
                                <input type="text" class="form-control" placeholder="" v-model="productoDatos.Nombre_producto" required>
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
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">{{texto_boton}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal proveedors -->
    <!-- Modal proveedors Fotos-->
    <div class="modal fade" id="modalproductosFoto" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalItemsFoto">Fotografía de {{productoFoto.Nombre_producto}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p align="center">
                        <img v-if="productoFoto.Imagen != null" class="avatar_grande" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+productoFoto.Imagen" alt="">
                        <img v-else class="avatar_grande" src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" alt="">
                    </p>
                    <hr>
                    <div class="horizontal-form">
                        <!-- <form class="form-horizontal" action="post" enctype="multipart/form-data" v-on:submit.prevent="crearproveedors()">  -->
                        <form class="form-horizontal" action="post" enctype="multipart/form-data" v-on:submit.prevent="uploadFotoProducto(productoFoto.Id)">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <input @change="archivoSeleccionado" type="file" class="form-control" name="Imagen">
                                </div>
                            </div>
                            <p v-show="preloader == 1">
                                <img src="http://grupopignatta.com.ar/images/preloader.gif" alt="">
                            </p>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-12">
                                    <button type="submit" class="btn btn-success">{{texto_boton}} imagen</button>
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
    <!-- modal categorias -->
    <div class="modal fade" id="categoriaModal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollmodalLabel">Listado de categorías</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <table class="table table-borderless table-striped table-earning">
                        <thead>
                            <tr>
                                <th>Nombre categoría</th>
                                <th>Descripción</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="categoriaDatos in listaCategorias">
                                <td>{{categoriaDatos.Nombre_categoria}}</td>
                                <td>{{categoriaDatos.Descripcion}}</td>
                                <td>
                                    <button class="item" v-on:click="editarFormularioCategoriaProductos(categoriaDatos)" title="Editar">
                                        <i class="zmdi zmdi-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <form class="form-horizontal" action="post" v-on:submit.prevent="crearCategoriaProductos()">
                    <div class="modal-body">
                        <div class="horizontal-form">
                            <div class="form-group">
                                <label class=" form-control-label">Nombre de la categoría</label> <input type="text" class="form-control" v-model="categoriaDatos.Nombre_categoria">
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Descripción</label>
                                <textarea class="form-control" rows="5" v-model="categoriaDatos.Descripcion"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">{{texto_boton}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal categorias -->
    <!-- END MAIN CONTENT-->
    <!-- END PAGE CONTAINER-->
    <?php
    // CABECERA
    include "footer.php";
    ?>
    </body>

    </html>
    <!-- end document-->