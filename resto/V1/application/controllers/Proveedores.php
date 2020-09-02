<?php
defined('BASEPATH') or exit('No direct script access allowed');

class proveedores extends CI_Controller
{

//// PROVEEDORES    | VISTA | LISTADO
    public function index()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } 
        else 
        {
            $this->load->view('proveedores_listado');

            /// REVISAR --- estos permisos, aca y en toda la app, especialmente cuando arme el acceso gratuito

            /* if ($this->session->userdata('Rol_acceso') > 3 || $this->session->userdata('Id') == 7) 
            {
                $this->load->view('proveedores_listado');
            } 
            else 
            {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            } */
        }
    }

//// PROVEEDORES    | VISTA | DATOS
    public function datos()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {
            ////COMENZAR A FILTRAR Y REDIRECCIONAR SEGUN ROL Y PLAN CONTRATADO
            //if (plan_contratado() > 1) {}
            $this->load->view('proveedores_datos');
            /* if ($this->session->userdata('Rol_acceso') > 3 || $this->session->userdata('Id') == 7) 
            {
                $this->load->view('proveedores_datos');
                
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            } */

        }
    }

//// PROVEEDORES 	| OBTENER 
	public function obtener_proveedores()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        {  exit("No coinciden los token");  }
        
        $Rubro_id = $_GET["Rubro_id"];

		$this->db->select(' tbl_proveedores.*,
                            tbl_proveedores_rubros.Nombre_rubro');
        $this->db->from('tbl_proveedores');
        $this->db->join('tbl_proveedores_rubros', 'tbl_proveedores_rubros.Id = tbl_proveedores.Rubro_id', 'left');
        $this->db->where('tbl_proveedores.Visible', 1);
        $this->db->where("tbl_proveedores.Negocio_id", $this->session->userdata('Negocio_id'));

        if($Rubro_id > 0)
        {
            $this->db->where('tbl_proveedores.Rubro_id', $Rubro_id);
        }
        $this->db->order_by('tbl_proveedores.Nombre_proveedor', 'asc');
        $query = $this->db->get();
        $array_proveedores = $query->result_array();
        
        
        $Datos_listado = array();

        /// BUSCANDO COMPRAS DE CADA PROVEEDOR
        foreach ($array_proveedores as $proveedor) 
        {
            // COMPRAS
            $this->db->select(' Id,
                                No_gravado,
                                IVA,
                                Neto');
            $this->db->from('tbl_compras');

            $this->db->where('Visible',1);
            $this->db->where('Proveedor_id', $proveedor["Id"]);

            $query = $this->db->get();
            $array_compras_proveedor = $query->result_array();

            $Total_pagado = 0;
            $Total_compras = 0;

            
            /// BUSCANDO PAGOS EN CADA COMPRA
            foreach ($array_compras_proveedor as $compra) 
            {
                //// CALCULANDO COMPRAS TOTALES
                $Total_compras = $Total_compras + $compra["Neto"] + $compra["No_gravado"] + $compra["IVA"];
                
                
                ///// FINANZAS
                    $this->db->select('Monto_bruto');
                    $this->db->from('tbl_finanzas_movimientos');
                    $this->db->where('Origen_movimiento', 'Compras');
                    $this->db->where('Fila_movimiento', $compra["Id"]);
                    $this->db->where('Visible', 1);

                    $query = $this->db->get();
                    $result_efectivo = $query->result_array();
                    $cant_efectivo = $query->num_rows();
                    
                    //// BUSCANDO PAGOS
                    if($cant_efectivo > 0)
                    {
                        foreach ($result_efectivo as $monto) 
                        {
                            $Total_pagado = $Total_pagado + $monto["Monto_bruto"];
                        }
                    }
            }

            $Saldo_proveedor = $Total_pagado - $Total_compras;
            
            $datos_proveedor = array('Datos_proveedor' => $proveedor, 'Saldo' => $Saldo_proveedor, 'Total_compras' => $Total_compras, 'Total_pagos' => $Total_pagado);
            
            array_push($Datos_listado, $datos_proveedor);
        }
            

		echo json_encode($Datos_listado);
		
    }

//// PROVEEDORES 	| OBTENER PARA USAR EN SELECT
	public function obtener_proveedores_select()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        {  exit("No coinciden los token");  }

		$this->db->select('Id, Nombre_proveedor');
        $this->db->from('tbl_proveedores');
        $this->db->where("tbl_proveedores.Negocio_id", $this->session->userdata('Negocio_id'));
        
        $this->db->order_by('Nombre_proveedor', 'asc');
        $query = $this->db->get();
        $array_proveedores = $query->result_array();
            

		echo json_encode($array_proveedores);
		
    }
    
