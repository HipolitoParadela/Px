            <nav class="navbar-mobile d-print-none">
                <div class="container-fluid">
                    <ul class="navbar-mobile__list list-unstyled">
                        <li>
                            <a href="<?= base_url(); ?>dashboard">
                                <i class="fas fa-tachometer-alt"></i>Dashboard
                            </a>
                        </li>
                        <li>
                                <a href="<?= base_url(); ?>periodos">
                                    <i class="fa fa-calendar"></i>Períodos y cobros
                                </a>
                            </li>
                        <li>
                                <a href="<?= base_url(); ?>suscripciones">
                                <i class="fas fa-tags"></i>Servicios Suscripción
                                </a>
                            </li>
                        <li>
                            <a href="<?= base_url(); ?>proveedores">
                                <i class="fas fa-truck"></i>Proveedores
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url(); ?>compras">
                                <i class="fas fa-shopping-cart"></i>Compras
                            </a>
                        </li>
                        
                        <li>
                            <a href="<?= base_url(); ?>stock">
                                <i class="fas fa-list-alt"></i>Insumos / Inventario
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url(); ?>clientes">
                                <i class="fas fa-suitcase"></i>Clientes
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url(); ?>ventas">
                                <i class="fa fa-tags"></i>Ventas
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url();?>ventas/produccion">
                            <i class="fas fa-cog"></i></i>Producción
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url();?>fabricacion">
                                <i class="fas fa-barcode"></i>Productos propios
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url();?>stock/pruductosdereventa">
                                <i class="fas fa-barcode"></i>Productos de reventa
                            </a>
                        </li>
                        <li>
                            <a class="js-arrow" href="<?= base_url(); ?>finanzas/fondo">
                                <i class="fa fa-usd"></i>Fondo</a>
                        </li>
                        <li>
                            <a href="<?= base_url(); ?>usuarios">
                                <i class="fas fa-users"></i>Personal
                            </a>
                        </li>
                        <li>
                        <!-- <li>
                            <a href="<?= base_url(); ?>fabricacion/stockfabricados/?Id=1">
                                <i class="fas fa-retweet"></i>Stock de reserva
                            </a>
                        </li> -->
                        <li class="has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fas fa-archive"></i>Curriculums</a>
                            <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                <li>
                                    <a href="<?= base_url(); ?>curriculum">Con puestos potenciales</a>
                                </li>
                                <li>
                                    <a href="<?= base_url(); ?>curriculum/todos">Todos</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            </header>
            <!-- END HEADER MOBILE-->

            <!-- MENU SIDEBAR-->
            <aside class="menu-sidebar d-none d-lg-block d-print-none">
                <div class="logo">
                    <img src="<?php echo base_url(); ?>uploads/imagenes/logoazul.png">
                </div>
                <div class="menu-sidebar__content js-scrollbar1">
                    <nav class="navbar-sidebar">
                        <ul class="list-unstyled navbar__list">
                            <li>
                                <a href="<?= base_url(); ?>dashboard">
                                    <i class="fas fa-tachometer-alt"></i>Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url(); ?>periodos">
                                    <i class="fa fa-calendar"></i>Períodos y cobros
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url(); ?>suscripciones">
                                <i class="fas fa-tags"></i>Servicios Suscripción
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url(); ?>proveedores">
                                    <i class="fas fa-truck"></i>Proveedores
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url(); ?>compras">
                                    <i class="fas fa-shopping-cart"></i>Compras
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url(); ?>stock">
                                    <i class="fas fa-list-alt"></i>Insumos / Inventario
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url(); ?>clientes">
                                    <i class="fas fa-suitcase"></i>Clientes
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url(); ?>ventas">
                                    <i class="fa fa-tags"></i>Ventas
                                </a>
                            </li>
                            <li>
                            <a href="<?= base_url();?>ventas/produccion">
                            <i class="fas fa-cog"></i></i>Producción
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url();?>fabricacion">
                                <i class="fas fa-barcode"></i>Productos propios
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url();?>stock/pruductosdereventa">
                                <i class="fas fa-barcode"></i>Productos de reventa
                            </a>
                        </li>
                            <li>
                                <a class="js-arrow" href="<?= base_url(); ?>finanzas/fondo">
                                    <i class="fa fa-usd"></i>Fondo</a>
                            </li>
                            <li>
                                <a href="<?= base_url(); ?>usuarios">
                                    <i class="fas fa-users"></i>Personal
                                </a>
                            </li>
                           <!--  <li>
                                <a href="<?= base_url(); ?>fabricacion/stockfabricados/?Id=1">
                                    <i class="fas fa-retweet"></i>Stock de reserva
                                </a>
                            </li> -->


                            <li class="has-sub">
                                <a class="js-arrow" href="#">
                                    <i class="fas fa-archive"></i>Curriculums</a>
                                <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                    <li>
                                        <a href="<?= base_url(); ?>curriculum">Con puestos potenciales</a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url(); ?>curriculum/todos">Todos</a>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                    </nav>
                    <!-- ZOOM A LA PAGINA CON BOTONES -->
                    <!--function zoomIn()
                    {
                        var Page = document.getElementById('Body');
                        var zoom = parseInt(Page.style.zoom) + 10 +'%'
                        Page.style.zoom = zoom;
                        return false;
                    }
                    
                    function zoomOut()
                    {
                        var Page = document.getElementById('Body');
                        var zoom = parseInt(Page.style.zoom) - 10 +'%'
                        Page.style.zoom = zoom;
                        return false;
                    }-->
                </div>
            </aside>
            <!-- END MENU SIDEBAR-->