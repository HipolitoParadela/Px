<?php
// CABECERA
include "head.php";
// TOP
include "top.php";
include "menusidebar.php";
?>
<!-- PAGE CONTAINER-->
<div class="page-container" id="app">
    <!-- HEADER DESKTOP-->
    <?PHP include "header_desktop.php"; ?>
    <!-- HEADER DESKTOP-->

    <!-- MAIN CONTENT-->
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!-- DATA TABLE -->
                        <h3 class="title-5 m-b-35">Control de stock</h3>

                        <div class="table-data__tool">
                            <div class="table-data__tool-left">
                                <div class="rs-select2--light">
                                    <select class="form-control-sm form-control" v-model="filtro_categoria" v-on:change="getListadoStock(filtro_categoria)">
                                        <option selected="selected" v-bind:value="0">Todas las categorías</option>
                                        <option v-for="categoriaSeleccionada in listaCategorias" v-bind:value="categoriaSeleccionada.Id">{{categoriaSeleccionada.Nombre_categoria}}</option>
                                    </select>
                                    <div class="dropDownSelect2"></div>
                                </div>

                                <div class="rs-select2--light rs-select2--sm">
                                    <input type="text" class="form-control-sm form-control" placeholder="Buscar item" v-model="buscar">
                                </div>
                                <!--<button class="au-btn-filter"><i class="zmdi zmdi-filter-list"></i>Filtros</button>-->
                            </div>
                            <div class="table-data__tool-right">
                                <button class="au-btn au-btn-icon au-btn--green au-btn--small" data-toggle="modal" data-target="#stockmodal" v-on:click="limpiarFormularioStock()">
                                    <i class="zmdi zmdi-plus"></i>Nuevo item</button>

                                <button class="au-btn au-btn-icon au-btn--blue au-btn--small" data-toggle="modal" data-target="#categoriaModal" v-on:click="limpiarFormularioCategoria()">
                                    <!--<i class="zmdi zmdi-plus"></i>-->Categorias</button>

                                <!--<div class="rs-select2--dark rs-select2--sm rs-select2--dark2">
                                            <select class="js-select2" name="type">
                                                <option selected="selected">Más</option>
                                                <option value="">Option 1</option>
                                                <option value="">Option 2</option>
                                            </select>
                                            <div class="dropDownSelect2"></div>
                                        </div>-->
                            </div>
                        </div>
                        <div class="table-responsive table-responsive-data2">
                            <table class="table table-data2" id="table2excel">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Nombre</th>
                                        <th>Categoría</th>
                                        <th>Cant. Actual</th>
                                        <th>Cant. Ideal</th>
                                        <th>Monto</th>

                                        <!-- <th width="200">Ingreso/Egreso stock</th> -->
                                        <th>Reportar egreso</th>
                                        <th>Última Modificación</th>
                                        <th>
                                            <form class="form-horizontal" action="<?php echo base_url(); ?>exportar" method="post" name="f1" id="f1">
                                                <input type="hidden" name="tabla" id="tabla">
                                                <button onclick="myFunction()" class="btn btn-outline-primary btn-sm" type="submit">
                                                    <i class="fa fa-download"></i> Descargar excel
                                                </button>
                                            </form>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="tr-shadow" v-for="(stock, index) in buscarStock">
                                        <td>
                                            <div class="round-img">
                                                <a href="#modalFotoItem" data-toggle="modal" v-on:click="editarFormularioItemFoto(stock)">
                                                    <img v-if="stock.Imagen != null" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+stock.Imagen" width="60px">
                                                    <img v-else src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" width="50px" alt="">
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <a v-bind:href="'stock/movimientos/?Id='+stock.Id" class="btn btn-link">
                                                {{stock.Nombre_item}}
                                            </a>
                                        </td>
                                        <td>{{stock.Nombre_categoria}}</td>

                                        <td>
                                            <h2 v-bind:class="classAlertaStock(stock.Cant_actual, stock.Cant_ideal)" align="center">{{stock.Cant_actual | Moneda}}</h2>
                                        </td>
                                        <td align="center">{{stock.Cant_ideal | Moneda}} {{stock.Unidad_medida}}</td>
                                        <td>
                                            <p class="text-success" align="center">${{stock.Precio * stock.Cant_actual | Moneda}}</p>
                                        </td>
                                        <!-- <td width="500" bgcolor="#F2F2F2">
                                            <div class="input-group">
                                                <input size="6" type="number" class="form-control" v-model="cantMovimientoStock[index]">
                                                <input type="text" placeholder="Descripción" class="form-control" v-model="descripcionMovimiento[index]" :disabled="cantMovimientoStock[index] == null">
                                                <div class="input-group-btn">
                                                    <button class="btn btn-warning" v-on:click="movimientoStock(stock.Id, cantMovimientoStock[index], descripcionMovimiento[index])" :disabled="cantMovimientoStock[index] == null"><i class="fa fa-save"></i></button>
                                                </div>
                                            </div>
                                        </td> -->
                                        <td>
                                            <button v-on:click="editarStockProducto(stock)" class="btn btn-info" data-toggle="modal" data-target="#egresoModal" data-placement="top" title="Reportar egreso">
                                                <i class="fa fa-wrench "></i>
                                            </button>
                                        </td>
                                        <td>{{stock.Fecha_hora | FechaTimestampBaseDatos}}</td>
                                        <td>
                                            <div class="table-data-feature">

                                                <a class="item" v-bind:href="'stock/movimientos/?Id='+stock.Id" title="Ver movimientos">
                                                    <i class="zmdi zmdi-mail-send"></i>
                                                </a>
                                                <button class="item" v-on:click="editarFormulariostock(stock)" data-toggle="modal" data-target="#stockmodal" data-placement="top" title="Edición rápida">
                                                    <i class="zmdi zmdi-edit"></i>
                                                </button>

                                                <button v-on:click="desactivarStock(stock.Id)" class="item" data-toggle="tooltip" data-placement="top" title="Borrar">
                                                    <i class="zmdi zmdi-delete"></i>
                                                </button>

                                            </div>
                                        </td>
                                    <tr class="spacer"></tr>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <!-- END DATA TABLE -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- modal stock -->
    <div class="modal fade" id="stockmodal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollmodalLabel">Formulario de stock</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal" action="post" v-on:submit.prevent="crearStock()">
                    <div class="modal-body">
                        <div class="horizontal-form">
                            <div class="form-group">
                                <label class=" form-control-label">Nombre del item</label> <input type="text" class="form-control" placeholder="" v-model="stockDato.Nombre_item">
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Precio de costo por unidad</label>
                                <input type="number" class="form-control" placeholder="" v-model="stockDato.Precio">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Categoría</label>
                                <select class="form-control" v-model="stockDato.Categoria_id">
                                    <option value="0">Sin categoría</option>
                                    <option v-for="categoria in listaCategorias" v-bind:value="categoria.Id">{{categoria.Nombre_categoria}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Unidad de medida</label>
                                <select class="form-control" v-model="stockDato.Unidad_medida">
                                    <option value="S/N">Sin identificar</option>
                                    <option value="mts">Metros</option>
                                    <option value="cm">Centimetros</option>
                                    <option value="kg">Kilogramos</option>
                                    <option value="g">Gramos</option>
                                    <option value="lts">Litros</option>
                                    <option value="Unidades">Unidad individual</option>
                                    <option value="Pack/Caja">Pack o Caja</option>
                                    
                                </select>
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Cantidad ideal</label>
                                <input type="number" class="form-control" placeholder="" v-model="stockDato.Cant_ideal">
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Descripción</label>
                                <textarea class="form-control" placeholder="" v-model="stockDato.Descripcion"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">{{texto_boton}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal stock -->
    <!-- Modal Usuarios Fotos-->
    <div class="modal fade" id="modalFotoItem" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalItemsFoto">Fotografía de {{stockFoto.Nombre_item}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p aling="center">
                        <img v-if="stockFoto.Imagen != null" class="avatar_grande" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+stockFoto.Imagen" alt="">
                        <img v-else class="avatar_grande" src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" alt="">
                    </p>
                    <hr>
                    <div class="horizontal-form">
                        <!-- <form class="form-horizontal" action="post" enctype="multipart/form-data" v-on:submit.prevent="crearUsuarios()">  -->
                        <form class="form-horizontal" action="post" enctype="multipart/form-data" v-on:submit.prevent="upload_foto_stock(stockFoto.Id)">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <input @change="archivoSeleccionado" type="file" class="form-control" name="Imagen">
                                </div>
                            </div>
                            <p v-show="preloader == 1">
                                <img src="http://grupopignatta.com.ar/images/preloader.gif" alt="">
                            </p>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-12">
                                    <button type="submit" class="btn btn-success">{{texto_boton}} imagen</button>
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
    <!-- modal categorias -->
    <div class="modal fade" id="categoriaModal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollmodalLabel">Listado de categorías</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <table class="table table-borderless table-striped table-earning">
                        <thead>
                            <tr>
                                <th>Nombre categoría</th>
                                <th>Descripción</th>
                                <th> </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="categoriaDatos in listaCategorias">
                                <td>{{categoriaDatos.Nombre_categoria}}</td>
                                <td>{{categoriaDatos.Descripcion}}</td>
                                <td>
                                    <button class="item" v-on:click="editarFormularioCategoria(categoriaDatos)" title="Editar">
                                        <i class="zmdi zmdi-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <form class="form-horizontal" action="post" v-on:submit.prevent="crearCategoria()">
                    <div class="modal-body">
                        <div class="horizontal-form">
                            <div class="form-group">
                                <label class=" form-control-label">Nombre de la categoría</label> <input type="text" class="form-control" v-model="categoriaDatos.Nombre_categoria">
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Descripción</label>
                                <textarea class="form-control" rows="5" v-model="categoriaDatos.Descripcion"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">{{texto_boton}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal categorias -->
    <!-- modal ORDEN TRABAJO SALIDA STOCK -->
    <div class="modal fade" id="egresoModal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollmodalLabel">Egreso de stock: {{egresoDato.Nombre_item}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal" action="post" v-on:submit.prevent="movimientoStock_v2()">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label">Orden de trabajo en la que se utilizará esta pieza/producto</label>
                            <select class="form-control" v-model="egresoDato.Venta_id" required>
                                <option value="0">No vincular a ninguna orden</option>
                                <option v-for="venta in listaVentas" v-bind:value="venta.Datos.Id">{{venta.Datos.Identificador_venta}} para {{venta.Datos.Nombre_cliente}}</option>
                            </select>
                        </div>
                        <div class="horizontal-form">
                            <div class="form-group">
                                <label class=" form-control-label">Cantidad retirada</label>
                                <input type="number" min="1" class="form-control" v-model="egresoDato.Cantidad" required>
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Descripción del egreso</label>
                                <textarea class="form-control" rows="5" v-model="egresoDato.Descripcion_egreso"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">{{texto_boton}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal ORDEN TRABAJO SALIDA STOCK -->
    <!-- END MAIN CONTENT-->
    <!-- END PAGE CONTAINER-->




    <?php
    // CABECERA
    include "footer.php";
    ?>

    </body>

    </html>
    <!-- end document-->