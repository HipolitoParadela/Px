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
                                <h1>Compras <span></span></h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">PX Resto</a></li>
                                    <li class="breadcrumb-item active">Compras</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->

                <section id="main-content">
                    <div class="row">
                        <div class="col-md-2">
                            <!-- DATA TABLE -->
                            <div class="card">
                                <p align="center">
                                    <strong>Adeudado vencidas</strong>
                                </p>
                                <div class="card-body">
                                    <h1 align="center">$ {{ listaCompras.Total_valor_compras_vencidas - listaCompras.Total_dinero_pagado_vencidas | Moneda}} </h1>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-2">
                            <!-- DATA TABLE -->
                            <div class="card">
                                <p align="center">
                                    <strong>Adeudado por vencer</strong>
                                </p>
                                <div class="card-body">
                                    <h1 align="center">$ {{ listaCompras.Total_valor_compras_no_vencidas - listaCompras.Total_dinero_pagado_no_vencidas | Moneda }} </h1>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <!-- DATA TABLE -->
                            <div class="card">

                                <strong>Filtrar la consulta</strong>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-2 d-print-none">
                                            <button class="btn btn-secondary btn-block" v-on:click="getListadoCompras(filtro_rubro, null, null, 0)"> Desde siempre</button>
                                        </div>
                                        <div class="col-lg-2">
                                            <select class="form-control" v-model="filtro_jornada" v-on:change="getListadoCompras(filtro_rubro, null, null, filtro_jornada)">
                                                <option value="0">Todas las jornadas</option>
                                                <option v-for="jornada in listaJornadas" v-bind:value="jornada.Id">{{jornada.Descripcion}} | Del {{jornada.Fecha_inicio | FechaTimestampBaseDatos}} al {{jornada.Fecha_final | FechaTimestampBaseDatos}}</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-2">
                                            <select class="form-control" v-model="filtro_rubro" v-on:change="getListadoCompras(filtro_rubro, null, null, filtro_jornada)">
                                                <option value="0">Todos los rubros</option>
                                                <option v-for="rubro in listaRubros" v-bind:value="rubro.Id">{{rubro.Nombre_rubro}}</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-2">
                                            <input type="date" class="form-control" v-model="Filtro_fecha_inicial">
                                        </div>
                                        <div class="col-lg-2">

                                            <input type="date" class="form-control" v-model="Filtro_fecha_final" :disabled="Filtro_fecha_inicial == null" v-on:change="getListadoCompras(filtro_rubro, Filtro_fecha_inicial, Filtro_fecha_final, 0)">

                                        </div>
                                        <div class="col-lg-2 d-print-none">
                                            <button class="btn btn-success btn-block" data-toggle="modal" data-target="#compramodal" v-on:click="limpiarFormularioCompras()">
                                                <i class="zmdi zmdi-plus"></i> Nueva compra
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <pre>{{listaCompras}}</pre> -->


                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <!-- COMPRAS ADEUDADAS VENCIDAS -->
                            <div class="card">
                                <div class="card-header">
                                    <h4>Compras vencidas sin saldar ({{listaCompras.Compras_vencidas.length}})</h4>
                                </div>
                                <div class="card-body">

                                    <table class="table table-striped" v-if="listaCompras.Compras_vencidas.length > 0">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Factura</th>
                                                <th>Proveedor</th>
                                                <th>Rubro</th>
                                                <th>F. Compra</th>
                                                <th>F. Venc.</th>
                                                <th style="background-color:lightpink">Monto</th>
                                                <th style="background-color:darkseagreen">Pagado</th>
                                                <th style="background-color:wheat">Saldo</th>
                                                <th>Descripción</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="tr-shadow" v-for="compraVenc in listaCompras.Compras_vencidas">
                                                <td>
                                                    <div class="round-img d-print-none">
                                                        <a href="#modalcomprasFoto" data-toggle="modal" v-on:click="editarFormulariocompraFoto(compraVenc)">
                                                            <img v-if="compraVenc.Datos.Imagen != null" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+compraVenc.Datos.Imagen" width="60px">
                                                            <img v-else src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" width="50px" alt="">
                                                        </a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a v-bind:href="'compras/datos/?Id='+compraVenc.Datos.Id" class="btn btn-dark btn-outline m-b-10 m-l-5">
                                                        {{compraVenc.Datos.Factura_identificador}}
                                                    </a>
                                                </td>
                                                <td>{{compraVenc.Datos.Nombre_proveedor}}</td>
                                                <td>{{compraVenc.Datos.Nombre_rubro}}</td>
                                                <td>{{compraVenc.Datos.Fecha_compra | Fecha}}</td>
                                                <td>{{compraVenc.Datos.Fecha_vencimiento_pago | Fecha}}</td>
                                                <td align="right" style="background-color:lightpink">
                                                    <h4>{{compraVenc.Valor_compra | Moneda}}</h4>
                                                </td>
                                                <td align="right" style="background-color:darkseagreen">
                                                    <h4>{{compraVenc.Total_abonado | Moneda}}</h4>
                                                </td>
                                                <td align="right" style="background-color:wheat">
                                                    <h4>{{compraVenc.Valor_compra - compraVenc.Total_abonado | Moneda}}</h4>
                                                </td>

                                                <td>{{compraVenc.Datos.Descripcion | Recortar}}
                                                    <button class="item" v-on:click="infoEtapa(compraVenc.Datos.Descripcion)" data-toggle="modal" data-target="#modalObservaciones" data-placement="top" title="Ver observaciones">
                                                        <i class="fa fa-exclamation-circle"></i>
                                                    </button></td>
                                                <td>
                                                    <div class="table-data-feature d-print-none">
                                                        <a class="item" v-bind:href="'compras/datos/?Id='+compraVenc.Datos.Id" title="Ver todos los datos">
                                                            <i class="zmdi zmdi-mail-send"></i>
                                                        </a>
                                                        <button class="item" v-on:click="editarFormularioCompra(compraVenc.Datos)" data-toggle="modal" data-target="#compramodal" data-placement="top" title="Edición rápida">
                                                            <i class="ti-pencil-alt"></i>
                                                        </button>
                                                        <!-- <button v-on:click="desactivarcompra(compra.Datos.Id)" class="item" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                                                    <i class="zmdi zmdi-delete"></i>
                                                                </button> -->
                                                    </div>
                                                </td>
                                            <tr class="spacer"></tr>
                                            </tr>

                                        </tbody>
                                        <tfoot>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th style="background-color:lightpink">
                                                <h3 align="right">{{ listaCompras.Total_valor_compras_vencidas | Moneda}}</h3>
                                            </th>
                                            <th style="background-color:darkseagreen">
                                                <h3 align="right">{{ listaCompras.Total_dinero_pagado_vencidas | Moneda}}</h3>
                                            </th>
                                            <th style="background-color:wheat">
                                                <h3 align="right">{{ listaCompras.Total_valor_compras_vencidas - listaCompras.Total_dinero_pagado_vencidas | Moneda}}</h3>
                                            </th>
                                            <th></th>
                                            <th></th>
                                        </tfoot>
                                    </table>

                                    <!-- END DATA TABLE -->
                                </div>
                            </div>
                            <!-- COMPRAS ADEUDADAS NO VENCIDAS -->
                            <div class="card">
                                <div class="card-header">
                                    <h4>Compras sin saldar por vencer ({{listaCompras.Compras_no_vencidas.length}})</h4>
                                </div>
                                <div class="card-body">

                                    <table class="table table-striped" v-if="listaCompras.Compras_no_vencidas.length > 0">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Factura</th>
                                                <th>Proveedor</th>
                                                <th>Rubro</th>
                                                <th>F. Compra</th>
                                                <th>F. Venc.</th>
                                                <th style="background-color:lightpink">Monto</th>
                                                <th style="background-color:darkseagreen">Pagado</th>
                                                <th style="background-color:wheat">Saldo</th>
                                                <th>Descripción</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="tr-shadow" v-for="compra in listaCompras.Compras_no_vencidas">
                                                <td>
                                                    <div class="round-img d-print-none">
                                                        <a href="#modalcomprasFoto" data-toggle="modal" v-on:click="editarFormulariocompraFoto(compra)">
                                                            <img v-if="compra.Datos.Imagen != null" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+compra.Datos.Imagen" width="60px">
                                                            <img v-else src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" width="50px" alt="">
                                                        </a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a v-bind:href="'compras/datos/?Id='+compra.Datos.Id" class="btn btn-dark btn-outline m-b-10 m-l-5">
                                                        {{compra.Datos.Factura_identificador}}
                                                    </a>
                                                </td>
                                                <td>{{compra.Datos.Nombre_proveedor}}</td>
                                                <td>{{compra.Datos.Nombre_rubro}}</td>
                                                <td>{{compra.Datos.Fecha_compra | Fecha}}</td>
                                                <td>{{compra.Datos.Fecha_vencimiento_pago | Fecha}}</td>
                                                <td align="right" style="background-color:lightpink">
                                                    <h4>{{compra.Valor_compra | Moneda}}</h4>
                                                </td>
                                                <td align="right" style="background-color:darkseagreen">
                                                    <h4>{{compra.Total_abonado | Moneda}}</h4>
                                                </td>
                                                <td align="right" style="background-color:wheat">
                                                    <h4>{{compra.Valor_compra - compra.Total_abonado | Moneda}}</h4>
                                                </td>

                                                <td>{{compra.Datos.Descripcion | Recortar}}
                                                    <button class="item" v-on:click="infoEtapa(compra.Datos.Descripcion)" data-toggle="modal" data-target="#modalObservaciones" data-placement="top" title="Ver observaciones">
                                                        <i class="fa fa-exclamation-circle"></i>
                                                    </button></td>
                                                <td>
                                                    <div class="table-data-feature d-print-none">
                                                        <a class="item" v-bind:href="'compras/datos/?Id='+compra.Datos.Id" title="Ver todos los datos">
                                                            <i class="zmdi zmdi-mail-send"></i>
                                                        </a>
                                                        <button class="item" v-on:click="editarFormularioCompra(compra.Datos)" data-toggle="modal" data-target="#compramodal" data-placement="top" title="Edición rápida">
                                                            <i class="ti-pencil-alt"></i>
                                                        </button>
                                                        <!-- <button v-on:click="desactivarcompra(compra.Datos.Id)" class="item" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                                                    <i class="zmdi zmdi-delete"></i>
                                                                </button> -->
                                                    </div>
                                                </td>
                                            <tr class="spacer"></tr>
                                            </tr>

                                        </tbody>
                                        <tfoot>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th style="background-color:lightpink">
                                                <h3 align="right">{{ listaCompras.Total_valor_compras_no_vencidas | Moneda}}</h3>
                                            </th>
                                            <th style="background-color:darkseagreen">
                                                <h3 align="right">{{ listaCompras.Total_dinero_pagado_no_vencidas | Moneda}}</h3>
                                            </th>
                                            <th style="background-color:wheat">
                                                <h3 align="right">{{ listaCompras.Total_valor_compras_no_vencidas - listaCompras.Total_dinero_pagado_no_vencidas | Moneda}}</h3>
                                            </th>
                                            <th></th>
                                            <th></th>
                                        </tfoot>
                                    </table>

                                    <!-- END DATA TABLE -->
                                </div>
                            </div>
                            <!-- COMPRAS SALDADAS -->
                            <div class="card">
                                <div class="card-header">
                                    <h4>Compras saldadas</h4>
                                </div>
                                <div class="card-body">

                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Factura</th>
                                                <th>Proveedor</th>
                                                <th>Rubro</th>
                                                <th>F. Compra</th>
                                                <th>F. Venc.</th>
                                                <th style="background-color:wheat">Monto</th>
                                                <th>Descripción</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="tr-shadow" v-for="(compra, index) in listaCompras.Compras_saldadas" v-show="(pag - 1) * NUM_RESULTS <= index  && pag * NUM_RESULTS > index">
                                                <td>
                                                    <div class="round-img d-print-none">
                                                        <a href="#modalcomprasFoto" data-toggle="modal" v-on:click="editarFormulariocompraFoto(compra)">
                                                            <img v-if="compra.Imagen != null" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+compra.Imagen" width="60px">
                                                            <img v-else src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" width="50px" alt="">
                                                        </a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a v-bind:href="'compras/datos/?Id='+compra.Id" class="btn btn-dark btn-outline m-b-10 m-l-5">
                                                        {{compra.Factura_identificador}}
                                                    </a>
                                                </td>
                                                <td>{{compra.Nombre_proveedor}}</td>
                                                <td>{{compra.Nombre_rubro}}</td>
                                                <td>{{compra.Fecha_compra | Fecha}}</td>
                                                <td>{{compra.Fecha_vencimiento_pago | Fecha}}</td>
                                                <td align="right" style="background-color:wheat">
                                                    <h4>{{sumarComrpra(compra.Neto, compra.No_gravado, compra.IVA) | Moneda}}</h4>
                                                </td>

                                                <td>
                                                    {{compra.Descripcion | Recortar}}
                                                    <button class="item" v-on:click="infoEtapa(compra.Descripcion)" data-toggle="modal" data-target="#modalObservaciones" data-placement="top" title="Ver observaciones">
                                                        <i class="fa fa-exclamation-circle"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    <div class="table-data-feature d-print-none">
                                                        <a class="item" v-bind:href="'compras/datos/?Id='+compra.Id" title="Ver todos los datos">
                                                            <i class="zmdi zmdi-mail-send"></i>
                                                        </a>
                                                        <button class="item" v-on:click="editarFormularioCompra(compra)" data-toggle="modal" data-target="#compramodal" data-placement="top" title="Edición rápida">
                                                            <i class="ti-pencil-alt"></i>
                                                        </button>
                                                        <!-- <button v-on:click="desactivarcompra(compra.Id)" class="item" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                                                    <i class="zmdi zmdi-delete"></i>
                                                                </button> -->
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th style="background-color:wheat">
                                                <h3 align="right">
                                                    {{totalCompras(listaCompras.Compras_saldadas) | Moneda}}
                                                </h3>
                                            </th>
                                            <th></th>
                                            <th></th>

                                        </tfoot>
                                    </table>

                                    <div class="row">
                                        <div class="col-lg-8">
                                        </div>
                                        <div class="col-lg-2">
                                            <nav aria-label="Page navigation" class="text-center">
                                                <hr>
                                                <ul class="pagination text-center">
                                                    <li>
                                                        <a href="#" class="btn btn-secondary btn-sm" aria-label="Previous" v-show="pag != 1" @click.prevent="pag -= 1">
                                                            <span aria-hidden="true">Anterior</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" class="btn btn-secondary btn-sm" aria-label="Next" v-show="pag * NUM_RESULTS / listaCompras.Compras_saldadas.length < 1" @click.prevent="pag += 1">
                                                            <span aria-hidden="true">Siguiente</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                        <div class="col-lg-2">
                                            <nav aria-label="Page navigation" class="text-center">
                                                <hr>
                                                <ul class="pagination text-center">
                                                    <li>
                                                        <select class="form-control" v-model="NUM_RESULTS">
                                                            <option value="10">10</option>
                                                            <option value="20">20</option>
                                                            <option value="50">50</option>
                                                            <option value="100">100</option>
                                                            <option value="500">500</option>
                                                        </select>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- modal compras -->
                <div class="modal fade" id="compramodal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="scrollmodalLabel">Formulario de compra</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <!-- <pre>{{compraDatos}}</pre> -->
                            <form class="form-horizontal" action="post" v-on:submit.prevent="crearCompra()">
                                <div class="modal-body">
                                    <div class="horizontal-form">
                                        <div class="form-group">
                                            <label class=" form-control-label">Número factura o Identificador de la compra</label>
                                            <input type="text" class="form-control" placeholder="" v-model="compraDatos.Factura_identificador" required>
                                        </div>
                                        <div class="form-group">
                                            <label class=" form-control-label">Seleccionar proveedor</label>
                                            <select class="form-control" v-model="compraDatos.Proveedor_id" required>
                                                <option value="0">No asignar proveedor</option>
                                                <option v-for="proveedor in listaProveedores_select" v-bind:value="proveedor.Id">{{proveedor.Nombre_proveedor}}</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class=" form-control-label">Jornada en la que se realizó la compra</label>
                                            <select class="form-control" v-model="compraDatos.Periodo_id" required>
                                                <option value="0">No asignar jornada</option>
                                                <option v-for="jornada in listaJornadas" v-bind:value="jornada.Id">{{jornada.Descripcion}} | Del {{jornada.Fecha_inicio | FechaTimestampBaseDatos}} al {{jornada.Fecha_final | FechaTimestampBaseDatos}}</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class=" form-control-label">Fecha en que se realizó la compra</label>
                                            <input type="date" class="form-control" placeholder="" v-model="compraDatos.Fecha_compra" required>
                                        </div>
                                        <div class="form-group">
                                            <label class=" form-control-label">Fecha vencimiento pago</label>
                                            <input type="date" class="form-control" placeholder="" v-model="compraDatos.Fecha_vencimiento_pago" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Valor neto de la compra</label>
                                            <input type="number" class="form-control" placeholder="" v-model="compraDatos.Neto" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">No gravado</label>
                                            <input type="number" class="form-control" placeholder="" v-model="compraDatos.No_gravado" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Valor IVA</label>
                                            <input type="number" class="form-control" placeholder="" v-model="compraDatos.IVA" required>
                                        </div>
                                        <div class="form-group">
                                            <label class=" form-control-label">Estado del pago</label>
                                            <select class="form-control" v-model="compraDatos.Saldada" required>
                                                <option value="0">Pago Parcial o nulo</option>
                                                <option value="1">Pago Completo</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class=" form-control-label">Descripción de la compra</label>
                                            <textarea class="form-control" rows="5" v-model="compraDatos.Descripcion"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-success">{{texto_boton}}</button>
                                    <a v-if="compraDatos.Id" v-bind:href="'compras/datos/?Id='+compraDatos.Id" class="btn btn-primary">
                                        Administrar compra
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- end modal compras -->
                <!-- Modal compras Fotos-->
                <div class="modal fade" id="modalcomprasFoto" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="true">
                    <div class="modal-dialog  modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalItemsFoto">Fotografía de {{compraFoto.Factura_identificador}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p align="center">
                                    <img v-if="compraFoto.Imagen != null" class="avatar_grande" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+compraFoto.Imagen" alt="">
                                    <img v-else class="avatar_grande" src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" alt="">
                                </p>
                                <hr>
                                <div class="horizontal-form">
                                    <!-- <form class="form-horizontal" action="post" enctype="multipart/form-data" v-on:submit.prevent="crearcompras()">  -->
                                    <form class="form-horizontal" action="post" enctype="multipart/form-data" v-on:submit.prevent="subirFotoCompra(compraFoto.Id)">
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

                <!-- END MAIN CONTENT-->
                <!-- END PAGE CONTAINER-->
                <?php /// CABECERA BODY
                include "footer.php";
                ?>