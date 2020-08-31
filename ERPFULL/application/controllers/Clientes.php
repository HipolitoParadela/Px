<?php
defined('BASEPATH') or exit('No direct script access allowed');

class clientes extends CI_Controller
{

//// CLIENTES       | VISTA | LISTADO
    public function index()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {

            if ($this->session->userdata('Rol_acceso') > 1)
            { 
                $this->load->view('clientes_listado');
            } 
            else 
            {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }

//// CLIENTES       | VISTA | DATOS
    public function datos()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {
            ////COMENZAR A FILTRAR Y REDIRECCIONAR SEGUN ROL Y PLAN CONTRATADO
            //if (plan_contratado() > 1) {}

            if ($this->session->userdata('Rol_acceso') > 1) 
            {
                $this->load->view('clientes_datos');
                
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }

//// CLIENTES 	    | OBTENER 
	public function obtener_clientes()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

		$this->db->select('*');
		$this->db->from('tbl_clientes');
        $this->db->where('Visible',1);
		$this->db->order_by("Nombre_cliente", "asc");
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
    }
    
//// CLIENTES 	    | OBTENER 
    public function obtener_datos_cliente()
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

        $this->db->select('*');
        $this->db->from('tbl_clientes');
        $this->db->where('Id', $Id);
        $this->db->order_by("Nombre_cliente", "asc");
        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);

    }

//// CLIENTES 	    | SUBIR FOTO 
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
					$insert_id = $this->App_model->insertar($data, $Id, 'tbl_clientes');
					
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

//// CLIENTES 	    | CARGAR O EDITAR USUARIOS
	public function cargar_cliente()
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
                        
					'Nombre_cliente' => 			$this->datosObtenidos->Datos->Nombre_cliente,
					'Producto_servicio' => 			$this->datosObtenidos->Datos->Producto_servicio,
                    'CUIT_CUIL' => 			        $this->datosObtenidos->Datos->CUIT_CUIL,
					'Direccion' => 				    $this->datosObtenidos->Datos->Direccion,
                    'Localidad' => 			        $this->datosObtenidos->Datos->Localidad,
                    'Provincia' => 		            $this->datosObtenidos->Datos->Provincia,
                    'Pais' => 			            $this->datosObtenidos->Datos->Pais,
                    'Telefono' => 			        $this->datosObtenidos->Datos->Telefono,
                    'Telefono_fijo' => 			    $this->datosObtenidos->Datos->Telefono_fijo,
					'Email' => 	                    $this->datosObtenidos->Datos->Email,
					'Web' => 			            $this->datosObtenidos->Datos->Web,
					'Nombre_persona_contacto' => 	$this->datosObtenidos->Datos->Nombre_persona_contacto,
					'Datos_persona_contacto' => 	$this->datosObtenidos->Datos->Datos_persona_contacto,
					'Mas_datos_cliente' => 			$this->datosObtenidos->Datos->Mas_datos_cliente,
					'Visible' => 		            1
                    
				);

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_clientes');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
    }

//// CLIENTES 	    | DESACTIVAR USUARIO
	public function desactivar_cliente()
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
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_clientes');
                
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
         ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

        $Id = $_GET["Id"];

        $this->db->select(' tbl_clientes_seguimiento.*,
                            tbl_clientes.Nombre_cliente,
                            tbl_usuarios.Nombre');
        
        $this->db->from('tbl_clientes_seguimiento');
        $this->db->join('tbl_clientes', 'tbl_clientes.Id = tbl_clientes_seguimiento.Id_cliente', 'left');
        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_clientes_seguimiento.Usuarios_id', 'left');

        $this->db->where('tbl_clientes_seguimiento.Id_cliente', $Id);
        $this->db->where('tbl_clientes_seguimiento.Visible', 1);

        $this->db->order_by('tbl_clientes_seguimiento.Id', 'desc');
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
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

        $Id = null;
        if (isset($this->datosObtenidos->Datos->Id)) {
            $Id = $this->datosObtenidos->Datos->Id;
        }

        $data = array(

            'Id_cliente' =>     $this->datosObtenidos->Id_cliente,
            'Fecha' =>          $this->datosObtenidos->Datos->Fecha,
            'Descripcion' =>    $this->datosObtenidos->Datos->Descripcion,
            'Usuarios_id' =>    $this->session->userdata('Id'),
            'Visible' =>        1

        );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_clientes_seguimiento');

        if ($insert_id >= 0) {
            echo json_encode(array("Id" => $insert_id));
        } else {
            echo json_encode(array("Id" => 0));
        }
    }
    



///// fin documento
}
