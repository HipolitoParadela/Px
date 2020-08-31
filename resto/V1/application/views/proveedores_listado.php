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
                                <h1>Listado de proveedores <span></span></h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Sistema restaurant</a></li>
                                    <li class="breadcrumb-item active">Proveedores</li>
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
                            <a href="#rubrosModal" data-toggle="modal" title="Nuevo item" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5" v-on:click="limpiarFormularioRubros()"><i class="ti-settings"></i> Gestionar Rubros</a>
                            <a href="#proveedormodal" data-toggle="modal" title="Nuevo item" class="btn btn-success btn-flat btn-addon m-b-10 m-l-5" v-on:click="limpiarFormularioProveedor()"><i class="ti-plus"></i> Nuevo proveedor</a>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-lg-2">
                            <div class="card">
                                <div class="list-group">
                                    <a href="#" class="list-group-item" v-on:click="getListadoProveedores(filtro_rubro)">Todos</a>
                                    <a href="#" v-for="rubro in listaRubros" class="list-group-item" v-on:click="getListadoProveedores(rubro.Id)">
                                        {{rubro.Nombre_rubro}}
                                    </a>

                                    <!-- <a href="#" class="list-group-item" v-bind:class="{ active: categoria.isActive }">Todos</a> -->

                                </div>
                            </div>
                        </div>
                        <!-- /# card -->

                        <div class="col-lg-10">
                            <div class="card">
                                <div class="input-group input-group-default">
                                    <span class="input-group-btn"><button class="btn btn-primary" type="submit"><i class="ti-search"></i></button></span>
                                    <input type="text" class="form-control" placeholder="Nombre del proveedor" v-model="buscar">
                                </div>
                            </div>
                            <div class="card">
                                <div class="bootstrap-data-table-panel">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Nombre</th>
                                                    <th>Cuenta</th>
                                                    <th>Rubro</th>
                                                    <th>CUIT/CUIL</th>
                                                    <th>Teléfono</th>
                                                    <th>Email</th>
                                                    <th>Web</th>
                                                    <th>Persona Contacto</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="tr-shadow" v-for="proveedor in buscarProveedor">
                                                    <td>
                                                        <div class="round-img">
                                                            <a href="#modalproveedorsFoto" data-toggle="modal" v-on:click="editarFormularioProveedorFoto(proveedor.Datos_proveedor)">
                                                                <img v-if="proveedor.Datos_proveedor.Imagen != null" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+proveedor.Datos_proveedor.Imagen" width="60px">
                                                                <img v-else src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" width="50px" alt="">
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a v-bind:href="'proveedores/datos/?Id='+proveedor.Datos_proveedor.Id" class="btn btn-dark btn-outline m-b-10 m-l-5">
                                                            {{proveedor.Datos_proveedor.Nombre_proveedor}}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <h4 align="center" v-bind:class="[{ 'text-danger' : proveedor.Saldo < 0}]"> ${{ proveedor.Saldo | Moneda }} </h4>
                                                    </td>
                                                    <td>{{proveedor.Datos_proveedor.Nombre_rubro}}</td>
                                                    <td>{{proveedor.Datos_proveedor.CUIT_CUIL}}</td>
                                                    <td>{{proveedor.Datos_proveedor.Telefono}}</td>
                                                    <td><span class="block-email">{{proveedor.Datos_proveedor.Email}}</span></td>
                                                    <td><a v-bind:href="'http://'+proveedor.Datos_proveedor.Web" target="_blank">{{proveedor.Datos_proveedor.Web}}</a></td>
                                                    <td>{{proveedor.Datos_proveedor.Nombre_persona_contacto}}</td>
                                                    <td>
                                                        <div class="table-data-feature">
                                                            <button class="item" v-on:click="editarFormularioProveedor(proveedor)" data-toggle="modal" data-target="#proveedormodal" data-placement="top" title="Edición rápida">
                                                                <i class="ti-pencil-alt"></i>
                                                            </button>
                                                            <?php
                                                            //if ($this->session->userdata('Rol_acceso') > 4) {
                                                            echo ' 
                                                                    <button v-on:click="desactivarProveedor(proveedor.Datos_proveedor.Id)" class="item" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                                                        <i class="ti-na"></i>
                                                                    </button>';
                                                            //}
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
                </section>
                <!-- modal proveedors -->
                <div class="modal fade" id="proveedormodal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="scrollmodalLabel">Formulario de proveedor</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form class="form-horizontal" action="post" v-on:submit.prevent="crearProveedor()">
                                <div class="modal-body">
                                    <div class="horizontal-form">
                                        <div class="form-group">
                                            <label class=" form-control-label">Nombre</label>
                                            <input type="text" class="form-control" placeholder="" v-model="proveedorDatos.Nombre_proveedor" required>
                                        </div>
                                        <div class="form-group">
                                            <label class=" form-control-label">Rubro del proveedor</label>
                                            <select class="form-control" v-model="proveedorDatos.Rubro_id" required>
                                                <option v-for="rubro in listaRubros" v-bind:value="rubro.Id">{{rubro.Nombre_rubro}}</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class=" form-control-label">Breve descripción del producto/servicio que brinda</label>
                                            <textarea class="form-control" rows="5" v-model="proveedorDatos.Producto_servicio"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class=" form-control-label">CUIT/CUIL</label>
                                            <input type="text" class="form-control" placeholder="" v-model="proveedorDatos.CUIT_CUIL" required>
                                        </div>
                                        <div class="form-group">
                                            <label class=" form-control-label">Teléfono whatsapp - numero entero de corrido</label>
                                            <input type="text" class="form-control" placeholder="" v-model="proveedorDatos.Telefono">
                                        </div>
                                        <div class="form-group">
                                            <label class=" form-control-label">Teléfono Fijo</label>
                                            <input type="text" class="form-control" placeholder="" v-model="proveedorDatos.Telefono_fijo">
                                        </div>
                                        <div class="form-group">
                                            <label class=" form-control-label">Dirección</label>
                                            <input type="text" class="form-control" placeholder="" v-model="proveedorDatos.Direccion" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Localidad</label>
                                            <input type="text" class="form-control" placeholder="" v-model="proveedorDatos.Localidad" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Provincia</label>
                                            <input type="text" class="form-control" placeholder="" v-model="proveedorDatos.Provincia" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Pais</label>
                                            <input type="text" class="form-control" placeholder="" v-model="proveedorDatos.Pais" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Email</label>
                                            <input type="email" class="form-control" placeholder="" v-model="proveedorDatos.Email">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Web - Sin "http://"</label>
                                            <input type="text" class="form-control" placeholder="" v-model="proveedorDatos.Web">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">URL de su fanpage</label>
                                            <input type="text" class="form-control" placeholder="" v-model="proveedorDatos.URL_facebook">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Nombre de persona de contacto en la empresa</label>
                                            <input type="text" class="form-control" placeholder="" v-model="proveedorDatos.Nombre_persona_contacto">
                                        </div>
                                        <div class="form-group">
                                            <label class=" form-control-label">Información de la persona de contacto</label>
                                            <textarea class="form-control" rows="5" v-model="proveedorDatos.Datos_persona_contacto"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class=" form-control-label">Más información sobre el proveedor</label>
                                            <textarea class="form-control" rows="5" v-model="proveedorDatos.Mas_datos_proveedor"></textarea>
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
                <div class="modal fade" id="modalproveedorsFoto" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="true">
                    <div class="modal-dialog  modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalItemsFoto">Fotografía de {{proveedorFoto.Nombre_proveedor}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p align="center">
                                    <img v-if="proveedorFoto.Imagen != null" class="avatar_grande" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+proveedorFoto.Imagen" alt="">
                                    <img v-else class="avatar_grande" src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" alt="">
                                </p>
                                <hr>
                                <div class="horizontal-form">
                                    <!-- <form class="form-horizontal" action="post" enctype="multipart/form-data" v-on:submit.prevent="crearproveedors()">  -->
                                    <form class="form-horizontal" action="post" enctype="multipart/form-data" v-on:submit.prevent="uploadProveedor(proveedorFoto.Id)">
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

                <!-- modal rubro -->
                <div class="modal fade" id="rubrosModal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="scrollmodalLabel">Listado de rubros de proveedores</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-borderless table-striped table-earning">
                                    <tbody>
                                        <tr v-for="rubro in listaRubros">
                                            <td><b>{{rubro.Nombre_rubro}}</b></td>
                                            <td>{{rubro.Descripcion}}</td>
                                            <td>
                                                <button class="item" v-on:click="editarFormularioRubros(rubro)" title="Editar">
                                                    <i class="zmdi zmdi-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <hr>
                            </div>
                            <form class="form-horizontal" action="post" v-on:submit.prevent="crearRubro()">
                                <div class="modal-body">
                                    <div class="horizontal-form">
                                        <div class="form-group">
                                            <label class=" form-control-label">Indentifcador de la rubro</label> <input type="text" class="form-control" placeholder="" v-model="rubroDato.Nombre_rubro">
                                        </div>
                                        <div class="form-group">
                                            <label class=" form-control-label">Descripción de la rubro</label>
                                            <textarea class="form-control" rows="5" placeholder="" v-model="rubroDato.Descripcion"></textarea>
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
                <!-- end modal manzana -->
                <!-- END MAIN CONTENT-->
                <!-- END PAGE CONTAINER-->
                <?php /// CABECERA BODY
                include "footer.php";
                ?>