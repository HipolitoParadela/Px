<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usuarios extends CI_Controller
{

//// USUARIOS       | VISTA | LISTADO
    public function index()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {

            if ($this->session->userdata('Rol_acceso') > 4 || $this->session->userdata('Id') == 12) 
            {
                $this->load->view('usuarios_listado');
            } 
            else 
            {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }

//// USUARIOS       | VISTA | DATOS
    public function datos()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {
            ////COMENZAR A FILTRAR Y REDIRECCIONAR SEGUN ROL Y PLAN CONTRATADO
            //if (plan_contratado() > 3) {}

            if ($this->session->userdata('Rol_acceso') > 4 || $this->session->userdata('Id') == 12) 
            {
                $this->load->view('usuarios_datos');
                
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }

//// USUARIOS       | VISTA | RESUMEN REPORTES
    public function resumenreportes()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {
            ////COMENZAR A FILTRAR Y REDIRECCIONAR SEGUN ROL Y PLAN CONTRATADO
            //if (plan_contratado() > 3) {}

            if ($this->session->userdata('Rol_acceso') > 4 || $this->session->userdata('Id') == 12) 
            {
                $this->load->view('usuarios_resumen_puntajes');
                
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }

//// USUARIOS 	    | OBTENER USUARIOS
	public function obtener_Usuarios()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

        $estado = $_GET["estado"];
        $empresa = $_GET["empresa"];
        $puesto = $_GET["puesto"];


		$this->db->select('	tbl_usuarios.*,
                            tbl_roles.Nombre_rol,
                            tbl_empresas.Nombre_empresa,
                            tbl_puestos.Nombre_puesto,
                            tbl_lider.Nombre as Nombre_lider');
		$this->db->from('tbl_usuarios');
        
        $this->db->join('tbl_roles', 'tbl_roles.Acceso = tbl_usuarios.Rol_acceso','left');
        $this->db->join('tbl_empresas', 'tbl_empresas.Id = tbl_usuarios.Empresa_id', 'left');
        $this->db->join('tbl_puestos', 'tbl_puestos.Id = tbl_usuarios.Puesto_Id', 'left');
        $this->db->join('tbl_usuarios as tbl_lider', 'tbl_lider.Id = tbl_usuarios.Superior_inmediato', 'left');


        $this->db->where('tbl_usuarios.Activo',$estado);

        if($empresa > 0)
        {
            $this->db->where('tbl_usuarios.Empresa_id', $empresa);
        }
        if ($puesto > 0) 
        {
            $this->db->where('tbl_usuarios.Puesto_Id', $puesto);
        }


		$this->db->order_by("Nombre", "asc");
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
    }
    
//// USUARIOS 	    | OBTENER USUARIOS
    public function obtener_Usuario()
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

        $this->db->select('	tbl_usuarios.*,
							tbl_roles.Nombre_rol,
							Superior.Nombre as Nombre_superior');
        $this->db->from('tbl_usuarios');
        $this->db->join('tbl_roles', 'tbl_roles.Acceso = tbl_usuarios.Rol_acceso', 'left');
        $this->db->join('tbl_usuarios as Superior', 'Superior.Id = tbl_usuarios.Superior_inmediato', 'left');
        $this->db->where('tbl_usuarios.Id', $Id);
        $this->db->order_by("Nombre", "asc");
        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);

    }

//// FORMACIONES 	| OBTENER FORMACIONES
    public function obtener_formaciones()
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

        $this->db->select('tbl_usuarios_formacion.*');
        $this->db->from('tbl_usuarios_formacion');
        $this->db->where('Usuario_id', $Id);
        $this->db->order_by("Anio_inicio", "asc");
        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);

    }

//// FORMACIONES 	| CARGAR O EDITAR FORMACION
    public function cargar_formacion()
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
        if (isset($this->datosObtenidos->formacionData->Id)) {
            $Id = $this->datosObtenidos->formacionData->Id;
        }

        $data = array(

            'Titulo' => $this->datosObtenidos->formacionData->Titulo,
            'Usuario_id' => $this->datosObtenidos->Usuario_id,
            'Establecimiento' => $this->datosObtenidos->formacionData->Establecimiento,
            'Anio_inicio' => $this->datosObtenidos->formacionData->Anio_inicio,
            'Anio_finalizado' => $this->datosObtenidos->formacionData->Anio_finalizado,
            'Descripcion_titulo' => $this->datosObtenidos->formacionData->Descripcion_titulo,
        );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_usuarios_formacion');

        if ($insert_id >= 0) {
            echo json_encode(array("Id" => $insert_id));
        } else {
            echo json_encode(array("Id" => 0));
        }
    }

