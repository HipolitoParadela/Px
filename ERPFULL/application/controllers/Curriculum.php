<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Curriculum extends CI_Controller
{

//// CURRICULUMS   | VISTA | LISTADO DE TESTEADOS
    public function index()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {

            if ($this->session->userdata('Rol_acceso') > 1) {
                $this->load->view('curriculum_listado');
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }
//// CURRICULUMS   | VISTA | LISTADO DETODOS
    public function todos()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {

            if ($this->session->userdata('Rol_acceso') > 1) {
                $this->load->view('curriculum_listado_todos');
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }
//// CURRICULUMS   | VISTA | DATOS
    public function datos()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {
            ////COMENZAR A FILTRAR Y REDIRECCIONAR SEGUN ROL Y PLAN CONTRATADO
            //if (plan_contratado() > 1) {}

            if ($this->session->userdata('Rol_acceso') > 1) 
            {
                $this->load->view('curriculum_datos');
                
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }

//// CURRICULUMS 	| OBTENER TODOS
	public function obtener_curriculums()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        
         ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

        //$estado = $_GET["estado"];
        //$empresa = $_GET["empresa"];
        //$puesto = $_GET["puesto"];

		$this->db->select('Id,foto,nombre,sexo,telefono,ciudad,fecha, Puntaje');
        
        $this->db->from('curriculum');
        
        $this->db->where('curriculum.Activo', 1);

        /*if ($sexo > 0) {$this->db->where('tbl_usuarios.sexo_id', $sexo);}
        if($edad > 0){$this->db->where('tbl_usuarios.edad_id', $edad);}
        if ($puesto > 0) {$this->db->where('tbl_usuarios.Puesto_Id', $puesto);}*/

        $this->db->order_by("Id", "desc");

        $query = $this->db->get();
        $array_curriculums = $query->result_array();
        
        $Datos = array();

        foreach ($array_curriculums as $curriculum) 
        {

            $this->db->select(' tbl_curriculum_puestos.*,
                                tbl_puestos.Nombre_puesto');

            $this->db->from('tbl_curriculum_puestos');
            $this->db->join('tbl_puestos', 'tbl_puestos.Id = tbl_curriculum_puestos.Puesto_Id', 'left');

            $this->db->where('tbl_curriculum_puestos.Curriculum_Id', $curriculum["Id"]);

            $query = $this->db->get();
            $resultPuestos = $query->result_array();

            //$datoCurriculum = array('Datos'=> , 'Puestos'=> $resultPuestos);
            array_push($curriculum, $resultPuestos);

            array_push($Datos, $curriculum);
        }
		echo json_encode($Datos);
		
    }

//// CURRICULUMS 	| OBTENER TESTEADOS
	public function getListadoCurriculumsTesteados()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        
         ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

        //$estado = $_GET["estado"];
        //$empresa = $_GET["empresa"];
        $puesto = $_GET["puesto"];


        /// CONSULTAR TABLA DE PUESTOS DE CURRICULUM, agrupar resultados por usuario, necesito obtener el listado de usuarios.
            /// si hay filtro, solo obtiene las filas que tengan

        $this->db->select('Curriculum_Id');
        $this->db->from('tbl_curriculum_puestos');  

        if ($puesto > 0) 
        {
            $this->db->where('Puesto_Id', $puesto);
        }

        $this->db->group_by('Curriculum_Id');

        $query = $this->db->get();
        $array_curriculums = $query->result_array();


        /// ARMO EL ARRAY DE CURRICULUMS FILTRADO POR PUESTO
            
            $Array_curriculum_filtrados = array();
            
            foreach ($array_curriculums as $curriculum_1) 
            {
                $this->db->select('Id,foto,nombre,sexo,telefono,ciudad,fecha, Puntaje');

                $this->db->from('curriculum');

                $this->db->where('Activo', 1);
                $this->db->where('Id', $curriculum_1["Curriculum_Id"]);
                
                $query = $this->db->get();
                $result = $query->result_array();

                /// Añade al array si encuentra un usuario activo
                if ($query->num_rows() > 0) { array_push($Array_curriculum_filtrados, $result); }
                
            }

        /// AL ARRAY ANTERIOR LE AGREGO LOS PUESTOS
            $Datos = array();

            foreach ($Array_curriculum_filtrados as $curriculum) 
            {

                $this->db->select(' tbl_curriculum_puestos.*,
                                    tbl_puestos.Nombre_puesto');

                $this->db->from('tbl_curriculum_puestos');
                $this->db->join('tbl_puestos', 'tbl_puestos.Id = tbl_curriculum_puestos.Puesto_Id', 'left');

                $this->db->where('tbl_curriculum_puestos.Curriculum_Id', $curriculum[0]["Id"]);

                $query = $this->db->get();
                $resultPuestos = $query->result_array();

                //$datoCurriculum = array('Datos'=> , 'Puestos'=> $resultPuestos);
                array_push($curriculum, $resultPuestos);

                array_push($Datos, $curriculum);
            }
        
            echo json_encode($Datos);

    }
    
//// CURRICULUMS 	| OBTENER UNO
    public function obtener_curriculum()
    {

        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        $token = @$CI->db->token;

        $Id = $_GET["Id"];

        $this->db->select('*');
        $this->db->from('curriculum');

        $this->db->where('Id', $Id);

        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);

    }

//// CURRICULUM 	    | ACTUALIZAR
	public function actualizar_curriculum()
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
        $Id = null;
		if(isset($this->datosObtenidos->Data->Id))
        {
            $Id = $this->datosObtenidos->Data->Id;
		}
		
		/*$Activo = 1;
		if(isset($this->datosObtenidos->Data->Activo))
        {
            $Activo = $this->datosObtenidos->Data->Activo;
        }*/

        $fecha = date("Y-m-d");

		$data = array(
                        
					'nombre' => 			$this->datosObtenidos->Data->nombre,
					'sexo' => 				$this->datosObtenidos->Data->sexo,
					'nacimiento' => 	    $this->datosObtenidos->Data->nacimiento,
                    'ciudad' => 			$this->datosObtenidos->Data->ciudad,
                    'domicilio' => 		    $this->datosObtenidos->Data->domicilio,
                    'telefono' => 			$this->datosObtenidos->Data->telefono,
					'email' => 			    $this->datosObtenidos->Data->email,
					'cuil' => 	            $this->datosObtenidos->Data->cuil,
					'referencia' => 		$this->datosObtenidos->Data->referencia,
					'hijos' => 		        $this->datosObtenidos->Data->hijos,
					'estadocivil' => 		$this->datosObtenidos->Data->estadocivil,
					'nivelestudios' => 		$this->datosObtenidos->Data->nivelestudios,
					'sobreestudios' => 		$this->datosObtenidos->Data->sobreestudios,
					'laboral' =>            $this->datosObtenidos->Data->laboral,
					'intereses' => 			$this->datosObtenidos->Data->intereses,
                    'personal' => 		    $this->datosObtenidos->Data->personal,
                    'Observaciones' => 		$this->datosObtenidos->Data->Observaciones,
                    'Puntaje' => 		    $this->datosObtenidos->Data->Puntaje,
                    
				);

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'curriculum');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
    }


//// CURRICULUM 	    | CARGAR PUESTO DE CURRICULUM
	public function cargar_puesto_curriculum()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

        $Id = $this->curriculum_existe($this->datosObtenidos->Data->Puesto_Id, $this->datosObtenidos->Id);
		
		/*$Activo = 1;
		if(isset($this->datosObtenidos->Data->Activo))
        {
            $Activo = $this->datosObtenidos->Data->Activo;
        }*/

		$data = array(
                        
					'Puesto_Id' => 			$this->datosObtenidos->Data->Puesto_Id,
					'Curriculum_Id' => 		$this->datosObtenidos->Id,
					'Justificacion' => 	    $this->datosObtenidos->Data->Justificacion,
                    'Usuario_id' => 		$this->session->userdata('Id'),
                    
				);

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_curriculum_puestos');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
    }

