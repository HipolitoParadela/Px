<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Restaurant extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/welcome
	 *	- or -
	 * 		http://example.com/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{	
		$this->load->model('Restaurant_model');

		$info_negocio = $this->Restaurant_model->info_negocio();
		
		$datos = array(
			
			'Info_negocio'	=> $info_negocio,
		);

		$this->load->view('dashboard', $datos);
	}

//// JORNADA 	| VISTA INICIAR JORNADA
	public function iniciarjornada()
	{
		if ( $this->session->userdata('Login') != true )
		{
			header("Location: ".base_url()."login"); /// enviar a pagina de error
		}
		else
		{	
			$this->load->model('Restaurant_model');
			$datos_jornada = $this->Restaurant_model->datos_jornada();
			$info_negocio = $this->Restaurant_model->info_negocio();
			$datos = array(
							'Estado_jornada'=> $datos_jornada["Estado"], 
							'Jornada_id' 	=> $datos_jornada["Id"],
							'Info_negocio'	=> $info_negocio,
						);
			
			$this->load->view('jornada-inicio', $datos);	
		}	
	}

//// JORNADA 	| VISTA INICIAR JORNADA
	public function jornada()
	{
		if ( $this->session->userdata('Login') != true )
		{
			header("Location: ".base_url()."login"); /// enviar a pagina de error
		}
		else
		{				
			$this->load->view('jornada-datos');	
		}	
	}

//// JORNADA 	| VISTA RESUMEN
	public function resumenjornadas()
	{
		if ( $this->session->userdata('Login') != true )
		{
			header("Location: ".base_url()."login"); /// enviar a pagina de error
		}
		else
		{	
			$this->load->view('resumen-jornadas');	
		}	
	}

//// JORNADA 	| CARGAR O EDITAR 
	public function crearJornada()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		//$datosObtenidos = json_decode(trim(file_get_contents('php://input')), true);

		//$datosObtenidos = $this->input->post();
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));

		if(isset($this->datosObtenidos->jornadaDatos->Id))
        {
            $Id = $this->datosObtenidos->jornadaDatos->Id;
        }
        else
        {
            $Id = NULL;
        }
		
		$fecha = $this->datosObtenidos->jornadaDatos->Fecha_inicio . ' '. $this->datosObtenidos->jornadaDatos->Hora_inicio;
		
		$data = array(
                        
					'Descripcion' => $this->datosObtenidos->jornadaDatos->Descripcion,
					'Fecha_inicio' => $fecha, 
					'Efectivo_caja_inicio' => $this->datosObtenidos->jornadaDatos->Efectivo_caja_inicio,
					'Usuario_id' => $this->session->userdata('Id'),
					'Estado' => 0,
					'Negocio_id' => $this->session->userdata('Negocio_id'),
                    
                );

        $this->load->model('Restaurant_model');
        $insert_id = $this->Restaurant_model->insertar($data, $Id, ' tbl_jornadas');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
	}


//// JORNADA 	| CERRAR JORNADA
	public function cerrarJornada()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		//$datosObtenidos = json_decode(trim(file_get_contents('php://input')), true);

		//$datosObtenidos = $this->input->post();
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));

		/// ONTENER EL ID DE UNA JORNADA
			$this->load->model('Restaurant_model');
			$datos_jornada = $this->Restaurant_model->datos_jornada();
			$Id = $datos_jornada["Id"];
		
		$fecha = $this->datosObtenidos->jornadaDatos->Fecha_fin . ' '. $this->datosObtenidos->jornadaDatos->Hora_fin;
		
		/// DATOS DE COMANDAS
                $this->db->select('	Valor_ingreso,
                                    Valor_egreso');
                $this->db->from('tbl_caja');
				$this->db->where("Jornada_id", $Id);
				$this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));

                $query = $this->db->get();
                $array_caja = $query->result_array();

                // sumar
                $total_movimientos = 0;
                foreach ($array_caja as $movimiento)
                {
                    $total_movimientos = $total_movimientos + $movimiento["Valor_ingreso"] - $movimiento["Valor_egreso"]; 
                }

		/// DATOS DE COMANDAS
                $this->db->select('	Valor_cuenta,
                                    Valor_descuento');
                $this->db->from('tbl_comandas');
                $this->db->where("Jornada_id", $Id);
				$this->db->where('Estado', 1);
				$this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));

                $query = $this->db->get();
                $array_comandas = $query->result_array();

                // sumar
                $valor_ventas_comandas = 0;
                foreach ($array_comandas as $comanda)
                {
                    $valor = $comanda["Valor_cuenta"];
                    $descuento = $comanda["Valor_descuento"];
                    $valor_ventas_comandas = $valor_ventas_comandas + $valor - $descuento; 
                }

            /// DATOS DE DELIVERY
                $this->db->select('	Valor_cuenta,
                                    Valor_descuento');
                $this->db->from('tbl_delibery');
                $this->db->where("Jornada_id", $Id);
				$this->db->where('Estado', 1);
				$this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));

                $query = $this->db->get();
                $array_delivery = $query->result_array();

                // sumar
                $valor_ventas_delivery = 0;
                foreach ($array_delivery as $comanda)
                {
                    $valor = $comanda["Valor_cuenta"];
                    $descuento = $comanda["Valor_descuento"];
                    $valor_ventas_delivery = $valor_ventas_delivery + $valor - $descuento; 
                }
            
            
            $total_ventas = $valor_ventas_comandas + $valor_ventas_delivery;
		
		$data = array(
					
					'Valor_ventas' => $total_ventas,
					'Valor_movimientos_caja' => $total_movimientos,
					'Efectivo_caja_final' => $this->datosObtenidos->jornadaDatos->Efectivo_caja_final,
					'Fecha_final' => $fecha,
					'Estado' => 1,
					'Negocio_id' => $this->session->userdata('Negocio_id'),
                );

        $this->load->model('Restaurant_model');
        $insert_id = $this->Restaurant_model->insertar($data, $Id, 'tbl_jornadas');
                
		if ($insert_id >=0 )
		{   
            echo json_encode(array("Id" => $insert_id));
		}
		
		//// EN UN FUTURO - FINALIZAR JORNADA DE TODOS LOS EMPLEADOS. --------------------------
			

	}

//// JORNADA	| OBTENER LISTADO DE JORNADAS
	public function obtener_jornadas()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

		//$Id = $_GET["Id"];

		$this->db->select('	tbl_jornadas.*,
							tbl_usuarios.Nombre');
		$this->db->from('tbl_jornadas');
		$this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_jornadas.Usuario_id','left');
		$this->db->where("tbl_jornadas.Negocio_id", $this->session->userdata('Negocio_id'));
		
		//$this->db->where('Usuario_id',$Id);
		$this->db->order_by("tbl_jornadas.Id", "desc");
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
	}

//// JORNADA	| OBTENER RESUMEN DE JORNADAS
	public function obtener_resumen_jornadas()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));

		$Desde = $this->datosObtenidos->Desde;
		$Hasta = $this->datosObtenidos->Hasta;
		
		if($Desde == NULL) 
		{ 
			$fecha = date('Y-m-d');
			$Desde = strtotime ( '-28 day' , strtotime ( $fecha ) ) ;
			$Desde = date ( 'Y-m-d' , $Desde );
		}
		if($Hasta == NULL) 
		{ 
			$Hasta = date('Y-m-d');
		}

		$this->db->select('	tbl_jornadas.*,
							tbl_usuarios.Nombre');
		$this->db->from('tbl_jornadas');
		$this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_jornadas.Usuario_id','left');

		$this->db->where("DATE_FORMAT(tbl_jornadas.Fecha_inicio,'%Y-%m-%d') >=", $Desde);
		$this->db->where("DATE_FORMAT(tbl_jornadas.Fecha_inicio,'%Y-%m-%d') <=", $Hasta);
		$this->db->where("tbl_jornadas.Negocio_id", $this->session->userdata('Negocio_id'));

		$this->db->order_by("tbl_jornadas.Id", "desc");
        $query = $this->db->get();
		$array_jornadas = $query->result_array();

		$Datos = array();
		/// DEBO CONSULTAR LAS VENTAS DE ESTA JORNADA Y LOS MOVIMIENTOS DE CAJA
		foreach ($array_jornadas as $jornada) 
		{
			
			// CONSULTANDO INGRESOS COMANDAS
				$this->db->select('Id, Valor_cuenta, Valor_descuento, Valor_adicional');
				$this->db->from('tbl_comandas');
				$this->db->where("Estado", 1);
				$this->db->where("Jornada_id", $jornada["Id"]);
				//$this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));

				$query = $this->db->get();
				$array_comandas_jornada = $query->result_array();
				$total_comandas = $query->num_rows();

				$Total_ing_comandas = 0;

				foreach ($array_comandas_jornada as $comanda) 
				{
					$Total_ing_comandas = $Total_ing_comandas + $comanda["Valor_cuenta"] - $comanda["Valor_descuento"] + $comanda["Valor_adicional"];
				}

			// CONSULTANDO INGRESOS DELIVERY
				$this->db->select('Id, Valor_cuenta, Valor_descuento, Valor_delivery');
				$this->db->from('tbl_delibery');
				$this->db->where("Estado", 1);
				$this->db->where("Jornada_id", $jornada["Id"]);
				//$this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));

				$query = $this->db->get();
				$array_delivery_jornada = $query->result_array();
				$cant_delivery = $query->num_rows();

				$Total_ing_delivery = 0;

				foreach ($array_delivery_jornada as $delivery) 
				{
					$Total_ing_delivery = $Total_ing_delivery + $delivery["Valor_cuenta"] + $delivery["Valor_delivery"] - $delivery["Valor_descuento"];
				}

			// MOVIMIENTOS DE CAJA
				$this->db->select('Id, Valor_ingreso, Valor_egreso');
				$this->db->from('tbl_caja');
				$this->db->where("Jornada_id", $jornada["Id"]);
				//$this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));

				$query = $this->db->get();
				$array_caja = $query->result_array();
				//$cant_delivery = $query->num_rows();

				$Result_caja = 0;

				foreach ($array_caja as $caja) 
				{
					$Result_caja = $Result_caja + $caja["Valor_ingreso"] - $caja["Valor_egreso"];
				}

			// RESULTADO DE LA JORNADA
				$Resultado_joranda = $Total_ing_comandas + $Total_ing_delivery + $Result_caja;

			
			$datos_jornada = array(
									'Datos_jornada' => $jornada,
									'Cant_comandas' => $total_comandas,
									'Ing_comadas' 	=> $Total_ing_comandas,
									'Cant_delivery' => $cant_delivery,
									'Ing_delivery' 	=> $Total_ing_delivery,
									'Result_caja' 	=> $Result_caja,
									'Resultado_joranda' 	=> $Resultado_joranda,
									'Negocio_id' => 				$this->session->userdata('Negocio_id'),
			);

			array_push($Datos, $datos_jornada);
		}


		echo json_encode($Datos);
		
	}
	
	
//// CARTA 		| VISTA DE ITEMS CARTA
	public function itemscarta()
    {
		if ( $this->session->userdata('Login') != true  )
		{
			header("Location: ".base_url()."login"); /// enviar a pagina de error
		}
		else{
			
			if ( $this->session->userdata('Rol_id') >= 4)
			{
				$this->load->view('itemscarta');
			}
			else 
			{
				header("Location: ".base_url()."login"); /// enviar a pagina de error
			}	
		}
	}

//// CARTA 		| VISTA DE ITEMS CARTA
	public function datos()
	{
		if ( $this->session->userdata('Login') != true  )
		{
			header("Location: ".base_url()."login"); /// enviar a pagina de error
		}
		else{
			
			if ( $this->session->userdata('Rol_id') >= 4)
			{
				$this->load->view('itemcarta_datos');
			}
			else 
			{
				header("Location: ".base_url()."login"); /// enviar a pagina de error
			}	
		}
	}

	
//// CARTA 		| MOSTRAR ITEMS CARTA
	public function mostrarItems()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;
		
		// PARA QUE ME FUNCIONEN LOS TAB DEBO ARMAR UN ARRAY CON TODAS LAS CATEGORIAS, LUEGO OTRO ARRAY QUE BUSCA CADA ITEM DE CADA CATEGORIA. 
		// ENTONCES HAGO LOS VUE FOR UNO CON EL ARRAY CON NOMBRES DE CATEGORIA, Y OTRO CON CADA ITEM, ESTE DEBERIA SER UN FOR DENTRO DE OTRO FOR
		

		$this->db->select('	tbl_stock.*,
							tbl_stock_categorias.Nombre_categoria');

		$this->db->from('tbl_stock');

		$this->db->join('tbl_stock_categorias', 'tbl_stock_categorias.Id = tbl_stock.Categoria_id', 'left');
		$this->db->where("tbl_stock.Negocio_id", $this->session->userdata('Negocio_id'));
		$this->db->where('tbl_stock.Apto_carta', '1');
		//$this->db->limit(5, 4);
		
		$this->db->order_by("tbl_stock.Nombre_item", "asc");
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
		
	}

	
//// CARTA 		| MOSTRAR ITEMS CARTA POR CATEGORIA
	public function mostrarItemsCategoria()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;
		
		$Id = $_GET["Id"];

		$this->db->select('	tbl_stock.*,
							tbl_stock_categorias.Nombre_categoria');
		$this->db->from('tbl_stock');

		$this->db->join('tbl_stock_categorias', 'tbl_stock_categorias.Id = tbl_stock.Categoria_id','left');

		$this->db->where('tbl_stock.Apto_carta', '1');
		$this->db->where("tbl_stock.Negocio_id", $this->session->userdata('Negocio_id'));
		$this->db->where('tbl_stock.Activo', 1);

		if($Id > 0) {	$this->db->where('tbl_stock.Categoria_id', $Id);	}

		$this->db->order_by("tbl_stock.Nombre_item", "asc");

        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
	}

//// CARTA 		| MOSTRAR ITEMS CARTA activos
	public function mostrarItemsActivos()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

		$this->db->select('	tbl_stock.*,
							tbl_stock_categorias.Nombre_categoria');

		$this->db->from('tbl_stock');
		$this->db->join('tbl_stock_categorias', 'tbl_stock_categorias.Id = tbl_stock.Categoria_id','left');

		$this->db->where('tbl_stock.Activo', 1);
		$this->db->where('tbl_stock.Apto_carta', 1);
		$this->db->where("tbl_stock.Negocio_id", $this->session->userdata('Negocio_id'));

		$this->db->order_by("tbl_stock.Nombre_item", "asc");
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
	}