//// USUARIOS 	    | OBTENER Roles
    public function obtener_roles()
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

        $this->db->select('*');
        $this->db->from('tbl_roles');
        $this->db->order_by("Acceso", "desc");
        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);

    }

//// USUARIOS 	    | OBTENER LIDERES
    public function obtener_lideres()
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

        $this->db->select('	tbl_usuarios.Id,
							tbl_usuarios.Nombre');
        $this->db->from('tbl_usuarios');
        $this->db->where('Lider', 1);
        $this->db->order_by("Nombre", "desc");
        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);

    }

//// USUARIOS 	    | CARGAR O EDITAR USUARIOS
	public function cargar_Usuarios()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

        //$Id = $this->usuario_existe($this->datosObtenidos->usuarioData->DNI);

		if(isset($this->datosObtenidos->usuarioData->Id))
        {
            $Id = $this->datosObtenidos->usuarioData->Id;
		}
		
		/*$Activo = 1;
		if(isset($this->datosObtenidos->usuarioData->Activo))
        {
            $Activo = $this->datosObtenidos->usuarioData->Activo;
        }*/

        $fecha = date("Y-m-d");

		$data = array(
                        
					'Nombre' => 			$this->datosObtenidos->usuarioData->Nombre,
                    'DNI' => 				$this->datosObtenidos->usuarioData->DNI,
                    'CUIT_CUIL' => 			$this->datosObtenidos->usuarioData->CUIT_CUIL,
					'Pass' => 				$this->datosObtenidos->usuarioData->Pass,
                    'Rol_acceso' => 			$this->datosObtenidos->usuarioData->Rol_acceso,
                    'Empresa_id' => 		$this->datosObtenidos->usuarioData->Empresa_id,
                    'Puesto_Id' => 			$this->datosObtenidos->usuarioData->Puesto_Id,
					'Telefono' => 			$this->datosObtenidos->usuarioData->Telefono,
					'Fecha_nacimiento' => 	$this->datosObtenidos->usuarioData->Fecha_nacimiento,
					'Domicilio' => 			$this->datosObtenidos->usuarioData->Domicilio,
					'Nacionalidad' => 		$this->datosObtenidos->usuarioData->Nacionalidad,
					'Genero' => 			$this->datosObtenidos->usuarioData->Genero,
					'Email' => 				$this->datosObtenidos->usuarioData->Email,
					'Obra_social' => 		$this->datosObtenidos->usuarioData->Obra_social,
					'Numero_obra_social' => $this->datosObtenidos->usuarioData->Numero_obra_social,
					'Hijos' => 				$this->datosObtenidos->usuarioData->Hijos,
					'Estado_civil' => 		$this->datosObtenidos->usuarioData->Estado_civil,
					'Datos_persona_contacto' => 	$this->datosObtenidos->usuarioData->Datos_persona_contacto,
					'Datos_bancarios' => 			$this->datosObtenidos->usuarioData->Datos_bancarios,
					'Periodo_liquidacion_sueldo' => $this->datosObtenidos->usuarioData->Periodo_liquidacion_sueldo,
					'Horario_laboral' => 			$this->datosObtenidos->usuarioData->Horario_laboral,
					'Lider' => 				    $this->datosObtenidos->usuarioData->Lider,
					'Superior_inmediato' => 		$this->datosObtenidos->usuarioData->Superior_inmediato,
					'Fecha_alta' => 		    $this->datosObtenidos->usuarioData->Fecha_alta,
                    'Observaciones' => 		    $this->datosObtenidos->usuarioData->Observaciones,
                    'Presencia' => 1,
                    'Activo' => 			    1,
                    'Ultima_actualizacion' =>   $fecha,
                    'Ultimo_editor_id' => 		$this->session->userdata('Id') 
                    
				);

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_usuarios');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
    }

//// USUARIOS 	    | DESACTIVAR USUARIO
	public function desactivar_usuario()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

		//$Id = $this->usuario_existe($this->datosObtenidos->usuarioData->DNI);
        $Id = $this->datosObtenidos->Id;

		$fecha = date("Y-m-d");

		$data = array(
                        
                'Fecha_baja' =>             $fecha,   
                'Activo' => 	            0,
                'Ultima_actualizacion' =>   $fecha,
                'Ultimo_editor_id' => 		$this->session->userdata('Id')    
				);

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_usuarios');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }

        /// , A TENER EN CUENTA PARA LLEVAR UN SEGUIMIENTO DE QUIEN ELIMINO A ESTE USUARIO
    }

//// USUARIOS 	| SUBIR FOTO USUARIO
	public function subirFotoUsuario()
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
					$insert_id = $this->App_model->insertar($data, $Id, 'tbl_usuarios');
					
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
    

