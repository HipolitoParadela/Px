<?php
// CABECERA
include "head.php";
// TOP
include "top.php";
include "menusidebar.php";
?>
<!-- PAGE CONTAINER-->
<div class="page-container" id="ordentrabajo">
    <!-- HEADER DESKTOP-->
    <?PHP include "header_desktop.php"; ?>
    <!-- HEADER DESKTOP-->

    <!-- MAIN CONTENT-->
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-2">
                        <div class="card">
                            <div class="card-header">
                                <h4>Info</h4>
                            </div>
                            <div class="card-body">
                                <div class="user-photo m-b-30">
                                    <img v-if="ordenDatos.Imagen != null" width="420px" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+ordenDatos.Imagen" alt="">
                                    <img v-else src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" alt="">
                                </div>
                                <h5 class="text-sm-center mt-2 mb-1">{{ordenDatos.Nombre_producto}}</h5>
                                <p class="text-sm-center mt-2 mb-1">Producción a cargo de {{ordenDatos.Nombre_usuario}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="card">
                            <div class="card-header">
                                <h4>Tiempos</h4>

                            </div>
                            <div class="card-body">
                                <div v-if="ordenDatos.Estado == 0">
                                    <p class="text-sm-center mt-2 mb-1"><b>{{diferenciasEntre_fechas(ordenDatos.Fecha_inicio, null)}}</b> de comenzada su producción</p>
                                    <p class="text-sm-center mt-2 mb-1"><b> Finalización:</b> {{diferenciasEntre_fechas(null, ordenDatos.Fecha_estimada_finalizacion)}} según fecha estimada</p>
                                </div>
                                <div v-if="ordenDatos.Estado > 0">
                                    <p class="text-sm-center mt-2 mb-1"><b>Finalizado el día </b>{{ordenDatos.Fecha_finalizado | Fecha}}. </p>
                                    <p class="text-sm-center mt-2 mb-1">Su producción demandó <b>{{diferenciasEntre_fechas(ordenDatos.Fecha_inicio, ordenDatos.Fecha_finalizado)}}</b>.</p>
                                    <p class="text-sm-center mt-2 mb-1">
                                        Diferencia entre finalizado y su estimación: <b>{{diferenciasEntre_fechas(ordenDatos.Fecha_finalizado, ordenDatos.Fecha_estimada_finalizacion)}}</b>
                                    </p>
                                </div>
                                <div v-if="ordenDatos.Estado == 2">
                                    <p class="text-sm-center mt-2 mb-1"><b>Despachado a cliente el día</b> {{ordenDatos.Fecha_despacho_cliente | Fecha}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card" v-show="ordenDatos.Estado == 0">
                            <div class="card-header">
                                <h4>Registrar finalizado</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label class=" form-control-label">Fecha de finalizado</label>
                                    <input type="date" class="form-control form-control" v-model="fecha_boton">
                                </div>
                                <button class="btn btn-warning btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="cambiar_estado(1)" v-show="fecha_boton != null">
                                    Guardar
                                </button>
                            </div>
                        </div>
                        <div class="card" v-show="ordenDatos.Estado == 1">
                            <div class="card-header">
                                <h4>Registrar despacho a cliente</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label class=" form-control-label">Fecha de despacho</label>
                                    <input type="date" class="form-control form-control" v-model="fecha_boton">
                                </div>
                                <button class="btn btn-warning btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="cambiar_estado(2)" v-show="fecha_boton != null">
                                    Guardar
                                </button>
                            </div>
                        </div>

                    </div>


                    <!-- SECCION FICHA cliente -->
                    <div class="col-lg-8">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 1 }" href="#" v-on:click="mostrar = 1">Ficha</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 2 }" href="#" v-on:click="mostrar = 2">Seguimiento</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 3 }" href="#" v-on:click="mostrar = 3">Insumos usados</a>
                            </li>
                        </ul>

                        <!-- SECCION DATOS EDITABLES DEL cliente -->
                        <div class="row" v-show="mostrar == '1'">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Ficha: {{ordenDatos.Nombre_producto }}</strong>
                                        Última actualización: {{ ordenDatos.Ultima_actualizacion | FechaTimeBD }}
                                    </div>
                                    <div class="card-body">
                                        <div class="horizontal-form">
                                            <form class="form-horizontal" action="post" v-on:submit.prevent="crearOrden()">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <h4>Datos de la orden</h4>
                                                        <hr>
                                                        <div class="form-group">
                                                            <label class="control-label">Responsable</label>
                                                            <select class="form-control" v-model="ordenDatos.Usuario_respondable_id" required>
                                                                <option value="0">Seleccionar persona</option>
                                                                <option v-for="persona in listaUsuarios" v-bind:value="persona.Id">{{persona.Nombre}}</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Cliente</label>
                                                            <select class="form-control" v-model="ordenDatos.Cliente_id" required>
                                                                <option value="0">Seleccionar persona</option>
                                                                <option v-for="cliente in listaClientes" v-bind:value="cliente.Id">{{cliente.Nombre_cliente}}</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class=" form-control-label">Identificador de pieza/pruducto</label>
                                                            <input type="text" class="form-control" v-model="ordenDatos.Numero_pieza" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class=" form-control-label">Seleccionar periodo al que pertenece esta orden</label>
                                                            <select class="form-control" v-model="ordenDatos.Periodo_id" required>
                                                                <option v-for="periodo in listaPeriodos" v-bind:value="periodo.Id">{{periodo.Nombre_periodo}}</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class=" form-control-label">Fecha inicio de fabricación</label>
                                                            <input type="date" class="form-control" v-model="ordenDatos.Fecha_inicio" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class=" form-control-label">Fecha estimada para finalizar fabricación</label>
                                                            <input type="date" class="form-control" v-model="ordenDatos.Fecha_estimada_finalizacion" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class=" form-control-label">Observaciones</label>
                                                            <textarea class="form-control" rows="5" v-model="ordenDatos.Observaciones"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <hr>
                                                        <button type="submit" class="btn btn-success">Actualizar datos</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECCION DATOS DE SEGUIMIENTO -->
                        <div class="row" v-show="mostrar == '2'">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Seguimiento</strong>
                                    </div>
                                    <div class="card-body">
                                        <p align="right">
                                            <a href="#modalSeguimiento" data-toggle="modal" title="Nuevo item" class="btn btn-success btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="limpiarFormularioSeguimiento()">
                                                <i class="ti-plus"></i> Añadir reporte
                                            </a>
                                        </p>
                                        <div class="bootstrap-data-table-panel col-lg-12">
                                            <div class="table-responsive">
                                                <table id="table2excel" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Fecha</th>
                                                            <th>Descripcion</th>
                                                            <!-- <th>Usuario</th> -->
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="seguimiento in listaSeguimiento">
                                                            <td>{{seguimiento.Fecha_hora | FechaTimeBD}}</td>
                                                            <td>{{seguimiento.Descripcion}}</td>
                                                            <!-- <td>{{seguimiento.Nombre}}</td> -->
                                                            <td>
                                                                <a href="#modalSeguimiento" data-toggle="modal" v-on:click="editarFormularioSeguimiento(seguimiento)">
                                                                    <i class="fa fa-edit"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECCION INSUMOS USADOS -->
                        <div class="row" v-show="mostrar == '3'">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Producto/insumos utilizados en la fabricación</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="bootstrap-data-table-panel col-lg-12">
                                            <div class="table-responsive">
                                                <div class="table-responsive">
                                                    <table id="table2excel" class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Producto</th>
                                                                <th>Cantidad</th>
                                                                <th>Fecha</th>
                                                                <th>Descripción</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="producto in listaproductosUsados">
                                                                <td>{{producto.Nombre_item}}</td>
                                                                <td>{{producto.Cantidad}}</td>
                                                                <td>{{producto.Fecha_hora | FechaTimeBD}}</td>
                                                                <td>{{producto.Descripcion}}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal SEGUIMIENTO-->
    <div class="modal fade" id="modalSeguimiento" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{texto_boton}} reporte de seguimiento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- <pre>{{seguimientoData}}</pre> -->
                    <div class="horizontal-form">
                        <form class="form-horizontal" action="post" v-on:submit.prevent="crearSeguimiento()">
                            <!--   -->
                            <div class="form-group">
                                <label class="control-label">Datos del seguimiento</label>
                                <textarea class="form-control" rows="5" placeholder="" v-model="seguimientoData.Descripcion" required></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">{{texto_boton}}</button>
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
    <!-- END MAIN CONTENT-->
    <!-- END PAGE CONTAINER-->
    <?php
    // CABECERA
    include "footer.php";
    ?>
    </body>

    </html>
    <!-- end document-->