<?php
include('afip.php');
$afip = new Afip(array(
    'homo' => true,            // Entorno de pruebas
    'cuit' => '2735471352', // CUIT del cliente
    'dir_auth' => 'auth',      // Directorio para guardar los tokens (Debe existir)
    'dir_wsdl' => 'wsdl',      // Directorio para guardar los WSDL (Debe existir)
    'key_file' => 'key', // Llave privada rsa
    'key_pass' => '33752277',                // Contraseña llave rsa
    'crt_file' => 'cert' // Certificado generado por Afip
));
$res = $afip->req('wsfe', 'FEParamGetTiposMonedas');

header('Content-Type: application/json');
echo json_encode($res, JSON_PRETTY_PRINT);
?>