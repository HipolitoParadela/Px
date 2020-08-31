<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller 
{ 

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
    
    public function logout()
	{   
        $this->session->sess_destroy();
        
        header("Location: ".base_url()."login"); /// enviar a pagina de error

    }

}