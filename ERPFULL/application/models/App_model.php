<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Created by Visual Studio Code.
 * User: hparadela
 * Date: 27/07/2018
 * Time: 22:36
 */

class App_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }


    //Actualiza o inserta NUEVO RENTING dependiendo si viene por post el Id
    public function insertar($data, $Id, $tabla)
    {
        if ($Id == null) {
            $result = $this->db->insert($tabla, $data);
            $insert_id = $this->db->insert_id();
            return $insert_id;
        } else {
            $this->db->where('Id', $Id);
            $result = $this->db->update($tabla, $data);
            $affected_id = $this->db->affected_rows();
            //return $affected_id;
            return $Id;
        }
    }

    //ELIMINA ITEMS

    public function eliminar($Id_item, $tabla)
    {

        $this->db->where('Id', $Id_item);
        $this->db->delete($tabla);
    }


//////////////////////////////////////////
}