//// CARTA 		| CARGAR O EDITAR UN ITEM, SI TRAE ID EDITA, SI NO CARGA UNO NUEVO
	public function cargarItem()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		//$datosObtenidos = json_decode(trim(file_get_contents('php://input')), true);

		//$datosObtenidos = $this->input->post();
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));

		$Id = NULL;
		if(isset($this->datosObtenidos->ItemCartaDatos->Id)) { $Id = $this->datosObtenidos->ItemCartaDatos->Id; }

		$Activo = 1;
		if(isset($this->datosObtenidos->ItemCartaDatos->Activo)) { $Activo = $this->datosObtenidos->ItemCartaDatos->Activo; }

		$Cant_actual = 0;
		if(isset($this->datosObtenidos->ItemCartaDatos->Cant_actual)) { $Cant_actual = $this->datosObtenidos->ItemCartaDatos->Cant_actual; }

		$Cant_ideal = 0;
		if(isset($this->datosObtenidos->ItemCartaDatos->Cant_ideal)) { $Cant_ideal = $this->datosObtenidos->ItemCartaDatos->Cant_ideal; }

		$Descripcion = null;
		if(isset($this->datosObtenidos->ItemCartaDatos->Descripcion)) { $Descripcion = $this->datosObtenidos->ItemCartaDatos->Descripcion; }
        
		
		$fecha = date("Y-m-d");

		$data = array(
					
					'Nombre_item' => 				$this->datosObtenidos->ItemCartaDatos->Nombre_item, 
					'Categoria_id' => 				$this->datosObtenidos->ItemCartaDatos->Categoria_id,
					'Apto_carta' => 				1,
					'Apto_stock' => 				$this->datosObtenidos->ItemCartaDatos->Apto_stock,
					'Descripcion' => 				$this->datosObtenidos->ItemCartaDatos->Descripcion,
					'Unidad_medida' => 				'Unidad',
					'Cant_actual' => 				$Cant_actual,
					'Cant_ideal' => 				$Cant_ideal,
					'Apto_delivery' => 				$this->datosObtenidos->ItemCartaDatos->Apto_delivery,
					'Precio_venta' => 				$this->datosObtenidos->ItemCartaDatos->Precio_venta,
					'Precio_costo' => 				$this->datosObtenidos->ItemCartaDatos->Precio_costo,
					'Tiempo_estimado_entrega' => 	$this->datosObtenidos->ItemCartaDatos->Tiempo_estimado_entrega,
					'Activo' => 					$Activo,
					'Negocio_id' => 				$this->session->userdata('Negocio_id'),
                    
                );

        $this->load->model('Restaurant_model');
           $insert_id = $this->Restaurant_model->insertarItemCarta($data, $Id);
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
	}
	


//// CARTA 		| OBTENER CATEGORIA DE ITEMS CARTA
	public function obtener_categorias_items()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;
		
		// PARA QUE ME FUNCIONEN LOS TAB DEBO ARMAR UN ARRAY CON TODAS LAS CATEGORIAS, LUEGO OTRO ARRAY QUE BUSCA CADA ITEM DE CADA CATEGORIA. 
		// ENTONCES HAGO LOS VUE FOR UNO CON EL ARRAY CON NOMBRES DE CATEGORIA, Y OTRO CON CADA ITEM, ESTE DEBERIA SER UN FOR DENTRO DE OTRO FOR
		//$variableGet = $_GET["Id"];

        $this->db->select('*');
		$this->db->from('tbl_stock_categorias');
		$this->db->where('Apto_carta', 1);
		$this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));
		$this->db->order_by("Nombre_categoria", "asc");
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
	}
	
//// CARTA 		| CARGAR O EDITAR CATEGORIAS ITEMS CARTA
	public function cargar_categoria_items()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));

		if(isset($this->datosObtenidos->categoriaCartaDatos->Id))
        {
            $Id = $this->datosObtenidos->categoriaCartaDatos->Id;
        }
        else
        {
            $Id = NULL;
        }

		$data = array(
                        
					'Nombre_categoria' => 	$this->datosObtenidos->categoriaCartaDatos->Nombre_categoria,
					'Descripcion_categoria' => 		$this->datosObtenidos->categoriaCartaDatos->Descripcion_categoria,
					'Apto_carta' => 1,
					'Apto_stock' => 		$this->datosObtenidos->categoriaCartaDatos->Apto_stock,
					'Negocio_id' => $this->session->userdata('Negocio_id'),
                    
                );

        $this->load->model('Restaurant_model');
        $insert_id = $this->Restaurant_model->insertarCategoriaCarta($data, $Id);
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
	}

//// MESAS 		| VISTA DE MESAS
	public function mesas()
	{
		if ( $this->session->userdata('Login') != true )
		{
			header("Location: ".base_url()."login"); /// enviar a pagina de error
		}
		else
		{	
			if ( (int)$this->session->userdata('Rol_id') >= 4)
			{
				$this->load->view('admin-mesas');
			}
			else 
			{
				header("Location: ".base_url()."login"); /// enviar a pagina de error
			}		
		}
	}

//// MESAS 		| OBTENER MESAS
	public function obtener_mesas()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;
		
		// PARA QUE ME FUNCIONEN LOS TAB DEBO ARMAR UN ARRAY CON TODAS LAS CATEGORIAS, LUEGO OTRO ARRAY QUE BUSCA CADA ITEM DE CADA CATEGORIA. 
		// ENTONCES HAGO LOS VUE FOR UNO CON EL ARRAY CON NOMBRES DE CATEGORIA, Y OTRO CON CADA ITEM, ESTE DEBERIA SER UN FOR DENTRO DE OTRO FOR
		//$variableGet = $_GET["Id"];

        $this->db->select('*');
		$this->db->from('tbl_mesas');
		$this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));
		$this->db->order_by("Identificador", "asc");
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
	}
	
//// MESAS 		| CARGAR O EDITAR MESAS
	public function cargar_mesas()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));

		if(isset($this->datosObtenidos->mesaData->Id))
        {
            $Id = $this->datosObtenidos->mesaData->Id;
        }
        else
        {
            $Id = NULL;
        }

		$data = array(
                        
					'Identificador' => 		$this->datosObtenidos->mesaData->Identificador,
					'Descripcion' => 	$this->datosObtenidos->mesaData->Descripcion,
					'Negocio_id' => $this->session->userdata('Negocio_id'),
                    
                );

        $this->load->model('Restaurant_model');
        $insert_id = $this->Restaurant_model->insertar($data, $Id, 'tbl_mesas');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
	}


//// USUARIOS 	| VISTA DE ADMIN USUARIOS
	public function usuarios()
	{	
		if ( $this->session->userdata('Login') != true )
		{
			header("Location: ".base_url()."login"); /// enviar a pagina de error
		}
		else
		{	
			if ( $this->session->userdata('Rol_id') >= 4)
			{
				$this->load->view('admin-usuarios');
			}
			else 
			{
				header("Location: ".base_url()."login"); /// enviar a pagina de error
			}	
			
		}
	}

//// USUARIOS 	| OBTENER USUARIOS
	public function obtener_Usuarios()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

		$estado = $_GET["estado"];

		$this->db->select('	tbl_usuarios.*,
							tbl_roles.Nombre_rol');
		$this->db->from('tbl_usuarios');
		$this->db->join('tbl_roles', 'tbl_roles.Id = tbl_usuarios.Rol_id','left');
		
		$this->db->where('tbl_usuarios.Activo', $estado);
		
		$this->db->where("tbl_usuarios.Negocio_id", $this->session->userdata('Negocio_id'));
		
		$this->db->order_by("Nombre", "asc");
		
		$query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
	}
	
//// USUARIOS 	| CARGAR O EDITAR USUARIOS
	public function cargar_Usuarios()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));

		
		
		$Id = NULL; 					if(isset($this->datosObtenidos->usuarioData->Id)) 				{ $Id = $this->datosObtenidos->usuarioData->Id; }
		$Nombre = null; 				if(isset($this->datosObtenidos->usuarioData->Nombre)) 			{ $Nombre = $this->datosObtenidos->usuarioData->Nombre; }
		$DNI = null; 					if(isset($this->datosObtenidos->usuarioData->DNI)) 				{ $DNI = $this->datosObtenidos->usuarioData->DNI; }
		$Pass = null; 					if(isset($this->datosObtenidos->usuarioData->Pass)) 			{ $Pass = $this->datosObtenidos->usuarioData->Pass; }
		$Rol_id = null; 				if(isset($this->datosObtenidos->usuarioData->Rol_id)) 			{ $Rol_id = $this->datosObtenidos->usuarioData->Rol_id; }
		$Telefono = null; 				if(isset($this->datosObtenidos->usuarioData->Telefono)) 		{ $Telefono = $this->datosObtenidos->usuarioData->Telefono; }
		$Fecha_nacimiento = null; 		if(isset($this->datosObtenidos->usuarioData->Fecha_nacimiento)) { $Fecha_nacimiento = $this->datosObtenidos->usuarioData->Fecha_nacimiento; }
		$Domicilio = null; 				if(isset($this->datosObtenidos->usuarioData->Domicilio)) 		{ $Domicilio = $this->datosObtenidos->usuarioData->Domicilio; }
		$Nacionalidad = null; 			if(isset($this->datosObtenidos->usuarioData->Nacionalidad))		{ $Nacionalidad = $this->datosObtenidos->usuarioData->Nacionalidad; }
		$Genero = null; 				if(isset($this->datosObtenidos->usuarioData->Genero)) 			{ $Genero = $this->datosObtenidos->usuarioData->Genero; }
		$Email = null; 					if(isset($this->datosObtenidos->usuarioData->Email)) 			{ $Email = $this->datosObtenidos->usuarioData->Email; }
		$Obra_social = null; 			if(isset($this->datosObtenidos->usuarioData->Obra_social)) 		{ $Obra_social = $this->datosObtenidos->usuarioData->Obra_social; }
		$Numero_obra_social = null; 	if(isset( $this->datosObtenidos->usuarioData->Numero_obra_social)) { $Numero_obra_social = $this->datosObtenidos->usuarioData->Numero_obra_social; }
		$Hijos = null; 					if(isset($this->datosObtenidos->usuarioData->Hijos)) 			{ $Hijos = $this->datosObtenidos->usuarioData->Hijos; }
		$Estado_civil = null; 			if(isset($this->datosObtenidos->usuarioData->Estado_civil)) 	{ $Estado_civil = $this->datosObtenidos->usuarioData->Estado_civil; }
		$Datos_persona_contacto = null; if(isset($this->datosObtenidos->usuarioData->Datos_persona_contacto)) { $Datos_persona_contacto = $this->datosObtenidos->usuarioData->Datos_persona_contacto; }
		$Datos_bancarios = null; 		if(isset($this->datosObtenidos->usuarioData->Datos_bancarios)) { $Datos_bancarios = $this->datosObtenidos->usuarioData->Datos_bancarios; }
		$Remuneracion_jornada = null; 	if(isset($this->datosObtenidos->usuarioData->Remuneracion_jornada)) { $Remuneracion_jornada = $this->datosObtenidos->usuarioData->Remuneracion_jornada; }
		$Periodo_liquidacion_sueldo = null; if(isset($this->datosObtenidos->usuarioData->Periodo_liquidacion_sueldo)) { $Periodo_liquidacion_sueldo = $this->datosObtenidos->usuarioData->Periodo_liquidacion_sueldo; }
		$Horario_laboral = null;		if(isset($this->datosObtenidos->usuarioData->Horario_laboral)) 	{ $Horario_laboral = $this->datosObtenidos->usuarioData->Horario_laboral; }
		$Lider = null; 					if(isset($this->datosObtenidos->usuarioData->Lider)) 			{ $Lider = $this->datosObtenidos->usuarioData->Lider; }
		$Superior_inmediato = null; 	if(isset($this->datosObtenidos->usuarioData->Superior_inmediato)) { $Superior_inmediato = $this->datosObtenidos->usuarioData->Superior_inmediato; }
		$Fecha_alta = null; 			if(isset($this->datosObtenidos->usuarioData->Fecha_alta)) 		{ $Fecha_alta = $this->datosObtenidos->usuarioData->Fecha_alta; }
		$Observaciones = null; 			if(isset($this->datosObtenidos->usuarioData->Observaciones)) 	{ $Observaciones = $this->datosObtenidos->usuarioData->Observaciones; }
		
		$Activo = 1;
		if(isset($this->datosObtenidos->usuarioData->Activo))
        {
            $Activo = $this->datosObtenidos->usuarioData->Activo;
		}
		
		//// CONTROLO QUE YA NO EXISTA ESTE USUARIO
		$this->load->model('user');
        $fila = $this->user->getUser($this->datosObtenidos->usuarioData->DNI);

        if($fila == null || $Id != NULL) //// GENERA LA CARGA SI el usuario es nuevo y no repite el mismo DNI, o si ya trae un ID 
        {
					$data = array(
								
						'Nombre' => 			$Nombre,
						'DNI' => 				$DNI,
						'Pass' => 				$Pass,
						'Rol_id' => 			$Rol_id,
						'Telefono' => 			$Telefono,
						'Fecha_nacimiento' => 	$Fecha_nacimiento,
						'Domicilio' => 			$Domicilio,
						'Nacionalidad' => 		$Nacionalidad,
						'Genero' => 			$Genero,
						'Email' => 				$Email,
						'Obra_social' => 		$Obra_social,
						'Numero_obra_social' => $Numero_obra_social,
						'Hijos' => 				$Hijos,
						'Estado_civil' => 		$Estado_civil,
						'Datos_persona_contacto' => 	$Datos_persona_contacto,
						'Datos_bancarios' => 			$Datos_bancarios,
						'Remuneracion_jornada' => 		$Remuneracion_jornada,
						'Periodo_liquidacion_sueldo' => $Periodo_liquidacion_sueldo,
						'Horario_laboral' => 			$Horario_laboral,
						'Lider' => 				$Lider,
						'Superior_inmediato' => 		$Superior_inmediato,
						'Fecha_alta' => 		$Fecha_alta,
						'Observaciones' => 		$Observaciones,
						'Activo' => 			$Activo,
						'Negocio_id' => $this->session->userdata('Negocio_id'),
						
					);

			$this->load->model('Restaurant_model');
			$insert_id = $this->Restaurant_model->insertar($data, $Id, 'tbl_usuarios');
					
			if ($insert_id >=0 ) 
			{   
				echo json_encode(array("Id" => $insert_id));         
			} 
			else 
			{
				echo json_encode(array("Id" => 0));
			}
		}

		/// SI YA EXISTIA DEBE DEVOLVER UN AVISO QUE YA EXISTE
		else 
		{
			echo json_encode(array("Id" => 0));
		}
		
	}


//// USUARIOS 	| OBTENER Roles
	public function obtener_roles()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

		$this->db->select('*');
		$this->db->from('tbl_roles');
		$this->db->order_by("Acceso", "desc");
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
	}

//// COMANDAS 	| VISTA LISTADO DE COMANDAS ABIERTAS
	public function comandas()
	{
		if ( $this->session->userdata('Login') != true )
		{
			header("Location: ".base_url()."login"); /// enviar a pagina de error
		}
		else
		{
			//CONSULTAR SI HAY JORNADA ABIERTA, si la hay puede operar, si no la hay, manda a la vista de abrir jornada
			$this->load->model('Restaurant_model');
		   	$datos_jornada = $this->Restaurant_model->datos_jornada();
		   
			if ($datos_jornada["Estado"] == 0) 
			{
				$this->load->view('admin-comandas');	
			}

			else 
			{
				header("Location: ".base_url()."restaurant/iniciarjornada"); /// enviar a pagina de error
			}
		}		
	}


//// COMANDAS 	| VISTA COMANDA
	public function comanda()
	{
		if ( $this->session->userdata('Login') != true )
		{
			header("Location: ".base_url()."login"); /// enviar a pagina de error
		}
		else
		{
			///CONSULTAR SI HAY JORNADA ABIERTA, si la hay puede operar, si no la hay, manda a la vista de abrir jornada
			$this->load->model('Restaurant_model');
		   	$datos_jornada = $this->Restaurant_model->datos_jornada();
		   
			if ($datos_jornada["Estado"] == 0)
			{
				$datos = array('Rol_usuario'=> $this->session->userdata('Rol_id'));
				$this->load->view('admin-comanda',$datos);	
			}
			else 
			{
				header("Location: ".base_url()."restaurant/iniciarjornada"); /// enviar a pagina de error
			}
		}	
	}

