<?php
defined('BASEPATH') or exit('No direct script access allowed');

class finanzas extends CI_Controller
{

//// FINANZAS       | VISTA | LISTADO
    public function index()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {

            if ($this->session->userdata('Rol_acceso') > 3) {
                $this->load->view('periodos_listado');
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }

//// FINANZAS       | VISTA | DATOS
    public function datos()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {
            ////COMENZAR A FILTRAR Y REDIRECCIONAR SEGUN ROL Y PLAN CONTRATADO
            //if (plan_contratado() > 3) {}

            if ($this->session->userdata('Rol_acceso') > 3) 
            {
                $this->load->view('periodos_datos');
                
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }

//// FINANZAS       | VISTA | FONDO
    public function fondo()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {
            ////COMENZAR A FILTRAR Y REDIRECCIONAR SEGUN ROL Y PLAN CONTRATADO
            //if (plan_contratado() > 3) {}

            if ($this->session->userdata('Rol_acceso') > 3) 
            {
                $this->load->view('finanzas_fondo');
                
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }




//// MOVIMIENTOS       | CARGAR EFECTIVO
    public function cargar_movimiento()
    {
        $CI =& get_instance();
        $CI->load->database();
        
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

        $Id = null;
        $fecha = date("Y-m-d");

        if(isset($this->datosObtenidos->Datos->Id))
        {
            $Id =       $this->datosObtenidos->Datos->Id;
            $fecha =    $this->datosObtenidos->Datos->Fecha_creado;
        }

        $Observaciones = NULL;  if(isset( $this->datosObtenidos->Datos->Observaciones )) { $Observaciones = $this->datosObtenidos->Datos->Observaciones; }

        /// ONTENER EL ID DE UNA JORNADA
			$this->load->model('Restaurant_model');
			$datos_jornada = $this->Restaurant_model->datos_jornada();
			$Jornada_id = $datos_jornada["Id"];

        $data = array(
                        
                    'Tipo_movimiento' => 	$this->datosObtenidos->Datos->Tipo_movimiento,
                    'Origen_movimiento' => 	$this->datosObtenidos->Origen_movimiento,
                    'Fila_movimiento' =>    $this->datosObtenidos->Fila_movimiento,
                    'Monto_bruto' => 	    $this->datosObtenidos->Datos->Monto_bruto,
                    'Op' =>                 $this->datosObtenidos->Op,
                    'Jornada_id' =>         $Jornada_id,
                    'Fecha_ejecutado' => 	$fecha,
                    'Fecha_cargado' => 	    $fecha,
                    'Usuario_id' => 		$this->session->userdata('Id'),
                    'Observaciones' =>      $Observaciones,  
                    'Negocio_id' => $this->session->userdata('Negocio_id'),                  
                );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_finanzas_movimientos');
                
        if ($insert_id >=0 ) 
        {   
            echo json_encode(array("Id" => $insert_id));         
        } 
        else 
        {
            echo json_encode(array("Id" => 0));
        }
    }

//// MOVIMIENTOS       | OBTENER MOVIMIENTOS
	public function obtener_movimientos()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) { exit("No coinciden los token"); }

        //// Condicional para saber setear el origen del movimiento
        
        $Jornada_id = 0; if(isset($this->datosObtenidos->Jornada_id)) {     $Jornada_id = $this->datosObtenidos->Jornada_id;     }
        $Origen_movimiento = 0; if(isset($this->datosObtenidos->Origen_movimiento)) {     $Origen_movimiento = $this->datosObtenidos->Origen_movimiento;     }
        $Tipo_movimiento = 0; if(isset($this->datosObtenidos->Tipo_movimiento)) {     $Tipo_movimiento = $this->datosObtenidos->Tipo_movimiento;     }
        $Fila_movimiento    = $this->datosObtenidos->Fila_movimiento;

        $this->db->select('*');

        $this->db->from('tbl_finanzas_movimientos');
        
        if($Jornada_id != 0)        {      $this->db->where('tbl_finanzas_movimientos.Jornada_id', $Jornada_id);        }
        if($Origen_movimiento != 0) {      $this->db->where('tbl_finanzas_movimientos.Origen_movimiento', $Origen_movimiento);        }
        if($Tipo_movimiento != 0)   {      $this->db->where('tbl_finanzas_movimientos.Tipo_movimiento', $Tipo_movimiento);        }

        $this->db->where('tbl_finanzas_movimientos.Fila_movimiento', $Fila_movimiento);
        $this->db->where('tbl_finanzas_movimientos.Visible', 1);
        $this->db->where("tbl_finanzas_movimientos.Negocio_id", $this->session->userdata('Negocio_id'));
        
        $query = $this->db->get();
		$arrayMovimientos = $query->result_array();
        
        /////  SUMAR MONTOS
        $Total = 0;
        foreach ($arrayMovimientos as $movimiento) 
        {
            $Total = $Total + $movimiento["Monto_bruto"];
        }
        
        $Datos = array("Datos"=> $arrayMovimientos, "Total_pagado" => $Total);

        echo json_encode($Datos);
    }

//// MOVIMIENTOS       | CARGAR MOVIMIENTO CHEQUES  
    public function cargar_movimiento_cheques()
    {
        $CI =& get_instance();
        $CI->load->database();
        
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

        //// REVISAR - porque fallar ahora la carga de cheques como funciona con nazareno, porque modifique la variable Cheque_id que viene desde app.js

        $Id = null;
        $fecha = date("Y-m-d");
        $Cheque_id = $this->datosObtenidos->Datos->Id;

        /* if(isset($this->datosObtenidos->Datos->Id))
        {
            $Id =       $this->datosObtenidos->Datos->Id;
            $fecha = date("Y-m-d");
            //$fecha =    $this->datosObtenidos->Datos->Fecha_creado;
        } */

        /// ONTENER EL ID DE UNA JORNADA
			$this->load->model('Restaurant_model');
			$datos_jornada = $this->Restaurant_model->datos_jornada();
			$Jornada_id = $datos_jornada["Id"];

        $data = array(
                        
                    'Tipo_movimiento' => 	3,
                    'Origen_movimiento' => 	$this->datosObtenidos->Origen_movimiento,
                    'Fila_movimiento' =>    $this->datosObtenidos->Fila_movimiento,
                    'Cheque_id' => 	        $Cheque_id,
                    'Monto_bruto' => 	    $this->datosObtenidos->Datos->Monto_bruto,
                    'Jornada_id' =>         $Jornada_id,
                    'Op' =>                 $this->datosObtenidos->Op,
                    'Fecha_ejecutado' => 	$fecha,
                    'Fecha_cargado' => 	    $fecha,
                    'Usuario_id' => 		$this->session->userdata('Id'),
                    'Observaciones' =>      $this->datosObtenidos->Datos->Observaciones,  
                    'Negocio_id' =>         $this->session->userdata('Negocio_id'),             
                );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_finanzas_movimientos');
                
        if ($insert_id >=0) 
        {   
           /// FUNCION PARA MARCAR COMO CHEQUE QUE INGRESA O EGRESA... Setear según el origen para definir si entra o sale
            if($this->datosObtenidos->Op == 1)
            {
                 $data = array(
                        
                'Estado' => 1,                  
                );
            }
            else
            {
                $data = array(
                        
                    'Estado' => 2,                  
                    );
            }

                $this->load->model('App_model');
                $insert_id2 = $this->App_model->insertar($data, $Cheque_id, 'tbl_cheques');
                        
                if ($insert_id2 >=0 ) 
                {   
                    //echo json_encode(array("Id" => $insert_id2));         
                } 
            
            
            echo json_encode(array("Id" => $insert_id));
            
        } 
    }

//// MOVIMIENTOS       | OBTENER MOVIMIENTOS CHEQUES
    public function obtener_movimientos_cheques()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) { exit("No coinciden los token"); }

