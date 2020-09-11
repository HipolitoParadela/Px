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
    <div class="content-wrap" id="app">
        <div class="main">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-lg-8 p-r-0 title-margin-right">
                        <div class="page-header">
                            <div class="page-title">
                                <h1>Administrar jornada</h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Sistema restaurant</a></li>
                                    <li class="breadcrumb-item active">Inicio de jornada</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->
                <?php
                if ($Estado_jornada == 1) {
                    echo ''; ?>
                    <section id="main-content">

                        <div class="row">
                            <!--  ESTE SE MOSTRARÍA CUANDO LA JORNADA ESTE INACTIVA. ESCONDER Y MOSTRAR CON PHP -->
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="container">
                                        <h3>Iniciar jornada</h3>
                                        <p class="text-success small">
                                            <em>
                                                Debe iniciar la jornada para comenzar a operar comercialmente.
                                            </em>
                                        </p>
                                        <div class="horizontal-form">
                                            <form class="form-horizontal" action="post" v-on:submit.prevent="crearJornada()">
                                                <div class="row">
                                                    <label class="col-sm-2 control-label">Descripción de la jornada</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" placeholder="Ej. Turno nocturno del Sábado" v-model="jornadaDatos.Descripcion" required>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <label class="col-sm-1 control-label">Efectivo en caja al inicio</label>
                                                    <div class="col-sm-3">
                                                        <input type="number" class="form-control" v-model="jornadaDatos.Efectivo_caja_inicio">
                                                    </div>
                                                    <label class="col-sm-1 control-label">Fecha inicio</label>
                                                    <div class="col-sm-3">
                                                        <input type="date" class="form-control" v-model="jornadaDatos.Fecha_inicio">
                                                    </div>
                                                    <label class="col-sm-1 control-label">Hora</label>
                                                    <div class="col-sm-3">
                                                        <input type="time" class="form-control" v-model="jornadaDatos.Hora_inicio" :disabled="jornadaDatos.Fecha_inicio == null">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-2"><button type="submit" class="btn btn-success" :disabled="jornadaDatos.Hora_inicio == null">Iniciar jornada</button></div>

                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php  } else {
                    echo ''; ?>

                        <div class="row">
                            <!--  ESTE SE MOSTRARÍA CUANDO LA JORNADA ESTE INACTIVA. ESCONDER Y MOSTRAR CON PHP -->
                            <div class="col-lg-12">
                                <div class="card">
                                    <h3>{{result_jornada.Datos_jornada.Descripcion}} - Contabilidad</h3>
                                    <p>Iniciada el {{result_jornada.Datos_jornada.Fecha_inicio | FechaTimestampBaseDatos}}.</p>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="stat-text">
                                                <p>Resultados de la jornada</p>
                                                <!-- <a v-on:click="mensaje()" href="javascript:void"> 
                                                                <i class="ti-info-alt"></i>
                                                                </a>-->
                                            </div>
                                            <div class="stat-digit">
                                                <h2>${{result_jornada.Resultado_jornada | Moneda}}</h2>
                                            </div>

                                        </div>
                                        <div class="col-lg-4">
                                            <div class="stat-text">
                                                <p>Total Ingresos Efectivo</p>
                                                <!-- <a v-on:click="mensaje()" href="javascript:void"> 
                                                                    <i class="ti-info-alt"></i>
                                                                </a>-->
                                            </div>
                                            <div class="stat-digit">
                                                <h4>${{result_jornada.Total_ingresos_efectivo | Moneda}}</h4>
                                            </div>

                                        </div>
                                        <div class="col-lg-4">
                                            <div class="stat-text">
                                                <p>Total Ingresos Tarjeta</p>
                                                <!-- <a v-on:click="mensaje()" href="javascript:void"> 
                                                                    <i class="ti-info-alt"></i>
                                                                </a>-->
                                            </div>
                                            <div class="stat-digit">
                                                <h4>${{result_jornada.Total_ingresos_tarjeta | Moneda}}</h4>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-2">


                                            <div class="stat-text"> Efectivo estimado en caja
                                                <!-- <a v-on:click="mensaje()" href="javascript:void"> 
                                                                <i class="ti-info-alt"></i>
                                                                </a>-->
                                            </div>
                                            <div class="stat-digit">
                                                <h4>${{result_jornada.Efectivo_estimado_caja | Moneda}}</h4>
                                            </div>

                                        </div>
                                        <div class="col-lg-2">
                                            <div class="stat-text">Efectivo de caja al inicio
                                                <!-- <a v-on:click="mensaje()" href="javascript:void" 
                                                                <i class="ti-info-alt"></i>
                                                                </a>-->
                                            </div>
                                            <div class="stat-digit">
                                                <h4>${{result_jornada.Datos_jornada.Efectivo_caja_inicio | Moneda}}</h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">


                                            <div class="stat-text">Ingresos por delivery
                                                <!-- <a v-on:click="mensaje()" href="javascript:void"> 
                                                                    <i class="ti-info-alt"></i>
                                                                </a>-->
                                            </div>
                                            <div class="stat-digit">
                                                <h4>${{result_jornada.Total_ingresos_delivery | Moneda}}</h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="stat-text">Ingresos por comandas
                                                <!-- <a v-on:click="mensaje()" href="javascript:void"> 
                                                                    <i class="ti-info-alt"></i>
                                                                </a>-->
                                            </div>
                                            <div class="stat-digit">
                                                <h4>${{result_jornada.Total_ingresos_comanda | Moneda}}</h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="stat-text">Ingresos externos en caja
                                                <!-- <a v-on:click="mensaje()" href="javascript:void">
                                                                    <i class="ti-info-alt"></i>
                                                                </a> -->
                                            </div>
                                            <div class="stat-digit">
                                                <h4>${{result_jornada.Total_ingresos_caja | Moneda}}</h4>
                                            </div>

                                        </div>
                                        <div class="col-lg-2">
                                            <div class="stat-text">Egresos de caja
                                                <!-- <a v-on:click="mensaje()" href="javascript:void"> 
                                                                    <i class="ti-info-alt"></i>
                                                                </a>-->
                                            </div>
                                            <div class="stat-digit">
                                                <h4>${{result_jornada.Total_egresos_caja | Moneda}}</h4>
                                            </div>

                                        </div>

                                    </div>

                                </div>
                                <div class="card">
                                    <h3>Cerrar jornada</h3>
                                    <div class="horizontal-form">
                                        <form class="form-horizontal" action="post" v-on:submit.prevent="cerrarJornada()">

                                            <div class="row">
                                                <label class="col-sm-2 control-label">Efectivo real en caja al cierre</label>
                                                <div class="col-sm-3">
                                                    <input type="number" class="form-control" placeholder="" v-model="jornadaDatos.Efectivo_caja_final">
                                                </div>
                                                <label class="col-sm-2 control-label">Fecha y hora del cierre</label>
                                                <div class="col-sm-3">
                                                    <input type="date" class="form-control" placeholder="" v-model="jornadaDatos.Fecha_fin" :disabled="jornadaDatos.Efectivo_caja_final == null">
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="time" class="form-control" placeholder="" v-model="jornadaDatos.Hora_fin" :disabled="jornadaDatos.Fecha_fin == null">
                                                </div>
                                            </div>
                                            <div class="row">

                                                <div class="col-lg-2"><button type="submit" class="btn btn-danger" :disabled="jornadaDatos.Hora_fin == null">Cerrar jornada</button></div>
                                                <div class="col-lg-10">
                                                    <p class="text-danger small"><em>
                                                            Antes de cerrar la jornada asegurese de haber cerrado todas las comandas y deliverys abiertos.
                                                            Deberá también dar de baja a todo el personal dado de alta en esta jornada. <br> Se recomienda cerrar la jornada
                                                            una vez que este seguro de que halla finalizado todas las tareas para evitar inconcistencias en los datos finales.</em>
                                                    </p>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <!-- /# card -->
                                <div class="card">
                                    <div class="bootstrap-data-table-panel">
                                        <h4>En jornada laboral</h4>
                                        <p>Los usuarios de esta lista tienen permiso para acceder al sistema.</p>
                                        <div class="table-responsive">
                                            <div class="modal-body">
                                                <div class="horizontal-form">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>Nombre</th>
                                                                <th>Rol</th>
                                                                <!-- <th>Hora Ingreso</th> -->
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="usuario in listaControlPresencia.Presentes">
                                                                <td>
                                                                    <div class="round-img">
                                                                        <img v-if="usuario.Imagen != null" v-bind:src="'<?php echo base_url(); ?>/pxresto/uploads/imagenes/'+usuario.Imagen" alt="">
                                                                        <!-- <img v-else  src="http://c1260237.ferozo.com/pxresto/uploads/imagenes/addimagen.jpg" alt=""> -->
                                                                    </div>
                                                                </td>
                                                                <td>{{usuario.Nombre}}</td>
                                                                <td>{{usuario.Nombre_rol}}</td>
                                                                <!-- <td>{{usuario.Fecha_hora}}</td> -->
                                                                <td>
                                                                    <a href="#" v-on:click="jornada(usuario.Id, 0)" tittle="Habilitar"><span class="badge badge-warning">Finalizar acceso al sistema para esta jornada</span></a>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /# card -->
                            </div>
                            <!-- /# column -->
                            <div class="col-lg-6">
                                <!-- /# card -->
                                <div class="card">
                                    <div class="bootstrap-data-table-panel">
                                        <h4>Fuera de jornada laboral</h4>
                                        <p>Los usuarios de esta lista, no tienen permiso para acceder al sistema.</p>
                                        <div class="table-responsive">
                                            <div class="modal-body">
                                                <div class="horizontal-form">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th></th>
                                                                <th>Nombre</th>
                                                                <th>Rol</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="usuario in listaControlPresencia.NoPresentes">
                                                                <td>
                                                                    <a href="#" v-on:click="jornada(usuario.Id, 1)" tittle="Desabilitar"><span class="badge badge-success">Permitir acceso al sistema para esta jornada</span></a>
                                                                </td>
                                                                <td>
                                                                    <div class="round-img">
                                                                        <img v-if="usuario.Imagen != null" v-bind:src="'<?php echo base_url(); ?>/pxresto/uploads/imagenes/'+usuario.Imagen" alt="">
                                                                        <!-- <img v-else  src="http://c1260237.ferozo.com/pxresto/uploads/imagenes/addimagen.jpg" alt=""> -->
                                                                    </div>
                                                                </td>
                                                                <td>{{usuario.Nombre}}</td>
                                                                <td>{{usuario.Nombre_rol}}</td>

                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /# card -->
                            </div>
                            <!-- /# column -->
                        </div>
                    <?php  } ?>
                    </section>
            </div>

        </div>
        <?php /// CABECERA BODY
        include "footer.php";
        ?>