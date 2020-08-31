<?php
defined('BASEPATH') or exit('No direct script access allowed');

class compras extends CI_Controller
{

//// COMPRAS       | VISTA | LISTADO
    public function index()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {

            if ($this->session->userdata('Rol_id') == 1) {
                $this->load->view('compras_listado');
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }

//// COMPRAS       | VISTA | DATOS
    public function datos()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {
            ////COMENZAR A FILTRAR Y REDIRECCIONAR SEGUN ROL Y PLAN CONTRATADO
            //if (plan_contratado() > 1) {}

            if ($this->session->userdata('Rol_id') == 1) 
            {
                $this->load->view('compras_datos');
                
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }

//// COMPRAS 	   | OBTENER listado todas
	public function obtener_compras()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

        $this->db->select(' tbl_compras.*,
                            tbl_proveedores.Nombre_proveedor,
                            tbl_usuarios.Nombre,
                            tbl_periodos.Nombre_periodo');
        $this->db->from('tbl_compras');
        
        $this->db->join('tbl_proveedores', 'tbl_proveedores.Id = tbl_compras.Proveedor_id', 'left');
        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_compras.Usuario_id', 'left');
        $this->db->join('tbl_periodos', 'tbl_periodos.Id = tbl_compras.Periodo_id', 'left');

        $this->db->where('tbl_compras.Visible',1);
		$this->db->order_by("tbl_compras.Fecha_compra", "desc");
        
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
    }


//// COMPRAS 	   | OBTENER listado proveedor
	public function obtener_compras_proveedor()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        $token = @$CI->db->token;
        
        $Proveedor_id = $_GET["Id"];

        $this->db->select(' tbl_compras.*,
                            tbl_proveedores.Nombre_proveedor,
                            tbl_usuarios.Nombre,
                            tbl_periodos.Nombre_periodo');
        $this->db->from('tbl_compras');
        
        $this->db->join('tbl_proveedores', 'tbl_proveedores.Id = tbl_compras.Proveedor_id', 'left');
        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_compras.Usuario_id', 'left');
        $this->db->join('tbl_periodos', 'tbl_periodos.Id = tbl_compras.Periodo_id', 'left');

        $this->db->where('tbl_compras.Visible',1);
        $this->db->where('tbl_compras.Proveedor_id', $Proveedor_id);

		$this->db->order_by("tbl_compras.Fecha_compra", "desc");
        
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
    }
    
//// COMPRAS 	   | OBTENER DATOS DE UNA COMPRA
    public function obtener_datos_compra()
    {

        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
         ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

        $Id = $_GET["Id"];

        $this->db->select(' tbl_compras.*,
                            tbl_proveedores.Id as Proveedor_id,
                            tbl_proveedores.Nombre_proveedor,
                            tbl_proveedores.Email,
                            tbl_proveedores.Telefono,
                            tbl_proveedores.Web,
                            tbl_usuarios.Nombre');
        $this->db->from('tbl_compras');
        
        $this->db->join('tbl_proveedores', 'tbl_proveedores.Id = tbl_compras.Proveedor_id', 'left');
        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_compras.Usuario_id', 'left');
        $this->db->where('tbl_compras.Id', $Id);
        $this->db->where('tbl_compras.Visible',1);
		$this->db->order_by("tbl_compras.Fecha_compra", "desc");
        
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);

    }

//// COMPRAS 	   | SUBIR FOTO 
	public function subirFoto()
	{
		$status = "";
		$msg = "";
		$file_element_name = 'Archivo';
		
		if ($status != "error")
		{
			$config['upload_path'] = './uploads/imagenes';
			$config['allowed_types'] = 'gif|jpg|jpeg|png';
			$config['max_size'] = 1024 * 8;
			$config['encrypt_name'] = TRUE;
	
			$this->load->library('upload', $config);
	
			if (!$this->upload->do_upload($file_element_name))
			{
				$status = 'error';
				$msg = $this->upload->display_errors('', '');
			}
			else
			{
				/// coloco el dato en la base de datos
					$Id = $_GET["Id"];
					
					$data = $this->upload->data();
					
					$file_info = $this->upload->data();
					$nombre_imagen = $file_info['file_name'];
					
					$data = array(    
						'Imagen' =>		$nombre_imagen,
					);

					$this->load->model('App_model');
					$insert_id = $this->App_model->insertar($data, $Id, 'tbl_compras');
					
					// $file_id = $this->files_model->insert_file($data['file_name'], $_POST['title']);
					if($insert_id > 0)
					{
						$status = 1;
						$msg = "File successfully uploaded";
					}
					else
					{
						unlink($data['full_path']);
						$status = 0;
						$msg = "Something went wrong when saving the file, please try again.";
					}
			}
			@unlink($_FILES[$file_element_name]);
		}
		echo json_encode(array('status' => $status, 'Imagen' => $nombre_imagen));
    }

//// COMPRAS 	   | CARGAR O EDITAR
	public function cargar_compra()
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
        if(isset($this->datosObtenidos->Datos->Id))
        {
            $Id = $this->datosObtenidos->Datos->Id;
		}

		$data = array(
                        
                    'Proveedor_id' => 			$this->datosObtenidos->Datos->Proveedor_id,
                    'Periodo_id' => 			$this->datosObtenidos->Datos->Periodo_id,
					'Fecha_compra' => 			$this->datosObtenidos->Datos->Fecha_compra,
					'Factura_identificador' => 	$this->datosObtenidos->Datos->Factura_identificador,
                    'Valor' => 			        $this->datosObtenidos->Datos->Valor,
                    'Usuario_id' => 		    $this->session->userdata('Id'),
                    'Descripcion' => 			$this->datosObtenidos->Datos->Descripcion,                    
				);

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_compras');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
    }

//// COMPRAS 	   | DESACTIVAR COMPRA
	public function desactivar_compra()
    {
        //// ESTA FUNCION DE FUNCIONAR DEBE ELIMINAR TODOS LOS ITEMS COMPRADOS, Y RESTAR DEL STOCK. EN LO POSIBLE NO SERÍA BUENO QUE EXISTA
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
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_compras');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
    }


//// COMPRAS 	   | OBTENER listado 25 Dashboard
	public function obtener_compras_dashboarad()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

        $this->db->select(' tbl_compras.*,
                            tbl_proveedores.Nombre_proveedor');
        $this->db->from('tbl_compras');
        
        $this->db->join('tbl_proveedores', 'tbl_proveedores.Id = tbl_compras.Proveedor_id', 'left');

        $this->db->where('tbl_compras.Visible',1);
        $this->db->order_by("tbl_compras.Fecha_compra", "desc");
        $this->db->limit(25);

        
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
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

        $this->db->select(' tbl_compras_seguimiento.*,
                            tbl_usuarios.Nombre');
        
        $this->db->from('tbl_compras_seguimiento');
        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_compras_seguimiento.Usuarios_id', 'left');

        $this->db->where('tbl_compras_seguimiento.Compra_id', $Id);
        $this->db->where('tbl_compras_seguimiento.Visible', 1);

        $this->db->order_by('tbl_compras_seguimiento.Id', 'desc');
        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);

    }

//// SEGUIMIENTOS 	| CARGAR O EDITAR FORMACION
    public function cargar_seguimiento()
    {
        $CI = &get_instance();
        $CI->load->database();

        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) {
            exit("No coinciden los token");
        }

        $Id = null;
        if(isset($this->datosObtenidos->Datos->Id))
        {
            $Id = $this->datosObtenidos->Datos->Id;
        }

        $data = array(

            'Compra_id' =>      $this->datosObtenidos->Compra_id,
            'Fecha' =>          $this->datosObtenidos->Datos->Fecha,
            'Descripcion' =>    $this->datosObtenidos->Datos->Descripcion,
            'Usuarios_id' =>    $this->session->userdata('Id'),
            'Visible' =>        1

        );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_compras_seguimiento');

        if ($insert_id >= 0) {
            echo json_encode(array("Id" => $insert_id));
        } else {
            echo json_encode(array("Id" => 'Error'));
        }
    }

//// SEGUIMIENTOS 	| SUBIR FOTO 
    public function subirFotoSeguimiento()
    {
        $status = "";
        $msg = "";
        $file_element_name = 'Archivo';
        
        if ($status != "error")
        {
            $config['upload_path'] = './uploads/imagenes';
            $config['allowed_types'] = 'jpg|jpeg|doc|docx|xlsx|pdf';
            $config['max_size'] = 0;
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload($file_element_name))
            {
                $status = 'error';
                $msg = $this->upload->display_errors('', '');
            }
            else
            {
                /// coloco el dato en la base de datos
                    $Id = $_GET["Id"];
                    
                    $data = $this->upload->data();
                    
                    $file_info = $this->upload->data();
                    $nombre_archivo = $file_info['file_name'];
                    
                    $data = array(    
                        'Url_archivo' =>		$nombre_archivo,
                    );

                    $this->load->model('App_model');
                    $insert_id = $this->App_model->insertar($data, $Id, 'tbl_compras_seguimiento');
                    
                    // $file_id = $this->files_model->insert_file($data['file_name'], $_POST['title']);
                    if($insert_id > 0)
                    {
                        $status = 1;
                        $msg = "File successfully uploaded";
                    }
                    else
                    {
                        unlink($data['full_path']);
                        $status = 0;
                        $msg = "Something went wrong when saving the file, please try again.";
                    }
            }
            @unlink($_FILES[$file_element_name]);
        }
        echo json_encode(array('status' => $status, 'Url_archivo' => $nombre_archivo));
    }
    //// LAS FUNCIONES DE OBTENER Y DE CARGAR INGRESOS Y EGRESOS DE STOCK SE HACEN DESDE EL CONTROLLER STOCK
///// fin documento
}
