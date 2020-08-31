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
                        <h3 class="title-5 m-b-35">Personal empresa</h3>

                        <div class="table-data__tool">
                            <div class="table-data__tool-left">
                                <div class="rs-select2--light rs-select2--md">
                                    <select class="form-control-sm form-control" v-model="filtro_empresa" v-on:change="getListadoUsuarios(1)">
                                        <option selected="selected" v-bind:value="0">Empresas</option>
                                        <option v-for="empresas in listaEmpresas" v-bind:value="empresas.Id">{{empresas.Nombre_empresa}}</option>
                                    </select>
                                    <div class="dropDownSelect2"></div>
                                </div>
                                <div class="rs-select2--light rs-select2--sm">
                                    <select class="form-control-sm form-control" v-model="filtro_puesto" v-on:change="getListadoUsuarios(1)">
                                        <option selected="selected" v-bind:value="0">Puestos</option>
                                        <option v-for="puestos in listaPuestos" v-bind:value="puestos.Id">{{puestos.Nombre_puesto}}</option>
                                    </select>
                                    <div class="dropDownSelect2"></div>
                                </div>
                                <div class="rs-select2--light rs-select2--sm">

                                    <input type="text" class="form-control-sm form-control" placeholder="Buscar persona" v-model="buscar">
                                </div>
                                <!--<button class="au-btn-filter"><i class="zmdi zmdi-filter-list"></i>Filtros</button>-->
                            </div>
                            <div class="table-data__tool-right">
                                <button class="au-btn au-btn-icon au-btn--green au-btn--small" data-toggle="modal" data-target="#usuariomodal" v-on:click="limpiarFormularioUsuarios()">
                                    <i class="zmdi zmdi-plus"></i>Nuevo usuario</button>

                                <button class="au-btn au-btn-icon au-btn--blue au-btn--small" data-toggle="modal" data-target="#empresasmodal" v-on:click="limpiarFormularioEmpresa()">
                                    <!--<i class="zmdi zmdi-plus"></i>-->Empresas</button>

                                <button class="au-btn au-btn-icon au-btn--blue au-btn--small" data-toggle="modal" data-target="#puestosmodal" v-on:click="limpiarFormularioPuesto()">
                                    <!--<i class="zmdi zmdi-plus"></i>-->Puestos</button>

                                <a href="<?php echo base_url(); ?>usuarios/resumenreportes" class="btn btn-warning">
                                    PUNTAJE DEL PERSONAL
                                </a>
                                <!--<div class="rs-select2--dark rs-select2--sm rs-select2--dark2">
                                            <select class="js-select2" name="type">
                                                <option selected="selected">Más</option>
                                                <option value="">Option 1</option>
                                                <option value="">Option 2</option>
                                            </select>
                                            <div class="dropDownSelect2"></div>
                                        </div>-->
                            </div>
                        </div>
                        <div class="table-responsive table-responsive-data2">
                            <table class="table table-data2">
                                <thead class="card-header">
                                    <tr>
                                        <th>

                                        </th>
                                        <th>Nombre</th>
                                        <th>CUIL</th>
                                        <th>empresa</th>
                                        <th>puesto</th>
                                        <th>superior</th>
                                        <th>teléfono</th>
                                        <th>email</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="tr-shadow" v-for="usuario in buscarUsuarios">
                                        <td>
                                            <div class="round-img">
                                                <a href="#modalUsuariosFoto" data-toggle="modal" v-on:click="editarFormulariousuarioFoto(usuario)">
                                                    <img v-if="usuario.Imagen != null" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+usuario.Imagen" width="60px">
                                                    <img v-else src="https://freeiconshop.com/wp-content/uploads/edd/person-flat.png" width="50px" alt="">
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <a v-bind:href="'usuarios/datos/?Id='+usuario.Id" class="btn btn-dark btn-outline m-b-10 m-l-5">
                                                {{usuario.Nombre}}
                                            </a>
                                        </td>
                                        <td>{{usuario.CUIT_CUIL}}</td>
                                        <td class="desc">{{usuario.Nombre_empresa}}</td>
                                        <td>{{usuario.Nombre_puesto}}</td>
                                        <td>
                                            <span class="status--process">{{usuario.Nombre_lider}}</span>
                                        </td>
                                        <td>{{usuario.Telefono}}</td>
                                        <td>
                                            <span class="block-email">{{usuario.Email}}</span>
                                        </td>
                                        <td>
                                            <div class="table-data-feature">

                                                <a class="item" v-bind:href="'usuarios/datos/?Id='+usuario.Id" title="Ver todos los datos">
                                                    <i class="zmdi zmdi-mail-send"></i>
                                                </a>
                                                <button class="item" v-on:click="editarFormulariousuario(usuario)" data-toggle="modal" data-target="#usuariomodal" data-placement="top" title="Edición rápida">
                                                    <i class="zmdi zmdi-edit"></i>
                                                </button>
                                                <?php
                                                if ($this->session->userdata('Rol_acceso') > 4) {
                                                    echo '
                                                                <button v-on:click="desactivarUsuario(usuario.Id)" class="item" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                                                    <i class="zmdi zmdi-delete"></i>
                                                                </button>';
                                                }
                                                ?>
                                                <!--<button class="item" data-toggle="tooltip" data-placement="top" title="More">
                                                            <i class="zmdi zmdi-more"></i>
                                                        </button>-->
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
    <!-- modal usuarios -->
    <div class="modal fade" id="usuariomodal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollmodalLabel">Formulario de usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal" action="post" v-on:submit.prevent="crearUsuarios()">
                    <div class="modal-body">
                        <div class="horizontal-form">
                            <div class="form-group">
                                <label class=" form-control-label">Nombre</label> <input type="text" class="form-control" placeholder="" v-model="usuario.Nombre" required>
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">DNI</label>
                                <input type="number" class="form-control" placeholder="" v-model="usuario.DNI" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label">CUIL</label>
                                <input type="number" class="form-control" placeholder="" v-model="usuario.CUIT_CUIL">
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Password</label>
                                <input type="password" class="form-control" placeholder="" v-model="usuario.Pass" required>
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Telefono <em>Sin guiones ni espacios</em></label>
                                <input type="text" class="form-control" placeholder="" v-model="usuario.Telefono">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Email</label>
                                <input type="email" class="form-control" placeholder="" v-model="usuario.Email">
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Rol</label>
                                <select class="form-control" v-model="usuario.Rol_acceso" required>
                                    <option v-for="rol in listaRoles" v-bind:value="rol.Acceso">{{rol.Nombre_rol}} -{{rol.Descripcion}} </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Fecha ingreso a la empresa</label>
                                <input type="date" class="form-control" placeholder="" v-model="usuario.Fecha_alta">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Empresa</label>
                                <select class="form-control" v-model="usuario.Empresa_id" required>
                                    <option value="0">...</option>
                                    <option v-for="empresas in listaEmpresas" v-bind:value="empresas.Id">{{empresas.Nombre_empresa}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Puesto</label>
                                <select class="form-control" v-model="usuario.Puesto_Id" required>
                                    <option value="0">...</option>
                                    <option v-for="puestos in listaPuestos" v-bind:value="puestos.Id">{{puestos.Nombre_puesto}}</option>
                                </select>
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
    <!-- end modal usuarios -->
    <!-- Modal Usuarios Fotos-->
    <div class="modal fade" id="modalUsuariosFoto" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalItemsFoto">Fotografía de {{usuarioFoto.Nombre}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p align="center">
                        <img v-if="usuarioFoto.Imagen != null" class="avatar_grande" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+usuarioFoto.Imagen" alt="">
                        <img v-else class="avatar_grande" src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" alt="">
                    </p>
                    <hr>
                    <div class="horizontal-form">
                        <!-- <form class="form-horizontal" action="post" enctype="multipart/form-data" v-on:submit.prevent="crearUsuarios()">  -->
                        <form class="form-horizontal" action="post" enctype="multipart/form-data" v-on:submit.prevent="upload(usuarioFoto.Id)">
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
    <!-- modal empresas -->
    <div class="modal fade" id="empresasmodal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollmodalLabel">Listado de empresas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <table class="table table-borderless table-striped table-earning">
                        <thead>
                            <tr>
                                <th>Nombre empresa</th>
                                <th>Descripción</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="empresaDatos in listaEmpresas">
                                <td>{{empresaDatos.Nombre_empresa}}</td>
                                <td>{{empresaDatos.Descripcion}}</td>
                                <td>
                                    <button class="item" v-on:click="editarFormularioEmpresa(empresaDatos)" title="Editar">
                                        <i class="zmdi zmdi-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <form class="form-horizontal" action="post" v-on:submit.prevent="crearEmpresa()">
                    <div class="modal-body">

                        <div class="horizontal-form">
                            <div class="form-group">
                                <label class=" form-control-label">Nombre de la empresa</label> <input type="text" class="form-control" v-model="empresaDatos.Nombre_empresa">
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Descripción</label>
                                <textarea class="form-control" rows="5" v-model="empresaDatos.Descripcion"></textarea>
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
    <!-- end modal empresas -->
    <!-- modal puestos -->
    <div class="modal fade" id="puestosmodal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollmodalLabel">Listado de puestos de trabajo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless table-striped table-earning">
                        <thead>
                            <tr>
                                <th>Nombre puesto</th>
                                <th>Descripción</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="puestos in listaPuestos">
                                <td>{{puestos.Nombre_puesto}}</td>
                                <td>{{puestos.Descripcion}}</td>
                                <td>
                                    <button class="item" v-on:click="editarFormularioPuesto(puestos)" title="Editar">
                                        <i class="zmdi zmdi-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <form class="form-horizontal" action="post" v-on:submit.prevent="crearPuesto()">
                    <div class="modal-body">
                        <div class="horizontal-form">
                            <div class="form-group">
                                <label class=" form-control-label">Nombre del puesto</label> <input type="text" class="form-control" placeholder="" v-model="puesto.Nombre_puesto">
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Descripción del puesto</label>
                                <textarea class="form-control" rows="5" placeholder="" v-model="puesto.Descripcion"></textarea>
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
    <!-- end modal puestos -->
    <!-- END MAIN CONTENT-->
    <!-- END PAGE CONTAINER-->




    <?php
    // CABECERA
    include "footer.php";
    ?>

    </body>

    </html>
    <!-- end document-->