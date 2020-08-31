<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Exportar extends CI_Controller
{

//// FABRICACIÓN        | VISTA | LISTADO
    public function index()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {

            if ($this->session->userdata('Rol_id') == 1) {
                $this->load->view('export_excel');
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }
}