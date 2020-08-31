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
                    <div class="col-lg-12">
                        <h2 class="title-1 m-b-25">Puntaje del personal</h2>
                        <div class="table-responsive table--no-card m-b-40">
                            <table class="table table-borderless table-striped table-earning">
                                <thead>
                                    <tr>
                                        <th>Persona</th>
                                        <th>General</th>
                                        <th v-for="categoria in listaReportes.Categorias">{{categoria.Nombre_categoria}}</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="persona in listaReportes.Reportes">
                                        <td>
                                            <h4>{{persona.Datos_usuario.Nombre}}</h4>
                                        </td>
                                        <td align="center">
                                            <h3 v-bind:class="classColorReporte(persona.Promedio_general)">{{persona.Promedio_general}}</h3>
                                        </td>
                                        <td  align="center" v-for="calificacion in persona.Datos_reportes_usuario">
                                            <h4><span v-bind:class="classColorReporte(calificacion)">{{calificacion}}</span></h4>
                                        </td>
                                        
                                    </tr>
                                </tbody>
                                <!-- <tfoot>
                                    <th>
                                        <td></td>
                                        <td>Total del comportamiento en esta categoría</td>
                                    </th>
                                </tfoot> -->
                            </table>
                        </div>
                    </div>
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