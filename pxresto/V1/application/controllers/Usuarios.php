<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends CI_Controller 
{ 


//// USUARIOS | LISTADO
	public function index()
	{	
		if ( $this->session->userdata('Login') != true )
		{
			header("Location: ".base_url()."login"); /// enviar a pagina de error
		}
		else
		{	
            
            if ( $this->session->userdata('Rol_id') >= 4)
			{
				$this->load->view('admin-usuarios');
			}
			else 
			{
				header("Location: ".base_url()."login"); /// enviar a pagina de error
			}	
			
		}
	}


//// USUARIOS | LISTADO
	public function datos()
	{	
		if ( $this->session->userdata('Login') != true )
		{
			header("Location: ".base_url()."login"); /// enviar a pagina de error
		}
		else
		{	
            ////COMENZAR A FILTRAR Y REDIRECCIONAR SEGUN ROL Y PLAN CONTRATADO
                //if (plan_contratado() > 1) {}
            
            if ( $this->session->userdata('Rol_id') >= 4)
			{
				if (plan_contratado() > 1) 	
				{ 
					$this->load->view('usuarios-datos'); 
				}
				else						
				{	
					$this->load->view('plan-medio'); 
				}
			}
			else 
			{
				header("Location: ".base_url()."login"); /// enviar a pagina de error
			}	
			
		}
	}

//// OBTENER USUARIOS
	public function obtener_Usuario()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

		$Id = $_GET["Id"];

		$this->db->select('	tbl_usuarios.*,
							tbl_roles.Nombre_rol,
							Superior.Nombre as Nombre_superior');
		$this->db->from('tbl_usuarios');
		$this->db->join('tbl_roles', 'tbl_roles.Id = tbl_usuarios.Rol_id','left');
		$this->db->join('tbl_usuarios as Superior', 'Superior.Id = tbl_usuarios.Superior_inmediato','left');
		$this->db->where('tbl_usuarios.Id',$Id);
		//$this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));
		$this->db->order_by("Nombre", "asc");
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
	}
	
//// OBTENER FORMACIONES
	public function obtener_formaciones()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

		$Id = $_GET["Id"];

		$this->db->select('tbl_usuarios_formacion.*');
		$this->db->from('tbl_usuarios_formacion');
		$this->db->where('Usuario_id',$Id);
		$this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));
		$this->db->order_by("Anio_inicio", "asc");
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
	}
	
//// CARGAR O EDITAR FORMACION
	public function cargar_formacion()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));

		$Id = NULL;
		if(isset($this->datosObtenidos->formacionData->Id))
        {
            $Id = $this->datosObtenidos->formacionData->Id;
		}

		$data = array(
                        
					'Titulo' => 			$this->datosObtenidos->formacionData->Titulo,
					'Usuario_id' => 		$this->datosObtenidos->Usuario_id,
					'Establecimiento' => 	$this->datosObtenidos->formacionData->Establecimiento,
					'Anio_inicio' => 		$this->datosObtenidos->formacionData->Anio_inicio,
					'Anio_finalizado' => 	$this->datosObtenidos->formacionData->Anio_finalizado,
					'Descripcion_titulo' => $this->datosObtenidos->formacionData->Descripcion_titulo,
                    'Negocio_id' => $this->session->userdata('Negocio_id')
				);

        $this->load->model('Restaurant_model');
        $insert_id = $this->Restaurant_model->insertar($data, $Id, 'tbl_usuarios_formacion');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
	}


//// OBTENER SUELDOS
	public function obtener_sueldos()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

		$Id = $_GET["Id"];
		$Fecha_inicio = $_GET["Fecha_inicio"];
        $Fecha_fin =    $_GET["Fecha_fin"];

		$this->db->select('*');
		$this->db->from('tbl_usuarios_sueldos');
		$this->db->where('Usuario_id',$Id);
		$this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));
		if($Fecha_inicio != 0)   { $this->db->where("DATE_FORMAT(Fecha, '%Y-%m-%d' ) >=", $Fecha_inicio); }
        if($Fecha_fin != 0)      { $this->db->where("DATE_FORMAT(Fecha, '%Y-%m-%d' ) <=", $Fecha_fin); }

		$this->db->order_by("Fecha", "desc");
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
	}
	
