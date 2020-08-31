<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Funcionescomunes extends CI_Controller
{
    //// 			| ELIMINAR ALGO
	public function eliminar()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

		$Id = NULL;
		$tabla = NULL;

		if(isset($this->datosObtenidos->Id))
        {
            $Id = $this->datosObtenidos->Id;
        }

		if(isset($this->datosObtenidos->tabla))
        {
            $tabla = $this->datosObtenidos->tabla;
        }
		
        $this->load->model('App_model');
        $insert_id = $this->App_model->eliminar($Id, $tabla);
                
    }
}