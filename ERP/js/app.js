/// *********************************************************************************************/////
//// FILTROS
/// *******************************************

/// FECHA TIME STAMP
Vue.filter('FechaTimestamp', function (fecha) {
    fecha = fecha.split('T');

    //var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
    var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');

    var fecha_hora = fecha[1].split(':');
    fecha_hora = fecha_hora[0] + ':' + fecha_hora[1];

    //return fecha_dia + ' ' + fecha_hora + 'hs '
    return fecha_dia
})

/// FECHA TIME STAMP
Vue.filter('FechaTimeBD', function (fecha) {
    if (fecha != null) {
        fecha = fecha.split(' ');

        //var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
        var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');

        var fecha_hora = fecha[1].split(':');
        fecha_hora = fecha_hora[0] + ':' + fecha_hora[1];

        return fecha_dia + ' ' + fecha_hora + 'hs '
        //return fecha_dia
    }
    else {
        return 'No definida'
    }

})

/// FECHA TIME STAMP
Vue.filter('Fecha', function (fecha) {

    if (fecha != null) {
        return fecha.replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
    }
    else {
        return "Sin definir";
    }

})

/// FORMATO DINERO
Vue.filter('Moneda', function (numero) {
    /// PARA QUE FUNCIONE DEBE TOMAR EL NUMERO COMO UN STRING
    //si no lo es, lo convierte

    if (numero > 0 || numero != null) {
        //if (numero % 1 == 0) {
        numero = numero.toString();
        //}

        // Variable que contendra el resultado final
        var resultado = "";
        var nuevoNumero;

        // Si el numero empieza por el valor "-" (numero negativo)
        if (numero[0] == "-") {
            // Cogemos el numero eliminando los posibles puntos que tenga, y sin
            // el signo negativo
            nuevoNumero = numero.replace(/\./g, '').substring(1);
        } else {
            // Cogemos el numero eliminando los posibles puntos que tenga
            nuevoNumero = numero.replace(/\./g, '');
        }

        // Si tiene decimales, se los quitamos al numero
        if (numero.indexOf(",") >= 0)
            nuevoNumero = nuevoNumero.substring(0, nuevoNumero.indexOf(","));

        // Ponemos un punto cada 3 caracteres
        for (var j, i = nuevoNumero.length - 1, j = 0; i >= 0; i-- , j++)
            resultado = nuevoNumero.charAt(i) + ((j > 0) && (j % 3 == 0) ? "." : "") + resultado;

        // Si tiene decimales, se lo añadimos al numero una vez forateado con 
        // los separadores de miles
        if (numero.indexOf(",") >= 0)
            resultado += numero.substring(numero.indexOf(","));

        if (numero[0] == "-") {
            // Devolvemos el valor añadiendo al inicio el signo negativo
            return "-" + resultado;
        } else {
            return resultado;
        }
    }
    else {
        return 0
    }
})

