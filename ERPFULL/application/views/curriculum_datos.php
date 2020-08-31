<?php
// CABECERA
include "head.php";
// TOP
include "top.php";
include "menusidebar.php";
?>
        <!-- PAGE CONTAINER-->
        <div class="page-container" id="curriculum">
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
                                            <img v-if="curriculum.foto != null" width="420px" v-bind:src="curriculum.foto" alt="">
                                            <img v-else src="https://freeiconshop.com/wp-content/uploads/edd/person-flat.png" alt="">
                                        </div>
                                        <h5 class="text-sm-center mt-2 mb-1">{{curriculum.nombre}}</h5>
                                        <div class="location text-sm-center">
                                            <i class="fa fa-map-marker"></i> {{curriculum.domicilio}}, {{curriculum.ciudad}}</div>
                                        </div>
                                        <hr>
                                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                            <!--<a href="#"class="nav-link" v-on:click="mostrar = 1">Ficha</a>-->
                                            <a href="#"class="nav-link" v-on:click="mostrar = 1">Datos del curriculum</a>
                                            <a href="#"class="nav-link" v-on:click="mostrar = 2">Puestos potenciales</a>
                                        </div>
                                    </div>
                                    <div>
                                        <a target="blank" v-bind:href="'https://api.whatsapp.com/send?phone=+549'+curriculum.telefono" class="btn btn-success btn-block" >
                                           <i class="fab fa-whatsapp"></i> Enviar whatsapp
                                        </a>
                                    <hr>
                                        <a target="blank" v-bind:href="'mailto:'+curriculum.email" class="btn btn-info btn-block" >
                                           <i class="fa fa-envelope"></i> Enviar email
                                        </a>
                                    </div>
                                </div>
                            
                            <!-- SECCION FICHA USUARIO -->
                            <div class="col-lg-10" >                                               
                                <!-- SECCION DATOS EDITABLES DEL USUARIO -->
                                <div class="row" v-show="mostrar == '1'">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <strong>Curriculum</strong>
                                                <small>Cargado: 
                                                    <code>{{curriculum.fecha}}</code>
                                                </small>
                                            </div>
                                            <div class="card-body">
                                                <div class="horizontal-form">
                                                    <form class="form-horizontal" action="post" v-on:submit.prevent="actualizarCurriculum()">
                                                        <div class="row">
                                                            <div class="col-sm-offset-2 col-sm-12">
                                                                <p align="right"><button type="submit" class="btn btn-success">Guardar datos</button></p>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label class="control-label" for="Puntaje">Calificar curriculum</label>
                                                                    <select class="form-control" v-model="curriculum.Puntaje" id="Puntaje">
                                                                        <option>Ninguno</option>
                                                                        <option value="1">No apto</option>
                                                                        <option value="2">Regular</option>
                                                                        <option value="3">Bueno</option>
                                                                        <option value="4">Excelente</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-9">
                                                                
                                                                <div class="form-group">
                                                                    <label class="control-label" for="masinfo">Observaciones sobre el postulante</label>
                                                                    <textarea class="form-control" v-model="curriculum.Observaciones" id="masinfo" cols="45" rows="5"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <h4>Datos personales</h4>
                                                                <hr>
                                                                <div class="form-group">
                                                                    <label class="control-label">Nombre</label>
                                                                    <input class="form-control" placeholder="Nombre y Apellido" type="text" v-model="curriculum.nombre" id="name" />
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label class="control-label" for="ciudad">Ciudad y Provincia</label>
                                                                    <input class="form-control" placeholder="Ciudad y Provincia" type="text" v-model="curriculum.ciudad" id="ciudad" />
                                                                </div>

                                                                <div class="form-group">
                                                                    <label class="control-label" for="domicilio">Domicilio</label>
                                                                    <input class="form-control" placeholder="Domicilio" type="text" v-model="curriculum.domicilio" id="domicilio" />
                                                                </div>

                                                                <div class="form-group">
                                                                    <label class="control-label" for="telefono">Teléfono</label>
                                                                    <input class="form-control" placeholder="Teléfono" type="text" v-model="curriculum.telefono" id="telefono" />
                                                                </div>

                                                                <div class="form-group">
                                                                    <label class="control-label" for="email">Email</label>
                                                                    <input class="form-control" placeholder="Email" type="email" v-model="curriculum.email" id="email" />
                                                                </div>

                                                                <div class="form-group">
                                                                    <label class="control-label" for="cuil">CUIL</label>
                                                                    <input class="form-control" placeholder="DNI o CUIL" type="text" v-model="curriculum.cuil" id="cuil" />
                                                                </div>

                                                                <div class="form-group">
                                                                    <label class="control-label" for="sexo">Sexo</label>
                                                                    <select class="form-control" v-model="curriculum.sexo" id="sexo">
                                                                        <option>Indique su sexo</option>
                                                                        <option value="Masculino">Masculino</option>
                                                                        <option value="Femenino">Femenino</option>
                                                                    </select>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label class="control-label" for="nacimiento">Fecha de Nacimiento</label>
                                                                    <input  class="form-control" type="date" v-model="curriculum.nacimiento" id="nacimiento" />
                                                                </div>

                                                                <div class="form-group">
                                                                    <label class="control-label" for="estadocivil">Estado civil</label>
                                                                    <select class="form-control" v-model="curriculum.estadocivil" id="estadocivil">
                                                                        <option>Indique su estado civil</option>
                                                                        <option value="Soltero">Soltero</option>
                                                                        <option value="Conviviendo">Conviviendo</option>
                                                                        <option value="Casado">Casado</option>
                                                                        <option value="Divorciado">Divorciado</option>
                                                                        <option value="Viudo">Viudo</option>
                                                                    </select>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label class="control-label" for="estadocivil">¿Tiene hijos?</label>
                                                                    <select class="form-control" v-model="curriculum.hijos" id="hijos">
                                                                    <option value="No">No</option>
                                                                    <option value="1">1</option>
                                                                    <option value="2">2</option>
                                                                    <option value="3">3</option>
                                                                    <option value="4">4</option>
                                                                    <option value="5mas">5 o más</option>
                                                                    </select>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label class="control-label" for="nivelestudios">Nivel de estudios</label>
                                                                    <input type="text" class="form-control" v-model="curriculum.nivelestudios" id="nivelestudios">
                                                                </div>

                                                                <div class="form-group">
                                                                    <label class="control-label" for="sobreestudios">Sobre sus estudios</label>
                                                                    <textarea class="form-control" v-model="curriculum.sobreestudios" id="sobreestudios" cols="45" rows="5"></textarea>
                                                                </div>

                                                                <div class="form-group"><strong></strong><br>
                                                                    <label class="control-label" for="sobreestudios">Experiencia laboral</label>
                                                                    <textarea class="form-control" v-model="curriculum.laboral" id="laboral" cols="45" rows="5"></textarea>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label class="control-label" for="intereses">Interes en la empresa</label>
                                                                    <textarea class="form-control" v-model="curriculum.intereses" id="intereses" cols="45" rows="5"></textarea>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label class="control-label" for="referencia">Persona de referencia en la empresa</label>
                                                                    <input class="form-control" placeholder="" type="text" v-model="curriculum.referencia" id="referencia">
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label class="control-label" for="personal">Acotaciones del postulante</label>
                                                                    <textarea class="form-control" v-model="curriculum.personal" id="personal" cols="45" rows="5"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-offset-2 col-sm-12">
                                                                <p align="right"><button type="submit" class="btn btn-success">Guardar datos</button></p>
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
                                                <strong>Puestos potenciales para esta persona</strong>
                                            </div>
                                            <div class="card-body"> 
                                                <div class="bootstrap-data-table-panel col-lg-12">
                                                    <div class="table-responsive">
                                                        <table id="table2excel" class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>Puesto</th>
                                                                    <th>Justificación</th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr v-for="puesto in listaPuestosInteres">
                                                                    <td>{{puesto.Nombre_puesto}}</td>
                                                                    <td>{{puesto.Justificacion}}</td>
                                                                    <td>
                                                                        <button v-on:click="eliminar(puesto.Id,'tbl_curriculum_puestos')" class="item" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                                                            <i class="zmdi zmdi-delete"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="horizontal-form">
                                                    <form class="form-horizontal" action="post" v-on:submit.prevent="agregarPuestoCurriculum()">
                                                        <div class="form-group">
                                                            <label class="control-label">Elegir puesto</label>
                                                            <select class="form-control form-control" v-model="puestoData.Puesto_Id">
                                                                <option selected="selected" v-bind:value="0">...</option>
                                                                <option v-for="puestos in listaPuestos" v-bind:value="puestos.Id">{{puestos.Nombre_puesto}}</option>
                                                            </select>
                                                        </div>    
                                                        <div class="form-group"> 
                                                            <label class="control-label">Justificación del puesto</label>
                                                            <textarea class="form-control" rows="5" placeholder="" v-model="puestoData.Justificacion"></textarea>
                                                        </div>    
                                                        <div class="form-group"> 
                                                            <button type="submit" class="btn btn-success">Añadir</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </DIV>
                                    </DIV>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           
        
            
            <!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->
        

    

   <?php
// CABECERA
include "footer.php";
?>

</body>

</html>
<!-- end document-->