        //// Condicional para saber setear el origen del movimiento

        $Origen_movimiento  = $this->datosObtenidos->Origen_movimiento;
        $Fila_movimiento    = $this->datosObtenidos->Fila_movimiento;
        $Jornada_id = 0; if(isset($this->datosObtenidos->Jornada_id)) { $Jornada_id = $this->datosObtenidos->Datos->Jornada_id;}


        $this->db->select(' tbl_finanzas_movimientos.*,
                            tbl_finanzas_movimientos.Id as Movimiento_id,
                            tbl_cheques.*,
                            tbl_cheques.Id as Cheque_id');

        $this->db->from('tbl_finanzas_movimientos');
        $this->db->join('tbl_cheques', 'tbl_cheques.Id = tbl_finanzas_movimientos.Cheque_id', 'left');

        if($Jornada_id != 0) {
            $this->db->where('Jornada_id', $Jornada_id);
        }
        
        $this->db->where('tbl_finanzas_movimientos.Origen_movimiento', $Origen_movimiento);
        $this->db->where('tbl_finanzas_movimientos.Fila_movimiento', $Fila_movimiento);
        $this->db->where('tbl_finanzas_movimientos.Visible', 1);
        $this->db->where("tbl_finanzas_movimientos.Negocio_id", $this->session->userdata('Negocio_id'));
        $this->db->where('tbl_finanzas_movimientos.Tipo_movimiento', 3); // 1 efectivo, 2 trans/tarj, 3 cheques
        
        $query = $this->db->get();
        $result = $query->result_array();

        /////  SUMAR MONTOS
        $Total_cheques = 0;
        foreach ($result as $cheque) 
        {
            $Total_cheques = $Total_cheques + $cheque["Monto_bruto"];
        }
        
        $Datos = array("Datos"=> $result, "Total_cheques" => $Total_cheques);

        echo json_encode($Datos);
    }

//// CHEQUES      | CARGAR
    public function cargar_cheques()
    {
        $CI =& get_instance();
        $CI->load->database();
        
        ///Seguridad
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

        $fecha = date("Y-m-d");

        $data = array(
                        
                    'Tipo' => 	        $this->datosObtenidos->Datos->Tipo,
                    'Nombre_entrega' => $this->datosObtenidos->Datos->Nombre_entrega,
                    'Monto' => 	        $this->datosObtenidos->Datos->Monto,
                    'Banco' => 	        $this->datosObtenidos->Datos->Banco,
                    'Numero_cheque' => 	$this->datosObtenidos->Datos->Numero_cheque,
                    'Vencimiento' => 	$this->datosObtenidos->Datos->Vencimiento,    
                    'Fecha_cargado' => 	$fecha,
                    'Usuario_id' =>     $this->session->userdata('Id'),
                    'Observaciones' =>  $this->datosObtenidos->Datos->Observaciones,  
                    'Negocio_id' => $this->session->userdata('Negocio_id'),                  
                );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_cheques');
                
        if ($insert_id >=0 ) 
        {   
            echo json_encode(array("Id" => $insert_id,
                                    "Monto_bruto" => $this->datosObtenidos->Datos->Monto,
                                    "Observaciones" => $this->datosObtenidos->Datos->Observaciones));         
        } 
        else 
        {
            echo json_encode(array("Id" => 0));
        }
    }

//// CHEQUES 	  | SUBIR IMAGEN 
    public function subirImagen()
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
                        'Imagen' =>		$nombre_archivo,
                    );

                    $this->load->model('App_model');
                    $insert_id = $this->App_model->insertar($data, $Id, 'tbl_cheques');
                    
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
        echo json_encode(array('status' => $status, 'Imagen' => $nombre_archivo));
    }