/// ELEMENTOS COMUNES PARA LA WEB
new Vue({
    el: '#app',

    created: function () {
        /// CARGO FUNCIONES SEGUN EL SECCTOR QUE ESTE VISUALIZANDO - uso un if, pero tal vez un swich sea mejor en este caso


        if (pathname == carpeta + 'usuarios') {
            this.getListadoUsuarios(1);
            this.getListadoRoles();
            this.getListadoPuesto();
            this.getListadoEmpresas();
        }

        if (pathname == carpeta + 'curriculum/todos') {
            this.getListadoCurriculums();
            //this.getListadoRoles();
            this.getListadoPuesto();
            //this.getListadoEmpresas();
        }

        if (pathname == carpeta + 'curriculum') {
            this.getListadoCurriculumsTesteados();
            //this.getListadoRoles();
            this.getListadoPuesto();
            //this.getListadoEmpresas();
        }

        if (pathname == carpeta + 'stock') {
            this.getListadoCategorias();
            this.getListadoStock(0);
            this.getListadoOrdenes(0, 0, 0, 0);
        }

        if (pathname == carpeta + 'clientes') {
            this.getListadoClientes();
        }

        if (pathname == carpeta + 'proveedores') {
            this.getListadoProveedores();
        }

        if (pathname == carpeta + 'fabricacion') {
            this.getListadoCategoriasProductos();
            this.getListadoProductos(0);
        }

        if (pathname == carpeta + 'periodos') {
            this.getListadoPeriodos();
        }

        if (pathname == carpeta + 'ordentrabajo') {
            this.getListadoOrdenes(0, 0, 0, 0);
            this.getListadoUsuarios(1);
            this.getListadoProductos(0);
            this.getListadoClientes();
            this.getListadoPeriodos();
        }

        if (pathname == carpeta + 'compras') {
            this.getListadoCompras();
            this.getListadoProveedores();
            this.getListadoPeriodos();
        }
    },

    data:
    {
        Rol_usuario: '',

        buscar: '',

        texto_boton: "Cargar",
        filtro_puesto: "0",
        filtro_empresa: "0",
        filtro_sexo: '0',
        filtro_edad: '0',


        //Usuarios
        listaUsuarios: [],
        usuario: {
            'Id': '',
            'Nombre': '',
            'DNI': '',
            'Pass': '',
            'Rol_id': '',
            'Empresa_id': '',
            'Puesto_Id': '',
            'Imagen': '',
            'Telefono': '',
            'Fecha_nacimiento': '',
            'Domicilio': '',
            'Nacionalidad': '',
            'Genero': '',
            'Email': '',
            'Obra_social': '',
            'Numero_obra_social': '',
            'Hijos': '',
            'Estado_civil': '',
            'Datos_persona_contacto': '',
            'Datos_bancarios': '',
            'Periodo_liquidacion_sueldo': '',
            'Horario_laboral': '',
            'Superior_inmediato': '',
            'Telefono': '',
            'Observaciones': '',
            'Presencia': '1',
            'Fecha_alta': '',
            'Fecha_baja': '',
            'Activo': 1
        },

        usuarioFoto: { 'Id': '', 'Nombre': '', 'Imagen': '' },
        Archivo: '',
        preloader: '0',

        listaRoles: [],

        listaEmpresas: [],
        empresaDatos: { 'Id': '', 'Nombre_empresa': '', 'Descripcion': '' },

        listaPuestos: [],
        puesto: { 'Id': '', 'Nombre_puesto': '', 'Descripcion': '' },

        listaCurriculums: [],

        //Stock
        listaCategorias: [],
        categoriaDatos: { 'Id': '', 'Nombre_categoria': '', 'Descripcion': '' },
        filtro_categoria: '0',

        listaStock: [],
        stockDato: { 'Id': '', 'Nombre_item': '', 'Categoria_id': '', 'Descripcion': '', 'Cant_actual': '0', 'Cant_ideal': '', 'Ult_modificacion_id': '' },

        stockFoto: { 'Id': '', 'Nombre': '', 'Imagen': '' },

        cantMovimientoStock: [],
        descripcionMovimiento: [],

        egresoDato: {},

        // Clientes
        listaClientes: [],
        clienteDatos: { 'Id': '', 'Nombre_cliente': '', 'Producto_servicio': '', 'Direccion': '', 'Localidad': '', 'Provincia': '', 'Pais': '', 'Telefono': '', 'Email': '', 'Web': '', 'Nombre_persona_contacto': '', 'Datos_persona_contacto': '', 'Mas_datos_cliente': '' },
        clienteFoto: { 'Id': '', 'Nombre_cliente': '', 'Imagen': '' },

        // Proveedores
        listaProveedores: [],
        proveedorDatos: { 'Id': '', 'Nombre_proveedor': '', 'Producto_servicio': '', 'Direccion': '', 'Localidad': '', 'Provincia': '', 'Pais': '', 'Telefono': '', 'Email': '', 'Web': '', 'Nombre_persona_contacto': '', 'Datos_persona_contacto': '', 'Mas_datos_proveedor': '' },
        proveedorFoto: { 'Id': '', 'Nombre_proveedor': '', 'Imagen': '' },

        // Productos
        listaProductos: [],
        productoDatos: {},

        productoFoto: { 'Id': '', 'Nombre': '', 'Imagen': '' },

        // Ordenes
        listaOrdenes: [],
        filtro_usuario: '0',
        filtro_producto: '0',
        filtro_cliente: '0',
        filtro_estado: '0',
        ordenDatos: {},

        // Compras
        listaCompras: [],
        compraDatos: { 'Id': '', 'Proveedor_id': '', 'Fecha_compra': '', 'Factura_identificador': '', 'Valor': '', 'Descripcion': '' },
        compraFoto: { 'Id': '', 'Factura_identificador': '', 'Imagen': '' },

        // Periodos
        listaPeriodos: [],
        periodoDatos: {},
    },

    methods:
    {

        //// MOSTRAR LISTADO DE Usuarios  
        getListadoUsuarios: function (estado) {
            var url = base_url + 'usuarios/obtener_Usuarios/?estado=' + estado + '&empresa=' + this.filtro_empresa + '&puesto=' + this.filtro_puesto; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaUsuarios = response.data
            });


        },

        //// LIMPIAR EL FORMULARIO DE CREAR Usuarios
        limpiarFormularioUsuarios() {
            this.usuario = {}
            this.texto_boton = "Cargar";
        },

        //// Carga el formulario Usuarios para editar
        editarFormulariousuario(usuario) {
            //this.usuario = {};
            this.usuario = usuario;
            this.texto_boton = "Actualizar";
        },

        //// CREAR O EDITAR Usuarios  
        crearUsuarios: function () {
            var url = base_url + 'usuarios/cargar_Usuarios'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                usuarioData: this.usuario
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Usuarios')

                this.usuario.Id = response.data.Id;
                this.texto_boton = "Actualizar"

                this.getListadoUsuarios(1);

            }).catch(error => {
                alert("mal");
                console.log(error)
                toastr.success('Proceso realizado correctamente', 'Usuarios')
            });
        },

        //// Dasactivar usuario  
        desactivarUsuario: function (Id) {
            var url = base_url + 'usuarios/desactivar_usuario'; // url donde voy a mandar los datos

            var opcion = confirm("¿Esta seguro de eliminar a este usuario?");
            if (opcion == true) {
                axios.post(url, {
                    token: token,
                    Id: Id
                }).then(response => {

                    toastr.success('Proceso realizado correctamente', 'Usuarios')

                    this.getListadoUsuarios(1);

                }).catch(error => {
                    alert("mal");
                    console.log(error)

                });
            }
        },

        //// ACTIVAR/DESACTIVAR USUARIOS    
        activarUsuario: function (usuario) {
            var url = base_url + 'usuarios/cargar_Usuarios'; // url donde voy a mandar los datos

            if (usuario.Activo == 1) {
                usuario.Activo = 0;
            }
            else {
                usuario.Activo = 1;
            }

            axios.post(url, {
                token: token,
                usuarioData: usuario
            }).then(response => {

                toastr.success('Proceso realizado con éxito', 'Items Carta')

                //this.usuario.Id = response.data.Id;
                //this.texto_boton = "Actualizar"

                this.getListadoUsuarios(1);

            }).catch(error => {
                //toastr.error('Error al cargar el item'); // hacer funcionar esto, esta bueno

                alert("mal");
                console.log(error)
            });
        },

        upload(Id) {
            //this.texto_boton = "Actualizar"
            var url = base_url + 'usuarios/subirFotoUsuario/?Id=' + Id; // url donde voy a mandar los datos
            this.preloader = 1;
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

                    this.preloader = 0;
                }).catch(error => {
                    alert("mal");
                    console.log(error)
                    this.preloader = 0;
                });
        },

        //// Carga el formulario Items para editar FOTO
        editarFormulariousuarioFoto(item) {
            this.usuarioFoto = item;
            this.texto_boton = "Actualizar";
        },

        //// MOSTRAR LISTADO DE ROLES  
        getListadoRoles: function () {
            var url = base_url + 'usuarios/obtener_roles'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaRoles = response.data
            });
        },

        //// FORMATO FECHA
        formatoFecha: function (fecha) {
            if (fecha == null) {
                return 'No definida'
            }
            else {
                return fecha.replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
            }

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
            var hora_actual_minuto = f.getMinutes();

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

        //// SUMAR CUENTA   
        sumarCuenta: function (items) {

            /// SUMAR LOS ENTREGADOS
            var Total = 0;

            for (var i = 0; i < items.length; i++) {
                var item = 0;

                if (isFinite(items[i].Valor_cuenta)) {
                    item = parseInt(items[i].Valor_cuenta);
                }

                Total = Total + item;
            }
            return Total
        },

        //// SCRITPS PARA SUBIR FOTOS USUARIOS
        archivoSeleccionado(event) {
            this.Archivo = event.target.files[0]
            //this.texto_boton = "Actualizar"
        },

        //// FORMATOS   | FORMATO Fecha Hora
        formatoFecha_hora: function (fecha) {
            //separador = ":",
            fecha = fecha.split(' ');

            //var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
            var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');

            var fecha_hora = fecha[1].split(':');
            fecha_hora = fecha_hora[0] + ':' + fecha_hora[1];

            return fecha_dia + ' ' + fecha_hora + 'hs '

        },

        //// ELIMINAR USUARIO    
        eliminar: function (Id, tbl) {
            var url = base_url + 'Funcionescomunes/eliminar'; // url donde voy a mandar los datos

            //SOLICITANDO CONFIRMACIÓN PARA ELIMINAR
            var opcion = confirm("¿Esta seguro de eliminar a este contenido?");
            if (opcion == true) {

                axios.post(url, {
                    token: token,
                    Id: Id, tabla: tbl
                }).then(response => {

                    if (pathname == carpeta + 'compras') {
                        this.getListadoCompras();
                    }
                    else if (pathname == carpeta + 'usuarios') {
                        this.getListadoUsuarios(1);
                    }
                    else if (pathname == carpeta + 'clientes') {
                        this.getListadoClientes();
                    }

                    
                    toastr.success('Item eliminado correctamente', 'Sistema')

                }).catch(error => {
                    alert("mal");
                    console.log(error)
                });
            }
        },

        //// MOSTRAR LISTADO DE EMPRESAS  
        getListadoEmpresas: function () {
            var url = base_url + 'usuarios/obtener_empresas'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaEmpresas = response.data
            });
        },

        //// LIMPIAR EL FORMULARIO DE CREAR EMPRESAS
        limpiarFormularioEmpresa() {
            this.empresaDatos = {}
            this.texto_boton = "Cargar";
        },

        //// Carga el formulario EMPRESAS para editar
        editarFormularioEmpresa(Empresa) {
            //this.usuario = {};
            this.empresaDatos = Empresa;
            this.texto_boton = "Actualizar";
        },

        //// CREAR O EDITAR EMPRESAS
        crearEmpresa: function () {
            var url = base_url + 'usuarios/cargar_empresa'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Data: this.empresaDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Usuarios')

                this.empresaDatos.Id = response.data.Id;
                this.texto_boton = "Actualizar"

                this.getListadoEmpresas();

            }).catch(error => {
                alert("mal");
                console.log(error)

            });
        },

        //// MOSTRAR LISTADO DE puestos  
        getListadoPuesto: function () {
            var url = base_url + 'usuarios/obtener_puestos'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaPuestos = response.data
            });
        },

        //// LIMPIAR EL FORMULARIO DE CREAR puestos
        limpiarFormularioPuesto() {
            this.puesto = {}
            this.texto_boton = "Cargar";
        },

        //// Carga el formulario puesto para editar
        editarFormularioPuesto(Puesto) {
            //this.usuario = {};
            this.puesto = Puesto;
            this.texto_boton = "Actualizar";
        },

        //// CREAR O EDITAR puesto
        crearPuesto: function () {
            var url = base_url + 'usuarios/cargar_puesto'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Data: this.puesto
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Usuarios')

                this.puesto.Id = response.data.Id;
                this.texto_boton = "Actualizar"

                this.getListadoPuesto();

            }).catch(error => {
                alert("mal");
                console.log(error)

            });
        },

        //// CURRICULUM | MOSTRAR LISTADO
        getListadoCurriculums: function () {
            var url = base_url + 'curriculum/obtener_curriculums/?edad=' + this.filtro_edad + '&sexo=' + this.filtro_sexo; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaCurriculums = response.data
            });
        },

        //// CURRICULUM | MOSTRAR TESTEADOS
        getListadoCurriculumsTesteados: function () {
            var url = base_url + 'curriculum/getListadoCurriculumsTesteados/?edad=' + this.filtro_edad + '&sexo=' + this.filtro_sexo + '&puesto=' + this.filtro_puesto; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaCurriculums = response.data

                console.log(this.listaCurriculums)
            });
        },

        //// CURRICULUM | Dasactivar testeados
        desactivarTesteado: function (Id) {
            var url = base_url + 'curriculum/desactivar'; // url donde voy a mandar los datos

            var opcion = confirm("¿Esta seguro de eliminar a este curriculum?");
            if (opcion == true) {
                axios.post(url, {
                    token: token,
                    Id: Id
                }).then(response => {

                    this.getListadoCurriculumsTesteados(); // las funciones son iguales solo cambia esto
                    toastr.success('Proceso realizado correctamente', 'Usuarios')

                }).catch(error => {
                    alert("mal");
                    console.log(error)
                });
            }
        },

        //// CURRICULUM | Dasactivar en la lista de todos
        desactivar: function (Id) {
            var url = base_url + 'curriculum/desactivar'; // url donde voy a mandar los datos

            var opcion = confirm("¿Esta seguro de eliminar a este curriculum?");
            if (opcion == true) {
                axios.post(url, {
                    token: token,
                    Id: Id
                }).then(response => {

                    this.getListadoCurriculums(); // las funciones son iguales solo cambia esto
                    toastr.success('Proceso realizado correctamente', 'Usuarios')

                }).catch(error => {
                    alert("mal");
                    console.log(error)

                });
            }
        },


        //// STOCK |  MOSTRAR LISTADO DE CATEGORIAS
        getListadoCategorias: function () {
            var url = base_url + 'stock/obtener_categorias'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaCategorias = response.data
            });
        },

        //// STOCK |  LIMPIAR EL FORMULARIO DE CREAR CATEGORIAS
        limpiarFormularioCategoria() {
            this.categoriaDatos = {}
            this.texto_boton = "Cargar";
        },

        //// STOCK |  Carga el formulario CATEGORIAS para editar
        editarFormularioCategoria(categoria) {
            //this.usuario = {};
            this.categoriaDatos = categoria;
            this.texto_boton = "Actualizar";
        },

        //// STOCK | CREAR O EDITAR CATEGORIA
        crearCategoria: function () {
            var url = base_url + 'stock/cargar_categoria'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Data: this.categoriaDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'stock')

                this.categoriaDatos.Id = response.data.Id;
                //this.texto_boton = "Actualizar"
                this.categoriaDatos = {}

                this.getListadoCategorias();

            }).catch(error => {
                alert("mal");
                console.log(error)

            });
        },

        //// STOCK |  MOSTRAR LISTADO DE CATEGORIAS
        getListadoStock: function (categoria) {
            var url = base_url + 'stock/obtener_listado_de_stock?categoria=' + categoria; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaStock = response.data
            });
        },

        //// STOCK | CREAR O EDITAR ITEM
        crearStock: function () {
            var url = base_url + 'stock/cargar_stock_item'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Data: this.stockDato
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'stock')

                this.stockDato.Id = response.data.Id;
                //this.texto_boton = "Actualizar"
                this.stockDato = {}

                this.getListadoStock(0);

            }).catch(error => {
                alert("mal");
                console.log(error)

            });
        },

        //// STOCK |  LIMPIAR EL FORMULARIO DE CREAR
        limpiarFormularioStock() {
            this.stockDato = {}
            this.texto_boton = "Cargar";
        },

        //// STOCK |  Carga el formulario para editar
        editarFormulariostock(item) {
            //this.usuario = {};
            this.stockDato = item;
            this.texto_boton = "Actualizar";
        },

        ///// STOCK |   CARGAR IMAGEN DE STOCK
        upload_foto_stock(Id) {
            //this.texto_boton = "Actualizar"
            var url = base_url + 'stock/subirFotoStock/?Id=' + Id; // url donde voy a mandar los datos
            this.preloader = 1;
            //const formData = event.target.files[0];
            const formData = new FormData();
            formData.append("Archivo", this.Archivo);

            formData.append('_method', 'PUT');

            //Enviamos la petición
            axios.post(url, formData)
                .then(response => {

                    ////DEBO HACER FUNCIONAR BIEN ESTO PARA QUE SE ACTUALICE LA FOTO QUE CARGO EN EL MOMENTO, SI NO PARECE Q NO SE CARGARA NADA
                    this.stockFoto.Imagen = response.data.Imagen;
                    this.getListadoStock(0);
                    toastr.success('Proceso realizado correctamente', 'Usuarios')

                    this.preloader = 0;
                }).catch(error => {
                    alert("mal");
                    console.log(error)
                    this.preloader = 0;
                });
        },

        //// STOCK |     Carga el formulario Items para editar FOTO
        editarFormularioItemFoto(item) {
            this.stockFoto = item;
            this.texto_boton = "Actualizar";
        },

        //// STOCK | CREAR O EDITAR -- solo servirá para actualizar egresos, los ingresos se harán desde compras nada más
        movimientoStock: function (id, cantidad, descripcion) {
            var url = base_url + 'stock/cargar_movimiento'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Id: id,
                Cantidad: cantidad,
                Descripcion: descripcion,
                Proceso_id: null,
                Tipo_movimiento: null
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'stock')

                this.getListadoStock(0);
                this.cantMovimientoStock = [];
                this.descripcionMovimiento = []

            }).catch(error => {
                alert("mal");
                console.log(error.response.data)

            });
        },

        editarStockProducto: function (item) {
            this.egresoDato = item;
        },

        //// STOCK | CREAR O EDITAR -- solo servirá para actualizar egresos, los ingresos se harán desde compras nada más
        movimientoStock_v2: function () {
            var url = base_url + 'stock/cargar_movimiento'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Id: this.egresoDato.Id,
                Cantidad: this.egresoDato.Cantidad,
                Descripcion: this.egresoDato.Descripcion_egreso,
                Proceso_id: this.egresoDato.Orden_trabajo_id,
                Tipo_movimiento: 2
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Stock')

                this.getListadoStock(0);

            }).catch(error => {
                alert("mal");
                console.log(error.response.data)

            });
        },

        /// STOCK  | COLOREAR ITEMS BAJOS DE STOCK
        classAlertaStock: function (actual, ideal) {
            var Valor_actual = parseInt(actual);
            var Valor_ideal = parseInt(ideal);

            if (Valor_actual < Valor_ideal) {
                return 'text-danger'
            }
            else {
                return ''
            }
        },

        //// STOCK  | Dasactivar un item
        desactivarStock: function (Id) {
            var url = base_url + 'stock/desactivar_producto'; // url donde voy a mandar los datos

            var opcion = confirm("¿Esta seguro de eliminar a este producto?");
            if (opcion == true) {
                axios.post(url, {
                    token: token,
                    Id: Id
                }).then(response => {

                    toastr.success('Proceso realizado correctamente', 'Proveedores')

                    this.getListadoStock(0);

                }).catch(error => {
                    alert("mal");
                    console.log(error)

                });
            }
        },


        //// CLIENTES  | MOSTRAR LISTADO
        getListadoClientes: function (estado) {
            var url = base_url + 'clientes/obtener_clientes'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaClientes = response.data
            });
        },

        //// CLIENTES  | LIMPIAR EL FORMULARIO DE CREAR
        limpiarFormularioClientes() {
            this.clienteDatos = {}
            this.texto_boton = "Cargar";
        },

        //// CLIENTES  | Carga el formulario  para editar
        editarFormularioCliente(clienteDatos) {
            //this.usuario = {};
            this.clienteDatos = clienteDatos;
            this.texto_boton = "Actualizar";
        },

        //// CLIENTES  | CREAR O EDITAR 
        crearCliente: function () {
            var url = base_url + 'clientes/cargar_cliente'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.clienteDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Usuarios')

                this.clienteDatos.Id = response.data.Id;
                this.texto_boton = "Actualizar"

                this.getListadoClientes();

            }).catch(error => {
                alert("mal");
                console.log(error)
                toastr.success('Proceso realizado correctamente', 'Usuarios')
            });
        },

        //// CLIENTES  | Dasactivar
        desactivarCliente: function (Id) {
            var url = base_url + 'clientes/desactivar_cliente'; // url donde voy a mandar los datos

            var opcion = confirm("¿Esta seguro de eliminar a este cliente?");
            if (opcion == true) {
                axios.post(url, {
                    token: token,
                    Id: Id
                }).then(response => {

                    toastr.success('Proceso realizado correctamente', 'Clientes')

                    this.getListadoClientes();

                }).catch(error => {
                    alert("mal");
                    console.log(error)

                });
            }
        },

        //// CLIENTES  | Subir imagen
        uploadCliente(Id) {
            //this.texto_boton = "Actualizar"
            var url = base_url + 'clientes/subirFoto/?Id=' + Id; // url donde voy a mandar los datos
            this.preloader = 1;
            //const formData = event.target.files[0];
            const formData = new FormData();
            formData.append("Archivo", this.Archivo);

            formData.append('_method', 'PUT');

            //Enviamos la petición
            axios.post(url, formData)
                .then(response => {

                    ////DEBO HACER FUNCIONAR BIEN ESTO PARA QUE SE ACTUALICE LA FOTO QUE CARGO EN EL MOMENTO, SI NO PARECE Q NO SE CARGARA NADA
                    this.clienteFoto.Imagen = response.data.Imagen;
                    this.getListadoClientes();
                    toastr.success('Proceso realizado correctamente', 'Usuarios')

                    this.preloader = 0;
                }).catch(error => {
                    alert("mal");
                    console.log(error)
                    this.preloader = 0;
                });
        },

        //// CLIENTES  |  Carga el formulario Items para editar FOTO
        editarFormularioClienteFoto(item) {
            this.clienteFoto = item;
            this.texto_boton = "Actualizar";
        },


        //// PROVEEDORES  | MOSTRAR LISTADO
        getListadoProveedores: function (estado) {
            var url = base_url + 'proveedores/obtener_proveedores'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaProveedores = response.data
            });
        },

        //// PROVEEDORES  | LIMPIAR EL FORMULARIO DE CREAR
        limpiarFormularioProveedor() {
            this.proveedorDatos = {}
            this.texto_boton = "Cargar";
        },

        //// PROVEEDORES  | Carga el formulario  para editar
        editarFormularioProveedor(proveedorDatos) {
            //this.usuario = {};
            this.proveedorDatos = proveedorDatos;
            this.texto_boton = "Actualizar";
        },

        //// PROVEEDORES  | CREAR O EDITAR 
        crearProveedor: function () {
            var url = base_url + 'proveedores/cargar_proveedor'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.proveedorDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Proveedores')

                this.proveedorDatos.Id = response.data.Id;
                this.texto_boton = "Actualizar"

                this.getListadoProveedores();

            }).catch(error => {
                alert("mal");
                console.log(error)
                toastr.success('Proceso realizado correctamente', 'Proveedores')
            });
        },

        //// PROVEEDORES  | Dasactivar
        desactivarProveedor: function (Id) {
            var url = base_url + 'proveedores/desactivar_proveedor'; // url donde voy a mandar los datos

            var opcion = confirm("¿Esta seguro de eliminar a este proveedor?");
            if (opcion == true) {
                axios.post(url, {
                    token: token,
                    Id: Id
                }).then(response => {

                    toastr.success('Proceso realizado correctamente', 'Proveedores')

                    this.getListadoProveedores();

                }).catch(error => {
                    alert("mal");
                    console.log(error)

                });
            }
        },

        //// PROVEEDORES  | Subir imagen
        uploadProveedor(Id) {
            //this.texto_boton = "Actualizar"
            var url = base_url + 'proveedores/subirFoto/?Id=' + Id; // url donde voy a mandar los datos
            this.preloader = 1;
            //const formData = event.target.files[0];
            const formData = new FormData();
            formData.append("Archivo", this.Archivo);

            formData.append('_method', 'PUT');

            //Enviamos la petición
            axios.post(url, formData)
                .then(response => {

                    ////DEBO HACER FUNCIONAR BIEN ESTO PARA QUE SE ACTUALICE LA FOTO QUE CARGO EN EL MOMENTO, SI NO PARECE Q NO SE CARGARA NADA
                    this.proveedorFoto.Imagen = response.data.Imagen;
                    this.getListadoProveedores();
                    toastr.success('Proceso realizado correctamente', 'Proveedores')

                    this.preloader = 0;
                }).catch(error => {
                    alert("mal");
                    console.log(error)
                    this.preloader = 0;
                });
        },

        //// PROVEEDORES  | Carga el formulario Items para editar FOTO
        editarFormularioProveedorFoto(item) {
            this.proveedorFoto = item;
            this.texto_boton = "Actualizar";
        },


        //// FABRICACION |  MOSTRAR LISTADO DE CATEGORIAS
        getListadoCategoriasProductos: function () {
            var url = base_url + 'fabricacion/obtener_categorias'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaCategorias = response.data
            });
        },

        //// FABRICACION |  LIMPIAR EL FORMULARIO DE CREAR CATEGORIAS
        limpiarFormularioCategoriaProductos() {
            this.categoriaDatos = {}
            this.texto_boton = "Cargar";
        },

        //// FABRICACION |  Carga el formulario CATEGORIAS para editar
        editarFormularioCategoriaProductos(categoria) {
            //this.usuario = {};
            this.categoriaDatos = categoria;
            this.texto_boton = "Actualizar";
        },

        //// FABRICACION | CREAR O EDITAR CATEGORIA
        crearCategoriaProductos: function () {
            var url = base_url + 'fabricacion/cargar_categoria'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Data: this.categoriaDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'stock')

                this.categoriaDatos.Id = response.data.Id;
                //this.texto_boton = "Actualizar"
                this.categoriaDatos = {}

                this.getListadoCategoriasProductos();

            }).catch(error => {
                alert("mal");
                console.log(error)

            });
        },

        //// FABRICACION |  MOSTRAR LISTADO DE CATEGORIAS
        getListadoProductos: function (categoria) {
            var url = base_url + 'fabricacion/obtener_listado_de_productos?categoria=' + categoria; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaProductos = response.data
            });
        },

        //// FABRICACION | CREAR O EDITAR ITEM
        crearProducto: function () {
            var url = base_url + 'fabricacion/cargar_producto'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Data: this.productoDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Fabricación')

                this.productoDatos.Id = response.data.Id;
                this.texto_boton = "Actualizar"

                this.getListadoProductos(0);

            }).catch(error => {
                alert("mal");
                console.log(error)

            });
        },

        //// FABRICACION |  LIMPIAR EL FORMULARIO DE CREAR
        limpiarFormularioProveedor() {
            this.productoDatos = {}
            this.texto_boton = "Cargar";
        },

        //// FABRICACION |  Carga el formulario para editar
        editarFormularioProducto(item) {
            //this.usuario = {};
            this.productoDatos = item;
            this.texto_boton = "Actualizar";
        },

        ///// FABRICACION |   CARGAR IMAGEN DE PRODUCTO DE FABRICACION
        uploadFotoProducto(Id) {
            //this.texto_boton = "Actualizar"
            var url = base_url + 'fabricacion/subirFotoProducto/?Id=' + Id; // url donde voy a mandar los datos
            this.preloader = 1;
            //const formData = event.target.files[0];
            const formData = new FormData();
            formData.append("Archivo", this.Archivo);

            formData.append('_method', 'PUT');

            //Enviamos la petición
            axios.post(url, formData)
                .then(response => {

                    ////DEBO HACER FUNCIONAR BIEN ESTO PARA QUE SE ACTUALICE LA FOTO QUE CARGO EN EL MOMENTO, SI NO PARECE Q NO SE CARGARA NADA
                    this.productoFoto.Imagen = response.data.Imagen;
                    this.getListadoProductos(0);
                    toastr.success('Proceso realizado correctamente', 'Fabricación')

                    this.preloader = 0;
                }).catch(error => {
                    alert("mal");
                    console.log(error)
                    this.preloader = 0;
                });
        },

        //// FABRICACION |     Carga el formulario Items para editar FOTO
        editarFormularioProductoFoto(item) {
            this.productoFoto = item;
            this.texto_boton = "Actualizar";
        },

        //// FABRICACION  | Dasactivar un item
        desactivarProducto: function (Id) {
            var url = base_url + 'fabricacion/desactivar_producto'; // url donde voy a mandar los datos

            var opcion = confirm("¿Esta seguro de eliminar a este producto?");
            if (opcion == true) {
                axios.post(url, {
                    token: token,
                    Id: Id
                }).then(response => {

                    toastr.success('Proceso realizado correctamente', 'Productos')

                    this.getListadoProductos(0);

                }).catch(error => {
                    alert("mal");
                    console.log(error)

                });
            }
        },



        //// ORDEN TRABAJO |  MOSTRAR LISTADO DE ORDENES
        getListadoOrdenes: function (Usuario_id, Producto_id, Cliente_id, Estado) {
            var url = base_url + 'ordentrabajo/obtener_listado_ordenes?Producto_id=' + Producto_id + '&Usuario_id=' + Usuario_id + '&Cliente_id=' + Cliente_id + '&Estado=' + Estado; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaOrdenes = response.data
            }).catch(error => {
                alert("mal");
                console.log(error.response.data)

            });
        },

        //// ORDEN TRABAJO | CREAR O EDITAR ITEM
        crearOrden: function () {
            var url = base_url + 'ordentrabajo/cargar_orden'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Data: this.ordenDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Fabricación')

                this.ordenDatos.Id = response.data.Id;
                this.texto_boton = "Actualizar"

                this.getListadoOrdenes(0, 0, 0, 0);

            }).catch(error => {
                alert("mal");
                console.log(error)
            });
        },

        //// ORDEN TRABAJO |  LIMPIAR EL FORMULARIO DE CREAR
        limpiarFormularioOrden() {
            this.ordenDatos = {}
            this.texto_boton = "Cargar";
        },

        //// ORDEN TRABAJO |  Carga el formulario para editar
        editarFormularioOrden(item) {
            this.ordenDatos = item;
            this.texto_boton = "Actualizar";
        },

        //// ORDEN TRABAJO  | Dasactivar un item
        desactivarOrden: function (Id) {
            var url = base_url + 'ordentrabajo/desactivar_Orden'; // url donde voy a mandar los datos

            var opcion = confirm("¿Esta seguro de eliminar a este Orden?");
            if (opcion == true) {
                axios.post(url, {
                    token: token,
                    Id: Id
                }).then(response => {

                    toastr.success('Proceso realizado correctamente', 'Ordens')

                    this.getListadoOrdenes(this.filtro_usuario, this.filtro_producto, this.filtro_cliente, this.filtro_estado);

                }).catch(error => {
                    alert("mal");
                    console.log(error)
                });
            }
        },

        //// ORDEN TRABAJO  | CANTIDAD DE DIAS ENTRE DOS FECHAS
        diferenciasEntre_fechas: function (fechaInicio, fechaFin) {

            if (fechaFin == null) { fechaFin = hoy_php }
            if (fechaInicio == null) { fechaInicio = hoy_php }

            var fechaInicio = new Date(fechaInicio).getTime();
            var fechaFin = new Date(fechaFin).getTime();
            var diff = fechaFin - fechaInicio;

            /// debe devolver mensajes completos
            diff = diff / (1000 * 60 * 60 * 24)

            if (diff < 0) {
                diff = diff * parseInt(-1)
                return diff + ' días de atrazo'
            }
            else {
                return diff + ' días'
            }
        },



        //// COMPRAS  | MOSTRAR LISTADO
        getListadoCompras: function (estado) {
            var url = base_url + 'compras/obtener_compras'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaCompras = response.data
            });
        },

        //// COMPRAS  | LIMPIAR EL FORMULARIO DE CREAR
        limpiarFormularioCompras() {
            this.compraDatos = {}
            this.texto_boton = "Cargar";
        },

        //// COMPRAS  | Carga el formulario  para editar
        editarFormularioCompra(compraDatos) {
            //this.usuario = {};
            this.compraDatos = compraDatos;
            this.texto_boton = "Actualizar";
        },

        //// COMPRAS  | CREAR O EDITAR 
        crearCompra: function () {
            var url = base_url + 'compras/cargar_compra'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.compraDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Compras')

                this.compraDatos.Id = response.data.Id;
                this.texto_boton = "Actualizar"

                this.getListadoCompras();

            }).catch(error => {
                alert("mal");
                console.log(error)
                toastr.success('Proceso realizado correctamente', 'Usuarios')
            });
        },

        //// COMPRAS  | Dasactivar
        desactivarCompra: function (Id) {
            var url = base_url + 'compras/desactivar_compra'; // url donde voy a mandar los datos

            var opcion = confirm("¿Esta seguro de eliminar a este compra?");
            if (opcion == true) {
                axios.post(url, {
                    token: token,
                    Id: Id
                }).then(response => {

                    toastr.success('Proceso realizado correctamente', 'Compras')

                    this.getListadoCompras();

                }).catch(error => {
                    alert("mal");
                    console.log(error)

                });
            }
        },

        //// COMPRAS  | Subir imagen
        subirFotoCompra(Id) {
            //this.texto_boton = "Actualizar"
            var url = base_url + 'compras/subirFoto/?Id=' + Id; // url donde voy a mandar los datos
            this.preloader = 1;
            //const formData = event.target.files[0];
            const formData = new FormData();
            formData.append("Archivo", this.Archivo);

            formData.append('_method', 'PUT');

            //Enviamos la petición
            axios.post(url, formData)
                .then(response => {

                    ////DEBO HACER FUNCIONAR BIEN ESTO PARA QUE SE ACTUALICE LA FOTO QUE CARGO EN EL MOMENTO, SI NO PARECE Q NO SE CARGARA NADA
                    this.compraFoto.Imagen = response.data.Imagen;
                    this.getListadoCompras();
                    toastr.success('Proceso realizado correctamente', 'Compras')

                    this.preloader = 0;
                }).catch(error => {
                    alert("mal");
                    console.log(error)
                    this.preloader = 0;
                });
        },

        //// COMPRAS  |  Carga el formulario Items para editar FOTO
        editarFormulariocompraFoto(item) {
            this.compraFoto = item;
            this.texto_boton = "Actualizar";
        },


        //// PERIODOS |  MOSTRAR LISTADO
        getListadoPeriodos: function () {
            var url = base_url + 'periodos/obtener_periodos'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaPeriodos = response.data
            }).catch(error => {
                alert("mal");
                console.log(error.response.data)

            });
        },

        //// PERIODOS | CREAR O EDITAR ITEM
        crearPeriodo: function () {
            var url = base_url + 'periodos/crear_periodo'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.periodoDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Periodos')

                this.periodoDatos.Id = response.data.Id;
                this.texto_boton = "Actualizar"

                this.getListadoPeriodos();

            }).catch(error => {
                alert("mal");
                console.log(error)
            });
        },

        //// PERIODOS |  LIMPIAR EL FORMULARIO DE CREAR
        limpiarFormularioPeriodo() {
            this.periodoDatos = {}
            this.texto_boton = "Cargar";
        },

        //// PERIODOS |  Carga el formulario para editar
        editarFormularioPeriodo(item) {
            this.periodoDatos = item;
            this.texto_boton = "Actualizar";
        },

        //// 
    },

    ////// ACCIONES COMPUTADAS     
    computed:
    {
        buscarUsuarios: function () {
            return this.listaUsuarios.filter((item) => item.Nombre.toLowerCase().includes(this.buscar));
        },

        buscarCurriculums: function () {
            return this.listaCurriculums.filter((item) => item.nombre.toLowerCase().includes(this.buscar));
        },

        buscarStock: function () {
            return this.listaStock.filter((item) => item.Nombre_item.toLowerCase().includes(this.buscar));
        },

        buscarCliente: function () {
            return this.listaClientes.filter((item) => item.Nombre_cliente.toLowerCase().includes(this.buscar));
        },

        buscarProveedor: function () {
            return this.listaProveedores.filter((item) => item.Nombre_proveedor.toLowerCase().includes(this.buscar));
        },

        buscarProducto: function () {
            return this.listaProductos.filter((item) => item.Nombre_producto.toLowerCase().includes(this.buscar));
        },

    }
});
///--------------