//// COMANDAS 	| VISTA IMPIMIR CUENTA
	public function imprimirCuenta()
	{
		if ( $this->session->userdata('Login') != true )
		{
			header("Location: ".base_url()."login"); /// enviar a pagina de error
		}
		else
		{
			//Esto siempre va es para instanciar la base de datos
			$CI =& get_instance();
			$CI->load->database();
			$token = @$CI->db->token;

			$Id = $_GET["Id"];

			//DATOS DE LA COMANDA
				$this->db->select('	tbl_comandas.*,
									tbl_mesas.Identificador,
									tbl_clientes.Nombre as Nombre_cliente,
									tbl_usuarios.Nombre as Nombre_moso');
				
				$this->db->from('tbl_comandas');

				$this->db->join('tbl_mesas', 'tbl_mesas.Id = tbl_comandas.Mesa_id','left');
				$this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_comandas.Moso_id','left');

				$this->db->where('tbl_comandas.Id', $Id);
				$this->db->where("tbl_comandas.Negocio_id", $this->session->userdata('Negocio_id'));

				$query = $this->db->get();
				$datosComanda = $query->result_array();
			
			// LISTADO DE ITEMS
				$this->db->select('	tbl_items_comanda.Estado,
									tbl_items_comanda.Id as Item_comanda_id,
									tbl_stock.*');
				
				$this->db->from('tbl_items_comanda');
				
				$this->db->join('tbl_stock', 'tbl_stock.Id = tbl_items_comanda.Item_carga_id','left');
				$this->db->join('tbl_clientes', 'tbl_clientes.Id = tbl_comandas.Cliente_id','left');
				
				$this->db->where('tbl_items_comanda.Comanda_id', $Id);
				$this->db->where("tbl_items_comanda.Negocio_id", $this->session->userdata('Negocio_id'));
				//$this->db->where('tbl_items_comanda.Estado', 1); //busca solo los entregados
				$this->db->order_by("tbl_stock.Nombre_item", "asc");

				$query = $this->db->get();
				$arrayItems = $query->result_array();

				// sumar el costo de los items
				$Total_cuenta = 0;

				foreach ($arrayItems as $item) 
				{
					$precio = $item["Precio_venta"];

					$Total_cuenta = $Total_cuenta + $precio;
				}

				$Total_cuenta = $Total_cuenta - $datosComanda[0]["Valor_descuento"];
			
			$datos = array('infoComandas' => $datosComanda[0], 'Items' => $arrayItems, 'Cuenta' => $Total_cuenta);

			$this->load->view('imprimircuenta', $datos);	
		}	
	}


//// COMANDAS 	| CARGAR O EDITAR COMANDAS
	public function crear_comanda()
    {
		
        $CI =& get_instance();
		$CI->load->database();
		$this->load->library('Ciqrcode'); /// LIbreria de QR
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));

		$Id = NULL; if(isset($this->datosObtenidos->comandaData->Id)) { $Id = $this->datosObtenidos->comandaData->Id; }
		$Modo_pago = 1; if(isset($this->datosObtenidos->comandaData->Modo_pago)) { $Modo_pago = $this->datosObtenidos->comandaData->Modo_pago; }
		$Direccion = "No informada."; if(isset($this->datosObtenidos->comandaData->Direccion)) { $Direccion = $this->datosObtenidos->comandaData->Direccion; }
		
		$Cliente_id = $this->datosObtenidos->comandaData->Cliente_id;
		//// OBTENIENDO DATO DE CLIENTE | SI EL CLIENTE NO EXISTIA LO DEBE CARGAR
			if($Cliente_id == 0)
			{
				$data = array(
							
					'Nombre' => 	$this->datosObtenidos->comandaData->Nombre_cliente,
					'Telefono' => 	$this->datosObtenidos->comandaData->Telefono,
					'Direccion' => 	$Direccion,
					'Ult_usuario_id' => 	$this->session->userdata('Id'),
					'Negocio_id' => $this->session->userdata('Negocio_id')
				);

			$this->load->model('Restaurant_model');
			$insert_id_cliente = $this->Restaurant_model->insertar($data, NULL, 'tbl_clientes');
					
				if ($insert_id_cliente >=0 ) 
				{   
					$Cliente_id = $insert_id_cliente;
				} 
			}
		
		/// ONTENER EL ID DE UNA JORNADA
			$this->load->model('Restaurant_model');
			$datos_jornada = $this->Restaurant_model->datos_jornada();
			$Jornada_id = $datos_jornada["Id"];
		
		$fecha = date('Y-m-d');
		$Hora = date("H:i");
		
		//// GENERANDO EL QR
			$str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
			$Codigo = "";
			//Reconstruimos la contraseña segun la longitud que se quiera
			for( $i=0; $i < 9; $i++ ) 
			{
			//obtenemos un caracter aleatorio escogido de la cadena de caracteres
			$Codigo .= substr($str, rand(0,62), 1);
			}

			//hacemos configuraciones
			$params['data'] = base_url()."restaurant/mipedido?Id=".$Codigo;
			$params['level'] = 'H';
			$params['size'] = 10;

			//decimos el directorio a guardar el codigo qr, en este 
			//caso una carpeta en la raíz llamada qr_code
			$params['savename'] = FCPATH . "uploads/QRs/".$Codigo.".png";
			//generamos el código qr
			$this->ciqrcode->generate($params);

			$QRimg = $Codigo.".png";
			
			$data = array(
						
					'Codigo' =>			$Codigo,
					'QRimg' =>			$QRimg,
					'Jornada_id' =>		$Jornada_id,
					'Mesa_id' => 		$this->datosObtenidos->comandaData->Mesa_id,
					'Cant_personas' => 	$this->datosObtenidos->comandaData->Cant_personas,
					'Cliente_id' => 	$Cliente_id,
					'Moso_id' => 		$this->session->userdata('Id'),
					'Fecha' => 			$fecha,
					'Hora_llegada' => 	$Hora,
					'Modo_pago' => 		$Modo_pago,
					'Negocio_id' => 	$this->session->userdata('Negocio_id'),
			);

        $this->load->model('Restaurant_model');
		$insert_id = $this->Restaurant_model->insertar($data, $Id, 'tbl_comandas');
		
	
		if ($insert_id >=0 ) 
		{   
			$data_timeline = array( 	'Comanda_id' => $insert_id,
										'Accion' => 1,
										'Negocio_id' => $this->session->userdata('Negocio_id'), );
			
										//// insert del timeline
			$this->load->model('Restaurant_model');
			$insert_id_timeline = $this->Restaurant_model->insertar($data_timeline, NULL, 'tbl_comandas_timeline');

            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
	}



//// 			| ELIMINAR ALGO
	public function eliminar()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));

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
		
        $this->load->model('Restaurant_model');
        $insert_id = $this->Restaurant_model->eliminar($Id, $tabla);
                
	}


//// COMANDAS 	| OBTENER lISTADO DE COMANDAS ABIERTAS
	public function obtener_listado_comandas_abiertas()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

		/// ONTENER EL ID DE UNA JORNADA
			$this->load->model('Restaurant_model');
			$datos_jornada = $this->Restaurant_model->datos_jornada();
			$Jornada_id = $datos_jornada["Id"];

		$this->db->select('	tbl_comandas.*,
							tbl_mesas.Identificador,
							tbl_clientes.Nombre as Nombre_cliente,
							tbl_clientes.Telefono,
							tbl_clientes.Direccion,
							tbl_usuarios.Nombre as Nombre_moso');
		$this->db->from('tbl_comandas');

		$this->db->join('tbl_mesas', 'tbl_mesas.Id = tbl_comandas.Mesa_id','left');
		$this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_comandas.Moso_id','left');
		$this->db->join('tbl_clientes', 'tbl_clientes.Id = tbl_comandas.Cliente_id','left');

		$this->db->where('tbl_comandas.Estado', 0);
		$this->db->where('tbl_comandas.Jornada_id', $Jornada_id);
		$this->db->where("tbl_comandas.Negocio_id", $this->session->userdata('Negocio_id'));

		/// si es moso, filtra para mostrar solo sus mesas
		
		if ($this->session->userdata('Rol_id') == 2)
		{
			$Usuario_login_id = $this->session->userdata('Id');
			$this->db->where('tbl_comandas.Moso_id', $Usuario_login_id);
		}
		
		$this->db->order_by("tbl_comandas.Id", "asc");
        $query = $this->db->get();
		$array_comandas = $query->result_array();

		$datos = array();

		foreach ($array_comandas as $comanda) 
		{
			$Comanda_id = $comanda["Id"];
			
			$this->db->select('	tbl_items_comanda.*,
								tbl_stock.*,
								tbl_categorias.Nombre_categoria');
		
			$this->db->from('tbl_items_comanda');
			
			$this->db->join('tbl_stock', 'tbl_stock.Id = tbl_items_comanda.Item_carga_id','left');
			$this->db->join('tbl_categorias', 'tbl_categorias.Id = tbl_stock.Categoria_id','left');
			
			$this->db->where('tbl_items_comanda.Comanda_id', $Comanda_id);
			$this->db->where('tbl_items_comanda.Estado', 0);
			$this->db->where("tbl_items_comanda.Negocio_id", $this->session->userdata('Negocio_id'));

			$query = $this->db->get();
			$result = $query->result_array();
			$total_pendientes = $query->num_rows();

			/// buscando cantididad de items totales
			$this->db->select('Id');
			$this->db->from('tbl_items_comanda');			
			$this->db->where('tbl_items_comanda.Comanda_id', $Comanda_id);
			//$this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));

			$query = $this->db->get();
			$result_todos = $query->result_array();
			$total_todos = $query->num_rows();

			$cant_entregados = $total_todos - $total_pendientes;

			
			//// consultando Alerta - Si lleva más de cierto tiempo en la entrega de algo genera alerta
				$Hora = date("H:i");
				$Alerta = 0;
				//si ya se entrego el menu, veo hace cuanto, si es mas de 40m MANDO ALERTA
				if($comanda["Hora_menu_en_mesa"] != NULL)
				{
					$num_alerta = $this->RestarHoras($comanda["Hora_menu_en_mesa"], $Hora);
						
						if($num_alerta < 25 & $num_alerta > 40) {
							$Alerta = 1;
						}
						elseif($num_alerta > 40) {
							$Alerta = 2;
						}
						else 
						{
							$Alerta = 0;
						}
				}
				else	/// si no se entrego el menu, veo hace cuanto que se entrego la entrada
				{
					/// si se entrego la entrada veo hace cuanto, si es mas de 20 minutos, MANDO ALERTA
					if($comanda["Hora_entrada_en_mesa"] != NULL)
					{
						$num_alerta = $this->RestarHoras($comanda["Hora_entrada_en_mesa"], $Hora);
						
						if($num_alerta < 15 & $num_alerta > 8) 
						{
							$Alerta = 1;
						}
						elseif($num_alerta > 15) 
						{
							$Alerta = 2;
						}
						else 
						{
							$Alerta = 0;
						}
					}
					else /// si no se entrego la entrada, veo hace cuanto tiempo llegaron, si es mas de 15 minutos MANDO ALERTA
					{
						$num_alerta = $this->RestarHoras($comanda["Hora_llegada"], $Hora);
						
						if($num_alerta < 15 & $num_alerta > 8) {
							$Alerta = 1;
						}
						elseif($num_alerta > 15) {
							$Alerta = 2;
						}
					}
				}
				
			//// Armado de array
			$comanda = array(
								'Datos_comanda' => $comanda, 
								'Datos_items'=> $result, 
								'Total_items' => $total_todos, 
								'Cant_entregados'=> $cant_entregados, 
								'Cant_pendientes'=> $total_pendientes, 
								'Alerta' => $Alerta);

			array_push($datos, $comanda);

		}
		///aca debo trabajarlo con foreach y array_push 

		echo json_encode($datos);
		
	}


//// 			| RESTA ENTRE HORAS
	public function RestarHoras($horaini, $horafin)
	{
		$horai = substr($horaini,0,2);
		$mini = substr($horaini,3,2);
	
		$horaf = substr($horafin,0,2);
		$minf = substr($horafin,3,2);

		/// compruebo que no sea menor la hora actual con la anterior, en caso que sea por ej 11 de la noche y 1 de la mañana
		if($horaf < $horai) { $horaf = $horaf + 24; 	} //le sumo 12 horas

		$ini = ($horai*60) + $mini;
		$fin = ($horaf*60) + $minf;
	
		$dif = $fin - $ini;
		return $dif;
		//$difh=floor($dif/3600);
		//$difm=floor(($dif-($difh*3600))/60);
		//$difs=$dif-($difm*60)-($difh*3600);
		//return date("H-i-s",mktime($difh,$difm,$difs));
	}

//// COMANDAS 	| OBTENER lISTADO DE CERRADAS PARA EL DASHBOARD
	public function obtener_listado_comandas_cerradas()
    {
		
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));

		$Desde = $this->datosObtenidos->Desde;
		$Hasta = $this->datosObtenidos->Hasta;
		$Moso_id = $this->datosObtenidos->Moso_id;
		$Jornada_id = $this->datosObtenidos->Jornada_id;
		
		if($Desde == NULL) 
		{ 
			$fecha = date('Y-m-d');
			$Desde = strtotime ( '-7 day' , strtotime ( $fecha ) ) ;
			$Desde = date ( 'Y-m-d' , $Desde );
		}
		if($Hasta == NULL) 
		{ 
			$Hasta = date('Y-m-d');
		}
		
		//Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

		$this->db->select('	tbl_comandas.*,
							tbl_clientes.Nombre as Nombre_cliente,
							tbl_clientes.Telefono,
							tbl_clientes.Direccion,
							tbl_mesas.Identificador,
							tbl_usuarios.Nombre as Nombre_moso');
		
		$this->db->from('tbl_comandas');

		$this->db->join('tbl_mesas', 'tbl_mesas.Id = tbl_comandas.Mesa_id','left');
		$this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_comandas.Moso_id','left');
		$this->db->join('tbl_clientes', 'tbl_clientes.Id = tbl_comandas.Cliente_id','left');

		$this->db->where("DATE_FORMAT(tbl_comandas.Fecha,'%Y-%m-%d') >=", $Desde);
		$this->db->where("DATE_FORMAT(tbl_comandas.Fecha,'%Y-%m-%d') <=", $Hasta);
		$this->db->where('tbl_comandas.Estado', 1);
		$this->db->where("tbl_comandas.Negocio_id", $this->session->userdata('Negocio_id'));

		if($Moso_id != "X")	{ $this->db->where('tbl_comandas.Moso_id', $Moso_id); }
		if($Jornada_id != 0)		{ $this->db->where('tbl_comandas.Jornada_id', $Jornada_id); }
		
		/// segun el rol el lo que pueda ver
		
		if ($this->session->userdata('Rol_id') < 3)
		{
			$Usuario_login_id = $this->session->userdata('Id');
			$this->db->where('tbl_comandas.Moso_id', $Usuario_login_id);
		}
		

		$this->db->order_by("tbl_comandas.Fecha", "desc");
		$query = $this->db->get();
		
		$array_comandas = $query->result_array();

		echo json_encode($array_comandas);
		
	}


//// COMANDAS 	| OBTENER DATOS DE UNA COMANDA
	public function datosComanda()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

		$Id = $_GET["Id"];

		$this->db->select('	tbl_comandas.*,
							tbl_clientes.Nombre as Nombre_cliente,
							tbl_clientes.Telefono,
							tbl_clientes.Direccion,
							tbl_clientes.Cant_compras,
							tbl_mesas.Identificador,
							tbl_usuarios.Nombre as Nombre_moso');

		$this->db->from('tbl_comandas');
		$this->db->join('tbl_mesas', 'tbl_mesas.Id = tbl_comandas.Mesa_id','left');
		$this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_comandas.Moso_id','left');
		$this->db->join('tbl_clientes', 'tbl_clientes.Id = tbl_comandas.Cliente_id','left');

		$this->db->where('tbl_comandas.Id', $Id);
		$this->db->where("tbl_comandas.Negocio_id", $this->session->userdata('Negocio_id'));

        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
	}


