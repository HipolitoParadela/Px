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
                                <h3 class="title-5 m-b-35">Listado de proveedores</h3>
                               
                                <div class="table-data__tool">
                                    <div class="table-data__tool-left">
                                        <div class="rs-select2--light ">
                                            <input type="text" class="form-control-sm form-control" placeholder="Buscar proveedor" v-model="buscar">
                                        </div>
                                    </div>
                                    <div class="table-data__tool-right">
                                        <button class="au-btn au-btn-icon au-btn--green au-btn--small" data-toggle="modal" data-target="#proveedormodal"  v-on:click="limpiarFormularioProveedor()">
                                            <i class="zmdi zmdi-plus"></i>Nuevo proveedor</button>
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
                                            <tr class="tr-shadow" v-for="proveedor in buscarProveedor">
                                                <td>
                                                    <div class="round-img">
                                                        <a href="#modalproveedorsFoto" data-toggle="modal" v-on:click="editarFormularioProveedorFoto(proveedor)">
                                                            <img v-if="proveedor.Imagen != null"  v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+proveedor.Imagen" width="60px">
                                                            <img v-else src="http://pxresto.com/pxresto/uploads/imagenes/addimagen.jpg" width="50px" alt="">
                                                        </a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a v-bind:href="'proveedores/datos/?Id='+proveedor.Id" class="btn btn-dark btn-outline m-b-10 m-l-5">
                                                        {{proveedor.Nombre_proveedor}}
                                                    </a>
                                                </td>
                                                <td>{{proveedor.CUIT_CUIL}}</td>
                                                <td>{{proveedor.Telefono}}</td>
                                                <td><span class="block-email">{{proveedor.Email}}</span></td>
                                                <td>{{proveedor.Web}}</td>
                                                <td>{{proveedor.Nombre_persona_contacto}}</td>
                                                <td>
                                                    <div class="table-data-feature">
                                                        
                                                        <a class="item" v-bind:href="'proveedores/datos/?Id='+proveedor.Id" title="Ver todos los datos">
                                                            <i class="zmdi zmdi-mail-send"></i>
                                                        </a>
                                                        <button class="item"  v-on:click="editarFormularioProveedor(proveedor)" data-toggle="modal" data-target="#proveedormodal" data-placement="top" title="Edición rápida">
                                                            <i class="zmdi zmdi-edit"></i>
                                                        </button>
                                                        
                                                        <button v-on:click="desactivarProveedor(proveedor.Id)" class="item" data-toggle="tooltip" data-placement="top" title="Eliminar">
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
            <!-- modal proveedors -->
			<div class="modal fade" id="proveedormodal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="scrollmodalLabel">Formulario de proveedor</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
                        </div>
                        <form class="form-horizontal" action="post" v-on:submit.prevent="crearProveedor()">
                            <div class="modal-body">
                                <div class="horizontal-form">
                                    <div class="form-group">
                                       <label  class=" form-control-label">Nombre</label> 
                                       <input type="text" class="form-control" placeholder="" v-model="proveedorDatos.Nombre_proveedor" required>
                                    </div>
                                    <div class="form-group">
                                        <label class=" form-control-label">Breve descripción del producto/servicio que brinda</label>
                                        <textarea class="form-control" rows="5" v-model="proveedorDatos.Producto_servicio"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label  class=" form-control-label">CUIT/CUIL</label> 
                                        <input type="text" class="form-control" placeholder="" v-model="proveedorDatos.CUIT_CUIL" required>
                                    </div>
                                    <div class="form-group">
                                        <label class=" form-control-label">Telefono</label>
                                        <input type="text" class="form-control" placeholder="" v-model="proveedorDatos.Telefono" required>
                                    </div>
                                    <div class="form-group">
                                        <label  class=" form-control-label">Dirección</label>
                                        <input type="text" class="form-control" placeholder="" v-model="proveedorDatos.Direccion" required>
                                    </div>                                    
                                    <div class="form-group">
                                        <label class="control-label">Localidad</label>
                                         <input type="text" class="form-control" placeholder="" v-model="proveedorDatos.Localidad" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Provincia</label>
                                         <input type="text" class="form-control" placeholder="" v-model="proveedorDatos.Provincia" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Pais</label>
                                         <input type="text" class="form-control" placeholder="" v-model="proveedorDatos.Pais" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Email</label>
                                         <input type="email" class="form-control" placeholder="" v-model="proveedorDatos.Email">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Web</label>
                                         <input type="text" class="form-control" placeholder="" v-model="proveedorDatos.Web">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">URL de su fanpage</label>
                                        <input type="text" class="form-control" placeholder="" v-model="proveedorDatos.URL_facebook">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Nombre de persona de contacto en la empresa</label>
                                        <input type="text" class="form-control" placeholder="" v-model="proveedorDatos.Nombre_persona_contacto">
                                    </div>
                                    <div class="form-group">
                                        <label class=" form-control-label">Información de la persona de contacto</label>
                                        <textarea class="form-control" rows="5" v-model="proveedorDatos.Datos_persona_contacto"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class=" form-control-label">Más información sobre el proveedor</label>
                                        <textarea class="form-control" rows="5" v-model="proveedorDatos.Mas_datos_proveedor"></textarea>
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
            <!-- end modal proveedors -->
            <!-- Modal proveedors Fotos-->
            <div class="modal fade" id="modalproveedorsFoto" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="true">
                <div class="modal-dialog  modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalItemsFoto">Fotografía de {{proveedorFoto.Nombre_proveedor}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p align="center">
                                <img v-if="proveedorFoto.Imagen != null" class="avatar_grande" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+proveedorFoto.Imagen" alt="">
                                <img v-else class="avatar_grande" src="http://pxresto.com/pxresto/uploads/imagenes/addimagen.jpg" alt="">
                            </p>
                            <hr>
                            <div class="horizontal-form">
                                <!-- <form class="form-horizontal" action="post" enctype="multipart/form-data" v-on:submit.prevent="crearproveedors()">  -->
                                <form class="form-horizontal" action="post" enctype="multipart/form-data" v-on:submit.prevent="uploadProveedor(proveedorFoto.Id)">
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
