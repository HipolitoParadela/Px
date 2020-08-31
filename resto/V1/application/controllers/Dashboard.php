<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function index()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {
            if ($this->session->userdata('Rol_id') == 1) /// si es delivery o colaborador no puede acceder
            {
                $this->session->sess_destroy();
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            } else {

                $this->load->model('Restaurant_model');

                $info_negocio = $this->Restaurant_model->info_negocio();

                $datos = array(

                    'Info_negocio'    => $info_negocio,
                );

                $this->load->view('dashboard', $datos);
            }
        }
    }

    /// CONSULTAR LAS VENTAS DE HOY
    public function obtener_listado_comandas_hoy()
    {

        $Fecha_hoy = date('Y-m-d');

        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        $token = @$CI->db->token;


        /// DATOS DE COMANDAS
        $this->db->select('	Valor_cuenta,
                                    Valor_descuento');
        $this->db->from('tbl_comandas');
        $this->db->where("DATE_FORMAT(Fecha,'%Y-%m-%d')", $Fecha_hoy);
        $this->db->where('Estado', 1);
        $this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));
        $query = $this->db->get();
        $array_comandas = $query->result_array();

        // sumar
        $valor_ventas_comandas = 0;
        foreach ($array_comandas as $comanda) {
            $valor = $comanda["Valor_cuenta"];
            $descuento = $comanda["Valor_descuento"];
            $valor_ventas_comandas = $valor_ventas_comandas + $valor - $descuento;
        }

        /// DATOS DE DELIVERY
        $this->db->select('	Valor_cuenta,
                                    Valor_descuento');
        $this->db->from('tbl_delibery');
        $this->db->where("DATE_FORMAT(FechaHora_pedido,'%Y-%m-%d')", $Fecha_hoy);
        $this->db->where('Estado', 1);
        $this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));
        $query = $this->db->get();
        $array_delivery = $query->result_array();

        // sumar
        $valor_ventas_delivery = 0;
        foreach ($array_delivery as $comanda) {
            $valor = $comanda["Valor_cuenta"];
            $descuento = $comanda["Valor_descuento"];
            $valor_ventas_delivery = $valor_ventas_delivery + $valor - $descuento;
        }


        $total_ventas = $valor_ventas_comandas + $valor_ventas_delivery;

        echo json_encode($total_ventas);
    }

    /// CONSULTAR LAS VENTAS DE AYER
    public function obtener_listado_comandas_de_ayer()
    {
        //$this->datosObtenidos = json_decode(file_get_contents('php://input'));

        $fecha = date('Y-m-d');
        $fecha_ayer = strtotime('-1 day', strtotime($fecha));
        $fecha_ayer = date('Y-m-d', $fecha_ayer);

        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        $token = @$CI->db->token;

        /// DATOS DE COMANDAS
        $this->db->select('	Valor_cuenta,
                                    Valor_descuento');
        $this->db->from('tbl_comandas');
        $this->db->where("DATE_FORMAT(Fecha,'%Y-%m-%d')", $fecha_ayer);
        $this->db->where('Estado', 1);
        $this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));
        $query = $this->db->get();
        $array_comandas = $query->result_array();

        // sumar
        $valor_ventas_comandas = 0;
        foreach ($array_comandas as $comanda) {
            $valor = $comanda["Valor_cuenta"];
            $descuento = $comanda["Valor_descuento"];
            $valor_ventas_comandas = $valor_ventas_comandas + $valor - $descuento;
        }

        /// DATOS DE DELIVERY
        $this->db->select('	Valor_cuenta,
                                    Valor_descuento');
        $this->db->from('tbl_delibery');
        $this->db->where("DATE_FORMAT(FechaHora_pedido,'%Y-%m-%d')", $fecha_ayer);
        $this->db->where('Estado', 1);
        $this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));
        $query = $this->db->get();
        $array_delivery = $query->result_array();

        // sumar
        $valor_ventas_delivery = 0;
        foreach ($array_delivery as $comanda) {
            $valor = $comanda["Valor_cuenta"];
            $descuento = $comanda["Valor_descuento"];
            $valor_ventas_delivery = $valor_ventas_delivery + $valor - $descuento;
        }


        $total_ventas = $valor_ventas_comandas + $valor_ventas_delivery;

        echo json_encode($total_ventas);
    }

    /// CONSULTAR LAS VENTAS DEL MES
    public function obtener_listado_comandas_de_este_mes()
    {
        //$this->datosObtenidos = json_decode(file_get_contents('php://input'));

        $Mes = date('Y-m');

        //$fecha = date("Y-m-d");

        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        $token = @$CI->db->token;

        /// DATOS DE COMANDAS
        $this->db->select('	Valor_cuenta,
                                    Valor_descuento');
        $this->db->from('tbl_comandas');
        $this->db->where("DATE_FORMAT(Fecha,'%Y-%m')", $Mes);
        $this->db->where('Estado', 1);
        $this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));
        $query = $this->db->get();
        $array_comandas = $query->result_array();

        // sumar
        $valor_ventas_comandas = 0;
        foreach ($array_comandas as $comanda) {
            $valor = $comanda["Valor_cuenta"];
            $descuento = $comanda["Valor_descuento"];
            $valor_ventas_comandas = $valor_ventas_comandas + $valor - $descuento;
        }

        /// DATOS DE DELIVERY
        $this->db->select('	Valor_cuenta,
                                    Valor_descuento');
        $this->db->from('tbl_delibery');
        $this->db->where("DATE_FORMAT(FechaHora_pedido,'%Y-%m')", $Mes);
        $this->db->where('Estado', 1);
        $this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));
        $query = $this->db->get();
        $array_delivery = $query->result_array();

        // sumar
        $valor_ventas_delivery = 0;
        foreach ($array_delivery as $comanda) {
            $valor = $comanda["Valor_cuenta"];
            $descuento = $comanda["Valor_descuento"];
            $valor_ventas_delivery = $valor_ventas_delivery + $valor - $descuento;
        }


        $total_ventas = $valor_ventas_comandas + $valor_ventas_delivery;

        echo json_encode($total_ventas);
    }

    /// CONSULTAR ALS VENTAS DEL AÑO
    public function obtener_listado_comandas_de_este_anio()
    {

        //$this->datosObtenidos = json_decode(file_get_contents('php://input'));

        $Anio = date('Y');

        //$fecha = date("Y-m-d");

        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        $token = @$CI->db->token;


        /// DATOS DE COMANDAS
        $this->db->select('	Valor_cuenta,
                                    Valor_descuento');
        $this->db->from('tbl_comandas');
        $this->db->where("DATE_FORMAT(Fecha,'%Y')", $Anio);
        $this->db->where('Estado', 1);
        $this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));
        $query = $this->db->get();
        $array_comandas = $query->result_array();

        // sumar
        $valor_ventas_comandas = 0;
        foreach ($array_comandas as $comanda) {
            $valor = $comanda["Valor_cuenta"];
            $descuento = $comanda["Valor_descuento"];
            $valor_ventas_comandas = $valor_ventas_comandas + $valor - $descuento;
        }

        /// DATOS DE DELIVERY
        $this->db->select('	Valor_cuenta,
                                    Valor_descuento');
        $this->db->from('tbl_delibery');
        $this->db->where("DATE_FORMAT(FechaHora_pedido,'%Y')", $Anio);
        $this->db->where('Estado', 1);
        $this->db->where("Negocio_id", $this->session->userdata('Negocio_id'));
        $query = $this->db->get();
        $array_delivery = $query->result_array();

        // sumar
        $valor_ventas_delivery = 0;
        foreach ($array_delivery as $comanda) {
            $valor = $comanda["Valor_cuenta"];
            $descuento = $comanda["Valor_descuento"];
            $valor_ventas_delivery = $valor_ventas_delivery + $valor - $descuento;
        }


        $total_ventas = $valor_ventas_comandas + $valor_ventas_delivery;

        echo json_encode($total_ventas);
    }


    /// info Minuto a minuto
    public function infoTimeline()
    {
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));

        $Pagina_actual = $this->datosObtenidos->Actual;

        $inicio = 0;
        $cantidadItems = 10;

        if ($this->datosObtenidos->Peticion == "Next") {
            $inicio =  $Pagina_actual + $cantidadItems;
        } elseif ($this->datosObtenidos->Peticion == "Prev") {
            $inicio =  $Pagina_actual - $cantidadItems;
        }


        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        $token = @$CI->db->token;

        $this->db->select('	tbl_comandas.Id,
                                tbl_comandas_timeline.*,
                                tbl_mesas.Identificador,
                                tbl_usuarios.Nombre as Nombre_moso');

        $this->db->from('tbl_comandas_timeline');

        $this->db->join('tbl_comandas', 'tbl_comandas.Id = tbl_comandas_timeline.Comanda_id', 'left');
        $this->db->join('tbl_mesas', 'tbl_mesas.Id = tbl_comandas.Mesa_id', 'left');
        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_comandas.Moso_id', 'left');
        $this->db->where("tbl_comandas_timeline.Negocio_id", $this->session->userdata('Negocio_id'));
        /// segun el rol el lo que pueda ver
        if ($this->session->userdata('Rol_id') == 2) {
            $Usuario_login_id = $this->session->userdata('Id');
            $this->db->where('tbl_comandas.Moso_id', $Usuario_login_id);
        }

        $this->db->order_by("tbl_comandas_timeline.Hora", "desc");
        $this->db->limit($cantidadItems, $inicio); /// paginación

        //$this->db->where("DATE_FORMAT(tbl_comandas_timeline.Fecha,'%Y-%m-%d')", $fecha_ayer);

        //$this->db->where('tbl_comandas.Estado', 1);

        $query = $this->db->get();
        $result = $query->result_array();
        $totalRegistros = $query->num_rows();

        $Final = $totalRegistros / $cantidadItems;
        $Final = ceil($Final);

        $datos = array('Datos' => $result, 'Inicio' => $inicio, 'Final' => $Final);

        echo json_encode($datos);
    }

    /// Logs presencia
    public function obtener_log_presencia()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        $token = @$CI->db->token;

        $this->datosObtenidos = json_decode(file_get_contents('php://input'));

        /// OBTENIENDO LA LISTA SOLAMENTE DE LOS USUARIOS ACTIVOS AHORA
        $this->db->select('	tbl_usuarios.Nombre,
                                    tbl_usuarios.Imagen,
                                    tbl_roles.Nombre_rol');

        $this->db->from('tbl_usuarios');

        $this->db->join('tbl_roles', 'tbl_roles.Id = tbl_usuarios.Rol_id', 'left');
        $this->db->where('tbl_usuarios.Presencia', 1);
        $this->db->where("tbl_usuarios.Negocio_id", $this->session->userdata('Negocio_id'));
        $this->db->order_by("tbl_usuarios.Nombre", "desc");

        $query = $this->db->get();
        $result_presentes = $query->result_array();


        /// OBTENIENDO LA LISTA COMPLETA DE LOS LOG
        $Pagina_actual = $this->datosObtenidos->Actual;

        $inicio = 0;
        $cantidadItems = 10;

        if ($this->datosObtenidos->Peticion == "Next") {
            $inicio =  $Pagina_actual + $cantidadItems;
        } elseif ($this->datosObtenidos->Peticion == "Prev") {
            $inicio =  $Pagina_actual - $cantidadItems;
        }

        $this->db->select('	tbl_log_usuarios.*,
                                    tbl_usuarios.Nombre as Nombre_usuario,
                                    tbl_usuarios.Imagen,
                                    tbl_roles.Nombre_rol');

        $this->db->from('tbl_log_usuarios');

        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_log_usuarios.Colaborador_id', 'left');
        $this->db->join('tbl_roles', 'tbl_roles.Id = tbl_usuarios.Rol_id', 'left');
        $this->db->where("tbl_log_usuarios.Negocio_id", $this->session->userdata('Negocio_id'));
        $this->db->limit($cantidadItems, $inicio); /// paginación
        $this->db->order_by("tbl_log_usuarios.Fecha_hora", "desc");

        $query = $this->db->get();
        $result = $query->result_array();

        $totalRegistros = $query->num_rows();
        $Final = $totalRegistros / $cantidadItems;
        $Final = ceil($Final);

        $datos = array('usuariosPresentes' => $result_presentes, 'Datos' => $result, 'Inicio' => $inicio, 'Final' => $Final);

        echo json_encode($datos);
    }


    /*function nationList($limit=null, $start=null) 
        {
            //if ($this->session->userdata('language')=="it")
                
            $this->db->select('nation.id, nation.name_it as name');

            //if ($this->session->userdata('language')=="en")
                //$this->db->select('nation.id, nation.name_en as name');

            $this->db->from('nation');
            $this->db->order_by("name", "asc");

            if($limit!='' && $start!='')
            {
                $this->db->limit($limit, $start);
            }

            $query  = $this->db->get();

            $nation = array();

            foreach ($query->result() as $row)
                array_push($nation, $row);

            return $nation;     
        }*/
    //////
}