//// COMANDAS 	| LISTAR TODOS LOS ITEMS DE UNA COMANDA
	public function mostrarItemsComanda()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

		$Id = $_GET["Id"];

		// LISTADO DE PENDIENTES
		$this->db->select('	tbl_items_comanda.Estado,
							tbl_items_comanda.Id as Item_comanda_id,
							tbl_stock.*,
							tbl_categorias.Nombre_categoria');
		
		$this->db->from('tbl_items_comanda');
		
		$this->db->join('tbl_stock', 'tbl_stock.Id = tbl_items_comanda.Item_carga_id','left');
		$this->db->join('tbl_categorias', 'tbl_categorias.Id = tbl_stock.Categoria_id','left');
		
		$this->db->where('tbl_items_comanda.Comanda_id', $Id);
		$this->db->where('tbl_items_comanda.Estado', 0);
		$this->db->where("tbl_items_comanda.Negocio_id", $this->session->userdata('Negocio_id'));

        $query = $this->db->get();
		$resultPendientes = $query->result_array();

		// LISTADO DE ENTREGADOS
		$this->db->select('	tbl_items_comanda.Estado,
							tbl_items_comanda.Id as Item_comanda_id,
							tbl_items_comanda.Hora_entregado,
							tbl_stock.*,
							tbl_categorias.Nombre_categoria');
		
		$this->db->from('tbl_items_comanda');
		
		$this->db->join('tbl_stock', 'tbl_stock.Id = tbl_items_comanda.Item_carga_id','left');
		$this->db->join('tbl_categorias', 'tbl_categorias.Id = tbl_stock.Categoria_id','left');
		
		$this->db->where('tbl_items_comanda.Comanda_id', $Id);
		$this->db->where('tbl_items_comanda.Estado', 1);
		$this->db->where("tbl_items_comanda.Negocio_id", $this->session->userdata('Negocio_id'));

		$this->db->order_by("tbl_items_comanda.Hora_entregado", "asc");
		
		
		
		$query = $this->db->get();
		$resultEntregados = $query->result_array();

		$datos = array('Pendientes' => $resultPendientes, 'Entregados' => $resultEntregados);
		
		echo json_encode($datos);
		
	}

//// COMANDAS 	| CARGAR ITEM DE UNA COMANDA
	public function cargar_item_comanda()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));

		if(isset($this->datosObtenidos->itemComandaData->Id))
        {
            $Id = $this->datosObtenidos->itemComandaData->Id;
        }
        else
        {
            $Id = NULL;
		}
		
		$data = array(
                        
					'Comanda_id' => 		$this->datosObtenidos->itemComandaData->Comanda_id,
					'Item_carga_id' => 		$this->datosObtenidos->itemComandaData->Item_carga_id,
					'Estado' => 			0,
					'Negocio_id' => $this->session->userdata('Negocio_id'),
                    
                );

        $this->load->model('Restaurant_model');
        $insert_id = $this->Restaurant_model->insertar($data, $Id, 'tbl_items_comanda');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
	}

//// COMANDAS 	| ENTREGAS EN MESA
	public function entrega_en_mesa()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));

		$Comanda_id = $this->datosObtenidos->Comanda_id;
		
		$Hora = date("H:i");
		
		if($this->datosObtenidos->Tipo == "entrada"){
			$data = array( 	'Hora_entrada_en_mesa' => $Hora );

			$data_timeline = array( 	'Comanda_id' => $Comanda_id,
										'Accion' => 2,
										'Negocio_id' => $this->session->userdata('Negocio_id'), );
		}
		
		elseif($this->datosObtenidos->Tipo == "menu"){
			$data = array( 	'Hora_menu_en_mesa' => $Hora    );

			$data_timeline = array( 	'Comanda_id' => $Comanda_id,
										'Accion' => 3,
										'Negocio_id' => $this->session->userdata('Negocio_id'), );
		}

		elseif($this->datosObtenidos->Tipo == "cerrar"){
			$data = array( 	'Hora_cierre_comanda' => $Hora,
							'Valor_cuenta' => $this->datosObtenidos->ValorCuenta,
							'Estado' => 1,
							'Negocio_id' => $this->session->userdata('Negocio_id'),  );

			$data_timeline = array( 	'Comanda_id' => $Comanda_id,
										'Accion' => 4,
										'Negocio_id' => $this->session->userdata('Negocio_id'));

			// Actualizar compras clientes
				$Cant_compras =  1 + $this->datosObtenidos->CantCompras;
				$data_cliente = array( 	
											'Cant_compras' => $Cant_compras,
										);
				//// insert del cliente
				$this->load->model('Restaurant_model');
				$insert_id_cliente = $this->Restaurant_model->insertar($data_cliente, $this->datosObtenidos->Cliente_id, '  tbl_clientes');
		}

		elseif($this->datosObtenidos->Tipo == "abrir")
		{
			$data = array( 	'Hora_cierre_comanda' => NULL,
							'Valor_cuenta' => NULL,
							'Estado' => 0,
							'Negocio_id' => $this->session->userdata('Negocio_id'),  );

			$data_timeline = array( 	
									'Comanda_id' => $Comanda_id,
									'Accion' => 5,
									'Negocio_id' => $this->session->userdata('Negocio_id'), );

			// Actualizar compras clientes
			$Cant_compras =  $this->datosObtenidos->CantCompras - 1;
			$data_cliente = array( 	
										'Cant_compras' => $Cant_compras,
									);
			//// insert del cliente
			$this->load->model('Restaurant_model');
			$insert_id_cliente = $this->Restaurant_model->insertar($data_cliente, $this->datosObtenidos->Cliente_id, 'tbl_clientes');
		}
		
		/// insert en la comanda
        $this->load->model('Restaurant_model');
		$insert_id = $this->Restaurant_model->insertar($data, $Comanda_id, 'tbl_comandas');
		
		//// insert del timeline
		$this->load->model('Restaurant_model');
		$insert_id_timeline = $this->Restaurant_model->insertar($data_timeline, NULL, 'tbl_comandas_timeline');
                
		if ($insert_id >=0 ) 
		{   
			/// si todo sale bien, lo carga también en el timeline de comandas
			echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
	}

//// COMANDAS 	| ENTREGAR UN ITEM DE UNA COMANDA
	public function entregar_item()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));

		$Id = NULL; 	if(isset($this->datosObtenidos->Id)) { $Id = $this->datosObtenidos->Id; }
		
		$Stock_id = $this->datosObtenidos->Item_stock_id;

		$Hora = date("G:i");
		
		$data = array(
    
					'Hora_entregado' => 	$Hora,
					'Estado' => 			1,				/// 0 es aun no entregado, 1 es entregado
					'Negocio_id' => $this->session->userdata('Negocio_id') 
                    
                );

        $this->load->model('Restaurant_model');
        $insert_id = $this->Restaurant_model->insertar($data, $Id, 'tbl_items_comanda');
                
		if ($insert_id >=0 ) 
		{   
			
			/// SI EL ITEM ES APTO STOCKEO debe restarse automaticamente
				
				if ($this->datosObtenidos->Apto_stock == 1)
				{	
					$this->load->model('Restaurant_model');
					$datos_jornada = $this->Restaurant_model->datos_jornada();
					$Jornada_id = $datos_jornada["Id"];

					// funcion para crear un movimiento de stock de este producto

					$data = array(

						'Stock_id'          => $Stock_id,
						'Jornada_id'        => $Jornada_id,
						'Cantidad'          => 1,
						'Descripcion'       => 'Entregado en una comanda',
						'Usuario_id'        => $this->session->userdata('Id'),
						'Tipo_movimiento'   => 2,
						'Negocio_id' => $this->session->userdata('Negocio_id')
					);
			
					$this->load->model('App_model');
					$insert_id_mov = $this->App_model->insertar($data, Null, 'tbl_stock_movimientos');

					if ($insert_id_mov >= 0) // SI SE CARGO BIEN DEBE ACTUALIZAR LA TABLA tbl_stock, con el calculod de stock actual y el Id de la última actualización
					{
						/// consultar stock en cuestión y obtener la cantidad hasta ese momento
							$this->db->select('Cant_actual');
							$this->db->from('tbl_stock');
							$this->db->where('Id', $Stock_id);
							$this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));

							$query = $this->db->get();
							$result = $query->result_array();
							
							$cant_actual = $result[0]["Cant_actual"] - 1; // resta cantidad
			
								//////----------------------////////
								// Si cantidad actual es menor a stock ideal, debería poner Stock bajo en algun campo, para poder filtrar más facilmente. y hacer listas solo de productos bajos de stock
								//////----------------------////////

						/// Actualizo tbl_stock con los datos nuevos
							$data = array(
								'Ult_modificacion_id' => $insert_id_mov,
								'Cant_actual' => $cant_actual,
								'Negocio_id' => $this->session->userdata('Negocio_id')
							);

							$this->load->model('App_model');
							$insert_id = $this->App_model->insertar($data, $Stock_id, 'tbl_stock');

							if ($insert_id >= 0) {
								echo json_encode(array("Id" => $insert_id));
							} else {
								echo json_encode(array("Id" => 0));
							}
					} 
				}   
		} 
		else 	
		{
            echo json_encode(array("Id" => 0));
        }
	}



	
//// COMANDAS 	| VISTA RESUMEN COMANDAS
	public function resumencomandas()
	{
		if ( $this->session->userdata('Login') != true )
		{
			header("Location: ".base_url()."login"); /// enviar a pagina de error
		}
		else
		{	
			$this->load->view('resumencomandas');	
		}
	}

//// COMANDAS 	| CARGAR DESCUENTO
	public function cargarDescuento()
	{
		$CI =& get_instance();
		$CI->load->database();
		
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));

		$Id = NULL;
		if(isset($this->datosObtenidos->Comanda_id))
		{
			$Id = $this->datosObtenidos->Comanda_id;
		}
		
		$data = array( 	
						'Valor_descuento' => $this->datosObtenidos->Descuento,
						'Negocio_id' => $this->session->userdata('Negocio_id') 
					);

		/// insert en la comanda
		$this->load->model('Restaurant_model');
		$insert_id = $this->Restaurant_model->insertar($data, $Id, 'tbl_comandas');
		
				
		if ($insert_id >=0 ) 
		{   
			echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
			echo json_encode(array("Id" => 0));
		}
	}

//// COMANDAS 	| QR |	 VISTA COMANDA CLIENTE
	public function mipedido()
	{
		/// VER ALGUN TIPO DE SEGURIDAD PARA QUE LA PERSONA NO SIGA JODIENDO CON EL ENLACE UNA VEZ CERRADO EL PEDIDO
		// CONTROLAR QUE EL CODIGO QUE LLEGUE ESTE EN ESTADO ABIERTA LA COMANDA
		
		$this->load->view('comanda-pedido-cliente');	
			
	}	
	

//// COMANDAS 	| QR |	OBTENER DATOS DE UNA COMANDA POR CÓDIGO
	public function datosComandaCodigo()
	{
			
		//Esto siempre va es para instanciar la base de datos
		$CI =& get_instance();
		$CI->load->database();
		$token = @$CI->db->token;

		$Codigo = $_GET["Codigo"];

		$this->db->select('	tbl_comandas.*,
							tbl_clientes.Nombre as Nombre_cliente,
							tbl_clientes.Telefono,
							tbl_clientes.Direccion,
							tbl_clientes.Cant_compras,
							tbl_mesas.Identificador,
							tbl_usuarios.Nombre as Nombre_moso');

		$this->db->from('tbl_comandas');
		$this->db->join('tbl_mesas', 'tbl_mesas.Id = tbl_comandas.Mesa_id','left');
		$this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_comandas.Moso_id','left');
		$this->db->join('tbl_clientes', 'tbl_clientes.Id = tbl_comandas.Cliente_id','left');

		$this->db->where('tbl_comandas.Estado', 0);
		$this->db->where('tbl_comandas.Codigo', $Codigo);
		//$this->db->where("tbl_comandas.Negocio_id", $this->session->userdata('Negocio_id'));

		$query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
	}

//// COMANDAS 	| QR |	 LISTAR PRODUCTOS PEDIDOS EN UNA COMANDA
	public function itemsPedidos()
	{
			
		//Esto siempre va es para instanciar la base de datos
		$CI =& get_instance();
		$CI->load->database();
		$token = @$CI->db->token;

		$Id = $_GET["Id"];

		// LISTADO DE PENDIENTES
		$this->db->select('	tbl_items_comanda.Estado,
							tbl_items_comanda.Id as Item_comanda_id,
							tbl_stock.*,
							tbl_categorias.Nombre_categoria');
		
		$this->db->from('tbl_items_comanda');
		
		$this->db->join('tbl_stock', 'tbl_stock.Id = tbl_items_comanda.Item_carga_id','left');
		$this->db->join('tbl_categorias', 'tbl_categorias.Id = tbl_stock.Categoria_id','left');
		
		$this->db->where('tbl_items_comanda.Comanda_id', $Id);
		$this->db->where("tbl_items_comanda.Negocio_id", $_GET["Negocio_id"]);

		$query = $this->db->get();
		$result = $query->result_array();
		
		echo json_encode($result);
		
	}

//// COMANDAS 	| QR | OBTENER CATEGORIA DE ITEMS CARTA
	public function obtener_categorias_items_qr()
	{
			
		//Esto siempre va es para instanciar la base de datos
		$CI =& get_instance();
		$CI->load->database();
		$token = @$CI->db->token;
		
		// PARA QUE ME FUNCIONEN LOS TAB DEBO ARMAR UN ARRAY CON TODAS LAS CATEGORIAS, LUEGO OTRO ARRAY QUE BUSCA CADA ITEM DE CADA CATEGORIA. 
		// ENTONCES HAGO LOS VUE FOR UNO CON EL ARRAY CON NOMBRES DE CATEGORIA, Y OTRO CON CADA ITEM, ESTE DEBERIA SER UN FOR DENTRO DE OTRO FOR
		//$variableGet = $_GET["Id"];

		$this->db->select('*');
		$this->db->from('tbl_stock_categorias');
		$this->db->where('Apto_carta', 1);
		$this->db->where("Negocio_id", $_GET["Negocio_id"]);
		$this->db->order_by("Nombre_categoria", "asc");
		$query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
	}

//// COMANDAS 	| QR | MOSTRAR ITEMS CARTA POR CATEGORIA
	public function mostrarItemsCategoria_qr()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;
		
		$Id = $_GET["Id"];

		$this->db->select('	tbl_stock.*,
							tbl_stock_categorias.Nombre_categoria');
		$this->db->from('tbl_stock');

		$this->db->join('tbl_stock_categorias', 'tbl_stock_categorias.Id = tbl_stock.Categoria_id','left');

		$this->db->where('tbl_stock.Apto_carta', '1');
		$this->db->where("tbl_stock.Negocio_id", $_GET["Negocio_id"]);
		$this->db->where('tbl_stock.Activo', 1);

		if($Id > 0) {	$this->db->where('tbl_stock.Categoria_id', $Id);	}

		$this->db->order_by("tbl_stock.Nombre_item", "asc");

        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
	}


//// COMANDAS 	| QR | CARGAR ITEM DE UNA COMANDA
	public function cargar_item_comanda_qr()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));

		if(isset($this->datosObtenidos->itemComandaData->Id))
        {
            $Id = $this->datosObtenidos->itemComandaData->Id;
        }
        else
        {
            $Id = NULL;
		}
		
		$data = array(
                        
					'Comanda_id' => 	$this->datosObtenidos->itemComandaData->Comanda_id,
					'Item_carga_id' => 	$this->datosObtenidos->itemComandaData->Item_carga_id,
					'Estado' => 		0,
					'Negocio_id' => 	$_GET["Negocio_id"],
                    
                );

        $this->load->model('Restaurant_model');
        $insert_id = $this->Restaurant_model->insertar($data, $Id, 'tbl_items_comanda');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
	}