//// PROVEEDORES 	| OBTENER 
    public function obtener_datos_proveedor()
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

        $this->db->select('*');
        $this->db->from('tbl_proveedores');
        $this->db->where('Id', $Id);

        $this->db->order_by("Nombre_proveedor", "asc");
        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);

    }

//// PROVEEDORES 	| SUBIR FOTO 
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
					$insert_id = $this->App_model->insertar($data, $Id, 'tbl_proveedores');
					
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

//// PROVEEDORES 	| CARGAR O EDITAR
	public function cargar_proveedor()
    {
        $CI =& get_instance();
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
                        
					'Nombre_proveedor' => 			$this->datosObtenidos->Datos->Nombre_proveedor,
					'Producto_servicio' => 			$this->datosObtenidos->Datos->Producto_servicio,
                    'Direccion' => 				    $this->datosObtenidos->Datos->Direccion,
                    'Rubro_id' => 				    $this->datosObtenidos->Datos->Rubro_id,
                    'CUIT_CUIL' => 			        $this->datosObtenidos->Datos->CUIT_CUIL,
                    'Localidad' => 			        $this->datosObtenidos->Datos->Localidad,
                    'Provincia' => 		            $this->datosObtenidos->Datos->Provincia,
                    'Pais' => 			            $this->datosObtenidos->Datos->Pais,
                    'Telefono' => 			        $this->datosObtenidos->Datos->Telefono,
                    'Telefono_fijo' => 			    $this->datosObtenidos->Datos->Telefono_fijo,
					'Email' => 	                    $this->datosObtenidos->Datos->Email,
                    'Web' => 			            $this->datosObtenidos->Datos->Web,
                    'URL_facebook' => 			    $this->datosObtenidos->Datos->URL_facebook,
					'Nombre_persona_contacto' => 	$this->datosObtenidos->Datos->Nombre_persona_contacto,
					'Datos_persona_contacto' => 	$this->datosObtenidos->Datos->Datos_persona_contacto,
					'Mas_datos_proveedor' => 		$this->datosObtenidos->Datos->Mas_datos_proveedor,
                    'Visible' => 		            1,
                    'Negocio_id' => $this->session->userdata('Negocio_id'),
                    
				);

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_proveedores');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
    }

//// PROVEEDORES 	| DESACTIVAR 
	public function desactivar_proveedor()
    {
        $CI =& get_instance();
		$CI->load->database();
		
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) {
            exit("No coinciden los token");
        }
        
        $Id = $this->datosObtenidos->Id;

		$data = array(
                        
                'Visible' => 0,
  
				);

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_proveedores');
                
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

        $this->db->select(' tbl_proveedores_seguimiento.*,
                            tbl_proveedores.Nombre_proveedor,
                            tbl_usuarios.Nombre');
        
        $this->db->from('tbl_proveedores_seguimiento');
        $this->db->join('tbl_proveedores', 'tbl_proveedores.Id = tbl_proveedores_seguimiento.Id_proveedor', 'left');
        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_proveedores_seguimiento.Usuarios_id', 'left');

        $this->db->where('tbl_proveedores_seguimiento.Id_proveedor', $Id);
        $this->db->where('tbl_proveedores_seguimiento.Visible', 1);

        $this->db->order_by('tbl_proveedores_seguimiento.Id', 'desc');
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
        if ($this->datosObtenidos->token != $token) {
            exit("No coinciden los token");
        }

        $Id = null;
        if(isset($this->datosObtenidos->Datos->Id))
        {
            $Id = $this->datosObtenidos->Datos->Id;
		}

        $data = array(

            'Id_proveedor' =>   $this->datosObtenidos->Id_proveedor,
            'Fecha' =>          $this->datosObtenidos->Datos->Fecha,
            'Descripcion' =>    $this->datosObtenidos->Datos->Descripcion,
            'Usuarios_id' =>    $this->session->userdata('Id'),
            'Visible' =>        1,
            'Negocio_id' => $this->session->userdata('Negocio_id'),

        );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_proveedores_seguimiento');

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
					$insert_id = $this->App_model->insertar($data, $Id, 'tbl_proveedores_seguimiento');
					
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
    

//// PROVEEDORES 	| OBTENER PRODUCTOS QUE OFRECE
	public function obtener_productos()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

        $Id = $_GET["Id"];

        $this->db->select(' tbl_stock.Id as Stock_id,
                            tbl_stock.Nombre_item,
                            tbl_stock.Imagen,
                            tbl_stock_vinculo_proveedor.Descripcion');
        
        $this->db->from('tbl_stock_vinculo_proveedor');
        
        $this->db->join('tbl_stock', 'tbl_stock.Id = tbl_stock_vinculo_proveedor.Stock_id', 'left');

        $this->db->where('tbl_stock_vinculo_proveedor.Visible', 1);
        $this->db->where('tbl_stock_vinculo_proveedor.Proveedor_id', $Id);
        $this->db->where("tbl_stock_vinculo_proveedor.Negocio_id", $this->session->userdata('Negocio_id'));

        $this->db->order_by('tbl_stock.Nombre_item', 'asc');
        
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
    }

////// ----------------------------------- RUBRO

//// RUBROS 	| OBTENER 
	public function obtener_rubros()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

		$this->db->select('*');
        $this->db->from('tbl_proveedores_rubros');
        $this->db->where("tbl_proveedores_rubros.Negocio_id", $this->session->userdata('Negocio_id'));
        
		$this->db->order_by("Nombre_rubro", "asc");
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
    }

