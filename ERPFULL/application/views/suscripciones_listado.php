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
                        <h3 class="title-5 m-b-35">Listado de Suscripciones</h3>

                        <div class="table-data__tool">
                            <div class="table-data__tool-left">
                                <div class="rs-select2--light">
                                    <select class="form-control-sm form-control" v-model="filtro_categoria" v-on:change="getListadoSuscripciones(filtro_categoria)">
                                        <option selected="selected" v-bind:value="0">Todas las categorías</option>
                                        <option v-for="categoriaSeleccionada in listaCategorias" v-bind:value="categoriaSeleccionada.Id">{{categoriaSeleccionada.Nombre_categoria}}</option>
                                    </select>
                                    <div class="dropDownSelect2"></div>
                                </div>
                                <div class="rs-select2--light">
                                    <select class="form-control-sm form-control" v-model="filtro_estado" v-on:change="getListadoSuscripciones(filtro_categoria, filtro_estado)">
                                        <option selected="selected" v-bind:value="1">Solo suscripciones activas</option>
                                        <option selected="selected" v-bind:value="0">Todas las suscripciones</option>
                                    </select>
                                    <div class="dropDownSelect2"></div>
                                </div>
                                <div class="rs-select2--light ">
                                    <input type="text" class="form-control-sm form-control" placeholder="Buscar suscripciones" v-model="buscar">
                                </div>
                            </div>
                            <div class="table-data__tool-right">
                                <button class="au-btn au-btn-icon au-btn--green au-btn--small" data-toggle="modal" data-target="#suscripcionesModal" v-on:click="limpiarFormularioSuscripcion()">
                                    <i class="zmdi zmdi-plus"></i>Nuevo Suscripción
                                </button>
                                <button class="au-btn au-btn-icon au-btn--blue au-btn--small" data-toggle="modal" data-target="#categoriaModal" v-on:click="limpiarFormularioCategorias()">
                                    Gestionar Categorías
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive table-responsive-data2">
                            <table class="table table-data2">
                                <thead class="card-header">
                                    <tr>
                                        <th></th>
                                        <th>Suscripcion</th>
                                        <th>Cliente</th>
                                        <th>Categoría</th>
                                        <th>F. Inicio</th>
                                        <th>F. finalización</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="tr-shadow" v-for="suscripciones in buscarSuscripcion">
                                        <td>
                                            <div class="round-img">
                                                <a href="#modalsuscripcionesFoto" data-toggle="modal" v-on:click="editarFormularioSuscripcionFoto(suscripciones)">
                                                    <img v-if="suscripciones.Imagen != null" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+suscripciones.Imagen" width="60px">
                                                    <img v-else src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" width="50px" alt="">
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <a v-bind:href="'suscripciones/datos/?Id='+suscripciones.Id" class="btn btn-dark btn-outline m-b-10 m-l-5">
                                                {{suscripciones.Titulo_suscripcion}}
                                            </a>
                                        </td>
                                        <td>{{suscripciones.Nombre_cliente}}</td>
                                        <td>{{suscripciones.Nombre_categoria}}</td>
                                        <td>{{suscripciones.Fecha_inicio_servicio | Fecha}}</td>
                                        <td>{{suscripciones.Fecha_finalizacion_servicio | Fecha}}</td>
                                        <td>
                                            <div class="table-data-feature">

                                                <a class="item" v-bind:href="'suscripciones/datos/?Id='+suscripciones.Id" title="Ver todos los datos">
                                                    <i class="zmdi zmdi-mail-send"></i>
                                                </a>
                                                <button class="item" v-on:click="editarFormularioSuscripciones(suscripciones)" data-toggle="modal" data-target="#suscripcionesModal" data-placement="top" title="Edición rápida">
                                                    <i class="zmdi zmdi-edit"></i>
                                                </button>
                                                <?php
                                                if ($this->session->userdata('Rol_acceso') > 4) {
                                                    echo '
                                                        <button v-on:click="desactivarSuscripcion(suscripciones.Id)" class="item" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                                            <i class="zmdi zmdi-delete"></i>
                                                        </button>';
                                                }
                                                ?>

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
    <!-- modal suscripciones -->
    <div class="modal fade " id="suscripcionesModal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollmodalLabel">Formulario de suscripciones</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal" action="post" v-on:submit.prevent="crearSuscripcion()">
                    <div class="modal-body">
                        <div class="horizontal-form">
                            <div class="form-group">
                                <label class=" form-control-label">Títutulo Suscripción</label>
                                <input type="text" class="form-control" v-model="suscripcionesDatos.Titulo_suscripcion" required>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Cliente</label>
                                <select class="form-control-sm form-control" v-model="suscripcionesDatos.Cliente_id">
                                    <option v-for="cliente in listaClientes" v-bind:value="cliente.Id">{{cliente.Nombre_cliente}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Categoría</label>
                                <select class="form-control-sm form-control" v-model="suscripcionesDatos.Categoria_id">

                                    <option v-for="categoria in listaCategorias" v-bind:value="categoria.Id">{{categoria.Nombre_categoria}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Fecha inicio del servicio</label>
                                <input type="date" class="form-control" v-model="suscripcionesDatos.Fecha_inicio_servicio" required>
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Fecha finalización del servicio</label>
                                <input type="date" class="form-control" v-model="suscripcionesDatos.Fecha_finalizacion_servicio">
                               
                            </div>
                            <div class="form-group">
                                <label class="control-label">Monto fijo a la fecha</label>
                                <input type="number" class="form-control" v-model="suscripcionesDatos.Monto">
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">{{texto_boton}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal suscripciones -->
    <!-- Modal suscripciones Fotos-->
    <div class="modal fade" id="modalsuscripcionesFoto" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalItemsFoto">Imagen de {{suscripcionesFoto.Titulo_suscripcion}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p align="center">
                        <img v-if="suscripcionesFoto.Imagen != null" class="avatar_grande" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+suscripcionesFoto.Imagen" alt="">
                        <img v-else class="avatar_grande" src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" alt="">
                    </p>
                    <hr>
                    <div class="horizontal-form">
                        <!-- <form class="form-horizontal" action="post" enctype="multipart/form-data" v-on:submit.prevent="crearsuscripciones()">  -->
                        <form class="form-horizontal" action="post" enctype="multipart/form-data" v-on:submit.prevent="uploadFotoSuscripcion(suscripcionesFoto.Id)">
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
    <!-- modal categoria -->
    <div class="modal fade" id="categoriaModal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollmodalLabel">Listado de categorias</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless table-striped table-earning">
                        <tbody>
                            <tr v-for="categoria in listaCategorias">
                                <td><b>{{categoria.Nombre_categoria}}</b></td>
                                <td>{{categoria.Descripcion}}</td>
                                <td>
                                    <button class="item" v-on:click="editarFormularioCategoria(categoria)" title="Editar">
                                        <i class="zmdi zmdi-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                </div>
                <form class="form-horizontal" action="post" v-on:submit.prevent="crearCategoriaSuscripciones()">
                    <div class="modal-body">
                        <div class="horizontal-form">
                            <div class="form-group">
                                <label class=" form-control-label">Indentifcador de la categoria</label> <input type="text" class="form-control" v-model="categoriaDato.Nombre_categoria">
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Descripción de la categoria</label>
                                <textarea class="form-control" rows="5" v-model="categoriaDato.Descripcion"></textarea>
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
    <!-- end modal categoria -->
    <!-- END MAIN CONTENT-->
    <!-- END PAGE CONTAINER-->
    <?php
    // CABECERA
    include "footer.php";
    ?>
    </body>

    </html>
    <!-- end document-->