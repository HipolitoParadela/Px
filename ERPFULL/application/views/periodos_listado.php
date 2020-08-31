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
                                <h3 class="title-5 m-b-35">Listado de periodos</h3>
                               
                                <div class="table-data__tool">
                                    <div class="table-data__tool-left">
                                        <!-- <div class="rs-select2--light ">
                                            <input type="text" class="form-control-sm form-control" placeholder="Buscar periodo" v-model="buscar">
                                        </div> -->
                                    </div>
                                    <div class="table-data__tool-right">
                                        <button class="au-btn au-btn-icon au-btn--green au-btn--small" data-toggle="modal" data-target="#periodomodal"  v-on:click="limpiarFormularioPeriodos()">
                                            <i class="zmdi zmdi-plus"></i>Añadir periodo</button>
                                    </div>
                                </div>
                                <div class="table-responsive table-responsive-data2">
                                    <table class="table table-data2">
                                        <thead class="card-header">
                                            <tr>
                                                <th>Identificador</th>
                                                <th>Inicia</th>
                                                <th>Finaliza</th>
                                                <!-- <th>Estado</th>
                                                <th>Saldo</th> -->
                                                <th>Observaciones al inicio</th>
                                                <!-- <th>Observaciones al cierre</th> -->
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="tr-shadow" v-for="periodo in listaPeriodos.Datos">
                                                <td>
                                                    <a v-bind:href="'periodos/datos/?Id='+periodo.Id" class="btn btn-dark btn-outline m-b-10 m-l-5">
                                                        {{periodo.Identificador}}
                                                    </a>
                                                </td>
                                                <td>{{periodo.Fecha_inicio | Fecha}}</td>
                                                <td>{{periodo.Fecha_cierre | Fecha}}</td>
                                                <!-- <td>{{periodo.Estado}}</td>
                                                <td><span class="block-email">${{periodo.Saldo}}</span></td> -->
                                                <td>{{periodo.Observaciones_iniciales}}</td>
                                                <!-- <td>{{periodo.Observaciones_finales}}</td> -->
                                                <td>
                                                    <div class="table-data-feature">
                                                        <a class="item" v-bind:href="'periodos/datos/?Id='+periodo.Id" title="Ver todos los datos">
                                                            <i class="zmdi zmdi-mail-send"></i>
                                                        </a>
                                                        <button class="item"  v-on:click="editarFormularioPeriodo(periodo)" data-toggle="modal" data-target="#periodomodal" data-placement="top" title="Edición rápida">
                                                            <i class="zmdi zmdi-edit"></i>
                                                        </button>
                                                        <!-- <button v-on:click="desactivarperiodo(periodo.Id)" class="item" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                                            <i class="zmdi zmdi-delete"></i>
                                                        </button> -->
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
            <!-- modal periodos -->
			<div class="modal fade" id="periodomodal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="scrollmodalLabel">Formulario de periodo</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
                        </div>
                        <form class="form-horizontal" action="post" v-on:submit.prevent="crearPeriodo()">
                            <div class="modal-body">
                                <div class="horizontal-form">
                                    <div class="form-group">
                                        <label  class=" form-control-label">Identificador del periodo</label>
                                        <input type="text" class="form-control" placeholder="" v-model="periodoDatos.Identificador" required>
                                    </div>
                                    <div class="form-group">
                                        <label class=" form-control-label">Fecha en que inicia el periodo</label>
                                        <input type="date" class="form-control" placeholder="" v-model="periodoDatos.Fecha_inicio" required>
                                    </div>
                                    <div class="form-group">
                                        <label class=" form-control-label">Fecha en que finaliza el periodo</label>
                                        <input type="date" class="form-control" placeholder="" v-model="periodoDatos.Fecha_cierre" required>
                                    </div>
                                    <div class="form-group">
                                        <label class=" form-control-label">Observaciones_iniciales</label>
                                        <textarea class="form-control" rows="5" v-model="periodoDatos.Observaciones_iniciales"></textarea>
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
            <!-- end modal periodos -->
            <!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->
<?php
    // CABECERA
    include "footer.php";
?>
</body>
</html>
<!-- end document-->
