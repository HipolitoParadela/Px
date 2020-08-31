<?php
defined('BASEPATH') or exit('No direct script access allowed');

class periodos extends CI_Controller
{

//// PERIODOS       | VISTA | LISTADO
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

//// PERIODOS       | VISTA | DATOS
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

//// PERIODOS       | OBTENER listado todas
	public function obtener_periodos()
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

        $this->db->select(' tbl_periodos.*,
                            tbl_usuarios.Nombre');
        $this->db->from('tbl_periodos');

        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_periodos.Usuario_id', 'left');

        $this->db->where('tbl_periodos.Visible', 1);
		$this->db->order_by("tbl_periodos.Fecha_cierre", "desc");
        
        $query = $this->db->get();
		$result = $query->result_array();

        
        /// tengo que hacer un scrip que me calcule los ingresos y egresos en este periodo, con un foreach
            /// por ahora que muestre solamente los datos
            $datos = array('Datos' => $result, 'Saldo' => 0);
        
        echo json_encode($datos);
		
    }


//// PERIODOS       | CARGAR O EDITAR
	public function cargar_periodo()
    {
        $CI =& get_instance();
		$CI->load->database();
		
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) {  exit("No coinciden los token"); }

        $Id = null;
        $fecha = date("Y-m-d");

        if(isset($this->datosObtenidos->Datos->Id))
        {
            $Id =       $this->datosObtenidos->Datos->Id;
            $fecha =    $this->datosObtenidos->Datos->Fecha_creado;
		}

		$data = array(
                        
					'Identificador' => 			$this->datosObtenidos->Datos->Identificador,
					'Fecha_inicio' => 			$this->datosObtenidos->Datos->Fecha_inicio,
                    'Fecha_cierre' => 	        $this->datosObtenidos->Datos->Fecha_cierre,
                    'Fecha_creado' => 	        $fecha,
                    'Usuario_id' => 		    $this->session->userdata('Id'),
                    'Observaciones_iniciales' => $this->datosObtenidos->Datos->Observaciones_iniciales,                    
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

//// PERIODOS       | OBTENER DATOS DE UN PERIODO
	public function obtener_datos_periodo()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) {  exit("No coinciden los token"); }

        $Id = $_GET["Id"];

