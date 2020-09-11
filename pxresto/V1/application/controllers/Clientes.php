<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clientes extends CI_Controller 
{ 


//// CLIENTES | LISTADO
	public function index()
	{	
		if ( $this->session->userdata('Login') != true )
		{
			header("Location: ".base_url()."login"); /// enviar a pagina de error
		}
		else
		{	
            
            if ( $this->session->userdata('Rol_id') >= 4)
			{
				$this->load->view('clientes-listado');
			}
			else 
			{
				header("Location: ".base_url()."login"); /// enviar a pagina de error
			}	
			
		}
	}


//// CLIENTES | LISTADO
	public function datos()
	{	
		if ( $this->session->userdata('Login') != true )
		{
			header("Location: ".base_url()."login"); /// enviar a pagina de error
		}
		else
		{	
            $this->load->view('clientes_datos');
            ////COMENZAR A FILTRAR Y REDIRECCIONAR SEGUN ROL Y PLAN CONTRATADO
                //if (plan_contratado() > 1) {}
            
            /* if ( $this->session->userdata('Rol_id') >= 4)
			{
				if (plan_contratado() > 1) 	
				{ 
					$this->load->view('CLIENTES-datos'); 
				}
				else						
				{	
					$this->load->view('plan-medio'); 
				}
			}
			else 
			{
				header("Location: ".base_url()."login"); /// enviar a pagina de error
			} */	
			
		}
	}

//// CLIENTES | OBTENER CLIENTES BASICO
    public function obtener_listado_de_clientes()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        $token = @$CI->db->token;

        //$Id = $_GET["Id"];

        $this->db->select('Id, Nombre, Telefono, Direccion');
        $this->db->from('tbl_clientes');
        $this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));
        $this->db->order_by("Cant_compras", "desc");
        //$this->db->order_by("Nombre", "asc");
        //$this->db->where('tbl_delibery.Id', $Id);

        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);
    }

//// CLIENTES | OBTENER CLIENTES AVANZADO
    public function obtener_listado_de_clientes_avanzado()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        $token = @$CI->db->token;

        //$Id = $_GET["Id"];

        $this->db->select('*');
        $this->db->from('tbl_clientes');
        $this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));
        $this->db->order_by("Cant_compras", "desc");
        $this->db->order_by("Nombre", "asc");
        //$this->db->where('tbl_delibery.Id', $Id);

        $query = $this->db->get();
        $array_clientes = $query->result_array();

        $Datos = array();
        
        //// BUSCANDO COMPRAS Y PAGOS DE CADA CLIENTE. 
        foreach ($array_clientes as $cliente) 
        {
            $Total_pagado = 0;
            $Total_compras = 0;

            /// BUSCANDO COMANDAS
                $this->db->select(' Id, Valor_cuenta, Valor_descuento');
                $this->db->from('tbl_comandas');
                //$this->db->where('Visible',1);
                $this->db->where('Cliente_id', $cliente["Id"]);
                $query = $this->db->get();
                
                $array_comandas_cliente = $query->result_array();

                foreach ($array_comandas_cliente as $comanda)
                {
                    //// CALCULANDO comanda TOTALES
                    $Total_compras = $Total_compras + $comanda["Valor_cuenta"] - $comanda["Valor_descuento"];
                    
                    ///// FINANZAS
                        $this->db->select('Monto_bruto');
                        $this->db->from('tbl_finanzas_movimientos');
                        $this->db->where('Origen_movimiento', 'Comandas');
                        $this->db->where('Fila_movimiento', $comanda["Id"]);
                        $this->db->where('Visible', 1);

                        $query = $this->db->get();
                        $result_movimientos = $query->result_array();
                        $cant_movimientos = $query->num_rows();
                        
                        //// BUSCANDO PAGOS
                        if($cant_movimientos > 0)
                        {
                            foreach ($result_movimientos as $monto) 
                            {
                                $Total_pagado = $Total_pagado + $monto["Monto_bruto"];
                            }
                        }
                }


            /// BUSCANDO PAGOS EN CADA DELIVERY
                $this->db->select('Id, Valor_delivery, Valor_descuento, Valor_cuenta');
                $this->db->from('tbl_delibery');
                //$this->db->where('Visible',1);
                $this->db->where('Cliente_id', $cliente["Id"]);
                $query = $this->db->get();
                $array_delivery_cliente = $query->result_array();

                foreach ($array_delivery_cliente as $delivery)
                {
                    //// CALCULANDO delivery TOTALES
                    $Total_compras = $Total_compras + $delivery["Valor_delivery"] + $delivery["Valor_cuenta"] - $delivery["Valor_descuento"];
                    
                    ///// FINANZAS
                        $this->db->select('Monto_bruto');
                        $this->db->from('tbl_finanzas_movimientos');
                        $this->db->where('Origen_movimiento', 'Delivery');
                        $this->db->where('Fila_movimiento', $delivery["Id"]);
                        $this->db->where('Visible', 1);

                        $query = $this->db->get();
                        $result_movimientos = $query->result_array();
                        $cant_movimientos = $query->num_rows();
                        
                        //// BUSCANDO PAGOS
                        if($cant_movimientos > 0)
                        {
                            foreach ($result_movimientos as $monto) 
                            {
                                $Total_pagado = $Total_pagado + $monto["Monto_bruto"];
                            }
                        }
                }


            /// BUSCANDO PAGOS GENERICOS DE ESTE CLIENTE
                $this->db->select('*');

                $this->db->from('tbl_finanzas_movimientos');
                
                $this->db->where('tbl_finanzas_movimientos.Origen_movimiento', "Clientes");
                $this->db->where('tbl_finanzas_movimientos.Fila_movimiento', $cliente["Id"]);
                $this->db->where('tbl_finanzas_movimientos.Visible', 1);
                $this->db->where("tbl_finanzas_movimientos.Negocio_id", $this->session->userdata('Negocio_id'));
                
                $query = $this->db->get();
                $arrayMovimientos = $query->result_array();
                
                /////  SUMAR MONTOS
                $Total_mov_genericos = 0;
                foreach ($arrayMovimientos as $movimiento) 
                {
                    $Total_mov_genericos = $Total_mov_genericos + $movimiento["Monto_bruto"];
                }

            $Total_pagado = $Total_pagado + $Total_mov_genericos;

            /// GENERANDO CUENTAS
            $Saldo_cliente = $Total_pagado - $Total_compras;
            
            $datos_cliente = array('Datos_cliente' => $cliente, 'Saldo' => $Saldo_cliente, 'Total_compras' => $Total_compras, 'Total_pagos' => $Total_pagado);
            
            array_push($Datos, $datos_cliente);
        }
        
        echo json_encode($Datos);
    }


