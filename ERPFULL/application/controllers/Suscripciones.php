<?php
defined('BASEPATH') or exit('No direct script access allowed');

class suscripciones extends CI_Controller
{

//// SUSCRIPCIONES   | VISTA | LISTADO
    public function index()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {

            if ($this->session->userdata('Rol_acceso') > 1)
            { 
                $this->load->view('suscripciones_listado');
            } 
            else 
            {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }

//// SUSCRIPCIONES   | VISTA | DATOS
    public function datos()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {
            ////COMENZAR A FILTRAR Y REDIRECCIONAR SEGUN ROL Y PLAN CONTRATADO
            //if (plan_contratado() > 1) {}

            if ($this->session->userdata('Rol_acceso') > 1) 
            {
                $this->load->view('suscripciones_datos');
                
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }

//// SUSCRIPCIONES 	| OBTENER 
	public function obtener_suscripciones()
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

        $hoy = date("Y-m-d");
        $Categoria_id = $_GET["Categoria_id"];

        $this->db->select(  'tbl_suscripciones.*,
                            tbl_suscripciones_categorias.Nombre_categoria,
                            tbl_clientes.Nombre_cliente');
		$this->db->from('tbl_suscripciones');
        
        $this->db->join('tbl_suscripciones_categorias', 'tbl_suscripciones_categorias.Id = tbl_suscripciones.Categoria_id', 'left');
        $this->db->join('tbl_clientes', 'tbl_clientes.Id = tbl_suscripciones.Cliente_id', 'left');

        $this->db->where('tbl_suscripciones.Visible',1);

        if($Categoria_id > 0)
        {
            $this->db->where('tbl_suscripciones.Categoria_id', $Categoria_id);
        }

        if($_GET["Estado"] == 1)
        {
             // controlar por fecha de inicio y fecha final... hoy debe ser mayor o igual al inicio y mayor o igual al final
             $this->db->where("DATE_FORMAT(tbl_suscripciones.Fecha_inicio_servicio,'%Y-%m-%d') <=", $hoy);
            
             $this->db->group_start(); //this will start grouping
                 $this->db->where("DATE_FORMAT(tbl_suscripciones.Fecha_finalizacion_servicio,'%Y-%m-%d') >=", $hoy);
                 $this->db->or_where('tbl_suscripciones.Fecha_finalizacion_servicio', null);
             $this->db->group_end(); //this will end grouping;
        }

		$this->db->order_by("tbl_suscripciones.Titulo_suscripcion", "asc");
        
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
    }
    
//// SUSCRIPCIONES 	| OBTENER 
    public function obtener_datos_suscripcion()
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

        $this->db->select(  'tbl_suscripciones.*,
                            tbl_suscripciones.Imagen as Imagen_suscripcion,
                            tbl_suscripciones_categorias.Nombre_categoria,
                            tbl_clientes.Nombre_cliente,
                            tbl_clientes.Direccion,
                            tbl_clientes.Localidad,
                            tbl_clientes.Provincia,
                            tbl_clientes.Telefono,
                            tbl_clientes.Email');

        $this->db->from('tbl_suscripciones');

        $this->db->join('tbl_clientes', 'tbl_clientes.Id = tbl_suscripciones.Cliente_id', 'left');
        $this->db->join('tbl_suscripciones_categorias', 'tbl_suscripciones_categorias.Id = tbl_suscripciones.Categoria_id', 'left');

        $this->db->where('tbl_suscripciones.Id', $Id);
        $this->db->order_by("tbl_suscripciones.Titulo_suscripcion", "asc");

        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);

    }

//// SUSCRIPCIONES 	| SUBIR FOTO 
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
					$insert_id = $this->App_model->insertar($data, $Id, 'tbl_suscripciones');
					
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

//// SUSCRIPCIONES 	| CARGAR O EDITAR 
	public function cargar_suscripcion()
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
                        
					'Titulo_suscripcion' => 	        $this->datosObtenidos->Datos->Titulo_suscripcion,
                    'Cliente_id' => 			        $this->datosObtenidos->Datos->Cliente_id,
                    'Categoria_id' => 			        $this->datosObtenidos->Datos->Categoria_id,
                    'Monto' => 			                $this->datosObtenidos->Datos->Monto,
                    'Datos_persona_contacto' =>         $this->datosObtenidos->Datos->Datos_persona_contacto,
                    'Fecha_inicio_servicio' =>          $this->datosObtenidos->Datos->Fecha_inicio_servicio,
                    'Fecha_finalizacion_servicio' =>    $this->datosObtenidos->Datos->Fecha_finalizacion_servicio,
                    'Observaciones' =>                  $this->datosObtenidos->Datos->Observaciones,
					'Visible' => 		                1
                    
				);

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_suscripciones');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
    }

