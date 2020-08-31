<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ventas extends CI_Controller
{

//// VENTAS       | VISTA | LISTADO
    public function index()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {

            if ($this->session->userdata('Rol_acceso') > 1) {
                $this->load->view('ventas_listado');
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }

//// VENTAS       | VISTA | DATOS
    public function datos()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {
            ////COMENZAR A FILTRAR Y REDIRECCIONAR SEGUN ROL Y PLAN CONTRATADO
            //if (plan_contratado() > 1) {}

            if ($this->session->userdata('Rol_acceso') > 1) 
            {
                //Esto siempre va es para instanciar la base de datos
                    $CI = &get_instance();
                    $CI->load->database();

                    $Id = $_GET["Id"];

                    $this->db->select(' tbl_ventas.*,
                                                tbl_vendedor.Nombre as Nombre_vendedor,
                                                tbl_resp_1.Nombre as Nombre_resp_1,
                                                tbl_resp_2.Nombre as Nombre_resp_2,
                                                tbl_empresas.Nombre_empresa,
                                                tbl_clientes.Nombre_cliente');

                    $this->db->from('tbl_ventas');

                    $this->db->join('tbl_clientes', 'tbl_clientes.Id   = tbl_ventas.Cliente_id', 'left');
                    $this->db->join('tbl_usuarios as tbl_vendedor', 'tbl_vendedor.Id   = tbl_ventas.Vendedor_id', 'left');
                    $this->db->join('tbl_empresas', 'tbl_empresas.Id   = tbl_ventas.Empresa_id', 'left');
                    $this->db->join('tbl_usuarios as tbl_resp_1', 'tbl_resp_1.Id   = tbl_ventas.Vendedor_id', 'left');
                    $this->db->join('tbl_usuarios as tbl_resp_2', 'tbl_resp_2.Id   = tbl_ventas.Vendedor_id', 'left');

                    $this->db->where('tbl_ventas.Id', $Id);
                    $this->db->where('tbl_ventas.Visible', 1);
                    

                    $query = $this->db->get();
                    $result = $query->result_array();

                
                $Datos = array('Datos_venta' => $result[0]);
                
                $this->load->view('ventas_datos', $Datos );
                
            } 
            else 
            {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }

//// VENTAS       | VISTA | PRODUCCION
    public function produccion()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {

            if ($this->session->userdata('Rol_acceso') > 1) 
            {
                $this->load->view('ventas_produccion');
            } 
            else 
            {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }

//// VENTAS 	  | OBTENER TODAS
	public function obtener_listado_ventas()
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
        $Empresa_id = $_GET["Empresa_id"];
        $Vendedor_id  = $_GET["Vendedor_id"];
        $Cliente_id  = $_GET["Cliente_id"]; 

        $this->db->select(' tbl_ventas.*,
                            tbl_usuarios.Nombre as Nombre_vendedor,
                            tbl_empresas.Nombre_empresa,
                            tbl_clientes.Nombre_cliente');
        
        $this->db->from('tbl_ventas');

        $this->db->join('tbl_clientes',   'tbl_clientes.Id    = tbl_ventas.Cliente_id', 'left');
        $this->db->join('tbl_usuarios',   'tbl_usuarios.Id    = tbl_ventas.Vendedor_id', 'left');
        $this->db->join('tbl_empresas',   'tbl_empresas.Id    = tbl_ventas.Empresa_id', 'left');

        if($Vendedor_id > 0)    { $this->db->where('tbl_ventas.Vendedor_id', $Vendedor_id); }
        if($Cliente_id > 0)     { $this->db->where('tbl_ventas.Cliente_id', $Cliente_id); }
        if($Empresa_id > 0)     { $this->db->where('tbl_ventas.Empresa_id', $Empresa_id); }

        $this->db->where('tbl_ventas.Visible', 1);
        
        if($_GET["Estado"] == 10) /// esto es porque si necesito la lista completa sin discriminar por estado, Mando un valor 4 al estado
        {
            $this->db->where('tbl_ventas.Estado', 10);
        }
        else {
            $this->db->where('tbl_ventas.Estado <', 10);
        }
        
		$this->db->order_by('tbl_ventas.Fecha_estimada_entrega', 'asc');
        
        $query = $this->db->get();
		$array_ventas = $query->result_array();

        $Datos = array();
        //// Condicional para saber setear el origen del movimiento
            
        foreach ($array_ventas as $ventas) 
        {   
            $Total_cobrado = 0;
            $Total_productos_precios = 0;
            $Total_productos_precios = $Total_productos_precios + $ventas["Valor_logistica"] + $ventas["Valor_instalacion"]; // sumo aca logistica e instalacion

            $Total_productos_cantidad = 0;
            $Origen_movimiento  = 'Ventas';
            $Fila_movimiento    = $ventas["Id"];
            
            /// MONTO DE LA VENTA
                /// PRODUCTOS PROPIOS
                $this->db->select(' Cantidad,
                                    Precio_venta_producto,');


                                    ///// METERLE UN JOIN PARA SACAR EL PRECIO DEL FLETE INSTALACIÓN Y SUMARLO A LA CUENTA
                $this->db->from('tbl_ventas_productos');
                $this->db->where('Venta_id', $ventas["Id"]);
                $this->db->where('Visible', 1);

                $query = $this->db->get();
                $array_productos_vendidos = $query->result_array();

                foreach ($array_productos_vendidos as $producto) 
                {
                    $Total_productos_precios = $Total_productos_precios + $producto["Precio_venta_producto"];
                    $Total_productos_cantidad = $Total_productos_cantidad + $producto["Cantidad"];
                }

                /// PRODUCTOS REVENTA
                $this->db->select(' tbl_stock_movimientos.Cantidad,
                                    tbl_stock.Precio');
            
                $this->db->from('tbl_stock_movimientos');
                
                $this->db->join('tbl_stock', 'tbl_stock.Id = tbl_stock_movimientos.Stock_id', 'left');
                
                $this->db->where('tbl_stock_movimientos.Tipo_movimiento', 2); // Tipo movimiento 2, es salida de stock
                $this->db->where('tbl_stock_movimientos.Proceso_id', $ventas["Id"]);

                $query = $this->db->get();
                $array_productos_reventa_vendidos = $query->result_array();

                foreach ($array_productos_reventa_vendidos as $producto) 
                {
                    $Total_productos_precios = $Total_productos_precios + $producto["Precio"];
                    $Total_productos_cantidad = $Total_productos_cantidad + $producto["Cantidad"];
                }


            
            ///// MONTOS COBRADOS 
                //// CALCULANDO PAGOS EN EFECTIVO
                    $this->db->select('*');
                    $this->db->from('tbl_dinero_efectivo');
                    $this->db->where('Origen_movimiento', $Origen_movimiento);
                    $this->db->where('Fila_movimiento', $Fila_movimiento);
                    $this->db->where('Visible', 1);
                    $query = $this->db->get();
                    $result = $query->result_array();
                    
                    /////  SUMAR MONTOS
                    foreach ($result as $movimiento) 
                    {
                        $Total_cobrado = $Total_cobrado + $movimiento["Monto"];
                    }

                //// CALCULANDO PAGOS EN TRANFERENCIAS
                    $this->db->select('*');
                    $this->db->from('tbl_dinero_transferencias');
                    $this->db->where('Origen_movimiento', $Origen_movimiento);
                    $this->db->where('Fila_movimiento', $Fila_movimiento);
                    $this->db->where('Visible', 1);
                    
                    $query = $this->db->get();
                    $result = $query->result_array();
                    
                    /////  SUMAR MONTOS
                    foreach ($result as $movimiento) 
                    {
                        $Total_cobrado = $Total_cobrado + $movimiento["Monto_bruto"];
                        //$Total_cobrado = $Total_cobrado + $movimiento["Monto_bruto"] - $movimiento["Retencion_ing_brutos"];
                    }

                //// CALCULANDO PAGOS EN CHEQUES
                    $this->db->select(' tbl_dinero_cheques.*,
                                        tbl_cheques.Monto');
                    $this->db->from('tbl_dinero_cheques');
                    $this->db->join('tbl_cheques', 'tbl_cheques.Id = tbl_dinero_cheques.Cheque_id', 'left');
                    $this->db->where('tbl_dinero_cheques.Origen_movimiento', $Origen_movimiento);
                    $this->db->where('tbl_dinero_cheques.Fila_movimiento', $Fila_movimiento);
                    $this->db->where('tbl_dinero_cheques.Visible', 1);
                    
                    $query = $this->db->get();
                    $result = $query->result_array();

                    /////  SUMAR MONTOS
                    foreach ($result as $cheque) 
                    {
                        $Total_cobrado = $Total_cobrado + $cheque["Monto"];
                    }

            
            $Datos_venta = array(   "Datos"=> $ventas, 
                                    "Total_cobrado" => $Total_cobrado,
                                    "Total_productos_precios" => $Total_productos_precios,
                                    "Total_productos_cantidad" => $Total_productos_cantidad,
                                    
                                );

            array_push($Datos, $Datos_venta);
       
        }   

        echo json_encode($Datos);
		
    }

//// VENTAS 	    | CARGAR O EDITAR 
    public function cargar_venta()
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

        ///// ANALIZAR ACA SI PONGO DIRECTAMENTE EL ID A MANO O LE BUSCO OTRA MANERA, POR EL MOMENTO A MANO VA A FUNCIONAR BIEN, SI ALGUN DIA TIENEN MAS DE UN RESPONSABLE, LO TENDRAN QUE ELEGIR DE UN LISTADO
        
        $Responsable_id_planif_inicial = $this->datosObtenidos->Data->Responsable_id_planif_inicial;
        $Responsable_id_planif_final = $this->datosObtenidos->Data->Responsable_id_planif_final;
        $Responsable_id_logistica = $this->datosObtenidos->Data->Responsable_id_logistica;
        $Responsable_id_instalacion = $this->datosObtenidos->Data->Responsable_id_instalacion;
        $Responsable_id_cobranza = $this->datosObtenidos->Data->Responsable_id_cobranza;
        
        
        $data = array(
                        
                    'Identificador_venta' => 	    $this->datosObtenidos->Data->Identificador_venta,
                    'Periodo_id' => 			    $this->datosObtenidos->Data->Periodo_id,
                    'Cliente_id' => 			    $this->datosObtenidos->Data->Cliente_id,
                    "Empresa_id" =>                 $this->datosObtenidos->Data->Empresa_id,
                    'Vendedor_id' => 			    $this->datosObtenidos->Data->Vendedor_id,
                    'Fecha_venta' => 	            $this->datosObtenidos->Data->Fecha_venta,
                    'Fecha_estimada_entrega' =>     $this->datosObtenidos->Data->Fecha_estimada_entrega,
                    
                    'Responsable_id_planif_inicial' => 	$Responsable_id_planif_inicial,
                    'Responsable_id_planif_final' => 	$Responsable_id_planif_final,
                    'Responsable_id_logistica' => 	    $Responsable_id_logistica,
                    'Responsable_id_instalacion' => 	$Responsable_id_instalacion,
                    'Responsable_id_cobranza' => 	    $Responsable_id_cobranza,

                    'Info_logistica' => 	        $this->datosObtenidos->Data->Info_logistica,
                    'Info_instalaciones' => 	    $this->datosObtenidos->Data->Info_instalaciones,
                    'Info_cobranza' => 	            $this->datosObtenidos->Data->Info_cobranza,

                    'Valor_logistica' => 	        $this->datosObtenidos->Data->Valor_logistica,
                    'Valor_instalacion' => 	        $this->datosObtenidos->Data->Valor_instalacion,

                    'Prioritario' =>                $this->datosObtenidos->Data->Prioritario,
                    'Observaciones_venta'=>         $this->datosObtenidos->Data->Observaciones_venta,
                    'Usuario_id' => 	            $this->session->userdata('Id'),
                    
                );

                $this->load->model('App_model');
                $insert_id = $this->App_model->insertar($data, $Id, 'tbl_ventas');
                        
                //// si cargo bien esto, toma el id de la orden y la carga en la tbl_ordentrabajo_seguimietno
                if ($insert_id >=0 ) 
                {   
                    
                    if($Id == null) {   $descripcion_seguimiento = "Inicio de fabricación";    }
                    else {   $descripcion_seguimiento = "Se actualizaron los datos en la ficha de esta venta.";
        }
            
            $data = array(

            'Venta_id' =>       $insert_id,
            'Descripcion' =>    $descripcion_seguimiento,
            'Usuario_id' =>    $this->session->userdata('Id'),
            'Visible' =>        1
            );

            $this->load->model('App_model');
            $insert_id_seguimiento = $this->App_model->insertar($data, null, 'tbl_ventas_seguimiento');
                
            echo json_encode(array("Id" => $insert_id, "Seguimiento_id" => $insert_id_seguimiento));         
        } 
        else 
        {
            echo json_encode(array("Id" => 0));
        }
    }

//// VENTAS 	    | AVANZAR ESTADO
    public function cambiar_estado_venta()
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

        ///// ANALIZAR ACA SI PONGO DIRECTAMENTE EL ID A MANO O LE BUSCO OTRA MANERA, POR EL MOMENTO A MANO VA A FUNCIONAR BIEN, SI ALGUN DIA TIENEN MAS DE UN RESPONSABLE, LO TENDRAN QUE ELEGIR DE UN LISTADO
        
        $estado = $this->datosObtenidos->Datos->Estado + 1;    
        
        if($estado == 6) // Estado 7 es el último estado de mis procesos
        {
            $Fecha_finalizada = date("Y-m-d");
        }
        else { $Fecha_finalizada = null; }
        
        $data = array(
                        
                        'Fecha_finalizada'  => $Fecha_finalizada,            
                        'Estado'            => $estado,                
                );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_ventas');
                
        //// si cargo bien esto, toma el id de la orden y la carga en la tbl_ordentrabajo_seguimietno
        if ($insert_id >=0 ) 
        {   
            /// ACTUALIZANDO SEGUIMIENTOS
            
                if($estado == 2)  {  $descripcion_seguimiento = "Avanzó el lote a la estación de Proceso de Materiales."; $Categoria = 2; }
                if($estado == 3)  {  $descripcion_seguimiento = "Avanzó el lote a la estación de Soldadura."; $Categoria = 2; }
                if($estado == 4)  {  $descripcion_seguimiento = "Avanzó el lote a la estación de Pintura."; $Categoria = 2; }
                if($estado == 5)  {  $descripcion_seguimiento = "Avanzó el lote a la estación de Rotulación."; $Categoria = 2; }
                if($estado == 6)  {  $descripcion_seguimiento = "Avanzó el lote a la estación de Empaque."; $Categoria = 2; }
                if($estado == 7)  {  $descripcion_seguimiento = "Finalizó el proceso de producción de este lote. Continúa en Logística."; $Categoria = 2; }
                if($estado == 8)  {  $descripcion_seguimiento = "Logística finalizada.";  $Categoria = 3;}
                if($estado == 9)  {  $descripcion_seguimiento = "Instalación finalizada.";  $Categoria = 4;}
                if($estado == 10) {  $descripcion_seguimiento = "Cobranza finalizada. Los insumos vinculados a esta producción fueron descontados correctamente del stock.";  $Categoria = 5;}

                
                $data = array(

                'Venta_id' =>       $insert_id,
                'Categoria_seguimiento' =>  $Categoria,
                'Descripcion' =>    $descripcion_seguimiento,
                'Usuario_id' =>    $this->session->userdata('Id'),
                'Visible' =>        1
                );

                $this->load->model('App_model');
                $insert_id_seguimiento = $this->App_model->insertar($data, null, 'tbl_ventas_seguimiento');

            echo json_encode(array( 
                                    "Id" => $insert_id, 
                                    "Seguimiento_id" => $insert_id_seguimiento
                                ));         
        } 
        else 
        {
            echo json_encode(array("Id" => 0));
        }
    }
    
//// VENTAS 	    | OBTENER DATOS DE UNA VENTA
    public function obtener_datos_venta()
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

        $this->db->select(' tbl_ventas.*,
                            tbl_empresas.Nombre_empresa,
                            tbl_clientes.Nombre_cliente,
                            tbl_clientes.CUIT_CUIL,
                            tbl_clientes.Imagen,
                            tbl_clientes.Direccion,
                            tbl_clientes.Provincia,
                            tbl_clientes.Telefono,
                            tbl_clientes.Email,
                            tbl_clientes.Id as Cliente_id,
                            tbl_vendedor.Nombre as Nombre_vendedor,
                            tbl_resp_1.Nombre as Nombre_resp_1,
                            tbl_resp_2.Nombre as Nombre_resp_2,
                            tbl_logistica.Nombre as Nombre_logistica,
                            tbl_instalacion.Nombre as Nombre_instalacion,
                            tbl_cobranza.Nombre as Nombre_cobranza');
        
        $this->db->from('tbl_ventas');

        $this->db->join('tbl_clientes',  'tbl_clientes.Id   = tbl_ventas.Cliente_id', 'left');
        $this->db->join('tbl_empresas',  'tbl_empresas.Id   = tbl_ventas.Empresa_id', 'left');
        $this->db->join('tbl_usuarios as tbl_vendedor',  'tbl_vendedor.Id   = tbl_ventas.Vendedor_id', 'left');
        $this->db->join('tbl_usuarios as tbl_resp_1',  'tbl_resp_1.Id   = tbl_ventas.Responsable_id_planif_inicial', 'left');
        $this->db->join('tbl_usuarios as tbl_resp_2', 'tbl_resp_2.Id   = tbl_ventas.Responsable_id_planif_final', 'left');
        $this->db->join('tbl_usuarios as tbl_logistica', 'tbl_logistica.Id   = tbl_ventas.Responsable_id_logistica', 'left');
        $this->db->join('tbl_usuarios as tbl_instalacion', 'tbl_instalacion.Id   = tbl_ventas.Responsable_id_instalacion', 'left');
        $this->db->join('tbl_usuarios as tbl_cobranza', 'tbl_cobranza.Id   = tbl_ventas.Responsable_id_cobranza', 'left');


        $this->db->where('tbl_ventas.Id', $Id);
        $this->db->where('tbl_ventas.Visible', 1);
        
        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);

    }


//// VENTAS 	    | DESACTIVAR / ELIMINAR
	public function desactivarVenta()
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
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_ventas');
                
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

        $this->db->select(' tbl_ventas_seguimiento.*,
                            tbl_usuarios.Nombre');
        
        $this->db->from('tbl_ventas_seguimiento');
        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_ventas_seguimiento.Usuario_id', 'left');

        if($_GET["Categoria_seguimiento"] != '0')
        {
            $this->db->where('tbl_ventas_seguimiento.Categoria_seguimiento', $_GET["Categoria_seguimiento"]);
        }

        $this->db->where('tbl_ventas_seguimiento.Venta_id', $Id);
        $this->db->where('tbl_ventas_seguimiento.Visible', 1);

        $this->db->order_by('tbl_ventas_seguimiento.Id', 'desc');
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

            'Venta_id'              => $this->datosObtenidos->Venta_id,
            'Categoria_seguimiento' => $this->datosObtenidos->Categoria_seguimiento,
            'Descripcion'           => $this->datosObtenidos->Datos->Descripcion,
            'Usuario_id'            => $this->session->userdata('Id'),
            'Visible' =>            1

        );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_ventas_seguimiento');

        if ($insert_id >= 0) {
            echo json_encode(array("Id" => $insert_id));
        } else {
            echo json_encode(array("Id" => 0));
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
                    $insert_id = $this->App_model->insertar($data, $Id, 'tbl_ventas_seguimiento');
                    
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

//// VENTAS 	    | LISTADO DASHBOARD
	public function obtener_listado_ventas_dashboard()
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

        $this->db->select(' tbl_ventas.*,
                            tbl_usuarios.Nombre as Nombre_vendedor,
                            tbl_empresas.Nombre_empresa,
                            tbl_clientes.Nombre_cliente');
        
        $this->db->from('tbl_ventas');

        $this->db->join('tbl_clientes',   'tbl_clientes.Id    = tbl_ventas.Cliente_id', 'left');
        $this->db->join('tbl_usuarios',   'tbl_usuarios.Id    = tbl_ventas.Vendedor_id', 'left');
        $this->db->join('tbl_empresas',   'tbl_empresas.Id    = tbl_ventas.Empresa_id', 'left');
        
        $this->db->where('tbl_ventas.Visible', 1);
        $this->db->where('tbl_ventas.Estado <', 10); /// EL ESTADO 10 ES LA VENTA QUE YA ESTA COBRADA Y FINALIZADA
        
        $this->db->order_by('tbl_ventas.Fecha_estimada_entrega', 'asc');
        
        $query = $this->db->get();
		$array_ventas = $query->result_array();

        $Total = 0;
        $Origen_movimiento  = 'Ventas';
        
        
        $Datos = array();
        //// Condicional para saber setear el origen del movimiento
            
        foreach ($array_ventas as $ventas) 
        {
            
            $Fila_movimiento    = $ventas["Id"]; 
            
            //// CALCULANDO PAGOS EN EFECTIVO
                $this->db->select('*');
                $this->db->from('tbl_dinero_efectivo');
                $this->db->where('Origen_movimiento', $Origen_movimiento);
                $this->db->where('Fila_movimiento', $Fila_movimiento);
                $this->db->where('Visible', 1);
                $query = $this->db->get();
                $result = $query->result_array();
                
                /////  SUMAR MONTOS
                foreach ($result as $cheque) 
                {
                    $Total = $Total + $cheque["Monto"];
                }

            //// CALCULANDO PAGOS EN TRANFERENCIAS
                $this->db->select('*');
                $this->db->from('tbl_dinero_transferencias');
                $this->db->where('Origen_movimiento', $Origen_movimiento);
                $this->db->where('Fila_movimiento', $Fila_movimiento);
                $this->db->where('Visible', 1);
                
                $query = $this->db->get();
                $result = $query->result_array();
                
                /////  SUMAR MONTOS
                foreach ($result as $movimiento) 
                {
                    $Total = $Total + $movimiento["Monto_bruto"];
                    //$Total = $Total + $movimiento["Monto_bruto"] - $movimiento["Retencion_ing_brutos"];
                }

            //// CALCULANDO PAGOS EN CHEQUES
                $this->db->select(' tbl_dinero_cheques.*,
                                    tbl_dinero_cheques.Id as Movimiento_id,
                                    tbl_cheques.*,
                                    tbl_cheques.Id as Cheque_id');

                $this->db->from('tbl_dinero_cheques');

                $this->db->join('tbl_cheques', 'tbl_cheques.Id = tbl_dinero_cheques.Cheque_id', 'left');
                $this->db->where('tbl_dinero_cheques.Origen_movimiento', $Origen_movimiento);
                $this->db->where('tbl_dinero_cheques.Fila_movimiento', $Fila_movimiento);
                $this->db->where('tbl_dinero_cheques.Visible', 1);
                
                $query = $this->db->get();
                $result = $query->result_array();

                /////  SUMAR MONTOS
                foreach ($result as $cheque) 
                {
                    $Total = $Total + $cheque["Monto"];
                }

                $Datos_venta = array("Datos"=> $ventas, "Total" => $Total);

                array_push($Datos, $Datos_venta);
       
        }   

        echo json_encode($Datos);
		
    }


//// CONSULTAR LAS VENTAS DEL AÑO
    public function obtener_cantVentas()
    {
        $Anio = date('Y');

        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        
        //Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) 
        {
            exit("No coinciden los token");
        }

        $this->db->select('Id');
        $this->db->from('tbl_ventas');
        $this->db->where("DATE_FORMAT(Fecha_venta,'%Y')", $Anio);
        
        $query = $this->db->get();
        $total = $query->num_rows();

        echo json_encode($total);

    }

//// VENTAS 	    | OBTENER 
    public function obtener_productos_usados()
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

        /// consultar en la tabla de tbl_ventas_productos, todos los productos que este vinculados a la Venta_id

        



        $this->db->select(' tbl_ventas.*,
                            tbl_vendedor.Nombre as Nombre_vendedor,
                            tbl_resp_1.Nombre as Nombre_resp_1,
                            tbl_resp_2.Nombre as Nombre_resp_2,
                            tbl_empresas.Nombre_empresa,
                            tbl_clientes.Nombre_cliente');
        
        $this->db->from('tbl_ventas');

        $this->db->join('tbl_clientes',  'tbl_clientes.Id   = tbl_ventas.Cliente_id', 'left');
        $this->db->join('tbl_usuarios as tbl_vendedor',  'tbl_vendedor.Id   = tbl_ventas.Vendedor_id', 'left');
        $this->db->join('tbl_empresas',  'tbl_empresas.Id   = tbl_ventas.Empresa_id', 'left');
        $this->db->join('tbl_usuarios as tbl_resp_1',  'tbl_resp_1.Id   = tbl_ventas.Vendedor_id', 'left');
        $this->db->join('tbl_usuarios as tbl_resp_2', 'tbl_resp_2.Id   = tbl_ventas.Vendedor_id', 'left');


        $this->db->where('tbl_ventas.Id', $Id);
        $this->db->where('tbl_ventas.Visible', 1);
        
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);

    }

//// PRODUCTOS VENDIDOS 	| CARGAR O EDITAR
    public function agregarProducto()
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

        $Venta_id = $_GET["Id"];

        $Cantidad = null;
        if (isset($this->datosObtenidos->Datos->Cantidad)) {
            $Cantidad = $this->datosObtenidos->Datos->Cantidad;
        }

        $Tipo_produccion = 1;
        if (isset($this->datosObtenidos->Datos->Tipo_produccion)) {
            $Tipo_produccion = $this->datosObtenidos->Datos->Tipo_produccion;
        }

        $data = array(

            'Venta_id' =>               $Venta_id,
            'Producto_id' =>            $this->datosObtenidos->Datos->Producto_id,
            'Cantidad' =>               $Cantidad,
            'Precio_venta_producto' =>  $this->datosObtenidos->Datos->Precio_venta_producto,
            'Tipo_produccion' =>        $Tipo_produccion,
            'Observaciones' =>          $this->datosObtenidos->Datos->Observaciones,
            'S_1_Requerimientos' =>     $this->datosObtenidos->Datos->S_1_Requerimientos,
            'S_2_Requerimientos' =>     $this->datosObtenidos->Datos->S_2_Requerimientos,
            'S_3_Requerimientos' =>     $this->datosObtenidos->Datos->S_3_Requerimientos,
            'S_4_Requerimientos' =>     $this->datosObtenidos->Datos->S_4_Requerimientos,
            'S_5_Requerimientos' =>     $this->datosObtenidos->Datos->S_5_Requerimientos,
            'S_6_Requerimientos' =>     $this->datosObtenidos->Datos->S_6_Requerimientos,
            'S_7_Requerimientos' =>     $this->datosObtenidos->Datos->S_7_Requerimientos,
            'Usuario_id' =>             $this->session->userdata('Id'),
            

        );

        $this->load->model('App_model');
        /// ESTE MODO, ES MAS SIMPLE... AL CARGAR UN PRODUCTO PONE UNA CANTIDAD Y SE GESTIONAN SUS AVANCES EN CONJUNTO, Y NO UNO POR UNO 
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_ventas_productos');
        
        /* 
        ESTE MODO... SIRVE PARA QUE UN PRODUCTO SE CARGUE MUCHAS VECES. DEPENDIENDO DE LA CANTIDAD

        if($Cantidad != null)
        {
            for ($i=0; $i < $Cantidad; $i++) 
            {  
                $insert_id = $this->App_model->insertar($data, $Id, 'tbl_ventas_productos');
            }
        }
        else
        {
            $insert_id = $this->App_model->insertar($data, $Id, 'tbl_ventas_productos');
        } */
        
            
        if ($insert_id >= 0) {
            echo json_encode(array("Id" => $insert_id));
        } else {
            echo json_encode(array("Id" => 0));
        }
    }
    

//// PRODUCTOS VENDIDOS 	| Obtener listado
    public function obtener_listado_de_productos_vendidos()
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

        $this->db->select(' tbl_ventas_productos.*,
                            tbl_fabricacion.Nombre_producto,
                            tbl_fabricacion.Imagen,
                            tbl_fabricacion.Precio_USD,
                            tbl_fabricacion.Codigo_interno');
        
        $this->db->from('tbl_ventas_productos');

        $this->db->join('tbl_fabricacion',  'tbl_fabricacion.Id   = tbl_ventas_productos.Producto_id', 'left');
        
        $this->db->where('tbl_ventas_productos.Venta_id', $Id);
        $this->db->where('tbl_ventas_productos.Visible', 1);
        
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);

    }

//// PRODUCTOS VENDIDOS 	| CAMBIAR ESTADO PRODUCTO
    public function cambiarEstadoProducto()
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
        if (isset($this->datosObtenidos->Datos->Producto_id)) {
            $Id = $this->datosObtenidos->Datos->Producto_id;
        }

        //// seteando en que lugar va a cargar los datos
            if($this->datosObtenidos->Datos->Estado == 2)
            {
                $data = array(
                    'S_1_Fecha_finalizado' =>   $this->datosObtenidos->Datos->Fecha,
                    'S_1_Observaciones' =>      $this->datosObtenidos->Datos->Comentarios,
                    'Estado' =>                 $this->datosObtenidos->Datos->Estado,
                );
            }
            elseif($this->datosObtenidos->Datos->Estado == 3)
            {
                $data = array(
                    'S_2_Fecha_finalizado' =>   $this->datosObtenidos->Datos->Fecha,
                    'S_2_Observaciones' =>      $this->datosObtenidos->Datos->Comentarios,
                    'Estado' =>                 $this->datosObtenidos->Datos->Estado,
                );
            }
            elseif($this->datosObtenidos->Datos->Estado == 4)
            {
                $data = array(
                    'S_3_Fecha_finalizado' =>   $this->datosObtenidos->Datos->Fecha,
                    'S_3_Observaciones' =>      $this->datosObtenidos->Datos->Comentarios,
                    'Estado' =>                 $this->datosObtenidos->Datos->Estado,
                );
            }
            elseif($this->datosObtenidos->Datos->Estado == 5)
            {
                $data = array(
                    'S_4_Fecha_finalizado' =>   $this->datosObtenidos->Datos->Fecha,
                    'S_4_Observaciones' =>      $this->datosObtenidos->Datos->Comentarios,
                    'Estado' =>                 $this->datosObtenidos->Datos->Estado,
                );
            }        
        

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_ventas_productos');

        if ($insert_id >= 0) {
            echo json_encode(array("Id" => $insert_id));
        } else {
            echo json_encode(array("Id" => 0));
        }
    }

//// PRODUCTOS VENDIDOS 	| ANULAR PRODUCTO
    public function anular_producto()
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
            'Venta_id' =>   1,
        );
        

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_ventas_productos');

        if ($insert_id >= 0) 
        {
           //// si cargo bien esto, toma el id de la orden y la carga en la tbl_ordentrabajo_seguimietno
            if ($insert_id >=0 ) 
            {   
                
                $data = array(

                    'Venta_id' =>      $this->datosObtenidos->Datos->Venta_id,
                    'Categoria_seguimiento' =>  2,
                    'Descripcion' =>   'Producto Anulado: '.$this->datosObtenidos->Datos->Nombre_producto .' - '.$this->datosObtenidos->Datos->Comentarios_anulacion,
                    'Usuario_id' =>    $this->session->userdata('Id'),
                    'Visible' =>       1
                );

                $this->load->model('App_model');
                $insert_id_seguimiento = $this->App_model->insertar($data, null, 'tbl_ventas_seguimiento');
                    
                echo json_encode(array("Id" => $insert_id, "Seguimiento_id" => $insert_id_seguimiento));
            }
        } 
        else 
        {
            echo json_encode(array("Id" => 0));
        }
    }

//// VENTAS 	    | DESACTIVAR / ELIMINAR
    public function eliminar_producto()
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
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_ventas_productos');
                
        if ($insert_id >=0 ) 
        {   
            echo json_encode(array("Id" => $insert_id));         
        } 
        else 
        {
            echo json_encode(array("Id" => 0));
        }
    }