//// EMPRESAS 	| OBTENER 
	public function obtener_empresas()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;


		$this->db->select('*');
		$this->db->from('tbl_empresas');
		$this->db->order_by("Nombre_empresa", "asc");
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
    }

//// EMPRESAS 	| CARGAR O EDITAR EMPRESA
    public function cargar_empresa()
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
        if (isset($this->datosObtenidos->Data->Id)) {
            $Id = $this->datosObtenidos->Data->Id;
        }

        $data = array(

            'Nombre_empresa' => $this->datosObtenidos->Data->Nombre_empresa,
            'Descripcion' => $this->datosObtenidos->Data->Descripcion,

        );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_empresas');

        if ($insert_id >= 0) {
            echo json_encode(array("Id" => $insert_id));
        } else {
            echo json_encode(array("Id" => 0));
        }
    }

//// PUESTOS 	| OBTENER 
	public function obtener_puestos()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;


		$this->db->select('*');
		$this->db->from('tbl_puestos');
		$this->db->order_by("Nombre_puesto", "asc");
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
    }

//// PUESTOS 	| CARGAR O EDITAR
    public function cargar_puesto()
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
        if (isset($this->datosObtenidos->Data->Id)) {
            $Id = $this->datosObtenidos->Data->Id;
        }

        $data = array(

            'Nombre_puesto' =>  $this->datosObtenidos->Data->Nombre_puesto,
            'Descripcion' =>    $this->datosObtenidos->Data->Descripcion,

        );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_puestos');

        if ($insert_id >= 0) {
            echo json_encode(array("Id" => $insert_id));
        } else {
            echo json_encode(array("Id" => 0));
        }
    }


//// USUARIOS 	| OBTENER 
	public function usuario_existe($DNI)
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;


		$this->db->select('Id');
        $this->db->from('tbl_usuarios');
        $this->db->where('DNI', $DNI);

        $query = $this->db->get();
		$result = $query->result_array();

        if ($query->num_rows() > 0) 
        {
            $result = $query->row_array()['Id'];
        } else {
            $result = null;
        }
        
        return $result;
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

        $this->db->select(' tbl_usuarios_seguimiento.*,
                            tbl_usuarios.Nombre,
                            tbl_rrhh.Nombre as Nombre_rrhh,
                            tbl_usuarios_seguimiento_categorias.Nombre_categoria');
        
        $this->db->from('tbl_usuarios_seguimiento');
        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_usuarios_seguimiento.Usuario_id', 'left');
        $this->db->join('tbl_usuarios as tbl_rrhh', 'tbl_rrhh.Id = tbl_usuarios_seguimiento.Usuario_rrhh_id', 'left');
        $this->db->join('tbl_usuarios_seguimiento_categorias', 'tbl_usuarios_seguimiento_categorias.Id = tbl_usuarios_seguimiento.Categoria_id', 'left');

        $this->db->where('tbl_usuarios_seguimiento.Usuario_id', $Id);
        $this->db->where('tbl_usuarios_seguimiento.Visible', 1);

        $this->db->order_by('tbl_usuarios_seguimiento.Fecha', 'desc');
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

            'Usuario_id' =>         $this->datosObtenidos->Usuario_id,
            'Fecha' =>              $this->datosObtenidos->Datos->Fecha,
            'Descripcion' =>        $this->datosObtenidos->Datos->Descripcion,
            'Calificacion' =>       $this->datosObtenidos->Datos->Calificacion,
            'Usuario_rrhh_id' =>    $this->session->userdata('Id'),
            'Categoria_id' =>       $this->datosObtenidos->Datos->Categoria_id,
            'Visible' =>            1

        );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_usuarios_seguimiento');

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
                    $insert_id = $this->App_model->insertar($data, $Id, 'tbl_usuarios_seguimiento');
                    
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

//// SEGUIMIENTOS 	| OBTENER Listado
    public function obtener_categorias_seguimientos()
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

        $this->db->select('*');
        
        $this->db->from('tbl_usuarios_seguimiento_categorias');
        $this->db->where('tbl_usuarios_seguimiento_categorias.Visible', 1);

        $this->db->order_by('tbl_usuarios_seguimiento_categorias.Nombre_categoria', 'asc');
        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);

    }

//// SEGUIMIENTOS CATEGORIAS 	| CARGAR O EDITAR FORMACION
    public function cargar_categorias_seguimiento()
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

            'Nombre_categoria' =>         $this->datosObtenidos->Datos->Nombre_categoria,
            'Descripcion' =>              $this->datosObtenidos->Datos->Descripcion,

        );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_usuarios_seguimiento_categorias');

        if ($insert_id >= 0) {
            echo json_encode(array("Id" => $insert_id));
        } else {
            echo json_encode(array("Id" => 'Error'));
        }
    }

