<div class="row">
                            <div class="col-md-12">
                                <div class="copyright">
                                    <p>Copyright © 2019 PX ERP para Las Acequias Pilar. Un sistema desarrollado por <a href="https://www.facebook.com/PX-Sistemas-y-Software-para-empresas-pymes-y-comercios-244169629828068">PX Sistemas</a>.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->
        </div>

    </div>
 <!--config -->
    <script src="<?php echo base_url(); ?>funcionescomunes/config"></script>  
<!-- VUE JS-->    
    <script src="<?php echo base_url(); ?>js/vue.min.js"></script>
    <script src="<?php echo base_url(); ?>js/axios.js"></script>
    <!-- <script src="<?php echo base_url(); ?>js/JsonExcel.vue"></script> -->
<!-- APP JS-->
    <script src="<?php echo base_url(); ?>js/app.js"></script>
<!-- Jquery JS-->
    <script src="<?php echo base_url(); ?>Plantilla/vendor/jquery-3.2.1.min.js"></script>

<!-- Bootstrap JS-->
    <script src="<?php echo base_url(); ?>Plantilla/vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="<?php echo base_url(); ?>Plantilla/vendor/bootstrap-4.1/bootstrap.min.js"></script>
<!-- Vendor JS       -->
    <script src="<?php echo base_url(); ?>js/toastr.js"></script>
    <script src="<?php echo base_url(); ?>Plantilla/vendor/slick/slick.min.js">
    </script>
    <script src="<?php echo base_url(); ?>Plantilla/vendor/wow/wow.min.js"></script>
    <script src="<?php echo base_url(); ?>Plantilla/vendor/animsition/animsition.min.js"></script>
    <script src="<?php echo base_url(); ?>Plantilla/vendor/bootstrap-progressbar/bootstrap-progressbar.min.js">
    </script>
    <script src="<?php echo base_url(); ?>Plantilla/vendor/counter-up/jquery.waypoints.min.js"></script>
    <script src="<?php echo base_url(); ?>Plantilla/vendor/counter-up/jquery.counterup.min.js">
    </script>
    <script src="<?php echo base_url(); ?>Plantilla/vendor/circle-progress/circle-progress.min.js"></script>
    <script src="<?php echo base_url(); ?>Plantilla/vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="<?php echo base_url(); ?>Plantilla/vendor/chartjs/Chart.bundle.min.js"></script>
    <script src="<?php echo base_url(); ?>Plantilla/vendor/select2/select2.min.js">
    </script>

<!-- Main JS-->
    <script src="<?php echo base_url(); ?>Plantilla/js/main.js"></script>

    <!-- SCRIPT PARA TOMAR VALOR DE LA TABLA A EXPORTAR A EXCEL-->                                            
    <script>
            function myFunction() {
                var x = document.getElementById("table2excel").innerHTML; // primero tomo el contenido de la tabla
                document.getElementById("tabla").value = x; /// lo paso al input
            }
    </script>