        $this->db->select(' tbl_periodos.*,
                            tbl_usuarios.Nombre');
        $this->db->from('tbl_periodos');

        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_periodos.Usuario_id', 'left');

        $this->db->where('tbl_periodos.Visible', 1);
        $this->db->where('tbl_periodos.Id', $Id);

		$this->db->order_by("tbl_periodos.Fecha_cierre", "desc");
        
        $query = $this->db->get();
		$result = $query->result_array();


        /// tengo que hacer un scrip que me calcule los ingresos y egresos en este periodo, con un foreach
            /// por ahora que muestre solamente los datos
            $datos = array('Datos' => $result, 'Saldo' => 0);
        
        echo json_encode($datos);
		
    }






//// SUSCRIPCIONES       | OBTENER listado todas
    public function obtener_suscripciones()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) {  exit("No coinciden los token"); }

        $Periodo_id = $_GET["Id"];

        $hoy = date("Y-m-d");
        $Fecha_inicio_periodo = $this->datosObtenidos->Fecha_inicio;
        $Fecha_final_periodo =  $this->datosObtenidos->Fecha_final;

        /// BUSCAR EL LISTADO DE SUSCRIPCIONES ACTIVAS
        $this->db->select( 'tbl_suscripciones.*,
                            tbl_suscripciones_categorias.Nombre_categoria,
                            tbl_clientes.Nombre_cliente');

		$this->db->from('tbl_suscripciones');
        
        $this->db->join('tbl_suscripciones_categorias', 'tbl_suscripciones_categorias.Id = tbl_suscripciones.Categoria_id', 'left');
        $this->db->join('tbl_clientes', 'tbl_clientes.Id = tbl_suscripciones.Cliente_id', 'left');

        ///////////////

            // controlar por fecha de inicio y fecha final... hoy debe ser mayor o igual al inicio y mayor o igual al final
            $this->db->where("DATE_FORMAT(tbl_suscripciones.Fecha_inicio_servicio,'%Y-%m-%d') <=", $Fecha_inicio_periodo);
            
            $this->db->group_start(); //this will start grouping
                $this->db->where("DATE_FORMAT(tbl_suscripciones.Fecha_finalizacion_servicio,'%Y-%m-%d') >=", $Fecha_final_periodo);
                $this->db->or_where('tbl_suscripciones.Fecha_finalizacion_servicio', null);
            $this->db->group_end(); //this will end grouping;
        
        
        $this->db->where('tbl_suscripciones.Visible',1);
		$this->db->order_by("tbl_suscripciones.Titulo_suscripcion", "asc");
        
        $query = $this->db->get();
		$array_suscripcions = $query->result_array();

        /// AVERIGUAR UNO POR UNO SI YA TIENE CREADA UNA EXPENSA DE TAL PERIODO        
            $Datos = array(); 
            
            foreach ($array_suscripcions as $suscripcion) 
            {
                
                $this->db->select('*');
                $this->db->from('tbl_suscripciones_cobros');

                $this->db->where('Periodo_id',      $Periodo_id);
                $this->db->where('Suscripcion_id',  $suscripcion["Id"]);
                $this->db->where('Visible', 1);
                
                $query = $this->db->get();
                $resultCobro = $query->result_array();
                $cant = $query->num_rows();

                $Datos_cobros = array();
                $Estado = 0;
                $Total_abonado = 0;

                //// SI ENCUENTRA UN COBRO EMPIEZA A BUSCAR DATOS
                if($cant > 0)
                {
                    $Datos_cobros = $resultCobro;
                    $Estado = $resultCobro[0]["Estado"];
                    
                    /// CONSULTANDO MONTOS ABONADOS EN EFECTIVO
                        $this->db->select('Monto');
                        $this->db->from('tbl_dinero_efectivo');
                        $this->db->where('Origen_movimiento', 'Suscripcion');
                        $this->db->where('Fila_movimiento', $suscripcion["Id"]);
                        $this->db->where('Periodo_id', $Periodo_id);
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
                        $this->db->select('Monto_bruto,  Retencion_ing_brutos');
                        $this->db->from('tbl_dinero_transferencias');
                        $this->db->where('Origen_movimiento', 'Suscripcion');
                        $this->db->where('Fila_movimiento', $suscripcion["Id"]);
                        $this->db->where('Periodo_id', $Periodo_id);
                        $this->db->where('Visible', 1);
                        
                        $query = $this->db->get();
                        $resultMontosTransferencia = $query->result_array();
                    
                    /////  SUMAR MONTOS
                    $Total_transferencias = 0;
                    foreach ($resultMontosTransferencia as $montos) 
                    {
                        $Total_transferencias = $Total_transferencias + $montos["Monto_bruto"] - $montos["Retencion_ing_brutos"];
                    }

                    ///MONTO ABONADO CON CHEQUES
                        $this->db->select('  tbl_cheques.Monto');
                        $this->db->from('tbl_dinero_cheques');
                        $this->db->join('tbl_cheques', 'tbl_cheques.Id = tbl_dinero_cheques.Cheque_id', 'left');
                        $this->db->where('Origen_movimiento', 'Suscripcion');
                        $this->db->where('Fila_movimiento', $suscripcion["Id"]);
                        $this->db->where('Periodo_id', $Periodo_id);
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

                $datos_suscripcion = array('Datos_suscripcion' => $suscripcion, 'Datos_cobros' => $Datos_cobros, "Estado" => $Estado, 'Total_abonado' => $Total_abonado);
                /// HAY INCONVENIENTES ACA CON LOS ARRAY CUANDO APARECEN VACIOS O LLENOS... 

                array_push($Datos, $datos_suscripcion);
            }
        
        echo json_encode($Datos);
        
    }

//// SUSCRIPCIONES       | CARGAR O EDITAR
    public function crearCobro()
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
        $Fecha = null;

        if(isset($this->datosObtenidos->Datos->Id))
        {
            $Id =  $this->datosObtenidos->Datos->Id;
            $Fecha = date("Y-m-d");
        }

        $data = array(
                        
                    'Periodo_id' => 		$this->datosObtenidos->Periodo_id,
                    'Suscripcion_id' => 	$this->datosObtenidos->Suscripcion_id,
                    'Valor_cobro' => 		$this->datosObtenidos->Datos->Valor_cobro,
                    'Fecha_pago' => 		$this->datosObtenidos->Datos->Fecha_pago,
                    'Valor_interes' => 		$this->datosObtenidos->Datos->Valor_interes,
                    'Estado' => 		    $this->datosObtenidos->Datos->Estado, 
                    'Fecha_carga' => 		$Fecha,
                    'Ultimo_usuario_id' => 		$this->session->userdata('Id'),            
                );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_suscripciones_cobros');
                
        if ($insert_id >=0 ) 
        {   
            echo json_encode(array("Id" => $insert_id));         
        } 
        else 
        {
            echo json_encode(array("Id" => 0));
        }
    }

