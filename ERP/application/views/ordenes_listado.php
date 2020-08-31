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
                                    Ordenes de trabajo 
                                </h3>
                                    <div class="table-data__tool">
                                    <div class="table-data__tool-left">
                                        <div class="rs-select2--light">
                                            <select class="form-control form-control" v-model="filtro_usuario" v-on:change="getListadoOrdenes(filtro_usuario, filtro_producto,filtro_cliente,filtro_estado)">
                                                <option selected="selected" v-bind:value="0">Todos los responsables</option>
                                                <option v-for="usuario in listaUsuarios" v-bind:value="usuario.Id">{{usuario.Nombre}}</option>
                                            </select>
                                            <div class="dropDownSelect2"></div>
                                        </div>
                                        <div class="rs-select2--light">
                                            <select class="form-control form-control" v-model="filtro_producto" v-on:change="getListadoOrdenes(filtro_usuario, filtro_producto,filtro_cliente,filtro_estado)">
                                                <option selected="selected" v-bind:value="0">Todos los productos</option>
                                                <option v-for="producto in listaProductos" v-bind:value="producto.Id">{{producto.Nombre_producto}}</option>
                                            </select>
                                            <div class="dropDownSelect2"></div>
                                        </div>
                                        <div class="rs-select2--light">
                                            <select class="form-control form-control" v-model="filtro_cliente" v-on:change="getListadoOrdenes(filtro_usuario, filtro_producto,filtro_cliente,filtro_estado)">
                                                <option selected="selected" v-bind:value="0">Todos los clientes</option>
                                                <option v-for="cliente in listaClientes" v-bind:value="cliente.Id">{{cliente.Nombre_cliente}}</option>
                                            </select>
                                            <div class="dropDownSelect2"></div>
                                        </div>
                                        <div class="rs-select2--light">
                                            <select class="form-control form-control" v-model="filtro_estado" v-on:change="getListadoOrdenes(filtro_usuario, filtro_producto,filtro_cliente,filtro_estado)">
                                                <option selected="selected" v-bind:value="0">Ordenes en fabricación</option>
                                                <option selected="selected" v-bind:value="1">Ordenes finalizadas</option>
                                                <option selected="selected" v-bind:value="2">Ordenes despachadas</option>
                                            </select>
                                            <div class="dropDownSelect2"></div>
                                        </div>
                                    </div>
                                    <div class="table-data__tool-right">
                                        <button class="au-btn au-btn-icon au-btn--green au-btn--small" data-toggle="modal" data-target="#ordenmodal"  v-on:click="limpiarFormularioOrden()">
                                            <i class="zmdi zmdi-plus"></i>Nueva orden de trabajo
                                        </button>
                                    </div>
                                </div>
                                <div class="table-responsive table-responsive-data2">
                                    <table class="table table-data2">
                                        <thead>
                                            <tr>
                                                <th>Producto/Pieza</th>
                                                <th>Responsable</th>
                                                <th>Cliente</th>
                                                <th>N° pieza</th>
                                                <th>Periodo</th>
                                                <th>Inicio</th>
                                                <th v-show="filtro_estado < 1">Finalización estimada</th>
                                                <th v-show="filtro_estado < 1">Días restantes estimados</th>
                                                <th v-show="filtro_estado > 0">Finalizado</th>
                                                <th v-show="filtro_estado > 0">Tiempo de fabricación</th>
                                                <th v-show="filtro_estado > 1">Despachado a cliente</th>
                                                <th>Observaciones</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="tr-shadow" v-for="orden in listaOrdenes">
                                                <td>
                                                    <a v-bind:href="'ordentrabajo/datos/?Id='+orden.Id" class="btn btn-dark btn-outline m-b-10 m-l-5">
                                                        {{orden.Nombre_producto}}
                                                    </a>
                                                </td>
                                                <td>{{orden.Nombre_usuario}}</td>
                                                <td>{{orden.Nombre_cliente}}</td>
                                                <td>{{orden.Numero_pieza}}</td>
                                                <td>{{orden.Nombre_periodo}}</td>
                                                <td>{{orden.Fecha_inicio | Fecha}}</td>
                                                
                                                <td v-show="filtro_estado < 1">{{orden.Fecha_estimada_finalizacion | Fecha}}</td>
                                                <td v-show="filtro_estado < 1">{{diferenciasEntre_fechas(null, orden.Fecha_estimada_finalizacion)}}</td>
                                                <td v-show="filtro_estado > 0">{{orden.Fecha_finalizado | Fecha}}</td>
                                                <td v-show="filtro_estado > 0">{{diferenciasEntre_fechas(orden.Fecha_inicio, orden.Fecha_finalizado)}}</td>
                                                <td v-show="filtro_estado > 1">{{orden.Fecha_despacho_cliente | Fecha}}</td>
                                                <td>{{orden.Observaciones}}</td>    
                                                <td>
                                                    <div class="table-data-feature">
                                                        
                                                        <a class="item" v-bind:href="'ordentrabajo/datos/?Id='+orden.Id" title="Ver todos los datos">
                                                            <i class="zmdi zmdi-mail-send"></i>
                                                        </a>
                                                        <button class="item" v-on:click="editarFormularioOrden(orden)" data-toggle="modal" data-target="#ordenmodal" data-placement="top" title="Edición rápida">
                                                            <i class="zmdi zmdi-edit"></i>
                                                        </button>
                                                        
                                                        <button v-on:click="desactivarOrden(orden.Id)" class="item" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                                            <i class="zmdi zmdi-delete"></i>
                                                        </button>
                                                        
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
			<div class="modal fade" id="ordenmodal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="scrollmodalLabel">Formulario de orden de trabajo</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
                        </div>
                        <form class="form-horizontal" action="post" v-on:submit.prevent="crearOrden()">
                            <div class="modal-body">
                                <div class="horizontal-form">
                                    <div class="form-group">
                                        <label class="control-label">Producto a fabricar</label>
                                        <select class="form-control" v-model="ordenDatos.Producto_id" required>
                                            <option value="0">Seleccionar Producto</option>
                                            <option v-for="producto in listaProductos" v-bind:value="producto.Id">{{producto.Nombre_producto}}</option>
                                        </select>
                                    </div>
                                    <!-- <div class="form-group">
                                        <label class="control-label">Es sub producto de</label>
                                        <select class="form-control" v-model="ordenDatos.Subproducto_de_id" required>
                                            <option value="0">Es pieza/producto final</option>
                                            <option v-for="producto in listaProductos" v-bind:value="producto.Id">{{producto.Nombre_producto}}</option>
                                        </select>
                                    </div> -->
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
                                       <label  class=" form-control-label">Seleccionar periodo al que pertenece esta orden</label> 
                                       <select class="form-control" v-model="ordenDatos.Periodo_id" required>
                                            <option v-for="periodo in listaPeriodos" v-bind:value="periodo.Id">{{periodo.Nombre_periodo}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                       <label  class=" form-control-label">Identificador de pieza/pruducto</label> 
                                       <input type="text" class="form-control" v-model="ordenDatos.Numero_pieza" required>
                                    </div>
                                    <div class="form-group">
                                       <label  class=" form-control-label">Fecha inicio de fabricación</label> 
                                       <input type="date" class="form-control" v-model="ordenDatos.Fecha_inicio" required>
                                    </div>
                                    <div class="form-group">
                                       <label  class=" form-control-label">Fecha estimada para finalizar fabricación</label> 
                                       <input type="date" class="form-control" v-model="ordenDatos.Fecha_estimada_finalizacion" required>
                                    </div>
                                    <div class="form-group">
                                        <label class=" form-control-label">Observaciones</label>
                                        <textarea class="form-control" rows="5" v-model="ordenDatos.Observaciones"></textarea>
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
