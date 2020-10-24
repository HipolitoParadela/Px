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
    <div class="content-wrap" id="comandas">
        <div class="main">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-8 p-r-0 title-margin-right">
                        <div class="page-header">
                            <div class="page-title">
                                <h1>Comanda mesa {{datoComanda.Identificador}}, <span> {{ formatoFecha(datoComanda.Fecha) }}, {{datoComanda.Nombre_cliente}}, atendidos por {{datoComanda.Nombre_moso}}</span></h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="../comandas">Comandas</a></li>
                                    <li class="breadcrumb-item active">Mesa {{datoComanda.Identificador }}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->

                <section id="main-content">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="card p-0">
                                <div class="stat-widget-three">
                                    <div class="stat-icon bg-danger">
                                        <i class="ti-hand-open"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="stat-digit">{{datoComanda.Identificador}}</div>
                                        <div class="stat-text">Mesa</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card p-0">
                                <div class="stat-widget-three">
                                    <div class="stat-icon bg-success">
                                        <i class="ti-user"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="stat-digit">{{datoComanda.Nombre_cliente}}</div>
                                        <div class="stat-text">{{datoComanda.Cant_personas}} comensales</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card p-0">
                                <div class="stat-widget-three">
                                    <div class="stat-icon bg-primary">
                                        <i class="ti-tag"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="stat-digit">{{contarItems(itemsComanda)}}</div>
                                        <div class="stat-text">Items pedidos</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card p-0">
                                <div class="stat-widget-three">
                                    <div class="stat-icon bg-warning">
                                        <i class="ti-money"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="stat-digit">${{ sumarCuenta(itemsComanda)}}</div>
                                        <div v-if="datoComanda.Estado == 0" class="stat-text">Cuenta al momento</div>
                                        <div v-if="datoComanda.Estado == 1" class="stat-text">Cuenta neta</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /# row -->
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="card">
                                <div class="stat-widget-one">
                                    <div class="stat-icon dib"><i class="ti-timer color-success border-success"></i></div>
                                    <div class="stat-content dib">
                                        <div class="stat-text">Hora llegada</div>

                                        <div class="stat-digit">{{formatoHora(datoComanda.Hora_llegada)}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="card">
                                <div class="stat-widget-one">
                                    <div class="stat-icon dib"><i class="ti-timer color-pink border-pink"></i></div>
                                    <div class="stat-content dib">
                                        <div class="stat-text">Entrada en mesa</div>
                                        <div v-if="datoComanda.Hora_entrada_en_mesa == null" class="stat-digit"><button v-on:click="entregasEnMesa(datoComanda.Id, 'entrada', null, null, null)" type="submit" class="btn btn-warning">Entregado</button></div>
                                        <div v-if="datoComanda.Hora_entrada_en_mesa != null" class="stat-digit">{{formatoHora(datoComanda.Hora_entrada_en_mesa)}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card">
                                <div class="stat-widget-one">
                                    <div class="stat-icon dib"><i class="ti-timer color-primary border-primary"></i></div>
                                    <div class="stat-content dib">
                                        <div class="stat-text">Menú en mesa</div>
                                        <div v-if="datoComanda.Hora_menu_en_mesa == null" class="stat-digit"><button v-on:click="entregasEnMesa(datoComanda.Id, 'menu', null, null, null)" type="submit" class="btn btn-warning">Entregado</button></div>
                                        <div v-if="datoComanda.Hora_menu_en_mesa != null" class="stat-digit">{{formatoHora(datoComanda.Hora_menu_en_mesa)}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card">
                                <div class="stat-widget-one">
                                    <div class="stat-icon dib"><i class="ti-timer color-danger border-danger"></i></div>
                                    <div class="stat-content dib">
                                        <div class="stat-text">Cierre de mesa</div>
                                        <?php if ($Rol_usuario > 3) {
                                            echo ''; /// inicio condicional para Roles PHP 
                                        ?>
                                            <div v-if="datoComanda.Hora_cierre_comanda == null" class="stat-digit">
                                                <button v-on:click="entregasEnMesa(datoComanda.Id, 'cerrar', datoComanda.Cant_compras, datoComanda.Cliente_id, sumarCuenta(itemsComanda))" type="submit" class="btn btn-danger">Cerrar comanda</button>
                                            </div>
                                        <?php '';
                                        } /// fin condicional para Roles PHP
                                        ?>
                                        <div v-if="datoComanda.Hora_cierre_comanda != null" class="stat-digit">{{formatoHora(datoComanda.Hora_cierre_comanda)}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /# row -->

                    <div class="row" class="stat-text">
                        <div class="col-lg-7" v-if="datoComanda.Estado == 0">
                            <h4>Items pendientes</h2>
                                <div class="card">
                                    <div class="bootstrap-data-table-panel">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Entregar</th>
                                                        <th>Nombre</th>
                                                        <th align="center" width="120px">Precio</th>
                                                        <th>Categoría</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="item in itemsComanda.Pendientes">
                                                        <td>
                                                            <a style="cursor:pointer;" v-on:click="entregarItem(item.Item_comanda_id, item.Apto_stock, item.Id)" tittle=""><span class="badge badge-info"><i class="ti-arrow-top-right"></i></span></a>
                                                        </td>
                                                        <td>{{item.Nombre_item}}</td>
                                                        <td align="center">${{item.Precio_venta}}</td>
                                                        <td>{{item.Nombre_categoria}}</td>
                                                        <td><a style="cursor:pointer;" v-on:click="eliminar(item.Item_comanda_id, 'tbl_items_comanda')" tittle=""><span class="badge badge-danger"><i class="ti-na"></i></span></a></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- /# card -->
                                <div class="card p-0" v-if="datoComanda.Estado == 0">
                                    <div class="stat-widget-three">
                                        <div class="stat-icon bg-info">
                                            <i class="ti-printer"></i>
                                        </div>
                                        <div class="stat-content">
                                            <div class="stat-digit">
                                                <a class="btn btn-info" v-bind:href="'../imprimircomanda/?Id='+datoComanda.Id" target="_blank" onClick="window.open(this.href, this.target, 'width=800,height=600'); return false;"> Imprimir Comanda </a>
                                            </div>
                                            <div class="stat-text">&nbsp;</div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <!-- /# column -->

                        <div class="col-lg-5">
                            <h4>Items entregados</h2>
                                <div class="card">
                                    <div class="bootstrap-data-table-panel">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Nombre</th>
                                                        <th align="center" width="120px">Precio</th>
                                                        <th>Categoría</th>
                                                        <th>Hora</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="item in itemsComanda.Entregados">
                                                        <td>{{item.Nombre_item}}</td>
                                                        <td align="center">${{item.Precio_venta}}</td>
                                                        <td>{{item.Nombre_categoria}}</td>
                                                        <td>{{item.Hora_entregado}}hs</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- /# card -->
                        </div>



                        <div class="col-lg-4" v-if="datoComanda.Estado == 1">
                            <h4>Cuenta final a cobrar</h2>
                                <div class="card">
                                    <div class="bootstrap-data-table-panel">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <td>Cuenta</td>
                                                        <td><span class="text-success">${{datoComanda.Valor_cuenta}}</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Descuento</td>
                                                        <td><span class="text-danger"> - ${{datoComanda.Valor_descuento}}</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Modo de pago</b></td>
                                                        <!-- <td align="left">
                                                            Efectivo <input type="radio" value="1" v-model="datoComanda.Modo_pago" v-on:change="modoPago()"><br>
                                                            Tarjeta <input type="radio" value="2" v-model="datoComanda.Modo_pago" v-on:change="modoPago()">
                                                        </td> -->
                                                    </tr>
                                                    <tr>
                                                        <td>Total</td>
                                                        <td>
                                                            <h4>${{datoComanda.Valor_cuenta - datoComanda.Valor_descuento}}</h4>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <?php if ($Rol_usuario > 3) {
                                    echo ''; ?>
                                    <a href="#modalDescuento" data-toggle="modal" title="Aplicar descuento" class="btn btn-text btn-flat btn-addon m-b-10 m-l-5"><i class="ti-minus"></i> Aplicar descuento</a>
                                <?php '';
                                } ?>

                                <!-- /# card -->
                        </div>
                        <?php if ($Rol_usuario > 3) {
                            echo ''; ?>
                            <div class="col-lg-3" v-if="datoComanda.Estado == 1">
                                <div class="col-lg-3">
                                    <div class="card p-0">
                                        <div class="stat-widget-three">
                                            <div class="stat-icon bg-info">
                                                <i class="ti-printer"></i>
                                            </div>
                                            <div class="stat-content">
                                                <div class="stat-digit">
                                                    <a class="btn btn-info" v-bind:href="'../imprimircuenta/?Id='+datoComanda.Id" target="_blank" onClick="window.open(this.href, this.target, 'width=800,height=600'); return false;"> Imprimir Cuenta </a>
                                                </div>
                                                <div class="stat-text">&nbsp;</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="card p-0">
                                        <div class="stat-widget-three">
                                            <div class="stat-icon bg-danger">
                                                <i class="ti-back-right"></i>
                                            </div>
                                            <div class="stat-content">
                                                <div class="stat-digit">
                                                    <button class="btn btn-danger" v-on:click="entregasEnMesa(datoComanda.Id, 'abrir', datoComanda.Cant_compras, datoComanda.Cliente_id, sumarCuenta(itemsComanda))">
                                                        Reabrir comanda
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php '';
                        } ?>
                    </div>
                    <!-- DATOS DE PAGOS -->
                    <?php if ($Rol_usuario > 3) {
                        echo ''; ?>
                        <div class="row" v-if="datoComanda.Estado == 1">
                                <div class="col-lg-3">
                                    <div class="card p-0">
                                        <div class="stat-widget-three">
                                            <div class="stat-icon bg-warning">
                                                <i class="ti-money"></i>
                                            </div>
                                            <div class="stat-content">
                                                <div class="stat-digit">$ {{datoComanda.Valor_cuenta - datoComanda.Valor_descuento | Moneda}}</div>
                                                <div class="stat-text">Monto total</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card p-0">
                                        <div class="stat-widget-three">
                                            <div class="stat-icon bg-success">
                                                <i class="ti-money"></i>
                                            </div>
                                            <div class="stat-content">
                                                <div class="stat-digit">$ {{Total_pagado | Moneda}}</div>
                                                <div class="stat-text">Monto Abonado</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card p-0">
                                        <div class="stat-widget-three">
                                            <div class="stat-icon bg-info">
                                                <i class="ti-money"></i>
                                            </div>
                                            <div class="stat-content">
                                                <div class="stat-digit">$ {{ datoComanda.Valor_cuenta - datoComanda.Valor_descuento - Total_pagado | Moneda}}</div>
                                                <div class="stat-text">Saldo</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-9">
                                    <div class="card">
                                        <div class="card-header">
                                            <strong>Pagos</strong>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="table2excel" class="table table-striped">
                                                    <thead>
                                                        <tr>

                                                            <th>Método</th>
                                                            <th>Monto</th>
                                                            <th>Fecha</th>
                                                            <th>
                                                                <button data-toggle="modal" data-target="#modalEfectivo" v-on:click="limpiarFormularioMovimiento()">
                                                                    <i class="fa fa-plus-circle text-success"></i> Cobrar
                                                                </button>
                                                                <button data-toggle="modal" data-target="#modalCheque" v-on:click="limpiarFormularioMovimiento()">
                                                                    <i class="fa fa-plus-circle text-success"></i> Cobrar con cheque
                                                                </button>
                                                            </th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="movimiento in listaMovimientos">
                                                            <td v-if="movimiento.Tipo_movimiento == 1">Efectivo</td>
                                                            <td v-if="movimiento.Tipo_movimiento == 2">Tarjeta/Banco</td>
                                                            <td v-if="movimiento.Tipo_movimiento == 3">Cheque</td>
                                                            <td v-if="movimiento.Tipo_movimiento == 4">Mercado Pago</td>

                                                            <td>$ {{movimiento.Monto_bruto | Moneda}}</td>
                                                            <td>{{movimiento.Fecha_ejecutado | Fecha}}</td>
                                                            <td>
                                                                {{movimiento.Observaciones}}
                                                                <!--<button class="item" v-on:click="infoEtapa(movimiento.Observaciones)" data-toggle="modal" data-target="#modalObservaciones" data-placement="top" title="Ver observaciones">
                                                                    <i class="fa fa-exclamation-circle"></i>
                                                                </button>
                                                                 <button v-on:click="desactivarAlgo(movimiento.Id, 'tbl_dinero_efectivo')" class="item" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                                                    <i class="zmdi zmdi-delete"></i>
                                                                </button> -->
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                        </div>
                    <?php '';
                    } ?>

                    <div class="row" v-if="datoComanda.Estado == 0">
                        <div class="col-lg-12">
                            <h4>Listado de items</h2>
                        </div>
                        <div class="col-lg-2">

                            <div class="card">
                                <div class="list-group">
                                    <a style="cursor:pointer;" class="list-group-item" v-on:click="getListadoItems()">Todos</a>
                                    <a style="cursor:pointer;" v-for="listaCategoria in categoriasCarta" class="list-group-item" v-on:click="cargarItemsbyCategoria(listaCategoria.Id)">{{listaCategoria.Nombre_categoria}}</a>
                                </div>
                            </div>

                        </div>
                        <!-- /# card -->
                        <div class="col-lg-10">
                            <div class="card">
                                <div class="input-group input-group-default">
                                    <span class="input-group-btn"><button class="btn btn-primary" type="submit"><i class="ti-search"></i></button></span>
                                    <input type="text" class="form-control" placeholder="Buscar por nombre" v-model="buscar">
                                </div>
                                <div class="bootstrap-data-table-panel">
                                    <div class="table">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th width="40px">Añadir</th>

                                                    <th width="40%">Nombre</th>
                                                    <th width="120px">Precio</th>

                                                    <th>Categoría</th>
                                                    <th width="60px">Tiempo</th>
                                                    <th width="50px"></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="item in buscarItems">
                                                    <td>
                                                        <a style="cursor:pointer;" v-on:click="addItem(item.Id)" title="Añadir item a la comanda"><span class="badge badge-success"><i class="ti-plus"></i></span></a>
                                                        <!-- <a href="#" v-on:click="activarItem(item)" tittle="Habilitar"><span v-if="item.Activo == 0" class="badge badge-danger"><i class="ti-na"></i></span></a> -->
                                                    </td>

                                                    <td>{{item.Nombre_item}}</td>
                                                    <td>${{item.Precio_venta}}</td>

                                                    <td>{{item.Nombre_categoria}}</td>
                                                    <td>{{item.Tiempo_estimado_entrega}}'</td>
                                                    <td valign="middle">
                                                        <div class="round-img">
                                                            <img v-if="item.Imagen != null" v-bind:src="'<?php echo base_url(); ?>pxresto/uploads/imagenes/'+item.Imagen" alt="">
                                                            <!-- <img v-else  src="<?php echo base_url(); ?>pxresto/uploads/imagenes/agregarimagen.jpg" alt=""> -->
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a style="cursor:pointer;" href="#modalInfo" data-toggle="modal" v-on:click="infoItem(item)" title="Información de este item"><span class="badge badge-info"><i class="ti-eye"></i></span></a>
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
                <!-- Modal  Descuento-->
                <div class="modal fade" id="modalDescuento" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="true">
                    <div class="modal-dialog  modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalItemsFoto">Aplicar descuento</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="horizontal-form">
                                    <!-- <form class="form-horizontal" action="post" enctype="multipart/form-data" v-on:submit.prevent="crearUsuarios()">  -->
                                    <form class="form-horizontal" action="post" v-on:submit.prevent="cargarDescuento()">
                                        <div class="form-group">

                                            <div class="col-sm-12">
                                                <p>Escriba el monto a descontar</p>
                                                <input type="number" class="form-control" v-model="descuento">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" class="btn btn-success">Aplicar</button>
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
                <!-- Modal INFO-->
                <div class="modal fade" id="modalInfo" tabindex="-1" role="dialog" aria-labelledby="modalItemsCartaTitle" aria-hidden="true">
                    <div class="modal-dialog  modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalItemsCartaTitle">{{itemCarta.Nombre_item}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                {{itemCarta.Descripcion}}

                                <!-- <p align="center">
                                    <img v-if="itemCarta.Imagen != null" class="avatar_grande" v-bind:src="'<?php echo base_url(); ?>pxresto/uploads/imagenes/'+itemCarta.Imagen" alt="">
                                    <img v-else class="avatar_grande" src="<?php echo base_url(); ?>pxresto/uploads/imagenes/addimagen.jpg" alt="">
                                </p> -->
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>

                <!-- /.modal -->
                <!-- Modal pagos - No cheques-->
                <div class="modal fade" id="modalEfectivo" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="true">
                    <div class="modal-dialog  modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalItemsFoto">Ingresar pago</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="horizontal-form">
                                    <form class="form-horizontal" action="post" v-on:submit.prevent="crear_movimiento()">
                                        <div class="form-group">
                                            <label class="control-label">Monto</label>
                                            <input type="number" class="form-control" v-model="movimientoDatos.Monto_bruto" required>
                                        </div>
                                        <label class="control-label">Modalidad de pago</label>
                                        <select class="form-control" v-model="movimientoDatos.Tipo_movimiento">
                                            <option value="1">Efectivo</option>
                                            <option value="2">Tarjeta / Transferencia</option>
                                            <option value="4">Mercado pago</option>
                                        </select>
                                        <div class="form-group">
                                            <label class=" form-control-label">Observaciones</label>
                                            <textarea class="form-control" rows="5" v-model="movimientoDatos.Observaciones"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-12">
                                                <button type="submit" class="btn btn-success">{{texto_boton}}</button>
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
                <!-- /.modal -->
                <!-- Modal cheques-->
                <div class="modal fade" id="modalCheque" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="true">
                    <div class="modal-dialog  modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalItemsFoto">Registrar un nuevo cheque</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                    <span aria-hidden="1">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="horizontal-form">
                                    <form class="form-horizontal" enctype="multipart/form-data" action="post" v-on:submit.prevent="crearCheque()">
                                        <input type="hidden" v-model="chequeData.Tipo" v-value="1">
                                        <div class="form-group">
                                            <label class="control-label">Cheque a nombre de</label>
                                            <input type="text" class="form-control" v-model="chequeData.Nombre_entrega" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Monto</label>
                                            <input type="number" class="form-control" v-model="chequeData.Monto_bruto" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Número de cheque</label>
                                            <input type="number" class="form-control" v-model="chequeData.Numero_cheque" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Banco</label>
                                            <input type="text" class="form-control" v-model="chequeData.Banco" required>
                                        </div>
                                        <div class="form-group">
                                            <label class=" form-control-label">Vencimiento</label>
                                            <input type="date" class="form-control" v-model="chequeData.Vencimiento">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Observaciones</label>
                                            <textarea class="form-control" rows="5" v-model="chequeData.Observaciones"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <input @change="archivoSeleccionado" type="file" class="form-control" name="Imagen">
                                            </div>
                                            <div class="col-sm-12" v-if="chequeData.Imagen != null">
                                                Archivo previamente cargado
                                                <a target="_blank" v-bind:href="'<?php echo base_url(); ?>uploads/imagenes/'+chequeData.Imagen"> Ver archivo</a>
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
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" :disabled="preloader == 1">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.modal -->
                <!-- Modal OBSERVACIONES DE LOS PAGOS-->
                <div class="modal fade" id="modalObservaciones" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
                    <div class="modal-dialog  modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Observaciones</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <h4 v-if="infoModal.Observaciones != null">{{infoModal.Observaciones}}</h4>
                                <h4 v-else><em>No se han registrado observaciones para este movimiento</em></h4>
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