<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function index()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {
            if ($this->session->userdata('Rol_acceso') > 3) 
            {
                $this->load->view('dashboard');

            }
            elseif ($this->session->userdata('Rol_acceso') < 4) 
            {
                $this->load->view('dashboard_alternativo');

            } 
            else 
            {
                $this->session->sess_destroy();
                header("Location: " . base_url() . "login"); /// enviar a pagina de error

            }
        }
    }

    /// CANTIDAD DE LISTADOS DE MES EN CURSO
    public function obtener_cantidad_de_items_stoqueados()
    {
        //$this->datosObtenidos = json_decode(file_get_contents('php://input'));

        //$Mes = date('Y-m');

        //$fecha = date("Y-m-d");

        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        
        //Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) {
            exit("No coinciden los token");
        }

        $this->db->select('Id');
        $this->db->from('tbl_stock');
        //$this->db->where("DATE_FORMAT(fecha,'%Y-%m')", $Mes);
        $this->db->where('Visible', 1);
        $query = $this->db->get();
        $total = $query->num_rows();

        echo json_encode($total);

    }


    /// CANTIDAD DE USUARIOS A LA FECHA
    public function obtener_cantidad_usuarios()
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

        /// DATOS DE COMANDAS
        $this->db->select('Id');
        $this->db->from('tbl_usuarios');
        $this->db->where('Activo', 1);
        $query = $this->db->get();
        $total = $query->num_rows();

        echo json_encode($total);

    }
    

    /// CONSULTAR ALS VENTAS DEL AÑO
    public function obtener_cantCompras()
    {
        $Anio = date('Y');

        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        
        //Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) 
        {
            exit("No coinciden los token");
        }

        $this->db->select('Id');
        $this->db->from('tbl_compras');
        $this->db->where("DATE_FORMAT(Fecha_compra,'%Y')", $Anio);
        
        $query = $this->db->get();
        $total = $query->num_rows();

        echo json_encode($total);

    }


    /// CANTIDAD DE PRDUCTOS DE FABRICACIÓN PROPIA
    public function obtener_productosPropios()
    {
        $Anio = date('Y');

        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        
        //Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) 
        {
            exit("No coinciden los token");
        }

        $this->db->select('Id');
        $this->db->from('tbl_fabricacion');
        
        $query = $this->db->get();
        $total = $query->num_rows();

        echo json_encode($total);

    }


    
    