//// PRODUCCION 	| Obtener listados
    public function obtener_productos_a_fabricar()
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

        $Estado = $this->datosObtenidos->Estado;

        $this->db->select(' tbl_ventas_productos.*,
                            tbl_fabricacion.Nombre_producto,
                            tbl_fabricacion.Imagen,
                            tbl_ventas.Identificador_venta,
                            tbl_ventas.Fecha_venta,
                            tbl_ventas.Responsable_id_planif_inicial,
                            tbl_ventas.Responsable_id_planif_final,
                            tbl_ventas.Fecha_estimada_entrega,
                            tbl_clientes.Nombre_cliente'); 
        
        $this->db->from('tbl_ventas_productos');  

        $this->db->join('tbl_fabricacion',  'tbl_fabricacion.Id   = tbl_ventas_productos.Producto_id', 'left');
        $this->db->join('tbl_ventas',  'tbl_ventas.Id   = tbl_ventas_productos.Venta_id', 'left');
        $this->db->join('tbl_clientes',  'tbl_clientes.Id   = tbl_ventas.Cliente_id', 'left');

        $this->db->where('tbl_ventas_productos.Estado <', $Estado);
        $this->db->where('tbl_ventas_productos.Visible', 1);
        
        /// ACA VIENE EL FILTRO, SOLO VA A BUSCAR EN LAS VENTAS DONDE EL USUARIO LOGUEADO HAYA SIDO ELEGIDO.
        if ($this->session->userdata('Rol_acceso') < 4)
        {
            $this->db->group_start(); // Open bracket
                $this->db->where('tbl_ventas.Responsable_id_planif_final',      $this->session->userdata('Id'));
                $this->db->or_where('tbl_ventas.Responsable_id_planif_inicial', $this->session->userdata('Id'));
            $this->db->group_end(); // Close bracket    
        }

        $this->db->order_by('tbl_ventas.Fecha_estimada_entrega', 'asc');
        
        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);

    }

