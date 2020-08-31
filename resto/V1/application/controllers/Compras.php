<?php
defined('BASEPATH') or exit('No direct script access allowed');

class compras extends CI_Controller
{

//// COMPRAS       | VISTA | LISTADO
    public function index()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {
            $this->load->view('compras_listado');
            /* if ($this->session->userdata('Rol_acceso') > 3) {
                $this->load->view('compras_listado');
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            } */

        }
    }

//// COMPRAS       | VISTA | DATOS
    public function datos()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {
            ////COMENZAR A FILTRAR Y REDIRECCIONAR SEGUN ROL Y PLAN CONTRATADO
            //if (plan_contratado() > 3) {}
            $this->load->view('compras_datos');
           /*  if ($this->session->userdata('Rol_acceso') > 3) 
            {
                $this->load->view('compras_datos');
                
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            } */

        }
    }

//// COMPRAS 	   | OBTENER listado todas
	public function obtener_compras()
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

        $Hoy = date("Y-m-d");

        //// BUSCANDO COMPRAS SALDADAS
            $this->db->select(' tbl_compras.*,
                                tbl_proveedores.Nombre_proveedor,
                                tbl_usuarios.Nombre,
                                tbl_proveedores_rubros.Nombre_rubro');
            $this->db->from('tbl_compras');
            
            $this->db->join('tbl_proveedores', 'tbl_proveedores.Id = tbl_compras.Proveedor_id', 'left');
            $this->db->join('tbl_proveedores_rubros', 'tbl_proveedores_rubros.Id = tbl_proveedores.Rubro_id', 'left');
            $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_compras.Usuario_id', 'left');

            if($this->datosObtenidos->Fecha_inicio != null)
            {
                $this->db->where("DATE_FORMAT(tbl_compras.Fecha_compra,'%Y-%m-%d') >=", $this->datosObtenidos->Fecha_inicio);
            }
            if($this->datosObtenidos->Fecha_fin != null)
            {
                $this->db->where("DATE_FORMAT(tbl_compras.Fecha_compra,'%Y-%m-%d') <=", $this->datosObtenidos->Fecha_fin);
            }
            /* if($this->datosObtenidos->Periodo_id != 0)
            {
                $this->db->where("tbl_compras.Periodo_id", $this->datosObtenidos->Periodo_id);
            } */
            if($this->datosObtenidos->Rubro_id != 0)
            {
                $this->db->where("tbl_proveedores.Rubro_id", $this->datosObtenidos->Rubro_id);
            }
            
            $this->db->where('tbl_compras.Visible', 1);
            $this->db->where('tbl_compras.Saldada', 1);
            $this->db->where("tbl_compras.Negocio_id", $this->session->userdata('Negocio_id'));

            $this->db->order_by("tbl_compras.Fecha_compra", "desc");
            
            $query = $this->db->get();
            $result_saldadas = $query->result_array();

        
        

        //// BUSCANDO COMPRAS NO SALDAS Y VENCIDAS
            $this->db->select(' tbl_compras.*,
                                tbl_proveedores.Nombre_proveedor,
                                tbl_usuarios.Nombre,
                                tbl_proveedores_rubros.Nombre_rubro');
            $this->db->from('tbl_compras');
            
            $this->db->join('tbl_proveedores', 'tbl_proveedores.Id = tbl_compras.Proveedor_id', 'left');
            $this->db->join('tbl_proveedores_rubros', 'tbl_proveedores_rubros.Id = tbl_proveedores.Rubro_id', 'left');
            $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_compras.Usuario_id', 'left');

            if($this->datosObtenidos->Fecha_inicio != null) 
            {
                $this->db->where("DATE_FORMAT(Fecha_compra,'%Y-%m-%d') >=", $this->datosObtenidos->Fecha_inicio);
            }
            if($this->datosObtenidos->Fecha_fin != null)
            {
                $this->db->where("DATE_FORMAT(Fecha_compra,'%Y-%m-%d') <=", $this->datosObtenidos->Fecha_fin);
            }
            /* if($this->datosObtenidos->Periodo_id != 0)
            {
                $this->db->where("Periodo_id", $this->datosObtenidos->Periodo_id);
            } */
            if($this->datosObtenidos->Rubro_id != 0)
            {
                $this->db->where("tbl_proveedores.Rubro_id", $this->datosObtenidos->Rubro_id);
            }
            
            $this->db->where("DATE_FORMAT(Fecha_vencimiento_pago,'%Y-%m-%d') <=", $Hoy); // FILTRANDO VENCIMIENTO
            $this->db->where('tbl_compras.Visible',1);
            $this->db->where('tbl_compras.Saldada',0);
            $this->db->where("tbl_compras.Negocio_id", $this->session->userdata('Negocio_id'));

            $this->db->order_by("tbl_compras.Fecha_vencimiento_pago", "desc");
            
            $query = $this->db->get();
            $result_vencidas = $query->result_array();

        /// OBTENER MONTOS DE DINERO DE COMPRAS VENCIDAS-----
            
            $Total_valor_compras_vencidas = 0;
            $Total_dinero_pagado_vencidas = 0;
            $Datos_vencidas = array();
            foreach ($result_vencidas as $compra) 
            {
                
                /////////// -------------------- OBTENER TOTAL DE DINERO TOTAL DEL COSTO DE LAS COMPRAS -----
                $Total_valor_compras_vencidas = $Total_valor_compras_vencidas + $compra["Neto"] + $compra["No_gravado"] + $compra["IVA"];
                ////
                $valor_compra = $compra["Neto"] + $compra["No_gravado"] + $compra["IVA"];
                
                
                /////////// -------------------- OBTENER TOTAL DE DINERO PAGADO EN ESTAS COMPRAS-----
                    $total_abonado = 0;
                /// CONSULTANDO MONTOS ABONADOS EN EFECTIVO
                    $this->db->select('Monto');
                    $this->db->from('tbl_dinero_efectivo');
                    $this->db->where('Origen_movimiento', 'Compras');
                    $this->db->where('Fila_movimiento', $compra["Id"]);
                    $this->db->where('Visible', 1);
                    
                    $query = $this->db->get();
                    $resultMontoEfectivo = $query->result_array();
                    
                    /////  SUMAR MONTOS
                    foreach ($resultMontoEfectivo as $montos) 
                    {
                        $Total_dinero_pagado_vencidas = $Total_dinero_pagado_vencidas + $montos["Monto"];
                        $total_abonado = $total_abonado + $montos["Monto"];
                    }
            
                ///MONTO ABONADO EN TRANSFERENCIA
                    $this->db->select('Monto_bruto, Retencion_ing_brutos');
                    $this->db->from('tbl_dinero_transferencias');
                    $this->db->where('Origen_movimiento', 'Compras');
                    $this->db->where('Fila_movimiento', $compra["Id"]);
                    $this->db->where('Visible', 1);
                    
                    $query = $this->db->get();
                    $resultMontosTransferencia = $query->result_array();
                
                    /////  SUMAR MONTOS
                    foreach ($resultMontosTransferencia as $montos) 
                    {
                        /* $Total_dinero_pagado_vencidas = $Total_dinero_pagado_vencidas + $montos["Monto_bruto"] - $montos["Retencion_ing_brutos"];
                        $total_abonado = $total_abonado + $montos["Monto_bruto"] - $montos["Retencion_ing_brutos"]; */
                        $Total_dinero_pagado_vencidas = $Total_dinero_pagado_vencidas + $montos["Monto_bruto"];
                        $total_abonado = $total_abonado + $montos["Monto_bruto"];
                    }

                ///MONTO ABONADO CON CHEQUES
                    $this->db->select('tbl_cheques.Monto');
                    $this->db->from('tbl_dinero_cheques');
                    $this->db->join('tbl_cheques', 'tbl_cheques.Id = tbl_dinero_cheques.Cheque_id', 'left');
                    $this->db->where('Origen_movimiento', 'Compras');
                    $this->db->where('Fila_movimiento', $compra["Id"]);
                    $this->db->where('tbl_dinero_cheques.Visible', 1);
                    
                    $query = $this->db->get();
                    $result = $query->result_array();

                    /////  SUMAR MONTOS
                    
                    foreach ($result as $monto) 
                    {
                        $Total_dinero_pagado_vencidas = $Total_dinero_pagado_vencidas + $monto["Monto"];
                        $total_abonado = $total_abonado + $monto["Monto"];
                    }

                    $info_esta_factura = array('Datos'=> $compra, 'Total_abonado' => $total_abonado, 'Valor_compra' => $valor_compra);
                    array_push($Datos_vencidas, $info_esta_factura);
                
            }

        //// BUSCANDO COMPRAS NO SALDAS Y NO VENCIDAS
            $this->db->select(' tbl_compras.*,
                                tbl_proveedores.Nombre_proveedor,
                                tbl_usuarios.Nombre,
                                tbl_proveedores_rubros.Nombre_rubro');
            $this->db->from('tbl_compras');

            $this->db->join('tbl_proveedores', 'tbl_proveedores.Id = tbl_compras.Proveedor_id', 'left');
            $this->db->join('tbl_proveedores_rubros', 'tbl_proveedores_rubros.Id = tbl_proveedores.Rubro_id', 'left');
            $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_compras.Usuario_id', 'left');

            if($this->datosObtenidos->Fecha_inicio != null)
            {
                $this->db->where("DATE_FORMAT(tbl_compras.Fecha_compra,'%Y-%m-%d') >=", $this->datosObtenidos->Fecha_inicio);
            }
            if($this->datosObtenidos->Fecha_fin != null)
            {
                $this->db->where("DATE_FORMAT(tbl_compras.Fecha_compra,'%Y-%m-%d') <=", $this->datosObtenidos->Fecha_fin);
            }
            /* if($this->datosObtenidos->Periodo_id != 0)
            {
                $this->db->where("Periodo_id", $this->datosObtenidos->Periodo_id);
            } */
            if($this->datosObtenidos->Rubro_id != 0)
            {
                $this->db->where("tbl_proveedores.Rubro_id", $this->datosObtenidos->Rubro_id);
            }

            $this->db->where("DATE_FORMAT(Fecha_vencimiento_pago,'%Y-%m-%d') >", $Hoy); // FILTRANDO VENCIMIENTO
            $this->db->where('tbl_compras.Visible',1);
            $this->db->where('tbl_compras.Saldada',0);
            $this->db->where("tbl_compras.Negocio_id", $this->session->userdata('Negocio_id'));

            $this->db->order_by("tbl_compras.Fecha_vencimiento_pago", "desc");

            $query = $this->db->get();
            $result_no_vencidas = $query->result_array();
  
        /// OBTENER MONTOS DE DINERO DE COMPRAS NO VENCIDAS-----
            
            $Total_valor_compras_no_vencidas = 0;
            $Total_dinero_pagado_no_vencidas = 0;
            $Datos_no_vencidas = array();
            foreach ($result_no_vencidas as $compra) 
            {
                
                /////////// -------------------- OBTENER TOTAL DE DINERO TOTAL DEL COSTO DE LAS COMPRAS -----
                $Total_valor_compras_no_vencidas = $Total_valor_compras_no_vencidas + $compra["Neto"] + $compra["No_gravado"] + $compra["IVA"];
                ////
                $valor_compra = $compra["Neto"] + $compra["No_gravado"] + $compra["IVA"];
                
                
                /////////// -------------------- OBTENER TOTAL DE DINERO PAGADO EN ESTAS COMPRAS-----
                    $total_abonado = 0;
                /// CONSULTANDO MONTOS ABONADOS EN EFECTIVO
                    $this->db->select('Monto');
                    $this->db->from('tbl_dinero_efectivo');
                    $this->db->where('Origen_movimiento', 'Compras');
                    $this->db->where('Fila_movimiento', $compra["Id"]);
                    $this->db->where('Visible', 1);
                    
                    $query = $this->db->get();
                    $resultMontoEfectivo = $query->result_array();
                    
                    /////  SUMAR MONTOS
                    foreach ($resultMontoEfectivo as $montos) 
                    {
                        $Total_dinero_pagado_no_vencidas = $Total_dinero_pagado_no_vencidas + $montos["Monto"];
                        $total_abonado = $total_abonado + $montos["Monto"];
                    }
            
                ///MONTO ABONADO EN TRANSFERENCIA
                    $this->db->select('Monto_bruto');
                    $this->db->from('tbl_dinero_transferencias');
                    $this->db->where('Origen_movimiento', 'Compras');
                    $this->db->where('Fila_movimiento', $compra["Id"]);
                    $this->db->where('Visible', 1);
                    
                    $query = $this->db->get();
                    $resultMontosTransferencia = $query->result_array();
                
                    /////  SUMAR MONTOS
                    foreach ($resultMontosTransferencia as $montos) 
                    {
                        $Total_dinero_pagado_no_vencidas = $Total_dinero_pagado_no_vencidas+ $montos["Monto_bruto"];
                        $total_abonado = $total_abonado+ $montos["Monto_bruto"];
                        
                    }

                ///MONTO ABONADO CON CHEQUES
                    $this->db->select('  tbl_cheques.Monto');
                    $this->db->from('tbl_dinero_cheques');
                    $this->db->join('tbl_cheques', 'tbl_cheques.Id = tbl_dinero_cheques.Cheque_id', 'left');
                    $this->db->where('tbl_dinero_cheques.Origen_movimiento', 'Compras');
                    $this->db->where('tbl_dinero_cheques.Fila_movimiento', $compra["Id"]);
                    $this->db->where('tbl_dinero_cheques.Visible', 1);
                    
                    $query = $this->db->get();
                    $result = $query->result_array();

                    /////  SUMAR MONTOS
                    
                    foreach ($result as $monto) 
                    {
                        $Total_dinero_pagado_no_vencidas = $Total_dinero_pagado_no_vencidas + $monto["Monto"];
                        $total_abonado = $total_abonado + $monto["Monto"];
                    }

                    $info_esta_factura = array('Datos'=> $compra, 'Total_abonado' => $total_abonado, 'Valor_compra' => $valor_compra);
                    array_push($Datos_no_vencidas, $info_esta_factura);
                
            }

        //// --------------
        $Datos = array(
                        'Compras_saldadas'=> $result_saldadas,
                        'Compras_vencidas' => $Datos_vencidas,
                        'Compras_no_vencidas' => $Datos_no_vencidas, 
                        'Total_dinero_pagado_no_vencidas' => $Total_dinero_pagado_no_vencidas,
                        'Total_valor_compras_no_vencidas' => $Total_valor_compras_no_vencidas,
                        'Total_dinero_pagado_vencidas' => $Total_dinero_pagado_vencidas, 
                        'Total_valor_compras_vencidas' => $Total_valor_compras_vencidas
                    );
        
        echo json_encode($Datos);
		
    }


//// COMPRAS 	   | OBTENER listado proveedor
	public function obtener_compras_proveedor()
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
        
        $Proveedor_id = $_GET["Id"];

        $this->db->select(' tbl_compras.*,
                            tbl_proveedores.Nombre_proveedor,
                            tbl_usuarios.Nombre');
        $this->db->from('tbl_compras');
        
        $this->db->join('tbl_proveedores', 'tbl_proveedores.Id = tbl_compras.Proveedor_id', 'left');
        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_compras.Usuario_id', 'left');

        $this->db->where('tbl_compras.Visible',1);
        $this->db->where('tbl_compras.Proveedor_id', $Proveedor_id);

		$this->db->order_by("tbl_compras.Fecha_compra", "desc");
        
        $query = $this->db->get();
        $array_compras_proveedor = $query->result_array();

        
        $Datos = array();
        
        foreach ($array_compras_proveedor as $compra) 
        {
            $Total_pagado = 0;

            ///// MONTOS EFECTIVO
            $this->db->select('Monto');
            $this->db->from('tbl_dinero_efectivo');
            $this->db->where('Origen_movimiento', 'Compras');
            $this->db->where('Fila_movimiento', $compra["Id"]);
            $this->db->where('Visible', 1);
            
            $query = $this->db->get();
            $result_efectivo = $query->result_array();
            $cant_efectivo = $query->num_rows();
            
            if($cant_efectivo > 0)
            {
                foreach ($result_efectivo as $monto) 
                {
                    $Total_pagado = $Total_pagado + $monto["Monto"];
                }
            }
            
            ///// MONTOS TRANSFERENCIAS
            $this->db->select('Monto_bruto');
            $this->db->from('tbl_dinero_transferencias');
            $this->db->where('Origen_movimiento', 'Compras');
            $this->db->where('Fila_movimiento', $compra["Id"]);
            $this->db->where('Visible', 1);
            
            $query = $this->db->get();
            $result_transferencias = $query->result_array();
            $cant_transferencias = $query->num_rows();
            
            if($cant_transferencias > 0)
            {
                foreach ($result_transferencias as $monto) 
                {
                    $Total_pagado = $Total_pagado + $monto["Monto_bruto"];
                }
            }

            ///// MONTOS CHEQUES
            $this->db->select('tbl_cheques.Monto');

            $this->db->from('tbl_dinero_cheques');
            $this->db->join('tbl_cheques', 'tbl_cheques.Id = tbl_dinero_cheques.Cheque_id', 'left');

            $this->db->where('tbl_dinero_cheques.Origen_movimiento', 'Compras');
            $this->db->where('tbl_dinero_cheques.Fila_movimiento', $compra["Id"]);
            $this->db->where('tbl_dinero_cheques.Visible', 1);
            
            $query = $this->db->get();
            $result_cheques = $query->result_array();
            $cant_cheques = $query->num_rows();
            
            if($cant_cheques > 0)
            {
                foreach ($result_cheques as $monto) 
                {
                    $Total_pagado = $Total_pagado + $monto["Monto"];
                }
            }
            
            $datosCompra = array("Datos"=> $compra, "Total" => $Total_pagado);
            
            array_push($Datos, $datosCompra);
        }
        
        echo json_encode($Datos);
		
    }
    
//// COMPRAS 	   | OBTENER DATOS DE UNA COMPRA
    public function obtener_datos_compra()
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

        $this->db->select(' tbl_compras.*,
                            tbl_proveedores.Id as Proveedor_id,
                            tbl_proveedores.Nombre_proveedor,
                            tbl_proveedores.Email,
                            tbl_proveedores.Telefono,
                            tbl_proveedores.Web,
                            tbl_usuarios.Nombre');
        $this->db->from('tbl_compras');
        
        $this->db->join('tbl_proveedores', 'tbl_proveedores.Id = tbl_compras.Proveedor_id', 'left');
        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_compras.Usuario_id', 'left');
        $this->db->where('tbl_compras.Id', $Id);
        $this->db->where('tbl_compras.Visible',1);
        
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);

    }

//// COMPRAS 	   | SUBIR FOTO 
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
					$insert_id = $this->App_model->insertar($data, $Id, 'tbl_compras');
					
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

//// COMPRAS 	   | CARGAR O EDITAR
	public function cargar_compra()
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

		$data = array(
                        
                    'Negocio_id' => $this->session->userdata('Negocio_id'),
                    'Proveedor_id' => 			$this->datosObtenidos->Datos->Proveedor_id,
                    'Periodo_id' => 			$this->datosObtenidos->Datos->Periodo_id,
                    'Fecha_vencimiento_pago' => $this->datosObtenidos->Datos->Fecha_vencimiento_pago,
					'Fecha_compra' => 			$this->datosObtenidos->Datos->Fecha_compra,
					'Factura_identificador' => 	$this->datosObtenidos->Datos->Factura_identificador,
                    'Neto' => 			        $this->datosObtenidos->Datos->Neto,
                    'No_gravado' => 			$this->datosObtenidos->Datos->No_gravado,
                    'IVA' => 			        $this->datosObtenidos->Datos->IVA,
                    'Saldada' => 			    $this->datosObtenidos->Datos->Saldada,
                    'Descripcion' => 			$this->datosObtenidos->Datos->Descripcion,
                    'Usuario_id' => 		    $this->session->userdata('Id'),                    
				);

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_compras');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
    }