//// RUBROS 	| CARGAR O EDITAR
    public function cargar_rubro()
    {
        $CI =& get_instance();
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
                        
                    'Nombre_rubro' => 			$this->datosObtenidos->Datos->Nombre_rubro,
                    'Descripcion' => 			$this->datosObtenidos->Datos->Descripcion,
                    'Negocio_id' => $this->session->userdata('Negocio_id'),
                    
                );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_proveedores_rubros');
                
        if ($insert_id >=0 ) 
        {   
            echo json_encode(array("Id" => $insert_id));         
        } 
        else 
        {
            echo json_encode(array("Id" => 0));
        }
    }

////// ----------------------------------- FUNCIONES PARA ASIGNAR PROVEEDOR
    
//// VINCULO PROVEEDOR | lista de proveedores no asignados 
    public function obtener_listado_de_categorias_no_asignadas()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

        $Proveedor_id = $_GET["Id"];

        /// CREANDO LISTADO DE CATEGORIAS STOCK VINCULADAS
            $this->db->select('	tbl_stock_categorias.Id');
                                
            $this->db->from('tbl_proveedor_vinculo_categorias_producto');

            $this->db->join('tbl_stock_categorias', 'tbl_stock_categorias.Id = tbl_proveedor_vinculo_categorias_producto.Categoria_id', 'left');

            $this->db->where('tbl_proveedor_vinculo_categorias_producto.Visible', 1);
            $this->db->where('tbl_proveedor_vinculo_categorias_producto.Proveedor_id', $Proveedor_id);
            $this->db->where("tbl_proveedor_vinculo_categorias_producto.Negocio_id", $this->session->userdata('Negocio_id'));

            $query = $this->db->get();
            $arrayCategoriasStockVinculados = $query->result_array();

        /// Listado completo de CATEGORIAS STOCK
            $this->db->select('Id, Nombre_categoria');
            $this->db->from('tbl_stock_categorias');
            $this->db->order_by("Nombre_categoria", "asc");
            $query = $this->db->get();
            $arrayCategoriasStock = $query->result_array();


        //// ELIMINANDO LAS CATEGORIAS ASIGNADAS DEL LISTADO COMPLETO
            foreach ($arrayCategoriasStockVinculados as $categoriaVinculada) // voy vinculado por vinculado
            {
                foreach ($arrayCategoriasStock as $categoria=>$valorCategoria) /// voy categoria por categoria $usuario=>$uservalue
                {
                    if ($categoriaVinculada['Id'] == $valorCategoria['Id']) /// comparo el Id del categoria vinculado, con el categoria que loopea en el momento
                    {
                        unset($arrayCategoriasStock[$categoria]); /// tengo q pasar el Id a eliminar
                    }
                }
            }

        echo json_encode($arrayCategoriasStock);

    }


//// VINCULO PROVEEDOR | lista de categorias de producto asignados
    public function obtener_listado_de_categorias_asignadas()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

        $Proveedor_id = $_GET["Id"];

        $this->db->select('	tbl_proveedor_vinculo_categorias_producto.Id,
                            tbl_proveedor_vinculo_categorias_producto.Descripcion,
                            tbl_stock_categorias.Nombre_categoria,
                            tbl_stock_categorias.Id as Categoria_id');

        $this->db->from('tbl_proveedor_vinculo_categorias_producto'); 

        $this->db->join('tbl_stock_categorias', 'tbl_stock_categorias.Id = tbl_proveedor_vinculo_categorias_producto.Categoria_id', 'left');

        $this->db->where('tbl_proveedor_vinculo_categorias_producto.Visible', 1);
        $this->db->where('tbl_proveedor_vinculo_categorias_producto.Proveedor_id', $Proveedor_id);
        $this->db->where("tbl_proveedor_vinculo_categorias_producto.Negocio_id", $this->session->userdata('Negocio_id'));

        $this->db->order_by("tbl_stock_categorias.Nombre_categoria", "asc");
        
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
    }