///         --------------------------------------------------------------------   ////
//// Elemento para del Dashboard
new Vue({
    el: '#dashboard',

    created: function () {

        this.obtener_cantidad_de_items_stoqueados();
        this.obtenerCantidadUsuarios();
        this.getListadoMovimientos();
        this.getListadoCompras();
        this.getListadoOrdenes();
        this.obtener_cantOrdenesTrabajo();
        this.obtener_cantCompras();
        this.obtener_cantProductosPropios();


        //setInterval(() => { this.getTimeline(0, null); }, 60000); //// funcion para actualizar automaticamente cada 1minuto
    },

    data: {

        cantItemsStock: '0',
        cantidadUsuarios: '0',
        cantOrdenesTrabajo: '0',
        cantCompras: '0',
        listaMovimientos: [],
        listaCompras: [],
        listaOrdenes: [],
        cantProductosPropios: '0',
    },

    methods:
    {

        ////CANTIDAD DE CURRICULUM DE ESTE MES    
        obtener_cantidad_de_items_stoqueados: function () {
            var url = base_url + 'dashboard/obtener_cantidad_de_items_stoqueados'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.cantItemsStock = response.data
            });
        },

        ////CANTIDAD DE CURRICULUM DE ESTE MES    
        obtener_cantOrdenesTrabajo: function () {
            var url = base_url + 'dashboard/obtener_cantOrdenesTrabajo'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.cantOrdenesTrabajo = response.data
            });
        },

        ////CANTIDAD DE CURRICULUM DE ESTE MES    
        obtener_cantCompras: function () {
            var url = base_url + 'dashboard/obtener_cantCompras'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.cantCompras = response.data
            });
        },

        ////CANTIDAD DE CURRICULUM DE ESTE MES    
        obtener_cantProductosPropios: function () {
            var url = base_url + 'dashboard/obtener_productosPropios'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.cantProductosPropios = response.data
            });
        },

        ////CANTIDAD DE USUARIOS TOTALES 
        obtenerCantidadUsuarios: function () {
            var url = base_url + 'dashboard/obtener_cantidad_usuarios'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.cantidadUsuarios = response.data
            });
        },

        //// FORMATO FECHA HORA
        formatoFecha_hora: function (fecha) {
            //separador = ":",
            fecha = fecha.split(' ');

            //var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
            var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');

            var fecha_hora = fecha[1].split(':');
            fecha_hora = fecha_hora[0] + ':' + fecha_hora[1];

            return fecha_dia + ' ' + fecha_hora + 'hs '

        },

        //// MOSTRAR LISTADO DE ROLES  
        getListadoMovimientos: function () {
            var url = base_url + 'stock/obtener_ultimos_movimientos'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaMovimientos = response.data
            });
        },

        //// MENSAJE DE LOS INGRESOS A CAJA
        mensaje: function () {
            toastr.warning('Este valor refleja los ingresos en bruto a caja durante el período indicado. Surge del total de las ventas y solo se le han restado los descuentos realizados en la cuenta de cada comanda y de cada delivery.', 'IMPORTANTE')
        },

        //// MOSTRAR LISTADO DE COMPRAS 
        getListadoCompras: function () {
            var url = base_url + 'compras/obtener_compras_dashboarad'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaCompras = response.data
            });
        },

        //// FORMATO FECHA
        formatoFecha: function (fecha) {
            if (fecha == null) {
                return 'No definida'
            }
            else {
                return fecha.replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
            }
        },

        //// ORDEN TRABAJO  | CANTIDAD DE DIAS ENTRE DOS FECHAS
        diferenciasEntre_fechas: function (fechaInicio, fechaFin) {

            if (fechaFin == null) { fechaFin = hoy_php }
            if (fechaInicio == null) { fechaInicio = hoy_php }

            var fechaInicio = new Date(fechaInicio).getTime();
            var fechaFin = new Date(fechaFin).getTime();
            var diff = fechaFin - fechaInicio;

            /// debe devolver mensajes completos
            diff = diff / (1000 * 60 * 60 * 24)

            if (diff < 0) {
                diff = diff * parseInt(-1)
                return diff + ' días de atrazo'
            }
            else {
                return diff + ' días'
            }
        },

        //// ORDEN TRABAJO |  MOSTRAR LISTADO DE ORDENES
        getListadoOrdenes: function () {
            var url = base_url + 'ordentrabajo/obtener_listado_ordenes_dashboard'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaOrdenes = response.data
            }).catch(error => {
                alert("mal");
                console.log(error.response.data)

            });
        },
        //////
    }
});


