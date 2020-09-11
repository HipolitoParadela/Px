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
    <div class="content-wrap" id="jornada">
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

                <section id="main-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <h3>{{Jornada_datos.Datos_jornada.Descripcion}}</h3>
                                <p><span class="text-info">Iniciada el {{Jornada_datos.Datos_jornada.Fecha_inicio | FechaTimestampBaseDatos}}</span> | <span class="text-danger">Finalizada el {{Jornada_datos.Datos_jornada.Fecha_final | FechaTimestampBaseDatos}}</span></p>
                                <div class="row">
                                    <div class="col-lg-4">

                                        <div class="stat-text">
                                            <p>Resultados de la jornada</p>
                                            <!-- <a v-on:click="mensaje()" href="javascript:void"> 
                                                                <i class="ti-info-alt"></i>
                                                                </a>-->
                                        </div>
                                        <div class="stat-digit">
                                            <h2>${{Jornada_datos.Resultado_jornada | Moneda}}</h2>
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
                                            <h4>${{Jornada_datos.Total_ingresos_efectivo | Moneda}}</h4>
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
                                            <h4>${{Jornada_datos.Total_ingresos_tarjeta | Moneda}}</h4>
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
                                            <h4>${{Jornada_datos.Efectivo_estimado_caja | Moneda}}</h4>
                                        </div>

                                    </div>
                                    <div class="col-lg-2">
                                        <div class="stat-text">Efectivo de caja al inicio
                                            <!-- <a v-on:click="mensaje()" href="javascript:void" 
                                                                <i class="ti-info-alt"></i>
                                                                </a>-->
                                        </div>
                                        <div class="stat-digit">
                                            <h4>${{Jornada_datos.Datos_jornada.Efectivo_caja_inicio | Moneda}}</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">


                                        <div class="stat-text">Ingresos por delivery
                                            <!-- <a v-on:click="mensaje()" href="javascript:void"> 
                                                                    <i class="ti-info-alt"></i>
                                                                </a>-->
                                        </div>
                                        <div class="stat-digit">
                                            <h4>${{Jornada_datos.Total_ingresos_delivery | Moneda}}</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="stat-text">Ingresos por comandas
                                            <!-- <a v-on:click="mensaje()" href="javascript:void"> 
                                                                    <i class="ti-info-alt"></i>
                                                                </a>-->
                                        </div>
                                        <div class="stat-digit">
                                            <h4>${{Jornada_datos.Total_ingresos_comanda | Moneda}}</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="stat-text">Ingresos externos en caja
                                            <!-- <a v-on:click="mensaje()" href="javascript:void">
                                                                    <i class="ti-info-alt"></i>
                                                                </a> -->
                                        </div>
                                        <div class="stat-digit">
                                            <h4>${{Jornada_datos.Total_ingresos_caja | Moneda}}</h4>
                                        </div>

                                    </div>
                                    <div class="col-lg-2">
                                        <div class="stat-text">Egresos de caja
                                            <!-- <a v-on:click="mensaje()" href="javascript:void"> 
                                                                    <i class="ti-info-alt"></i>
                                                                </a>-->
                                        </div>
                                        <div class="stat-digit">
                                            <h4>${{Jornada_datos.Total_egresos_caja | Moneda}}</h4>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

        </div>
        <?php /// CABECERA BODY
        include "footer.php";
        ?>