//// USUARIOS 	| VISTA ADMIN CONTROL PRESENCIA
	public function controlpresencia()
	{
		if ( $this->session->userdata('Login') != true )
		{
			header("Location: ".base_url()."login"); /// enviar a pagina de error
		}
		else
		{	
			if ( $this->session->userdata('Rol_id') >= 4)
			{
				
				//CONSULTAR SI HAY JORNADA ABIERTA, si la hay puede operar, si no la hay, manda a la vista de abrir jornada
				$this->load->model('Restaurant_model');
				$datos_jornada = $this->Restaurant_model->datos_jornada();
			
				if ($datos_jornada["Estado"] == 0) 
				{
					$this->load->view('admin-control-presencia');
				}

				else 
				{
					header("Location: ".base_url()."restaurant/iniciarjornada"); /// enviar a pagina de error
				}
			}
			else 
			{
				header("Location: ".base_url()."login"); /// enviar a pagina de error
			}		
		}
	}

//// USUARIOS 	| OBTENER USUARIOS ACTIVOS PARA CONTROL PRESENCIA
	public function obtener_Usuarios_control_presencia()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

		/// Obtengo los presentes
		$this->db->select('	tbl_usuarios.*,
							tbl_roles.Nombre_rol');
		$this->db->from('tbl_usuarios');
		$this->db->join('tbl_roles', 'tbl_roles.Id = tbl_usuarios.Rol_id','left');
		$this->db->where('tbl_usuarios.Activo',1);
		$this->db->where('tbl_usuarios.Presencia',1);
		$this->db->where("tbl_usuarios.Negocio_id", $this->session->userdata('Negocio_id'));
		$this->db->order_by("Nombre", "asc");

        $query = $this->db->get();
		$array_presentes = $query->result_array();
		
		/// Obtengo los No presentes
		$this->db->select('	tbl_usuarios.*,
							tbl_roles.Nombre_rol');
		$this->db->from('tbl_usuarios');
		$this->db->join('tbl_roles', 'tbl_roles.Id = tbl_usuarios.Rol_id','left');
		$this->db->where('tbl_usuarios.Activo',1);
		$this->db->where('tbl_usuarios.Presencia',0);
		$this->db->where("tbl_usuarios.Negocio_id", $this->session->userdata('Negocio_id'));

		$this->db->order_by("Nombre", "asc");
        $query = $this->db->get();
		$array_nopresentes = $query->result_array();
		
		$datos = array('Presentes' => $array_presentes, 'NoPresentes' => $array_nopresentes);
		
		echo json_encode($datos);
		

		///// PENDIENTE - para el modulo completo de manejo de empleados. debo hacer que muestre horarios de entrada, salida, tiempo trabajando, resumenes completos.
	}


//// USUARIOS 	| ACTIVAR/DESACTIVAR USUARIO EN CONTROL PRESENCIA
	public function jornada_activar_desactivar()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$this->datosEnviados = json_decode(file_get_contents('php://input'));

		$Id = NULL;
		
		if(isset($this->datosEnviados->Usuario_id))
        {
            $Id = $this->datosEnviados->Usuario_id;
        }
		
		/// ONTENER EL ID DE UNA JORNADA
			$this->load->model('Restaurant_model');
			$datos_jornada = $this->Restaurant_model->datos_jornada();
			$Jornada_id = $datos_jornada["Id"];

		$data = array(
					'Presencia' => 	$this->datosEnviados->Presencia,
					'Negocio_id' => $this->session->userdata('Negocio_id')
				);

        $this->load->model('Restaurant_model');
        $insert_id = $this->Restaurant_model->insertar($data, $Id, 'tbl_usuarios');
                
		if ($insert_id > 0) 
		{   
			// si eso sale bien, actualiza el log
			$data_log = array(
				'Colaborador_id' => $Id,	
				'Accion' => 	$this->datosEnviados->Presencia,
				'Jornada_id' =>		$Jornada_id,
				'Negocio_id' => $this->session->userdata('Negocio_id')
				);

			$this->load->model('Restaurant_model');
			$insert_id_log = $this->Restaurant_model->insertar($data_log, NULL, 'tbl_log_usuarios');
			
			echo json_encode(array("Id" => $insert_id_log));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
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
						'Negocio_id' => $this->session->userdata('Negocio_id')
					);

					$this->load->model('Restaurant_model');
					$insert_id = $this->Restaurant_model->insertar($data, $Id, 'tbl_usuarios');
					
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


//// COCINA 	| VISTA ADMIN COCINA
	public function cocina()
	{
		if ( $this->session->userdata('Login') != true )
		{
			header("Location: ".base_url()."login"); /// enviar a pagina de error
		}
		else
		{	
			if ( $this->session->userdata('Rol_id') > 2)
			{
				$this->load->model('Restaurant_model');
				$datos_jornada = $this->Restaurant_model->datos_jornada();
				if ($datos_jornada["Estado"] == 0) 
				{
					$this->load->view('admin-cocina');	
				}

				else 
				{
					header("Location: ".base_url()."restaurant/iniciarjornada"); /// enviar a pagina de error
				}
				
			}
			else 
			{
				header("Location: ".base_url()."login"); /// enviar a pagina de error
			}		
		}
	}

//// CARTA 		| SUBIR FOTO ITEMS CARTA
	public function subirFotoItemCarta()
	{
		$status = "";
		$msg = "";
		$file_element_name = 'Archivo';
		
		if ($status != "error")
		{
			$config['upload_path'] = './uploads/imagenes';
			$config['allowed_types'] = 'gif|jpg|jpg|png|doc|txt';
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

					$this->load->model('Restaurant_model');
					$insert_id = $this->Restaurant_model->insertar($data, $Id, 'tbl_stock');
					
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




//// DELIVERY	| VISTA LISTADO DE DELIVERYS ABIERTOS
	public function deliverys()
	{
		if ( $this->session->userdata('Login') != true )
		{
			header("Location: ".base_url()."login"); /// enviar a pagina de error
			
		}
		else
		{
			//CONSULTAR SI HAY JORNADA ABIERTA, si la hay puede operar, si no la hay, manda a la vista de abrir jornada
				$this->load->model('Restaurant_model');
				$datos_jornada = $this->Restaurant_model->datos_jornada();
			
				if ($datos_jornada["Estado"] == 0) 
				{
					$this->load->view('admin-deliverys');
				}

				else 
				{
					header("Location: ".base_url()."restaurant/iniciarjornada"); /// enviar a pagina de error
				}
		}		
	}


//// DELIVERY	| VISTA POR ID	
	public function delivery()
	{
		if ( $this->session->userdata('Login') != true )
		{
			header("Location: ".base_url()."login"); /// enviar a pagina de error
		}
		else
		{	
			//CONSULTAR SI HAY JORNADA ABIERTA, si la hay puede operar, si no la hay, manda a la vista de abrir jornada
				$this->load->model('Restaurant_model');
				$datos_jornada = $this->Restaurant_model->datos_jornada();
			
				if ($datos_jornada["Estado"] == 0) 
				{
					$datos = array('Rol_usuario'=> $this->session->userdata('Rol_id'));
					$this->load->view('admin-delivery',$datos);	
				}

				else 
				{
					header("Location: ".base_url()."restaurant/iniciarjornada"); /// enviar a pagina de error
				}
		}	
	}

//// DELIVERY	| VISTA RESUMEN
	public function resumendelivery()
	{
		if ( $this->session->userdata('Login') != true )
		{
			header("Location: ".base_url()."login"); /// enviar a pagina de error
		}
		else
		{	
			$datos = array('Rol_usuario'=> $this->session->userdata('Rol_id'));
			$this->load->view('delivery-resumen',$datos);	
		}	
	}

//// DELIVERY	| OBTENER LISTADO ABIERTOS
	public function obtener_listado_deliverys_abiertos()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

		/// ONTENER EL ID DE UNA JORNADA
			$this->load->model('Restaurant_model');
			$datos_jornada = $this->Restaurant_model->datos_jornada();
			$Jornada_id = $datos_jornada["Id"];

		$this->db->select('	tbl_delibery.*,
							tbl_clientes.Nombre as Nombre_cliente,
							tbl_clientes.Telefono,
							tbl_clientes.Direccion,
							tbl_usuarios.Nombre as Nombre_repartidor');

		$this->db->from('tbl_delibery');

		$this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_delibery.Repartidor_id','left');
		$this->db->join('tbl_clientes', 'tbl_clientes.Id = tbl_delibery.Cliente_id','left');

		$this->db->where('tbl_delibery.Estado', 0);
		$this->db->where('tbl_delibery.Jornada_id', $Jornada_id);
		$this->db->where("tbl_delibery.Negocio_id", $this->session->userdata('Negocio_id'));
		$this->db->order_by("tbl_delibery.FechaHora_pedido", "asc");
		
        $query = $this->db->get();
		$array_deliverys = $query->result_array();

		$datos = array();
		$valor_cuenta = 0;
		foreach ($array_deliverys as $delivery) 
		{
			$Id = $delivery["Id"];
			
			$this->db->select('	tbl_delibery_items.*,
								tbl_stock.Nombre_item,
								tbl_stock.Precio_venta');
		
			$this->db->from('tbl_delibery_items');
			
			$this->db->join('tbl_stock', 'tbl_stock.Id = tbl_delibery_items.Item_id','left');
			
			$this->db->where('tbl_delibery_items.Delibery_id', $Id);
			$this->db->where("tbl_delibery_items.Negocio_id", $this->session->userdata('Negocio_id'));

			$query = $this->db->get();
			$array_items = $query->result_array();
			$total = $query->num_rows();

			$valor_cuenta = 0;
			foreach ($array_items as $item) 
			{
				$valor_cuenta = $valor_cuenta + $item["Precio_venta"];
			}

			//// consultando Alerta - Si lleva más de cierto tiempo en la entrega de algo genera alerta
				$Hora = date("H:i");
				$Alerta = 0;
				$hora_pedido = date("H:i", strtotime($delivery["FechaHora_pedido"]));
				
				// CONSULTAR HACE CUANTO QUE SE REALIZÓ EL PEDIDO
				$num_alerta = $this->RestarHoras($hora_pedido, $Hora);
						
					if($num_alerta < 25 & $num_alerta > 15) {
							$Alerta = 1;
						}
					elseif($num_alerta > 25) {
							$Alerta = 2;
						}
					else 
						{
							$Alerta = 0;
						}
			
				
			//// Armado de array
			$Data_delivery = array(	'Datos_delivery' => $delivery, 
									'Datos_items'=> $array_items, 
									'Total_items' => $total, 
									'Valor_cuenta' => $valor_cuenta, 
									'Alerta' => $Alerta,
									'Negocio_id' => $this->session->userdata('Negocio_id')
								);

			array_push($datos, $Data_delivery);

		}
		///aca debo trabajarlo con foreach y array_push 

		echo json_encode($datos);
		
	}


//// DELIVERY 	| CARGAR O EDITAR
	public function crear_delivery()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));
		
		/// SETEAR EL ID
		$Id = null;
		if(isset($this->datosObtenidos->Datos->Id)) 	{ $Id = $this->datosObtenidos->Datos->Id; } $Repartidor_id = 1;
		if(isset($this->datosObtenidos->Repartidor_id)) { $Repartidor_id = $this->datosObtenidos->Repartidor_id; }
		
		$Cliente_id = $this->datosObtenidos->Datos->Cliente_id;

		//// SI EL CLIENTE NO EXISTIA LO DEBE CARGAR
			if($Cliente_id == 0)
			{
				$data = array(
							
					'Nombre' => 	$this->datosObtenidos->Datos->Nombre_cliente,
					'Telefono' => 	$this->datosObtenidos->Datos->Telefono,
					'Direccion' => 	$this->datosObtenidos->Datos->Direccion,
					'Ult_usuario_id' => 	$this->session->userdata('Id'),
					'Negocio_id' => $this->session->userdata('Negocio_id')
				);

			$this->load->model('Restaurant_model');
			$insert_id_cliente = $this->Restaurant_model->insertar($data, NULL, 'tbl_clientes');
					
				if ($insert_id_cliente >=0 ) 
				{   
					$Cliente_id = $insert_id_cliente;
				} 
			}

		
		/// ONTENER EL ID DE UNA JORNADA
		$this->load->model('Restaurant_model');
		$datos_jornada = $this->Restaurant_model->datos_jornada();
		$Jornada_id = $datos_jornada["Id"];
		
		$data = array(
					'Jornada_id' =>					$Jornada_id,
					'Cliente_id' => 				$Cliente_id,
					'Repartidor_id' => 				$Repartidor_id,
					'Observaciones' =>				$this->datosObtenidos->Datos->Observaciones,
					'Valor_delivery' =>				$this->datosObtenidos->Datos->Valor_delivery,
					'Observaciones_cocina' =>		$this->datosObtenidos->Datos->Observaciones_cocina,
					'Observaciones_delivery' =>		$this->datosObtenidos->Datos->Observaciones_delivery,
					'Estado' => 					0,
					'Negocio_id' => $this->session->userdata('Negocio_id')
                );

        $this->load->model('Restaurant_model');
		$insert_id = $this->Restaurant_model->insertar($data, $Id, 'tbl_delibery');
		
		
		if ($insert_id >=0 ) 
		{   
			
			$data_timeline = array( 	'Comanda_id' => $insert_id,
										'Accion' => 6,
										'Negocio_id' => $this->session->userdata('Negocio_id'));
			//// insert del timeline
			$this->load->model('Restaurant_model');
			$insert_id_timeline = $this->Restaurant_model->insertar($data_timeline, null, 'tbl_comandas_timeline');
			echo json_encode(array("Id" => $insert_id, 'Timeline_id' => $insert_id_timeline)); 
                   
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
	}

//// DELIVERY 	| Asignar cadete
	public function asignar_cadete()
	{
		$CI =& get_instance();
		$CI->load->database();
		
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));
		
		/// SETEAR EL ID
		$Id = null;
		if(isset($this->datosObtenidos->deliveryData->Id))
		{
			$Id = $this->datosObtenidos->deliveryData->Id;
		}
		
		$data = array(

					'Repartidor_id' => 	$this->datosObtenidos->Repartidor_id,
					'Negocio_id' => $this->session->userdata('Negocio_id')
				);
				
				echo json_encode($data);

		$this->load->model('Restaurant_model');
		$insert_id = $this->Restaurant_model->insertar($data, $Id, 'tbl_delibery');
		
		
		if ($insert_id >=0 ) 
		{   
			echo json_encode(array("Id" => $insert_id)); 
		} 
		else 
		{
			echo json_encode(array("Id" => 0));
		}
	}