///         --------------------------------------------------------------------   ////
//// Elemento para el manejo de usuarios por Id
new Vue({
    el: '#usuarios',

    created: function () {
        this.getDatosUsuario();
        this.getListadoRoles();
        this.getFormaciones();
        this.getSuperiores();
        this.getListadoEmpresas();
        this.getListadoPuesto();
    },

    data: {

        mostrar: "1",

        usuario: {
            'Id': '',
            'Nombre': '',
            'DNI': '',
            'Pass': '',
            'Rol_id': '',
            'Imagen': '',
            'Telefono': '',
            'Fecha_nacimiento': '',
            'Domicilio': '',
            'Nacionalidad': '',
            'Genero': '',
            'Email': '',
            'Obra_social': '',
            'Numero_obra_social': '',
            'Hijos': '',
            'Estado_civil': '',
            'Datos_persona_contacto': '',
            'Datos_bancarios': '',
            'Periodo_liquidacion_sueldo': '',
            'Horario_laboral': '',
            'Superior_inmediato': '',
            'Telefono': '',
            'Observaciones': '',
            'Presencia': '1',
            'Fecha_alta': '',
            'Fecha_baja': '',
            'Activo': 1
        },

        usuarioFoto: { 'Id': '', 'Nombre': '', 'Imagen': '' },
        Archivo: '',

        listaSuperiores: [],

        listaRoles: [],
        texto_boton: "Cargar",

        listaFormaciones: [],
        formacionData: { 'Id': '', 'Titulo': '', 'Establecimiento': '', 'Anio_inicio': '', 'Anio_finalizado': '', 'Descripcion_titulo': '' },

        listaEmpresas: [],
        listaPuestos: [],
    },

    methods:
    {

        //// MOSTRAR LISTADO DE ROLES  
        getListadoRoles: function () {
            var url = base_url + 'usuarios/obtener_roles'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaRoles = response.data
            });
        },

        //// OBTENER DATOS DE UN USUARIO 
        getDatosUsuario: function () {
            var url = base_url + 'usuarios/obtener_Usuario/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.usuario = response.data[0]
            });
        },

        //// OBTENER USUARIOS LIDERES
        getSuperiores: function () {
            var url = base_url + 'usuarios/obtener_lideres';  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaSuperiores = response.data


            });
        },

        //// CREAR O EDITA un Usuario 
        crearUsuarios: function () {
            var url = base_url + 'usuarios/cargar_Usuarios'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                usuarioData: this.usuario
            }).then(response => {

                toastr.success('Datos actualizados correctamente', 'Usuarios')

                this.usuario.Id = response.data.Id;
                this.texto_boton = "Actualizar"

            }).catch(error => {
                alert("mal");
                console.log(error)
            });
        },

        //// FORMATO FECHA
        formatoFecha: function (fecha) {
            return fecha.replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
        },

        //// CREAR O EDITAR una formación
        crearFormacion: function () {
            var url = base_url + 'usuarios/cargar_formacion'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                formacionData: this.formacionData, Usuario_id: Get_Id
            }).then(response => {

                toastr.success('Datos actualizados correctamente', 'Usuarios')

                this.formacionData.Id = response.data.Id;
                this.texto_boton = "Actualizar"
                this.getFormaciones();

            }).catch(error => {
                alert(response.data);
            });
        },

        //// MOSTRAR LISTADO DE FORMACIONES
        getFormaciones: function () {
            var url = base_url + 'usuarios/obtener_formaciones/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaFormaciones = response.data

                //console.log(response.data)
            });
        },

        editarFormularioFormacion: function (formacion) {
            this.formacionData = formacion;
        },

        //// LIMPIAR FORMULARIO FORMACION
        limpiarFormularioFormacion: function () {
            this.formacionData = { 'Id': '', 'Titulo': '', 'Establecimiento': '', 'Anio_inicio': '', 'Anio_finalizado': '', 'Descripcion_titulo': '' }
        },

        //// SCRITPS PARA SUBIR FOTOS FORMACIONES
        archivoSeleccionado(event) {
            this.Archivo = event.target.files[0]
            //this.texto_boton = "Actualizar"
        },

        upload(Id) {
            //this.texto_boton = "Actualizar"
            var url = base_url + 'usuarios/subirFotoUsuario/?Id=' + Id; // url donde voy a mandar los datos

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
                    console.log(error)

                });
        },

        //// Carga el formulario Items para editar FOTO
        editarFormulariousuarioFoto(item) {
            this.usuarioFoto = item;
            this.texto_boton = "Actualizar";
        },

        //// MOSTRAR LISTADO DE puestos  
        getListadoPuesto: function () {
            var url = base_url + 'usuarios/obtener_puestos'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaPuestos = response.data
            });
        },

        //// MOSTRAR LISTADO DE EMPRESAS  
        getListadoEmpresas: function () {
            var url = base_url + 'usuarios/obtener_empresas'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaEmpresas = response.data
            });
        },

    },

    ////// ACCIONES COMPUTADAS     
    computed:
    {

    }
});


