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
                            <div class="col-md-6">
                                <!-- DATA TABLE -->
                                <h3 class="title-5 m-b-35">Todos los curriculums</h3>
                                
                                <div class="table-data__tool">
                                    <div class="table-data__tool-left">
                                        <!--<div class="rs-select2--light rs-select2--md">
                                            <select class="form-control-sm form-control" v-model="filtro_edad" v-on:change="getListadoCurriculums()">
                                                <option selected="selected" value="0">Todas las edades</option>
                                                <option selected="selected" value="1">18 a 25 años</option>
                                                <option selected="selected" value="2">26 a 35 años</option>
                                                <option selected="selected" value="3">Más de 35 años</option>
                                                
                                            </select>
                                            <div class="dropDownSelect2"></div>
                                        </div>
                                        <div class="rs-select2--light rs-select2--md">
                                            <select class="form-control-sm form-control" v-model="filtro_sexo" v-on:change="getListadoCurriculums()">
                                                <option selected="selected" value="0">Ambos sexos</option>
                                                <option selected="selected" value="1">Varones</option>
                                                <option selected="selected" value="2">Mujeres</option>
                                            </select>
                                            <div class="dropDownSelect2"></div>
                                        </div>-->
                                        <div class="rs-select2--light rs-select2--lg">
                                            <input type="text" class="form-control form-control" placeholder="Buscar persona" v-model="buscar">
                                        </div>
                                         
                                        <!--<button class="au-btn-filter"><i class="zmdi zmdi-filter-list"></i>Filtros</button>-->
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <p align="right"><a class="btn btn-dark btn-outline m-b-10 m-l-5" href="<?= base_url();?>curriculum">Ver curriculums testeados</a></p>
                                <!-- <pre>{{listaCurriculums}}</pre> -->
                            </div>
                        </div>   
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive table-responsive-data2">
                                    <table class="table table-data2">
                                        <thead>
                                            <tr>
                                                <th>
                                                    
                                                </th>
                                                <th>Nombre</th>    
                                                <th>sexo</th>
                                                <th>telefono</th>
                                                <th>ciudad</th>
                                                <th>fecha</th>
                                                <th>calificación</th>
                                                <th>puestos potenciales</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="tr-shadow" v-for="curriculum in buscarCurriculums">
                                                <td>
                                                    <div class="round-img">
                                                        <a href="#modalcurriculumsFoto" data-toggle="modal">
                                                            <img v-if="curriculum.foto != null"  v-bind:src="curriculum.foto" width="60px">
                                                            <img v-else src="https://freeiconshop.com/wp-content/uploads/edd/person-flat.png" width="50px" alt="">
                                                        </a>
                                                    </div>
                                                </td>
                                                <td style="text-transform: capitalize;">
                                                    <a v-bind:href="'datos/?Id='+curriculum.Id" class="btn btn-dark btn-outline m-b-10 m-l-5">
                                                        {{curriculum.nombre}}
                                                    </a>
                                                </td>
                                                <td class="desc">{{curriculum.sexo}}</td>
                                                <td>{{curriculum.telefono}}</td>
                                                <td>
                                                    {{curriculum.ciudad}}
                                                </td>
                                                <td>{{curriculum.fecha}}</td>
                                                <td>
                                                    <span v-if="curriculum.Puntaje == 1" class="btn btn-danger btn-sm ">No apto</span>
                                                    <span v-if="curriculum.Puntaje == 2" class="btn btn-warning btn-sm ">Regular</span>
                                                    <span v-if="curriculum.Puntaje == 3" class="btn btn-info btn-sm ">Bueno</span>
                                                    <span v-if="curriculum.Puntaje == 4" class="btn btn-success btn-sm ">Excelente</span>
                                                </td>
                                                <td>
                                                    <h6><span v-for="puesto in curriculum[0]">
                                                        {{puesto.Nombre_puesto}} | 
                                                    </span></h6>
                                                </td>
                                                <td>
                                                    <div class="table-data-feature">
                                                        <a class="item" v-bind:href="'datos/?Id='+curriculum.Id" title="Ver todos los datos">
                                                            <i class="zmdi zmdi-mail-send"></i>
                                                        </a>                                                        
                                                        <button v-on:click="desactivar(curriculum.Id)" class="item" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                                            <i class="zmdi zmdi-delete"></i>
                                                        </button>
                                                       <!--<button class="item" data-toggle="tooltip" data-placement="top" title="More">
                                                            <i class="zmdi zmdi-more"></i>
                                                        </button>-->
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
            <!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->
        

    

   <?php
// CABECERA
include "footer.php";
?>

</body>

</html>
<!-- end document-->
