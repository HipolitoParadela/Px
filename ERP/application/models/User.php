<?php

/**
 *
 */
class User extends CI_Model
{
    public function getUser($dni = '')
    {
        $result = $this->db->query("SELECT * FROM tbl_usuarios WHERE DNI = '" . $dni . "' LIMIT 1");

        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

}
