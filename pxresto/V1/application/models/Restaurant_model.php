<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: jmanzano
 * Date: 20/9/16
 * Time: 22:36
 */


class Restaurant_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }


    //Actualiza o inserta Items de la carta dependiendo si viene por post el Id
    function insertarItemCarta($data, $Id)
    {
        if ($Id == NULL)
        {
            $result = $this->db->insert('tbl_stock', $data);
            $insert_id = $this->db->insert_id();
            return $insert_id;        
        }
        else
        {
            $this->db->where('Id', $Id);
            $result = $this->db->update('tbl_stock', $data);
            $affected_id = $this->db->affected_rows();
            return $affected_id;
        }
    }


    //Actualiza o inserta NUEVO RENTING dependiendo si viene por post el Id
    function insertarCategoriaCarta($data, $Id)
    {
        if ($Id == NULL)
        {
            $result = $this->db->insert('tbl_stock_categorias', $data);
            $insert_id = $this->db->insert_id();
            return $insert_id;
        }
        else
        {
            $this->db->where('Id', $Id);
            $result = $this->db->update('tbl_stock_categorias', $data);
            $affected_id = $this->db->affected_rows();
            return $affected_id;
        }
    }


    //Actualiza o inserta NUEVO RENTING dependiendo si viene por post el Id
    function insertar($data, $Id, $tabla)
    {
        if ($Id == NULL)
        {
            $result = $this->db->insert($tabla,$data);
            $insert_id = $this->db->insert_id();
            return $insert_id;
        }
        else
        {
            $this->db->where('Id', $Id);
            $result = $this->db->update($tabla,$data);
            $affected_id = $this->db->affected_rows();
            return $affected_id;
        }
    }

    //ELIMINA ITEMS

    function eliminar($Id_item, $tabla)
    {

        $this->db->where('Id', $Id_item);
        $this->db->delete($tabla);
    }


    //// CONSULTANDO ESTADO DE LA ULTIMA JORNADA
    function datos_jornada()
    {
        $CI =& get_instance();
        $CI->load->database();

        ///BUSCO EL ESTADO DE LA ULTIMA FILA CARGADA EN JORNADA
		$this->db->select('*');
		$this->db->from('tbl_jornadas');
        $this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));
        $this->db->order_by("Id", "desc");
        $this->db->limit(2, 0);

        $query = $this->db->get();
        $result = $query->result_array();
        $Cant = $query->num_rows();
        
        if($Cant > 0) 
        {   
            return $result[0];
           
        }
        else {
            return array('Id' => 0, 'Estado' => 1);
        }
        
    }

    //// CONSULTANDO ESTADO DE LA ULTIMA JORNADA
    function info_negocio()
    {
        $CI =& get_instance();
        $CI->load->database();

        ///BUSCO EL ESTADO DE LA ULTIMA FILA CARGADA EN JORNADA
		$this->db->select('*');
		$this->db->from('tbl_negocios');
        $this->db->where("Id", $this->session->userdata('Negocio_id'));

        $query = $this->db->get();
        $result = $query->result_array();

        return $result[0];
           
    }
//////////////////////////////////////////    
}