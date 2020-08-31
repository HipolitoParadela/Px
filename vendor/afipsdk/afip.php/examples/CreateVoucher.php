<?php
include '../src/Afip.php'; 

$afip = new Afip(array(
						'CUIT' => 20337522778, 
						'PASSPHRASE' => '33752277',
						//'res_folder' => '../../../../../afip_res/' // hago volver la carpeta donde tengo estas credenciales a donde no se puede acceder online
					));

//Devuelve el número del último comprobante creado para el punto de venta 1 y el tipo de comprobante 6 (Factura B)
	/*- 01, 02, 03, 04, 05,34,39,60, 63 para los clase A
	- 06, 07, 08, 09, 10, 35, 40,64, 61 para los clase B.
	- 11, 12, 13, 15 para los clase C.*/
$last_voucher = $afip->ElectronicBilling->GetLastVoucher(1,6); 

echo $last_voucher;

$num_factura = $last_voucher + 1;

echo ' . ' . $num_factura;
echo "<br>__DIR_ solo: " . __DIR__ . "<br>";
echo "<br>__DIR_ retocado: " . __DIR__ . "<br><br><br>";

$data = array(
	'CantReg' 		=> 1, // Cantidad de comprobantes a registrar
	'PtoVta' 		=> 1, // Punto de venta
	'CbteTipo' 		=> 6, // Tipo de comprobante (ver tipos disponibles) 
	'Concepto' 		=> 1, // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
	'DocTipo' 		=> 80, // Tipo de documento del comprador (ver tipos disponibles)
	'DocNro' 		=> 27354713522, // Numero de documento del comprador
	'CbteDesde' 	=> $num_factura, // Numero de comprobante o numero del primer comprobante en caso de ser mas de uno
	'CbteHasta' 	=> $num_factura, // Numero de comprobante o numero del ultimo comprobante en caso de ser mas de uno
	'CbteFch' 		=> intval(date('Ymd')), // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo
	'ImpTotal' 		=> 121, // Importe total del comprobante
	'ImpTotConc' 	=> 0, // Importe neto no gravado
	'ImpNeto' 		=> 100, // Importe neto gravado
	'ImpOpEx' 		=> 0, // Importe exento de IVA
	'ImpIVA' 		=> 21, //Importe total de IVA
	'ImpTrib' 		=> 0, //Importe total de tributos
	'FchServDesde' 	=> NULL, // (Opcional) Fecha de inicio del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
	'FchServHasta' 	=> NULL, // (Opcional) Fecha de fin del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
	'FchVtoPago' 	=> NULL, // (Opcional) Fecha de vencimiento del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
	'MonId' 		=> 'PES', //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos) 
	'MonCotiz' 		=> 1, // Cotización de la moneda usada (1 para pesos argentinos)
	'Iva' 			=> array( // (Opcional) Alícuotas asociadas al comprobante
		array(
			'Id' 		=> 5, // Id del tipo de IVA (ver tipos disponibles) 
			'BaseImp' 	=> 100, // Base imponible
			'Importe' 	=> 21 // Importe 
		)
	),  
	/* 'CbtesAsoc' 	=> array( // (Opcional) Comprobantes asociados
		array(
			'Tipo' 		=> 6, // Tipo de comprobante (ver tipos disponibles) 
			'PtoVta' 	=> 1, // Punto de venta
			'Nro' 		=> $num_factura, // Numero de comprobante
			'Cuit' 		=> 27354713522 // (Opcional) Cuit del emisor del comprobante
			)
		),
	'Tributos' 		=> array( // (Opcional) Tributos asociados al comprobante
		array(
			'Id' 		=>  99, // Id del tipo de tributo (ver tipos disponibles) 
			'Desc' 		=> 'Ingresos Brutos', // (Opcional) Descripcion
			'BaseImp' 	=> 0, // Base imponible para el tributo
			'Alic' 		=> 0, // Alícuota
			'Importe' 	=> 0 // Importe del tributo
		)
	), 
	
	'Opcionales' 	=> array( // (Opcional) Campos auxiliares
		array(
			'Id' 		=> 17, // Codigo de tipo de opcion (ver tipos disponibles) 
			'Valor' 	=> 2 // Valor 
		)
	), 
	'Compradores' 	=> array( // (Opcional) Detalles de los clientes del comprobante 
		array(
			'DocTipo' 		=> 80, // Tipo de documento (ver tipos disponibles) 
			'DocNro' 		=> 27354713522, // Numero de documento
			'Porcentaje' 	=> 100 // Porcentaje de titularidad del comprador
		)
	) */
);

/// GUARDANDO RESULTADO DE LA PETICIÓN
	$resultado = $afip->ElectronicBilling->CreateVoucher($data);

	echo json_encode($resultado);

?>