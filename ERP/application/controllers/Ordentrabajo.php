<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ordentrabajo extends CI_Controller
{

//// ORDEN DE TRABAJO       | VISTA | LISTADO
    public function index()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {

            if ($this->session->userdata('Rol_id') == 1) {
                $this->load->view('ordenes_listado');
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }

//// ORDEN DE TRABAJO       | VISTA | DATOS
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

//// ORDEN DE TRABAJO 	    | OBTENER 
	public function obtener_listado_ordenes()
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
        
        ///Filtros
        $Producto_id = $_GET["Producto_id"];
        $Usuario_id = $_GET["Usuario_id"];
        $Cliente_id = $_GET["Cliente_id"];

        $this->db->select(' tbl_orden_trabajos.*,
                            tbl_usuarios.Nombre as Nombre_usuario,
                            tbl_producto.Nombre_producto,
                            tbl_clientes.Nombre_cliente,
                            tbl_periodos.Nombre_periodo');
        
        $this->db->from('tbl_orden_trabajos');

        $this->db->join('tbl_clientes',  'tbl_clientes.Id    = tbl_orden_trabajos.Cliente_id', 'left');
        $this->db->join('tbl_usuarios',  'tbl_usuarios.Id    = tbl_orden_trabajos.Usuario_respondable_id', 'left');
        $this->db->join('tbl_fabricacion as tbl_producto', 'tbl_producto.Id = tbl_orden_trabajos.Producto_id', 'left');
        $this->db->join('tbl_periodos', 'tbl_periodos.Id = tbl_orden_trabajos.Periodo_id', 'left');
        //$this->db->join('tbl_fabricacion',                      'tbl_fabricacion.Id = tbl_orden_trabajos.Subproducto_de_id.', 'left');
        
        if($Producto_id > 0)    { $this->db->where('tbl_orden_trabajos.Producto_id', $Producto_id); }
        if($Usuario_id > 0)     { $this->db->where('tbl_orden_trabajos.Usuario_respondable_id', $Usuario_id); }
        if($Cliente_id > 0)     {$this->db->where('tbl_orden_trabajos.Cliente_id', $Cliente_id);}

        $this->db->where('tbl_orden_trabajos.Visible', 1);
        
        if($_GET["Estado"] < 4) /// esto es porque si necesito la lista completa sin discriminar por estado, Mando un valor 4 al estado
        {
            $this->db->where('tbl_orden_trabajos.Estado', $_GET["Estado"]);
        }
        
		$this->db->order_by('tbl_orden_trabajos.Fecha_estimada_finalizacion', 'asc');
        
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
    }
    
//// ORDEN DE TRABAJO 	    | OBTENER 
    public function obtener_datos_orden()
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

        $this->db->select(' tbl_orden_trabajos.*,
                            tbl_usuarios.Nombre as Nombre_usuario,
                            tbl_producto.Nombre_producto,
                            tbl_producto.Imagen,
                            tbl_clientes.Nombre_cliente');
        
        $this->db->from('tbl_orden_trabajos');

        $this->db->join('tbl_clientes',                         'tbl_clientes.Id    = tbl_orden_trabajos.Cliente_id', 'left');
        $this->db->join('tbl_usuarios',                         'tbl_usuarios.Id    = tbl_orden_trabajos.Usuario_respondable_id', 'left');
        $this->db->join('tbl_fabricacion as tbl_producto',      'tbl_producto.Id    = tbl_orden_trabajos.Producto_id', 'left');

        $this->db->where('tbl_orden_trabajos.Id', $Id);
        $this->db->where('tbl_orden_trabajos.Visible', 1);
		$this->db->order_by('tbl_orden_trabajos.Fecha_estimada_finalizacion', 'asc');
        
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);

    }



//// ORDEN DE TRABAJO 	    | CARGAR O EDITAR 
	public function cargar_orden()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

        $Id = null;
        if(isset($this->datosObtenidos->Data->Id))
        {
            $Id = $this->datosObtenidos->Data->Id;
		}

        
        $data = array(
                        
                    'Periodo_id' => 			    $this->datosObtenidos->Data->Periodo_id,        
                    'Producto_id' => 			    $this->datosObtenidos->Data->Producto_id,
					'Subproducto_de_id' => 			$this->datosObtenidos->Data->Subproducto_de_id,
                    'Usuario_respondable_id' => 	$this->datosObtenidos->Data->Usuario_respondable_id,
                    'Cliente_id' => 	            $this->datosObtenidos->Data->Cliente_id,
                    'Numero_pieza' => 			    $this->datosObtenidos->Data->Numero_pieza,
                    'Fecha_inicio' => 		        $this->datosObtenidos->Data->Fecha_inicio,
                    'Fecha_estimada_finalizacion'=> $this->datosObtenidos->Data->Fecha_estimada_finalizacion,
					'Observaciones' => 			    $this->datosObtenidos->Data->Observaciones,
					'Usuario_id' => 	            $this->session->userdata('Id'),
					
				);

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_orden_trabajos');
                
        //// si cargo bien esto, toma el id de la orden y la carga en la tbl_ordentrabajo_seguimietno
        if ($insert_id >=0 ) 
		{   
            $data = array(

            'Orden_id' =>       $insert_id,
            'Descripcion' =>    "Inicio de fabricación",
            'Usuario_id' =>    $this->session->userdata('Id'),
            'Visible' =>        1
            );

            $this->load->model('App_model');
            $insert_id_seguimiento = $this->App_model->insertar($data, $Id, 'tbl_orden_trabajos_seguimiento');
                
            echo json_encode(array("Id" => $insert_id, "Seguimiento_id" => $insert_id_seguimiento));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
    }

//// ORDEN DE TRABAJO 	    | CAMBIAR ESTADO
	public function cambiar_estado_orden()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

        $Id = $_GET["Id"];
        
        $estado = $this->datosObtenidos->Estado;

        /// dependiendo el estado, setea una fecha u otra
        if($estado == 1) 
        {  
            $data = array(
                'Fecha_finalizado' => $this->datosObtenidos->Fecha,
                'Estado' => $estado,
            );
        }
        if($estado == 2) 
        {  
            $data = array(
                'Fecha_despacho_cliente' => $this->datosObtenidos->Fecha,
                'Estado' => $estado,
            );
        }

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_orden_trabajos');
                
        //// si cargo bien esto, toma el id de la orden y la carga en la tbl_ordentrabajo_seguimietno
        if ($insert_id >=0 ) 
		{   
  
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
    }

//// ORDEN DE TRABAJO 	    | DESACTIVAR USUARIO
	public function desactivar_Orden()
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
                        
                'Visible' => 0,
				);

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_orden_trabajos');
                
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



    //// ORDEN DE TRABAJO 	    | LISTADO DASHBOARD
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
        
        $this->db->order_by('tbl_orden_trabajos.Fecha_estimada_finalizacion', 'asc');
        $this->db->limit(10);

        
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
    }
    
///// fin documento
}
