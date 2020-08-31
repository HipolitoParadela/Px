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
                        <h3 class="title-5 m-b-35">
                            Ventas
                        </h3>
                        <div class="table-data__tool">
                            <div class="table-data__tool-left">
                                <div class="rs-select2--light">
                                    <select class="form-control form-control" v-model="filtro_vendedor" v-on:change="getListadoVentas(filtro_vendedor, filtro_empresa ,filtro_cliente,filtro_estado)">
                                        <option selected="selected" v-bind:value="0">Todos los vendedores</option>
                                        <option v-for="usuario in listaUsuarios" v-bind:value="usuario.Id">{{usuario.Nombre}}</option>

                                        <!-- ACA DEBE TRAER SOLO USUARIOS VENDEDORES -->

                                    </select>
                                    <div class="dropDownSelect2"></div>
                                </div>
                                <div class="rs-select2--light">
                                    <select class="form-control form-control" v-model="filtro_empresa" v-on:change="getListadoVentas(filtro_vendedor, filtro_empresa ,filtro_cliente,filtro_estado)">
                                        <option selected="selected" v-bind:value="0">Todas las empresas</option>
                                        <option v-for="empresa in listaEmpresas" v-bind:value="empresa.Id">{{empresa.Nombre_empresa}}</option>
                                    </select>
                                    <div class="dropDownSelect2"></div>
                                </div>
                                <div class="rs-select2--light">
                                    <select class="form-control form-control" v-model="filtro_cliente" v-on:change="getListadoVentas(filtro_vendedor, filtro_empresa ,filtro_cliente,filtro_estado)">
                                        <option selected="selected" v-bind:value="0">Todos los clientes</option>
                                        <option v-for="cliente in listaClientes" v-bind:value="cliente.Id">{{cliente.Nombre_cliente}}</option>
                                    </select>
                                    <div class="dropDownSelect2"></div>
                                </div>
                                <div class="rs-select2--light">
                                    <select class="form-control form-control" v-model="filtro_estado" v-on:change="getListadoVentas(filtro_vendedor, filtro_empresa ,filtro_cliente,filtro_estado)">
                                        <option selected="selected" v-bind:value="1">Ventas en proceso</option>
                                        <option selected="selected" v-bind:value="5">Ventas cerradas</option>
                                    </select>
                                    <div class="dropDownSelect2"></div>
                                </div>
                            </div>
                            <div class="table-data__tool-right">
                                <button class="au-btn au-btn-icon au-btn--green au-btn--small" data-toggle="modal" data-target="#ventaModal" v-on:click="limpiarFormularioVenta()">
                                    <i class="zmdi zmdi-plus"></i>Nueva venta
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive table-responsive-data2">
                            <table class="table table-data2">
                                <thead>
                                    <tr>
                                        <th>Identificador</th>
                                        <th>Estado Actual</th>
                                        <th>Empresa</th>
                                        <th>Vendedor</th>
                                        <th>Cliente</th>
                                        <th>Iniciado</th>
                                        <th v-show="filtro_estado > 4">Finalizado</th>
                                        <th v-show="filtro_estado > 4">Tiempo de fabricación</th>
                                        <th v-show="filtro_estado < 5">Finalización estimada</th>
                                        <th v-show="filtro_estado < 5">Días restantes estimados</th>

                                        <th>Cant. Prod</th>
                                        <th style="background-color:darkseagreen">Monto Venta</th>
                                        <th style="background-color:darkseagreen">Monto Abonado</th>
                                        <th style="background-color:wheat">Saldo</th>

                                        <th>Observaciones</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="tr-shadow" v-for="venta in listaVentas">
                                        <td>
                                            <a v-bind:href="'ventas/datos/?Id='+venta.Datos.Id" class="btn btn-dark btn-outline m-b-10 m-l-5">
                                                {{venta.Datos.Identificador_venta}}
                                            </a>
                                        </td>
                                        <!-- Seteando Estados -->
                                        <td v-if="venta.Datos.Estado == 1"><b>Etapa Inicial</b></td>
                                        <td v-if="venta.Datos.Estado == 2"><b>Etapa de producción</b></td>
                                        <td v-if="venta.Datos.Estado == 3"><b>Etapa de colocación / Publicación</b></td>
                                        <td v-if="venta.Datos.Estado == 4"><b>Etapa de cierre y cobro</b></td>
                                        <td v-if="venta.Datos.Estado > 4"><b>Trabajo Terminado</b></td>

                                        <td>{{venta.Datos.Nombre_empresa}}</td>
                                        <td>{{venta.Datos.Nombre_vendedor}}</td>
                                        <td>{{venta.Datos.Nombre_cliente}}</td>
                                        <td>{{venta.Datos.Fecha_venta | Fecha}}</td>

                                        <td v-show="filtro_estado > 4">{{venta.Datos.Fecha_entrega | Fecha}}</td>
                                        <td v-show="filtro_estado > 4">{{diferenciasEntre_fechas(venta.Datos.Fecha_venta, venta.Datos.Fecha_entrega)}}</td>

                                        <td v-show="filtro_estado < 4">{{venta.Datos.Fecha_estimada_entrega | Fecha}}</td>
                                        <td v-show="filtro_estado < 4">{{diferenciasEntre_fechas(null, venta.Datos.Fecha_estimada_entrega)}}</td>

                                        <td align="center"><h4>{{venta.Total_productos_cantidad}}</h4></td>
                                        <td style="background-color:darkseagreen" align="center"><h4>${{venta.Total_productos_precios | Moneda}}</h4></td>
                                        <td style="background-color:darkseagreen" align="center"><h4>${{venta.Total_cobrado | Moneda}}</h4></td>
                                        <td style="background-color:wheat" align="center"><h4>${{venta.Total_productos_precios - venta.Total_cobrado  | Moneda}}</h4></td>

                                        <td>{{venta.Datos.Observaciones_venta}}</td>
                                        <td>
                                            <div class="table-data-feature">

                                                <a class="item" v-bind:href="'ventas/datos/?Id='+venta.Datos.Id" title="Ver todos los datos">
                                                    <i class="zmdi zmdi-mail-send"></i>
                                                </a>
                                                <button v-show="filtro_estado < 5" class="item" v-on:click="editarFormularioVenta(venta.Datos)" data-toggle="modal" data-target="#ventaModal" data-placement="top" title="Edición rápida">
                                                    <i class="zmdi zmdi-edit"></i>
                                                </button>
                                                <?php
                                                if ($this->session->userdata('Rol_acceso') > 4) {
                                                    echo '
                                                                <button v-show="filtro_estado < 10" v-on:click="desactivarVenta(venta.Datos.Id)" class="item" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                                                    <i class="zmdi zmdi-delete"></i>
                                                                </button>';
                                                }
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
    </div>
    <!-- modal ordens -->
    <div class="modal fade" id="ventaModal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollmodalLabel">Formulario de venta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal" action="post" v-on:submit.prevent="crearVenta()">
                    <div class="modal-body">
                        <div class="horizontal-form">
                            <div class="form-group">
                                <label class=" form-control-label">Identificador de la vente</label>
                                <input type="text" class="form-control" v-model="ventaDatos.Identificador_venta" required>
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Periodo en el que se realizó la venta</label>
                                <select class="form-control" v-model="ventaDatos.Periodo_id" required>
                                    <option v-for="periodo in listaPeriodos.Datos" v-bind:value="periodo.Id">{{periodo.Identificador}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Empresa</label>
                                <select class="form-control" v-model="ventaDatos.Empresa_id" required>
                                    <option value="0">Seleccionar empresa</option>
                                    <option v-for="empresa in listaEmpresas" v-bind:value="empresa.Id">{{empresa.Nombre_empresa}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Vendedor</label>
                                <select class="form-control" v-model="ventaDatos.Vendedor_id" required>
                                    <option value="0">Seleccionar persona</option>
                                    <option v-for="persona in listaUsuarios" v-bind:value="persona.Id">{{persona.Nombre}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Cliente</label>
                                <select class="form-control" v-model="ventaDatos.Cliente_id" required>
                                    <option value="0">Seleccionar persona</option>
                                    <option v-for="cliente in listaClientes" v-bind:value="cliente.Id">{{cliente.Nombre_cliente}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Responsable producción parte 1</label>
                                <select class="form-control" v-model="ventaDatos.Responsable_id_planif_inicial" required>
                                    <option value="0">Seleccionar persona</option>
                                    <option v-for="persona in listaUsuarios" v-bind:value="persona.Id">{{persona.Nombre}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Responsable producción parte 2</label>
                                <select class="form-control" v-model="ventaDatos.Responsable_id_planif_final" required>
                                    <option value="0">Seleccionar persona</option>
                                    <option v-for="persona in listaUsuarios" v-bind:value="persona.Id">{{persona.Nombre}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Prioridad de producción</label>
                                <select class="form-control" v-model="ventaDatos.Prioritario" required>
                                    <option value="0">Sin prioridad</option>
                                    <option value="1">Dar prioridad</option>
                                </select>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class=" form-control-label">Fecha de venta</label>
                                <input type="date" class="form-control" v-model="ventaDatos.Fecha_venta" required>
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Fecha estimada para finalizar fabricación</label>
                                <input type="date" class="form-control" v-model="ventaDatos.Fecha_estimada_entrega" required>
                            </div>

                            <div class="form-group">
                                <label class=" form-control-label">Observaciones sobre la venta</label>
                                <textarea class="form-control" rows="5" v-model="ventaDatos.Observaciones_venta"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Responsable de logística</label>
                                <select class="form-control" v-model="ventaDatos.Responsable_id_logistica" required>
                                    <option value="0">Seleccionar persona</option>
                                    <option v-for="persona in listaUsuarios" v-bind:value="persona.Id">{{persona.Nombre}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Costo por logística</label>
                                <input type="int" class="form-control" v-model="ventaDatos.Valor_logistica" required>
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Información sobre logística</label>
                                <textarea class="form-control" rows="5" v-model="ventaDatos.Info_logistica"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Responsable de instalación/colocaciones</label>
                                <select class="form-control" v-model="ventaDatos.Responsable_id_instalacion" required>
                                    <option value="0">Seleccionar persona</option>
                                    <option v-for="persona in listaUsuarios" v-bind:value="persona.Id">{{persona.Nombre}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Costo por instalación</label>
                                <input type="int" class="form-control" v-model="ventaDatos.Valor_instalacion" required>
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Información sobre Instalaciones</label>
                                <textarea class="form-control" rows="5" v-model="ventaDatos.Info_instalaciones"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Responsable de cobranza</label>
                                <select class="form-control" v-model="ventaDatos.Responsable_id_cobranza" required>
                                    <option value="0">Seleccionar persona</option>
                                    <option v-for="persona in listaUsuarios" v-bind:value="persona.Id">{{persona.Nombre}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Información sobre cobranza</label>
                                <textarea class="form-control" rows="5" v-model="ventaDatos.Info_cobranza"></textarea>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">{{texto_boton}}</button>
                        <a v-if="ventaDatos.Id" v-bind:href="'compras/datos/?Id='+compraDatos.Id" class="btn btn-primary">
                            Administrar Venta
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal ordens -->
    <!-- END MAIN CONTENT-->
    <!-- END PAGE CONTAINER-->
    <?php
    // CABECERA
    include "footer.php";
    ?>
    </body>

    </html>
    <!-- end document-->