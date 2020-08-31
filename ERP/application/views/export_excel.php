<?php
$dia = date('d-m-Y');
header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: attachment; filename=reporte_$dia.xls");
header('Pragma: public');
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
header('Pragma: no-cache');
header('Expires: 0');
header('Content-Transfer-Encoding: none');
header('Content-type: application/vnd.ms-excel;charset=utf-8');// This should work for IE & Opera
header('Content-type: application/x-msexcel; charset=utf-8'); // This should work for the rest
header("Content-Disposition: attachment; filename=reporte_$dia.xls");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");

//// CREAR CAMPO OCULTO CON EL NOMBRE DEL ARCHIVO

$tabla_limpia = $_POST["tabla"];

// ELIMINANDO ACENTOS Y Ñ
function eliminar_tildes($tabla_limpia)
{
    //Codificamos la tabla_limpia en formato utf8 en caso de que nos de errores
    //$tabla_limpia = utf8_encode($tabla_limpia);

    //Ahora reemplazamos las letras
    $tabla_limpia = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $tabla_limpia
    );

    $tabla_limpia = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $tabla_limpia);

    $tabla_limpia = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $tabla_limpia);

    $tabla_limpia = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $tabla_limpia);

    $tabla_limpia = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $tabla_limpia);

    $tabla_limpia = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C'),
        $tabla_limpia
    );
}

//$tabla_limpia = eliminar_tildes($tabla_limpia);  INTENTAR HACER ANDAR ESTO ASI NO HAY PROBLEMAS CON LOS ACENTOS

?>
<table>
<? echo $tabla_limpia; ?>
</table>