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
                            <div class="col-lg-2">
                                <div>
                                    <div class="col-lg-12">
                                        <div class="card p-0">
                                            <div class="stat-widget-three">
                                                <div class="stat-icon bg-info">
                                                    <i class="ti-user"></i>
                                                </div>
                                                <div class="stat-content">
                                                    <div class="stat-digit">
                                                        <a href="controlpresencia" class="btn">
                                                            Personal
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="col-lg-12">
                                        <div class="card p-0">
                                            <div class="stat-widget-three">
                                                <div class="stat-icon bg-warning">
                                                    <i class="ti-harddrive"></i>
                                                </div>
                                                <div class="stat-content">
                                                    <div class="stat-digit">
                                                        <a href="caja" class="btn">
                                                            Caja
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-10">
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
                    <?php  } ?>
                    </section>
            </div>

        </div>
        <?php /// CABECERA BODY
        include "footer.php";
        ?>