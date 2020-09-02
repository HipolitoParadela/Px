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
    <div class="content-wrap" id="delivery">
        <div class="main">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-8 p-r-0 title-margin-right">
                        <div class="page-header">
                            <div class="page-title">
                                <h1>Delivery - {{datoDelivery.Nombre_cliente}}, <span> {{ datoDelivery.FechaHora_pedido | FechaTimestampBaseDatos }}, {{datoDelivery.Direccion}}, repartidor asignado {{datoDelivery.Nombre_repartidor}}</span></h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="../comandas">Sistema restaurant</a></li>
                                    <li class="breadcrumb-item active">Cliente {{datoDelivery.Identificador }}</li>
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
                                        <i class="ti-user"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="stat-digit">{{datoDelivery.Nombre_cliente}}</div>
                                        <div class="stat-text">{{datoDelivery.Direccion}} - Te: {{datoDelivery.Telefono}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card p-0">
                                <div class="stat-widget-three">
                                    <div class="stat-icon bg-success">
                                        <i class="ti-car"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div v-if="datoDelivery.Repartidor_id == 0" class="stat-digit">Retira el cliente</div>
                                        <div v-else class="stat-digit">{{datoDelivery.Nombre_repartidor}}</div>
                                        <div class="stat-text">Repartidor</div>
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
                                        <div class="stat-digit">{{contarItems(itemsDelivery)}}</div>
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
                                        <div class="stat-digit">${{ sumarCuenta(itemsDelivery)}}</div>
                                        <div v-if="datoDelivery.Estado == 0" class="stat-text">Cuenta al momento</div>
                                        <div v-if="datoDelivery.Estado == 1" class="stat-text">Cuenta neta</div>
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
                                    <div class="stat-icon dib"><i class="ti-info-alt color-success border-success"></i></div>
                                    <div class="stat-content dib">
                                        <div class="stat-text">Datos extras de este pedido</div>

                                        <p>
                                            {{datoDelivery.Observaciones}}
                                            <hr>
                                            {{datoDelivery.Observaciones_cocina}}
                                            <hr>
                                            {{datoDelivery.Observaciones_delivery}}
                                        </p>
                                    </div>
                                    <!--  <pre>{{datoDelivery}}</pre> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card">
                                <div class="stat-widget-one">
                                    <!-- <div class="stat-icon dib"><i class="ti-timer color-primary border-primary"></i></div> -->
                                    <div class="stat-content dib">
                                        <div class="stat-text">Pedido tomado</div>
                                        <div class="stat-digit">{{datoDelivery.FechaHora_pedido | FechaTimestampBaseDatos}}</div>
                                    </div>
                                    <div class="stat-content dib" v-if="datoDelivery.FechaHora_cocina != null">
                                        <div class="stat-text">Finalizado en cocina</div>
                                        <div class="stat-digit">{{datoDelivery.FechaHora_cocina | FechaTimestampBaseDatos}}</div>
                                    </div>
                                    <div class="stat-content dib" v-else>
                                        <div class="stat-text">Finalizado en cocina</div>
                                        <button v-on:click="reportarCocina()" type="submit" class="btn btn-warning">Preparado</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card">
                                <div class="stat-widget-one">
                                    <div class="stat-icon dib"><i class="ti-timer color-danger border-danger"></i></div>
                                    <div class="stat-content dib">
                                        <div class="stat-text">Retiro/Envio del pedido</div>
                                        <div v-if="datoDelivery.FechaHora_salidarepartidor == null" class="stat-digit">
                                            <div class="row">
                                                <div class="col-6">
                                                    <select class="form-control" v-model="datoDelivery.Repartidor_id" v-on:change="asignar_cadete(datoDelivery.Repartidor_id)">
                                                        <option value="0">Retira cliente</option>
                                                        <option v-for="repartidor in listaUsuariosRepartidores" v-bind:value="repartidor.Id">{{repartidor.Nombre}}</option>
                                                    </select>
                                                </div>
                                                <div class="col-3">

                                                    <select class="form-control" v-model="datoDelivery.Valor_delivery" v-on:change="asignar_cadete(datoDelivery.Repartidor_id)">
                                                        <option value="0">Sin costo</option>
                                                        <option value="70">$70</option>
                                                        <option value="80">$80</option>
                                                        <option value="90">$90</option>
                                                        <option value="100">$100</option>
                                                        <option value="110">$110</option>
                                                        <option value="120">$120</option>
                                                        <option value="130">$130</option>
                                                    </select>
                                                </div>
                                                <div class="col-3">
                                                    <button v-on:click="cerrarPedido(datoDelivery.Id, sumarCuenta(itemsDelivery))" type="submit" class="btn btn-warning">Entregado a repartidor</button>
                                                </div>
                                            </div>

                                        </div>
                                        <div v-if="datoDelivery.FechaHora_salidarepartidor != null" class="stat-digit">
                                            {{datoDelivery.FechaHora_salidarepartidor | FechaTimestampBaseDatos}}

                                            <div v-if="datoDelivery.FechaHora_entregaCliente == null" class="stat-digit">
                                                <button v-on:click="reportarEntrega()" type="submit" class="btn btn-success">Reportar entrega</button>
                                            </div>

                                        </div>
                                    </div>
                                    <div v-if="datoDelivery.FechaHora_entregaCliente != null" class="stat-content dib">
                                        <div class="stat-text">Entregado al cliente</div>
                                        <div class="stat-digit">
                                            {{datoDelivery.FechaHora_entregaCliente | FechaTimestampBaseDatos}}
                                        </div>
                                    </div>
                                </div>
                                <!--  <pre>{{datoDelivery}}</pre> -->
                            </div>
                        </div>
                    </div>

                    <!-- /# row -->
                    <div class="row" class="stat-text">
                        <div class="col-lg-5">
                            <h4>Items pedidos</h2>
                                <div class="card">
                                    <div class="bootstrap-data-table-panel">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Nombre</th>
                                                        <th align="center" width="120px">Precio</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="item in itemsDelivery">
                                                        <td>{{item.Nombre_item}}</td>
                                                        <td align="center">${{item.Precio_venta}}</td>
                                                        <td>
                                                            <a v-if="datoDelivery.Estado == 0" style="cursor:pointer;" v-on:click="eliminar(item.Id,'tbl_delibery_items', item.Item_stock_id)" tittle="">
                                                                <span class="badge badge-danger">
                                                                    <i class="ti-na"></i>
                                                                </span>
                                                            </a>
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

                        <div class="col-lg-4" v-if="datoDelivery.Estado == 1">
                            <h4>Cuenta final a cobrar</h2>
                                <div class="card">
                                    <div class="bootstrap-data-table-panel">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <td>Cuenta</td>
                                                        <td><span class="text-success">${{datoDelivery.Valor_cuenta}}</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Costo delivery</td>
                                                        <td><span class="text-info"> ${{datoDelivery.Valor_delivery}}</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Descuento</td>
                                                        <td><span class="text-danger"> - ${{datoDelivery.Valor_descuento}}</span></td>
                                                    </tr>
                                                    <!-- <tr>
                                                        <td><b>Modo de pago</b></td>
                                                        <td align="left">
                                                            Efectivo <input type="radio" value="1" v-model="datoDelivery.Modo_pago" v-on:change="modoPago()"><br>
                                                            Tarjeta <input type="radio" value="2" v-model="datoDelivery.Modo_pago" v-on:change="modoPago()">
                                                        </td>
                                                    </tr> -->
                                                    <tr>
                                                        <td>Total</td>
                                                        <td>
                                                            <h4>${{sumarMontos(datoDelivery.Valor_cuenta, datoDelivery.Valor_delivery, datoDelivery.Valor_descuento)}}</h4>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <a href="#modalDescuento" data-toggle="modal" title="Aplicar descuento" class="btn btn-text btn-flat btn-addon m-b-10 m-l-5"><i class="ti-minus"></i> Aplicar descuento</a>
                                <!-- /# card -->
                        </div>

                        <div class="col-lg-3" v-if="datoDelivery.Estado == 1">
                            <div class="col-lg-3">
                                <div class="card p-0">
                                    <div class="stat-widget-three">
                                        <div class="stat-icon bg-info">
                                            <i class="ti-printer"></i>
                                        </div>
                                        <div class="stat-content">
                                            <div class="stat-digit">
                                                <a class="btn btn-info" v-bind:href="'../imprimirCuentaDelivery/?Id='+datoDelivery.Id" target="_blank" onClick="window.open(this.href, this.target, 'width=800,height=600'); return false;"> Imprimir Cuenta </a>
                                            </div>
                                            <div class="stat-text">&nbsp;</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card p-0">
                                    <div class="stat-widget-three">
                                        <div class="stat-icon bg-danger">
                                            <i class="ti-back-right"></i>
                                        </div>
                                        <div class="stat-content">
                                            <div class="stat-digit">
                                                <button class="btn btn-danger" v-on:click="reabrir_delivery()">
                                                    Reabrir pedido
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FINANZAS -->
                    <div class="row" v-if="datoDelivery.Estado == 1">
                        <div class="col-lg-3">
                            <div class="card p-0">
                                <div class="stat-widget-three">
                                    <div class="stat-icon bg-warning">
                                        <i class="ti-money"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="stat-digit">$ {{sumarMontos(datoDelivery.Valor_cuenta, datoDelivery.Valor_delivery, datoDelivery.Valor_descuento) | Moneda}}</div>
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
                                        <div class="stat-digit">$ {{ sumarMontos(datoDelivery.Valor_cuenta, datoDelivery.Valor_delivery, datoDelivery.Valor_descuento) - Total_pagado | Moneda}}</div>
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
                    <!-- fin FINANZAS -->
            </div>
        </div>
        <!-- -->

        <div class="row" v-if="datoDelivery.Estado == 0">
            <div class="col-lg-12">
                <h4>Listado de items</h2>
            </div>
            <div class="col-lg-2">

                <div class="card">
                    <div class="list-group">
                        <a style="cursor:pointer;" class="list-group-item" v-on:click="getListadoItems()">Menues delivery</a>
                        <a style="cursor:pointer;" class="list-group-item" v-on:click="getListadoTodosItems()">Todos</a>
                        <a style="cursor:pointer;" v-for="listaCategoria in categoriasCarta" class="list-group-item" v-on:click="cargarItemsbyCategoria(listaCategoria.Id)">{{listaCategoria.Nombre_categoria}}</a>
                    </div>
                </div>

            </div>
            <!-- /# card -->
            <div class="col-lg-10">
                <div class="card">
                    <div class="input-group input-group-default">
                        <span class="input-group-btn"><button class="btn btn-primary" type="submit"><i class="ti-search"></i></button></span>
                        <input type="text" class="form-control" placeholder="Buscar item" v-model="buscar"><br>
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
                                            <a style="cursor:pointer;" v-on:click="addItemDelivery(item.Id, item.Apto_stock)" title="Añadir item al pedido">
                                                <span class="badge badge-success"><i class="ti-plus"></i></span>
                                            </a>

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
                                            <a style="cursor:pointer;" href="#modalInfo" data-toggle="modal" v-on:click="infoItem(item)" title="Información de este item">
                                                <span class="badge badge-info"><i class="ti-eye"></i></span>
                                            </a>
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
                        <h5 class="modal-title" id="modalItemsCartaTitle">{{itemCarta.Nombre}}</h5>
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