//// DELIVERY	| CERRAR
	public function cerrar_delivery()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));

		if(isset($this->datosObtenidos->Id))
        {
            $Id = $this->datosObtenidos->Id;
        }
        else
        {
            $Id = NULL;
        }
		$fecha = date("Y-m-d H:i");
		$data = array(
                        
					'Valor_cuenta' => 	$this->datosObtenidos->Valor_cuenta, 
					'FechaHora_salidarepartidor' => $fecha,
					'Valor_delivery' => $this->datosObtenidos->Datos->Valor_delivery,
					'Estado' => 		1,
					//'Negocio_id' => $this->session->userdata('Negocio_id')
                );

        $this->load->model('Restaurant_model');
		$insert_id = $this->Restaurant_model->insertar($data, $Id, ' tbl_delibery');
		
		
		if ($insert_id >=0 ) 
		{   
			// Actualizar Timeline
				$data_timeline = array( 	'Comanda_id' => $insert_id,
											'Accion' => 7,
											'Negocio_id' => $this->session->userdata('Negocio_id') 
										);
				//// insert del timeline
				$this->load->model('Restaurant_model');
				$insert_id_timeline = $this->Restaurant_model->insertar($data_timeline, NULL, '  tbl_comandas_timeline');

			// Actualizar compras clientes
				$Cant_compras =  1 + $this->datosObtenidos->Datos->Cant_compras;
				$data_cliente = array( 	
										'Cant_compras' => $Cant_compras,
										);
				//// insert del cliente
				$this->load->model('Restaurant_model');
				$insert_id_cliente = $this->Restaurant_model->insertar($data_cliente, $this->datosObtenidos->Datos->Cliente_id, 'tbl_clientes');
			

            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
	}

//// DELIVERY	| REABRIR
	public function reabrir_delivery()
	{
		$CI =& get_instance();
		$CI->load->database();
		
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));

		$Id = $this->datosObtenidos->Delivery_id;
		
		$data = array(
						
					'Valor_cuenta' => 	0, 
					'FechaHora_salidarepartidor' => null,
					'Valor_delivery' => $this->datosObtenidos->Datos->Valor_delivery,
					'Estado' => 		0,
					'Negocio_id' => $this->session->userdata('Negocio_id')
				);

		$this->load->model('Restaurant_model');
		$insert_id = $this->Restaurant_model->insertar($data, $Id, ' tbl_delibery');
		
		
		if ($insert_id >=0 ) 
		{   
			$data_timeline = array( 	'Comanda_id' => $insert_id,
										'Accion' => 8,
										'Negocio_id' => $this->session->userdata('Negocio_id')
									 );
			//// insert del timeline
			$this->load->model('Restaurant_model');
			$insert_id_timeline = $this->Restaurant_model->insertar($data_timeline, NULL, '  tbl_comandas_timeline');

			// Actualizar compras clientes
			$Cant_compras =  $this->datosObtenidos->Datos->Cant_compras - 1;
			$data_cliente = array( 	
									'Cant_compras' => $Cant_compras,
									);
			//// insert del cliente
			$this->load->model('Restaurant_model');
			$insert_id_cliente = $this->Restaurant_model->insertar($data_cliente, $this->datosObtenidos->Datos->Cliente_id, 'tbl_clientes');

			echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
			echo json_encode(array("Id" => 0));
		}
	}


//// DELIVERY	| Reportar que ha sido entregado al cliente
	public function reportarEntrega()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));

		if(isset($this->datosObtenidos->Id))
        {
            $Id = $this->datosObtenidos->Id;
        }
        else
        {
            $Id = NULL;
        }
		$fecha = date("Y-m-d H:i");
		$data = array(
                        
					'FechaHora_entregaCliente' => $fecha,
					'Negocio_id' => $this->session->userdata('Negocio_id')
                );

        $this->load->model('Restaurant_model');
		$insert_id = $this->Restaurant_model->insertar($data, $Id, ' tbl_delibery');
		
		
		if ($insert_id >=0 ) 
		{   

            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
	}

//// DELIVERY	| Reportar que ha sido entregado al cliente
	public function reportarTomado()
	{
		$CI =& get_instance();
		$CI->load->database();
		
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));

		if(isset($this->datosObtenidos->Id))
		{
			$Id = $this->datosObtenidos->Id;
		}
		else
		{
			$Id = NULL;
		}
		$fecha = date("Y-m-d H:i");
		$data = array(
						
					'FechaHora_salidarepartidor' => $fecha,
					'Estado'			=> 0,
					'Negocio_id' => $this->session->userdata('Negocio_id')
				);

		$this->load->model('Restaurant_model');
		$insert_id = $this->Restaurant_model->insertar($data, $Id, ' tbl_delibery');
		
		
		if ($insert_id >=0 ) 
		{   

			echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
			echo json_encode(array("Id" => 0));
		}
	}

//// DELIVERY	| Reportar que ha sido entregado al cliente
	public function reportarCocina()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));

		if(isset($this->datosObtenidos->Id))
        {
            $Id = $this->datosObtenidos->Id;
        }
        else
        {
            $Id = NULL;
        }
		$fecha = date("Y-m-d H:i");
		$data = array(
                        
					'FechaHora_cocina' 	=> $fecha,
					'Negocio_id' => $this->session->userdata('Negocio_id')
					
                );

        $this->load->model('Restaurant_model');
		$insert_id = $this->Restaurant_model->insertar($data, $Id, ' tbl_delibery');
		
		
		if ($insert_id >=0 ) 
		{   

            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
	}
	

//// DELIVERY 	| OBTENER USUARIOS REPARTIDORES
	public function obtener_Usuarios_repartidores()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

		$this->db->select('	tbl_usuarios.*,
							tbl_roles.Nombre_rol');
		$this->db->from('tbl_usuarios');
		$this->db->join('tbl_roles', 'tbl_roles.Id = tbl_usuarios.Rol_id','left');
		$this->db->where('tbl_usuarios.Activo',1);
		$this->db->where('tbl_usuarios.Rol_id',5);
		$this->db->where("tbl_usuarios.Negocio_id", $this->session->userdata('Negocio_id'));
		$this->db->order_by("Nombre", "asc");
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
	}

//// DELIVERY 	| OBTENER DATOS POR ID
	public function datosDelivery()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

		$Id = $_GET["Id"];

		$this->db->select('	tbl_delibery.*,
							tbl_usuarios.Nombre as Nombre_repartidor,
							tbl_clientes.Nombre as Nombre_cliente,
							tbl_clientes.Cant_compras,
							tbl_clientes.Telefono,
							tbl_clientes.Direccion');
		$this->db->from('tbl_delibery');
		$this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_delibery.Repartidor_id','left');
		$this->db->join('tbl_clientes', 'tbl_clientes.Id = tbl_delibery.Cliente_id','left');
		//$this->db->where('tbl_delibery.Estado', 0);
		$this->db->order_by("tbl_delibery.FechaHora_pedido", "asc");
		$this->db->where('tbl_delibery.Id', $Id);
		$this->db->where("tbl_delibery.Negocio_id", $this->session->userdata('Negocio_id'));

        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
	}

//// DELIVERY 	| LISTAR TODOS LOS ITEMS DE UN DELIVERY CONSULTADO
	public function mostrarItemsDelivery()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

		$Id = $_GET["Id"];

		$this->db->select('	tbl_delibery_items.*,
							tbl_stock.Precio_venta,
							tbl_stock.Id as Item_stock_id,
							tbl_stock.Nombre_item');
		
		$this->db->from('tbl_delibery_items');
			
		$this->db->join('tbl_stock', 'tbl_stock.Id = tbl_delibery_items.Item_id','left');
			
		$this->db->where('tbl_delibery_items.Delibery_id', $Id);
		$this->db->where("tbl_delibery_items.Negocio_id", $this->session->userdata('Negocio_id'));

		$query = $this->db->get();
		$result = $query->result_array();
		
		echo json_encode($result);
	}

//// DELIVERY 	| MOSTRAR ITEMS CARTA APTO PARA DELIVERY
	public function mostrarItemsAptoDelivery()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;
		
		// PARA QUE ME FUNCIONEN LOS TAB DEBO ARMAR UN ARRAY CON TODAS LAS CATEGORIAS, LUEGO OTRO ARRAY QUE BUSCA CADA ITEM DE CADA CATEGORIA. 
		// ENTONCES HAGO LOS VUE FOR UNO CON EL ARRAY CON NOMBRES DE CATEGORIA, Y OTRO CON CADA ITEM, ESTE DEBERIA SER UN FOR DENTRO DE OTRO FOR
		
		$this->db->select('	tbl_stock.*,
							tbl_categorias.Nombre_categoria');
		$this->db->from('tbl_stock');
		$this->db->join('tbl_categorias', 'tbl_categorias.Id = tbl_stock.Categoria_id','left');
		
		$this->db->where('tbl_stock.Apto_delivery', 1);
		$this->db->where('tbl_stock.Apto_carta', 1);
		$this->db->where("tbl_stock.Negocio_id", $this->session->userdata('Negocio_id'));
		
		$this->db->order_by("tbl_stock.Nombre_item", "asc");
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
		
	}

//// DELIVERY 	| CARGAR ITEM
	public function cargar_item_delivery()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));

		if(isset($this->datosObtenidos->itemDeliveryData->Id))
        {
            $Id = $this->datosObtenidos->itemDeliveryData->Id;
        }
        else
        {
            $Id = NULL;
		}

		$Stock_id = $this->datosObtenidos->itemDeliveryData->Item_carga_id;
		
		$data = array(
                        
					'Delibery_id' => 	$this->datosObtenidos->itemDeliveryData->Delivery_id,
					'Item_id' => 		$Stock_id,
					'Negocio_id' => $this->session->userdata('Negocio_id') 
                    
                );

        $this->load->model('Restaurant_model');
        $insert_id = $this->Restaurant_model->insertar($data, $Id, 'tbl_delibery_items');
                
		if ($insert_id >=0 ) 
		{   
			//echo json_encode(array("Id" => $insert_id));  
			
			/// SI EL ITEM ES APTO STOCKEO debe restarse automaticamente
			if ($this->datosObtenidos->itemDeliveryData->Apto_stock == 1)
			{	
				$this->load->model('Restaurant_model');
				$datos_jornada = $this->Restaurant_model->datos_jornada();
				$Jornada_id = $datos_jornada["Id"];

				// funcion para crear un movimiento de stock de este producto

				$data = array(

					'Stock_id'          => $Stock_id,
					'Jornada_id'        => $Jornada_id,
					'Cantidad'          => 1,
					'Descripcion'       => 'Salida en delivery',
					'Usuario_id'        => $this->session->userdata('Id'),
					'Tipo_movimiento'   => 2,
					'Negocio_id' => $this->session->userdata('Negocio_id')
				);
		
				$this->load->model('App_model');
				$insert_id_mov = $this->App_model->insertar($data, Null, 'tbl_stock_movimientos');

				if ($insert_id_mov >= 0) // SI SE CARGO BIEN DEBE ACTUALIZAR LA TABLA tbl_stock, con el calculod de stock actual y el Id de la última actualización
				{
					/// consultar stock en cuestión y obtener la cantidad hasta ese momento
						$this->db->select('Cant_actual');
						$this->db->from('tbl_stock');
						$this->db->where('Id', $Stock_id);
						$this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));

						$query = $this->db->get();
						$result = $query->result_array();
						
						$cant_actual = $result[0]["Cant_actual"] - 1; // resta cantidad
		
							//////----------------------////////
							// Si cantidad actual es menor a stock ideal, debería poner Stock bajo en algun campo, para poder filtrar más facilmente. y hacer listas solo de productos bajos de stock
							//////----------------------////////

					/// Actualizo tbl_stock con los datos nuevos
						$data = array(
							'Ult_modificacion_id' => $insert_id_mov,
							'Cant_actual' => $cant_actual,
							'Negocio_id' => $this->session->userdata('Negocio_id')
						);

						$this->load->model('App_model');
						$insert_id = $this->App_model->insertar($data, $Stock_id, 'tbl_stock');

						if ($insert_id >= 0) {
							echo json_encode(array("Id" => $insert_id));
						} else {
							echo json_encode(array("Id" => 0));
						}
				} 
			}

		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
	}


//// DELIVERY 	| CARGAR UN DESCUENTO
	public function cargarDescuentoDelivery()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));

		$Id = NULL;
		if(isset($this->datosObtenidos->Delivery_id))
        {
            $Id = $this->datosObtenidos->Delivery_id;
        }
		
		$data = array( 	'Valor_descuento' => $this->datosObtenidos->Descuento );

		/// insert en la comanda
        $this->load->model('Restaurant_model');
		$insert_id = $this->Restaurant_model->insertar($data, $Id, 'tbl_delibery');
		
                
		if ($insert_id >=0 ) 
		{   
			echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
	}

//// DELIVERY 	| OBTENER lISTADO DE DELIVERYS CERRADOS
	public function obtener_listado_delivery_cerrados()
    {
		
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));

		$Desde = $this->datosObtenidos->Desde;
		$Hasta = $this->datosObtenidos->Hasta;
		$Repartidor_id = $this->datosObtenidos->Repartidor_id;
		$Jornada_id = $this->datosObtenidos->Jornada_id;
		
		if($Desde == 0) 
		{ 
			$fecha = date('Y-m-d');
			$Desde = strtotime ( '-7 day' , strtotime ( $fecha )) ;
			$Desde = date ( 'Y-m-d' , $Desde );
		}
		if($Hasta == 0) 
		{ 
			$Hasta = date('Y-m-d');
		}

		//Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

		$this->db->select('	tbl_delibery.*,
							tbl_usuarios.Nombre as Nombre_repartidor,
							tbl_jornadas.Descripcion,
							tbl_clientes.Nombre as Nombre_cliente,
							tbl_clientes.Telefono,
							tbl_clientes.Direccion');

		$this->db->from('tbl_delibery');

		$this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_delibery.Repartidor_id','left');
		$this->db->join('tbl_jornadas', 'tbl_jornadas.Id = tbl_delibery.Jornada_id','left');
		$this->db->join('tbl_clientes', 'tbl_clientes.Id = tbl_delibery.Cliente_id','left');
		
		$this->db->where("DATE_FORMAT(tbl_delibery.FechaHora_pedido,'%Y-%m-%d') >=", $Desde);
		$this->db->where("DATE_FORMAT(tbl_delibery.FechaHora_pedido,'%Y-%m-%d') <=", $Hasta);
		$this->db->where("tbl_delibery.Negocio_id", $this->session->userdata('Negocio_id'));
		$this->db->where('tbl_delibery.Estado', 1);

		if($Repartidor_id != "X")	{ 	$this->db->where('tbl_delibery.Repartidor_id', $Repartidor_id); 	}
		if($Jornada_id != 0)		{ 	$this->db->where('tbl_delibery.Jornada_id', $Jornada_id); 			}

		$this->db->order_by("tbl_delibery.FechaHora_pedido", "asc");
		
        $query = $this->db->get();
		$array_deliverys = $query->result_array();

		echo json_encode($array_deliverys);
		
	}


