/// --- SETEANDO VARIABLES DE URL ----- //////
var pathname = window.location.pathname;
var carpeta = '/resto/V1/' /// carpeta que hay q modificar segun cliente
var base_url = window.location.origin + carpeta
var URLactual = window.location.search;
var Get_Id = URLactual.slice(4); ///ID QUE VIENE POR URL
var token = "a8B6c4D4e8F2";

//// FUNCIONES  | Fecha actual
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();
    var hora = today.getHours()
    var minutos = today.getMinutes()

    if (dd < 10) { dd = '0' + dd; }
    if (mm < 10) { mm = '0' + mm; }
    var hoy = dd + '/' + mm + '/' + yyyy;
    var hoy_php = yyyy + '-' + mm + '-' + dd;
    var horaHoy_php = hora + ':' + minutos;

    

    <?php 
        if(isset($Id))
        {
            echo 'var Id = '. $Id.';';
            echo 'var Nombre = "'. $Nombre.'";';
            echo 'var Rol_id = '. $Rol_id.';';
            echo 'var Negocio_id = '. $Negocio_id.';';
            echo 'var Tipo_suscripcion = '. $Tipo_suscripcion.';';
        }
        else
        {
            echo 'var Usuario_id = false;';
            echo 'var Rol_acceso = false;';
        }
        
    ?>