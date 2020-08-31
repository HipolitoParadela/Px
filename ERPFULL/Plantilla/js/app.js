/// --- SETEANDO VARIABLES DE URL ----- //////
var pathname = window.location.pathname;
var carpeta = '/pxresto/' /// carpeta que hay q modificar segun cliente
var base_url = window.location.origin + carpeta
var URLactual = window.location.search;
var Get_Id = URLactual.slice(4); ///ID QUE VIENE POR URL

//// FUNCIONES  | Fecha actual
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();
    if (dd < 10) {
        dd = '0' + dd;
    }
    if (mm < 10) {
        mm = '0' + mm;
    }
    var hoy = dd + '/' + mm + '/' + yyyy;


///// AVERIGUAR COMO IMPORTAR ELEMENTOS

/// ELEMENTOS COMUNES PARA LA WEB
new Vue({
    el: '#app',

    created: function () 
    {
        /// CARGO FUNCIONES SEGUN EL SECCTOR QUE ESTE VISUALIZANDO - uso un if, pero tal vez un swich sea mejor en este caso
        if (pathname == carpeta + 'restaurant/itemscarta') 
        {
            this.getListadoItems();
            this.getListadoCategorias();
        }

        
    },

    data: 
    {
        Rol_usuario: '',

        buscar: '',

        texto_boton: "Cargar",

        listaUsuarios: [],
        usuario: { 'Id': '', 'Nombre': '', 'DNI': '', 'Pass': '', 'Rol_id': '', 'Imagen': '','Telefono': '', 'Observaciones': '', 'Presencia': '', 'Fecha_alta': '', 'Fecha_baja': '', 'Activo': 1 },
        usuarioFoto: { 'Id': '', 'Nombre': '', 'Imagen': '' },
        Archivo: '',

        listaRoles: [],
       
    },

    methods:
    {
        

        //// MOSTRAR LISTADO DE Usuarios  
            getListadoUsuarios: function (estado)
            {
                var url = base_url + 'restaurant/obtener_Usuarios/?estado='+estado; // url donde voy a mandar los datos

                axios.get(url).then(response => {
                    this.listaUsuarios = response.data
                });

                
            },

        //// LIMPIAR EL FORMULARIO DE CREAR Usuarios
            limpiarFormularioUsuarios() 
            {
                this.usuario={}
                this.texto_boton = "Cargar";
            },

        //// Carga el formulario Usuarios para editar
            editarFormulariousuario(usuario) 
            {
                //this.usuario = {};
                this.usuario = usuario;
                this.texto_boton = "Actualizar";
            },

        

        //// CREAR O EDITAR Usuarios  
            crearUsuarios: function () 
            {
                var url = base_url + 'restaurant/cargar_Usuarios'; // url donde voy a mandar los datos

                axios.post(url, {
                    usuarioData: this.usuario
                }).then(response => {

                    toastr.success('Proceso realizado correctamente', 'Usuarios')

                    this.usuario.Id = response.data.Id;
                    this.texto_boton = "Actualizar"

                    this.getListadoUsuarios(1);

                }).catch(error => {
                    alert("mal");
                });
            },

        //// ACTIVAR/DESACTIVAR USUARIOS    
            activarUsuario: function (usuario) 
            {
                var url = base_url + 'restaurant/cargar_Usuarios'; // url donde voy a mandar los datos

                if (usuario.Activo == 1) {
                    usuario.Activo = 0;
                }
                else {
                    usuario.Activo = 1;
                }

                axios.post(url, {
                    usuarioData: usuario
                }).then(response => {

                    toastr.success('Proceso realizado con éxito', 'Items Carta')

                    //this.usuario.Id = response.data.Id;
                    //this.texto_boton = "Actualizar"

                    this.getListadoUsuarios(1);

                }).catch(error => {
                    //toastr.error('Error al cargar el item'); // hacer funcionar esto, esta bueno

                    alert("mal");
                });
            },

        //// MOSTRAR LISTADO DE ROLES  
            getListadoRoles: function () {
                var url = base_url + 'restaurant/obtener_roles'; // url donde voy a mandar los datos

                axios.get(url).then(response => {
                    this.listaRoles = response.data
                });
            },
            

            
        //// FORMATO FECHA
            formatoFecha: function (fecha) 
            {
                return fecha.replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
            },

        //// FORMATO HORA
            formatoHora: function (hora) {
                separador = ":",
                    arrayHora = hora.split(separador);

                return arrayHora[0] + ':' + arrayHora[1] + 'hs'

            },
        //// CALCULANDO DIFERENCIA ENTRE DOS HORAS
            diferenciaTiempo: function (horaEnviada)  //// no funciona 
            {
                var f = new Date();
                var hora_actual_hora = f.getHours();
                var hora_actual_minuto =  f.getMinutes();
                    
                var separador = ":",
                horaEnviada = horaEnviada.split(separador);

                var horaEnviada_en_minutos = (parseInt(horaEnviada[0]) * 60) + parseInt(horaEnviada[1]);

                var hora_actual_en_minutos = (hora_actual_hora * 60) + hora_actual_minuto;

                var diferencia = hora_actual_en_minutos - horaEnviada_en_minutos;

                diferencia = diferencia / 60; 
                diferencia = diferencia.toString();
                
                var arr = diferencia.split(".");

                var horas = arr[0];

                var minutos = parseInt(arr[1]) * 100 / 60;
                
                minutos = minutos.toString();
                minutos = minutos.substr(0, 2)
                
                return horas + 'h ' + minutos + 'm'

             
            },

            
            upload(Id)
            {   
                //this.texto_boton = "Actualizar"
                var url = base_url + 'restaurant/subirFotoUsuario/?Id='+Id; // url donde voy a mandar los datos
                
                //const formData = event.target.files[0];
                const formData = new FormData();
                formData.append("Archivo", this.Archivo);
                
                formData.append('_method', 'PUT');
                
                //Enviamos la petición
                axios.post(url, formData)
                    .then(response => {
                        
                        ////DEBO HACER FUNCIONAR BIEN ESTO PARA QUE SE ACTUALICE LA FOTO QUE CARGO EN EL MOMENTO, SI NO PARECE Q NO SE CARGARA NADA
                            this.usuarioFoto.Imagen = response.data.Imagen;
                            this.getListadoUsuarios(1);
                            toastr.success('Proceso realizado correctamente', 'Usuarios')
                        

                    }).catch(error => {
                        alert("mal");

                    });
            },
        
        //// Carga el formulario Items para editar FOTO
            editarFormulariousuarioFoto(item) {
                this.usuarioFoto = item;
                this.texto_boton = "Actualizar";
            },   



        //// FORMATOS   | FORMATO Fecha Hora
            formatoFecha_hora: function (fecha) {
                //separador = ":",
                fecha = fecha.split(' ');

                //var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
                var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2');

                var fecha_hora = fecha[1].split(':');
                fecha_hora = fecha_hora[0] + ':' + fecha_hora[1];

                return fecha_hora + 'hs ' + fecha_dia

            },
        
        //// 
    },
    
    
    ////// ACCIONES COMPUTADAS     
    computed:
    {
        buscarItems: function()
        {
            return this.itemsCarta.filter((item) => item.Nombre.toLowerCase().includes(this.buscar));
        },

    }
});
///--------------