//// CHEQUES      | OBTENER
    public function obtener_cheques()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) { exit("No coinciden los token"); }

        $Estado = $this->datosObtenidos->Estado;

        $this->db->select('*');
        $this->db->from('tbl_cheques');
        if($Estado > 0)
        {
            $this->db->where('Estado', $Estado);
        }
        $this->db->where('Visible', 1);
        $this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));
        $this->db->order_by("Vencimiento", "asc");
    
        $query = $this->db->get();
        $result = $query->result_array();

        //// FUNCIÓN PARA SETEAR SI ESTA VENCIDO O NO
            // consultar si fecha de vencimiento es menor o igual a date("")

        echo json_encode($result);
    }


//// CHEQUES      | OBTENER DIFERENCIADOS POR VENCIMIENTO
    public function obtener_cheques_diferenciados()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) { exit("No coinciden los token"); }

        $fecha = date('Y-m-d');
        $treintaDias = strtotime('30 day', strtotime($fecha));
        $treintaDias = date('Y-m-d', $treintaDias);

        //// CHEQUES VENCIDOS
        $this->db->select('*');
        $this->db->from('tbl_cheques');
        $this->db->where('Estado', 1);
        $this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));
        $this->db->where("DATE_FORMAT(Vencimiento,'%Y-%m-%d') <=", $fecha);

        $this->db->where('Visible', 1);
        $this->db->order_by("Vencimiento", "asc");
    
        $query = $this->db->get();
        $array_vencidos = $query->result_array();

        //// CHEQUES QUE VENCEN DENTRO DE LOS PROXIMOS 30 DÍAS
        $this->db->select('*');
        $this->db->from('tbl_cheques');
        $this->db->where('Estado', 1);
    
        $this->db->where("DATE_FORMAT(Vencimiento,'%Y-%m-%d') >", $fecha);
        $this->db->where("DATE_FORMAT(Vencimiento,'%Y-%m-%d') <=", $treintaDias);
        $this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));
        $this->db->where('Visible', 1);
        $this->db->order_by("Vencimiento", "asc");
    
        $query = $this->db->get();
        $array_cerca = $query->result_array();

        //// CHEQUES QUE VENCEN PASADOS LOS 30 DÍAS
        $this->db->select('*');
        $this->db->from('tbl_cheques');
        $this->db->where('Estado', 1);
    
        $this->db->where("DATE_FORMAT(Vencimiento,'%Y-%m-%d') >", $treintaDias);
        $this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));
        $this->db->where('Visible', 1);
        $this->db->order_by("Vencimiento", "asc");
    
        $query = $this->db->get();
        $array_lejos = $query->result_array();


        $Datos = array('Vencidos' => $array_vencidos, 'Cerca' => $array_cerca, 'Lejos' => $array_lejos);

        echo json_encode($Datos);
    }