//// DELIVERY 	| VISTA IMPIMIR CUENTA
	public function imprimirCuentaDelivery()
	{
		if ( $this->session->userdata('Login') != true )
		{
			header("Location: ".base_url()."login"); /// enviar a pagina de error
		}
		else
		{
			//Esto siempre va es para instanciar la base de datos
			$CI =& get_instance();
			$CI->load->database();
			$token = @$CI->db->token;

			$Id = $_GET["Id"];

			
			$this->db->select('	tbl_delibery.*,
								tbl_usuarios.Nombre as Nombre_repartidor,
								tbl_clientes.Nombre as Nombre_cliente,
								tbl_clientes.Telefono,
								tbl_clientes.Direccion');
			$this->db->from('tbl_delibery');
			$this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_delibery.Repartidor_id','left');
			$this->db->join('tbl_clientes', 'tbl_clientes.Id = tbl_delibery.Cliente_id','left');
			$this->db->where('tbl_delibery.Id', $Id);
			
			$query = $this->db->get();
			$datosDelivery = $query->result_array();

			
			$this->db->select('	tbl_delibery_items.*,
								tbl_stock.*');
		
			$this->db->from('tbl_delibery_items');
			
			$this->db->join('tbl_stock', 'tbl_stock.Id = tbl_delibery_items.Item_id','left');
			
			$this->db->where('tbl_delibery_items.Delibery_id', $Id);
			$this->db->where("tbl_delibery_items.Negocio_id", $this->session->userdata('Negocio_id'));

			$query = $this->db->get();
			$arrayItems = $query->result_array();
			//$total = $query->num_rows();
			
			// sumar el costo de los items
				$Total_cuenta = 0;

				foreach ($arrayItems as $item) 
				{
					$precio = $item["Precio_venta"];

					$Total_cuenta = $Total_cuenta + $precio;
				}

				$Total_cuenta = $Total_cuenta - $datosDelivery[0]["Valor_descuento"];
			
			$datos = array('infoDelivery' => $datosDelivery[0], 'Items' => $arrayItems, 'Cuenta' => $Total_cuenta);

			$this->load->view('imprimircuentadelivery', $datos);	
		}	
	}

//// DELIVERY 	| SETEAR MODO DE PAGO
	public function setear_modo_pago_delivery()
	{
		$CI =& get_instance();
		$CI->load->database();
		
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));

		$Id = NULL;
		if(isset($_GET["Id"])) { $Id = $_GET["Id"]; }
		
		$data = array(

					'Modo_pago' => $this->datosObtenidos->Modo_pago,
					'Negocio_id' => $this->session->userdata('Negocio_id')
					
				);

		$this->load->model('Restaurant_model');
		$insert_id = $this->Restaurant_model->insertar($data, $Id, 'tbl_delibery');

		echo json_encode(array("Id" => $insert_id));  
	}



//// DELIVERY REPARTOS	| VISTA LISTADO DE DELIVERYS ABIERTOS
	public function repartos()
	{
		if ( $this->session->userdata('Login') != true )
		{
			header("Location: ".base_url()."login"); /// enviar a pagina de error
			
		}
		else
		{
			//CONSULTAR SI HAY JORNADA ABIERTA, si la hay puede operar, si no la hay, manda a la vista de abrir jornada
				$this->load->model('Restaurant_model');
				$datos_jornada = $this->Restaurant_model->datos_jornada();
			
				if ($datos_jornada["Estado"] == 0) 
				{
					$this->load->view('admin-repartos');
				}

				else 
				{
					header("Location: ".base_url()."restaurant/iniciarjornada"); /// enviar a pagina de error
				}
		}		
	}

//// DELIVERY REPARTOS	| VISTA LISTADO DE REPARTOS CERRADOS
	public function resumenrepartos()
	{
		if ( $this->session->userdata('Login') != true )
		{
			header("Location: ".base_url()."login"); /// enviar a pagina de error
		}
		else
		{
			$this->load->view('repartos-resumen');
		}		
	}

//// DELIVERY REPARTOS | Listado de repartos a realizar
	public function obtener_repartos_abiertos()
	{
		//Esto siempre va es para instanciar la base de datos
		$CI =& get_instance();
		$CI->load->database();
		$token = @$CI->db->token;

		/// ONTENER EL ID DE UNA JORNADA
			$this->load->model('Restaurant_model');
			$datos_jornada = $this->Restaurant_model->datos_jornada();
			$Jornada_id = $datos_jornada["Id"];

			$this->db->select('	tbl_delibery.*,
								tbl_clientes.Nombre as Nombre_cliente,
								tbl_clientes.Telefono,
								tbl_clientes.Direccion,
								tbl_usuarios.Nombre as Nombre_repartidor');

			$this->db->from('tbl_delibery');

			$this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_delibery.Repartidor_id','left');
			$this->db->join('tbl_clientes', 'tbl_clientes.Id = tbl_delibery.Cliente_id','left');

			// Si es usuario delivery solo puede ver los que esten asignados a él
			if($this->session->userdata('Rol_id') < 4)
			{
				$this->db->where('tbl_delibery.Repartidor_id', $this->session->userdata('Id'));
			}

			$this->db->where('tbl_delibery.FechaHora_cocina >', 0);
			$this->db->where('tbl_delibery.FechaHora_entregaCliente', NULL);
			//$this->db->where('tbl_delibery.Estado', 1);
			$this->db->where('tbl_delibery.Jornada_id', $Jornada_id);
			$this->db->where("tbl_delibery.Negocio_id", $this->session->userdata('Negocio_id'));
			
			$this->db->order_by("tbl_delibery.FechaHora_pedido", "asc");
			
			$query = $this->db->get();
			$array_deliverys = $query->result_array();

			$Datos_tomados = array();
			$Datos_a_tomar = array();

			foreach ($array_deliverys as $delivery) 
			{
				$Id = $delivery["Id"];
				
				$this->db->select('	tbl_delibery_items.*,
									tbl_stock.Nombre_item,
									tbl_stock.Precio_venta');
			
				$this->db->from('tbl_delibery_items');
				
				$this->db->join('tbl_stock', 'tbl_stock.Id = tbl_delibery_items.Item_id','left');
				
				$this->db->where('tbl_delibery_items.Delibery_id', $delivery["Id"]);
				//$this->db->where("tbl_stock.Negocio_id", $this->session->userdata('Negocio_id'));

				$query = $this->db->get();
				$result = $query->result_array();
				$total = $query->num_rows();

				//// consultando Alerta - Si lleva más de cierto tiempo en la entrega de algo genera alerta
					$Hora = date("H:i");
					$Alerta = 0;
					$hora_pedido = date("H:i", strtotime($delivery["FechaHora_pedido"]));
					
					// CONSULTAR HACE CUANTO QUE SE REALIZÓ EL PEDIDO
						$num_alerta = $this->RestarHoras($hora_pedido, $Hora);
							
						if($num_alerta < 25 & $num_alerta > 15) {
								$Alerta = 1;
							}
						elseif($num_alerta > 25) {
								$Alerta = 2;
							}
						else 
							{
								$Alerta = 0;
							}
				
					
				//// Armado de array
				$Data_delivery = array('Datos_reparto' => $delivery, 'Datos_items'=> $result, 'Total_items' => $total, 'Alerta' => $Alerta);

				///// POSICIONO LA COMANDA SEGÚN SI ESTA EN COCINA O SI YA LA RETIRÓ EL REPARTIDOR
							// si tiene la fecha hora de salida repartidor es porque ya lo tiene, si no aun esta en cocina
				if($delivery["FechaHora_salidarepartidor"] == null)
				{	
					array_push($Datos_a_tomar, $Data_delivery);	
				}			
				else
				{	
					array_push($Datos_tomados, $Data_delivery);
				}
				
			}
			///aca debo trabajarlo con foreach y array_push 

		

		$Datos_todos = array('Deliverys_tomados' => $Datos_tomados, 'Deliverys_a_tomar' => $Datos_a_tomar);
		
		echo json_encode($Datos_todos);
		
	}

//// DELIVERY REPARTOS | Listado de repartos realizados
	public function obtener_listado_repartos()
	{
		
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));

		$Desde = $this->datosObtenidos->Desde;
		$Hasta = $this->datosObtenidos->Hasta;
		$Jornada_id = $this->datosObtenidos->Jornada_id;
		
		if($Desde == 0) 
		{ 
			$fecha = date('Y-m-d');
			$Desde = strtotime ( '-2 day' , strtotime ( $fecha ) ) ;
			$Desde = date ( 'Y-m-d' , $Desde );
		}
		if($Hasta == 0) 
		{ 
			$Hasta = date('Y-m-d');
		}

		//Esto siempre va es para instanciar la base de datos
		$CI =& get_instance();
		$CI->load->database();
		$token = @$CI->db->token;

		$this->db->select('	tbl_delibery.*,
							tbl_usuarios.Nombre as Nombre_repartidor,
							tbl_jornadas.Descripcion,
							tbl_clientes.Nombre as Nombre_cliente,
							tbl_clientes.Telefono,
							tbl_clientes.Direccion');
		$this->db->from('tbl_delibery');
		$this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_delibery.Repartidor_id','left');
		$this->db->join('tbl_jornadas', 'tbl_jornadas.Id = tbl_delibery.Jornada_id','left');
		$this->db->join('tbl_clientes', 'tbl_clientes.Id = tbl_delibery.Cliente_id','left');
		
		$this->db->where("DATE_FORMAT(tbl_delibery.FechaHora_pedido,'%Y-%m-%d') >=", $Desde);
		$this->db->where("DATE_FORMAT(tbl_delibery.FechaHora_pedido,'%Y-%m-%d') <=", $Hasta);
		
		$this->db->where('tbl_delibery.Estado', 1);

		$this->db->where('tbl_delibery.Repartidor_id', $this->session->userdata('Id'));
		$this->db->where("tbl_delibery.Negocio_id", $this->session->userdata('Negocio_id'));

		if($Jornada_id != 0) { $this->db->where('tbl_delibery.Jornada_id', $Jornada_id); }

		$this->db->order_by("tbl_delibery.FechaHora_pedido", "asc");

	
		$query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
	}


//// COMANDAS 	| SETEAR MODO DE PAGO
	public function setear_modo_pago_comanda()
	{
		$CI =& get_instance();
		$CI->load->database();
		
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));

		$Id = NULL;
		if(isset($_GET["Id"])) { $Id = $_GET["Id"]; }
		
		$data = array(

					'Modo_pago' => $this->datosObtenidos->Modo_pago,
					
				);

		$this->load->model('Restaurant_model');
		$insert_id = $this->Restaurant_model->insertar($data, $Id, 'tbl_comandas');

		echo json_encode(array("Id" => $insert_id));  
	}


