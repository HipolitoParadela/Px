<?php
// CABECERA
include "head.php";
// TOP
include "top.php";
include "menusidebar.php";
?>
<!-- PAGE CONTAINER-->
<div class="page-container" id="usuarios">
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
                                    <img v-if="usuario.Imagen != null" width="420px" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+usuario.Imagen" alt="">
                                    <img v-else src="https://freeiconshop.com/wp-content/uploads/edd/person-flat.png" alt="">
                                </div>
                                <h5 class="text-sm-center mt-2 mb-1">{{usuario.Nombre}}</h5>
                                <div class="location text-sm-center">
                                    <i class="fa fa-map-marker"></i> {{usuario.Domicilio}}</div>
                            </div>
                        </div>
                        <div>
                            <a target="_blank" v-bind:href="'https://api.whatsapp.com/send?phone=+549'+usuario.Telefono" class="btn btn-success btn-block">
                                <i class="fab fa-whatsapp"></i> Enviar whatsapp
                            </a>
                            <hr>
                            <a target="_blank" v-bind:href="'mailto:'+usuario.Email" class="btn btn-info btn-block">
                                <i class="fa fa-envelope"></i> Enviar email
                            </a>
                        </div>
                    </div>

                    <!-- SECCION FICHA USUARIO -->
                    <div class="col-lg-10">

                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 1 }" href="#" v-on:click="mostrar = 1">Ficha</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 2 }" href="#" v-on:click="mostrar = 2">Formaciones</a>
                            </li>
                        </ul>

                        <!-- SECCION DATOS EDITABLES DEL USUARIO -->
                        <div class="row" v-show="mostrar == '1'">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Ficha: {{usuario.Nombre}}</strong>
                                        <small>Última actualización
                                            <code>{{usuario.Ultima_actualizacion}}</code>
                                        </small>
                                    </div>
                                    <div class="card-body">
                                        <div class="horizontal-form">
                                            <form class="form-horizontal" action="post" v-on:submit.prevent="crearUsuarios()">
                                                <div class="row">
                                                    <div class="col-sm-offset-2 col-sm-12">
                                                        <p align="right"><button type="submit" class="btn btn-success">Actualizar datos</button></p>

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <h4>Datos personales</h4>
                                                        <hr>
                                                        <div class="form-group">
                                                            <label class="control-label">Nombre</label>
                                                            <input type="text" class="form-control" placeholder="" v-model="usuario.Nombre">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">DNI</label>
                                                            <input type="number" class="form-control" placeholder="" v-model="usuario.DNI">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">CUIL</label>
                                                            <input type="number" class="form-control" placeholder="" v-model="usuario.CUIT_CUIL">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Domicilio</label>
                                                            <input type="text" class="form-control" placeholder="" v-model="usuario.Domicilio">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Nacionalidad</label>
                                                            <input type="text" class="form-control" placeholder="" v-model="usuario.Nacionalidad">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Genero</label>
                                                            <select class="form-control" v-model="usuario.Genero">
                                                                <option value="1">Masculino</option>
                                                                <option value="2">Femenino</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Telefono</label>
                                                            <input type="text" class="form-control" placeholder="" v-model="usuario.Telefono">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Email</label>
                                                            <input type="email" class="form-control" placeholder="" v-model="usuario.Email">
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="control-label">Estado civil</label>
                                                            <select class="form-control" v-model="usuario.Estado_civil">
                                                                <option value="Soltero">Soltero</option>
                                                                <option value="Casado">Casado</option>
                                                                <option value="Viudo">Viudo</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Cantidad de hijos</label>
                                                            <input type="number" class="form-control" placeholder="" min="0" v-model="usuario.Hijos">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Fecha de nacimiento</label>
                                                            <input type="date" class="form-control" placeholder="" v-model="usuario.Fecha_nacimiento">
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="control-label">Obra social</label>
                                                            <input type="text" class="form-control" placeholder="" v-model="usuario.Obra_social">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Número de afiliado obra social</label>
                                                            <input type="text" class="form-control" placeholder="" v-model="usuario.Numero_obra_social">
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="control-label">Nombre y datos de persona de contacto</label>
                                                            <textarea class="form-control" rows="5" placeholder="" v-model="usuario.Datos_persona_contacto"></textarea>
                                                        </div>

                                                    </div>

                                                    <div class="col-sm-6">
                                                        <h4>Datos empresariales</h4>
                                                        <hr>
                                                        <div class="form-group">
                                                            <label class="control-label">Rol</label>
                                                            <select class="form-control" v-model="usuario.Rol_id">
                                                                <option v-for="rol in listaRoles" v-bind:value="rol.Id">{{rol.Nombre_rol}}</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Fecha ingreso a la empresa</label>
                                                            <input type="date" class="form-control" placeholder="" v-model="usuario.Fecha_alta">
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="control-label">Empresa</label>
                                                            <select class="form-control" v-model="usuario.Empresa_id">
                                                                <option value="0">...</option>
                                                                <option v-for="empresas in listaEmpresas" v-bind:value="empresas.Id">{{empresas.Nombre_empresa}}</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Puesto</label>
                                                            <select class="form-control" v-model="usuario.Puesto_Id">
                                                                <option value="0">...</option>
                                                                <option v-for="puestos in listaPuestos" v-bind:value="puestos.Id">{{puestos.Nombre_puesto}}</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Tiene personal a cargo</label>
                                                            <select class="form-control" v-model="usuario.Lider">
                                                                <option value="1">Si</option>
                                                                <option value="0">No</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Superior inmediato</label>
                                                            <select class="form-control" v-model="usuario.Superior_inmediato">
                                                                <option v-for="persona in listaSuperiores" v-bind:value="persona.Id">{{persona.Nombre}}</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="control-label">Datos bancarios</label>
                                                            <textarea class="form-control" rows="5" placeholder="" v-model="usuario.Datos_bancarios"></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Periodo de liquidación de sueldo</label>
                                                            <select class="form-control" v-model="usuario.Periodo_liquidacion_sueldo">
                                                                <option value="4">Diario</option>
                                                                <option value="3">Semanal</option>
                                                                <option value="2">Quincenal</option>
                                                                <option value="1">Mensual</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Horario laboral</label>
                                                            <input type="text" class="form-control" placeholder="" v-model="usuario.Horario_laboral">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Observaciones</label>
                                                            <textarea class="form-control" rows="5" placeholder="" v-model="usuario.Observaciones"></textarea>
                                                        </div>
                                                        <hr>
                                                        <div class="form-group">
                                                            <label class="control-label">Password</label>
                                                            <input type="password" class="form-control" placeholder="" v-model="usuario.Pass">
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-offset-2 col-sm-12">
                                                        <p align="right"><button type="submit" class="btn btn-success">Actualizar datos</button></p>

                                                    </div>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- SECCION DATOS DE FORMACIÓN -->
                        <div class="row" v-show="mostrar == '2'">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Formaciones académicas y técnicas</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="bootstrap-data-table-panel col-lg-12">
                                            <div class="table-responsive">
                                                <table id="table2excel" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <!-- <th></th> -->
                                                            <th>Titulo</th>
                                                            <th>Establecimiento</th>
                                                            <th>Fecha inicio</th>
                                                            <th>Fecha finalizado</th>
                                                            <th>Descripcion</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="formacion in listaFormaciones">
                                                            <!-- <td>
                                                                        <div class="round-img">
                                                                            <a href="#modalUsuariosFormacion" data-toggle="modal" v-on:click="editarFormularioFormacion(formacion)">
                                                                                <img v-if="formacion.Imagen != null"  v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+formacion.Imagen" alt="">
                                                                                <img v-else  src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" alt="">
                                                                            </a>
                                                                        </div>
                                                                    </td> -->
                                                            <td>{{formacion.Titulo}}</td>
                                                            <td>{{formacion.Establecimiento}}</td>
                                                            <td>{{formatoFecha(formacion.Anio_inicio)}}</td>
                                                            <td>{{formatoFecha(formacion.Anio_finalizado)}}</td>
                                                            <td>{{formacion.Descripcion_titulo}}</td>
                                                            <td>
                                                                <a href="#modalFormaciones" data-toggle="modal" v-on:click="editarFormularioFormacion(formacion)">
                                                                    <i class="ti-pencil-alt"></i> Editar
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p align="right">
                                            <a href="#modalFormaciones" data-toggle="modal" title="Nuevo item" class="btn btn-success btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="limpiarFormularioFormacion()">
                                                <i class="ti-plus"></i> Añadir formación
                                            </a>
                                        </p>
                                    </div>
                                </DIV>
                            </DIV>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Formacion-->
        <div class="modal fade" id="modalFormaciones" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="true">
            <div class="modal-dialog  modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalItemsCartaTitle">{{texto_boton}} usuarios</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="horizontal-form">
                            <form class="form-horizontal" action="post" v-on:submit.prevent="crearFormacion()">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label class="control-label">Titulo</label> <input type="text" class="form-control" placeholder="" v-model="formacionData.Titulo">
                                    </div>


                                    <div class="col-sm-12">
                                        <label class="control-label">Establecimiento</label>
                                        <input type="text" class="form-control" placeholder="" v-model="formacionData.Establecimiento">
                                    </div>

                                    <div class="col-sm-12">
                                        <label class="control-label">Año Inicio</label>
                                        <input type="date" class="form-control" placeholder="" v-model="formacionData.Anio_inicio">
                                    </div>

                                    <div class="col-sm-12">
                                        <label class="control-label">Año Finalizado</label>
                                        <input type="date" class="form-control" placeholder="" v-model="formacionData.Anio_finalizado">
                                    </div>

                                    <div class="col-sm-12">
                                        <label class="control-label">Descripcion del titulo</label>
                                        <textarea class="form-control" rows="5" placeholder="" v-model="formacionData.Descripcion_titulo"></textarea>
                                    </div>

                                    <div class="col-sm-offset-2 col-sm-12">
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