//// FONDO       | OBTENER MOVIMIENTOS EFECTIVO
	public function obtener_movimientos_efectivo_fondo()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance(); 
        $CI->load->database();
        
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) { exit("No coinciden los token"); }

        $this->db->select('tbl_finanzas_movimientos.*, tbl_usuarios.Nombre');

        $this->db->from('tbl_finanzas_movimientos');
        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_finanzas_movimientos.Usuario_id', 'left');

        $this->db->where('tbl_finanzas_movimientos.Visible', 1);
        $this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));
        $this->db->where('tbl_finanzas_movimientos.Tipo_movimiento', 1); // 1 efectivo, 2 trans/tarj, 3 cheques
        
        if($this->datosObtenidos->Fecha_inicio != null)
        {
            $this->db->where("DATE_FORMAT(Fecha_ejecutado,'%Y-%m-%d') >=", $this->datosObtenidos->Fecha_inicio);
        }
        if($this->datosObtenidos->Fecha_fin != null)
        {
            $this->db->where("DATE_FORMAT(Fecha_ejecutado,'%Y-%m-%d') <=", $this->datosObtenidos->Fecha_fin);
        }
        if($this->datosObtenidos->Jornada_id != 0)
        {
            $this->db->where("Jornada_id", $this->datosObtenidos->Jornada_id);
        }
            
        $this->db->order_by("tbl_finanzas_movimientos.Id", "desc");

        $query = $this->db->get();
		$result = $query->result_array();
        
        /////  SUMAR MONTOS
        $Total = 0;
        $Entrante = 0;
        $Saliente = 0;
        foreach ($result as $monto) 
        {
            if($monto["Op"] == true){
                $Entrante = $Entrante + $monto["Monto_bruto"];
            }
            else {
                $Saliente = $Saliente + $monto["Monto_bruto"];
            }

            $Total = $Entrante - $Saliente;
        }
        
        $Datos = array("Datos"=> $result, "Total" => $Total, "Entrante" => $Entrante, "Saliente" => $Saliente);

        echo json_encode($Datos);
    }