//// CARGAR O EDITAR SUELDOS
	public function cargar_liquidacion()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));

		$Id = NULL;
		if(isset($this->datosObtenidos->Data->Id))
        {
            $Id = $this->datosObtenidos->Data->Id;
		}

		$data = array(
                        
					'Sueldo_pactado' => 		$this->datosObtenidos->Data->Sueldo_pactado,
					'Sueldo_abonado' => 		$this->datosObtenidos->Data->Sueldo_abonado,
					'Bonificacion' => 			$this->datosObtenidos->Data->Bonificacion,
					'Descuento' => 				$this->datosObtenidos->Data->Descuento,
					'Fecha' => 					$this->datosObtenidos->Data->Fecha,
					'Costes_impositivos_adicionales' => $this->datosObtenidos->Data->Costes_impositivos_adicionales,
					'Observaciones' => 			$this->datosObtenidos->Data->Observaciones,
					'Usuario_id' => 			$this->datosObtenidos->Usuario_id,
                    'Negocio_id' => $this->session->userdata('Negocio_id')
				);

        $this->load->model('Restaurant_model');
        $insert_id = $this->Restaurant_model->insertar($data, $Id, 'tbl_usuarios_sueldos');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
	}


//// OBTENER Roles
	public function obtener_roles()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

		$this->db->select('*');
		$this->db->from('tbl_roles');
		
		$this->db->order_by("Acceso", "desc");
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
	}

//// OBTENER LIDERES
	public function obtener_lideres()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

		$this->db->select('	tbl_usuarios.Id,
							tbl_usuarios.Nombre');
		$this->db->from('tbl_usuarios');
		$this->db->where('Lider',1);
		$this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));
		$this->db->order_by("Nombre", "desc");
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
	}

//// OBTENER USUARIOS
	public function obtener_jornadas_trabajadas()
	{
			
		//Esto siempre va es para instanciar la base de datos
		$CI =& get_instance();
		$CI->load->database();
		$token = @$CI->db->token;

		$Id = $_GET["Id"];
		$Fecha_inicio = $_GET["Fecha_inicio"];
        $Fecha_fin =    $_GET["Fecha_fin"];

		$this->db->select('	tbl_log_usuarios.*,
							tbl_usuarios.Remuneracion_jornada,
							tbl_jornadas.Descripcion,
							tbl_jornadas.Fecha_inicio,
							tbl_jornadas.Fecha_final');

		$this->db->from('tbl_log_usuarios');

		$this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_log_usuarios.Colaborador_id','left');
		$this->db->join('tbl_jornadas', 'tbl_jornadas.Id = tbl_log_usuarios.Jornada_id','left');

		$this->db->where('tbl_log_usuarios.Colaborador_id', $Id);
		$this->db->where('tbl_log_usuarios.Accion', 1); /// 1 accion de ingresar, 0 de salida
		$this->db->where("tbl_log_usuarios.Negocio_id", $this->session->userdata('Negocio_id'));

		if($Fecha_inicio != 0)   { $this->db->where("DATE_FORMAT( tbl_jornadas.Fecha_inicio, '%Y-%m-%d' ) >=", $Fecha_inicio); }
        if($Fecha_fin != 0)      { $this->db->where("DATE_FORMAT( tbl_jornadas.Fecha_inicio, '%Y-%m-%d' ) <=", $Fecha_fin); }

		$this->db->order_by("tbl_log_usuarios.Id", "desc");
		
		$query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
	}

//// OBTENER LIDERES
	public function obtener_listado_mozos()
	{
			
		//Esto siempre va es para instanciar la base de datos
		$CI =& get_instance();
		$CI->load->database();
		$token = @$CI->db->token;

		$this->db->select('	tbl_usuarios.Id,
							tbl_usuarios.Nombre');
		$this->db->from('tbl_usuarios');
		$this->db->where('Rol_id', 2);
		$this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));
		$this->db->order_by("Nombre", "asc");
		$query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
	}
	
//////////////////////
}