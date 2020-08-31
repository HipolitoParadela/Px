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
                                <h3 class="title-5 m-b-35">Listado de clientes</h3>
                               
                                <div class="table-data__tool">
                                    <div class="table-data__tool-left">
                                        <div class="rs-select2--light ">
                                            <input type="text" class="form-control-sm form-control" placeholder="Buscar cliente" v-model="buscar">
                                        </div>
                                    </div>
                                    <div class="table-data__tool-right">
                                        <button class="au-btn au-btn-icon au-btn--green au-btn--small" data-toggle="modal" data-target="#clientemodal"  v-on:click="limpiarFormularioClientes()">
                                            <i class="zmdi zmdi-plus"></i>Nuevo cliente</button>
                                    </div>
                                </div>
                                <div class="table-responsive table-responsive-data2">
                                    <table class="table table-data2">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Nombre</th>
                                                <th>CUIT/CUIL</th>
                                                <th>Teléfono</th>
                                                <th>Email</th>
                                                <th>Web</th>
                                                <th>Persona Contacto</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="tr-shadow" v-for="cliente in buscarCliente">
                                                <td>
                                                    <div class="round-img">
                                                        <a href="#modalclientesFoto" data-toggle="modal" v-on:click="editarFormularioClienteFoto(cliente)">
                                                            <img v-if="cliente.Imagen != null"  v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+cliente.Imagen" width="60px">
                                                            <img v-else src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" width="50px" alt="">
                                                        </a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a v-bind:href="'clientes/datos/?Id='+cliente.Id" class="btn btn-dark btn-outline m-b-10 m-l-5">
                                                        {{cliente.Nombre_cliente}}
                                                    </a>
                                                </td>
                                                <td>{{cliente.CUIT_CUIL}}</td>
                                                <td>{{cliente.Telefono_fijo}}  {{cliente.Telefono}}</td>
                                                <td><span class="block-email">{{cliente.Email}}</span></td>
                                                <td><a v-bind:href="'http://'+cliente.Web" target="_blank">{{cliente.Web}}</a></td>
                                                <td>{{cliente.Nombre_persona_contacto}}</td>
                                                <td>
                                                    <div class="table-data-feature">
                                                        
                                                        <a class="item" v-bind:href="'clientes/datos/?Id='+cliente.Id" title="Ver todos los datos">
                                                            <i class="zmdi zmdi-mail-send"></i>
                                                        </a>
                                                        <button class="item"  v-on:click="editarFormularioCliente(cliente)" data-toggle="modal" data-target="#clientemodal" data-placement="top" title="Edición rápida">
                                                            <i class="zmdi zmdi-edit"></i>
                                                        </button>
                                                        <?php 
                                                            if($this->session->userdata('Rol_acceso') > 4) 
                                                            {
                                                                echo '
                                                                <button v-on:click="desactivarCliente(cliente.Id)" class="item" data-toggle="tooltip" data-placement="top" title="Eliminar">
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
            <!-- modal clientes -->
			<div class="modal fade" id="clientemodal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="scrollmodalLabel">Formulario de cliente</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
                        </div>
                        <form class="form-horizontal" action="post" v-on:submit.prevent="crearCliente()">
                            <div class="modal-body">
                                <div class="horizontal-form">
                                    <div class="form-group">
                                       <label  class=" form-control-label">Nombre</label> 
                                       <input type="text" class="form-control" placeholder="" v-model="clienteDatos.Nombre_cliente" required>
                                    </div>
                                    <div class="form-group">
                                        <label  class="form-control-label">CUIT/CUIL</label> 
                                        <input type="text" class="form-control" placeholder="" v-model="clienteDatos.CUIT_CUIL" required>
                                    </div>
                                    <div class="form-group">
                                        <label class=" form-control-label">Productos/Servicios que le ofrecemos</label>
                                        <textarea class="form-control" rows="5" v-model="clienteDatos.Producto_servicio"></textarea>
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
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">{{texto_boton}}</button>
                            </div>
                        </form>
					</div>
				</div>
			</div>
            <!-- end modal clientes -->
            <!-- Modal clientes Fotos-->
            <div class="modal fade" id="modalclientesFoto" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="true">
                <div class="modal-dialog  modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalItemsFoto">Fotografía de {{clienteFoto.Nombre_cliente}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p align="center">
                                <img v-if="clienteFoto.Imagen != null" class="avatar_grande" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+clienteFoto.Imagen" alt="">
                                <img v-else class="avatar_grande" src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" alt="">
                            </p>
                            <hr>
                            <div class="horizontal-form">
                                <!-- <form class="form-horizontal" action="post" enctype="multipart/form-data" v-on:submit.prevent="crearclientes()">  -->
                                <form class="form-horizontal" action="post" enctype="multipart/form-data" v-on:submit.prevent="uploadCliente(clienteFoto.Id)">
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
            <!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->
<?php
    // CABECERA
    include "footer.php";
?>
</body>
</html>
<!-- end document-->
