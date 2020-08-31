<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Analiticas extends CI_Controller 
{ 


//// USUARIOS | LISTADO
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
				$this->load->view('analitica-index');
			}
			else 
			{
				header("Location: ".base_url()."login"); /// enviar a pagina de error
			}	
			
		}
    }


//// ANALITICA | VENTAS | VISTA
	public function ventas()
	{	
		if ( $this->session->userdata('Login') != true )
		{
			header("Location: ".base_url()."login"); /// enviar a pagina de error
		}
		else
		{	
            ////COMENZAR A FILTRAR Y REDIRECCIONAR SEGUN ROL Y PLAN CONTRATADO
                //if (plan_contratado() > 1) {}
            
            if ( $this->session->userdata('Rol_id') >= 4)
			{
				if (plan_contratado() > 1) 	
				{ 
					$this->load->view('analiticas-ventas'); 
				}
				else						
				{	
					$this->load->view('plan-medio'); 
				}
			}
			else 
			{
				header("Location: ".base_url()."login"); /// enviar a pagina de error
			}	
			
		}
	}

//// ANALITICA | VENTAS | OBTENER LISTADO
	public function listado_ventas()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

        // Filtros - por jornada - por fechas - por item
            
            $Jornada_id =   $_GET["Jornada_id"];
            $Fecha_inicio = $_GET["Fecha_inicio"];
            $Fecha_fin =    $_GET["Fecha_fin"];
            $Categoria_id = $_GET["Categoria_id"];

        
        
        // Ventas por comanda
            //Primero hacer un array con todos los item de la carta

                $this->db->select('	tbl_stock.Id,
                                    tbl_stock.Nombre_item,
                                    tbl_stock.Categoria_id,
                                    tbl_stock.Imagen,
                                    tbl_stock_categorias.Nombre_categoria');
                
                $this->db->from('tbl_stock');
                
                $this->db->join('tbl_stock_categorias', 'tbl_stock_categorias.Id = tbl_stock.Categoria_id','left');
                
                $this->db->where('tbl_stock.Apto_carta', 1);
                $this->db->where("tbl_stock.Negocio_id", $this->session->userdata('Negocio_id'));

                if($Categoria_id != 0) {    $this->db->where('tbl_stock.Categoria_id', $Categoria_id);  }
                
                // where para categoria
                
                $query = $this->db->get();
                $array_items = $query->result_array();

                $Datos = array();

            //Luego armar otro array con cada item y datos: cantidad de veces que aparece.
                
                foreach ($array_items as $item) 
                {
                    // Consulto cuantas veces encuentra este item en comandas.
                        $this->db->select('tbl_items_comanda.Id');
                        $this->db->from('tbl_items_comanda');
                        
                        $this->db->join('tbl_comandas', 'tbl_comandas.Id = tbl_items_comanda.Comanda_id','left');

                        $this->db->where('tbl_items_comanda.Item_carga_id', $item["Id"]);
                        $this->db->where("tbl_items_comanda.Negocio_id", $this->session->userdata('Negocio_id'));

                        if($Fecha_inicio != 0)   { $this->db->where("DATE_FORMAT( tbl_comandas.Hora_llegada, '%Y-%m-%d' ) >=", $Fecha_inicio); }
                        if($Fecha_fin != 0)      { $this->db->where("DATE_FORMAT( tbl_comandas.Hora_llegada, '%Y-%m-%d' ) <=", $Fecha_fin); }
                        if($Jornada_id != 0)        { $this->db->where("tbl_comandas.Jornada_id", $Jornada_id); }

                        $query = $this->db->get();
                        $total_ventas_comandas = $query->num_rows();

                    
                    // Consulto cuantas veces encuentra este item en delivery.
                        $this->db->select('tbl_delibery_items.Id');
                        $this->db->from('tbl_delibery_items');

                        $this->db->join('tbl_delibery', 'tbl_delibery.Id = tbl_delibery_items.Delibery_id','left');

                        $this->db->where('tbl_delibery_items.Item_id', $item["Id"]);
                        $this->db->where("tbl_delibery_items.Negocio_id", $this->session->userdata('Negocio_id'));

                        if($Fecha_inicio != 0)   { $this->db->where("DATE_FORMAT( tbl_delibery.FechaHora_pedido, '%Y-%m-%d' ) >=", $Fecha_inicio); }
                        if($Fecha_fin != 0)      { $this->db->where("DATE_FORMAT( tbl_delibery.FechaHora_pedido, '%Y-%m-%d' ) <=", $Fecha_fin); }
                        if($Jornada_id != 0)        { $this->db->where("tbl_delibery.Jornada_id", $Jornada_id); }

                        $query = $this->db->get();
                        $total_ventas_delivery = $query->num_rows();

                    $Total_ventas = $total_ventas_comandas + $total_ventas_delivery;

                    $datos_item = array( 'Item_id' => $item["Id"], 
                                    'Nombre_item' => $item["Nombre_item"], 
                                    'Categoria' => $item["Nombre_categoria"], 
                                    'Ventas_comanda' => $total_ventas_comandas, 
                                    'Ventas_delivery' => $total_ventas_delivery,
                                    'Total_ventas' => $Total_ventas);

                    array_push($Datos, $datos_item);
                }

            
            //Luego este array con item mas cantidad ordenarlo de menor a mayor

                foreach ($Datos as $key => $row) {
                    $aux[$key] = $row['Total_ventas'];
                }

                array_multisort($aux, SORT_DESC, $Datos);
            
       //Y este largarlo a imprimir
        echo json_encode($Datos);
		
	}
	



}