//// SUSCRIPCIONES 	| DESACTIVAR 
	public function desactivar_suscripcion()
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
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_suscripciones');
                
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

        $this->db->select(' tbl_suscripciones_seguimiento.*,
                            tbl_suscripciones.Titulo_suscripcion,
                            tbl_usuarios.Nombre');
        
        $this->db->from('tbl_suscripciones_seguimiento');
        $this->db->join('tbl_suscripciones', 'tbl_suscripciones.Id = tbl_suscripciones_seguimiento.Suscripcion_id', 'left');
        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_suscripciones_seguimiento.Usuario_id', 'left');

        $this->db->where('tbl_suscripciones_seguimiento.Suscripcion_id', $Id);
        $this->db->where('tbl_suscripciones_seguimiento.Visible', 1);

        $this->db->order_by('tbl_suscripciones_seguimiento.Id', 'desc');
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
        { exit("No coinciden los token"); }

        $Id = null;
        if (isset($this->datosObtenidos->Datos->Id)) {
            $Id = $this->datosObtenidos->Datos->Id;
        }

        $data = array(

            'Suscripcion_id' =>     $this->datosObtenidos->Suscripcion_id,
            'Fecha' =>              $this->datosObtenidos->Datos->Fecha,
            'Descripcion' =>        $this->datosObtenidos->Datos->Descripcion,
            'Usuario_id' =>        $this->session->userdata('Id'),
            'Visible' =>            1

        );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_suscripciones_seguimiento');

        if ($insert_id >= 0) {
            echo json_encode(array("Id" => $insert_id));
        } else {
            echo json_encode(array("Id" => 0));
        }
    }
    

//// CATEGORIAS SUSCRIPCIONES 	    | OBTENER 
    public function obtener_categorias()
    {
            
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        $token = @$CI->db->token;

        $this->db->select('*');
        $this->db->from('tbl_suscripciones_categorias');
        
        $this->db->order_by("Nombre_categoria", "asc");
        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);
        
    }

//// CATEGORIAS SUSCRIPCIONES 	| CARGAR O EDITAR 
    public function cargar_categoria()
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
                        
                    'Nombre_categoria' => 	$this->datosObtenidos->Datos->Nombre_categoria,
                    'Descripcion' => 		$this->datosObtenidos->Datos->Descripcion,
                    
                );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_suscripciones_categorias');
                
        if ($insert_id >=0 ) 
        {   
            echo json_encode(array("Id" => $insert_id));         
        } 
        else 
        {
            echo json_encode(array("Id" => 0));
        }
    }