///         --------------------------------------------------------------------   ////
//// Elemento para el manejo de CURRICULUM por Id
new Vue({
    el: '#curriculum',

    created: function () {
        //this.getDatosCurriculum();
        //this.getListadoPuestosCurriculum();
        //this.getListadoPuestos();
    },

    data: {

        mostrar: "1",

        curriculum: {
            'Id': '',
            'IP': '',
            'fecha': '',
            'nombre': '',
            'sexo': '',
            'nacimiento': '',
            'foto': '',
            'ciudad': '',
            'domicilio': '',
            'telefono': '',
            'email': '',
            'cuil': '',
            'referencia': '',
            'url': '',
            'hijos': '',
            'estadocivil': '',
            'nivelestudios': '',
            'sobreestudios': '',
            'laboral': '',
            'intereses': '',
            'personal': '',
            'Puntaje': '',
            'Puestos': '',
            'Observaciones': ''
        },

        texto_boton: "Cargar",

        listaPuestos: [],

        listaPuestosInteres: [],

        puestoData: { 'Id': '', 'Puesto_id': '', 'Justificacion': '' },
    },

    methods:
    {

        ////  CURRICULUM | OBTENER DATOS DE UNO
        getDatosCurriculum: function () {
            var url = base_url + 'curriculum/obtener_curriculum/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.curriculum = response.data[0]
            });
        },


        ////  CURRICULUM | CREAR O EDITA
        actualizarCurriculum: function () {
            var url = base_url + 'curriculum/actualizar_curriculum'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Data: this.curriculum
            }).then(response => {

                toastr.success('Datos actualizados correctamente', 'Curriculum')

                this.curriculum.Id = response.data.Id;
                this.texto_boton = "Actualizar"

            }).catch(error => {
                alert("mal");
                console.log(error)
            });
        },

        //// MOSTRAR LISTADO DE puestos  
        getListadoPuestos: function () {
            var url = base_url + 'usuarios/obtener_puestos'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaPuestos = response.data
            });
        },


        //// CURRICULUM | CREAR O EDITAR EMPRESAS
        agregarPuestoCurriculum: function () {
            var url = base_url + 'curriculum/cargar_puesto_curriculum'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Data: this.puestoData, Id: Get_Id
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Curriculum')

                this.puestoData.Id = response.data.Id;
                this.texto_boton = "Actualizar"

                this.getListadoPuestosCurriculum();

            }).catch(error => {
                alert("mal");
                console.log(error)

            });
        },


        ////  CURRICULUM | OBTENER PUESTOS ASIGNADOS
        getListadoPuestosCurriculum: function () {
            var url = base_url + 'curriculum/obtener_puestos_curriculum/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaPuestosInteres = response.data
            });
        },


        //// ELIMINAR UN PUESTO
        eliminar: function (Id, tbl) {
            var url = base_url + 'curriculum/eliminar'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Id: Id, tabla: tbl
            }).then(response => {

                this.getListadoPuestosCurriculum();
                toastr.success('pUESTO eliminado correctamente', 'Curriculum')

            }).catch(error => {
                alert("mal");
                console.log(error)
            });
        },
    },

    ////// ACCIONES COMPUTADAS     
    computed:
    {

    }
});


