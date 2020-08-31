<?php
// CABECERA
include "head.php";
// TOP
include "top.php";
include "menusidebar.php";
?>
        <!-- PAGE CONTAINER-->
        <div class="page-container" id="ventas">
            <!-- HEADER DESKTOP-->
            <?PHP include "header_desktop.php"; ?>
            <!-- HEADER DESKTOP-->

            <!-- MAIN CONTENT-->
            <div class="main-content">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="row"> 
                            <div class="col-lg-12">
                                <!-- SECCION PRODUCCIÒN -->
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <strong>Productos en stock de reserva</strong>
                                                    </div>
                                                    <div class="card-body">
                                                        <a href="#modalProductos" data-toggle="modal" class="btn btn-success btn-flat btn-addon" v-on:click="limpiarFormularioProductos()">
                                                            <i class="ti-plus"></i> Añadir producto
                                                        </a>
                                                    </div>
                                                    <div class="card-body" >
                                                        <div class="bootstrap-data-table-panel col-lg-12">
                                                            <div class="table-responsive">
                                                                <div class="table-responsive">
                                                                    <table id="table2excel" class="table table-striped">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Producto</th>
                                                                                <td></td>
                                                                                <th>Stock</th>
                                                                                <th>Proceso materiales</th>
                                                                                <th>Soldadura</th>
                                                                                <th>Pintura</th>
                                                                                <th>Rotulación</th>
                                                                                <th>Empaque</th>
                                                                                <th></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr v-for="productoFabricado in listaProductosVendidos">
                                                                                <td>
                                                                                    <h4> {{productoFabricado.Nombre_producto}}</h4>
                                                                                </td>
                                                                                <td>
                                                                                    <span v-if="productoFabricado.Tipo_produccion == 1" class="text-success"><i class="fa fa-circle"></i></span>
                                                                                    <span v-if="productoFabricado.Tipo_produccion == 2" class="text-warning"><i class="fa fa-circle"></i></span>
                                                                                    <span v-if="productoFabricado.Tipo_produccion == 3" class="text-info"><i class="fa fa-circle"></i></span>
                                                                                </td>
                                                                                <td>
                                                                                    <a  v-if="productoFabricado.Estado == 1" href="#modalPasoapaso" data-toggle="modal" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(productoFabricado.Id, 2)">
                                                                                        <i class="ti-plus"></i> Stock OK >>
                                                                                    </a>
                                                                                    <span v-if="productoFabricado.Estado > 1"> {{formatoFecha(productoFabricado.S_1_Fecha_finalizado)}}</span>
                                                                                    <span v-if="productoFabricado.Estado < 1">En etapa previa</span>
                                                                                    <button class="item" v-on:click="infoEtapa(productoFabricado.S_1_Requerimientos, productoFabricado.S_1_Observaciones)" data-toggle="modal" data-target="#modalDatosEtapa" data-placement="top" title="Info de esta etapa">
                                                                                        <i class="fa fa-exclamation-circle"></i>
                                                                                    </button>
                                                                                </td>

                                                                                <td>
                                                                                    <a v-if="productoFabricado.Estado == 2" href="#modalPasoapaso" data-toggle="modal" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(productoFabricado.Id, 3)">
                                                                                        <i class="ti-plus"></i> Procesamiento completado >>
                                                                                    </a>
                                                                                    <span v-if="productoFabricado.Estado > 2">{{formatoFecha(productoFabricado.S_2_Fecha_finalizado)}}</span>
                                                                                    <span v-if="productoFabricado.Estado < 2">En etapa previa</span>
                                                                                    <button class="item" v-on:click="infoEtapa(productoFabricado.S_2_Requerimientos, productoFabricado.S_2_Observaciones)" data-toggle="modal" data-target="#modalDatosEtapa" data-placement="top" title="Info de esta etapa">
                                                                                        <i class="fa fa-exclamation-circle"></i>
                                                                                    </button>
                                                                                </td>

                                                                                <td>
                                                                                    <a v-if="productoFabricado.Estado == 3" href="#modalPasoapaso" data-toggle="modal" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(productoFabricado.Id, 4)">
                                                                                        <i class="ti-plus"></i> Soldadura completada >>
                                                                                    </a>
                                                                                    <span v-if="productoFabricado.Estado > 3">{{formatoFecha(productoFabricado.S_3_Fecha_finalizado)}}</span>
                                                                                    <span v-if="productoFabricado.Estado < 3">En etapa previa</span>
                                                                                    <button class="item" v-on:click="infoEtapa(productoFabricado.S_3_Requerimientos, productoFabricado.S_3_Observaciones)" data-toggle="modal" data-target="#modalDatosEtapa" data-placement="top" title="Info de esta etapa">
                                                                                        <i class="fa fa-exclamation-circle"></i>
                                                                                    </button>
                                                                                </td>

                                                                                <td>
                                                                                    <a v-if="productoFabricado.Estado == 4" href="#modalPasoapaso" data-toggle="modal" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(productoFabricado.Id, 5)">
                                                                                        <i class="ti-plus"></i> Pintura completada >>
                                                                                    </a>
                                                                                    <span v-if="productoFabricado.Estado > 4">{{formatoFecha(productoFabricado.S_4_Fecha_finalizado)}}</span>
                                                                                    <span v-if="productoFabricado.Estado < 4"> En etapa previa</span> 
                                                                                    <button class="item" v-on:click="infoEtapa(productoFabricado.S_4_Requerimientos, productoFabricado.S_4_Observaciones)" data-toggle="modal" data-target="#modalDatosEtapa" data-placement="top" title="Info de esta etapa">
                                                                                        <i class="fa fa-exclamation-circle"></i>
                                                                                    </button>
                                                                                </td>

                                                                                <td>
                                                                                    <a v-if="productoFabricado.Estado == 5" href="#modalPasoapaso" data-toggle="modal" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(productoFabricado.Id, 6)">
                                                                                        <i class="ti-plus"></i> Rotulación completada >>
                                                                                    </a>
                                                                                    <span v-if="productoFabricado.Estado > 5">{{formatoFecha(productoFabricado.S_5_Fecha_finalizado)}}</span>
                                                                                    <span v-if="productoFabricado.Estado < 5">En etapa previa</span> 
                                                                                    <button class="item" v-on:click="infoEtapa(productoFabricado.S_5_Requerimientos, productoFabricado.S_5_Observaciones)" data-toggle="modal" data-target="#modalDatosEtapa" data-placement="top" title="Info de esta etapa">
                                                                                        <i class="fa fa-exclamation-circle"></i>
                                                                                    </button>
                                                                                </td>

                                                                                <td>
                                                                                    <a v-if="productoFabricado.Estado == 6" href="#modalPasoapaso" data-toggle="modal" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(productoFabricado.Id, 7)">
                                                                                        <i class="ti-plus"></i> Producto empacado >>
                                                                                    </a>
                                                                                    <span v-if="productoFabricado.Estado < 6">En etapa previa</span>
                                                                                    <span v-if="productoFabricado.Estado > 6">{{formatoFecha(productoFabricado.S_6_Fecha_finalizado)}}</span>
                                                                                    <button class="item" v-on:click="infoEtapa(productoFabricado.S_6_Requerimientos, productoFabricado.S_6_Observaciones)" data-toggle="modal" data-target="#modalDatosEtapa" data-placement="top" title="Info de esta etapa">
                                                                                        <i class="fa fa-exclamation-circle"></i>
                                                                                    </button>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="table-data-feature"> 
                                                                                        <button class="item" v-on:click="editAsignarProductoVenta(productoFabricado)" data-toggle="modal" data-target="#modalAsignacion" data-placement="top" title="Editar">
                                                                                            <i class="zmdi zmdi-mail-send"></i>
                                                                                        </button>
                                                                                        <button class="item" v-on:click="editarFormularioProductos(productoFabricado)" data-toggle="modal" data-target="#modalProductos" data-placement="top" title="Editar">
                                                                                            <i class="zmdi zmdi-edit"></i>
                                                                                        </button>
                                                                                        <?php 
                                                                                            if($this->session->userdata('Rol_acceso') > 4) 
                                                                                            {
                                                                                                echo '
                                                                                                <button v-on:click="desactivarProductoVenta(productoFabricado.Id)" class="item" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                                                                                    <i class="zmdi zmdi-delete"></i>
                                                                                                </button>'; 
                                                                                            }
                                                                                        ?>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <p align="center">
                                                    <span class="text-success"><i class="fa fa-circle"></i> Normal</span> 
                                                    <span class="text-warning"><i class="fa fa-circle"></i> Reclamo</span>
                                                    <span class="text-info"><i class="fa fa-circle"></i> Muestra</span>
                                                </p>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal -->
            <!-- Modal AÑADIR PRODUCTOS-->
            <div class="modal fade" id="modalProductos" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
                <div class="modal-dialog  modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Añadir producto a esta venta</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>  
                        <div class="modal-body">
                                                            
                            <div class="horizontal-form">
                                <form class="form-horizontal" action="post" v-on:submit.prevent="agregarProducto()"> <!--   -->
                                    
                                    <div class="form-group">
                                        <label class="control-label">Producto a fabricar</label>
                                        <select class="form-control" v-model="productoData.Producto_id" required :disabled="productoData.Id > 0">
                                                <option value="0">Seleccionar producto</option>
                                                <option v-for="producto in listaProductos" v-bind:value="producto.Id">{{producto.Nombre_producto}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group" v-if="productoData.Id == null">
                                        <label  class=" form-control-label">Cantidad de unidades de este producto</label> 
                                        <input type="number" class="form-control" v-model="productoData.Cantidad" required>
                                    </div>
                                    <div class="form-group" v-if="productoData.Id > 0">
                                        <label  class=" form-control-label">Tipo de producción</label> 
                                        <select class="form-control" v-model="productoData.Tipo_produccion">
                                            <option value="1">Normal</option>
                                            <option value="2">Reclamo</option>
                                            <option value="3">Muestra</option>
                                        </select>
                                    </div>
                                    <hr>
                                    <h4>Requerimentos especificos para este producto en sus respecticas áreas y estaciones. </h4>
                                    <p>Estos campos no son obligatorios, añadir información en el área que sea necesario.</p><br>
                                    <div class="form-group">
                                        <label class=" form-control-label">Área de compras</label>
                                        <textarea class="form-control" rows="3" v-model="productoData.S_1_Requerimientos"></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class=" form-control-label">Proceso de materiales</label>
                                        <textarea class="form-control" rows="3" v-model="productoData.S_2_Requerimientos"></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class=" form-control-label">Soldadura</label>
                                        <textarea class="form-control" rows="3" v-model="productoData.S_3_Requerimientos"></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class=" form-control-label">Pintura</label>
                                        <textarea class="form-control" rows="3" v-model="productoData.S_4_Requerimientos"></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class=" form-control-label">Rotulación</label>
                                        <textarea class="form-control" rows="3" v-model="productoData.S_5_Requerimientos"></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class=" form-control-label">Empaque y Loteo</label>
                                        <textarea class="form-control" rows="3" v-model="productoData.S_6_Requerimientos"></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class=" form-control-label">Logística</label>
                                        <textarea class="form-control" rows="3" v-model="productoData.S_7_Requerimientos"></textarea>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <label class="control-label">Comentarios adicionales</label>
                                        <textarea class="form-control" rows="3" placeholder="" v-model="productoData.Observaciones"></textarea>
                                    </div>
                                            
                                    <div class="form-group">
                                         <button type="submit" class="btn btn-success":disabled="preloader == 1">{{texto_boton}}</button>
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
            <!-- Modal PASO A PASO PRODUCTO-->
            <div class="modal fade" id="modalPasoapaso" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
                <div class="modal-dialog  modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Avanzar producto a siguiente etapa</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>  
                        <div class="modal-body">
                            <div class="horizontal-form">
                                <form class="form-horizontal" action="post" v-on:submit.prevent="cambiarEstadoProducto()"> <!--   -->
                                    <!-- <pre>{{productoPasoData}}</pre> -->
                                    <div class="form-group">
                                        <label  class=" form-control-label">Fecha del movimiento</label> 
                                        <input type="date" class="form-control" v-model="productoPasoData.Fecha" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Comentarios de esta etapa</label>
                                        <textarea class="form-control" rows="5" placeholder="" v-model="productoPasoData.Comentarios"></textarea>
                                    </div>
        
                                    <div class="form-group">
                                         <button type="submit" class="btn btn-success">Avanzar a siguiente etapa</button>
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
            <!-- Modal INFORMACIÓN SOBRE LA ETAPA-->
            <div class="modal fade" id="modalDatosEtapa" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
                <div class="modal-dialog  modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Información etapa</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>  
                        <div class="modal-body">
                            <h4>Requerimentos</h4>                                                               
                            <p>{{infoModal.Requerimentos}}</p>
                            <hr>
                            <h4>Observaciones</h4>                                                               
                            <p>{{infoModal.Observaciones}}</p> 
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal -->
            <!-- Modal AÑADIR PRODUCTOS-->
            <div class="modal fade" id="modalAsignacion" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
                <div class="modal-dialog  modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Asignar producto a una venta</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>  
                        <div class="modal-body">                    
                            <div class="horizontal-form">
                                <form class="form-horizontal" action="post" v-on:submit.prevent="reasignar_producto()"> <!--   -->
                                    <div class="form-group">
                                        <label class="control-label">Seleccionar venta </label>
                                        <select class="form-control" v-model="productoAsignado.Venta_id" required>
                                                <option v-for="venta in listaVentas" v-bind:value="venta.Id">{{venta.Identificador_venta}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                         <button type="submit" class="btn btn-success">Avanzar a siguiente etapa</button>
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
