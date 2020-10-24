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
    <div class="content-wrap" id="pedido_comanda">
        <div class="main">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-lg-8 p-r-0 title-margin-right">
                        <div class="page-header">
                            <div class="page-title">
                                <h1>Carta digital</h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">PX Resto</a></li>
                                    <li class="breadcrumb-item active">Mesa {{datoComanda.Identificador}}, <span> {{datoComanda.Nombre_cliente}}, atendidos por {{datoComanda.Nombre_moso}},{{ formatoFecha(datoComanda.Fecha) }}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->

                <section id="main-content">
                    <div class="row">
                        tab uno con toda la lista de cosas a pedir y otro para el listado de cosas pedidas y el monto, mas un boton de llamar al moso
                        <div class="row">
                            <div class="col-lg-6">
                                Listado Categorías
                            </div>
                            <div class="col-lg-6">
                                Buscar por nombre
                            </div>
                            <div class="col-lg-12">
                                Galería con las cosas.

                                Mostrar la foto
                                El Nombre
                                Y el precio
                                Boton de Pedir
                                --
                                Debe salir una notificacion de item Pedido.
                            </div>
                            <div class="col-lg-12">
                                Listado simpre de items pedidos con su valor
                                y al final la cuenta.
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-10">
                        <div class="card">
                            <div class="input-group input-group-default">
                                <span class="input-group-btn"><button class="btn btn-primary" type="submit"><i class="ti-search"></i></button></span>
                                <input type="text" class="form-control" placeholder="Buscar por nombre" v-model="buscar">
                            </div>
                        </div>
                        <div class="card">

                            <div class="bootstrap-data-table-panel">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Imagen</th>
                                                <th>Nombre</th>
                                                <th width="120px">Venta</th>
                                                <th width="120px">Costo</th>
                                                <th width="120px">Ganancia</th>
                                                <!-- <th>Descripción</th> -->
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
                                                    <a href="#modalFoto" data-toggle="modal" v-on:click="editarFormularioitemCarta(item)">
                                                        <img v-if="item.Imagen != null" class="avatar" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+item.Imagen" alt="">
                                                        <img v-else class="avatar" src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" alt="">
                                                    </a>
                                                </td>
                                                <td>

                                                    <a v-bind:href="'datos/?Id='+item.Id" class="btn btn-dark btn-outline m-b-10 m-l-5">
                                                        {{item.Nombre_item}}
                                                    </a>
                                                </td>
                                                <td>
                                                    <h4 align="center">${{item.Precio_venta | Moneda}}</h4>
                                                </td>
                                                <td>
                                                    <h4 class="text-warning" align="center">${{item.Precio_costo | Moneda}}</h4>
                                                </td>
                                                <td>
                                                    <h4 class="text-success" align="center">${{item.Precio_venta - item.Precio_costo | Moneda}}</h4>
                                                </td>
                                                <!-- <td>{{item.Descripcion}}</td> -->
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
                                                    <a style="cursor:pointer;" href="#modalInfo" data-toggle="modal" v-on:click="infoItem(item)" title="Información de este item">
                                                        <span class="badge badge-info"><i class="ti-eye"></i></span>
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
                    <div class="col-lg-2">

                        <div class="card">
                            <div class="list-group">
                                <a href="#" class="list-group-item" v-on:click="getListadoItems()">Todos</a>
                                <a href="#" v-for="listaCategoria in categoriasCarta" class="list-group-item" v-on:click="cargarItemsbyCategoria(listaCategoria.Id)">{{listaCategoria.Nombre_categoria}}</a>

                                <!-- <a href="#" class="list-group-item" v-bind:class="{ active: categoria.isActive }">Todos</a> -->

                            </div>
                        </div>
                    </div>


                </section>
            </div>
        </div>
        <!-- Modal INFO-->
        <div class="modal fade" id="modalInfo" tabindex="-3" role="dialog" aria-labelledby="modalItemsCartaTitle" aria-hidden="true">
            <div class="modal-dialog  modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalItemsCartaTitle">{{itemCarta.Nombre_item}}</h5>
                        <button type="button" class="Cerrar" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <h4>{{itemCarta.Descripcion}}</h4>

                        <p align="center">
                            <a href="#modalFoto" data-dismiss="modal" data-toggle="modal" v-on:click="editarFormularioitemCarta(item)">
                                <img v-if="itemCarta.Imagen != null" class="avatar_grande" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+itemCarta.Imagen" alt="">
                                <img v-else class="avatar_grande" src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" alt="">
                            </a>
                        </p>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
        <?php /// CABECERA BODY
        include "footer.php";
        ?>