///         --------------------------------------------------------------------   ////
//// Elemento para el manejo de STOCK por Id
new Vue({
    el: '#itemStock',

    created: function () {
        this.getDatosItem();
        this.getListadoMovimientos();
        this.obtener_listado_de_proveedores_asignados();
    },

    data: {

        itemDatos: {},
        listaMovimientos: [],
        lista_proveedores_vinculados: [],
        lista_proveedores_no_vinculados: [],

        movimientoDatos: ''

    },

    methods:
    {

        //// MOSTRAR LISTADO DE MOVIMIENTOS  
        getListadoMovimientos: function () {
            var url = base_url + 'stock/obtener_movimientos/?Id=' + Get_Id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Tipo_movimiento: 0

            }).then(response => {
                this.listaMovimientos = response.data
            });
        },

        //// OBTENER DATOS DE UN PRODUCTO/ITEM 
        getDatosItem: function () {
            var url = base_url + 'stock/obtener_datos_item/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.itemDatos = response.data[0]
            });
        },


        classAlertaStock: function (actual, ideal) {
            var Valor_actual = parseInt(actual);
            var Valor_ideal = parseInt(ideal);

            if (Valor_actual < Valor_ideal) {
                return 'text-danger'
            }
            else {
                return ''
            }
        },

        //// FORMATO FECHA HORA
        formatoFecha_hora: function (fecha) {
            //separador = ":",
            fecha = fecha.split(' ');

            //var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
            var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');

            var fecha_hora = fecha[1].split(':');
            fecha_hora = fecha_hora[0] + ':' + fecha_hora[1];

            return fecha_dia + ' ' + fecha_hora + 'hs '

        },

        //// VINCULO STOCK PROVEEDOR |  Vincular proveedor a producto del stock
        Vincular_producto_proveedor: function (Proveedor_id) {
            var url = base_url + 'stock/Vincular_producto_proveedor'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Proveedor_id: Proveedor_id, Stock_id: Get_Id
            }).then(response => {

                toastr.success('Datos actualizados correctamente', 'Clientes')

                this.Vincular_producto_proveedor(); // actualizar listado de proveedores no vinculados
                this.obtener_listado_de_proveedores_asignados(); // actualizar listado de proveedores vinculados

            }).catch(error => {
                alert(response.data);
            });
        },


        //// VINCULO STOCK PROVEEDOR |  Desvincular proveedor a producto del stock
        Vincular_producto_proveedor: function (Proveedor_id) {
            var url = base_url + 'stock/Vincular_producto_proveedor'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Proveedor_id: Proveedor_id, Stock_id: Get_Id
            }).then(response => {

                toastr.success('Datos actualizados correctamente', 'Clientes')

                this.obtener_listado_de_proveedores_asignados(); // actualizar listado de proveedores no vinculados
                this.obtener_listado_de_proveedores_no_asignados(); // actualizar listado de proveedores vinculados

            }).catch(error => {
                alert(response.data);
            });
        },


        //// VINCULO STOCK PROVEEDOR |  OBTENER LISTADO DE PROVEEDORES VINCULADOS
        obtener_listado_de_proveedores_asignados: function () {
            var url = base_url + 'stock/obtener_listado_de_proveedores_asignados/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.lista_proveedores_vinculados = response.data
            });
        },


        //// VINCULO STOCK PROVEEDOR |  OBTENER LISTADO DE PROVEEDORES NO VINCULADOS
        obtener_listado_de_proveedores_no_asignados: function () {
            var url = base_url + 'stock/obtener_listado_de_proveedores_no_asignados/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.lista_proveedores_no_vinculados = response.data
            });
        },

        //// REPORTES DE MOVIMIENTOS DE STOCK | FORMULARIO
        editarFormularioMovimiento: function (datos) {
            this.movimientoDatos = datos;
        },

        //// REPORTES DE MOVIMIENTOS DE STOCK | ACTUALIZAR
        actualizarMovimiento: function () {
            var url = base_url + 'stock/actualizarMovimiento'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Data: this.movimientoDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Stock')

                this.texto_boton = "Actualizar"

                this.getListadoMovimientos();

            }).catch(error => {
                alert("mal");
                console.log(error)

            });
        },
    },

    ////// ACCIONES COMPUTADAS     
    computed:
    {

    }
});


///         --------------------------------------------------------------------   ////
//// Elemento para el manejo de CLIENTES por Id
new Vue({
    el: '#clientes',

    created: function () {
        this.getDatosCliente();
        this.getListadoSeguimiento();
        this.getListadoOrdenes(0, 0, 0, 0);

    },

    data: {
        mostrar: "1",
        clienteDatos: { 'Id': '', 'Nombre_cliente': '', 'Producto_servicio': '', 'Imagen': '', 'Direccion': '', 'Localidad': '', 'Provincia': '', 'Pais': '', 'Telefono': '', 'Email': '', 'Web': '', 'Nombre_persona_contacto': '', 'Datos_persona_contacto': '', 'Mas_datos_cliente': '' },
        listaSeguimiento: [],
        texto_boton: "Cargar",
        seguimientoData: {},

        listaOrdenes: [],
    },

    methods:
    {

        //// MOSTRAR LISTADO
        getListadoSeguimiento: function () {
            var url = base_url + 'clientes/obtener_seguimientos/?Id=' + Get_Id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaSeguimiento = response.data
            });
        },

        //// OBTENER DATOS DE UN CLIENTE
        getDatosCliente: function () {
            var url = base_url + 'clientes/obtener_datos_cliente/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.clienteDatos = response.data[0]
            });
        },

        //// CREAR O EDITAR una SEGUIMIENTO
        crearSeguimiento: function () {
            var url = base_url + 'clientes/cargar_seguimiento'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.seguimientoData, Id_cliente: Get_Id
            }).then(response => {

                toastr.success('Datos actualizados correctamente', 'Clientes')

                this.seguimientoData.Id = response.data.Id;
                this.texto_boton = "Actualizar"
                this.getListadoSeguimiento();

            }).catch(error => {
                alert(response.data);
            });
        },

        /// EDITAR UN SEGUIMIENTO
        editarFormularioSeguimiento: function (dato) {
            this.seguimientoData = dato;
        },

        //// LIMPIAR FORMULARIO SEGUIMIENTO
        limpiarFormularioSeguimiento: function () {
            this.seguimientoData = {}
        },

        //// CLIENTES  | CREAR O EDITAR 
        crearCliente: function () {
            var url = base_url + 'clientes/cargar_cliente'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.clienteDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Usuarios')

                this.clienteDatos.Id = response.data.Id;
                this.texto_boton = "Actualizar"

            }).catch(error => {
                alert("mal");
                console.log(error)
                toastr.success('Proceso realizado correctamente', 'Usuarios')
            });
        },

        //// CLIENTE |  MOSTRAR LISTADO DE ORDENES
        getListadoOrdenes: function (Usuario_id, Producto_id, Cliente_id, Estado) {
            var url = base_url + 'ordentrabajo/obtener_listado_ordenes?Producto_id=' + Producto_id + '&Usuario_id=' + Usuario_id + '&Cliente_id=' + Get_Id + '&Estado=' + 4; // estado 4 me trae todas las ordenes

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaOrdenes = response.data

            }).catch(error => {
                alert("mal");
                console.log(error.response.data)

            });
        },

        //// FORMATOS   | FORMATO Fecha Hora
        formatoFecha_hora: function (fecha) {
            //separador = ":",
            fecha = fecha.split(' ');

            //var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
            var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');

            var fecha_hora = fecha[1].split(':');
            fecha_hora = fecha_hora[0] + ':' + fecha_hora[1];

            return fecha_dia + ' ' + fecha_hora + 'hs '

        },

        //// FORMATO FECHA
        formatoFecha: function (fecha) {
            if (fecha == null) {
                return 'No definida'
            }
            else {
                return fecha.replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
            }
        },

        //// ORDEN TRABAJO  | CANTIDAD DE DIAS ENTRE DOS FECHAS
        diferenciasEntre_fechas: function (fechaInicio, fechaFin) {

            if (fechaFin == null) { fechaFin = hoy_php }
            if (fechaInicio == null) { fechaInicio = hoy_php }

            var fechaInicio = new Date(fechaInicio).getTime();
            var fechaFin = new Date(fechaFin).getTime();
            var diff = fechaFin - fechaInicio;

            /// debe devolver mensajes completos
            diff = diff / (1000 * 60 * 60 * 24)

            if (diff < 0) {
                diff = diff * parseInt(-1)
                return diff + ' días de atrazo'
            }
            else {
                return diff + ' días'
            }
        },
    },

    ////// ACCIONES COMPUTADAS     
    computed:
    {

    }
});


///         --------------------------------------------------------------------   ////
//// Elemento para el manejo de PROVEEDORES por Id
new Vue({
    el: '#proveedores',

    created: function () {
        this.getDatosProveedores();
        this.getListadoSeguimiento();
        this.obtener_listado_de_categorias_asignadas();
        this.obtener_listado_de_compras();
    },

    data: {
        mostrar: "1",
        proveedoresDatos: { 'Id': '', 'Nombre_proveedor': '', 'Producto_servicio': '', 'Imagen': '', 'Direccion': '', 'Localidad': '', 'Provincia': '', 'Pais': '', 'Telefono': '', 'Email': '', 'Web': '', 'Nombre_persona_contacto': '', 'Datos_persona_contacto': '', 'Mas_datos_proveedor': '' },
        listaSeguimiento: [],
        texto_boton: "Cargar",

        seguimientoData: {},
        listaSeguimiento: [],
        Archivo: null,
        preloader: '0',

        listaProductos: [],
        
        comentarioDatos: {},

        lista_categoria_productos_vinculados: [],
        lista_categoria_productos_no_vinculados: [],

        listaComprasProveedor: []
    },

    methods:
    {

        //// MOSTRAR LISTADO
        obtener_listado_de_compras: function () {
            var url = base_url + 'compras/obtener_compras_proveedor/?Id=' + Get_Id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaComprasProveedor = response.data
            });
        },

        //// OBTENER DATOS DE UN CLIENTE
        getDatosProveedores: function () {
            var url = base_url + 'proveedores/obtener_datos_proveedor/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.proveedoresDatos = response.data[0]
            });
        },

        //// MOSTRAR LISTADO
        getListadoSeguimiento: function () {
            var url = base_url + 'proveedores/obtener_seguimientos/?Id=' + Get_Id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaSeguimiento = response.data
            });
        },

        

        //// DATOS SOBRE EL ARCHIVO SELECCIONADO EN CASO QUE QUIERA CARGAR ALGUNA ARCHIVO
        archivoSeleccionado(event) {
            this.Archivo = event.target.files[0]
            //this.texto_boton = "Actualizar"
        },

        //// CREAR O EDITAR una SEGUIMIENTO
        crearSeguimiento: function () {
            var url = base_url + 'proveedores/cargar_seguimiento'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.seguimientoData, Id_proveedor: Get_Id
            }).then(response => {

                this.seguimientoData.Id = response.data.Id;

                /// si eso se ralizó bien, debe comprobar si hay un archivo a cargar.
                if (this.Archivo != null) {
                    var url = base_url + 'proveedores/subirFotoSeguimiento/?Id=' + this.seguimientoData.Id;
                    this.preloader = 1;

                    //const formData = event.target.files[0];
                    const formData = new FormData();
                    formData.append("Archivo", this.Archivo);

                    formData.append('_method', 'PUT');

                    //Enviamos la petición
                    axios.post(url, formData)
                        .then(response => {

                            this.seguimientoData.Url_archivo = response.data.Url_archivo;

                            toastr.success('El archivo se cargo correctamente', 'Proveedores')
                            this.preloader = 0;
                            this.getListadoSeguimiento();

                        }).catch(error => {
                            alert("MAL LA CARGA EN FUNCIÓN DE CARGAR ARCHIVO");
                            this.preloader = 0;
                            //this.seguimientoData.Url_archivo = response.data.Url_archivo;
                        });
                }
                // si lo hay lo carga, si no lo hay no hace nada

                this.getListadoSeguimiento();
                this.Archivo = null
                this.texto_boton = "Actualizar"
                toastr.success('Datos actualizados correctamente', 'Proveedores')

            }).catch(error => {
                alert("MAL LA CARGA EN FUNCIÓN DE CARGAR DATOS");
            });
        },

        /// EDITAR UN SEGUIMIENTO
        editarFormularioSeguimiento: function (dato) {
            this.seguimientoData = dato;
        },

        //// LIMPIAR FORMULARIO SEGUIMIENTO
        limpiarFormularioSeguimiento: function () {
            this.seguimientoData = {}
        },

        //// PROVEEDOR  | CREAR O EDITAR 
        crearProveedor: function () {
            var url = base_url + 'proveedores/cargar_proveedor'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.proveedoresDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Proveedores')

                this.proveedoresDatos.Id = response.data.Id;
                this.texto_boton = "Actualizar"

            }).catch(error => {
                alert("mal");
                console.log(error)
                toastr.success('Proceso realizado correctamente', 'Proveedores')
            });
        },

        //// OBTENER PRODUCTOS QUE OFRECE ESTE PROVEEDOR
        getProductos: function () {
            var url = base_url + 'proveedores/obtener_productos/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaProductos = response.data
            });
        },

        //// VINCULO CATEGORÍA STOCK PROVEEDOR |  Vincular proveedor a producto del stock
        Vincular_categoria_productos_proveedor: function (Categoria_id) {
            var url = base_url + 'proveedores/Vincular_categoria_productos_proveedor'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Categoria_id: Categoria_id, Proveedor_id: Get_Id
            }).then(response => {

                toastr.success('Datos actualizados correctamente', 'Proveedor')

                this.obtener_listado_de_categorias_no_asignados(); // actualizar listado no vinculados
                this.obtener_listado_de_categorias_asignadas(); // actualizar listado vinculados

            }).catch(error => {
                alert(response.data);
            });
        },

        //// VINCULO CATEGORÍA STOCK PROVEEDOR |  Desvincular proveedor a producto del stock
        Desvincular_categoria_productos_proveedor: function (Categoria_id) {
            var url = base_url + 'proveedores/Desvincular_categoria_productos_proveedor'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Categoria_id: Categoria_id, Proveedor_id: Get_Id
            }).then(response => {

                toastr.success('Datos actualizados correctamente', 'Clientes')

                this.obtener_listado_de_categorias_asignadas(); // actualizar listado  no vinculados
                this.obtener_listado_de_categorias_no_asignados(); // actualizar listado  vinculados

            }).catch(error => {
                alert(response.data);
            });
        },

        //// VINCULO CATEGORÍA STOCK PROVEEDOR |  OBTENER LISTADO  VINCULADOS
        obtener_listado_de_categorias_asignadas: function () {
            var url = base_url + 'proveedores/obtener_listado_de_categorias_asignadas/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.lista_categoria_productos_vinculados = response.data
            });
        },

        //// VINCULO CATEGORÍA STOCK PROVEEDOR |  OBTENER LISTADO  NO VINCULADOS
        obtener_listado_de_categorias_no_asignados: function () {
            var url = base_url + 'proveedores/obtener_listado_de_categorias_no_asignadas/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.lista_categoria_productos_no_vinculados = response.data
            });
        },

        /// VINCULO CATEGORÍA STOCK PROVEEDOR | EDITAR UN COMENTARIO DE UN PRODUCTO
        editarFormularioComentario: function (dato) {
            this.comentarioDatos = dato;
        },

        /// VINCULO CATEGORÍA STOCK PROVEEDOR |  ACTUALIZAR COMENTARIOS SOBRE EL PRODUCTO
        actualizarComentario: function () {
            var url = base_url + 'proveedores/actualizarComentario'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Data: this.comentarioDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Proveedores')

                this.texto_boton = "Actualizar"

                this.obtener_listado_de_categorias_asignadas();

            }).catch(error => {
                alert("mal");
                console.log(error)

            });
        },

        //// FORMATO FECHA
        formatoFecha: function (fecha) {
            if (fecha == null) {
                return 'No definida'
            }
            else {
                return fecha.replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
            }

        },
    },

    ////// ACCIONES COMPUTADAS     
    computed:
    {

    }
});