/// CONSULTAR LAS VENTAS DE HOY
    public function obtener_listado_comandas_hoy()
    {

        $Fecha_hoy = date('Y-m-d');

        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        
        //Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) {
            exit("No coinciden los token");
        }

        /// DATOS DE COMANDAS
        $this->db->select('	Valor_cuenta,
                                    Valor_descuento');
        $this->db->from('tbl_comandas');
        $this->db->where("DATE_FORMAT(Fecha,'%Y-%m-%d')", $Fecha_hoy);
        $this->db->where('Estado', 1);
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
        
        //Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) {
            exit("No coinciden los token");
        }

        /// DATOS DE COMANDAS
        $this->db->select('	Valor_cuenta,
                                    Valor_descuento');
        $this->db->from('tbl_comandas');
        $this->db->where("DATE_FORMAT(Fecha,'%Y-%m-%d')", $fecha_ayer);
        $this->db->where('Estado', 1);
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

//// DASHBOARD       | CALCULOS DE MONTOS INGRESADOS EN DIFERENTES PERIODOS
    public function obtener_movimientos_de_fondos()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance(); 
        $CI->load->database();
        
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) { exit("No coinciden los token"); }

            $Total_mes = 0;
            $Total_anio = 0;

            $Entrante_mes = 0;
            $Saliente_mes = 0;
            $Entrante_anio = 0;
            $Saliente_anio = 0;

            $Este_mes = date("Y-m");
            $Este_anio = date("Y");

        /// FONDOS EN EFECTIVO DE ESTE MES
            $this->db->select('*');
            $this->db->from('tbl_dinero_efectivo');
            $this->db->where('Visible', 1);
            $this->db->where("DATE_FORMAT(Fecha_ejecutado,'%Y-%m')", $Este_mes);
            $query = $this->db->get();
            $result = $query->result_array();
            
            foreach ($result as $monto) 
            {
                if($monto["Op"] == 1){  $Entrante_mes = $Entrante_mes + $monto["Monto"]; }
                else {                  $Saliente_mes = $Saliente_mes + $monto["Monto"]; }
                
            }
        
        /// FONDOS EN EFECTIVO DE ESTE AÑO
            $this->db->select('*');
            $this->db->from('tbl_dinero_efectivo');
            $this->db->where('Visible', 1);
            $this->db->where("DATE_FORMAT(Fecha_ejecutado,'%Y')", $Este_anio);
            $query = $this->db->get();
            $result = $query->result_array();
            
            foreach ($result as $monto) 
            {
                if($monto["Op"] == 1){  $Entrante_anio = $Entrante_anio + $monto["Monto"]; }
                else {                  $Saliente_anio = $Saliente_anio + $monto["Monto"]; }
               
            }

        /// FONDOS EN TRANSFERENCIAS DE ESTE MES
            $this->db->select('*');
            $this->db->from('tbl_dinero_transferencias');
            $this->db->where('Visible', 1);
            $this->db->where("DATE_FORMAT(Fecha_ejecutado,'%Y-%m')", $Este_mes);
            $query = $this->db->get();
            $result = $query->result_array();
            
            foreach ($result as $monto) 
            {
                if($monto["Op"] == 1){  $Entrante_mes = $Entrante_mes + $monto["Monto_bruto"]; }
                else {                  $Saliente_mes = $Saliente_mes + $monto["Monto_bruto"]; }
                
            }
        
        /// FONDOS EN TRANSFERENCIAS DE ESTE AÑO
            $this->db->select('*');
            $this->db->from('tbl_dinero_transferencias');
            $this->db->where('Visible', 1);
            $this->db->where("DATE_FORMAT(Fecha_ejecutado,'%Y')", $Este_anio);
            $query = $this->db->get();
            $result = $query->result_array();
            
            foreach ($result as $monto) 
            {
                if($monto["Op"] == 1){  $Entrante_anio = $Entrante_anio + $monto["Monto_bruto"]; }
                else {                  $Saliente_anio = $Saliente_anio + $monto["Monto_bruto"]; }
            }
        
        /// FONDOS EN CHEQUES DE ESTE MES
            $this->db->select('*');
            $this->db->from('tbl_dinero_cheques');
            $this->db->where('Visible', 1);
            $this->db->where("DATE_FORMAT(Fecha_ejecutado,'%Y-%m')", $Este_mes);
            $query = $this->db->get();
            $result = $query->result_array();
            
            foreach ($result as $monto) 
            {
                if($monto["Op"] == 1){  $Entrante_mes = $Entrante_mes + $monto["Monto"]; }
                else {                  $Saliente_mes = $Saliente_mes + $monto["Monto"]; }
                
            }
        
        /// FONDOS EN CHEQUES DE ESTE AÑO
            $this->db->select('*');
            $this->db->from('tbl_dinero_cheques');
            $this->db->where('Visible', 1);
            $this->db->where("DATE_FORMAT(Fecha_ejecutado,'%Y')", $Este_anio);
            $query = $this->db->get();
            $result = $query->result_array();
            
            foreach ($result as $monto) 
            {
                if($monto["Op"] == 1){  $Entrante_anio = $Entrante_anio + $monto["Monto"]; }
                else {                  $Saliente_anio = $Saliente_anio + $monto["Monto"]; }
                
            }
            
        $Total_mes = $Entrante_mes - $Saliente_mes;
        $Total_anio = $Entrante_anio - $Saliente_anio;
        
        $Datos = array(
                        "Total_mes" => $Total_mes,  
                        "Entrantes_mes" => $Entrante_mes,
                        "Saliente_mes" => $Saliente_mes,  
                        "Total_anio" => $Total_anio,
                        "Entrante_anio" => $Entrante_anio,  
                        "Saliente_anio" => $Saliente_anio,
                        );

        echo json_encode($Datos);
    }

    /// LISTADO DE SEGUIMIENTO DE PERSONAL
    public function obtener_seguimiento_personal()
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

        $this->db->select(' tbl_usuarios_seguimiento.*,
                            tbl_usuarios.Nombre,
                            tbl_usuarios.Imagen,
                            tbl_usuarios.Id as Usuarios_id,
                            tbl_usuarios_seguimiento_categorias.Nombre_categoria');

        $this->db->from('tbl_usuarios_seguimiento');

        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_usuarios_seguimiento.Usuario_id', 'left');
        $this->db->join('tbl_usuarios_seguimiento_categorias', 'tbl_usuarios_seguimiento_categorias.Id = tbl_usuarios_seguimiento.Categoria_id', 'left');

        $this->db->order_by("tbl_usuarios_seguimiento.Fecha", "desc");
        $this->db->limit(25, 0); /// paginación

        //$this->db->where("DATE_FORMAT(tbl_usuarios_seguimiento.Fecha,'%Y-%m-%d')", $fecha_ayer);

        $this->db->where('tbl_usuarios_seguimiento.Visible', 1);

        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);

    }



//////
}
