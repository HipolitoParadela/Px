<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller 
{ 

//// VISTA 
    public function index()
	{   
        if ( $this->session->userdata('Login') == true )
		{
			header("Location: ".base_url()."dashboard"); /// enviar a pagina de error
		}
        else
        {
            $this->load->view('login');
        }
    }

//// INICIAR SESIÓN
    public function iniciar_session()
    {
        $dni  = $this->input->post('dni'); 
        $Pass  = $this->input->post('Pass'); 

        $this->load->model('user');
        $fila = $this->user->getUser($dni);

        if($fila != null) //// si el usuario existe
        {
            if($fila->Pass == $Pass) /// si la contraseña es correcta
            {
                if($fila->Rol_id >= 4) /// si es admin 
                {
                    $data = array(
                        'Nombre' => $fila->Nombre,
                        'Id' => $fila->Id,
                        'Login' => true,
                        'Rol_id' => $fila->Rol_id,
                        'Negocio_id' => $fila->Negocio_id
                    
                    );

                    $this->session->set_userdata($data);

                    header("Location: ".base_url()."dashboard");
                }
                
                else /// si no es admin 
                {
                    if($fila->Presencia == 1) /// si tiene activo el control de presencia y con un rol distinto a 1 y a 5
                    {
                        $data = array(
                            'Nombre' => $fila->Nombre,
                            'Id' => $fila->Id,
                            'Login' => true,
                            'Rol_id' => $fila->Rol_id,
                            'Negocio_id' => $fila->Negocio_id
                        );

                        $this->session->set_userdata($data);

                        header("Location: ".base_url()."dashboard");
                    }
                    else /// si tiene NO esta activo el control de presencia
                    {
                        header("Location: ".base_url()."login?Error=3");
                    }
                }
                
            }
            else /// si la contraseña NO es correcta
            {
                header("Location: ".base_url()."login?Error=1");
            }
        }
        else  //// si el usuario NO existe
        {
           header("Location: ".base_url()."login?Error=2"); 
        }
        
    }

//// REGISTRO MANUAL GRATIS
    public function registrogratispxresto()
    {
        if ( $this->session->userdata('Login') == true )
		{
			header("Location: ".base_url()."dashboard"); /// enviar a pagina de error
		}
        else
        {
            $this->load->view('registro_gratis');
        }
    
    }

//// REGISTRO MANUAL GRATIS
    public function registro()
    {
        $Nombre_negocio  = $this->input->post('Nombre_negocio'); 
        $Tipo_negocio  = $this->input->post('Tipo_negocio');
        $Telefono_1  = $this->input->post('Telefono_1'); 
        $Direccion  = $this->input->post('Direccion');
        $Email  = $this->input->post('Email'); 
        $Nombre_responsable  = $this->input->post('Nombre_responsable');
        $dni  = $this->input->post('dni'); 
        $Pass  = $this->input->post('Pass');
        $fecha_alta = date("Y-m-d");

        $this->load->model('user');
        $fila = $this->user->getUser($dni);

        if($fila == null) //// si el usuario NO existe permite su registro. Si existe 
        {
            $Id = null;
            
            // GUARDO EL NEGOCIO
            $data = array(
                        
                'Nombre_negocio' => 	$Nombre_negocio,
                'Nombre_responsable' => $Nombre_responsable,
                'Tipo_negocio' =>   	$Tipo_negocio,
                'Telefono_1' => 	    $Telefono_1,
                'Direccion' => 	        $Direccion,
                'Email' => 	            $Email,
                'DNI_responsable' => 	$dni,
                'Fecha_alta' =>         $fecha_alta
            );

            $this->load->model('Restaurant_model');
            $insert_negocio_id = $this->Restaurant_model->insertar($data, $Id, 'tbl_negocios');
            
            
            
            // ARMO EL USUARIO ADMINISTRADOR DEL NEGOCIO
                $data = array(
                            
                    'Nombre' => 	        $Nombre_responsable,
                    'DNI' =>                $dni,
                    'Pass' =>               $Pass,
                    'Rol_id' =>   	        4,
                    'Telefono' => 	        $Telefono_1,
                    'Domicilio' => 	        $Direccion,
                    'Email' => 	            $Email,
                    'Negocio_id' => 	    $insert_negocio_id,
                    'Fecha_alta' =>         $fecha_alta
                );
                
                $this->load->model('Restaurant_model');
                $insert_usuario_id = $this->Restaurant_model->insertar($data, $Id, 'tbl_usuarios');

            // ENVÍO UN EMAIL A LA PERSONA CON SUS DATOS
                $Asunto = "Bienvenido a PX Resto . Datos de acceso";
                $Destinatario = $Email . ', info@pxsistemas.com';
                $Mensaje = "<h2>Hola!</h2>
                            <h4>Comience a utilizar Px Resto con los siguientes datos de acceso.</h4>
                            <p>Enlace de acceso: <b>pxresto.pxsistemas.com</b></p>
                            <p>Dni: <b>".$dni."</b>, contraseña: <b>".$Pass."</b></p>";
                
                //Load email library
                $this->load->library('email');

                //SMTP & mail configuration
                $config = array(
                    'protocol'  => 'smtp',
                    'smtp_host' => 'ssl://c1610606.ferozo.com',
                    'smtp_port' => 465,
                    'smtp_user' => 'no-reply@pxsistemas.com',
                    'smtp_pass' => '@kl6lV@2lS',
                    'mailtype'  => 'html',
                    'charset'   => 'utf-8'
                );
                $this->email->initialize($config);
                $this->email->set_mailtype("html");
                $this->email->set_newline("\r\n");

                //Email content
                $htmlContent = '<h1>Px Sistemas</h1>';
                $htmlContent .= '<p>'.$Mensaje.'</p>';
                $htmlContent .= '<hr><h6>www.pxsistemas.com</h6>';

                $this->email->to($Destinatario);
                $this->email->from('no-reply@pxsistemas.com','Px Sistemas');
                $this->email->subject($Asunto);
                $this->email->message($htmlContent);

                //Send email
                $this->email->send();


            
            // GENERA LAS VARIABLES DE SESSIÓN NECESARIAS
                $data = array(
                    'Nombre' =>     $Nombre_responsable,
                    'Id' =>         $insert_usuario_id,
                    'Login' =>      true,
                    'Rol_id' =>     4,
                    'Negocio_id' => $insert_negocio_id
                
                );

                $this->session->set_userdata($data);

                header("Location: ".base_url()."dashboard");
            

        }
    }

    
//// CERRAR SESIÓN
    public function logout()
	{   
        $this->session->sess_destroy();
        
        header("Location: ".base_url()."login"); /// enviar a pagina de error

    }

}