//// COBRANZAS 	    | Obtener listado resumen productos
    public function obtener_listado_resumen_productos()
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

        /// ARMO UN ARRAY CON LOS PRODUCTOS VENDITOS, AGRUPANDOLOS
            $this->db->select(' tbl_ventas_productos.*,
                                tbl_fabricacion.Nombre_producto,
                                tbl_fabricacion.Codigo_interno,
                                tbl_fabricacion.Imagen,
                                tbl_fabricacion.Id as Producto_id,
                                tbl_fabricacion.Precio_USD');
            
            $this->db->from('tbl_ventas_productos');

            $this->db->join('tbl_fabricacion',  'tbl_fabricacion.Id   = tbl_ventas_productos.Producto_id', 'left');
            
            $this->db->where('tbl_ventas_productos.Venta_id', $Id);
            $this->db->where('tbl_ventas_productos.Visible', 1);
            $this->db->group_by('tbl_ventas_productos.Producto_id'); 
            
            $query = $this->db->get();
            $array_productos = $query->result_array();
        
        /// RECORRO EL ARRAY CONTANDO CUANTOS SON DE CADA UNO
            $Datos = array();
            
            foreach ($array_productos as $productos) 
            {
                
                $this->db->select('Id');
                $this->db->from('tbl_ventas_productos');
                $this->db->where('Producto_id', $productos["Producto_id"]);
                $this->db->where('Venta_id', $Id);
                $this->db->where('Visible', 1);

                /// con un poco mas de laburo puedo incluso traer un promedio de por donde van en su construcción
        
                $query = $this->db->get();
                $Cantidad_metodo_uno_por_uno = $query->num_rows();
                
                $subtotal = $Cantidad_metodo_uno_por_uno * $productos["Precio_venta_producto"];
                
                
                $datos_producto = array(
                                            'Codigo_interno' =>     $productos["Codigo_interno"],
                                            'Nombre_producto' =>    $productos["Nombre_producto"], 
                                            'Precio_venta' =>       $productos["Precio_venta_producto"],
                                            'Cantidad' =>           $productos["Cantidad"],
                                            'Cantidad_metodo_uno_por_uno' =>           $Cantidad_metodo_uno_por_uno,
                                            'Subtotal' =>           $subtotal,
                                        );

                array_push($Datos, $datos_producto);
            }

        echo json_encode($Datos);

    }


