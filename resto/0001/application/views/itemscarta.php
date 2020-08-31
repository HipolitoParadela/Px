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
    <div class="content-wrap" id="app">
        <div class="main">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-lg-8 p-r-0 title-margin-right">
                        <div class="page-header">
                            <div class="page-title">
                                <h1>Carta, <span>Listado completo de items</span></h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Sistema restaurant</a></li>
                                    <li class="breadcrumb-item active">Listado completo de items</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->

                <section id="main-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <a href="#modalCategorias" data-toggle="modal" title="Nuevo item" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5" v-on:click="limpiarFormularioCategorias()"><i class="ti-settings"></i> Categorías</a>
                            <a href="#modalItemsCarta" data-toggle="modal" title="Nuevo item" class="btn btn-success btn-flat btn-addon m-b-10 m-l-5" v-on:click="limpiarFormularioItems()"><i class="ti-plus"></i> Nuevo item</a>
                        </div>
                        <div class="col-lg-2">
                            <div class="input-group input-group-default">
                                <span class="input-group-btn"><button class="btn btn-primary" type="submit"><i class="ti-search"></i></button></span>
                                <input type="text" class="form-control" placeholder="Buscar por nombre" v-model="buscar">
                            </div>
                            <div class="card">
                                <div class="list-group">
                                    <a href="#" class="list-group-item" v-on:click="getListadoItems()">Todos</a>
                                    <a href="#" v-for="listaCategoria in categoriasCarta" class="list-group-item" v-on:click="cargarItemsbyCategoria(listaCategoria.Id)">{{listaCategoria.Nombre_categoria}}</a>

                                    <!-- <a href="#" class="list-group-item" v-bind:class="{ active: categoria.isActive }">Todos</a> -->

                                </div>
                            </div>
                        </div>
                        <!-- /# card -->
                        <div class="col-lg-10">
                            <div class="card">
                                <div class="bootstrap-data-table-panel">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Imagen</th>
                                                    <th>Nombre</th>
                                                    <th width="120px">Precio</th>
                                                    <th>Descripción</th>
                                                    <th>Categoría</th>
                                                    <th width="60px">Tiempo</th>
                                                    <th width="120px">Actualizado</th>
                                                    <th width="40px">Activo</th>
                                                    <th width="10px"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="item in buscarItems">
                                                    <td valign="middle">
                                                        <a href="#modalFoto" data-toggle="modal" v-on:click="editarFormularioItemFoto(item)">
                                                            <img v-if="item.Imagen != null" class="avatar" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+item.Imagen" alt="">
                                                            <img v-else class="avatar" src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" alt="">
                                                        </a>
                                                    </td>
                                                    <td>{{item.Nombre_item}}</td>
                                                    <td><h4 align="center">${{item.Precio_venta | Moneda}}</h4></td>
                                                    <td>{{item.Descripcion}}</td>
                                                    <td>{{item.Nombre_categoria}}</td>
                                                    <td>{{item.Tiempo_estimado_entrega}}'</td>
                                                    <td>{{item.Ult_act | FechaTimestampBaseDatos}}</td>
                                                    <td>
                                                        <a href="#" v-on:click="activarItem(item)" title="Dar click para que este item no se muestre en la carta actualmente">
                                                            <span v-if="item.Activo == 1" class="badge badge-success"><i class="ti-check"></i></span>
                                                        </a>
                                                        <a href="#" v-on:click="activarItem(item)" title="Dar click para que este item Si se muestre en la carta actualmente">
                                                            <span v-if="item.Activo == 0" class="badge badge-danger"><i class="ti-na"></i></span>
                                                        </a>
                                                    </td>
                                                    <td valign="middle">
                                                        <a href="#modalItemsCarta" data-toggle="modal" v-on:click="cargarFormularioItem(item)">
                                                            <i class="ti-pencil-alt"></i></a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- /# card -->
                        </div>
                        <!-- /# column -->
                    </div>
                    <!-- /# row -->


                </section>
            </div>

        </div>
        <!-- Modal ITEMS CARTA -->
        <div class="modal fade" id="modalItemsCarta" tabindex="-1" role="dialog" aria-labelledby="modalItemsCartaTitle" aria-hidden="true">
            <div class="modal-dialog  modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalItemsCartaTitle">{{texto_boton}} item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="horizontal-form">
                            <!-- {{itemCarta}} -->
                            <form class="form-horizontal" action="post" v-on:submit.prevent="crearItem()">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Nombre</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" v-model="itemCarta.Nombre_item" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Categoría</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" v-model="itemCarta.Categoria_id" required>
                                            <option v-for="categoria in categoriasCarta" v-bind:value="categoria.Id">{{categoria.Nombre_categoria}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Descripción</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" rows="5" v-model="itemCarta.Descripcion"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">

                                    <label class="col-sm-2 control-label">Precio (Requerido)</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" v-model="itemCarta.Precio_venta" required>
                                    </div>
                                </div>
                                <div class="form-group">

                                    <label class="col-sm-2 control-label">Apto delivery (Requerido)</label>
                                    <div class="col-sm-10">
                                        Si <input type="radio" name="Apto_delivery" value="1" v-model="itemCarta.Apto_delivery" required>
                                        No <input type="radio" name="Apto_delivery" value="0" v-model="itemCarta.Apto_delivery" required>
                                    </div>
                                </div>
                                <div class="form-group">

                                    <label class="col-sm-2 control-label" >Apto stockeo (Requerido)</label>
                                    <div class="col-sm-10">
                                        Si <input type="radio" name="Apto_stock" value="1" v-model="itemCarta.Apto_stock" required>
                                        No <input type="radio" name="Apto_stock" value="0" v-model="itemCarta.Apto_stock" required>
                                    </div>
                                </div>
                                <div class="form-group" v-show="itemCarta.Apto_stock == 1">
                                    <label v-if="itemCarta.Id == null" class="col-sm-2 control-label">Cantidad actual</label>
                                    <div v-if="itemCarta.Id == null" class="col-sm-10">
                                        <input type="number" class="form-control" v-model="itemCarta.Cant_actual">
                                    </div>
                                </div>
                                <div class="form-group" v-show="itemCarta.Apto_stock == 1">
                                    <label v-if="itemCarta.Id == null" class="col-sm-2 control-label">Cantidad ideal</label>
                                    <div v-if="itemCarta.Id == null" class="col-sm-10">
                                        <input type="number" class="form-control" v-model="itemCarta.Cant_ideal">
                                    </div>
                                </div>
                                <div class="form-group">

                                    <label class="col-sm-2 control-label">Tiempo aprox. entrega</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" v-model="itemCarta.Tiempo_estimado_entrega" required>
                                            <option value="5">5 min</option>
                                            <option value="10">10 min</option>
                                            <option value="15">15 min</option>
                                            <option value="20">20 min</option>
                                            <option value="30">30 min</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <!-- <h1>{{itemCarta.Id}}</h1> -->
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" class="btn btn-success">{{texto_boton}}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal -->
        <!-- Modal cATEGORIAS-->
        <div class="modal fade" id="modalCategorias" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="true">
            <div class="modal-dialog  modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalItemsCartaTitle">{{texto_boton}} categorías</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="horizontal-form">
                            <form class="form-horizontal" action="post">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Nombre" v-model="categoria.Nombre_categoria">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        ¿Visualizar esta categoría en stock?<br>
                                        Si <input type="radio" name="Apto_stock" value="1" v-model="categoria.Apto_stock">
                                        No <input type="radio" name="Apto_stock" value="0" v-model="categoria.Apto_stock">
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <textarea class="form-control" rows="5" placeholder="Descripción" v-model="categoria.Descripcion_categoria"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <button type="submit" v-on:click.prevent="crearCategoria()" class="btn btn-success">{{texto_boton}} categoría</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr>
                    <div class="modal-body">
                        <div class="horizontal-form">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="categoria in categoriasCarta">
                                        <td>{{categoria.Nombre_categoria}}</td>
                                        <td>{{categoria.Descripcion_categoria}}</td>
                                        <td valign="middle">
                                            <a v-on:click="editarFormularioCategoriaCarta(categoria)">
                                                <i class="ti-pencil-alt"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal -->
        <!-- Modal  Fotos-->
        <div class="modal fade" id="modalFoto" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="true">
            <div class="modal-dialog  modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalItemsFoto">Fotografía de {{itemFoto.Nombre}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p align="center">
                            <img v-if="itemFoto.Imagen != null" class="avatar_grande" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+itemFoto.Imagen" alt="">
                            <img v-else class="avatar_grande" src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" alt="">
                        </p>
                        <div class="horizontal-form">
                            <!-- <form class="form-horizontal" action="post" enctype="multipart/form-data" v-on:submit.prevent="crearUsuarios()">  -->
                            <form class="form-horizontal" action="post" enctype="multipart/form-data" v-on:submit.prevent="uploadItem(itemFoto.Id)">
                                <div class="form-group">
                                    <p>Nueva imagen</p>
                                    <div class="col-sm-10">
                                        <input @change="archivoSeleccionado" type="file" class="form-control" name="Imagen">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" class="btn btn-success">{{texto_boton}}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal -->
        <?php /// CABECERA BODY
        include "footer.php";
        ?>