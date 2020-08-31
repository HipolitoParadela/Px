            <nav class="navbar-mobile">
                <div class="container-fluid">
                    <ul class="navbar-mobile__list list-unstyled">
                        <li>
                            <a href="<?= base_url();?>dashboard">
                                <i class="fas fa-tachometer-alt"></i>Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url();?>periodos">
                                <i class="fas fa-calendar"></i>Periodos
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url();?>usuarios">
                                <i class="fas fa-users"></i>Personal
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url();?>stock">
                                <i class="fas fa-list-alt"></i>Stock
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url();?>clientes">
                                <i class="fas fa-suitcase"></i>Clientes
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url();?>proveedores">
                                <i class="fas fa-truck"></i>Proveedores
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url();?>ordentrabajo">
                                <i class="fas fa-wrench"></i>Ordenes de trabajo
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url();?>fabricacion">
                                <i class="fas fa-barcode"></i>Productos propios
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url();?>compras">
                                <i class="fas fa-shopping-cart"></i>Compras
                            </a>
                        </li>
                        <!--<li class="has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fas fa-archive"></i>Curriculums</a>
                            <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                <li>
                                    <a href="<?= base_url();?>curriculum">Con puestos potenciales</a>
                                </li>
                                <li>
                                    <a href="<?= base_url();?>curriculum/todos">Todos</a>
                                </li>
                            </ul>
                        </li>-->
                    </ul>
                </div>
            </nav>
        </header>
        <!-- END HEADER MOBILE-->

        <!-- MENU SIDEBAR-->
        <aside class="menu-sidebar d-none d-lg-block">
            <div class="logo">
            <h1 align="center"> DEMO </h1>
                    
            </div>
            <div class="menu-sidebar__content js-scrollbar1">
                <nav class="navbar-sidebar">
                    <ul class="list-unstyled navbar__list">
                        <li>
                            <a href="<?= base_url();?>dashboard">
                                <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                        </li>
                        <li>
                            <a href="<?= base_url();?>periodos">
                                <i class="fas fa-calendar"></i>Periodos
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url();?>usuarios">
                                <i class="fas fa-users"></i>Personal</a>
                        </li>
                        <li>
                            <a href="<?= base_url();?>stock">
                                <i class="fas fa-list-alt"></i>Stock</a>
                        </li>
                        <li>
                            <a href="<?= base_url();?>clientes">
                                <i class="fas fa-suitcase"></i>Clientes</a>
                        </li>
                        <li>
                            <a href="<?= base_url();?>proveedores">
                                <i class="fas fa-truck"></i>Proveedores
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url();?>ordentrabajo">
                                <i class="fas fa-wrench"></i>Ordenes de trabajo
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url();?>fabricacion">
                                <i class="fas fa-barcode"></i>Productos propios
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url();?>compras">
                                <i class="fas fa-shopping-cart"></i>Compras
                            </a>
                        </li>
                        <!--<li class="has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fas fa-archive"></i>Curriculums</a>
                            <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                <li>
                                    <a href="<?= base_url();?>curriculum">Con puestos potenciales</a>
                                </li>
                                <li>
                                    <a href="<?= base_url();?>curriculum/todos">Todos</a>
                                </li>
                            </ul>
                        </li>-->
                        
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