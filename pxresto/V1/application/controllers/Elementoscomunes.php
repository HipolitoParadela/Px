<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Elementoscomunes extends CI_Controller
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

/// DATOS USUARIO JS | VISTA | PRODUCCION
	public function config()
	{
		if ($this->session->userdata('Login') != true) 
		{
			//header("Location: " . base_url() . "login"); /// enviar a pagina de error
			$Datos = array( 
							'Rol_acceso' => 0,
							'Usuario_id' => 0
							);

			$this->load->view('config', $Datos);
		} 
		else 
		{
			$Datos = array( 'Nombre' =>     $this->session->userdata('Nombre'),
                            'Id' =>         $this->session->userdata('Id'),
                            'Login' =>      $this->session->userdata('Login'),
                            'Rol_id' =>     $this->session->userdata('Rol_id'),
                            'Negocio_id' => $this->session->userdata('Negocio_id'),
                            'Tipo_suscripcion' => $this->session->userdata('Tipo_suscripcion')
						);

			$this->load->view('config', $Datos);
		}
	}

/// 			| ELIMINAR ALGO
	public function eliminar()
	{
		$CI = &get_instance();
		$CI->load->database();

		$token = @$CI->db->token;
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));
		if ($this->datosObtenidos->token != $token) {
			exit("No coinciden los token");
		}

		$Id = NULL;
		$tabla = NULL;

		if (isset($this->datosObtenidos->Id)) {
			$Id = $this->datosObtenidos->Id;
		}

		if (isset($this->datosObtenidos->tabla)) {
			$tabla = $this->datosObtenidos->tabla;
		}

		$this->load->model('App_model');
		$insert_id = $this->App_model->eliminar($Id, $tabla);
	}


/// ENVIO DE EMAIL
	public function envio_mails()
	{
	
		//Seguridad
		$CI =& get_instance();
        $CI->load->database();
		//Seguridad
		$token = @$CI->db->token;
		
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) {
            exit("No coinciden los token");
        }
        
		$Asunto = $this->datosObtenidos->Asunto;
		$Destinatario = $this->datosObtenidos->Destinatario . ', info@institutojlc.com';
		$Mensaje = $this->datosObtenidos->Mensaje;
		
		//Load email library
		$this->load->library('email');

		//SMTP & mail configuration
		$config = array(
			'protocol'  => 'smtp',
			'smtp_host' => 'ssl://c1570036.ferozo.com',
			'smtp_port' => 465,
			'smtp_user' => 'info@institutojlc.com',
			'smtp_pass' => 'ey@giyfjej6Vijg',
			'mailtype'  => 'html',
			'charset'   => 'utf-8'
		);
		$this->email->initialize($config);
		$this->email->set_mailtype("html");
		$this->email->set_newline("\r\n");

		//Email content
		$htmlContent = '<h1>Instituto JLC</h1>';
		$htmlContent .= '<p>'.$Mensaje.'</p>';
		$htmlContent .= '<h6>Mensaje autómatico enviado desde la plataforma www.institutojlc.com</h6>';

		$this->email->to($Destinatario);
		$this->email->from('info@institutojlc.com','Instituto JLC');
		$this->email->subject($Asunto);
		$this->email->message($htmlContent);

		//Send email
		$this->email->send();

	}

/// 
}
