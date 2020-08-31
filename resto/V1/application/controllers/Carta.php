<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Carta extends CI_Controller
{

//// CARTA | VISTA | LISTADO
    public function index()
    {
        if ($this->session->userdata('Login') != true) 
        {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } 
        else 
        {   
            if ($this->session->userdata('Rol_acceso') > 0) // visible para todos
            {
                $this->load->view('itemscarta');
            } 
            else 
            {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }

//// CARTA | VISTA | DATOS
    public function datos()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } 
        else 
        {
            $this->load->view('itemcarta_datos');
            ////COMENZAR A FILTRAR Y REDIRECCIONAR SEGUN ROL Y PLAN CONTRATADO
            /* if ($this->session->userdata('Rol_acceso') > 0)  // visible para todos
            {
                $this->load->view('itemscarta_datos');
                
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            } */

        }
    }



    
//// CARTA | OBTENER Datos del item
    public function obtener_datos_id()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        $token = @$CI->db->token;

        $Id = $_GET["Id"];

        $this->db->select('	tbl_stock.*,
                            tbl_stock_categorias.Nombre_categoria');

        $this->db->from('tbl_stock');

        $this->db->join('tbl_stock_categorias', 'tbl_stock_categorias.Id = tbl_stock.Categoria_id', 'left');

        $this->db->where('tbl_stock.Visible', 1);

        $this->db->where('tbl_stock.Id', $Id);


        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);

    }


//// CARTA | DESACTIVAR 
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
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_stock');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
    }

//// CARTA| OBTENER Listado archivos
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

        $this->db->select(' tbl_itemcarta_archivos.*,
                            tbl_usuarios.Nombre');
        $this->db->from('tbl_itemcarta_archivos');
        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_itemcarta_archivos.Usuario_id', 'left');
        
        $this->db->where('tbl_itemcarta_archivos.ItemCarta_id', $Id);
        $this->db->order_by("tbl_itemcarta_archivos.Nombre_archivo", "desc");
        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);
    }

//// CARTA| CARGAR ACTUALIZAR
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

            'ItemCarta_id'      => $this->datosObtenidos->ItemCarta_id,
            'Nombre_archivo'    =>  $this->datosObtenidos->Datos->Nombre_archivo,
            'Descripcion'       =>  $this->datosObtenidos->Datos->Descripcion,
            'Usuario_id'        => $this->session->userdata('Id'),
            'Negocio_id'        => $this->session->userdata('Negocio_id'),
        );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_itemcarta_archivos');

        if ($insert_id >= 0) // SI SE CARGO BIEN DEBE ACTUALIZAR LA TABLA tbl_stock, con el calculod de stock actual y el Id de la última actualización
        {
            echo json_encode(array("Id" => $insert_id));
        } 
        else 
        {
            echo json_encode(array("Id" => 0));
        }
    }

//// CARTA| OBTENER ULTIMOS archivos REGISTRADOS --- SIRVE PARA EL DASHBOARD
    public function obtener_ultimos_archivos()
    {

        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        $token = @$CI->db->token;

        //$Id = $_GET["Id"];
        $limit = 25;
        $start = 0;
        
        $this->db->select(  'tbl_itemcarta_archivos.*,
                            tbl_usuarios.Nombre,
                            tbl_stock.Nombre_item');
        $this->db->from('tbl_itemcarta_archivos');
        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_itemcarta_archivos.Usuario_id', 'left');
        $this->db->join('tbl_stock', 'tbl_stock.Id = tbl_itemcarta_archivos.ItemCarta_id', 'left');
        
        //$this->db->where('Stock_id', $Id);
        $this->db->order_by("tbl_itemcarta_archivos.Id", "desc");
        $this->db->limit($limit, $start);

        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);

    }

//// CARTA | ACTUALIZAR DESCRIPCIÓN
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
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_itemcarta_archivos');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
    }


//// CARTA | SUBIR FOTO
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
					$insert_id = $this->App_model->insertar($data, $Id, 'tbl_stock');
					
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
		$this->db->from('tbl_stock_categorias');
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
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_stock_categorias');

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
			$config['allowed_types'] = 'jpg|jpeg|doc|docx|xlsx|pdf|dwg';
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
					$insert_id = $this->App_model->insertar($data, $Id, 'tbl_itemcarta_archivos');
					
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


