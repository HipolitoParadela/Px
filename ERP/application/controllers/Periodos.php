<?php
defined('BASEPATH') or exit('No direct script access allowed');

class periodos extends CI_Controller
{

//// PERIODOS     | VISTA | LISTADO
    public function index()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {

            if ($this->session->userdata('Rol_id') == 1) {
                $this->load->view('periodos_listado');
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }

//// PERIODOS     | VISTA | DATOS
    public function datos()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {
            ////COMENZAR A FILTRAR Y REDIRECCIONAR SEGUN ROL Y PLAN CONTRATADO
            //if (plan_contratado() > 1) {}

            if ($this->session->userdata('Rol_id') == 1) 
            {
                $this->load->view('ordenes_datos');
                
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }

//// PERIODOS    | OBTENER 
	public function obtener_periodos()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        
        //Seguridad
        $token = @$CI->db->token;
        
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) {
            exit("No coinciden los token");
        }
        
        $this->db->select('*');

        $this->db->from('tbl_periodos');

        $this->db->where('Visible', 1);
        
		$this->db->order_by('Fecha_inicio', 'desc');
        
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
    }
    

//// PERIODOS    | CREAR EDITAR
	public function crear_periodo()
    {
        $CI =& get_instance();
		$CI->load->database();
		
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }
        
        $Id = $this->datosObtenidos->Id;

		$data = array(
                'Nombre_periodo' => $this->datosObtenidos->Datos->Nombre_periodo,
                'Fecha_inicio' => $this->datosObtenidos->Datos->Fecha_inicio,
                'Fecha_fin' => $this->datosObtenidos->Datos->Fecha_fin,
                'Observaciones' => $this->datosObtenidos->Datos->Observaciones,
                'Visible' => 1,
				);

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_periodos');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
    }

//// SEGUIMIENTOS 	| OBTENER Listado
    public function obtener_seguimientos()
    {

        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        
        //Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) {
            exit("No coinciden los token");
        }

        $Id = $_GET["Id"];

        $this->db->select(' tbl_orden_trabajos_seguimiento.*,
                            tbl_usuarios.Nombre');
        
        $this->db->from('tbl_orden_trabajos_seguimiento');
        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_orden_trabajos_seguimiento.Usuario_id', 'left');

        $this->db->where('tbl_orden_trabajos_seguimiento.Orden_id', $Id);
        $this->db->where('tbl_orden_trabajos_seguimiento.Visible', 1);

        $this->db->order_by('tbl_orden_trabajos_seguimiento.Id', 'desc');
        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);

    }

//// SEGUIMIENTOS 	| CARGAR O EDITAR
    public function cargar_seguimiento()
    {
        $CI = &get_instance();
        $CI->load->database();

        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

        $Id = null;
        if (isset($this->datosObtenidos->Datos->Id)) {
            $Id = $this->datosObtenidos->Datos->Id;
        }

        $data = array(

            'Orden_id' =>       $this->datosObtenidos->Orden_id,
            'Descripcion' =>    $this->datosObtenidos->Datos->Descripcion,
            'Usuario_id' =>    $this->session->userdata('Id'),
            'Visible' =>        1

        );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_orden_trabajos_seguimiento');

        if ($insert_id >= 0) {
            echo json_encode(array("Id" => $insert_id));
        } else {
            echo json_encode(array("Id" => 0));
        }
    }



    //// PERIODOS    | LISTADO DASHBOARD
	public function obtener_listado_ordenes_dashboard()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        
        //Seguridad
        $token = @$CI->db->token;
        
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) {
            exit("No coinciden los token");
        }

        $this->db->select(' tbl_orden_trabajos.*,
                            tbl_usuarios.Nombre as Nombre_usuario,
                            tbl_producto.Nombre_producto,
                            tbl_clientes.Nombre_cliente');
        
        $this->db->from('tbl_orden_trabajos');

        $this->db->join('tbl_clientes',                         'tbl_clientes.Id    = tbl_orden_trabajos.Cliente_id', 'left');
        $this->db->join('tbl_usuarios',                         'tbl_usuarios.Id    = tbl_orden_trabajos.Usuario_respondable_id', 'left');
        $this->db->join('tbl_fabricacion as tbl_producto',      'tbl_producto.Id    = tbl_orden_trabajos.Producto_id', 'left');
        //$this->db->join('tbl_fabricacion',                      'tbl_fabricacion.Id = tbl_orden_trabajos.Subproducto_de_id.', 'left');
        
        $this->db->where('tbl_orden_trabajos.Visible', 1);
        
        $this->db->where('tbl_orden_trabajos.Estado', 0);
        
        $this->db->order_by('tbl_orden_trabajos.Fecha_estimada_finalizacion', 'desc');
        $this->db->limit(10);

        
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
    }
    
///// fin documento
}
