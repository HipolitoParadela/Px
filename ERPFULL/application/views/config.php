/// --- SETEANDO VARIABLES DE URL ----- //////
var pathname = window.location.pathname;
var carpeta = '/ERPFULL/' /// carpeta que hay q modificar segun cliente
var base_url = window.location.origin + carpeta
var URLactual = window.location.search;
var Get_Id = URLactual.slice(4); ///ID QUE VIENE POR URL

//// FUNCIONES  | Fecha actual
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth() + 1; //Enero es 0!
var yyyy = today.getFullYear();
if (dd < 10) {
    dd = '0' + dd;
}
if (mm < 10) {
    mm = '0' + mm;
}
var hoy_slash = dd + '/' + mm + '/' + yyyy;
var hoy_php = yyyy + '-' + mm + '-' + dd;
var token = "a8B6c4D4e8F2";

var Cookies = document.cookie;

    <?php 
        echo 'var Usuario_id = '. $Usuario_id.';';
        echo 'var Rol_acceso = '. $Rol_acceso.';';
    ?>