//// SUSCRIPCIONES       |  INGRESOS SEGUN PERIODO
	public function ingresos_suscripciones_por_periodo()
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

        $Periodo_id = $_GET["Id"];

        /// CALCULANDO INGRESOS EFECTIVO
            $this->db->select('Monto');
            $this->db->from('tbl_dinero_efectivo');

            $this->db->where('Origen_movimiento', 'Suscripcion');
            $this->db->where('Periodo_id', $Periodo_id);
            $this->db->where('Visible', 1);
            
            $query = $this->db->get();
            $arrayEfectivo = $query->result_array();
            
            $Total_efectivo = 0;
            foreach ($arrayEfectivo as $efectivo) 
            {
                $Total_efectivo = $Total_efectivo + $efectivo["Monto"];
            }

        /// CALCULANDO INGRESOS EFECTIVO
            $this->db->select('Monto_bruto, Retencion_ing_brutos');
            $this->db->from('tbl_dinero_transferencias');

            $this->db->where('Origen_movimiento', 'Suscripcion');
            $this->db->where('Periodo_id', $Periodo_id);
            $this->db->where('Visible', 1);
            
            $query = $this->db->get();
            $arrayTransferencias = $query->result_array();
            
            $TotalTransferencias = 0;
            foreach ($arrayTransferencias as $transferencia) 
            {
                $TotalTransferencias = (int)$TotalTransferencias + (int)$transferencia["Monto_bruto"] - (int)$transferencia["Retencion_ing_brutos"];
            }

        /// CALCULANDO INGRESOS POR CHEQUE
            $this->db->select(' tbl_dinero_cheques.*,
                                tbl_cheques.*');

            $this->db->from('tbl_dinero_cheques');
            $this->db->join('tbl_cheques', 'tbl_cheques.Id = tbl_dinero_cheques.Cheque_id', 'left');

            $this->db->where('tbl_dinero_cheques.Origen_movimiento', 'Suscripcion');
            $this->db->where('Periodo_id', $Periodo_id);
            $this->db->where('tbl_dinero_cheques.Visible', 1);

            $query = $this->db->get();
            $result = $query->result_array();

            /////  SUMAR MONTOS
            $Total_cheques = 0;
            foreach ($result as $cheque) 
            {
            $Total_cheques = $Total_cheques + (int)$cheque["Monto"];
            }

        $Total = $Total_efectivo + $TotalTransferencias + $Total_cheques;
        
        echo json_encode($Total);
		
    }

//// VENTAS       |  INGRESOS SEGUN PERIODO
    public function ingresos_ventas_por_periodo()
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

        $Periodo_id = $_GET["Id"];

        /// CALCULANDO INGRESOS EFECTIVO
            $this->db->select('Monto');
            $this->db->from('tbl_dinero_efectivo');

            $this->db->where('Origen_movimiento', 'Ventas');
            $this->db->where('Periodo_id', $Periodo_id);
            $this->db->where('Visible', 1);
            
            $query = $this->db->get();
            $arrayEfectivo = $query->result_array();
            
            $Total_efectivo = 0;
            foreach ($arrayEfectivo as $efectivo) 
            {
                $Total_efectivo = $Total_efectivo + $efectivo["Monto"];
            }

        /// CALCULANDO INGRESOS EFECTIVO
            $this->db->select('Monto_bruto, Retencion_ing_brutos');
            $this->db->from('tbl_dinero_transferencias');

            $this->db->where('Origen_movimiento', 'Ventas');
            $this->db->where('Periodo_id', $Periodo_id);
            $this->db->where('Visible', 1);
            
            $query = $this->db->get();
            $arrayTransferencias = $query->result_array();
            
            $TotalTransferencias = 0;
            foreach ($arrayTransferencias as $transferencia) 
            {
                $TotalTransferencias = (int)$TotalTransferencias + (int)$transferencia["Monto_bruto"] - (int)$transferencia["Retencion_ing_brutos"];
            }

        /// CALCULANDO INGRESOS POR CHEQUE
            $this->db->select(' tbl_dinero_cheques.*,
                                tbl_cheques.*');

            $this->db->from('tbl_dinero_cheques');
            $this->db->join('tbl_cheques', 'tbl_cheques.Id = tbl_dinero_cheques.Cheque_id', 'left');

            $this->db->where('tbl_dinero_cheques.Origen_movimiento', 'Ventas');
            $this->db->where('Periodo_id', $Periodo_id);
            $this->db->where('tbl_dinero_cheques.Visible', 1);

            $query = $this->db->get();
            $result = $query->result_array();

            /////  SUMAR MONTOS
            $Total_cheques = 0;
            foreach ($result as $cheque) 
            {
            $Total_cheques = $Total_cheques + (int)$cheque["Monto"];
            }

        $Total = $Total_efectivo + $TotalTransferencias + $Total_cheques;
        
        echo json_encode($Total);
        
    }