//// VINCULO PROVEEDOR | Vincular proveedor a categoria de producto
    public function Vincular_categoria_productos_proveedor()
    {

        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();

        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) {
            exit("No coinciden los token");
        }

        /// si existe pero no se veia, es posible que estuviera borrado, armar función que vuelva a 1 el campo VISIBLE

        $Proveedor_id       = $this->datosObtenidos->Proveedor_id;
        $Categoria_id       = $this->datosObtenidos->Categoria_id;

        $data = array(
                    'Proveedor_id'  => $Proveedor_id,
                    'Categoria_id'      => $Categoria_id,
                    'Visible'       => 1,
                    'Negocio_id' => $this->session->userdata('Negocio_id'),
        );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, null, 'tbl_proveedor_vinculo_categorias_producto');

                
        if ($insert_id >=0 ) 
        {
            echo json_encode(array("Id" => $insert_id));
        } 
        else 
        {
            echo json_encode(array("Id" => "failed"));
        }
    }

//// VINCULO PROVEEDOR | desvincular proveedor a producto
    public function Desvincular_producto_proveedor()
    {

        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) {
            exit("No coinciden los token");
        }

        
        $Id = $this->datosObtenidos->Id;

        $Proveedor_id = $this->datosObtenidos->Proveedor_id;
        $Stock_id = $this->datosObtenidos->Stock_id;

        $data = array(
                    
            'Visible' => 0,
        );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_proveedor_vinculo_categorias_producto');

                
        if ($insert_id >=0 ) 
        {
            echo json_encode(array("Id_Vinc" => $insert_id));
        } 
        else 
        {
            echo json_encode(array("Id_Vinc" => "failed"));
        }
    }
    
    
//// VINCULO PROVEEDOR | ACTUALIZAR COMENTARIO
	public function actualizarComentario()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) {
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
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_proveedor_vinculo_categorias_producto');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
    }

//// PRODUCTOS | lista de productos que ofrece este proveedor
    public function obtener_productos_proveedor()
    {
            
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) {
            exit("No coinciden los token");
        }

        $Proveedor_id = $this->datosObtenidos->Proveedor_id;

        $this->db->select('	tbl_stock_categorias.Nombre_categoria,
                            tbl_stock_categorias.Id as Categoria_id');

        $this->db->from('tbl_proveedor_vinculo_categorias_producto'); 

        $this->db->join('tbl_stock_categorias', 'tbl_stock_categorias.Id = tbl_proveedor_vinculo_categorias_producto.Categoria_id', 'left');

        $this->db->where('tbl_proveedor_vinculo_categorias_producto.Visible', 1);
        $this->db->where('tbl_proveedor_vinculo_categorias_producto.Proveedor_id', $Proveedor_id);
        
        $this->db->where("tbl_proveedor_vinculo_categorias_producto.Negocio_id", $this->session->userdata('Negocio_id'));

        $this->db->order_by("tbl_stock_categorias.Nombre_categoria", "asc");
        
        $query = $this->db->get();
        $array_categorias = $query->result_array();

        $Datos = array();
        foreach ($array_categorias as $categoria) 
        {
            // OBTENER PRODUCTOS PERTENECIENTES A UNA CATEGORÍA
            $Categoria_id = $categoria["Categoria_id"];

            $this->db->select(' Id, 
                                Nombre_item,
                                Precio_costo');
            $this->db->from('tbl_stock');
            $this->db->where('Visible', 1);
            $this->db->where('Categoria_id', $Categoria_id);
            $this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));

            $this->db->order_by("Nombre_item", "asc");
            $query = $this->db->get();
            $array_productos = $query->result_array();

            /// UN LOOP QUE VALLA METIENDO UNO A UNO CADA PRODUCTO ENCONTRADO EN OTRO ARRAY

            foreach ($array_productos as $producto) 
            {
                array_push($Datos, $producto);
            }

        }
        
        echo json_encode($Datos);
        
    }
    
///// fin documento
}
