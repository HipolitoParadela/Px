<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Planificaciones extends CI_Controller 
{ 

/// PLANIFICACIONES - VISTA
    public function index()
	{   
        if ( $this->session->userdata('Login') != true )
		{
			header("Location:".base_url()."dashboard"); /// enviar a pagina de error
		}
        else
        {
            ///CONTROLO QUE PLAN TIENE PARA LIMITAR LO QUE PUEDA VER
            if (plan_contratado() > 1) 
            {
                $this->load->view('calendario');
            }
            else {
                $this->load->view('plan-medio'); 
            }

            //$this->load->view('calendario');
        }
    }


/// PLANIFICACIONES - CREAR EVENTO
    public function crearevento()
    {
        $CI =& get_instance();
		$CI->load->database();
		
        //$this->datosObtenidos = json_decode(file_get_contents('php://input'));
        //$datoFormacion = json_decode(trim(file_get_contents('php://input')), true);

        $title = $_POST["title"];
        $start = $_POST["start"];
        $end = $_POST["end"];
        $className = $_POST["className"];
        ///PARA SABER EL ID tendre que ver si existe una fecha igual, aunq seguramente podré agregar el id en cada campo.
        
        $Id = NULL;
        // if(isset($this->datosObtenidos->Id))
        // {
        //     $Id = $this->datosObtenidos->Id;
        // }
		// Sat May 05 2018 21:00:00 GMT-0300 (Hora estándar de Argentina)
        
        $data = array(
                        
					'title' => 		$title,
					'start' => 		$start,
					'end' => 		$end,
					'className' => 	$className
                );

        $this->load->model('Restaurant_model');
		$insert_id = $this->Restaurant_model->insertar($data, $Id, ' tbl_planificaciones');
		
		
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
        
    }


/// PLANIFICACIONES - EDITAR EVENTO
    public function editarevento()
    {
        $CI =& get_instance();
		$CI->load->database();

        $Id = $_POST["Id"];
        $title = $_POST["title"];

		$data = array(
                        
					'title' => 		$title,
                );

        $this->load->model('Restaurant_model');
		$insert_id = $this->Restaurant_model->insertar($data, $Id, ' tbl_planificaciones');
		
		
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
    }

/// PLANIFICACIONES - ELIMINAR EVENTO
    public function eliminarevento()
    {
        $CI =& get_instance();
		$CI->load->database();

        $Id = $_POST["Id"];

        $this->load->model('Restaurant_model');
		$insert_id = $this->Restaurant_model->eliminar($Id, ' tbl_planificaciones');		
		
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
    }

/// PLANIFICACIONES - CARGAR LISTADO
    public function obtener_eventos()
	{   
        
        
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

		$this->db->select('*');
		$this->db->from('tbl_planificaciones');

        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
    }

}