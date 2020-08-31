<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Fabricacion extends CI_Controller
{

//// FABRICACIÓN        | VISTA | LISTADO
    public function index()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {

            if ($this->session->userdata('Rol_id') == 1) {
                $this->load->view('fabricacion_listado');
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }

//// FABRICACIÓN        | VISTA | DATOS
    public function datos()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {
            ////COMENZAR A FILTRAR Y REDIRECCIONAR SEGUN ROL Y PLAN CONTRATADO
            //if (plan_contratado() > 1) {}

            if ($this->session->userdata('Rol_id') == 1) 
            {
                $this->load->view('fabricacion_datos');
                
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }

//// FABRICACIÓN        | OBTENER LISTADO 
	public function obtener_listado_de_productos()
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

        //$estado = $_GET["estado"];
        $categoria = $_GET["categoria"];

		$this->db->select('	tbl_fabricacion.*,
                            tbl_fabricacion_categorias.Nombre_categoria');

        $this->db->from('tbl_fabricacion');

        $this->db->join('tbl_fabricacion_categorias', 'tbl_fabricacion_categorias.Id = tbl_fabricacion.Categoria_fabricacion_id','left');

        $this->db->where('tbl_fabricacion.Visible', 1);

        if($categoria > 0)
        {
            $this->db->where('tbl_fabricacion.Categoria_fabricacion_id', $categoria);
        }

		$this->db->order_by("tbl_fabricacion.Nombre_producto", "asc");
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
    }
    
//// FABRICACIÓN        | OBTENER Datos del item
    public function obtener_datos_id()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        $token = @$CI->db->token;

        $Id = $_GET["Id"];

        $this->db->select('	tbl_fabricacion.*,
                            tbl_fabricacion_categorias.Nombre_categoria');

        $this->db->from('tbl_fabricacion');

        $this->db->join('tbl_fabricacion_categorias', 'tbl_fabricacion_categorias.Id = tbl_fabricacion.Categoria_fabricacion_id', 'left');

        $this->db->where('tbl_fabricacion.Visible', 1);

        $this->db->where('tbl_fabricacion.Id', $Id);


        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);

    }


//// FABRICACIÓN        | DESACTIVAR 
	public function desactivar_producto()
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
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_fabricacion');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
    }

//// FABRICACIÓN ARCH 	| OBTENER Listado archivos
    public function obtener_listado_archivos()
    {

        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        
        $token = @$CI->db->token;
        
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        
        if ($this->datosObtenidos->token != $token) 
        { 
            exit("No coinciden los token");
        }

        $Id = $_GET["Id"];

        $this->db->select(' tbl_fabricacion_archivos.*,
                            tbl_usuarios.Nombre');
        $this->db->from('tbl_fabricacion_archivos');
        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_fabricacion_archivos.Usuario_id', 'left');
        
        $this->db->where('tbl_fabricacion_archivos.Producto_id', $Id);
        $this->db->order_by("tbl_fabricacion_archivos.Nombre_archivo", "desc");
        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);
    }

//// FABRICACIÓN ARCH 	| CARGAR ACTUALIZAR
    public function cargar_archivo()
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
        if (isset($this->datosObtenidos->Datos->Id)) 
        {
            $Id = $this->datosObtenidos->Datos->Id;
        }

        $data = array(

            'Producto_id'       => $this->datosObtenidos->Producto_id,
            'Nombre_archivo'    => $Cantidad = $this->datosObtenidos->Datos->Nombre_archivo,
            'Descripcion'       => $Cantidad = $this->datosObtenidos->Datos->Descripcion,
            'Usuario_id'        => $this->session->userdata('Id'),
        );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_fabricacion_archivos');

        if ($insert_id >= 0) // SI SE CARGO BIEN DEBE ACTUALIZAR LA TABLA tbl_fabricacion, con el calculod de stock actual y el Id de la última actualización
        {
            echo json_encode(array("Id" => $insert_id));
        } 
        else 
        {
            echo json_encode(array("Id" => 0));
        }
    }