//// CURRICULUM 	| CONSULTAR ID 
	public function curriculum_existe($Puesto_Id, $Curriculum_Id)
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }


		$this->db->select('Id');
        $this->db->from('tbl_curriculum_puestos');
        $this->db->where('Puesto_Id', $Puesto_Id);
        $this->db->where('Curriculum_Id', $Curriculum_Id);

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


//// CURRICULUMS 	| OBTENER PUESTOS DE CURRICULUM
	public function obtener_puestos_curriculum()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        
         ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

        $Curriculum_Id = $_GET["Id"];

        $this->db->select(' tbl_curriculum_puestos.*,
                            tbl_puestos.Nombre_puesto');
        
        $this->db->from('tbl_curriculum_puestos');
        $this->db->join('tbl_puestos', 'tbl_puestos.Id = tbl_curriculum_puestos.Puesto_Id', 'left');

        $this->db->where('tbl_curriculum_puestos.Curriculum_Id', $Curriculum_Id);
        
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
    }


//// CURRICULUM 	    | DESACTIVAR
	public function desactivar()
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
        if (isset($this->datosObtenidos->Id)) {
            $Id = $this->datosObtenidos->Id;
        }

		$fecha = date("Y-m-d");

		$data = array(
                        
                'Fecha_eliminado' =>        $fecha,   
                'Activo' => 	            0,
                'Ultimo_editor_id' => 		$this->session->userdata('Id')    
				);

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'curriculum');
                
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


//// | ELIMINAR ALGO
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
///// fin documento
}