//// VENTAS       |  INGRESOS SEGUN PERIODO
    public function egresos_compras_por_periodo()
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

        $Periodo_id = $_GET["Id"];

        /// CALCULANDO INGRESOS EFECTIVO
            $this->db->select('Monto');
            $this->db->from('tbl_dinero_efectivo');

            $this->db->where('Origen_movimiento', 'Ventas');
            $this->db->where('Periodo_id', $Periodo_id);
            $this->db->where('Visible', 1);
            
            $query = $this->db->get();
            $arrayEfectivo = $query->result_array();
            
            $Total_efectivo = 0;
            foreach ($arrayEfectivo as $efectivo) 
            {
                $Total_efectivo = $Total_efectivo + $efectivo["Monto"];
            }

        /// CALCULANDO INGRESOS EFECTIVO
            $this->db->select('Monto_bruto, Retencion_ing_brutos');
            $this->db->from('tbl_dinero_transferencias');

            $this->db->where('Origen_movimiento', 'Ventas');
            $this->db->where('Periodo_id', $Periodo_id);
            $this->db->where('Visible', 1);
            
            $query = $this->db->get();
            $arrayTransferencias = $query->result_array();
            
            $TotalTransferencias = 0;
            foreach ($arrayTransferencias as $transferencia) 
            {
                $TotalTransferencias = (int)$TotalTransferencias + (int)$transferencia["Monto_bruto"] - (int)$transferencia["Retencion_ing_brutos"];
            }

        /// CALCULANDO INGRESOS POR CHEQUE
            $this->db->select(' tbl_dinero_cheques.*,
                                tbl_cheques.*');

            $this->db->from('tbl_dinero_cheques');
            $this->db->join('tbl_cheques', 'tbl_cheques.Id = tbl_dinero_cheques.Cheque_id', 'left');

            $this->db->where('tbl_dinero_cheques.Origen_movimiento', 'Ventas');
            $this->db->where('Periodo_id', $Periodo_id);
            $this->db->where('tbl_dinero_cheques.Visible', 1);

            $query = $this->db->get();
            $result = $query->result_array();

            /////  SUMAR MONTOS
            $Total_cheques = 0;
            foreach ($result as $cheque) 
            {
            $Total_cheques = $Total_cheques + (int)$cheque["Monto"];
            }

        $Total = $Total_efectivo + $TotalTransferencias + $Total_cheques;
        
        echo json_encode($Total);
        
    }


//// DASHBOARD       | OBTENER LISTADO SUSCRIPCIONES GENERADAS
	public function obtener_cobros_dashboarad()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) {  exit("No coinciden los token"); }


        $this->db->select(' tbl_suscripciones_cobros.Fecha_pago,
                            tbl_suscripciones_cobros.Estado,
                            tbl_suscripciones_cobros.Valor_cobro,
                            tbl_periodos.Id as Periodo_id,
                            tbl_periodos.Identificador,
                            tbl_suscripciones.Titulo_suscripcion,
                            tbl_clientes.Nombre_cliente');
        $this->db->from('tbl_suscripciones_cobros');

        $this->db->join('tbl_periodos', 'tbl_periodos.Id = tbl_suscripciones_cobros.Periodo_id', 'left');
        $this->db->join('tbl_suscripciones', 'tbl_suscripciones.Id = tbl_suscripciones_cobros.Suscripcion_id', 'left');
        $this->db->join('tbl_clientes', 'tbl_clientes.Id = tbl_suscripciones.Cliente_id', 'left');

        $this->db->where('tbl_suscripciones_cobros.Visible', 1);

        $this->db->order_by("tbl_suscripciones_cobros.Fecha_pago", "desc");
        
        $this->db->limit(25, 0); /// paginación
        
        $query = $this->db->get();
		$result = $query->result_array();

        
        echo json_encode($result);
		
    }

///// fin documento
}
