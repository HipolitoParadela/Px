<?php
// CABECERA
include "head.php";
// TOP
include "top.php";
include "menusidebar.php";
?>
        <!-- PAGE CONTAINER-->
        <div class="page-container" id="clientes">
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
                                            <img v-if="clienteDatos.Imagen != null" width="420px" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+clienteDatos.Imagen" alt="">
                                            <img v-else src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" alt="">
                                        </div>
                                        <h5 class="text-sm-center mt-2 mb-1">{{clienteDatos.Nombre_cliente}}</h5>
                                        <div class="location text-sm-center">
                                            <i class="fa fa-map-marker"></i> {{clienteDatos.Direccion}}, {{clienteDatos.Localidad}}, {{clienteDatos.Provincia}}, {{clienteDatos.Pais}}</div>
                                        </div>
                                    </div>
                                    <div>
                                        <a target="_blank" v-bind:href="'https://api.whatsapp.com/send?phone='+clienteDatos.Telefono" class="btn btn-success btn-block" >
                                           <i class="fab fa-whatsapp"></i> Enviar whatsapp
                                        </a>
                                    <hr>
                                        <a target="_blank" v-bind:href="'mailto:'+clienteDatos.Email" class="btn btn-info btn-block" >
                                           <i class="fa fa-envelope"></i> Enviar email
                                        </a>
                                    </div>
                                    <hr>
                                    <span v-show="clienteDatos.Web != null">
                                        <a target="_blank" v-bind:href="'http://'+clienteDatos.Web" class="btn btn-secondary btn-block" >
                                            <i class="fa fa-share-square"></i> {{clienteDatos.Web}}
                                        </a>
                                    </span>
                                </div>
                            
                            <!-- SECCION FICHA cliente -->
                            <div class="col-lg-10" >

                                <ul class="nav nav-tabs">
								    <li class="nav-item">
										<a class="nav-link" v-bind:class="{ active: mostrar == 1 }" href="#" v-on:click="mostrar = 1">Ficha</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" v-bind:class="{ active: mostrar == 2 }" href="#" v-on:click="mostrar = 2">Seguimiento</a>
									</li>
                                    <li class="nav-item">
										<a class="nav-link" v-bind:class="{ active: mostrar == 3 }" href="#" v-on:click="mostrar = 3">Ventas</a>
									</li>
							    </ul>
                                                                     
                                <!-- SECCION DATOS EDITABLES DEL cliente -->
                                <div class="row" v-show="mostrar == '1'">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <strong>Ficha: {{clienteDatos.Nombre_cliente}}</strong>
                                            </div>
                                            <div class="card-body">
                                                <div class="horizontal-form">
                                                    <form class="form-horizontal" action="post" v-on:submit.prevent="crearCliente()"> 
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label  class=" form-control-label">Nombre</label> 
                                                                    <input type="text" class="form-control" placeholder="" v-model="clienteDatos.Nombre_cliente" required>
                                                                </div>
                                                                <div class="form-group">
                                                                        <label class=" form-control-label">Producto/Servicio que le ofrecemos</label>
                                                                        <textarea class="form-control" rows="3" v-model="clienteDatos.Producto_servicio"></textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label  class=" form-control-label">CUIT/CUIL</label> 
                                                                    <input type="text" class="form-control" placeholder="" v-model="clienteDatos.CUIT_CUIL" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class=" form-control-label">Teléfono whatsapp - numero entero de corrido</label>
                                                                    <input type="text" class="form-control" placeholder="" v-model="clienteDatos.Telefono">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class=" form-control-label">Teléfono Fijo</label>
                                                                    <input type="text" class="form-control" placeholder="" v-model="clienteDatos.Telefono_fijo">
                                                                </div>
                                                                <div class="form-group">
                                                                        <label  class=" form-control-label">Dirección</label>
                                                                        <input type="text" class="form-control" placeholder="" v-model="clienteDatos.Direccion" required>
                                                                </div>                                    
                                                                <div class="form-group">
                                                                        <label class="control-label">Localidad</label>
                                                                        <input type="text" class="form-control" placeholder="" v-model="clienteDatos.Localidad" required>
                                                                </div>
                                                                <div class="form-group">
                                                                        <label class="control-label">Provincia</label>
                                                                        <input type="text" class="form-control" placeholder="" v-model="clienteDatos.Provincia" required>
                                                                </div>
                                                                <div class="form-group">
                                                                        <label class="control-label">Pais</label>
                                                                        <input type="text" class="form-control" placeholder="" v-model="clienteDatos.Pais" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                        <label class="control-label">Email</label>
                                                                        <input type="email" class="form-control" placeholder="" v-model="clienteDatos.Email">
                                                                </div>
                                                                <div class="form-group">
                                                                        <label class="control-label">Web - Sin "http://"</label>
                                                                        <input type="text" class="form-control" placeholder="" v-model="clienteDatos.Web">
                                                                </div>
                                                                <div class="form-group">
                                                                        <label class="control-label">Nombre de persona de contacto en la empresa</label>
                                                                        <input type="text" class="form-control" placeholder="" v-model="clienteDatos.Nombre_persona_contacto">
                                                                </div>
                                                                <div class="form-group">
                                                                        <label class=" form-control-label">Información de la persona de contacto</label>
                                                                        <textarea class="form-control" rows="5" v-model="clienteDatos.Datos_persona_contacto"></textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                        <label class=" form-control-label">Más información sobre el cliente</label>
                                                                        <textarea class="form-control" rows="5" v-model="clienteDatos.Mas_datos_cliente"></textarea>
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
                                            </div>  
                                            <div class="card-body"> 
                                                <div class="bootstrap-data-table-panel col-lg-12">
                                                    <div class="table-responsive">
                                                        <table id="table2excel" class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>Cliente</th>
                                                                    <th>Fecha</th>
                                                                    <th>Descripcion</th>
                                                                    <th>Usuario</th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr v-for="seguimiento in listaSeguimiento">
                                                                    <td>{{seguimiento.Nombre_cliente}}</td>
                                                                    <td>{{seguimiento.Fecha}}</td>
                                                                    <td>{{seguimiento.Descripcion}}</td>
                                                                    <td>{{seguimiento.Nombre}}</td>
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
                                        </DIV>
                                    </DIV>
                                </div>
                                <!-- SECCION VENTAS REALIZADAS A ESTE CLIENTE -->
                                <div class="row" v-show = "mostrar == '3'">              
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <strong>Ventas realizadas a {{clienteDatos.Nombre_cliente}}</strong>
                                            </div>
                                            <div class="card-body">
                                                <div class="rs-select2--light">
                                                    <select class="form-control form-control" v-model="filtro_estado" v-on:change="getListadoVentas(0, 0 , clienteDatos.Id, filtro_estado)">
                                                        <option selected="selected" v-bind:value="1">En fabricación</option>
                                                        <option selected="selected" v-bind:value="10">Finalizadas</option>
                                                    </select>
                                                    <div class="dropDownSelect2"></div>
                                                </div>
                                            </div>
                                            <div class="card-body"> 
                                                <div class="bootstrap-data-table-panel col-lg-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-data2">
                                                            <thead>
                                                                <tr>
                                                                    <th>Identificador</th>
                                                                    <th>Estado Actual</th>
                                                                    <th>Empresa</th>
                                                                    <th>Vendedor</th>
                                                                    <th>Cliente</th>
                                                                    <th>Iniciado</th>
                                                                    <th v-show="filtro_estado == 10">Finalizado</th>
                                                                    <th v-show="filtro_estado == 10">Tiempo de fabricación</th>

                                                                    <th v-show="filtro_estado < 10">Finalización estimada</th>
                                                                    <th v-show="filtro_estado < 10">Días restantes estimados</th>

                                                                    

                                                                    <th>Observaciones</th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr class="tr-shadow" v-for="venta in listaVentas">
                                                                    <td>
                                                                        <h4>{{venta.Identificador_venta}}</h4>
                                                                    </td>
                                                                    <!-- Seteando Estados -->
                                                                        <td v-if="venta.Estado == 1"><b>Compras</b></td>
                                                                        <td v-if="venta.Estado == 2"><b>Proceso de materiales</b></td>
                                                                        <td v-if="venta.Estado == 3"><b>Soldadura</b></td>
                                                                        <td v-if="venta.Estado == 4"><b>Pintura</b></td>
                                                                        <td v-if="venta.Estado == 5"><b>Rotulación</b></td>
                                                                        <td v-if="venta.Estado == 6"><b>Empaque</b></td>
                                                                        <td v-if="venta.Estado == 7"><b>Logística</b></td>
                                                                        <td v-if="venta.Estado == 8"><b>Instalación</b></td>
                                                                        <td v-if="venta.Estado == 9"><b>Cobranza</b></td>
                                                                        <td v-if="venta.Estado == 10"><b>Vemta finalizada</b></td>
                                                                    <td>
                                                                        {{venta.Nombre_empresa}}
                                                                    </td>
                                                                    
                                                                    <td>{{venta.Nombre_vendedor}}</td>
                                                                    <td>
                                                                        {{venta.Nombre_cliente}}
                                                                    </td>
                                                                    <td>{{formatoFecha(venta.Fecha_venta)}}</td>

                                                                    <td v-show="filtro_estado == 10">{{formatoFecha(venta.Fecha_entrega)}}</td>
                                                                    <td v-show="filtro_estado == 10">{{diferenciasEntre_fechas(venta.Fecha_venta, venta.Fecha_entrega)}}</td>

                                                                    <td v-show="filtro_estado < 10">{{formatoFecha(venta.Fecha_estimada_entrega)}}</td>
                                                                    <td v-show="filtro_estado < 10">{{diferenciasEntre_fechas(null, venta.Fecha_estimada_entrega)}}</td>
                                                                    

                                                                    
                                                                    <td>{{venta.Observaciones}}</td>    
                                                                    <td>
                                                                        <div class="table-data-feature">
                                                                            
                                                                            <a class="item" v-bind:href="'../ventas/datos/?Id='+venta.Id" title="Ver todos los datos">
                                                                                <i class="zmdi zmdi-mail-send"></i>
                                                                            </a>
                                                                            
                                                                        </div>
                                                                    </td>
                                                                <tr class="spacer"></tr>
                                                                </tr>
                                                                
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </DIV>
                                    </DIV>
                                </div><!-- -->
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
                            <div class="horizontal-form">
                                <form class="form-horizontal" action="post" v-on:submit.prevent="crearSeguimiento()">
                                        <div class="form-group">
                                            <label  class=" form-control-label">Fecha del reporte</label>
                                            <input type="date" class="form-control" placeholder="" v-model="seguimientoData.Fecha">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Datos del seguimiento</label>
                                            <textarea class="form-control" rows="5" placeholder="" v-model="seguimientoData.Descripcion"></textarea>
                                        </div>
                                        <div class="form-group">
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
    <!-- END MAIN CONTENT-->
    <!-- END PAGE CONTAINER-->
<?php
// CABECERA
include "footer.php";
?>
</body>
</html>
<!-- end document-->
