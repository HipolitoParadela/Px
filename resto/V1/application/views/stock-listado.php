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
                                <h1>Stock, <span>Listado</span></h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Stock</a></li>
                                    <li class="breadcrumb-item active">Listado</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->

                <div class="main-content">
                    <div class="section__content section__content--p30">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- DATA TABLE -->

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="rs-select2--light">
                                                <select class="form-control" v-model="filtro_categoria" v-on:change="getListadoStock(filtro_categoria)">
                                                    <option selected="selected" v-bind:value="0">Todas las categorías</option>
                                                    <option v-for="categoriaSeleccionada in listaCategorias" v-bind:value="categoriaSeleccionada.Id">{{categoriaSeleccionada.Nombre_categoria}}</option>
                                                </select>
                                                <div class="dropDownSelect2"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="rs-select2--light rs-select2--sm">
                                                <input type="text" class="form-control-sm form-control" placeholder="Buscar item" v-model="buscar">
                                            </div>
                                            <!--<button class="au-btn-filter"><i class="zmdi zmdi-filter-list"></i>Filtros</button>-->
                                        </div>
                                        <div class="col-md-4">
                                            <button class="btn btn-success btn-flat m-b-10 m-l-5" data-toggle="modal" data-target="#stockmodal" v-on:click="limpiarFormularioStock()">
                                                Nuevo item</button>

                                            <button class="btn btn-info btn-flat m-b-10 m-l-5" data-toggle="modal" data-target="#categoriaModal" v-on:click="limpiarFormularioCategoria()">
                                                <!--<i class="zmdi zmdi-plus"></i>-->Gestionar Categorias</button>

                                            <button class="btn btn-danger btn-flat m-b-10 m-l-5" data-toggle="modal" data-target="#enfaltaModal" v-on:click="getProductosFalta(filtro_categoria)">
                                                <!--<i class="zmdi zmdi-plus"></i>-->Productos en falta</button>
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
                                            <thead class="card-header">
                                                <tr>
                                                    <th></th>
                                                    <th>Nombre</th>
                                                    <th>Categoría</th>
                                                    <th>Cant. Actual</th>
                                                    <th>Precio Costo</th>
                                                    <th>Valor total</th>
                                                    <th>Cant. Ideal</th>
                                                    <th>Medida</th>
                                                    <!--<th width="200">Ingreso/Egreso stock</th>-->
                                                    <th>Movimiento</th>
                                                    <th>Última Modificación</th>
                                                    <th>
                                                        <!-- <form class="form-horizontal" action="<?php echo base_url(); ?>exportar" method="post" name="f1" id="f1">
                                                        <input type="hidden" name="tabla" id="tabla">
                                                        <button onclick="myFunction()" class="btn btn-outline-primary btn-sm" type="submit">
                                                            <i class="fa fa-download"></i> Descargar excel
                                                        </button>
                                                    </form> -->
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
                                                        <a v-bind:href="'stock/movimientos/?Id='+stock.Id" class="btn btn-default btn-rounded m-b-10">
                                                            {{stock.Nombre_item}}
                                                        </a>
                                                    </td>
                                                    <td>{{stock.Nombre_categoria}}</td>

                                                    <td>
                                                        <h2 v-bind:class="classAlertaStock(stock.Cant_actual, stock.Cant_ideal)" align="center">{{stock.Cant_actual}}</h2>
                                                    </td>
                                                    <td align="center">${{stock.Precio_costo | Moneda}} </td>
                                                    <td align="center">${{stock.Cant_actual * stock.Precio_costo  | Moneda}} </td>

                                                    <td align="center">{{stock.Cant_ideal}} </td>
                                                    <td align="center">{{stock.Unidad_medida}} </td>
                                                    <!--<td width= "500" bgcolor="#F2F2F2">
                                                    <div class="input-group">
                                                        <input size="6" type="number" class="form-control" v-model="cantMovimientoStock[index]">
                                                        <input type="text" placeholder="Descripción" class="form-control" v-model="descripcionMovimiento[index]" :disabled="cantMovimientoStock[index] == null">
                                                        <div class="input-group-btn">
                                                            <button class="btn btn-warning" v-on:click="movimientoStock(stock.Id, cantMovimientoStock[index], descripcionMovimiento[index])" :disabled="cantMovimientoStock[index] == null"><i class="fa fa-save"></i></button>
                                                        </div>
                                                    </div>
                                                </td>-->
                                                    <td>
                                                        <button v-on:click="editarStockProducto(stock)" class="btn btn-success" data-toggle="modal" data-target="#ingresoModal" data-placement="top" title="Reportar ingreso">
                                                            <i class="fa fa-chevron-circle-up"></i>
                                                        </button>
                                                        <button v-on:click="editarStockProducto(stock)" class="btn btn-danger" data-toggle="modal" data-target="#egresoModal" data-placement="top" title="Reportar egreso">
                                                            <i class="fa fa-chevron-circle-down"></i>
                                                        </button>
                                                    </td>
                                                    <td>{{stock.Fecha_hora | FechaTimestampBaseDatos}}</td>
                                                    <td>
                                                        <div class="table-data-feature">

                                                            <!-- <a class="item" v-bind:href="'stock/movimientos/?Id='+stock.Id" title="Ver movimientos">
                                                                <i class="zmdi zmdi-mail-send"></i>
                                                            </a> -->
                                                            <button class="item" v-on:click="editarFormulariostock(stock)" data-toggle="modal" data-target="#stockmodal" data-placement="top" title="Edición rápida">
                                                                <i class="ti-pencil-alt"></i>
                                                            </button>

                                                            <button v-on:click="desactivarStock(stock.Id)" class="item" data-toggle="tooltip" data-placement="top" title="Borrar">
                                                                <i class="ti-close"></i>
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
                                            <label class=" form-control-label">Nombre del item</label>
                                            <input type="text" class="form-control" placeholder="" v-model="stockDato.Nombre_item" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Categoría</label>
                                            <select class="form-control" v-model="stockDato.Categoria_id" required>
                                                <option value="0">Sin categoría</option>
                                                <option v-for="categoria in listaCategorias" v-bind:value="categoria.Id">{{categoria.Nombre_categoria}}</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class=" form-control-label">Unidades de Medida</label>
                                            <select class="form-control" v-model="stockDato.Unidad_medida" required>
                                                <option value="kg">Kg</option>
                                                <option value="g">Gramo</option>
                                                <option value="Litro">Litro</option>
                                                <option value="Un">Unidad</option>
                                                <option value="Caja/Pack">Caja/Pack</option>
                                                <option value="Sin Definir">Sin definir</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class=" form-control-label">Cantidad inicial</em></label>
                                            <input type="number" class="form-control" placeholder="" v-model="stockDato.Cant_actual" required> <!-- :disabled="stockDato.Id" -->
                                        </div>
                                        <div class="form-group">
                                            <label class=" form-control-label">Cantidad ideal</label>
                                            <input type="number" class="form-control" placeholder="" v-model="stockDato.Cant_ideal" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Precio Costo</label>
                                            <div class="col-sm-10">
                                                <input type="number" class="form-control" placeholder="" v-model="stockDato.Precio_costo" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            ¿Este producto puede aparecer en la carta?<br>
                                            Si <input type="radio" name="Apto_carta" value="1" v-model="stockDato.Apto_carta" required>
                                            No <input type="radio" name="Apto_carta" value="0" v-model="stockDato.Apto_carta" required>
                                        </div>
                                        <div v-if="stockDato.Apto_carta == 1" class="form-group">
                                            <label class="col-sm-2 control-label">Precio Venta</label>
                                            <div class="col-sm-10">
                                                <input type="number" class="form-control" placeholder="" v-model="stockDato.Precio_venta" required>
                                            </div>
                                        </div>
                                        <div v-if="stockDato.Apto_carta == 1" class="form-group">
                                            <label class="col-sm-2 control-label">Apto delivery</label>
                                            <div class="col-sm-10">
                                                Si <input type="radio" name="Apto_delivery" value="1" v-model="stockDato.Apto_delivery" required>
                                                No <input type="radio" name="Apto_delivery" value="0" v-model="stockDato.Apto_delivery" required>
                                            </div>
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
                <!-- Modal Stock Fotos-->
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
                                            <td>{{categoriaDatos.Descripcion_categoria}}</td>
                                            <td>
                                                <button class="item" v-on:click="editarFormularioCategoria(categoriaDatos)" title="Editar">
                                                    <i class="ti-pencil-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <form class="form-horizontal" action="post" v-on:submit.prevent="crearCategoriaStock()">
                                <div class="modal-body">
                                    <div class="horizontal-form">
                                        <div class="form-group">
                                            <label class=" form-control-label">Nombre de la categoría</label> <input type="text" class="form-control" v-model="categoriaDatos.Nombre_categoria">
                                        </div>
                                        <div class="form-group">
                                            ¿Esta categoría es utilizada en productos de la carta?<br>
                                            Si <input type="radio" name="Apto_carta" value="1" v-model="categoriaDatos.Apto_carta">
                                            No <input type="radio" name="Apto_carta" value="0" v-model="categoriaDatos.Apto_carta">
                                        </div>
                                        <div class="form-group">
                                            <label class=" form-control-label">Descripción</label>
                                            <textarea class="form-control" rows="5" v-model="categoriaDatos.Descripcion_categoria"></textarea>
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
                                <h5 class="modal-title" id="scrollmodalLabel">Egreso de: {{egresoDato.Nombre_item}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form class="form-horizontal" action="post" v-on:submit.prevent="movimientoStock_v2(2)">
                                <div class="modal-body">

                                    <div class="form-group">
                                        <label class="control-label">Seleccionar jornada en que se realizó este egreso</label>
                                        <select class="form-control" v-model="egresoDato.Jornada_id">
                                            <option v-for="jornada in listaJornadas" v-bind:value="jornada.Id"> {{jornada.Descripcion}} ---- Fecha {{jornada.Fecha_inicio}}, {{jornada.Fecha_final}}</option>
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
                <!-- /.modal -->
                <!-- modal ORDEN TRABAJO INGRESO DE STOCK -->
                <div class="modal fade" id="ingresoModal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">

                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="scrollmodalLabel">Ingreso de: {{egresoDato.Nombre_item}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form class="form-horizontal" action="post" v-on:submit.prevent="movimientoStock_v2(1)">
                                <div class="modal-body">

                                    <div class="form-group">
                                        <label class="control-label">Seleccionar jornada en que se realizó este ingreso</label>
                                        <select class="form-control" v-model="egresoDato.Jornada_id">
                                            <option v-for="jornada in listaJornadas" v-bind:value="jornada.Id"> {{jornada.Descripcion}} ---- Fecha {{jornada.Fecha_inicio}}, {{jornada.Fecha_final}}</option>
                                        </select>
                                    </div>
                                    <div class="horizontal-form">
                                        <div class="form-group">
                                            <label class=" form-control-label">Cantidad ingresada</label>
                                            <input type="number" min="1" class="form-control" v-model="egresoDato.Cantidad" required>
                                        </div>
                                        <div class="form-group">
                                            <label class=" form-control-label">Descripción del ingreso</label>
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
                <!-- /.modal -->
                <!-- modal PRODUCTOS EN FALTA -->
                <div class="modal fade" id="enfaltaModal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">

                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="scrollmodalLabel">Productos faltantes</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-borderless table-striped table-earning">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Faltante</th>
                                            <th>Actual</th>
                                            <th>Ideal</th>
                                            <th>Costo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="prodFalta in listaProductosFalta">
                                            <td><b>{{prodFalta.Nombre_item}}</b></td>
                                            <td>
                                                <h4>{{prodFalta.Cant_ideal - prodFalta.Cant_actual}}</h4>
                                            </td>
                                            <td>{{prodFalta.Cant_actual}}</td>
                                            <td>{{prodFalta.Cant_ideal}}</td>
                                            <td>${{prodFalta.Precio_costo * (prodFalta.Cant_ideal - prodFalta.Cant_actual) | Moneda}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <p align="right">

                                    <a class="btn btn-info" v-bind:href="'stock/imprimirFaltantesStock/?categoria='+filtro_categoria" target="_blank" onClick="window.open(this.href, this.target, 'width=800,height=600'); return false;"> Imprimir </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end modal categorias -->
                <?php /// CABECERA BODY
                include "footer.php";
                ?>