//// RESUMEN REPORTES   | OBTENER TABLA
	//// RESUMEN REPORTES   | OBTENER TABLA
	public function obtener_resumenReportes()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

           
        /// ARRAY DE USUARIOS
            $estado = 1;
            $empresa = $_GET["empresa"];
            $puesto = $_GET["puesto"];

            $this->db->select('	tbl_usuarios.*,
                                tbl_roles.Nombre_rol,
                                tbl_empresas.Nombre_empresa,
                                tbl_puestos.Nombre_puesto,
                                tbl_lider.Nombre as Nombre_lider');
            $this->db->from('tbl_usuarios');
            $this->db->join('tbl_roles', 'tbl_roles.Acceso = tbl_usuarios.Rol_acceso','left');
            $this->db->join('tbl_empresas', 'tbl_empresas.Id = tbl_usuarios.Empresa_id', 'left');
            $this->db->join('tbl_puestos', 'tbl_puestos.Id = tbl_usuarios.Puesto_Id', 'left');
            $this->db->join('tbl_usuarios as tbl_lider', 'tbl_lider.Id = tbl_usuarios.Superior_inmediato', 'left');
            $this->db->where('tbl_usuarios.Activo',$estado);

            if($empresa > 0) { $this->db->where('tbl_usuarios.Empresa_id', $empresa); }
            if ($puesto > 0) {  $this->db->where('tbl_usuarios.Puesto_Id', $puesto); }

            $this->db->order_by("Nombre", "asc");
            $query = $this->db->get();
            $array_usuarios = $query->result_array();

        /// ARRAY DE CATEGORIAS
            $this->db->select('*');
            $this->db->from('tbl_usuarios_seguimiento_categorias');
            $this->db->where('Visible', 1);
            $this->db->order_by('Nombre_categoria', 'asc');
            $query = $this->db->get();
            $array_categorias = $query->result_array();
        
        
        
        /// Armando el array final
            $Datos_reportes = array();
            
            foreach ($array_usuarios as $usuario) 
            {
                $Datos_reportes_usuarios = array();
                $suma_calificaciones_totales = 0;
                $cant_calificaciones_encontradas = 0;
            
                // comienzo a recorrer categoría por categoría, buscando los reportes que pueda tener este usuario y generando un promedio.
                foreach ($array_categorias as $categoria) 
                {

                    $this->db->select('Calificacion');
                    $this->db->from('tbl_usuarios_seguimiento');
                    $this->db->where('Usuario_id', $usuario["Id"]);
                    $this->db->where('Categoria_id', $categoria["Id"]);
                    $this->db->where('Visible', 1);
                    
                    $query = $this->db->get();
                    $result_calificaciones = $query->result_array();

                    if ($query->num_rows() > 0) 
                    {
                        $suma_calificaciones = 0;

                        // recorre todas las calificaciones que encuentre de ese usuario y de esa categoria
                        foreach ($result_calificaciones as $calificacion) 
                        {
                            $suma_calificaciones = $suma_calificaciones + $calificacion["Calificacion"];
                        }
                        
                        $promedio = $suma_calificaciones / $query->num_rows();
                        $promedio = round($promedio, 2);

                        $suma_calificaciones_totales = $suma_calificaciones_totales + $suma_calificaciones; /// sumando calificaciones, para dar con promedio general
                        $cant_calificaciones_encontradas = $cant_calificaciones_encontradas + $query->num_rows(); /// sumando cantidad de calificaciones encontradas, para dar con el divisor general
                    } 
                    else 
                    {
                        $promedio = "";
                    }

                    array_push($Datos_reportes_usuarios, $promedio);
                    
                }
                
                if($cant_calificaciones_encontradas > 0)
                {
                    $promedio_general = $suma_calificaciones_totales / $cant_calificaciones_encontradas; /// sacando promedio general de la persona
                    $promedio_general = round($promedio_general, 2);
                }
                else
                {
                    $promedio_general = ""; 
                }
                    
                
                $datos_usuario = array(
                                        "Datos_usuario" => $usuario,
                                        "Datos_reportes_usuario" => $Datos_reportes_usuarios,
                                        "Promedio_general" => $promedio_general,
                                    );
                
                array_push( $Datos_reportes, $datos_usuario);
            
            }
            
            
            // necesito, un array de las categorias solas, y otro de los usaurios y su tabla, incluso también uno de los totales por categoría. si no es mucho lio.
            $Datos = array(
                            "Categorias" => $array_categorias,
                            "Reportes" => $Datos_reportes
                        );
            
        echo json_encode($Datos);
		
    }

///// fin documento
}