//// FABRICACIÓN ARCH 	| OBTENER ULTIMOS archivos REGISTRADOS --- SIRVE PARA EL DASHBOARD
    public function obtener_ultimos_archivos()
    {

        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        $token = @$CI->db->token;

        //$Id = $_GET["Id"];
        $limit = 25;
        $start = 0;
        
        $this->db->select(  'tbl_fabricacion_archivos.*,
                            tbl_usuarios.Nombre,
                            tbl_fabricacion.Nombre_item');
        $this->db->from('tbl_fabricacion_archivos');
        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_fabricacion_archivos.Usuario_id', 'left');
        $this->db->join('tbl_fabricacion', 'tbl_fabricacion.Id = tbl_fabricacion_archivos.Producto_id', 'left');
        
        //$this->db->where('Stock_id', $Id);
        $this->db->order_by("tbl_fabricacion_archivos.Id", "desc");
        $this->db->limit($limit, $start);

        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);

    }

//// FABRICACIÓN ARCH   | ACTUALIZAR DESCRIPCIÓN
	public function actualizarMovimiento()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

		if(isset($this->datosObtenidos->Data->Id))
        {
            $Id = $this->datosObtenidos->Data->Id;
		}

		$data = array(
					'Descripcion' => 		$this->datosObtenidos->Data->Descripcion,
                );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_fabricacion_archivos');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
    }

//// FABRICACIÓN        | CARGAR O EDITAR PRODUCTO
	public function cargar_producto()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

		if(isset($this->datosObtenidos->Data->Id))
        {
            $Id = $this->datosObtenidos->Data->Id;
		}
		
		$data = array(
                        
					'Codigo_interno' => 		        $this->datosObtenidos->Data->Codigo_interno,
					'Categoria_fabricacion_id' => 	    $this->datosObtenidos->Data->Categoria_fabricacion_id,
					'Nombre_producto' => 		        $this->datosObtenidos->Data->Nombre_producto,
                    'Descripcion_publica_corta' => 		$this->datosObtenidos->Data->Descripcion_publica_corta,
                    'Descripcion_publica_larga' => 		$this->datosObtenidos->Data->Descripcion_publica_larga,
                    'Descripcion_tecnica_privada' => 	$this->datosObtenidos->Data->Descripcion_tecnica_privada
                     
                );
                /// 'Ultimo_editor_id' => 		$this->session->userdata('Id')

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_fabricacion');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
    }


//// FABRICACIÓN        | SUBIR FOTO
	public function subirFotoProducto()
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
					$insert_id = $this->App_model->insertar($data, $Id, 'tbl_fabricacion');
					
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
    

//// CATEGORIAS 	    | OBTENER 
	public function obtener_categorias()
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
		$this->db->from('tbl_fabricacion_categorias');
		$this->db->order_by("Nombre_categoria", "asc");
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
    }

//// CATEGORIAS 	    | CARGAR O EDITAR
    public function cargar_categoria()
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
        if (isset($this->datosObtenidos->Data->Id)) 
        {
            $Id = $this->datosObtenidos->Data->Id;
        }

        $Id = null;
        if (isset($this->datosObtenidos->Data->Id)) 
        {
            $Id = $this->datosObtenidos->Data->Id;
        }

        $data = array(

            'Nombre_categoria' => $this->datosObtenidos->Data->Nombre_categoria,
            'Descripcion' => $this->datosObtenidos->Data->Descripcion,

        );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_fabricacion_categorias');

        if ($insert_id >= 0) {
            echo json_encode(array("Id" => $insert_id));
        } else {
            echo json_encode(array("Id" => 0));
        }
    }




//// ARCHIVOS 	        | SUBIR  
	public function subirArchivo()
	{
		$status = "";
		$msg = "";
		$file_element_name = 'Archivo';
		
		if ($status != "error")
		{
			$config['upload_path'] = './uploads/imagenes';
			$config['allowed_types'] = 'jpg|jpeg|doc|docx|xlsx|pdf|dwg|xls|rar';
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
					$insert_id = $this->App_model->insertar($data, $Id, 'tbl_fabricacion_archivos');
					
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


    ///// fin documento
}