//// CLIENTES|  CARGAR O EDITAR
    public function crear_cliente()
    {
        $CI =& get_instance();
        $CI->load->database();
        
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        
        $Id = NULL; if(isset($this->datosObtenidos->Datos->Id)) { $Id = $this->datosObtenidos->Datos->Id; }

        $Telefono = NULL;               if(isset( $this->datosObtenidos->Datos->Telefono )) { $Telefono = $this->datosObtenidos->Datos->Telefono; }
        $Telefono_secundario = NULL;    if(isset( $this->datosObtenidos->Datos->Telefono_secundario )) { $Telefono_secundario = $this->datosObtenidos->Datos->Telefono_secundario; }
        $Email = NULL;                  if(isset( $this->datosObtenidos->Datos->Email )) { $Email = $this->datosObtenidos->Datos->Email; }
        $Direccion = NULL;              if(isset( $this->datosObtenidos->Datos->Direccion )) { $Direccion = $this->datosObtenidos->Datos->Direccion; }
        $Observaciones = NULL;          if(isset( $this->datosObtenidos->Datos->Observaciones )) { $Observaciones = $this->datosObtenidos->Datos->Observaciones; }
        
        $data = array(
                        
                    'Nombre' => 	$this->datosObtenidos->Datos->Nombre,
                    'Telefono' => 	$Telefono,
                    'Telefono_secundario' => 	$Telefono_secundario,
                    'Email' => 	$Email,
                    'Direccion' => 	$Direccion,
                    'Observaciones' => 	$Observaciones,
                    'Ult_usuario_id' => 	$this->session->userdata('Id'),
                    'Negocio_id' => $this->session->userdata('Negocio_id')
                );

        $this->load->model('Restaurant_model');
        $insert_id = $this->Restaurant_model->insertar($data, $Id, 'tbl_clientes');
                
        if ($insert_id >=0) 
        {   
            echo json_encode(array("Id" => $insert_id));         
        } 
        else 
        {
            echo json_encode(array("Id" => 0));
        }
    }