//// COMPRAS 	   | DESACTIVAR COMPRA
	public function desactivar_compra()
    {
        //// ESTA FUNCION DE FUNCIONAR DEBE ELIMINAR TODOS LOS ITEMS COMPRADOS, Y RESTAR DEL STOCK. EN LO POSIBLE NO SERÍA BUENO QUE EXISTA
        $CI =& get_instance();
		$CI->load->database();
		
        ///Seguridad
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
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_compras');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
    }


//// COMPRAS 	   | OBTENER listado 25 Dashboard
	public function obtener_compras_dashboarad()
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

        $this->db->select(' tbl_compras.Fecha_compra,
                            tbl_compras.Neto,
                            tbl_compras.No_gravado,
                            tbl_compras.IVA,
                            tbl_compras.Id,
                            tbl_proveedores.Nombre_proveedor');
        $this->db->from('tbl_compras');
        
        $this->db->join('tbl_proveedores', 'tbl_proveedores.Id = tbl_compras.Proveedor_id', 'left');

        $this->db->where('tbl_compras.Visible',1);
        $this->db->order_by("tbl_compras.Fecha_compra", "desc");
        $this->db->limit(25);

        
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
    }

//// COMPRAS 	   | OBTENER MONTO COMPRAS POR PERIODO
	public function obtener_monto_compras_por_periodo()
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
        
        $this->db->select(' tbl_compras.Neto,
                            tbl_compras.No_gravado,
                            tbl_compras.IVA');
        $this->db->from('tbl_compras');

        $this->db->where('tbl_compras.Periodo_id', $Periodo_id);
        $this->db->where('tbl_compras.Visible',1);
        
        $query = $this->db->get();
		$arraycompras = $query->result_array();

        $Total = 0;
        foreach ($arraycompras as $compra)
        {
            $Total = $Total + $compra["Neto"] + $compra["No_gravado"] + $compra["IVA"];
        }
        
        echo json_encode($Total);
		
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

        $this->db->select(' tbl_compras_seguimiento.*,
                            tbl_usuarios.Nombre');
        
        $this->db->from('tbl_compras_seguimiento');
        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_compras_seguimiento.Usuario_id', 'left');

        $this->db->where('tbl_compras_seguimiento.Compra_id', $Id);
        $this->db->where('tbl_compras_seguimiento.Visible', 1);
        $this->db->where("tbl_compras_seguimiento.Negocio_id", $this->session->userdata('Negocio_id'));

        $this->db->order_by('tbl_compras_seguimiento.Id', 'desc');
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

            'Compra_id' =>      $this->datosObtenidos->Compra_id,
            'Fecha' =>          $this->datosObtenidos->Datos->Fecha,
            'Descripcion' =>    $this->datosObtenidos->Datos->Descripcion,
            'Usuario_id' =>    $this->session->userdata('Id'),
            'Visible' =>        1,
            'Negocio_id' => $this->session->userdata('Negocio_id'),

        );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_compras_seguimiento');

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
                    $insert_id = $this->App_model->insertar($data, $Id, 'tbl_compras_seguimiento');
                    
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
    //// LAS FUNCIONES DE OBTENER Y DE CARGAR INGRESOS Y EGRESOS DE STOCK SE HACEN DESDE EL CONTROLLER STOCK
///// fin documento
}
