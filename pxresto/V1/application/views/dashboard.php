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
} elseif ($this->session->userdata('Rol_id') == 5) {
    include "navegadores/nav-bar-rol-delivery.php";
}
/// CABECERA BODY
include "header-body.php";
?>

<body>
    <div class="content-wrap" id="dashboard">
        <div class="main">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-lg-8 p-r-0 title-margin-right">
                        <div class="page-header">
                            <div class="page-title">
                                <h1>Dashboard <span> </span></h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="#">
                                            <?php  echo $Info_negocio["Nombre_negocio"]; ?>
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active">Dashboard</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->

                <section id="main-content">

                    <?php if ($this->session->userdata('Rol_id') == 4) {
                        echo ''; /// inicio condicional para Roles PHP 
                    ?>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="card">
                                    <div class="stat-widget-one">
                                        <div class="stat-icon dib"><i class="ti-money color-success border-success"></i></div>
                                        <div class="stat-content dib">
                                            <div class="stat-text">Ingresos por ventas hoy
                                                <a v-on:click="mensaje()" href="javascript:void">
                                                    <i class="ti-info-alt"></i>
                                                </a>
                                            </div>
                                            <div class="stat-digit">${{valorVentasHoy}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="card">
                                    <div class="stat-widget-one">
                                        <div class="stat-icon dib"><i class="ti-money color-success border-success"></i></div>
                                        <div class="stat-content dib">
                                            <div class="stat-text">Ingresos por ventas ayer <a v-on:click="mensaje()" href="javascript:void">
                                                    <i class="ti-info-alt"></i>
                                                </a></div>
                                            <div class="stat-digit">${{valorVentasAyer}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="card">
                                    <div class="stat-widget-one">
                                        <div class="stat-icon dib"><i class="ti-money color-success border-success"></i></div>
                                        <div class="stat-content dib">
                                            <div class="stat-text">Ingresos por ventas este mes <a v-on:click="mensaje()" href="javascript:void">
                                                    <i class="ti-info-alt"></i>
                                                </a></div>
                                            <div class="stat-digit">${{valorVentasMes}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="card">
                                    <div class="stat-widget-one">
                                        <div class="stat-icon dib"><i class="ti-money color-success border-success"></i></div>
                                        <div class="stat-content dib">
                                            <div class="stat-text">Ingresos por ventas de este año <a v-on:click="mensaje()" href="javascript:void">
                                                    <i class="ti-info-alt"></i>
                                                </a></div>
                                            <div class="stat-digit">${{valorVentasAnio}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-5">
                                <div class="card bg-primary">
                                    <?php echo $Info_negocio["Meteored"]; ?>
                                </div>
                                <div class="card">
                                    <div class="card-title">
                                        <h4>Minuto a minuto</h4>
                                    </div>
                                    <div class="card-body">
                                        <ul class="timeline">
                                            <li v-for="dato in listaTimeline.Datos">
                                                <div v-if="dato.Accion == 1" class="timeline-badge success"><i class="ti-export"></i></div>
                                                <div v-if="dato.Accion == 2" class="timeline-badge pink"><i class="ti-angle-right"></i></div>
                                                <div v-if="dato.Accion == 3" class="timeline-badge warning"><i class="ti-angle-double-right"></i></div>
                                                <div v-if="dato.Accion == 4" class="timeline-badge danger"><i class="ti-import"></i></div>
                                                <div v-if="dato.Accion == 5" class="timeline-badge info"><i class="ti-back-right"></i></div>
                                                <div v-if="dato.Accion == 6" class="timeline-badge success"><i class="ti-car"></i></div>
                                                <div v-if="dato.Accion == 7" class="timeline-badge warning"><i class="ti-car"></i></div>
                                                <div v-if="dato.Accion == 7" class="timeline-badge warning"><i class="ti-car"></i></div>
                                                <div class="timeline-panel">
                                                    <div class="timeline-heading">
                                                        <div class="timeline-body">
                                                            <h5 v-if="dato.Accion == 1" class="timeline-title">Apertura de mesa {{dato.Identificador}} | <span class="text-primary">{{dato.Nombre_moso}}</span></h5>
                                                            <h5 v-if="dato.Accion == 2" class="timeline-title">Entrada en mesa {{dato.Identificador}} | <span class="text-primary">{{dato.Nombre_moso}}</span></h5>
                                                            <h5 v-if="dato.Accion == 3" class="timeline-title">Menú en mesa {{dato.Identificador}} | <span class="text-primary">{{dato.Nombre_moso}}</span></h5>
                                                            <h5 v-if="dato.Accion == 4" class="timeline-title">Cierre de mesa {{dato.Identificador}} | <span class="text-primary">{{dato.Nombre_moso}}</span></h5>
                                                            <h5 v-if="dato.Accion == 5" class="timeline-title">Reapertura de mesa {{dato.Identificador}} | <span class="text-primary">{{dato.Nombre_moso}}</span></h5>
                                                            <h5 v-if="dato.Accion == 6" class="timeline-title">Pedido de delivery </h5>
                                                            <h5 v-if="dato.Accion == 7" class="timeline-title">Entrega de delivery </h5>
                                                            <h5 v-if="dato.Accion == 8" class="timeline-title">Delivery reabierto</h5>
                                                        </div>
                                                        <div class="timeline-body">
                                                            <p>{{formatoFecha_hora(dato.Hora)}}</p>
                                                        </div>
                                                    </div>
                                            </li>
                                        </ul>
                                        <p align="center">
                                            <input type="button" v-on:click="getTimeline(listaTimeline.Inicio, 'Prev')" value="< Anterior" :disabled="listaTimeline.Inicio == 0">
                                            <input type="button" v-on:click="getTimeline(listaTimeline.Inicio, 'Next')" value="Siguiente >">
                                        </p>
                                    </div>
                                </div>
                                <!-- /# card -->
                            </div>
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-title">
                                        <h4>Personal presente</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Nombre</th>
                                                        <th>Función</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="usuario in listaLogPresencia.usuariosPresentes">
                                                        <td>
                                                            <div class="round-img">
                                                                <img v-if="usuario.Imagen != null" v-bind:src="'<?php echo base_url(); ?> uploads/imagenes/'+usuario.Imagen" alt="">
                                                                <!-- <img v-else  src="<?php echo base_url(); ?>uploads/imagenes/agregarimagen.jpg" alt=""> -->
                                                            </div>
                                                        </td>
                                                        <td>{{usuario.Nombre}}</td>
                                                        <td><span>{{usuario.Nombre_rol}}</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <?php if ($this->session->userdata('Rol_id') > 3) {
                                    echo ''; /// inicio condicional para Roles PHP 
                                ?>
                                    <div class="card">
                                        <div class="card-title">
                                            <h4>Ingresos y egresos de personal</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th>#</th>
                                                            <th>Nombre</th>
                                                            <th>Función</th>
                                                            <th>Hora y Fecha</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="dato in listaLogPresencia.Datos">
                                                            <td v-if="dato.Accion == 1"><span class="badge badge-success"><i class="ti-angle-up"></i></span></td>
                                                            <td v-if="dato.Accion == 0"><span class="badge badge-warning"><i class="ti-angle-down"></i></span></td>
                                                            <td>
                                                                <div class="round-img">
                                                                    <img v-if="dato.Imagen != null" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+dato.Imagen" alt="">
                                                                    <img v-else src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" alt="">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <span class="text-success" v-if="dato.Accion == 1">{{dato.Nombre_usuario}}</span>
                                                                <span class="text-warning" v-if="dato.Accion == 0">{{dato.Nombre_usuario}}</span>
                                                            </td>
                                                            <td><span>{{dato.Nombre_rol}}</span></td>
                                                            <td><span>{{formatoFecha_hora(dato.Fecha_hora)}}</span></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <p align="center">
                                                <input type="button" v-on:click="getListadoLogPresencia(listaLogPresencia.Inicio, 'Prev')" value="< Anterior" :disabled="listaLogPresencia.Inicio == 0">
                                                <input type="button" v-on:click="getListadoLogPresencia(listaLogPresencia.Inicio, 'Next')" value="Siguiente >">
                                            </p>
                                        </div>
                                        <p class="text-info" align="right"><a href="../restaurant/controlpresencia">Marcar ingreso/egreso de personal</a> </p>

                                    </div>
                                <?php '';
                                } /// fin condicional para Roles PHP
                                ?>
                            </div>
                            <div class="col-lg-3">
                                <div class="card">
                                    <?php echo $Info_negocio["Facebook"]; ?>
                                </div>

                                <!--
                                <div class="card">
                                    <div class="testimonial-widget-one p-17">
                                        <div class="testimonial-widget-one owl-carousel owl-theme">
                                            <div class="item">
                                                <div class="testimonial-content">
                                                    <div class="testimonial-text">
                                                        <i class="fa fa-quote-left"></i> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                                                        exercitation. consectetur adipisicing elit.
                                                        <i class="fa fa-quote-right"></i>
                                                    </div>
                                                    <img class="testimonial-author-img" src="assets/images/avatar/1.jpg" alt="" />
                                                    <div class="testimonial-author">TYRION LANNISTER</div>
                                                    <div class="testimonial-author-position">Founder-Ceo. Dell Corp</div>
                                                </div>
                                            </div>

                                            <div class="item">
                                                <div class="testimonial-content">
                                                    <div class="testimonial-text">
                                                        <i class="fa fa-quote-left"></i> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                                                        exercitation. consectetur adipisicing elit.
                                                        <i class="fa fa-quote-right"></i>
                                                    </div>
                                                    <img class="testimonial-author-img" src="assets/images/avatar/1.jpg" alt="" />
                                                    <div class="testimonial-author">TYRION LANNISTER</div>
                                                    <div class="testimonial-author-position">Founder-Ceo. Dell Corp</div>
                                                </div>
                                            </div>
                                            <div class="item">
                                                <div class="testimonial-content">
                                                    <div class="testimonial-text">
                                                        <i class="fa fa-quote-left"></i> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                                                        exercitation. consectetur adipisicing elit.
                                                        <i class="fa fa-quote-right"></i>
                                                    </div>
                                                    <img class="testimonial-author-img" src="assets/images/avatar/1.jpg" alt="" />
                                                    <div class="testimonial-author">TYRION LANNISTER</div>
                                                    <div class="testimonial-author-position">Founder-Ceo. Dell Corp</div>
                                                </div>
                                            </div>
                                            <div class="item">
                                                <div class="testimonial-content">
                                                    <div class="testimonial-text">
                                                        <i class="fa fa-quote-left"></i> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                                                        exercitation. consectetur adipisicing elit.
                                                        <i class="fa fa-quote-right"></i>
                                                    </div>
                                                    <img class="testimonial-author-img" src="assets/images/avatar/1.jpg" alt="" />
                                                    <div class="testimonial-author">TYRION LANNISTER</div>
                                                    <div class="testimonial-author-position">Founder-Ceo. Dell Corp</div>
                                                </div>
                                            </div>
                                            <div class="item">
                                                <div class="testimonial-content">
                                                    <div class="testimonial-text">
                                                        <i class="fa fa-quote-left"></i> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                                                        exercitation. consectetur adipisicing elit.
                                                        <i class="fa fa-quote-right"></i>
                                                    </div>
                                                    <img class="testimonial-author-img" src="assets/images/avatar/1.jpg" alt="" />
                                                    <div class="testimonial-author">TYRION LANNISTER</div>
                                                    <div class="testimonial-author-position">Founder-Ceo. Dell Corp</div>
                                                </div>
                                            </div>
                                            <div class="item">
                                                <div class="testimonial-content">
                                                    <div class="testimonial-text">
                                                        <i class="fa fa-quote-left"></i> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                                                        exercitation. consectetur adipisicing elit.
                                                        <i class="fa fa-quote-right"></i>
                                                    </div>
                                                    <img class="testimonial-author-img" src="assets/images/avatar/1.jpg" alt="" />
                                                    <div class="testimonial-author">TYRION LANNISTER</div>
                                                    <div class="testimonial-author-position">Founder-Ceo. Dell Corp</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">

                                    <iframe src="https://calendar.google.com/calendar/embed?title=Planificaciones&amp;height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=hparadela67%40gmail.com&amp;color=%232952A3&amp;ctz=America%2FArgentina%2FBuenos_Aires" style="border-width:0" width="100%" height="600" frameborder="0" scrolling="no"></iframe>

                                </div>-->
                                <!-- /# card -->
                            </div>

                            <!-- /# column -->






                            </row>
                            <!-- /# row -->
                            <div class="row">
                                <div class="col-lg-4">

                                    </row>
                                    <!-- /# row -->
                </section>
            </div>
        <?php '';
                    } /// fin condicional para Roles PHP
        ?>
        </div>
        <?php /// FOOTER
        include "footer.php";
        ?>