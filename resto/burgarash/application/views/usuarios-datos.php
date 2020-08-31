<?php
// CABECERA
include "header.php";
/// NAVEGADOR SIDEBAR
if ($this->session->userdata('Rol_id') == 4) {
    include "navegadores/nav-bar-rol-4.php";
} elseif ($this->session->userdata('Rol_id') == 3) {
    include "navegadores/nav-bar-rol-3.php";
} elseif ($this->session->userdata('Rol_id') == 2) {
    include "navegadores/nav-bar-rol-2.php";
}
/// CABECERA BODY
include "header-body.php";
?>

<body>
    <div class="content-wrap" id="usuarios">
        <div class="main">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-lg-8 p-r-0 title-margin-right">
                        <div class="page-header">
                            <div class="page-title">
                                <h1>Usuarios, <span>Datos</span></h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="../">Listado de personal</a></li>
                                    <li class="breadcrumb-item active">{{usuario.Nombre}}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->

                <section id="main-content">
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="card">
                                <div class="list-group">
                                    <a href="#" class="list-group-item" v-on:click="mostrar = 1">Ficha</a>
                                    <a href="#" class="list-group-item" v-on:click="mostrar = 2">Editar datos personales</a>
                                    <a href="#" class="list-group-item" v-on:click="mostrar = 4">Formaciones</a>
                                    <a href="#" class="list-group-item" v-on:click="mostrar = 3">Sueldos</a>
                                    <a href="#" class="list-group-item" v-on:click="mostrar = 5">Jornadas</a>

                                </div>
                            </div>
                        </div>
                        <!-- SECCION FICHA USUARIO -->
                        <div class="col-lg-10" v-show="mostrar=='1'">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="user-profile">
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="user-photo m-b-30">
                                                            <img class="img-fluid" v-if="usuario.Imagen != null" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+usuario.Imagen" >
                                                            <img class="img-fluid" v-else src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" >
                                                        </div>
                                                        <div class="user-work">
                                                            <h4>Laborales</h4>
                                                            <div class="work-content">
                                                                <p>Antigüedad: {{usuario.Fecha_alta | Fecha}} </p>

                                                                <p v-if="usuario.Periodo_liquidacion_sueldo ==1">Sueldo mensual </p>
                                                                <p v-if="usuario.Periodo_liquidacion_sueldo ==2">Sueldo quincenal </p>
                                                                <p v-if="usuario.Periodo_liquidacion_sueldo ==3">Sueldo semanal </p>
                                                                <p v-if="usuario.Periodo_liquidacion_sueldo ==4">Cobra por día </p>

                                                                <p v-if="usuario.Lider == 1"> Tiene personas a cargo </p>
                                                                <p v-if="usuario.Lider == 0"> No tiene personas a cargo </p>
                                                                <p>Superior directo: {{usuario.Nombre_superior}} </p>
                                                            </div>

                                                        </div>
                                                        <div class="user-work">
                                                            <h4>Obra social</h4>
                                                            <div class="work-content">
                                                                <h3>{{usuario.Obra_social}} </h3>
                                                                <p>Número de asociado: {{usuario.Numero_obra_social}} </p>
                                                            </div>

                                                        </div>
                                                        <div class="user-work">
                                                            <h4>Datos bancarios</h4>
                                                            <div class="work-content">
                                                                <p>{{usuario.Datos_bancarios}} </p>
                                                            </div>

                                                        </div>


                                                    </div>
                                                    <div class="col-lg-8">
                                                        <div class="user-profile-name">{{usuario.Nombre}}</div>
                                                        <div class="user-Location"><i class="ti-location-pin"></i> {{usuario.Domicilio}}</div>
                                                        <div class="user-job-title">{{usuario.Nombre_rol}}</div>
                                                        <!-- <div class="ratings">
                                                <h4>Ratings</h4>
                                                <div class="rating-star">
                                                <span>8.9</span>
                                                <i class="ti-star color-primary"></i>
                                                <i class="ti-star color-primary"></i>
                                                <i class="ti-star color-primary"></i>
                                                <i class="ti-star color-primary"></i>
                                                <i class="ti-star"></i>
                                                </div>
                                            </div>  -->
                                                        <div class="user-send-message">
                                                            <a v-bind:href="'https://api.whatsapp.com/send?phone='+usuario.Telefono" class="btn btn-success btn-addon">
                                                                <i class="fa fa-whatsapp"></i>Enviar whatsapp
                                                            </a>
                                                        </div>
                                                        <div class="custom-tab user-profile-tab">
                                                            <ul class="nav nav-tabs" role="tablist">
                                                                <li role="presentation" class="active"><a href="#1" aria-controls="1" role="tab" data-toggle="tab">Acerca de</a></li>
                                                            </ul>
                                                            <div class="tab-content">
                                                                <div role="tabpanel" class="tab-pane active" id="1">
                                                                    <div class="contact-information">
                                                                        <h4>Información de contacto</h4>
                                                                        <div class="phone-content">
                                                                            <span class="contact-title">Teléfono:</span>
                                                                            <span class="phone-number">{{usuario.Telefono}}</span>
                                                                        </div>
                                                                        <div class="address-content">
                                                                            <span class="contact-title">Domicilio:</span>
                                                                            <span class="mail-address">{{usuario.Domicilio}}</span>
                                                                        </div>
                                                                        <div class="email-content">
                                                                            <span class="contact-title">Email:</span>
                                                                            <span class="contact-email">{{usuario.Email}}</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="basic-information">
                                                                        <h4>Información básica</h4>
                                                                        <div class="birthday-content">
                                                                            <span class="contact-title">Cumpleaños:</span>
                                                                            <span class="birth-date">{{usuario.Fecha_nacimiento | Fecha}}</span>
                                                                        </div>
                                                                        <div class="birthday-content">
                                                                            <span class="contact-title">Nacionalidad:</span>
                                                                            <span class="birth-date">{{usuario.Nacionalidad}}</span>
                                                                        </div>
                                                                        <div class="gender-content">
                                                                            <span class="contact-title">Genero:</span>
                                                                            <span v-if="usuario.Genero == 1" class="gender">Masculino</span>
                                                                            <span v-if="usuario.Genero == 0" class="gender">Femenino</span>
                                                                        </div>
                                                                        <div class="birthday-content">
                                                                            <span class="contact-title">Estado civil:</span>
                                                                            <span class="birth-date">{{usuario.Estado_civil}}</span>
                                                                        </div>
                                                                        <div class="birthday-content">
                                                                            <span class="contact-title">Hijos:</span>
                                                                            <span v-if="usuario.Hijos == 0" class="gender">No tiene hijos</span>
                                                                            <span v-if="usuario.Hijos == 1" class="gender">Tiene un hijo</span>
                                                                            <span v-if="usuario.Hijos > 1" class="gender">Tiene {{usuario.Hijos}} hijos</span>
                                                                        </div>
                                                                        <div class="birthday-content">
                                                                            <span class="contact-title">Observaciones:</span>
                                                                            <span class="birth-date">{{usuario.Observaciones}}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /# row -->
                        </div>

                        <!-- SECCION DATOS DEL USUARIO -->
                        <div class="col-lg-10" v-show="mostrar == '2'">
                            <div class="card">
                                <div class="card-title">
                                    <h4>Datos personales</h4>
                                </div>
                                <div class="card-body">
                                    <div class="horizontal-form">
                                        <form class="form-horizontal" action="post" v-on:submit.prevent="crearUsuarios()">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Nombre</label>
                                                        <input type="text" class="form-control" placeholder="" v-model="usuario.Nombre">

                                                        <label class="control-label">DNI</label>
                                                        <input type="number" class="form-control" placeholder="" v-model="usuario.DNI">

                                                        <label class="control-label">Password</label>
                                                        <input type="password" class="form-control" placeholder="" v-model="usuario.Pass">

                                                        <label class="control-label">Telefono</label>
                                                        <input type="text" class="form-control" placeholder="" v-model="usuario.Telefono">

                                                        <label class="control-label">Email</label>
                                                        <input type="email" class="form-control" placeholder="" v-model="usuario.Email">

                                                        <label class="control-label">Rol</label>
                                                        <select class="form-control" v-model="usuario.Rol_id">
                                                            <option v-for="rol in listaRoles" v-bind:value="rol.Id">{{rol.Nombre_rol}}</option>
                                                        </select>

                                                        <label class="control-label">Fecha ingreso a la empresa</label>
                                                        <input type="date" class="form-control" placeholder="" v-model="usuario.Fecha_alta">

                                                        <label class="control-label">Estado civil</label>
                                                        <select class="form-control" v-model="usuario.Estado_civil">
                                                            <option value="Soltero">Soltero</option>
                                                            <option value="Casado">Casado</option>
                                                            <option value="Viudo">Viudo</option>
                                                        </select>

                                                        <label class="control-label">Tiene personal a cargo</label>
                                                        <select class="form-control" v-model="usuario.Lider">
                                                            <option value="1">Si</option>
                                                            <option value="0">No</option>
                                                        </select>

                                                        <label class="control-label">Superior inmediato</label>
                                                        <select class="form-control" v-model="usuario.Superior_inmediato">
                                                            <option v-for="persona in listaSuperiores" v-bind:value="persona.Id">{{persona.Nombre}}</option>
                                                        </select>

                                                        <label class="control-label">Observaciones</label>
                                                        <textarea class="form-control" rows="5" placeholder="" v-model="usuario.Observaciones"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Fecha de nacimiento</label>
                                                        <input type="date" class="form-control" placeholder="" v-model="usuario.Fecha_nacimiento">

                                                        <label class="control-label">Domicilio</label>
                                                        <input type="text" class="form-control" placeholder="" v-model="usuario.Domicilio">

                                                        <label class="control-label">Nacionalidad</label>
                                                        <input type="text" class="form-control" placeholder="" v-model="usuario.Nacionalidad">

                                                        <label class="control-label">Genero</label>
                                                        <select class="form-control" v-model="usuario.Genero">
                                                            <option value="1">Masculino</option>
                                                            <option value="2">Femenino</option>
                                                        </select>

                                                        <label class="control-label">Obra social</label>
                                                        <input type="text" class="form-control" placeholder="" v-model="usuario.Obra_social">

                                                        <label class="control-label">Número de afiliado obra social</label>
                                                        <input type="text" class="form-control" placeholder="" v-model="usuario.Numero_obra_social">

                                                        <label class="control-label">Cantidad de hijos</label>
                                                        <input type="number" class="form-control" placeholder="" min="0" v-model="usuario.Hijos">

                                                        <label class="control-label">Nombre y datos de persona de contacto</label>
                                                        <textarea class="form-control" rows="5" placeholder="" v-model="usuario.Datos_persona_contacto"></textarea>

                                                        <label class="control-label">Remuneración por jornada</label>
                                                        <input type="number" class="form-control" placeholder="" min="0" v-model="usuario.Remuneracion_jornada">

                                                        <label class="control-label">Periodo de liquidación de sueldo</label>
                                                        <select class="form-control" v-model="usuario.Periodo_liquidacion_sueldo">
                                                            <option value="4">Diario</option>
                                                            <option value="3">Semanal</option>
                                                            <option value="2">Quincenal</option>
                                                            <option value="1">Mensual</option>
                                                        </select>

                                                        <label class="control-label">Horario laboral</label>
                                                        <input type="text" class="form-control" placeholder="" v-model="usuario.Horario_laboral">

                                                        <label class="control-label">Datos bancarios</label>
                                                        <textarea class="form-control" rows="5" placeholder="" v-model="usuario.Datos_bancarios"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-offset-2 col-sm-12">
                                                    <button type="submit" class="btn btn-success">Actualizar datos</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECCION DATOS DE SUELDO -->
                        <div class="col-lg-10" v-if="mostrar == '3'">
                            <div class="row">
                                <div class="col-lg-2">
                                    <div class="card">
                                        <h4>Filtrar</h4>
                                        <form class="form-horizontal" action="post">

                                            <label for="desde">Desde</label>
                                            <input type='date' class="form-control" v-model="Fecha_desde" />

                                            <label for="desde">Hasta</label>
                                            <input type='date' class="form-control" v-model="Fecha_hasta" :disabled="Fecha_desde == 0" v-on:change="getSueldos()" v-bind:min="Fecha_desde" />

                                        </form>
                                    </div>
                                </div>
                                <div class="col-lg-10">
                                    <div class="card">
                                        <div class="card-title">
                                            <h4>Liquidación de sueldos</h4>
                                        </div>
                                        <div class="card-body">


                                            <div class="bootstrap-data-table-panel">
                                                <div class="table-responsive">
                                                    <table id="table2excel" class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Fecha de liquidación</th>
                                                                <th align="right">Sueldo pactado</th>
                                                                <th align="right">Sueldo abonado</th>
                                                                <th align="right">Bonificacion</th>
                                                                <th align="right">Otros Costos</th>
                                                                <th>Observaciones</th>
                                                                <th>
                                                                    <a href="#modalSueldos" data-toggle="modal" title="Nuevo item" class="btn btn-success btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="limpiarFormularioSueldos()">
                                                                        <i class="ti-plus"></i> Liquidación
                                                                    </a>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="sueldo in listaSueldos">
                                                                <td>{{sueldo.Fecha | Fecha}}</td>
                                                                <td align="right">{{sueldo.Sueldo_pactado}}</td>
                                                                <td align="right">{{sueldo.Sueldo_abonado}}</td>
                                                                <td align="right">{{sueldo.Bonificacion}}</td>
                                                                <td align="right">{{sueldo.Descuento}}</td>
                                                                <td align="right">{{sueldo.Observaciones}}</td>
                                                                <td>
                                                                    <a href="#modalSueldos" data-toggle="modal" v-on:click="editarFormularioSueldo(sueldo)">
                                                                        <i class="ti-pencil-alt"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <!-- <td></td>
                                                                    <td align="right"><h4><span class="text-info">${{sumarCuenta(listasueldosCerradas)}}</span></h4></td>
                                                                    <td align="right"><h4><span class="text-danger">${{sumarDescuentos(listasueldosCerradas)}}</span></h4></td>
                                                                    <td align="right"><h4><span class="text-success">${{sumarCuenta(listasueldosCerradas) - sumarDescuentos(listasueldosCerradas)}}</span></h4></td> -->
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <!-- /# row -->
                                </div>
                            </div>
                        </div>

                        <!-- SECCION DATOS DE FORMACIÓN -->
                        <div class="col-lg-10" v-show="mostrar == '4'">
                            <div class="card">
                                <div class="card-title">
                                    <h4>Formaciones académicas y técnicas</h4>
                                </div>
                                <div class="card-body">

                                    <div class="row">
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
                                                            <td>{{formacion.Anio_inicio | Fecha}}</td>
                                                            <td>{{formacion.Anio_finalizado | Fecha}}</td>
                                                            <td>{{formacion.Descripcion_titulo}}</td>
                                                            <td>
                                                                <a href="#modalFormaciones" data-toggle="modal" v-on:click="editarFormularioFormacion(formacion)">
                                                                    <i class="ti-pencil-alt"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <p align="right">
                                        <a href="#modalFormaciones" data-toggle="modal" title="Nuevo item" class="btn btn-success btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="limpiarFormularioFormacion()">
                                            <i class="ti-plus"></i> Añadir formación
                                        </a>
                                    </p>
                                </DIV>
                            </DIV>
                        </div>

                        <!-- SECCION DATOS DE SUELDO -->
                        <div class="col-lg-10" v-if="mostrar == '5'">
                            <div class="row">
                                <div class="col-lg-2">
                                    <div class="card">
                                        <h4>Filtrar</h4>
                                        <form class="form-horizontal" action="post">
                                            <div class="form-group">
                                                <label for="desde">Desde</label>
                                                <input type='date' class="form-control" v-model="Fecha_desde" />
                                            </div>
                                            <div class="form-group">
                                                <label for="desde">Hasta</label>
                                                <input type='date' class="form-control" v-model="Fecha_hasta" :disabled="Fecha_desde == 0" v-on:change="getJornadasTrabajadas()" v-bind:min="Fecha_desde" />
                                            </div>
                                        </form>
                                        <br>
                                        <p v-if="Fecha_hasta == 0">Se muestran los últimos 7 días</p> <!-- <pre>{{Fecha_hasta}}</pre> -->
                                    </div>
                                </div>
                                <div class="col-lg-10">
                                    <div class="card">
                                        <div class="card-title">
                                            <h4>Jornadas presente</h4>
                                        </div>
                                        <div class="card-body">


                                            <div class="bootstrap-data-table-panel">
                                                <div class="table-responsive">
                                                    <table id="table2excel" class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Jornada</th>
                                                                <th>Inicio</th>
                                                                <th>Final</th>
                                                                <!-- <th>Duración</th> -->
                                                                <th>Monto</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="jornada in listaJornadasTrabajadas">
                                                                <td>{{jornada.Descripcion}}</td>
                                                                <td>{{jornada.Fecha_inicio | FechaTimestampBaseDatos}}</td>
                                                                <td>{{jornada.Fecha_final | FechaTimestampBaseDatos}}</td>
                                                                <!-- <td>{{jornada.Bonificacion}}</td> -->
                                                                <td>{{jornada.Remuneracion_jornada}}</td>
                                                            </tr>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td>
                                                                    <h4 align="right">
                                                                        $ {{sumarMontos(listaJornadasTrabajadas)}}
                                                                    </h4>
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <!-- /# row -->
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
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
        <!-- Modal Sueldo-->
        <div class="modal fade" id="modalSueldos" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="true">
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
                            <form class="form-horizontal" action="post" v-on:submit.prevent="cargarLiquidacion()">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label class="control-label">Fecha de liquidación</label>
                                        <input type="date" class="form-control" placeholder="" v-model="sueldoDato.Fecha">
                                    </div>


                                    <div class="col-sm-12">
                                        <label class="control-label">Monto pactado para esta liquidación</label>
                                        <input type="number" class="form-control" placeholder="" v-model="sueldoDato.Sueldo_pactado">
                                    </div>

                                    <div class="col-sm-12">
                                        <label class="control-label">Monto abonado</label>
                                        <input type="number" class="form-control" placeholder="" v-model="sueldoDato.Sueldo_abonado">
                                    </div>

                                    <div class="col-sm-12">
                                        <label class="control-label">Monto de bonificación</label>
                                        <input type="number" class="form-control" placeholder="" v-model="sueldoDato.Bonificacion">
                                    </div>

                                    <div class="col-sm-12">
                                        <label class="control-label">Monto descontado</label>
                                        <input type="number" class="form-control" placeholder="" v-model="sueldoDato.Descuento">
                                    </div>
                                    <div class="col-sm-12">
                                        <label class="control-label">Otros montos adicionales</label>
                                        <input type="number" class="form-control" placeholder="" v-model="sueldoDato.Costes_impositivos_adicionales">
                                    </div>
                                    <div class="col-sm-12">
                                        <label class="control-label">Observaciones</label>
                                        <textarea class="form-control" rows="5" placeholder="" v-model="sueldoDato.Observaciones"></textarea>
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
        <?php /// FOOTER
        include "footer.php";
        ?>