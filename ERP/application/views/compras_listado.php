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
                                <h3 class="title-5 m-b-35">Listado de compras</h3>
                               
                                <div class="table-data__tool">
                                    <div class="table-data__tool-left">
                                        <!-- <div class="rs-select2--light ">
                                            <input type="text" class="form-control-sm form-control" placeholder="Buscar compra" v-model="buscar">
                                        </div> -->
                                    </div>
                                    <div class="table-data__tool-right">
                                        <button class="au-btn au-btn-icon au-btn--green au-btn--small" data-toggle="modal" data-target="#compramodal"  v-on:click="limpiarFormularioCompras()">
                                            <i class="zmdi zmdi-plus"></i>Nueva compra</button>
                                    </div>
                                </div>
                                <div class="table-responsive table-responsive-data2">
                                    <table class="table table-data2">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Factura</th>
                                                <th>Proveedor</th>
                                                <th>Valor</th>
                                                <th>Fecha</th>
                                                <th>Periodo</th>
                                                <th>Descripción</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="tr-shadow" v-for="compra in listaCompras">
                                                <td>
                                                    <div class="round-img">
                                                        <a href="#modalcomprasFoto" data-toggle="modal" v-on:click="editarFormulariocompraFoto(compra)">
                                                            <img v-if="compra.Imagen != null"  v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+compra.Imagen" width="60px">
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
                                                
                                                <td><span class="block-email">${{compra.Valor | Moneda}}</span></td>
                                                <td>{{formatoFecha(compra.Fecha_compra)}}</td>
                                                <td>{{compra.Nombre_periodo}}</td>
                                                <td>{{compra.Descripcion}}</td>
                                                <td>
                                                    <div class="table-data-feature">
                                                        <a class="item" v-bind:href="'compras/datos/?Id='+compra.Id" title="Ver todos los datos">
                                                            <i class="zmdi zmdi-mail-send"></i>
                                                        </a>
                                                        <button class="item"  v-on:click="editarFormularioCompra(compra)" data-toggle="modal" data-target="#compramodal" data-placement="top" title="Edición rápida">
                                                            <i class="zmdi zmdi-edit"></i>
                                                        </button>
                                                        <button v-on:click="eliminar(compra.Id, 'tbl_compras')" class="item" data-toggle="tooltip" data-placement="top" title="Eliminar">
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
                        <form class="form-horizontal" action="post" v-on:submit.prevent="crearCompra()">
                            <div class="modal-body">
                                <div class="horizontal-form">
                                    <div class="form-group">
                                        <label  class=" form-control-label">Número factura o Identificador de la compra</label>
                                        <input type="text" class="form-control" placeholder="" v-model="compraDatos.Factura_identificador" required>
                                    </div>
                                    <div class="form-group">
                                       <label  class=" form-control-label">Seleccionar proveedor</label> 
                                       <select class="form-control" v-model="compraDatos.Proveedor_id" required>
                                            <option value="0">No asignar proveedor</option>
                                            <option v-for="proveedor in listaProveedores" v-bind:value="proveedor.Id">{{proveedor.Nombre_proveedor}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                       <label  class=" form-control-label">Seleccionar periodo al que pertenece esta compra</label> 
                                       <select class="form-control" v-model="compraDatos.Periodo_id" required>
                                            <option v-for="periodo in listaPeriodos" v-bind:value="periodo.Id">{{periodo.Nombre_periodo}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class=" form-control-label">Fecha en que se realizó la compra</label>
                                        <input type="date" class="form-control" placeholder="" v-model="compraDatos.Fecha_compra" required>
                                    </div>
                                                                        
                                    <div class="form-group">
                                        <label class="control-label">Monto de la compra en pesos</label>
                                         <input type="number" class="form-control" placeholder="" v-model="compraDatos.Valor" required>
                                    </div>
                                    <div class="form-group">
                                        <label class=" form-control-label">Descripción de la compra</label>
                                        <textarea class="form-control" rows="5" v-model="compraDatos.Descripcion"></textarea>
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
            <!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->
<?php
    // CABECERA
    include "footer.php";
?>
</body>
</html>
<!-- end document-->