///         --------------------------------------------------------------------   ////
//// Elemento para el manejo de ORDENES por Id
new Vue({
    el: '#ordentrabajo',

    created: function () {
        this.getDatosOrdenTrabajo();
        this.getListadoUsuarios(1);
        this.getListadoProductos(0);
        this.getListadoClientes();
        this.getListadoSeguimiento();
        this.getListaComprados();
        this.getListadoPeriodos();
    },

    data: {
        mostrar: "1",
        ordenDatos: {
            'Id': '',
            'Nombre_usuario': '',
            'Producto_servicio': '',
            'Imagen': '',
            'Direccion': '',
            'Localidad': '',
            'Provincia': '',
            'Pais': '',
            'Telefono': '',
            'Email': '',
            'Web': '',
            'Nombre_persona_contacto': '',
            'Datos_persona_contacto': '',
            'Mas_datos_proveedor': ''
        },
        listaSeguimiento: [],
        texto_boton: "Cargar",
        seguimientoData: {},

        listaProductos: [],
        listaUsuarios: [],
        listaClientes: [],
        hoy: '',
        fecha_boton: null,
        listaproductosUsados: [],
        listaPeriodos:[]
    },

    methods:
    {
        //// MOSTRAR LISTADO DE Usuarios  
        getListadoUsuarios: function (estado) {
            var url = base_url + 'usuarios/obtener_Usuarios/?estado=' + estado + '&empresa=' + this.filtro_empresa + '&puesto=' + this.filtro_puesto; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaUsuarios = response.data
            });
        },

        //// FABRICACION |  MOSTRAR LISTADO DE CATEGORIAS
        getListadoProductos: function (categoria) {
            var url = base_url + 'fabricacion/obtener_listado_de_productos?categoria=' + categoria; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaProductos = response.data
            });
        },

        //// CLIENTES  | MOSTRAR LISTADO
        getListadoClientes: function (estado) {
            var url = base_url + 'clientes/obtener_clientes'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaClientes = response.data
            });
        },

        //// PERIODOS |  MOSTRAR LISTADO
        getListadoPeriodos: function () {
            var url = base_url + 'periodos/obtener_periodos'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaPeriodos = response.data
            }).catch(error => {
                alert("mal");
                console.log(error.response.data)

            });
        },

        //// OBTENER DATOS DE UN CLIENTE
        getDatosOrdenTrabajo: function () {
            var url = base_url + 'ordentrabajo/obtener_datos_orden/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.ordenDatos = response.data[0]
            });
        },

        //// ORDEN TRABAJO | CREAR O EDITAR ITEM
        crearOrden: function () {
            var url = base_url + 'ordentrabajo/cargar_orden'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Data: this.ordenDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Orden trabajo')

                this.ordenDatos.Id = response.data.Id;
                this.texto_boton = "Actualizar"

            }).catch(error => {
                alert("mal");
                console.log(error)
            });
        },

        /// REPORTAR FINALIZADO
        cambiar_estado: function (estado) {
            var url = base_url + 'ordentrabajo/cambiar_estado_orden/?Id=' + Get_Id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Fecha: this.fecha_boton,
                Estado: estado
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Orden trabajo')

                //this.ordenDatos.Estado = estado;
                this.getDatosOrdenTrabajo()

            }).catch(error => {
                alert("mal");
                console.log(error.response.data)
            });
        },

        //// MOSTRAR LISTADO
        getListadoSeguimiento: function () {
            var url = base_url + 'ordentrabajo/obtener_seguimientos/?Id=' + Get_Id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaSeguimiento = response.data
            });
        },

        //// CREAR O EDITAR una SEGUIMIENTO
        crearSeguimiento: function () {
            var url = base_url + 'ordentrabajo/cargar_seguimiento'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.seguimientoData, Orden_id: Get_Id
            }).then(response => {

                this.seguimientoData.Id = response.data.Id;

                this.getListadoSeguimiento();

                this.texto_boton = "Actualizar"
                toastr.success('Datos actualizados correctamente', 'Orden Trabajo')

            }).catch(error => {
                alert("MAL LA CARGA EN FUNCIÓN DE CARGAR DATOS");
                console.log(error.response.data)
            });
        },

        /// EDITAR UN SEGUIMIENTO
        editarFormularioSeguimiento: function (dato) {
            this.seguimientoData = dato;
        },

        //// LIMPIAR FORMULARIO SEGUIMIENTO
        limpiarFormularioSeguimiento: function () {
            this.seguimientoData = {}
        },

        //// MOSTRAR LISTADO DE PRODUCTOS COMPRADOS -- la consulta ya esta lista si no me equivoco
        getListaComprados: function () {
            var url = base_url + 'stock/obtener_movimientos_v2/?Id=' + Get_Id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Tipo_movimiento: 2
            }).then(response => {
                this.listaproductosUsados = response.data
            });
        },


        //// OPERACIONES CON FECHAS

        //// FORMATOS   | FORMATO Fecha Hora
        formatoFecha_hora: function (fecha) {
            //separador = ":",
            fecha = fecha.split(' ');

            //var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
            var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');

            var fecha_hora = fecha[1].split(':');
            fecha_hora = fecha_hora[0] + ':' + fecha_hora[1];

            return fecha_dia + ' ' + fecha_hora + 'hs '

        },

        //// FORMATO FECHA
        formatoFecha: function (fecha) {
            return fecha.replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
        },

        /// CANTIDAD DE DIAS ENTRE DOS FECHAS       
        diferenciasEntre_fechas: function (fechaInicio, fechaFin) {

            if (fechaFin == null) { fechaFin = hoy_php }
            if (fechaInicio == null) { fechaInicio = hoy_php }

            var fechaInicio = new Date(fechaInicio).getTime();
            var fechaFin = new Date(fechaFin).getTime();
            var diff = fechaFin - fechaInicio;

            /// debe devolver mensajes completos
            diff = diff / (1000 * 60 * 60 * 24)

            if (diff < 0) {
                diff = diff * parseInt(-1)
                return diff + ' días de atrazo'
            }
            else {
                return diff + ' días'
            }
        },


    },

    ////// ACCIONES COMPUTADAS     
    computed:
    {

    }
});


