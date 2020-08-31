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
            ////COMENZAR A FILTRAR Y REDIRECCIONAR SEGUN ROL Y PLAN CONTRATADO
                //if (plan_contratado() > 1) {}
            
            if ( $this->session->userdata('Rol_id') >= 4)
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
			}	
			
		}
	}

//// CLIENTES | OBTENER CLIENTES
    public function obtener_listado_de_clientes()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        $token = @$CI->db->token;

        //$Id = $_GET["Id"];

        $this->db->select('*');
        $this->db->from('tbl_clientes');
        $this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));
        $this->db->order_by("Nombre", "asc");
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

        $Id = NULL;
        if(isset($this->datosObtenidos->Datos->Id))
        {
            $Id = $this->datosObtenidos->Datos->Id;
        }
        
        $data = array(
                        
                    'Nombre' => 	$this->datosObtenidos->Datos->Nombre,
                    'Telefono' => 	$this->datosObtenidos->Datos->Telefono,
                    'Telefono_secundario' => 	$this->datosObtenidos->Datos->Telefono_secundario,
                    'Email' => 	$this->datosObtenidos->Datos->Email,
                    'Direccion' => 	$this->datosObtenidos->Datos->Direccion,
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


	
//////////////////////
}