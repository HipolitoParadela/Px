<script>
    /// SCRIPT ESPECIAL PARA PÁGINAS QUE SE HABREN SIN LOGUEARSE
    var Nombre = '';
    var Id = '';
    var Rol_id = '';
    var Negocio_id = '';
    var Tipo_suscripcion = '';
</script>

<?php
// CABECERA

include "header.php";
/*/// NAVEGADOR SIDEBAR
if ($this->session->userdata('Rol_id') == 4) {
    include "navegadores/nav-bar-rol-4.php";
} elseif ($this->session->userdata('Rol_id') == 3) {
    include "navegadores/nav-bar-rol-3.php";
} elseif ($this->session->userdata('Rol_id') == 2) {
    include "navegadores/nav-bar-rol-2.php";
}
/// CABECERA BODY
include "header-body.php"; */
?>

<body>
    <div class="content-wrap" id="pedido_comanda">
        <div class="main">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-lg-8 p-r-0 title-margin-right">
                        <div class="page-header">
                            <div class="page-title">
                                <h1>Carta digital</h1>
                                <p>Mesa {{datosComanda.Identificador}}, <span> {{datosComanda.Nombre_cliente}}, atendidos por {{datosComanda.Nombre_moso}}, {{ datosComanda.Fecha | Fecha }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /# row -->

                <section id="main-content">
                    <div class="row">


                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 1 }" href="#" v-on:click="mostrar = 1">Carta Digital</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 2 }" href="#" v-on:click="obtenerlistadoItemsPedidos()">Mi pedido</a>
                            </li>
                        </ul>
                    </div>

                    <div class="row" v-show="mostrar == 1">
                        <!-- Carta Virtual -->
                        <div class="col-lg-6">
                            <select class="form-control" v-model="categoriaItem" v-on:change="obtenerlistadoItemsCarta()">
                                <option value="0">Todos</option>
                                <option v-for="categoria in listaCategorias" v-bind:value="categoria.Id">{{categoria.Nombre_categoria}}</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <div class="input-group input-group-default">
                                <span class="input-group-btn"><button class="btn btn-primary" type="submit"><i class="ti-search"></i></button></span>
                                <input type="text" class="form-control" placeholder="Buscar producto" v-model="buscar">
                            </div>
                        </div>
                        <div class="col-sm-3 col-6" v-for="item in buscarItems">
                            <img v-if="item.Imagen != null" class="card-img-top" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+item.Imagen" alt="Card image cap">
                            <div class="card">

                                <div class="card-body">
                                    <h3 align="center">{{item.Nombre_item}}</h3>
                                    <p align="center" class="card-text">{{item.Descripcion }}</p>
                                    <h4 align="center">${{item.Precio_venta | Moneda}}</h4>

                                </div>

                            </div>
                            <button type="button" class="btn btn-warning btn-block" v-on:click="addItem(item.Id, item.Nombre_item)" >Añadir al pedido</button>

                        </div>

                    </div>
            </div>
            <div class="row" v-show="mostrar == 2">
                <!-- Pedidos realizados -->
                <div class="col-lg-6">
                    <div class="card p-0">
                        <div class="stat-widget-three">
                            <div class="stat-icon bg-primary">
                                <i class="ti-tag"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-digit">{{listadoItemsPedidos.length}}</div>
                                <div class="stat-text">Items pedidos</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card p-0">
                        <div class="stat-widget-three">
                            <div class="stat-icon bg-warning">
                                <i class="ti-money"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-digit">$ {{sumarCuenta(listadoItemsPedidos) | Moneda}}</div>
                                <div class="stat-text">Cuenta al momento</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="bootstrap-data-table-panel">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th align="center" width="120px">Precio</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in listadoItemsPedidos">
                                            <td>{{item.Nombre_item}}</td>
                                            <td align="center">${{item.Precio_venta}}</td>
                                            <td v-if="item.Estado == 0"> A la espera </td>
                                            <td v-if="item.Estado == 1"> Entregado </td>
                                        </tr>
                                    </tbody>
                                    <th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    </th>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /# card -->
                </div>


            </div>
            </section>
        </div>
    </div>
    <?php /// CABECERA BODY
    include "footer.php";
    ?>