///         --------------------------------------------------------------------   ////
//// Elemento para el manejo de FABRICACION por Id
new Vue({
    el: '#fabricacion',

    created: function () {
        this.getDatosId();
        this.getListadoArchivos();
        this.getListadoCategoriasProductos();
        this.getListadoOrdenes(0, 0, 0, 0);
    },

    data: {
        mostrar: "1",
        productoDatos: {
            'Id': '',
            'Codigo_interno': '',
            'Codigo_interno': '',
        },
        'Categoria_fabricacion_id': '',
        'Nombre_producto': '',
        'Imagen': '',
        'Descripcion_publica_corta': '',
        'Descripcion_publica_larga': '',
        'Descripcion_tecnica_privada': '',

        listaArchivos: [],
        texto_boton: "Cargar",
        archivoData: {},

        listaProductos: [],
        Archivo: null,
        preloader: '0',
        comentarioDatos: {},

        listaCategorias: [],
        listaOrdenes: [],
        filtro_usuario: '0',
        filtro_producto: '0',
        filtro_cliente: '0',
        filtro_estado: '0',
    },

    methods:
    {

        //// MOSTRAR LISTADO
        getListadoArchivos: function () {
            var url = base_url + 'fabricacion/obtener_listado_archivos/?Id=' + Get_Id; // url donde voy a mandar los datos
            axios.post(url, {
                token: token,
            }).then(response => {
                this.listaArchivos = response.data
            });
        },

        //// OBTENER DATOS DEL ID
        getDatosId: function () {
            var url = base_url + 'fabricacion/obtener_datos_id/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.productoDatos = response.data[0]
            });
        },

        //// PRODUCTO  | CREAR O EDITAR 
        actualizarProducto: function () {
            var url = base_url + 'fabricacion/cargar_producto'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Data: this.productoDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Fabricación')

                this.productoDatos.Id = response.data.Id;
                this.texto_boton = "Actualizar"

            }).catch(error => {
                alert("mal");
                console.log(error)
                //toastr.success('Proceso realizado correctamente', 'Proveedores')
            });
        },

        //// FABRICACION |  MOSTRAR LISTADO DE ORDENES
        getListadoOrdenes: function (Usuario_id, Producto_id, Cliente_id, Estado) {
            var url = base_url + 'ordentrabajo/obtener_listado_ordenes?Producto_id=' + Get_Id + '&Usuario_id=' + Usuario_id + '&Cliente_id=' + Cliente_id + '&Estado=' + 4; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaOrdenes = response.data

            }).catch(error => {
                alert("mal");
                console.log(error.response.data)

            });
        },

        //// FABRICACION |  MOSTRAR LISTADO DE CATEGORIAS
        getListadoCategoriasProductos: function () {
            var url = base_url + 'fabricacion/obtener_categorias'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaCategorias = response.data
            });
        },

        //// FORMATOS   | FORMATO Fecha Hora
        formatoFecha_hora: function (fecha) {
            //separador = ":",
            fecha = fecha.split(' ');

            //var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
            var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');

            var fecha_hora = fecha[1].split(':');
            fecha_hora = fecha_hora[0] + ':' + fecha_hora[1];

            return fecha_dia + ' ' + fecha_hora + 'hs '

        },

        //// FORMATO FECHA
        formatoFecha: function (fecha) {
            if (fecha == null) {
                return 'No definida'
            }
            else {
                return fecha.replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
            }
        },

        //// ORDEN TRABAJO  | CANTIDAD DE DIAS ENTRE DOS FECHAS
        diferenciasEntre_fechas: function (fechaInicio, fechaFin) {

            if (fechaFin == null) { fechaFin = hoy_php }
            if (fechaInicio == null) { fechaInicio = hoy_php }

            var fechaInicio = new Date(fechaInicio).getTime();
            var fechaFin = new Date(fechaFin).getTime();
            var diff = fechaFin - fechaInicio;

            /// debe devolver mensajes completos
            diff = diff / (1000 * 60 * 60 * 24)

            if (diff < 0) {
                diff = diff * parseInt(-1)
                return diff + ' días de atrazo'
            }
            else {
                return diff + ' días'
            }
        },

        //// DATOS SOBRE EL ARCHIVO SELECCIONADO EN CASO QUE QUIERA CARGAR ALGUNA ARCHIVO
        archivoSeleccionado(event) {
            this.Archivo = event.target.files[0]
            //this.texto_boton = "Actualizar"
        },

        //// CREAR O EDITAR una SEGUIMIENTO
        cargarArchivo: function () {
            var url = base_url + 'fabricacion/cargar_archivo'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.archivoData, Producto_id: Get_Id
            }).then(response => {

                this.archivoData.Id = response.data.Id;

                /// si eso se ralizó bien, debe comprobar si hay un archivo a cargar.
                if (this.Archivo != null) {
                    var url = base_url + 'fabricacion/subirArchivo/?Id=' + this.archivoData.Id;
                    this.preloader = 1;

                    //const formData = event.target.files[0];
                    const formData = new FormData();
                    formData.append("Archivo", this.Archivo);

                    formData.append('_method', 'PUT');

                    //Enviamos la petición
                    axios.post(url, formData)
                        .then(response => {

                            this.archivoData.Url_archivo = response.data.Url_archivo;

                            toastr.success('El archivo se cargo correctamente', 'fabricacion')
                            this.preloader = 0;
                            this.getListadoArchivos();

                        }).catch(error => {
                            alert("MAL LA CARGA EN FUNCIÓN DE CARGAR ARCHIVO");
                            this.preloader = 0;

                        });
                }
                // si lo hay lo carga, si no lo hay no hace nada

                this.getListadoArchivos();
                this.Archivo = null
                this.texto_boton = "Actualizar"
                toastr.success('Datos actualizados correctamente', 'fabricacion')

            }).catch(error => {
                alert("MAL LA CARGA EN FUNCIÓN DE CARGAR DATOS");
            });
        },

        /// EDITAR UN SEGUIMIENTO
        editarFormularioarchivo: function (dato) {
            this.archivoData = dato;
        },

        //// LIMPIAR FORMULARIO SEGUIMIENTO
        limpiarFormularioArchivo: function () {
            this.archivoData = {}
        },

        ////////////////////////////-----------------



    },

    ////// ACCIONES COMPUTADAS     
    computed:
    {

    }
});


///         --------------------------------------------------------------------   ////
//// Elemento para el manejo de compraS por Id
new Vue({
    el: '#compras',

    created: function () {
        this.getDatoscompra();
        this.getListaComprados();
        this.getListadoProveedores();
        this.getListadoPeriodos();
        this.getListadoSeguimiento();

    },

    data: {
        mostrar: "1",
        compraDatos: {},
        texto_boton: "Cargar",
        buscar: '',

        listaProveedores: [],
        listaComprados: [],
        listaProductos: [],

        cantMovimientoStock: [],
        descripcionMovimiento: [],
        listaPeriodos:[],

        seguimientoData: {},
        listaSeguimiento: [],
        Archivo: null,
        preloader: '0',
    },

    methods:
    {

        //// OBTENER DATOS DE UN compra
        getDatoscompra: function () {
            var url = base_url + 'compras/obtener_datos_compra/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.compraDatos = response.data[0]
            }).catch(error => {
                alert("mal");
                console.log(error.response.data)
            });
        },

        //// STOCK | CREAR
        movimientoStock: function (id, cantidad, descripcion, precio) {
            var url = base_url + 'stock/cargar_movimiento'; // url donde voy a mandar los datos

            // 1 Equivale a compras, 2 a Ordenes de trabajo

            axios.post(url, {
                token: token,
                Id: id,
                Cantidad: cantidad,
                Descripcion: descripcion,
                Proceso_id: Get_Id, 
                Tipo_movimiento: 1,
                Precio: precio
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Stock')

                this.getListaComprados();
                this.cantMovimientoStock = [];
                this.descripcionMovimiento = []

            }).catch(error => {
                alert("mal");
                console.log(error.response)

            });
        },

        //// MOSTRAR LISTADO DE PRODUCTOS COMPRADOS -- la consulta ya esta lista si no me equivoco
        getListaComprados: function () {
            var url = base_url + 'stock/obtener_movimientos_v2/?Id=' + Get_Id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Tipo_movimiento: 1
            }).then(response => {
                this.listaComprados = response.data
                
            });
        },

        //// compraS  | CREAR O EDITAR 
        crearcompra: function () {
            var url = base_url + 'compras/cargar_compra'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.compraDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Compras')

                this.compraDatos.Id = response.data.Id;
                this.texto_boton = "Actualizar"
                this.getDatoscompra()


            }).catch(error => {
                alert("mal");
                console.log(error.response.data)
            });
        },

        //// PERIODOS |  MOSTRAR LISTADO
        getListadoPeriodos: function () {
            var url = base_url + 'periodos/obtener_periodos'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaPeriodos = response.data
            }).catch(error => {
                alert("mal");
                console.log(error.response.data)

            });
        },

        //// PROVEEDORES  | MOSTRAR LISTADO
        getListadoProveedores: function (estado) {
            var url = base_url + 'proveedores/obtener_proveedores'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaProveedores = response.data
            });
        },

        //// MOSTRAR LISTADO DE PRODUCTOS QUE OFRECE EL PROVEEDOR EN CUESTIÓN
        getListaProductosProveedor: function () {
            var url = base_url + 'proveedores/obtener_productos_proveedor'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Proveedor_id: this.compraDatos.Proveedor_id,


            }).then(response => {
                this.listaProductos = response.data
                this.mostrar = 2
            });
        },


        //// SEGUIMIENTO | MOSTRAR LISTADO
        getListadoSeguimiento: function () {
            var url = base_url + 'compras/obtener_seguimientos/?Id=' + Get_Id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaSeguimiento = response.data
            });
        },

        //// SEGUIMIENTO | DATOS SOBRE EL ARCHIVO SELECCIONADO EN CASO QUE QUIERA CARGAR ALGUNA ARCHIVO
        archivoSeleccionado(event) {
            this.Archivo = event.target.files[0]
            //this.texto_boton = "Actualizar"
        },

        //// SEGUIMIENTO | CREAR O EDITAR una SEGUIMIENTO
        crearSeguimiento: function () {
            var url = base_url + 'compras/cargar_seguimiento'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.seguimientoData, Compra_id: Get_Id
            }).then(response => {

                this.seguimientoData.Id = response.data.Id;

                /// si eso se ralizó bien, debe comprobar si hay un archivo a cargar.
                if (this.Archivo != null) {
                    var url = base_url + 'compras/subirFotoSeguimiento/?Id=' + this.seguimientoData.Id;
                    this.preloader = 1;

                    //const formData = event.target.files[0];
                    const formData = new FormData();
                    formData.append("Archivo", this.Archivo);

                    formData.append('_method', 'PUT');

                    //Enviamos la petición
                    axios.post(url, formData)
                        .then(response => {

                            this.seguimientoData.Url_archivo = response.data.Url_archivo;

                            toastr.success('El archivo se cargo correctamente', 'Compras')
                            this.preloader = 0;
                            this.getListadoSeguimiento();

                        }).catch(error => {
                            alert("MAL LA CARGA EN FUNCIÓN DE CARGAR ARCHIVO");
                            this.preloader = 0;
                            //this.seguimientoData.Url_archivo = response.data.Url_archivo;
                        });
                }
                // si lo hay lo carga, si no lo hay no hace nada

                this.getListadoSeguimiento();
                this.Archivo = null
                this.texto_boton = "Actualizar"
                toastr.success('Datos actualizados correctamente', 'Proveedores')

            }).catch(error => {
                alert("MAL LA CARGA EN FUNCIÓN DE CARGAR DATOS");
            });
        },

        //// SEGUIMIENTO | EDITAR UN SEGUIMIENTO
        editarFormularioSeguimiento: function (dato) {
            this.seguimientoData = dato;
        },

        //// SEGUIMIENTO | LIMPIAR FORMULARIO SEGUIMIENTO
        limpiarFormularioSeguimiento: function () {
            this.seguimientoData = {}
        },

    },

    ////// ACCIONES COMPUTADAS     
    computed:
    {
        buscarProducto: function () {
            return this.listaProductos.filter((item) => item.Nombre_item.toLowerCase().includes(this.buscar));
        },
    }
}); 