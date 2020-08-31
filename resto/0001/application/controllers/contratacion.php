<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller 
{ 

    public function index()
	{   
        require(base_url()."/contactform/class.phpmailer.php");
        require(base_url()."/contactform/class.smtp.php");

        // Valores enviados desde el formulario
        if ( !isset($_POST["name"]) || !isset($_POST["email"]) || !isset($_POST["telefono"]) ) 
        {
            die ("Es necesario completar todos los datos del formulario");
        }

        /// SETEANDO VARIABLES    
            $nombre = $_POST["name"];
            $telefono = $_POST["telefono"];
            $email = $_POST["email"];
            $DNI = $_POST["DNI"];
            $nombre_local = $_POST["nombre_local"];
            $direccion = $_POST["direccion"];
            $provincia = $_POST["provincia"];
            $pais = $_POST["pais"];
            $telefono_local = $_POST["telefono_local"];
            $message = $_POST["message"];
            $collection_id = $_POST["collection_id"];
            $collection_status = $_POST["collection_status"];
            $preference_id = $_POST["preference_id"];
            $external_reference = $_POST["external_reference"];
            $payment_type = $_POST["payment_type"];
            $merchant_order_id = $_POST["merchant_order_id"];


        /// CARGANDO A BASE DE DATOS
                // conectar a MySQL, base de datos
                define('cServidor', 'localhost');
                define('cUsuario', 'root');
                define('cPass','root');
                define('cBd','restaurant');

                $conexion = mysqli_connect(cServidor, cUsuario, cPass, cBd);

                

                // $host = "localhost";	$user = "root";	$pwd = "root";
                // $link = mysql_connect( $host, $user, $pwd ) or die( "Error de conexión: " . mysql_error() );  	
                // //selecciona base de datos
                // mysql_select_db("restaurant");
                
                $queryinsertar = "insert into clientes values ( '', 
                                                                '".$nombre."', 
                                                                '".$telefono."',
                                                                '".$email."', 
                                                                '".$DNI."', 
                                                                '".$nombre_local."', 
                                                                '".$direccion."',
                                                                '".$provincia."', 
                                                                '".$pais."', 
                                                                '".$telefono_local."', 
                                                                '".$message."',
                                                                '')";

                $resultado = mysqli_query($conexion, $queryinsertar);
            
                if ($resultado)
                { 		
                    $cliente_id = mysql_insert_id(); 
                    
                    $queryinsertar = "insert into pagos values (    '', 
                                                                    '".$cliente_id."',
                                                                    '".$collection_id."', 
                                                                    '".$collection_status."', 
                                                                    '".$preference_id."', 
                                                                    '".$external_reference."', 
                                                                    '".$payment_type."', 
                                                                    '".$merchant_order_id."',
                                                                    '')";

                    $consultainsertar = mysql_query($queryinsertar);
                    
                }
                

            // DEVOLVER EL ID CARGADO PARA TENER UNA REFERENCIA EN EL MAIL TAMBIÉN
            // A LA LARGA DEBO ARMAR UN SISTEMA QUE SE ENCARGUE DE AVISAR AUTOMATICAMENTE LOS VENCIMIENTOS

        /// ENVIO DE EMAIL 
            // Datos de la cuenta de correo utilizada para enviar vía SMTP
            $smtpHost = "c1260237.ferozo.com";  // Dominio alternativo brindado en el email de alta 
            $smtpUsuario = "no-reply@c1260237.ferozo.com";  // Mi cuenta de correo
            $smtpClave = "iCreatix88";  // Mi contraseña

            // Email donde se enviaran los datos cargados en el formulario de contacto
            $emailDestino = "ventas@pixelestudio.net";

            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->SMTPAuth = true;
            $mail->Port = 587; 
            $mail->IsHTML(true); 
            $mail->CharSet = "utf-8";

            $mail->Host = $smtpHost; 
            $mail->Username = $smtpUsuario; 
            $mail->Password = $smtpClave;

            $mail->From = $smtpUsuario; // Email desde donde envío el correo.
            $mail->FromName = $nombre;
            $mail->AddAddress($emailDestino); // Esta es la dirección a donde enviamos los datos del formulario
            $mail->AddReplyTo($email); // Esto es para que al recibir el correo y poner Responder, lo haga a la cuenta del visitante. 
            $mail->Subject = "Notificación de compra de plan"; // Este es el titulo del email.
            
            /// configurando texto del mensaje
            $mensajeHtml = nl2br($message);
            $mail->Body = "{$mensajeHtml} <br /><br />Compra de plan PX Resto<br />
                Nombre: ".$nombre." <br />
                Teléfono: ".$telefono." <br />
                Email: ".$email." <br />
                DNI: ".$DNI." <br /><br />
                Nombre del local: ".$nombre_local." <br />
                Dirección: ".$direccion." <br />
                Provincia: ".$provincia." <br /> 
                Pais: ".$pais." <br /> 
                Telefono del local: ".$telefono_local." <br />
                Mensaje: ".$message." <br /> <br /> 

                Collection Id: ".$collection_id." <br /> 
                Collection Status: ".$collection_status." <br /> 
                Preferencia Id: ".$preference_id." <br /> 
                External Reference: ".$external_reference." <br /> 
                Tipo de pago: ".$payment_type." <br /> 
                Merchant order Id: ".$merchant_order_id." <br /> 

            
            "; // Texto del email en formato HTML
            
            $mail->AltBody = "{$message} \n\n <br /><br />Compra de plan PX Resto<br />
                Nombre: ".$nombre." \n
                Teléfono: ".$telefono." \n
                Email: ".$email." \n
                DNI: ".$DNI." \n\n
                Nombre del local: ".$nombre_local." \n
                Dirección: ".$direccion." \n
                Provincia: ".$provincia." \n 
                Pais: ".$pais." \n 
                Telefono del local: ".$telefono_local." \n
                Mensaje: ".$message." \n \n 

                Collection Id: ".$collection_id." \n 
                Collection Status: ".$collection_status." \n 
                Preferencia Id: ".$preference_id." \n 
                External Reference: ".$external_reference." \n 
                Tipo de pago: ".$payment_type." \n 
                Merchant order Id: ".$merchant_order_id." \n "; // Texto sin formato HTML
            // FIN - VALORES A MODIFICAR //

            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $estadoEnvio = $mail->Send(); 
            if($estadoEnvio){
                echo "1";
            } else {
                echo "0";
            }

    }
    
}