//// SUSCRIPCIONES       | OBTENER listado todas
    public function obtener_periodos()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) {  exit("No coinciden los token"); }

        $Suscripcion_id = $_GET["Id"];
        $Fecha_inicio_suscripcion = $this->datosObtenidos->Fecha_inicio;
        $Fecha_final_suscripcion =  $this->datosObtenidos->Fecha_final;

        /// BUSCAR EL LISTADO DE PERÍODOS
        $this->db->select('*');
        $this->db->from('tbl_periodos');
        
        // controlar por fecha de inicio y fecha final... hoy debe ser mayor o igual al inicio y mayor o igual al final
        $this->db->where("DATE_FORMAT(Fecha_inicio,'%Y-%m-%d') >=", $Fecha_inicio_suscripcion);
            
        $this->db->group_start(); //this will start grouping
            $this->db->where("DATE_FORMAT(Fecha_cierre,'%Y-%m-%d') <=", $Fecha_final_suscripcion);
            $this->db->or_where('Fecha_cierre', null);
        $this->db->group_end(); //this will end grouping; */
        
        $this->db->where('Visible',1);
		$this->db->order_by("Fecha_inicio", "desc");
        
        $query = $this->db->get();
		$array_periodos = $query->result_array();

        /// AVERIGUAR PERIODO POR PERIODO, SI ESTE USUARIO TIENE CREADA LA EXPENSA        
            $Datos = array(); 
            
            foreach ($array_periodos as $periodo) 
            {
                
                $this->db->select('*');
                $this->db->from('tbl_suscripciones_cobros');

                $this->db->where('Periodo_id', $periodo["Id"]);
                $this->db->where('Suscripcion_id', $Suscripcion_id);
                $this->db->where('Visible',1);
                
                $query = $this->db->get();
                $resultPagoSuscripcion = $query->result_array();
                $cant = $query->num_rows();

                $Datos_cobro_suscripcion = array();
                $Estado = 0;
                $Total_abonado = 0;
                //// SI ENCUENTRA UNA EXPENSA EMPIEZA A BUSCAR DATOS
                if($cant > 0)
                {
                    $Datos_cobro_suscripcion = $resultPagoSuscripcion;
                    $Estado = $resultPagoSuscripcion[0]["Estado"];
                    
                    /// CONSULTANDO MONTOS ABONADOS EN EFECTIVO
                        $this->db->select('Monto');
                        $this->db->from('tbl_dinero_efectivo');
                        $this->db->where('Origen_movimiento', 'Suscripcion');
                        $this->db->where('Fila_movimiento', $Suscripcion_id);
                        $this->db->where('Periodo_id', $periodo["Id"]);
                        $this->db->where('Visible', 1);
                        
                        $query = $this->db->get();
                        $resultMontoEfectivo = $query->result_array();
                        
                        /////  SUMAR MONTOS
                        $Total_efectivo = 0;
                        foreach ($resultMontoEfectivo as $montos) 
                        {
                            $Total_efectivo = $Total_efectivo + $montos["Monto"];
                        }
                    
                    ///MONTO ABONADO EN TRANSFERENCIA
                        $this->db->select('Monto_bruto, Retencion_ing_brutos');
                        $this->db->from('tbl_dinero_transferencias');
                        $this->db->where('Origen_movimiento', 'Suscripcion');
                        $this->db->where('Fila_movimiento', $Suscripcion_id);
                        $this->db->where('Periodo_id', $periodo["Id"]);
                        $this->db->where('Visible', 1);
                        
                        $query = $this->db->get();
                        $resultMontosTransferencia = $query->result_array();
                    
                        /////  SUMAR MONTOS
                        $Total_transferencias = 0;
                        foreach ($resultMontosTransferencia as $montos) 
                        {
                            $Total_transferencias = $Total_transferencias + $montos["Monto_bruto"]; /*  - $montos["Retencion_ing_brutos"] */
                        }

                    ///MONTO ABONADO CON CHEQUES
                        $this->db->select('  tbl_cheques.Monto');
                        $this->db->from('tbl_dinero_cheques');
                        $this->db->join('tbl_cheques', 'tbl_cheques.Id = tbl_dinero_cheques.Cheque_id', 'left');
                        $this->db->where('Origen_movimiento', 'Suscripcion');
                        $this->db->where('Fila_movimiento', $Suscripcion_id);
                        $this->db->where('Periodo_id', $periodo["Id"]);
                        $this->db->where('tbl_dinero_cheques.Visible', 1);
                        
                        $query = $this->db->get();
                        $result = $query->result_array();

                    /////  SUMAR MONTOS
                    $Total_cheques = 0;
                    foreach ($result as $monto) 
                    {
                        $Total_cheques = $Total_cheques + $monto["Monto"];
                    }
                    
                    // SUMANDO TOTALES ABONADOS
                    $Total_abonado = $Total_efectivo + $Total_transferencias + $Total_cheques;
                }



                $datos_periodo = array('Datos_periodo' => $periodo, 'Datos_cobro_suscripcion' => $Datos_cobro_suscripcion, "Estado" => $Estado, 'Total_abonado' => $Total_abonado);
                /// HAY INCONVENIENTES ACA CON LOS ARRAY CUANDO APARECEN VACIOS O LLENOS... 

                array_push($Datos, $datos_periodo);
            }
        
        echo json_encode($Datos);
        
    }
///// fin documento
}
