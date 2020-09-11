<?php 
// CABECERA
include "header.php";
/// NAVEGADOR SIDEBAR
if($this->session->userdata('Rol_id') == 4) { include "navegadores/nav-bar-rol-4.php"; }
elseif($this->session->userdata('Rol_id') == 3) { include "navegadores/nav-bar-rol-3.php"; }
elseif($this->session->userdata('Rol_id') == 2) { include "navegadores/nav-bar-rol-2.php"; }
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
                                <h1>Caja, <span>Movimientos</span></h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="../">Caja</a></li>
                                    <li class="breadcrumb-item active">Movimientos de caja</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->
               
                <section id="main-content">
                    <div class="row"> 
                        <p align="right">
                            <a href="#modalMovimientos" data-toggle="modal" title="Nuevo movimiento" class="btn btn-success btn-flat btn-addon m-b-10 m-l-5" v-on:click="limpiarFormulariocaja()">
                                <i class="ti-plus"></i> Cargar movimiento
                            </a>
                        </p>
                    </div>
                    <div class="row"> 
                        <div class="col-lg-3">
                            <div class="card">
                                <div class="list-group">
                                    <a href="#" v-on:click="getMovimientosCaja(0)" class="list-group-item">Todas las jornadas</a>
                                    <a href="#" v-for="jornada in listaJornadas" v-on:click="getMovimientosCaja(jornada.Id)" class="list-group-item">{{jornada.Fecha_inicio | FechaTimestampBaseDatos}} . {{jornada.Descripcion}}</a>
                                </div>
                            </div>
                        </div>
                    <!-- SECCION DATOS DE FORMACIÓN -->
                        <div class="col-lg-9" >
                            <div class="card">
                                <div class="card-title">
                                    <h4>Movimientos de caja <!-- <span class="text-primary small">Jornada: {{formatoFecha_hora(listaMovCaja[0].Fecha_inicio)}}</span> --></h4>
                                </div>
                                <div class="card-body">
                                    
                                    <div class="row">     
                                         <div class="bootstrap-data-table-panel col-lg-12">
                                            <div class="table-responsive">
                                                <table id="table2excel" class="table">
                                                    <thead>
                                                        <tr>
                                                            <!-- <th></th> -->
                                                            <th>Jornada</th>
                                                            <th>F. Movimiento</th>
                                                            <th bgcolor="#E4FFCA">Monto ingresado</th>
                                                            <th bgcolor="#FFDFDF">Monto egresado</th>
                                                            <th>Descripcion</th>
                                                            <th>Encargado</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="caja in listaMovCaja">
                                                            <td>{{caja.Descripcion}}</td>
                                                            <td>{{caja.Fecha | FechaTimestampBaseDatos}}</td>
                                                            <td align="right" bgcolor="#E4FFCA">{{caja.Valor_ingreso}}</td>
                                                            <td align="right" bgcolor="#FFDFDF">{{caja.Valor_egreso}}</td>
                                                            <td>{{caja.Observaciones}}</td>
                                                            <td>{{caja.Nombre}}</td>
                                                            
                                                            <td>
                                                                <a href="#modalMovimientos" data-toggle="modal" v-on:click="editarFormularioCaja(caja)">
                                                                    <i class="ti-pencil-alt"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tbody>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td align="right" bgcolor="#E4FFCA"><h4>{{sumarIngresos(listaMovCaja)}}</h4></td>
                                                            <td align="right" bgcolor="#FFDFDF"><h4>{{sumarEgresos(listaMovCaja)}}</h4></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                        
                                    </DIV>
                                </DIV>
                        </div>
                    </div>
                </section>
            </div>

        </div>
        <!-- Modal caja-->
            <div class="modal fade" id="modalMovimientos" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="true">
                <div class="modal-dialog  modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalItemsCartaTitle">{{texto_boton}} movimiento</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>  
                        <div class="modal-body">
                            <div class="horizontal-form">
                                <form class="form-horizontal" action="post" v-on:submit.prevent="crearMovimiento()">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label class="control-label">Monto que ingresa a caja</label> 
                                            <input type="number" class="form-control" placeholder="" v-model="cajaData.Valor_ingreso">
                                        </div>
                                        <div class="col-sm-12">
                                            <label class="control-label">Monto que sale de caja</label>
                                            <input type="number"  class="form-control" placeholder="" v-model="cajaData.Valor_egreso">
                                        </div>                                        
                                        <div class="col-sm-12">
                                            <label class="control-label">Descripcion del movimiento</label>
                                            <textarea class="form-control" rows="5" placeholder="" v-model="cajaData.Observaciones"></textarea>
                                        </div>
                                        <div class="col-sm-offset-2 col-sm-12">
                                            <button type="submit" class="btn btn-success">{{texto_boton}}</button>
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
    <?php /// FOOTER
include "footer.php";
?>
    