//// CLIENTES | OBTENER DATOS DE UN CLIENTE
    public function obtener_datos()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        $token = @$CI->db->token;

        $Cliente_id = $_GET["Id"];

        $this->db->select('*');
        $this->db->from('tbl_clientes');
        $this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));
        $this->db->where("Id", $Cliente_id);
        $this->db->order_by("Cant_compras", "desc");
        $this->db->order_by("Nombre", "asc");
        //$this->db->where('tbl_delibery.Id', $Id);

        $query = $this->db->get();
        $result = $query->result_array();

        $Datos = array();
        
        //// BUSCANDO COMPRAS Y PAGOS DE ESTE CLIENTE

            $Total_pagado = 0;
            $Total_compras_delivery = 0;
            $Total_compras_comandas = 0;

            /// BUSCANDO COMANDAS

            $this->db->select(' tbl_comandas.Id,
                                tbl_comandas.Fecha,
                                tbl_comandas.Cant_personas, 
                                tbl_comandas.Valor_cuenta, 
                                tbl_comandas.Valor_descuento,
                                tbl_usuarios.Nombre as Nombre_mozo');
            $this->db->from('tbl_comandas');
            $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_comandas.Moso_id', 'left');
            //$this->db->where('Visible',1);
            $this->db->where('tbl_comandas.Cliente_id', $Cliente_id);
            $this->db->order_by("tbl_comandas.Fecha", "desc");
            
            $query = $this->db->get();
            
            $array_comandas_cliente = $query->result_array();

            foreach ($array_comandas_cliente as $comanda)
            {
                //// CALCULANDO comanda TOTALES
                $Total_compras_comandas = $Total_compras_comandas + $comanda["Valor_cuenta"] - $comanda["Valor_descuento"];
                
                ///// FINANZAS
                    $this->db->select('Monto_bruto');
                    $this->db->from('tbl_finanzas_movimientos');
                    $this->db->where('Origen_movimiento', 'Comandas');
                    $this->db->where('Fila_movimiento', $comanda["Id"]);
                    $this->db->where('Visible', 1);

                    $query = $this->db->get();
                    $result_movimientos = $query->result_array();
                    $cant_movimientos = $query->num_rows();
                    
                    //// BUSCANDO PAGOS
                    if($cant_movimientos > 0)
                    {
                        foreach ($result_movimientos as $monto) 
                        {
                            $Total_pagado = $Total_pagado + $monto["Monto_bruto"];
                        }
                    }
            }


            /// BUSCANDO PAGOS EN CADA DELIVERY
            $this->db->select(' tbl_delibery.Id, 
                                tbl_delibery.Valor_cuenta,
                                tbl_delibery.Valor_delivery, 
                                tbl_delibery.Valor_descuento,
                                tbl_delibery.FechaHora_pedido,
                                tbl_usuarios.Nombre as Nombre_repartidor');
            $this->db->from('tbl_delibery');
            $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_delibery.Repartidor_id', 'left');
            //$this->db->where('Visible',1);
            $this->db->where('Cliente_id', $Cliente_id);
            $this->db->order_by("tbl_delibery.FechaHora_pedido", "desc");

            $query = $this->db->get();
            $array_delivery_cliente = $query->result_array();

            foreach ($array_delivery_cliente as $delivery)
            {
                //// CALCULANDO delivery TOTALES
                $Total_compras_delivery = $Total_compras_delivery + $delivery["Valor_cuenta"] + $delivery["Valor_delivery"] - $delivery["Valor_descuento"];
                
                ///// FINANZAS
                    $this->db->select('Monto_bruto');
                    $this->db->from('tbl_finanzas_movimientos');
                    $this->db->where('Origen_movimiento', 'Delivery');
                    $this->db->where('Fila_movimiento', $delivery["Id"]);
                    $this->db->where('Visible', 1);

                    $query = $this->db->get();
                    $result_movimientos = $query->result_array();
                    $cant_movimientos = $query->num_rows();
                    
                    //// BUSCANDO PAGOS
                    if($cant_movimientos > 0)
                    {
                        foreach ($result_movimientos as $monto) 
                        {
                            $Total_pagado = $Total_pagado + $monto["Monto_bruto"];
                        }
                    }
            }


            /// GENERANDO CUENTAS
            $Total_compras = $Total_compras_delivery + $Total_compras_comandas;
            $Saldo_cliente = $Total_pagado - $Total_compras;
            
            $Datos = array( 'Datos' => $result[0], 
                            'Saldo' => $Saldo_cliente, 
                            'Total_compras' => $Total_compras,
                            'Total_compras_delivery' => $Total_compras_delivery, 
                            'Total_compras_comandas' => $Total_compras_comandas, 
                            'Total_pagos' => $Total_pagado,
                            'Datos_comandas' => $array_comandas_cliente, 
                            'Datos_delivery' => $array_delivery_cliente);

            echo json_encode($Datos);
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

        $this->db->select(' tbl_clientes_seguimiento.*,
                            tbl_clientes.Nombre as Nombre_cliente,
                            tbl_usuarios.Nombre');
        
        $this->db->from('tbl_clientes_seguimiento');
        $this->db->join('tbl_clientes', 'tbl_clientes.Id = tbl_clientes_seguimiento.CLiente_id', 'left');
        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_clientes_seguimiento.Usuarios_id', 'left');

        $this->db->where('tbl_clientes_seguimiento.CLiente_id', $Id);
        $this->db->where('tbl_clientes_seguimiento.Visible', 1);

        $this->db->order_by('tbl_clientes_seguimiento.Id', 'desc');
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

            'CLiente_id' =>   $this->datosObtenidos->CLiente_id,
            'Fecha' =>          $this->datosObtenidos->Datos->Fecha,
            'Descripcion' =>    $this->datosObtenidos->Datos->Descripcion,
            'Usuarios_id' =>    $this->session->userdata('Id'),
            'Visible' =>        1,
            'Negocio_id' => $this->session->userdata('Negocio_id'),

        );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_clientes_seguimiento');

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
                    $insert_id = $this->App_model->insertar($data, $Id, 'tbl_clientes_seguimiento');
                    
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
	
//////////////////////
}