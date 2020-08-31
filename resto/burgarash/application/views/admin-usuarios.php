<?php 
// CABECERA
include "header.php";
/// NAVEGADOR SIDEBAR
if($this->session->userdata('Rol_id') == 4) { include "navegadores/nav-bar-rol-4.php"; }
elseif($this->session->userdata('Rol_id') == 3) { include "navegadores/nav-bar-rol-3.php"; }
elseif($this->session->userdata('Rol_id') == 2) { include "navegadores/nav-bar-rol-2.php"; }
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
                                <h1>Gestión de personal, <span>Listado</span></h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Sistema restaurant</a></li>
                                    <li class="breadcrumb-item active">Listado de personal</li>
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
                            <a href="#modalUsuarios" data-toggle="modal" title="Nuevo item" class="btn btn-success btn-flat btn-addon m-b-10 m-l-5" v-on:click="limpiarFormularioUsuarios()"><i class="ti-plus"></i> Nuevo personal</a>
                        </div>
                        <div class="col-lg-2">
                            <div class="card">
                                 <div class="list-group">
                                    <a href="#" class="list-group-item" v-on:click="getListadoUsuarios(1)">Activos</a>       
                                    <a href="#" class="list-group-item" v-on:click="getListadoUsuarios(0)">Inactivos</a>       
                                </div>
                            </div>
                        </div>
                            <!-- /# card -->
                        <div class="col-lg-10">
                            <div class="card">
                                <div class="bootstrap-data-table-panel">
                                    <div class="table-responsive">
                                        <div class="modal-body">
                                            <div class="horizontal-form">
                                                <p align="right">Estas viendo personas que trabajan o trabajaron en la empresa.</p>
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th>Nombre</th>
                                                            <th>DNI</th>
                                                            <th>Rol</th>
                                                            <th>Observaciones</th>
                                                            <th>Alta/Baja</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="usuario in listaUsuarios">
                                                            <td valign="middle">
                                                                <div class="round-img">
                                                                    <a href="#modalUsuariosFoto" data-toggle="modal" v-on:click="editarFormulariousuarioFoto(usuario)">
                                                                        <img v-if="usuario.Imagen != null"  v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+usuario.Imagen" alt="">
                                                                        <img v-else  src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" alt="">
                                                                    </a>
                                                                </div>
                                                            </td>
                                                            <td valign="middle">
                                                                <a v-bind:href="'datos/?Id='+usuario.Id" class="btn btn-dark btn-outline m-b-10 m-l-5">
                                                                    {{usuario.Nombre}}
                                                                </a>
                                                            </td>
                                                            <td>{{usuario.DNI}}</td>
                                                            <td>{{usuario.Nombre_rol}}</td>
                                                            <td>{{usuario.Observaciones}}</td>
                                                            <td>
                                                                <a href="#" v-on:click="activarUsuario(usuario)" tittle="Desabilitar"><span v-if="usuario.Activo == 1" class="badge badge-danger"><i class="ti-na"> </i></span></a>
                                                                <a href="#" v-on:click="activarUsuario(usuario)" tittle="Habilitar"><span v-if="usuario.Activo == 0" class="badge badge-success"><i class="ti-check"></i></span></a>
                                                            </td>
                                                            <td valign="middle">
                                                                <a href="#modalUsuarios" data-toggle="modal" v-on:click="editarFormulariousuario(usuario)">
                                                                <i class="ti-pencil-alt"></i></a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
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
        <!-- Modal Usuarios-->
        <div class="modal fade" id="modalUsuarios" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="true">
            <div class="modal-dialog  modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalItemsCartaTitle">{{texto_boton}} usuarios</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>  
                    <div class="modal-body">
                        <div class="horizontal-form">
                            <form class="form-horizontal" action="post" v-on:submit.prevent="crearUsuarios()">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                       <label class="control-label">Nombre</label> <input type="text" class="form-control" placeholder="" v-model="usuario.Nombre">
                                    </div>
                                
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">DNI</label>
                                            <input type="number"  class="form-control" placeholder="" v-model="usuario.DNI">
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Password</label>
                                            <input type="password" class="form-control" placeholder="" v-model="usuario.Pass">
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Telefono</label>
                                            <input type="text" class="form-control" placeholder="" v-model="usuario.Telefono">
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Fecha de nacimiento</label>
                                            <input type="date" class="form-control" placeholder="" v-model="usuario.Fecha_nacimiento">
                                        </div>
                                     </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Rol</label>
                                            <select class="form-control" v-model="usuario.Rol_id">
                                                <option v-for="rol in listaRoles" v-bind:value="rol.Id">{{rol.Nombre_rol}}</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Fecha ingreso a la empresa</label>
                                            <input type="date" class="form-control" placeholder="" v-model="usuario.Fecha_alta">
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-12">
                                        <label class="control-label">Observaciones</label>
                                        <textarea class="form-control" rows="5" placeholder="" v-model="usuario.Observaciones"></textarea>
                                    </div>
                                
                                    <div class="col-sm-offset-2 col-sm-12">
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
                        <div class="horizontal-form">
                            <!-- <form class="form-horizontal" action="post" enctype="multipart/form-data" v-on:submit.prevent="crearUsuarios()">  -->
                            <form class="form-horizontal" action="post" enctype="multipart/form-data" v-on:submit.prevent="upload(usuarioFoto.Id)">
                                <div class="form-group">
                                    <p>Nueva imagen</p>
                                    <div class="col-sm-10">
                                        <input @change="archivoSeleccionado" type="file" class="form-control" name="Imagen">
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" class="btn btn-success">{{texto_boton}} imagen</button>
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
    




