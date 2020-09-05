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
                                <h1>Listado de clientes <span></span></h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Sistema restaurant</a></li>
                                    <li class="breadcrumb-item active">Clientes</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->

                <section id="main-content">
                    <div class="row">
                        <!-- <div class="col-lg-2">
                            <div class="card">
                                 <div class="list-group">
                                    <a href="#" class="list-group-item" v-on:click="getListadoUsuarios(1)">Activos</a>       
                                    <a href="#" class="list-group-item" v-on:click="getListadoUsuarios(0)">Inactivos</a>       
                                </div>
                            </div>
                        </div> -->
                        <!-- /# card -->
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="bootstrap-data-table-panel">
                                    <div class="table-responsive">
                                        <div class="modal-body">
                                            <div class="horizontal-form">

                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <!-- <th></th> -->
                                                            <th>Compras</th>
                                                            <th>Nombre</th>
                                                            <th>Teléfono</th>
                                                            <th>Dirección</th>
                                                            <th>Email</th>
                                                            <th style="background-color:#F7F2E0">Compras</th>
                                                            <th style="background-color:#F7F2E0">Pagos</th>
                                                            <th style="background-color:lightgrey">Saldo</th>
                                                            <th>Observaciones</th>
                                                            <th><a href="#modalclientes" data-toggle="modal" title="Nuevo item" class="btn btn-success btn-flat btn-addon m-b-10 m-l-5" v-on:click="limpiarFormCliente()"><i class="ti-plus"></i> Nuevo Cliente</a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="cliente in listaClientes">
                                                            <!-- <td valign="middle">
                                                                <div class="round-img">
                                                                    <a href="#modalclientesFoto" data-toggle="modal" v-on:click="editarFormularioclienteFoto(cliente)">
                                                                        <img v-if="cliente.Imagen != null" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+cliente.Imagen" alt="">
                                                                        <img v-else src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" alt="">
                                                                    </a>
                                                                </div>
                                                            </td> -->
                                                            <th align="center">
                                                                <h1>{{cliente.Datos_cliente.Cant_compras}}</h1>
                                                            </th>
                                                            <td valign="middle">
                                                                <a v-bind:href="'clientes/datos/?Id='+cliente.Datos_cliente.Id" class="btn btn-dark btn-outline m-b-10 m-l-5">
                                                                    {{cliente.Datos_cliente.Nombre}}
                                                                </a>
                                                            </td>
                                                            <td>{{cliente.Datos_cliente.Telefono}}</td>
                                                            <td>{{cliente.Datos_cliente.Direccion}}</td>
                                                            <td>{{cliente.Datos_cliente.Email}}</td>
                                                            <td align="right" style="background-color:#F7F2E0">
                                                                <h5> {{cliente.Total_compras | Moneda}} </h5>
                                                            </td>
                                                            <td align="right" style="background-color:#F7F2E0">
                                                                <h5> {{cliente.Total_pagos | Moneda}} </h5>
                                                            </td>
                                                            <td align="right" style="background-color:lightgrey">
                                                                <h4 v-bind:class="[{ 'text-danger' : cliente.Saldo < 0}, { 'text-success' : cliente.Saldo > 0}]"> <b>{{cliente.Saldo | Moneda}} </b></h4>
                                                            </td>
                                                            <td>{{cliente.Datos_cliente.Observaciones}}</td>
                                                            <td valign="middle">
                                                                <a href="#modalclientes" data-toggle="modal" v-on:click="editarCliente(cliente.Datos)">
                                                                    <i class="ti-pencil-alt"></i></a>
                                                            </td>
                                                            <!-- <td>
                                                                <a href="#" v-on:click="activarcliente(cliente)" tittle="Desabilitar"><span v-if="cliente.Activo == 1" class="badge badge-danger"><i class="ti-na"> </i></span></a>
                                                                <a href="#" v-on:click="activarcliente(cliente)" tittle="Habilitar"><span v-if="cliente.Activo == 0" class="badge badge-success"><i class="ti-check"></i></span></a>
                                                            </td>
                                                             -->
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
        <div class="modal fade" id="modalclientes" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="true">
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
                            <form class="form-horizontal" action="post" v-on:submit.prevent="crear_cliente()">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label class="control-label">Nombre</label> <input type="text" class="form-control" placeholder="" v-model="clienteDato.Nombre">
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Telefono</label>
                                            <input type="text" class="form-control" placeholder="" v-model="clienteDato.Telefono">
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Teléfono secundario</label>
                                            <input type="text" class="form-control" placeholder="" v-model="clienteDato.Telefono_secundario">
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Dirección</label>
                                            <input type="text" class="form-control" placeholder="" v-model="clienteDato.Direccion">
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Email</label>
                                            <input type="text" class="form-control" placeholder="" v-model="clienteDato.Email">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <label class="control-label">Observaciones</label>
                                        <textarea class="form-control" rows="5" placeholder="" v-model="clienteDato.Observaciones"></textarea>
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