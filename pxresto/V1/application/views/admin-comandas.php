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
                                <h1>Comandas, <span>Listado de comandas en proceso</span></h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Sistema restaurant</a></li>
                                    <li class="breadcrumb-item active">Comandas</li>
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
                            <a href="#modalNuevaComanda" data-toggle="modal" title="Nueva comanda" class="btn btn-success btn-flat btn-addon m-b-10 m-l-5" v-on:click="limpiarFormularioComandas()">
                                <i class="ti-plus"></i> Nueva comanda</a>
                            <!-- <a href="#" data-toggle="modal" class="btn btn-danger btn-flat btn-addon m-b-10 m-l-5" v-on:click="actualizarListadoComandas()"><i class="ti-reload"></i> Actualizar</a> -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3" v-for="comanda in listaComandas">
                            <div class="card">
                                <a v-bind:href="'comanda/?Id='+comanda.Datos_comanda.Id">
                                    <div class="stat-widget-two">
                                        <div class="stat-content">
                                            <div class="stat-digit">Mesa {{comanda.Datos_comanda.Identificador}}</div>
                                            <p class="text-primary" align="center">Mozo: {{comanda.Datos_comanda.Nombre_moso}}</p>
                                            <p align="center">{{comanda.Datos_comanda.Nombre_cliente}} | {{formatoFecha(comanda.Datos_comanda.Fecha)}}</p>

                                            <div v-if="comanda.Cant_pendientes > 0" class="stat-text text-danger">Items restantes de entregar</div>

                                            <ul>
                                                <li v-for="item in comanda.Datos_items">{{item.Nombre_item}}</li>
                                            </ul>
                                        </div>
                                        <div class="progress">
                                            <div v-if="comanda.Alerta == 0" class="progress-bar progress-bar-success w-100" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                            <div v-if="comanda.Alerta == 1" class="progress-bar progress-bar-warning w-100" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                            <div v-if="comanda.Alerta == 2" class="progress-bar progress-bar-danger w-100" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <p align="center">
                                <a href="#modalNuevaComanda" data-toggle="modal" title="Nueva comanda" v-on:click="editComanda(comanda.Datos_comanda)"><i class="ti-pencil-alt"></i></a>
                            </p>
                        </div>
                    </div>
                    <!-- /# row -->
                    <div class="row">
                        <div class="col-lg-12">
                            <p align="center"><a class="btn btn-info" href="../restaurant/resumencomandas"> Ver comandas cerradas</a></p>
                            <!-- /# column -->
                        </div>
                        <!-- /# row -->
                </section>
            </div>
            <!-- Modal NUEVA COMANDA -->
            <div class="modal fade" id="modalNuevaComanda" tabindex="-1" role="dialog" aria-labelledby="modalItemsCartaTitle" aria-hidden="true">
                <div class="modal-dialog  modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalItemsCartaTitle">{{texto_boton}} Comanda</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-8">
                                    <div class="horizontal-form">
                                        <form class="form-horizontal" action="post" v-on:submit.prevent="crearComanda()">

                                            <!-- <div class="form-group">
                                                <label class="col-sm-12 control-label">Nombre o descripción de clientes <span style="font-size:12px"><br><em>A modo de identificar quienes estan en la mesa</em></span></label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" placeholder="" v-model="comanda.Cliente_referente">
                                                </div>
                                            </div> -->

                                            <!-- BUSCANDO CLIENTE YA CARGADO -->
                                            <div class="form-group">
                                                <label class="col-sm-12 control-label">Escribir nombre del cliente *<span style="font-size:12px"></span></label>
                                                <input list="productos" class="form-control" v-model="comanda.Cliente_id" required v-on:change="datoCliente(comanda.Cliente_id)">
                                                <datalist type="search" id="productos">
                                                    <option value="0">NUEVO CLIENTE</option>
                                                    <option v-for="cliente in listaClientes" v-bind:value="cliente.Id">{{cliente.Nombre}} - {{cliente.Telefono}}</option>
                                                </datalist>

                                                <!-- SI NO ESTA CARGADO, LO CARGA DESDE ACA -->
                                                <div v-if="comanda.Cliente_id == 0">
                                                    <label class="col-sm-12 control-label">Nombre del cliente *<span style="font-size:12px"></span></label>
                                                    <div class="col-sm-12">
                                                        <input type="text" class="form-control" placeholder="" v-model="comanda.Nombre_cliente" required>
                                                    </div>
                                                </div>
                                                <div v-if="comanda.Cliente_id == 0">
                                                    <label class="col-sm-12 control-label">Dirección y localidad</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" class="form-control" placeholder="" v-model="comanda.Direccion" :disabled="comanda.Nombre_cliente == null">
                                                    </div>
                                                </div>
                                                <div v-if="comanda.Cliente_id == 0">
                                                    <label class="col-sm-12 control-label">Teléfono</label>
                                                    <div class="col-sm-12">
                                                        <input type="number" class="form-control" placeholder="" v-model="comanda.Telefono" :disabled="comanda.Nombre_cliente == null" required>
                                                    </div>
                                                </div>
                                                <!-- fin clientes -->
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="col-sm-12 control-label">Cant. personas</label>

                                                            <input type="number" class="form-control" placeholder="" v-model="comanda.Cant_personas" required>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="form-group">
                                                            <label class="col-sm-12 control-label">Seleccionar Mesa </label>
                                                            <select class="form-control" v-model="comanda.Mesa_id" required>
                                                                <option v-for="mesa in listaMesas" v-bind:value="mesa.Id">{{mesa.Identificador}}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="form-group">
                                                    <div class="col-sm-offset-4 col-sm-10">
                                                        <a v-if="comanda.Id" v-bind:href="'comanda/?Id='+comanda.Id" class="btn btn-info">Administrar comanda</a>
                                                        <button type="submit" class="btn btn-success">{{texto_boton}}</button>

                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-sm-4" v-if="comanda.Cliente_id > 0">
                                    <h6>Datos del cliente</h6>
                                    <h2>{{datoClienteSeleccionado.Nombre}}</h2>
                                    <h4>{{datoClienteSeleccionado.Direccion}}</h4>
                                    <h4 class="text-success">{{datoClienteSeleccionado.Telefono}}</h4>
                                    <!-- <hr>
                                    <h6>Ultimos pedidos</h6>
                                    <ul>
                                        <li></li>
                                    </ul> -->
                                </div>

                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal -->
            
        </div>
        <?php /// FOOTER
        include "footer.php";
        ?>