//// FONDO       | OBTENER MOVIMIENTOS TRANSFERENCIAS
    public function obtener_movimientos_transferencia_fondo()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) { exit("No coinciden los token"); }


        $this->db->select(' tbl_finanzas_movimientos.*,
                            tbl_usuarios.Nombre');

        $this->db->from('tbl_finanzas_movimientos');
        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_finanzas_movimientos.Usuario_id', 'left');

        $this->db->where('tbl_finanzas_movimientos.Visible', 1);
        $this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));
        $this->db->where('tbl_finanzas_movimientos.Tipo_movimiento', 2); // 1 efectivo, 2 trans/tarj, 3 cheques

        if($this->datosObtenidos->Fecha_inicio != null)
        {
            $this->db->where("DATE_FORMAT(tbl_finanzas_movimientos.Fecha_ejecutado,'%Y-%m-%d') >=", $this->datosObtenidos->Fecha_inicio);
        }
        if($this->datosObtenidos->Fecha_fin != null)
        {
            $this->db->where("DATE_FORMAT(tbl_finanzas_movimientos.Fecha_ejecutado,'%Y-%m-%d') <=", $this->datosObtenidos->Fecha_fin);
        }
        if($this->datosObtenidos->Jornada_id != 0)
        {
            $this->db->where("Jornada_id", $this->datosObtenidos->Jornada_id);
        }
        $this->db->order_by("tbl_finanzas_movimientos.Id", "desc");
        
        $query = $this->db->get();
        $result = $query->result_array();
        
        /////  SUMAR MONTOS --------- Necesito 3 variables, las que sumas, las que restas y las de ingresos brutos. 
        $Total = 0;
        $Montos_entrantes = 0;
        $Montos_salientes = 0;
        $Ing_brutos = 0;
        foreach ($result as $monto) 
        {
            if($monto["Op"] == true)
            {
                $Montos_entrantes = $Montos_entrantes + $monto["Monto_bruto_bruto"];
                $Ing_brutos = $Ing_brutos + $monto["Retencion_ing_brutos"];
            }
            else
            {
                $Montos_salientes = $Montos_salientes + $monto["Monto_bruto_bruto"];
            }

            $Total = $Montos_entrantes - $Montos_salientes - $Ing_brutos;
            /* if($monto["Op"] == true){
                $Total = $Total + $monto["Monto_bruto_bruto"] - $monto["Retencion_ing_brutos"];
            }
            else {
                $Total = $Total - $monto["Monto_bruto_bruto"] - $monto["Retencion_ing_brutos"];
            } */
        }
        
        $Datos = array("Datos"=> $result, "Total" => $Total, "Montos_entrantes" => $Montos_entrantes, "Montos_salientes" => $Montos_salientes, "Ing_brutos" => $Ing_brutos);

        echo json_encode($Datos);
    }

//// FONDO       | OBTENER MOVIMIENTOS CHEQUES
    public function obtener_movimientos_cheques_fondo()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) { exit("No coinciden los token"); }

        $this->db->select(' tbl_cheques.Banco,
                            tbl_cheques.Monto,
                            tbl_cheques.Tipo,
                            tbl_cheques.Numero_cheque,
                            tbl_cheques.Nombre_entrega,
                            tbl_cheques.Imagen,
                            tbl_cheques.Vencimiento,
                            tbl_finanzas_movimientos.Op,
                            tbl_cheques.Estado,
                            tbl_finanzas_movimientos.Origen_movimiento,
                            tbl_finanzas_movimientos.Fecha_ejecutado,
                            tbl_finanzas_movimientos.Observaciones,
                            tbl_usuarios.Nombre');

        $this->db->from('tbl_finanzas_movimientos');
        $this->db->join('tbl_cheques', 'tbl_cheques.Id = tbl_finanzas_movimientos.Cheque_id', 'left');
        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_finanzas_movimientos.Usuario_id', 'left');

        $this->db->where('tbl_finanzas_movimientos.Visible', 1);
        $this->db->where('tbl_finanzas_movimientos.Tipo_movimiento', 3); // 1 efectivo, 2 trans/tarj, 3 cheques
        $this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));

        if($this->datosObtenidos->Fecha_inicio != null)
        {
            $this->db->where("DATE_FORMAT(tbl_finanzas_movimientos.Fecha_ejecutado,'%Y-%m-%d') >=", $this->datosObtenidos->Fecha_inicio);
        }
        if($this->datosObtenidos->Fecha_fin != null)
        {
            $this->db->where("DATE_FORMAT(tbl_finanzas_movimientos.Fecha_ejecutado,'%Y-%m-%d') <=", $this->datosObtenidos->Fecha_fin);
        }
        if($this->datosObtenidos->Jornada_id != 0)
        {
            $this->db->where("Jornada_id", $this->datosObtenidos->Jornada_id);
        }
        $this->db->order_by("tbl_finanzas_movimientos.Id", "desc");
        
        $query = $this->db->get();
        $result = $query->result_array();

        /////  SUMAR MONTOS
        $Total = 0;
        $Entrante = 0;
        $Saliente = 0;
        foreach ($result as $monto) 
        {
            if($monto["Op"] == 1){
                $Entrante = $Entrante + $monto["Monto_bruto"];
            }
            elseif($monto["Op"] == 0) {
                $Saliente = $Saliente + $monto["Monto_bruto"];
            }
            
            $Total = $Entrante - $Saliente;
        }
        
        $Datos = array("Datos"=> $result, "Total" => $Total, "Entrante" => $Entrante, "Saliente" => $Saliente);

        echo json_encode($Datos);
    }

///// fin documento
}