//// CARTA | OBTENER VENTAS DE UN PRODUCTO
    public function obtener_ventas_producto()
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

        //// BUSCAR EN TBL-VENTAS-PRODUCTOS, TODOS LAS FILAS DONDE APAREZCA EL PRODUCTO EN CUESTIÓN
            /// Adicionalmente debe agrupar los resultados por venta_id
                /// al final debo tener un array con las ventas donde aparecen
            
            $ItemCarta_id = $_GET["ItemCarta_id"];

            $this->db->select(' tbl_ventas.Id,
                                tbl_ventas.Identificador_venta,
                                tbl_clientes.Nombre_cliente,
                                tbl_usuarios.Nombre as Nombre_vendedor,
                                tbl_ventas.Fecha_venta,
                                tbl_ventas.Fecha_finalizada');

            $this->db->from('tbl_ventas_productos');

            $this->db->join('tbl_ventas', 'tbl_ventas.Id = tbl_ventas_productos.Venta_id','left');
            $this->db->join('tbl_clientes', 'tbl_clientes.Id = tbl_ventas.Cliente_id','left');
            $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_ventas.Vendedor_id','left');

            $this->db->where('tbl_ventas.Visible', 1);
            $this->db->where('tbl_ventas_productos.ItemCarta_id', $ItemCarta_id);

            $this->db->group_by('tbl_ventas_productos.Venta_id');
            
            $query = $this->db->get();
            $array_ventas_vinculadas = $query->result_array();
        
        //// EMPIEZO A RECORRER ESTE ARRAY, BUSCANDO INFORMACIÓN DE LA VENTA, 
            ///dentro de este array, debo generar también una consulta para saber la CANTIDAD DE VECES QUE APARECE ESTE PRODUCTO EN ESA VENTA

            $Datos_ventas = array();

            foreach ($array_ventas_vinculadas as $venta) 
            {
                // CONSULTANDO CANTIDAD DEL PRODUCTO VENDIDO
                $this->db->select('Id');
                $this->db->from('tbl_ventas_productos');
                    //$this->db->where("DATE_FORMAT(fecha,'%Y-%m')", $Mes);
                $this->db->where('ItemCarta_id', $ItemCarta_id);
                $this->db->where('Venta_id', $venta["Id"]);
                $query = $this->db->get();
                $cant_vendida = $query->num_rows();
                
                $datos = array('Datos_venta' => $venta, 'Cantidad_vendida_producto' => $cant_vendida);

                array_push($Datos_ventas, $datos);
            }

        echo json_encode($Datos_ventas);
        
    }

//// CARTA | CARGAR O EDITAR UN INSUMO
	public function cargar_insumo_producto()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

		if(isset($this->datosObtenidos->Datos->Id))
        {
            $Id = $this->datosObtenidos->Datos->Id;
        }
        
        $ItemCarta_id = $_GET["ItemCarta_id"];
		
		$data = array(
                        
					'ItemCarta_id' =>       $ItemCarta_id,
					'Stock_id' => 	        $this->datosObtenidos->Datos->Stock_id,
					'Cantidad' => 		    $this->datosObtenidos->Datos->Cantidad,
                    'Observaciones' => 		$this->datosObtenidos->Datos->Observaciones,
                    'Negocio_id' => $this->session->userdata('Negocio_id'),
                     
                );
                /// 'Ultimo_editor_id' => 		$this->session->userdata('Id')

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_itemcarta_insumos_producto');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
    }

//// CARTA | OBTENER LISTADO DE INSUMOS VINCULADOS
    public function obtener_listado_insumos()
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
        $ItemCarta_id = $_GET["ItemCarta_id"];

        $this->db->select('	tbl_itemcarta_insumos_producto.*,
                            tbl_stock.Nombre_item');

        $this->db->from('tbl_itemcarta_insumos_producto');

        $this->db->join('tbl_stock', 'tbl_stock.Id = tbl_itemcarta_insumos_producto.Stock_id','left');

        $this->db->where('tbl_itemcarta_insumos_producto.Visible', 1);
        $this->db->where('tbl_itemcarta_insumos_producto.ItemCarta_id', $ItemCarta_id);

        $this->db->order_by("tbl_stock.Nombre_item", "asc");

        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);
        
    }

