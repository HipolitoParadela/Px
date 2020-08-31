<?php
// CABECERA
include "head.php";
// TOP
include "top.php";
include "menusidebar.php";
?>
<!-- PAGE CONTAINER-->
<div class="page-container">
    <!-- HEADER DESKTOP-->
    <?PHP include "header_desktop.php"; ?>
    <!-- HEADER DESKTOP-->

    <!-- MAIN CONTENT-->
    <div class="main-content" id="dashboard">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="overview-wrap">
                            <h2 class="title-1">Dasboard</h2>
                            <!--<button class="au-btn au-btn-icon au-btn--blue">
                                        <i class="zmdi zmdi-plus"></i>add item</button>-->
                        </div>
                    </div>
                </div>
                <div class="row m-t-25">

                    <div class="col-sm-6 col-lg-3">
                        <div class="overview-item overview-item--c1">
                            <div class="overview__inner">
                                <div class="overview-box clearfix">
                                    <div class="icon">
                                        <i class="fa fa-money "></i>
                                    </div>
                                    <div class="text">
                                        <h2>$ {{valorDolarHoy.libre}}</h2>
                                        <span>DOLAR HOY <em>valor estimado</em><br></span>
                                    </div>
                                </div>
                                <!-- <div class="overview-chart">
                                            <canvas id="widgetChart1"></canvas>
                                        </div> -->
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="overview-item overview-item--c3">
                            <div class="overview__inner">
                                <div class="overview-box clearfix">
                                    <div class="icon">
                                        <i class="fa fa-dollar-sign"></i>
                                    </div>
                                    <div class="text">
                                        <h2>{{listaMovimientosEfectivo.Total + listaMovimientosTransferencias.Total + listaMovimientosCheques.Total | Moneda}} </h2>
                                        <span>Fondos a la fecha</span>
                                    </div>
                                </div>
                                <!-- <div class="overview-chart">
                                            <canvas id="widgetChart3"></canvas>
                                        </div>-->
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="overview-item overview-item--c2">
                            <div class="overview__inner">
                                <div class="overview-box clearfix">
                                    <div class="icon">
                                        <i class="fa fa-dollar-sign"></i>
                                    </div>
                                    <div class="text">
                                        <h2>{{ganancias.Total_mes | Moneda}}</h2>
                                        <span>Ganancias de este mes</span>
                                    </div>
                                </div>
                                <!-- <div class="overview-chart">
                                    <canvas id="widgetChart2"></canvas>
                                </div> -->
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="overview-item overview-item--c4">
                            <div class="overview__inner">
                                <div class="overview-box clearfix">
                                    <div class="icon">
                                        <i class="fa fa-dollar-sign"></i>
                                    </div>
                                    <div class="text">
                                        <h2>{{ganancias.Total_anio | Moneda}}</h2>
                                        <span>Ganancias de este año</span>
                                    </div>
                                </div>
                                <!-- <div class="overview-chart">
                                    <canvas id="widgetChart4"></canvas>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="title-1 m-b-25">Ventas - En proceso</h2>
                        <div class="table-responsive table--no-card m-b-40">
                            <table class="table table-borderless table-striped table-earning">
                                <thead>
                                    <tr>
                                        <th>Identificador</th>
                                        <th>Estado Actual</th>
                                        <th>Empresa</th>
                                        <th>Vendedor</th>
                                        <th>Cliente</th>
                                        <th>Monto Venta</th>
                                        <th>Monto Abonado</th>
                                        <th>Saldo</th>
                                        <th>Iniciado</th>
                                        <th>Fin. estimada</th>
                                        <th>Días rest. est.</th>


                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="venta in listaVentas">
                                        <td>
                                            <h4>{{venta.Datos.Identificador_venta}}</h4>
                                        </td>
                                        <td v-if="venta.Datos.Estado == 1"><b>Diseño</b></td>
                                        <td v-if="venta.Datos.Estado == 2"><b>Imprenta / Producción</b></td>
                                        <td v-if="venta.Datos.Estado == 3"><b>Armado / Recorte</b></td>
                                        <td v-if="venta.Datos.Estado == 4"><b>Colocación</b></td>
                                        <td v-if="venta.Datos.Estado == 5"><b>Cobro</b></td>
                                        <td v-if="venta.Datos.Estado > 5"><b>Trabajo Terminado</b></td>
                                        <td>
                                            {{venta.Datos.Nombre_empresa}}
                                        </td>
                                        <td>{{venta.Datos.Nombre_vendedor}}</td>
                                        <td>
                                            {{venta.Datos.Nombre_cliente}}
                                        </td>
                                        <td align="center"><b>${{venta.Datos.Monto_cobrado}}</b></td>
                                        <td align="center"><b>${{venta.Total}}</b></td>
                                        <td align="center"><b>${{venta.Datos.Monto_cobrado - venta.Total}}</b></td>
                                        <td>{{venta.Datos.Fecha_venta | Fecha}}</td>

                                        <td>{{venta.Datos.Fecha_estimada_entrega | Fecha}}</td>
                                        <td>{{diferenciasEntre_fechas(null, venta.Datos.Fecha_estimada_entrega)}}</td>

                                        <td>
                                            <div class="table-data-feature">

                                                <a class="item" v-bind:href="'ventas/datos/?Id='+venta.Datos.Id" title="Ver todos los datos">
                                                    <i class="zmdi zmdi-mail-send"></i>
                                                </a>
                                                </button>

                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- <div class="col-lg-9">
                                <h2 class="title-1 m-b-25">Últimos movimientos de stock</h2>
                                <div class="au-card au-card--bg-blue au-card-top-countries m-b-40">
                                    <div class="au-card-inner">
                                        <div class="table-responsive">
                                            <table class="table table-top-countries">
                                                <tbody>
                                                    <tr>
                                                        <td><b>Producto</b></td>
                                                        <td><b>Movimiento</b></td>
                                                        <td><b>Cantidad</b></td>
                                                        <td><b>Descripcíon</b></td>
                                                        <td><b>Fecha</b></td>
                                                        <td><b>Registrado por</b></td>
                                                        <td><b></b></td>           
                                                    </tr>
                                                    <tr v-for="movimiento in listaMovimientos">
                                                        <td>{{movimiento.Nombre_item}}</td>
                                                        <td align="center">
                                                            <span v-show="movimiento.Tipo_movimiento == '2'"><i class="fa fa-chevron-circle-down"></i></span>
                                                            <span v-show="movimiento.Tipo_movimiento == '1'"><i class="fa fa-chevron-circle-up"></i></span>
                                                        </td>                                                                  
                                                        <td align="center"> {{movimiento.Cantidad}}</td>
                                                        <td>{{movimiento.Descripcion}}</td>
                                                        <td align="center">{{formatoFecha_hora(movimiento.Fecha_hora)}}</td>
                                                        <td>{{movimiento.Nombre}}</td>
                                                        <td>
                                                            <a class="item" v-bind:href="'stock/movimientos/?Id='+movimiento.Stock_id" title="Ver movimientos">
                                                                <i class="zmdi zmdi-mail-send"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                    <div class="col-lg-8">
                        <h2 class="title-1 m-b-25">Últimos cobros de suscripciones</h2>
                        <div class="au-card au-card--bg-blue au-card-top-countries m-b-40">
                            <div class="au-card-inner">
                                <div class="table-responsive">
                                    <table class="table table-top-countries">
                                        <tbody>
                                            <tr>
                                                <td><b>Servicio</b></td>
                                                <td><b>Cliente</b></td>
                                                <td><b>Monto</b></td>
                                                <td><b>Fecha</b></td>
                                                <td><b>Periodo</b></td>
                                                <td><b>Estado</b></td>
                                                <td><b></b></td>
                                            </tr>
                                            <tr v-for="cobro in listaCobrosSuscripcion">
                                                <td>{{cobro.Titulo_suscripcion}}</td>
                                                <td>{{cobro.Nombre_cliente}}</td>
                                                <td>{{cobro.Valor_cobro}}</td>
                                                <td>{{cobro.Fecha_pago | Fecha}}</td>
                                                <td>{{cobro.Identificador}}</td>
                                                <td>
                                                    <span v-if="cobro.Estado == '2'">Pago completo</span>
                                                    <span v-if="cobro.Estado == '1'">Pago parcial</span>
                                                </td>
                                                <td>{{cobro.Nombre}}</td>
                                                <td>
                                                    <a class="item" v-bind:href="'periodos/datos/?Id='+cobro.Periodo_id" title="Ver cobros">
                                                        <i class="zmdi zmdi-mail-send"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <h2 class="title-1 m-b-25">Últimas compras realizadas</h2>
                        <div class="au-card au-card--bg-blue au-card-top-countries m-b-40">
                            <div class="au-card-inner">
                                <div class="table-responsive">
                                    <table class="table table-top-countries">
                                        <tbody>
                                            <tr>
                                                <td><b>Proveedor</b></td>
                                                <td><b>Fecha</b></td>
                                                <td class="text-right"><b>Monto</b></td>
                                                <td><b></b></td>
                                            </tr>
                                            <tr v-for="compra in listaCompras">
                                                <td>{{compra.Nombre_proveedor}}</td>
                                                <td>{{compra.Fecha_compra | Fecha}}</td>
                                                <td class="text-right">$ {{sumarComrpra(compra.Neto, compra.No_gravado, compra.IVA) | Moneda}}</td>
                                                <td>
                                                    <a class="item" v-bind:href="'compras/datos/?Id='+compra.Id" title="Ver esta compra">
                                                        <i class="zmdi zmdi-mail-send"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <div class="row">
                            <div class="col-lg-6">
                                <div class="au-card recent-report">
                                    <div class="au-card-inner">
                                        <h3 class="title-2">recent reports</h3>
                                        <div class="chart-info">
                                            <div class="chart-info__left">
                                                <div class="chart-note">
                                                    <span class="dot dot--blue"></span>
                                                    <span>products</span>
                                                </div>
                                                <div class="chart-note mr-0">
                                                    <span class="dot dot--green"></span>
                                                    <span>services</span>
                                                </div>
                                            </div>
                                            <div class="chart-info__right">
                                                <div class="chart-statis">
                                                    <span class="index incre">
                                                        <i class="zmdi zmdi-long-arrow-up"></i>25%</span>
                                                    <span class="label">products</span>
                                                </div>
                                                <div class="chart-statis mr-0">
                                                    <span class="index decre">
                                                        <i class="zmdi zmdi-long-arrow-down"></i>10%</span>
                                                    <span class="label">services</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="recent-report__chart">
                                            <canvas id="recent-rep-chart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="au-card chart-percent-card">
                                    <div class="au-card-inner">
                                        <h3 class="title-2 tm-b-5">char by %</h3>
                                        <div class="row no-gutters">
                                            <div class="col-xl-6">
                                                <div class="chart-note-wrap">
                                                    <div class="chart-note mr-0 d-block">
                                                        <span class="dot dot--blue"></span>
                                                        <span>products</span>
                                                    </div>
                                                    <div class="chart-note mr-0 d-block">
                                                        <span class="dot dot--red"></span>
                                                        <span>services</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="percent-chart">
                                                    <canvas id="percent-chart"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="au-card au-card--no-shadow au-card--no-pad m-b-40">
                                    <div class="au-card-title" style="background-image:url('images/bg-title-01.jpg');">
                                        <div class="bg-overlay bg-overlay--blue"></div>
                                        <h3>
                                            <i class="zmdi zmdi-account-calendar"></i>Noticias del día</h3>
                                         <button class="au-btn-plus">
                                            <i class="zmdi zmdi-plus"></i>
                                        </button> 
                                    </div>
                                    <div class="au-task js-list-load">
                                        <div class="au-task__title">
                                            <p>Google Noticias</p>
                                        </div>
                                        <div class="au-task-list js-scrollbar3">
                                            <div class="au-task__item au-task__item--danger">
                                                <div class="au-task__item-inner">
                                                    <h5 class="task">
                                                        <a href="#">Meeting about plan for Admin Template 2018</a>
                                                    </h5>
                                                    <span class="time">10:00 AM</span>
                                                </div>
                                            </div>
                                            <div class="au-task__item au-task__item--warning">
                                                <div class="au-task__item-inner">
                                                    <h5 class="task">
                                                        <a href="#">Create new task for Dashboard</a>
                                                    </h5>
                                                    <span class="time">11:00 AM</span>
                                                </div>
                                            </div>
                                            <div class="au-task__item au-task__item--primary">
                                                <div class="au-task__item-inner">
                                                    <h5 class="task">
                                                        <a href="#">Meeting about plan for Admin Template 2018</a>
                                                    </h5>
                                                    <span class="time">02:00 PM</span>
                                                </div>
                                            </div>
                                            <div class="au-task__item au-task__item--success">
                                                <div class="au-task__item-inner">
                                                    <h5 class="task">
                                                        <a href="#">Create new task for Dashboard</a>
                                                    </h5>
                                                    <span class="time">03:30 PM</span>
                                                </div>
                                            </div>
                                            <div class="au-task__item au-task__item--danger js-load-item">
                                                <div class="au-task__item-inner">
                                                    <h5 class="task">
                                                        <a href="#">Meeting about plan for Admin Template 2018</a>
                                                    </h5>
                                                    <span class="time">10:00 AM</span>
                                                </div>
                                            </div>
                                            <div class="au-task__item au-task__item--warning js-load-item">
                                                <div class="au-task__item-inner">
                                                    <h5 class="task">
                                                        <a href="#">Create new task for Dashboard</a>
                                                    </h5>
                                                    <span class="time">11:00 AM</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="au-task__footer">
                                            <button class="au-btn au-btn-load js-load-btn">load more</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        -->
                <div class="row">
                    <div class="col-lg-5">
                        <div class="au-card au-card--no-shadow au-card--no-pad m-b-40">
                            <div class="au-card-title" style="background-image:url('https://static.iris.net.co/dinero/upload/images/2019/4/15/269729_1.jpg');">
                                <div class="bg-overlay "></div><!-- bg-overlay--blue -->
                                <h3>
                                    <i class="zmdi zmdi-comment-text"></i>Reportes de recursos humanos</h3>
                                <!-- <button class="au-btn-plus">
                                            <i class="zmdi zmdi-plus"></i>
                                        </button> -->
                            </div>
                            <div class="au-inbox-wrap js-inbox-wrap">
                                <div class="au-message js-list-load">
                                    <div class="au-message-list">

                                        <div v-for="reporte in listaSeguimientoPersonal" class="au-message__item">
                                            <div class="au-message__item-inner">
                                                <div class="au-message__item-text">
                                                    <div class="avatar-wrap">
                                                        <div class="avatar">
                                                            <img v-bind:src="reporte.Imagen">
                                                        </div>
                                                    </div>
                                                    <div class="text">
                                                        <h5 class="name">{{reporte.Nombre}}</h5>
                                                        <p>{{reporte.Descripcion}}</p>

                                                    </div>
                                                </div>
                                                <div class="au-message__item-time">
                                                    <span class="text-primary">{{reporte.Fecha | Fecha}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="au-message__footer">
                                                <button class="au-btn au-btn-load js-load-btn">load more</button>
                                            </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="au-card au-card--no-shadow au-card--no-pad m-b-40">
                            <div class="au-card-title" style="background-image:url('http://imuargentina.com.ar/wp-content/uploads/2019/03/Sin-título-1.jpg');">
                                <div class="bg-overlay "></div><!-- bg-overlay--blue -->
                                <h3>
                                    <i class="zmdi zmdi-comment-text"></i>Noticias del día</h3>
                                <!-- <button class="au-btn-plus">
                                            <i class="zmdi zmdi-plus"></i>
                                        </button> -->
                            </div>
                            <div class="au-inbox-wrap js-inbox-wrap">
                                <div class="au-message js-list-load">
                                    <div class="au-message__noti">
                                        <p>
                                            Selección de Google Noticias
                                        </p>
                                    </div>
                                    <div class="au-message-list">
                                        <div v-for="noticia in listaNoticias.articles" class="au-message__item">
                                            <div class="au-message__item-inner">
                                                <div class="au-message__item-text">
                                                    <div class="avatar-wrap">
                                                        <div class="avatar">
                                                            <img v-bind:src="noticia.urlToImage">
                                                        </div>
                                                    </div>
                                                    <div class="text">
                                                        <h5 class="name">{{noticia.title}}</h5>
                                                        <p>{{noticia.content}}</p>
                                                        <p><a target="_blank" v-bind:href="noticia.url">Leer más </a></p>
                                                    </div>
                                                </div>
                                                <div class="au-message__item-time">
                                                    <span>{{noticia.name}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="au-message__footer">
                                                <button class="au-btn au-btn-load js-load-btn">load more</button>
                                            </div> -->
                                </div>
                                <!-- <div class="au-chat">
                                            <div class="au-chat__title">
                                                <div class="au-chat-info">
                                                    <div class="avatar-wrap online">
                                                        <div class="avatar avatar--small">
                                                            <img src="images/icon/avatar-02.jpg" alt="John Smith">
                                                        </div>
                                                    </div>
                                                    <span class="nick">
                                                        <a href="#">John Smith</a>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="au-chat__content">
                                                <div class="recei-mess-wrap">
                                                    <span class="mess-time">12 Min ago</span>
                                                    <div class="recei-mess__inner">
                                                        <div class="avatar avatar--tiny">
                                                            <img src="images/icon/avatar-02.jpg" alt="John Smith">
                                                        </div>
                                                        <div class="recei-mess-list">
                                                            <div class="recei-mess">Lorem ipsum dolor sit amet, consectetur adipiscing elit non iaculis</div>
                                                            <div class="recei-mess">Donec tempor, sapien ac viverra</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="send-mess-wrap">
                                                    <span class="mess-time">30 Sec ago</span>
                                                    <div class="send-mess__inner">
                                                        <div class="send-mess-list">
                                                            <div class="send-mess">Lorem ipsum dolor sit amet, consectetur adipiscing elit non iaculis</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="au-chat-textfield">
                                                <form class="au-form-icon">
                                                    <input class="au-input au-input--full au-input--h65" type="text" placeholder="Type a message">
                                                    <button class="au-input-icon">
                                                        <i class="zmdi zmdi-camera"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div> -->
                            </div>
                        </div>
                    </div>
                </div>


                <?php
                // CABECERA
                include "footer.php";
                ?>

                </body>

                </html>
                <!-- end document-->