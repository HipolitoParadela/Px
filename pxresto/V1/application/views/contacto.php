{
    "COOOOOÑAAAAA": "SIIII COOOÑAAAA",
    
    "results": [
        {
            "gender": "male",
            "name": {
                "title": "mr",
                "first": "romain",
                "last": "hoogmoed"
            },
            "location": {
                "street": "1861 jan pieterszoon coenstraat",
                "city": "maasdriel",
                "state": "zeeland",
                "postcode": 69217
            },
            "email": "romain.hoogmoed@example.com",
            "login": {
                "username": "lazyduck408",
                "password": "jokers",
                "salt": "UGtRFz4N",
                "md5": "6d83a8c084731ee73eb5f9398b923183",
                "sha1": "cb21097d8c430f2716538e365447910d90476f6e",
                "sha256": "5a9b09c86195b8d8b01ee219d7d9794e2abb6641a2351850c49c309f1fc204a0"
            },
            "dob": "1983-07-14 07:29:45",
            "registered": "2010-09-24 02:10:42",
            "phone": "(656)-976-4980",
            "cell": "(065)-247-9303",
            "id": {
                "name": "BSN",
                "value": "04242023"
            },
            "picture": {
                "large": "https://randomuser.me/api/portraits/men/83.jpg",
                "medium": "https://randomuser.me/api/portraits/med/men/83.jpg",
                "thumbnail": "https://randomuser.me/api/portraits/thumb/men/83.jpg"
            },
            "nat": "NL"
        }
    ],
    "info": {
        "seed": "2da87e9305069f1d",
        "results": 1,
        "page": 1,
        "version": "1.1"
    }
}



public function get_evaluacion_formacion()

     {
         //Esto siempre va es para instanciar la base de datos
         $CI =& get_instance();
         $CI->load->database();
         $token = @$CI->db->token;

         //este if es para controlar un valor de seguridad que siempre enviaremos desde la plantilla
         if ($this->input->post('token') == $token || $this->input->get('token') == $token) {

            $Formacion_Id = $this->input->post('Formacion_Id');
            $Usuario_Id = $this->input->post('Usuario_Id');

            $this->db->select('*');
            $this->db->from('tbl_formaciones_evaluacion');
            $this->db->where('Id_formacion', $Formacion_Id);
            $this->db->where('Usuario_Id', $Usuario_Id);
            $this->db->where('Estado', 1);
            //}
            $query = $this->db->get();
            $result = $query->result_array();
         }
         echo json_encode($result);
     }