//// VENTAS        | OBTENER LISTADO DE INSUMOS VINCULADOS A LA VENTA
    public function obtener_listado_insumos_venta()
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

        $Venta_id = $_GET["Id"];
        $listadoItems_desordenados = array();

        ///BUSCAR TODOS LOS PRODUCTOS PEDIDOS EN ESTA VENTA
        
            $this->db->select('	ItemCarta_id,
                                Cantidad');

            $this->db->from('tbl_ventas_productos');
            
            $this->db->where('Venta_id', $Venta_id);
            $this->db->where('Visible', 1);

            $query = $this->db->get();
            $array_productos_totales = $query->result_array();      // TODOS LOS PRODUCTOS DE LA VENTA

            /// BUSCAR POR CADA PRODUCTO SUS INSUMOS

                foreach ($array_productos_totales as $producto) 
                {
                    $this->db->select('	tbl_itemcarta_insumos_producto.Cantidad,
                                        tbl_stock.Nombre_item,
                                        tbl_stock.Id,
                                        tbl_stock.Unidad_medida');

                    $this->db->from('tbl_itemcarta_insumos_producto');
                    $this->db->join('tbl_stock', 'tbl_stock.Id = tbl_itemcarta_insumos_producto.Stock_id','left');
                        
                    $this->db->where('tbl_itemcarta_insumos_producto.ItemCarta_id', $producto["ItemCarta_id"]);
                    $this->db->where('tbl_itemcarta_insumos_producto.Visible', 1);

                    $query = $this->db->get();
                    $array_insumos = $query->result_array();      // TODOS LOS PRODUCTOS DE LA VENTA

                    foreach ($array_insumos as $insumo) 
                    {
                        $cantidad_insumo = $insumo["Cantidad"] * $producto["Cantidad"];
                        
                        $datos_producto = array(    
                            'Insumo_id'   => $insumo["Id"],
                            'Nombre_insumo'   => $insumo["Nombre_item"],
                            'Cantidad'      => $cantidad_insumo,
                            'Unidad_medida'   => $insumo["Unidad_medida"],
                            
                        );

                        array_push($listadoItems_desordenados, $datos_producto);
                    }
                }

            //// AGRUPANDO ITEMS --

                $listadoItems_agrupados = array();

                foreach ($listadoItems_desordenados as $item) 
                {
                    $hacer_push = true;

                    for ($i=0; $i < count($listadoItems_agrupados); $i++) 
                    { 
                        // compruebo si algun elemento del array agrupados concide con el array desordenados
                            // si alguno coincide hago que se sume la cantidad
                            if($item["Insumo_id"] == $listadoItems_agrupados[$i]["Insumo_id"])
                            {
                                $listadoItems_agrupados[$i]["Cantidad"] = $listadoItems_agrupados[$i]["Cantidad"] + $item["Cantidad"];

                                $hacer_push = false;
                            }
                    }

                    ///
                    if ($hacer_push) 
                    {
                        array_push($listadoItems_agrupados, $item);
                    }
                }


             
        //echo json_encode(array('Desagrupados' => $listadoItems_desordenados, 'Agrupados' => $listadoItems_agrupados));
        echo json_encode($listadoItems_agrupados);
    }

//// PRODUCTOS VENDIDOS 	| ANULAR PRODUCTO
    public function reasignar_producto()
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
            'Venta_id' =>   $this->datosObtenidos->Datos->Venta_id,
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
                    'Descripcion' =>   'Producto añadido desde stock de reserva: '.$this->datosObtenidos->Datos->Nombre_producto,
                    'Usuario_id' =>    $this->session->userdata('Id'),
                    'Visible' =>       1,
                    'Negocio_id' => $this->session->userdata('Negocio_id'),
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

///// fin documento
}