//// SEGUIMIENTOS 	| CARGAR O EDITAR
    public function descontarInsumosStock()
    {
        $CI = &get_instance();
        $CI->load->database();

        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

        //// DESCUENTO DE LA TABLA STOCK Y REPORTO LOS DESCUENTOS DE LA TABLA MOVIMIENTOS STOCK
            for ($i=0; $i < count($this->datosObtenidos->Datos_insumos); $i++) 
            { 
                /// RECORRO EL ARRAY. DANDOLE DE BAJA UNO POR UNO A CADA INSUMO
                    $Stock_id =         $this->datosObtenidos->Datos_insumos[$i]->Insumo_id;
                    $Cantidad =         $this->datosObtenidos->Datos_insumos[$i]->Cantidad;
                    $Descripcion =      "Venta: " . $this->datosObtenidos->Datos_venta->Identificador_venta;
                    $Proceso_id =       $this->datosObtenidos->Datos_venta->Id;                // Se refiere al Id, de la orden de trabajo, o de la Compra
                    $Tipo_movimiento =  2;      // Recibe un Número: 1 Equivale a compras, 2 a Ordenes de trabajo

                    $data = array(

                        'Stock_id'              => $Stock_id,
                        'Cantidad'              => $Cantidad,
                        'Descripcion'           => $Descripcion,
                        'Mostrar_reventas'      => 1,
                        'Usuario_id'            => $this->session->userdata('Id'),
                        'Proceso_id'            => $Proceso_id,
                        'Tipo_movimiento'       => 2,
                    );

                    $this->load->model('App_model');
                    $insert_id = $this->App_model->insertar($data, Null, 'tbl_stock_movimientos');

                    if ($insert_id >= 0) // SI SE CARGO BIEN DEBE ACTUALIZAR LA TABLA tbl_stock, con el calculo de stock actual y el Id de la última actualización
                    {
                        /// consultar stock en cuestión y obtener la cantidad hasta ese momento
                            $this->db->select('Cant_actual');
                            $this->db->from('tbl_stock');
                            $this->db->where('Id', $Stock_id);
                            $query = $this->db->get();
                            $result = $query->result_array();
                            
                            if ($query->num_rows() > 0) // si encontro alguna fila previa, hace el calculo
                            {
                                /// SEGUN EL TIPO DE MOVIMIENTO VA A SUMAR O RESTAR LA CANTIDAD INDICADA
                                if      ($Tipo_movimiento == 1)    {$cant_actual = $result[0]["Cant_actual"] + $Cantidad;} // suma cantidad
                                else if ($Tipo_movimiento == null) {$cant_actual = $result[0]["Cant_actual"] + $Cantidad;} // SI VIENE NULO, TOMA EL SIGNO QUE TRAE LA VARIABLE
                                else                                { $cant_actual = $result[0]["Cant_actual"] - $Cantidad;} // resta cantidad
                            }
                            else // de lo contrario la cantidad actual será la primera reportada
                            {
                                $cant_actual = $Cantidad;
                            }
                                //////----------------------////////
                                // Si cantidad actual es menor a stock ideal, debería poner Stock bajo en algun campo, para poder filtrar más facilmente. y hacer listas solo de productos bajos de stock
                                //////----------------------////////

                        /// Actualizo tbl_stock con los datos nuevos
                            $data = array(
                                'Ult_modificacion_id' => $insert_id,
                                'Cant_actual' => $cant_actual,
                            );

                            $this->load->model('App_model');
                            $insert_id_2 = $this->App_model->insertar($data, $Stock_id, 'tbl_stock');

                            if ($insert_id_2 >= 0) 
                            {
                                echo json_encode(array(
                                                        "Id" => $insert_id,
                                                        "Estado" => "Proceso realizado con exito, items modificados:" + count($this->datosObtenidos->Datos_insumos)));
                            } 
                            else 
                            {
                                echo json_encode(array("Id" => 0));
                            }
                    } 
                    else 
                    {
                        echo json_encode(array("Id" => 0));
                    }

                
            
            }

        
        //// ACTUALIZO AL INFO DE LA VENTA, para evitar que vuelva a aparecer el boton descontar stock
            $data = array(
                            
                'Stock_descontado' => 	 1,
                'Usuario_id' => 	     $this->session->userdata('Id'),
                
            );

            $this->load->model('App_model');
            $insert_id_venta = $this->App_model->insertar($data, $this->datosObtenidos->Datos_venta->Id, 'tbl_ventas');
                    
            //// si cargo bien esto, toma el id de la orden y la carga en la tbl_ordentrabajo_seguimietno
            if ($insert_id_venta >=0 ) 
            {   
                $descripcion_seguimiento = "Se realizó el descuento de los insumos de esta producción.";
            }
        
            $data = array(

            'Venta_id' =>       $insert_id_venta,
            'Descripcion' =>    $descripcion_seguimiento,
            'Usuario_id' =>    $this->session->userdata('Id'),
            'Visible' =>        1
            );

            $this->load->model('App_model');
            $insert_id_seguimiento = $this->App_model->insertar($data, null, 'tbl_ventas_seguimiento');
                
            echo json_encode(array("Id" => $insert_id_venta, "Seguimiento_id" => $insert_id_seguimiento));
        
    }

    
///// fin documento
}