//// COCINA 	| OBTENER LISTADO DE COMANDAS Y DELIVERYS PARA PREPARAR
	public function obtener_listado_cocina()
    {
		//// CONSULTANDO DATOS DE COMANDAS
			//Esto siempre va es para instanciar la base de datos
			$CI =& get_instance();
			$CI->load->database();
			$token = @$CI->db->token;

			/// ONTENER EL ID DE UNA JORNADA
			$this->load->model('Restaurant_model');
			$datos_jornada = $this->Restaurant_model->datos_jornada();
			$Jornada_id = $datos_jornada["Id"];

			$this->db->select('	tbl_comandas.*,
								tbl_mesas.Identificador,
								tbl_usuarios.Nombre as Nombre_moso');
			$this->db->from('tbl_comandas');
			$this->db->join('tbl_mesas', 'tbl_mesas.Id = tbl_comandas.Mesa_id','left');
			$this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_comandas.Moso_id','left');
			$this->db->where('tbl_comandas.Estado', 0);
			$this->db->where('tbl_comandas.Jornada_id', $Jornada_id);
			$this->db->where("tbl_comandas.Negocio_id", $this->session->userdata('Negocio_id'));
			$this->db->order_by("tbl_comandas.Id", "asc");

			$query = $this->db->get();
			$array_comandas = $query->result_array();
			$Cantidad_comandas = $query->num_rows();

			$Datos_comandas = array();

			foreach ($array_comandas as $comanda) 
			{
				$Id = $comanda["Id"];
				
				$this->db->select('	tbl_items_comanda.*,
									tbl_stock.*,
									tbl_categorias.Nombre_categoria');
			
				$this->db->from('tbl_items_comanda');
				
				$this->db->join('tbl_stock', 'tbl_stock.Id = tbl_items_comanda.Item_carga_id','left');
				$this->db->join('tbl_categorias', 'tbl_categorias.Id = tbl_stock.Categoria_id','left');
				
				$this->db->where('tbl_items_comanda.Comanda_id', $Id);
				$this->db->where('tbl_items_comanda.Estado', 0);
				$this->db->where("tbl_items_comanda.Negocio_id", $this->session->userdata('Negocio_id'));

				$query = $this->db->get();
				$result = $query->result_array();
				
				//// consultando Alerta - Si lleva más de cierto tiempo en la entrega de algo genera alerta
					$Hora = date("H:i");
					$Alerta = 0;
					//si ya se entrego el menu, veo hace cuanto, si es mas de 40m MANDO ALERTA
					if($comanda["Hora_menu_en_mesa"] != NULL)
					{
						$num_alerta = $this->RestarHoras($comanda["Hora_menu_en_mesa"], $Hora);
							
							if($num_alerta < 25 & $num_alerta > 40) {
								$Alerta = 1;
							}
							elseif($num_alerta > 40) {
								$Alerta = 2;
							}
							else 
							{
								$Alerta = 0;
							}
					}
					else	/// si no se entrego el menu, veo hace cuanto que se entrego la entrada
					{
						/// si se entrego la entrada veo hace cuanto, si es mas de 20 minutos, MANDO ALERTA
						if($comanda["Hora_entrada_en_mesa"] != NULL)
						{
							$num_alerta = $this->RestarHoras($comanda["Hora_entrada_en_mesa"], $Hora);
							
							if($num_alerta < 15 & $num_alerta > 8) 
							{
								$Alerta = 1;
							}
							elseif($num_alerta > 15) 
							{
								$Alerta = 2;
							}
							else 
							{
								$Alerta = 0;
							}
						}
						else /// si no se entrego la entrada, veo hace cuanto tiempo llegaron, si es mas de 15 minutos MANDO ALERTA
						{
							$num_alerta = $this->RestarHoras($comanda["Hora_llegada"], $Hora);
							
							if($num_alerta < 15 & $num_alerta > 8) {
								$Alerta = 1;
							}
							elseif($num_alerta > 15) {
								$Alerta = 2;
							}
						}
					}
					
				//// Armado de array
				$comanda = array('Info_comanda' => $comanda, 'Datos_items'=> $result,  'Alerta' => $Alerta);

				array_push($Datos_comandas, $comanda);

			}
			
			$Comandas = array("Datos" => $Datos_comandas, 'Cantidad' => $Cantidad_comandas);

		
		//// CONSULTANDO DATOS DE DELIVERYS

			$this->db->select('	tbl_delibery.*,
								tbl_usuarios.Nombre as Nombre_repartidor,
								tbl_clientes.Nombre as Nombre_cliente');
			$this->db->from('tbl_delibery');
			$this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_delibery.Repartidor_id','left');
			$this->db->join('tbl_clientes', 'tbl_clientes.Id = tbl_delibery.Cliente_id','left');

			$this->db->where('tbl_delibery.Estado', 0);
			$this->db->where('tbl_delibery.Jornada_id', $Jornada_id);
			$this->db->where('tbl_delibery.FechaHora_cocina', NULL);
			$this->db->where("tbl_delibery.Negocio_id", $this->session->userdata('Negocio_id'));

			$this->db->order_by("tbl_delibery.FechaHora_pedido", "asc");
			
			$query = $this->db->get();
			$array_deliverys = $query->result_array();
			$Cantidad_deliverys = $query->num_rows();

			$Datos_deliverys = array();

			foreach ($array_deliverys as $delivery) 
			{
				$Id = $delivery["Id"];
				
				$this->db->select('	tbl_delibery_items.*,
									tbl_stock.Nombre_item');
			
				$this->db->from('tbl_delibery_items');
				$this->db->join('tbl_stock', 'tbl_stock.Id = tbl_delibery_items.Item_id','left');
				$this->db->where('tbl_delibery_items.Delibery_id', $Id);
				$this->db->where("tbl_delibery_items.Negocio_id", $this->session->userdata('Negocio_id'));

				$query = $this->db->get();
				$result = $query->result_array();

				//// consultando Alerta - Si lleva más de cierto tiempo en la entrega de algo genera alerta
					$Hora = date("H:i");
					$Alerta = 0;
					$hora_pedido = date("H:i", strtotime($delivery["FechaHora_pedido"]));
					
					// CONSULTAR HACE CUANTO QUE SE REALIZÓ EL PEDIDO
					$num_alerta = $this->RestarHoras($hora_pedido, $Hora);
							
						if($num_alerta < 30 & $num_alerta > 15) {
								$Alerta = 1;
							}
						elseif($num_alerta > 30) {
								$Alerta = 2;
							}
						else 
							{
								$Alerta = 0;
							}
				
					
				//// Armado de array
				$Data_delivery = array('Info_delivery' => $delivery, 'Datos_items'=> $result, 'Alerta' => $Alerta);

				array_push($Datos_deliverys, $Data_delivery);

			}
			
			$Delivery = array("Datos" => $Datos_deliverys, 'Cantidad' => $Cantidad_deliverys);
		
		
		///ARMANDO ARRAY FINAL
		$Datos = array('Comandas' => $Comandas, 'Delivery' => $Delivery);
		echo json_encode($Datos);
		
	}

//// CAJA		| VISTA ADMIN CONTROL PRESENCIA
	public function caja()
	{
		if ( $this->session->userdata('Login') != true )
		{
			header("Location: ".base_url()."login"); /// enviar a pagina de error
		}
		else
		{	
			if ( $this->session->userdata('Rol_id') >= 4)
			{
				
				//CONSULTAR SI HAY JORNADA ABIERTA, si la hay puede operar, si no la hay, manda a la vista de abrir jornada
				$this->load->model('Restaurant_model');
				$datos_jornada = $this->Restaurant_model->datos_jornada();
			
				if ($datos_jornada["Estado"] == 0) 
				{
					$this->load->view('caja-admin');
				}

				else 
				{
					header("Location: ".base_url()."restaurant/iniciarjornada"); /// enviar a pagina de error
				}
			}
			else 
			{
				header("Location: ".base_url()."login"); /// enviar a pagina de error
			}		
		}
	}


//// CAJA		| OBTENER MOVIMIENTOS
	public function obtener_movimientos_caja()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

		$Id = $_GET["Id"];

		$this->db->select('	tbl_caja.*,
							tbl_usuarios.Nombre,
							tbl_jornadas.Fecha_inicio,
							tbl_jornadas.Descripcion');
		$this->db->from('tbl_caja');
		$this->db->join('tbl_jornadas', 'tbl_jornadas.Id = tbl_caja.Jornada_id','left');
		$this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_caja.Usuario_id','left');
		
		if ($Id > 0) 
		{
			$this->db->where('tbl_caja.Jornada_id', $Id);
		}
		$this->db->where("tbl_caja.Negocio_id", $this->session->userdata('Negocio_id'));

		$this->db->order_by("tbl_caja.Id", "desc");
		
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
	}
	
//// CAJA		|  CARGAR O EDITAR MOVIMIENTO DE CAJA
	public function cargar_movimientos_caja()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));

		$Id = NULL;
		if(isset($this->datosObtenidos->Data->Id))
        {
            $Id = $this->datosObtenidos->Data->Id;
		}
		/// ONTENER EL ID DE UNA JORNADA
			$this->load->model('Restaurant_model');
			$datos_jornada = $this->Restaurant_model->datos_jornada();
			$Jornada_id = $datos_jornada["Id"];

		$data = array(
                        
					'Jornada_id' => 	$Jornada_id,
					'Valor_ingreso' => 	$this->datosObtenidos->Data->Valor_ingreso,
					'Valor_egreso' => 	$this->datosObtenidos->Data->Valor_egreso,
					'Observaciones' => 	$this->datosObtenidos->Data->Observaciones,
					'Usuario_id' => 	$this->session->userdata('Id'),
					'Negocio_id' => 	$this->session->userdata('Negocio_id')
				);

        $this->load->model('Restaurant_model');
        $insert_id = $this->Restaurant_model->insertar($data, $Id, 'tbl_caja');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
	}
	////

//// COMANDAS 	| VISTA IMPIMIR COMANDA
	public function imprimircomanda()
	{
		if ( $this->session->userdata('Login') != true )
		{
			header("Location: ".base_url()."login"); /// enviar a pagina de error
		}
		else
		{
			//Esto siempre va es para instanciar la base de datos
			$CI =& get_instance();
			$CI->load->database();
			$token = @$CI->db->token;

			$Id = $_GET["Id"];

			//DATOS DE LA COMANDA
				$this->db->select('	tbl_comandas.*,
									tbl_mesas.Identificador,
									tbl_usuarios.Nombre as Nombre_moso');
				
				$this->db->from('tbl_comandas');

				$this->db->join('tbl_mesas', 'tbl_mesas.Id = tbl_comandas.Mesa_id','left');
				$this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_comandas.Moso_id','left');
			
				$this->db->where('tbl_comandas.Id', $Id);
				$this->db->where("tbl_comandas.Negocio_id", $this->session->userdata('Negocio_id'));
				$query = $this->db->get();
				$datosComanda = $query->result_array();
			
			// LISTADO DE ITEMS
				$this->db->select('	tbl_items_comanda.Estado,
									tbl_items_comanda.Id as Item_comanda_id,
									tbl_stock.*');
				
				$this->db->from('tbl_items_comanda');
				
				$this->db->join('tbl_stock', 'tbl_stock.Id = tbl_items_comanda.Item_carga_id','left');
				
				$this->db->where('tbl_items_comanda.Comanda_id', $Id);
				$this->db->where("tbl_items_comanda.Negocio_id", $this->session->userdata('Negocio_id'));

				//$this->db->where('tbl_items_comanda.Estado', 1); //busca solo los entregados
				$this->db->order_by("tbl_stock.Nombre_item", "asc");

				$query = $this->db->get();
				$arrayItems = $query->result_array();

				
			
			$datos = array('infoComandas' => $datosComanda[0], 'Items' => $arrayItems);

			$this->load->view('imprimircomanda', $datos);	
		}	
	}

//// JORNADA	| OBTENER CONTABILIDAD
	public function obtener_contabilidad_jornada_actual()
	{
			
		//Esto siempre va es para instanciar la base de datos
		$CI =& get_instance();
		$CI->load->database();
		$token = @$CI->db->token;

		/// ONTENER EL ID DE UNA JORNADA
			$this->load->model('Restaurant_model');
			$datos_jornada = $this->Restaurant_model->datos_jornada();
			$Jornada_id = $datos_jornada["Id"];
			// --------

			/// SI TRAE EL ID, TOMA ESE ID Y NO LA JORNADA ACTUAL
			if(isset($_GET["Id"]))
			{
				$Jornada_id = $_GET["Id"];
				
				// BUSCO DATOS DE ESTA JORNADA
				$this->db->select('*');
				$this->db->from('tbl_jornadas');
				$this->db->where('Id', $_GET["Id"]);
				$this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));
				

				$query = $this->db->get();
				$datos_jornada = $query->result_array();
				$datos_jornada = $datos_jornada[0];
			}
		
		/// CALCULANDO INGRESOS DELIVERY EN EFECTIVO	
			$this->db->select('	Valor_cuenta, Valor_descuento');
			$this->db->from('tbl_delibery');

			$this->db->where('Jornada_id', $Jornada_id);
			$this->db->where('Estado', 1);
			$this->db->where('Modo_pago', 1); //Solo se muestra en caja lo cobrado en efectivo
			$this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));


			$query = $this->db->get();
			$result_delivery_efectivo = $query->result_array();

			$total_ingresos_delivery_efectivo = 0;

			foreach ($result_delivery_efectivo as $movimiento) 
			{
				$total_ingresos_delivery_efectivo = $total_ingresos_delivery_efectivo + $movimiento["Valor_cuenta"] - $movimiento["Valor_descuento"];
			}
		
		/// CALCULANDO INGRESOS DELIVERY Con TARJETA	
			$this->db->select('	Valor_cuenta, Valor_descuento');
			$this->db->from('tbl_delibery');

			$this->db->where('Jornada_id', $Jornada_id);
			$this->db->where('Estado', 1);
			$this->db->where('Modo_pago', 2); //Solo se muestra en caja lo cobrado Con TARJETA
			$this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));


			$query = $this->db->get();
			$result_delivery_tarjeta = $query->result_array();

			$total_ingresos_delivery_tarjeta = 0;

			foreach ($result_delivery_tarjeta as $movimiento) 
			{
				$total_ingresos_delivery_tarjeta = $total_ingresos_delivery_tarjeta + $movimiento["Valor_cuenta"] - $movimiento["Valor_descuento"];
			}

		/// CALCULANDO TOTAL DELIVERY
			$total_ingresos_delivery = $total_ingresos_delivery_efectivo + $total_ingresos_delivery_tarjeta;

		

		/// CALCULANDO INGRESOS POR COMANDAS EN EFECTIVO
			$this->db->select('	Valor_cuenta,
								Valor_descuento,
								Valor_adicional');
			$this->db->from('tbl_comandas');

			$this->db->where('Jornada_id', $Jornada_id);
			$this->db->where('Estado', 1);
			$this->db->where('Modo_pago', 1); //Solo se muestra en caja lo cobrado en efectivo
			$this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));


			$query = $this->db->get();
			$result_comanda_efectivo = $query->result_array();

			$total_ingresos_comanda_efectivo = 0;

			foreach ($result_comanda_efectivo as $movimiento) 
			{
				$total_ingresos_comanda_efectivo = $total_ingresos_comanda_efectivo + $movimiento["Valor_cuenta"] + $movimiento["Valor_adicional"] - $movimiento["Valor_descuento"];
			}

		/// CALCULANDO INGRESOS POR COMANDAS con tarjeta
		$this->db->select('	Valor_cuenta,
							Valor_descuento,
							Valor_adicional');
		$this->db->from('tbl_comandas');

		$this->db->where('Jornada_id', $Jornada_id);
		$this->db->where('Estado', 1);
		$this->db->where('Modo_pago', 2); //Solo se muestra en caja lo cobrado con tarjeta
		$this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));

		$query = $this->db->get();
		$result_comanda_tarjeta = $query->result_array();

		$total_ingresos_comanda_tarjeta = 0;

		foreach ($result_comanda_tarjeta as $movimiento) 
		{
		$total_ingresos_comanda_tarjeta = $total_ingresos_comanda_tarjeta + $movimiento["Valor_cuenta"] + $movimiento["Valor_adicional"] - $movimiento["Valor_descuento"];
		} 


		/// CALCULANDO INGRESOS Y EGRESOS CAJA	
			$this->db->select('	Valor_ingreso,
								Valor_egreso');
			$this->db->from('tbl_caja');

			$this->db->where('Jornada_id', $Jornada_id);
			$this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));

			$query = $this->db->get();
			$result_caja = $query->result_array();  

			$total_ingresos_caja = 0;
			$total_egresos_caja = 0;

			foreach ($result_caja as $movimiento) 
			{
			$total_ingresos_caja = $total_ingresos_caja + $movimiento["Valor_ingreso"];
			$total_egresos_caja = $total_egresos_caja + $movimiento["Valor_egreso"];
			}

		/// CALCULANDO TOTAL DELIVERY
			$total_ingresos_comanda = $total_ingresos_comanda_efectivo + $total_ingresos_comanda_tarjeta;
		
		/// CALCULANDO TOTAL EFECTIVO
			$Total_ingresos_efectivo = $total_ingresos_comanda_efectivo + $total_ingresos_delivery_efectivo;

		/// CALCULANDO TOTAL EFECTIVO
			$Total_ingresos_tarjeta = $total_ingresos_comanda_tarjeta + $total_ingresos_delivery_tarjeta;

		/// CALCULANDO TOTAL EFECTIVO EN CAJA
			$total_efectivo_estimado_caja = $total_ingresos_caja - $total_egresos_caja + $total_ingresos_delivery_efectivo + $total_ingresos_comanda_efectivo + $datos_jornada["Efectivo_caja_inicio"];

		/// CALCULANDO TOTAL EFECTIVO EN CAJA
			$Resultado_jornada = $total_ingresos_caja - $total_egresos_caja + $total_ingresos_delivery_efectivo + $total_ingresos_comanda_efectivo + $Total_ingresos_tarjeta;
		
		$Datos = array(
				'Datos_jornada' => $datos_jornada,

				'Resultado_jornada' => $Resultado_jornada, 
				'Total_ingresos_efectivo' => $Total_ingresos_efectivo, 
				'Total_ingresos_tarjeta' => $Total_ingresos_tarjeta, 
				
				'Efectivo_estimado_caja' => $total_efectivo_estimado_caja,

				'Total_ingresos_delivery' => $total_ingresos_delivery,
				'Total_ingresos_comanda' => $total_ingresos_comanda,
				'Total_ingresos_caja' => $total_ingresos_caja, 
				'Total_egresos_caja' => $total_egresos_caja,);

		echo json_encode($Datos);
		
	}

//// JORNADA	| DATOS DE LA JORNADA PARA LA CABECERA
	public function obtener_datos_jornada_actual()
    {

		/// ONTENER EL ID DE UNA JORNADA
			$this->load->model('Restaurant_model');
			$datos_jornada = $this->Restaurant_model->datos_jornada();
			
  
            echo json_encode($datos_jornada);         

	}

//// CLIENTES 	| OBTENER LISTADO
	public function obtener_listado_de_clientes()
	{
		//Esto siempre va es para instanciar la base de datos
		$CI =& get_instance();
		$CI->load->database();
		$token = @$CI->db->token;

		//$Id = $_GET["Id"];

		$this->db->select('*');
		$this->db->from('tbl_clientes');
		
		$this->db->order_by("Nombre", "asc");
		$this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));
		//$this->db->where('tbl_delibery.Id', $Id);

		$query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
	}
	

//// CLIENTES	|  CARGAR O EDITAR
	public function crear_cliente()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$this->datosObtenidos = json_decode(file_get_contents('php://input'));

		/* $Id = NULL;
		if(isset($this->datosObtenidos->Data->Id))
        {
            $Id = $this->datosObtenidos->Data->Id;
		} */
		
		$data = array(
                        
					'Nombre' => 	$this->datosObtenidos->Datos->Nombre_cliente,
					'Telefono' => 	$this->datosObtenidos->Datos->Telefono,
					'Direccion' => 	$this->datosObtenidos->Datos->Direccion,
					'Ult_usuario_id' => 	$this->session->userdata('Id'),
					'Negocio_id' => $this->session->userdata('Negocio_id')
				);

        $this->load->model('Restaurant_model');
        $insert_id = $this->Restaurant_model->insertar($data, NULL, 'tbl_clientes');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
	}

//// CLIENTES 	|  VISTA 
	public function clientes()
	{
		if ( $this->session->userdata('Login') != true )
		{
			header("Location: ".base_url()."login"); /// enviar a pagina de error
		}
		else
		{	
			
			$this->load->view('clientes-listado');	
		}	
	}
//// CLIENTES 	| OBTENER DATOS DE UN CLIENTE POR ID
	public function datoCliente()
	{
		//Esto siempre va es para instanciar la base de datos
		$CI =& get_instance();
		$CI->load->database();
		$token = @$CI->db->token;

		
		/* $array_id = explode("-", $_GET["Id"]);
		$Id = $array_id[1]; */
		$Id = $_GET["Id"];
		$this->db->select('*');
		$this->db->from('tbl_clientes');
		$this->db->where('Id', $Id);
		$this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));

		$query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
	}
	////
	////
////////////////////// FIN DOCUMENTO
}
