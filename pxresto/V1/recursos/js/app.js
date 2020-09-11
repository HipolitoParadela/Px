
/// FECHA TIME STAMP
Vue.filter('FechaTimestamp', function (fecha) {

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
Vue.filter('FechaTimestampBaseDatos', function (fecha) {

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
        return 'Sin definir'
    }


})

/// FORMATO DINERO
Vue.filter('Moneda', function (numero) {
    /// PARA QUE FUNCIONE DEBE TOMAR EL NUMERO COMO UN STRING
    //si no lo es, lo convierte

    if (numero > 0 || numero != null) {
        //if (numero % 1 == 0) {
        numero = Math.round(numero);
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
        for (var j, i = nuevoNumero.length - 1, j = 0; i >= 0; i--, j++)
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

///// AVERIGUAR COMO IMPORTAR ELEMENTOS

/// ELEMENTOS COMUNES PARA LA WEB
new Vue({
    el: '#app',

    created: function () {
        
        //// DATOS DE USUARIO PARA EL SISTEMA
        this.setVariablesUsuario(Nombre, Id, Rol_id, Negocio_id, Tipo_suscripcion);

        /// CARGO FUNCIONES SEGUN EL SECCTOR QUE ESTE VISUALIZANDO - uso un if, pero tal vez un swich sea mejor en este caso
        if (pathname == carpeta + 'restaurant/itemscarta') {
            this.getListadoItems();
            this.getListadoCategorias();
        }

        if (pathname == carpeta + 'restaurant/mesas') {
            this.getListadoMesas();
        }

        if (pathname == carpeta + 'restaurant/usuarios' || pathname == carpeta + 'usuarios/') {
            this.getListadoUsuarios(1);
            this.getListadoRoles();
        }


        if (pathname == carpeta + 'restaurant/comandas') {
            this.getListadoMesas();
            this.getListadoComandas();
            this.getListadoClientes();

            setInterval(() => { this.getListadoComandas() }, 60000); //// funcion para actualizar automaticamente cada 1minuto
        }

        if (pathname == carpeta + 'restaurant/cocina') {
            this.getListadoCocina();

            setInterval(() => { this.getListadoCocina() }, 60000); //// funcion para actualizar automaticamente cada 1minuto

        }

        if (pathname == carpeta + 'restaurant/resumencomandas') {

            this.comandasEntreFechas();
            this.getJornadas();
            this.getMozos();
            
        }

        if (pathname == carpeta + 'restaurant/controlpresencia' || pathname == carpeta + 'restaurant/iniciarjornada') {

            this.getListadoControlPrecencia();
        }

        if (pathname == carpeta + 'restaurant/iniciarjornada') {

            this.obtener_contabilidad_jornada_actual();
        }


        /////// DELIVERY -------------------- /////////////////
        if (pathname == carpeta + 'restaurant/deliverys') {

            this.getListadoDeliverys();
            this.getListadoUsuariosRepartidores();
            this.getListadoClientes();
        }

        if (pathname == carpeta + 'restaurant/resumendelivery') {

            this.deliveryEntreFechas();
            this.getJornadas();
            this.getListadoUsuariosRepartidores();
        }

        if (pathname == carpeta + 'restaurant/repartos') {

            this.obtener_repartos();
        }

        if (pathname == carpeta + 'restaurant/resumenrepartos') {

            this.obtener_listado_repartos();
            this.getJornadas();
        }

        /////// CAJA -------------------- /////////////////
        if (pathname == carpeta + 'restaurant/caja') {
            this.getMovimientosCaja(0);
            this.getJornadas();
        }

        /////// JORNADAS -------------------- /////////////////
        if (pathname == carpeta + 'restaurant/resumenjornadas') {
            this.getJornadasResumen(null, null);
        }

        /////// STOCK ---------------------- /////////////////
        if (pathname == carpeta + 'stock') {
            this.getListadoCategoriasStock();
            this.getListadoStock(0);
            this.getJornadas();
            this.getProductosFalta();
        }

        /////// CLIENTES -------------------- /////////////////
        if (pathname == carpeta + 'clientes') {

            this.getListadoClientesAvanzado();
        }

        /////// PROVEEDORES -------------------- /////////////////
        if (pathname == carpeta + 'proveedores') {
            this.getListadoProveedores(0);
            this.getListadoRubro();
        }

        /////// COMPRAS -------------------- /////////////////
        if (pathname == carpeta + 'compras') {
            this.getListadoCompras(null, null, null, null);
            this.getListadoRubro();
            this.getListadoProveedores_select();
            this.getJornadas();
        }

         /////// FINANZAS -------------------- /////////////////
         if (pathname == carpeta + 'finanzas') {
            this.getListadoCheques(1);
            this.consultarMovimientos(null, null, 0);
            this.getListadoChequesDiferenciados();
            this.getJornadas();
            
        }
    },

    data:
    {
        //// DATOS DE USUARIO PARA EL SISTEMA
            Nombre:'',
            Usuario_id:'',
            Rol_id:'',
            Negocio_id:'',
            Tipo_suscripcion,
        
        Rol_usuario: '',
        Filtro_fecha_inicial: null,
        Filtro_fecha_final: null,
        buscar: '',
        mostrar: "1",
        // finanzas  
        pag_efectivo: 1, // Página inicial EFECTIVO
        pag_trans: 1, // Página inicial TRANSFERENCIAS
        pag_cheques: 1, // Página inicial CHEQUES
        // PAGINACION
        NUM_RESULTS: 20, // Numero de resultados por página
        // comun
        pag: 1,
        texto_boton: "Cargar",
        infoModal: { 'Observaciones': '' },

        preloader: '0',

        itemsCarta: [],
        itemCarta: {},
        itemFoto: {},
        itemSeleccionado: {},

        categoriasCarta: [],
        categoria: { 'Id': '', 'Nombre_categoria': '', 'Descripcion': '' },

        listaMesas: [],
        mesa: { 'Id': '', 'Identificador': '', 'Descripcion': '' },

        listaUsuarios: [],
        usuario: { 
                'Id': '', 
                'Nombre': '', 
                'DNI': '', 
                'Pass': '', 
                'Rol_id': '',
                'Telefono': '',
                'Imagen': '',
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
                'Remuneracion_jornada': '',
                'Periodo_liquidacion_sueldo': '',
                'Horario_laboral': '',
                'Lider': '',
                'Superior_inmediato': '',
                'Fecha_alta': '',
                'Observaciones': '',
                'Activo': 1},
        usuarioFoto: {},
        Archivo: '',

        listaRoles: [],

        listaComandas: [],
        listaComandasCerradas: [],
        comanda: { 'Id': '', 'Mesa_id': '', 'Cant_personas': '', 'Fecha_hora': '', 'Moso_id': '', 'Valor_cuenta': '', 'Valor_descuento': '', 'Valor_adicional': '' },

        fecha_desde: 0,
        fecha_hasta: 0,

        listaControlPresencia: [],

        listaClientes: [],

        // Delivery
        listaDeliverys: [],
        listaDeliverysCerrados: [],
        delivery: {
            'Repartidor_id': 0,
            
            'Direccion': '',
            'Telefono': '',
            'Observaciones': '',
            'Observaciones_delivery': '',
            'Observaciones_cocina': '',
            'Modo_pago': '0',
            'Valor_delivery': '0',
        },
        listaRepartos: [],
        listaUsuariosRepartidores: [],
        datoClienteSeleccionado: {},

        filtro_jornada: 0,
        Filtro_mozo: 'X',

        listaMovCaja: [],
        cajaData: { Id: '', Jornada_id: '', Valor_ingreso: '0', Valor_egreso: '0', Observaciones: '', Usuario_id: '' },

        //Stock
        listaCategorias: [],
        categoriaDatos: {},
        filtro_categoria: '0',
        listaProductosFalta: [],

        listaStock: [],
        stockDato: { 'Id': '', 'Nombre_item': '', 'Categoria_id': '', 'Descripcion': '', 'Cant_actual': '0', 'Cant_ideal': '', 'Ult_modificacion_id': '' },

        stockFoto: { 'Id': '', 'Nombre': '', 'Imagen': '' },

        cantMovimientoStock: [],
        descripcionMovimiento: [],

        egresoDato: { Venta_id: 0 },

        // JORNADA
        result_jornada: {},
        jornadaDatos: { 'Efectivo_caja_inicio': 0, 'Efectivo_caja_final': 0 },
        listaJornadas: [],
        listaResumenJornadas: [],
        Filtro_repartidor: 'X',

        listaMozos: [],

        clienteDato: {},

        // Proveedores
        listaProveedores: [],
        listaProveedores_select: [],
        proveedorDatos: { 'Id': '', 'Nombre_proveedor': '', 'Manzana_id': '', 'Direccion': '', 'Localidad': '', 'Provincia': '', 'Pais': '', 'Telefono': '', 'Email': '', 'Web': '', 'Nombre_persona_contacto': '', 'Datos_persona_contacto': '', 'Mas_datos_proveedor': '' },
        proveedorFoto: { 'Id': '', 'Nombre_proveedor': '', 'Imagen': '' },
        listaRubros: [],
        rubroDato: {},
        filtro_rubro: 0,

        // Compras
        listaCompras: [],
        compraDatos: { 'Id': '', 'Proveedor_id': '', 'Fecha_compra': '', 'Factura_identificador': '', 'Valor': '', 'Descripcion': '' },
        compraFoto: { 'Id': '', 'Factura_identificador': '', 'Imagen': '' },

        // Finanzas
        movimientoDatos: {},
        listaMovimientosEfectivo: [],
        listaMovimientosTransferencias: [],
        listaMovimientosCheques: [],
        listaMovimientosMPago: [],
        listaCheques: [],
        listaChequesDiferenciados: [],
        chequeSeleccionado: {},
        chequeData: {}

    },

    methods:
    {   
        //// DATOS DE USUARIO PARA EL SISTEMA
        setVariablesUsuario: function (Nombre, Id, Rol_id, Negocio_id, Tipo_suscripcion) {
            this.Nombre = Nombre;
            this.Usuario_id = Id;
            this.Rol_id = Rol_id;
            this.Negocio_id = Negocio_id;
            this.Tipo_suscripcion = Tipo_suscripcion;
        },

        //// CARTA | MOSTRAR LISTADO DE ITEMS DE LA CARTA    
        getListadoItems: function () {
            var url = base_url + 'restaurant/mostrarItems'; // url donde voy a mandar los datos

            axios.get(url).then(response => {
                this.itemsCarta = response.data
            }).catch(error => {
                //toastr.error('Error al cargar el item'); // hacer funcionar esto, esta bueno

                console.log(error.response.data)
            });

            /// CODIGO NECESARIO PARA FORMATEAR LA TABLA, Y LOGRAR QUE FUNCIONEN
            // $(document).ready(function () {
            //     $('#bootstrap-data-table-export').DataTable({
            //         "ajax": response.data,
            //         "columns": [
            //             { "data": "Nombre" },
            //             { "data": "Categoria_id" },
            //             { "data": "Descripcion" },
            //             { "data": "Precio_venta" },
            //             { "data": "Tiempo_estimado_entrega" },
            //             { "data": "Fecha_actualizado" },
            //             { "data": "Activo" }
            //         ]
            //     });
            // });
        },

        //// CARTA | MOSTRAR LISTADO DE ITEMS DE LA CARTA POR CATEGORIA   
        cargarItemsbyCategoria: function (Id) {
            var url = base_url + 'restaurant/mostrarItemsCategoria/?Id=' + Id; // url donde voy a mandar los datos

            axios.get(url).then(response => {
                this.itemsCarta = response.data
            }).catch(error => {
                //toastr.error('Error al cargar el item'); // hacer funcionar esto, esta bueno

                console.log(error.response.data)
            });

        },

        //// CARTA | ACTIVAR/DESACTIVAR ITEMS DE LA CARTA    
        activarItem: function (itemCarta) {
            var url = base_url + 'restaurant/cargarItem'; // url donde voy a mandar los datos

            if (itemCarta.Activo == 1) {
                itemCarta.Activo = 0;
            }
            else {
                itemCarta.Activo = 1;
            }

            axios.post(url, {
                ItemCartaDatos: itemCarta
            }).then(response => {

                toastr.success('Proceso realizado con éxito', 'Items Carta')

                this.itemCarta.Id = response.data.Id;
                this.texto_boton = "Actualizar"

                this.getListadoItems();

            }).catch(error => {
                //toastr.error('Error al cargar el item'); // hacer funcionar esto, esta bueno

                alert("mal");
            });
        },


        //// CARTA | LIMPIAR EL FORMULARIO DE CREAR ITEMS CARTA    
        limpiarFormularioItems: function () {
            this.itemCarta = {};

            this.texto_boton = "Cargar";
        },

        //// CARTA | Carga el formulario ITEMS CARTA para editar
        cargarFormularioItem(item) {
            this.itemCarta = item;

            this.texto_boton = "Actualizar";
        },

        //// CARTA | CREAR O EDITAR ITEMS DE LA CARTA
        crearItem: function () {
            var url = base_url + 'restaurant/cargarItem'; // url donde voy a mandar los datos

            axios.post(url, {
                ItemCartaDatos: this.itemCarta
            }).then(response => {

                toastr.success('Nuevo item cargado correctamente', 'Items Carta')


                this.texto_boton = "Actualizar"

                this.getListadoItems();
                this.itemCarta.Id = response.data.Id;

            }).catch(error => {
                //toastr.error('Error al cargar el item'); // hacer funcionar esto, esta bueno

                console.log(error.response.data)
            });
        },

        //// CARTA | Info item comanda en modal
        infoItem: function (item) {
            this.itemCarta = item;
        },

        //// CARTA | MOSTRAR LISTADO DE Categorias DE LA CARTA    
        getListadoCategorias: function () {
            var url = base_url + 'restaurant/obtener_categorias_items'; // url donde voy a mandar los datos

            axios.get(url).then(response => {
                this.categoriasCarta = response.data
            });
        },

        //// CARTA | LIMPIAR EL FORMULARIO DE CREAR Categorias CARTA    
        limpiarFormularioCategorias() {
            this.categoria.Id = '';
            this.categoria.Nombre_categoria = '';
            this.categoria.Descripcion = '';
            this.texto_boton = "Cargar";
        },

        //// CARTA | Carga el formulario cATEGORIAS para editar
        editarFormularioCategoriaCarta(categoria) {
            this.categoria = categoria;
            this.texto_boton_cat = "Editar";
        },

        //// CARTA | CREAR O EDITAR CATEGORIA
        crearCategoria: function () {
            var url = base_url + 'restaurant/cargar_categoria_items'; // url donde voy a mandar los datos

            axios.post(url, {

                categoriaCartaDatos: this.categoria
            }).then(response => {

                toastr.success('Nueva categoría cargada correctamente', 'Items Carta')

                this.categoria.Id = response.data.Id;
                this.texto_boton = "Actualizar"

                this.getListadoCategorias();

            }).catch(error => {
                console.log(error.response.data)
            });
        },

        //// MOSTRAR LISTADO DE MESAS   
        getListadoMesas: function () {
            var url = base_url + 'restaurant/obtener_mesas'; // url donde voy a mandar los datos

            axios.get(url).then(response => {
                this.listaMesas = response.data
            });
        },

        //// LIMPIAR EL FORMULARIO DE CREAR MESAS
        limpiarFormularioMesas() {
            this.mesa.Id = '';
            this.mesa.Identificador = '';
            this.mesa.Descripcion = '';
            this.texto_boton = "Cargar";
        },

        //// Carga el formulario MESAS para editar
        editarFormularioMesa(mesa) {
            this.mesa = mesa;
            this.texto_boton = "Actualizar";
        },

        //// CREAR O EDITAR MESAS  
        crearMesas: function () {
            var url = base_url + 'restaurant/cargar_mesas'; // url donde voy a mandar los datos

            axios.post(url, {
                mesaData: this.mesa
            }).then(response => {

                toastr.success('Nueva mesa cargada correctamente', 'Mesas')

                this.mesa.Id = response.data.Id;
                this.texto_boton = "Actualizar"

                this.getListadoMesas();

            }).catch(error => {
                console.log(error.response.data)
            });
        },

        //// MOSTRAR LISTADO DE Usuarios  
        getListadoUsuarios: function (estado) {
            var url = base_url + 'restaurant/obtener_Usuarios/?estado=' + estado; // url donde voy a mandar los datos

            axios.get(url).then(response => {
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
            var url = base_url + 'restaurant/cargar_Usuarios'; // url donde voy a mandar los datos

            axios.post(url, {
                usuarioData: this.usuario
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Usuarios')

                this.usuario.Id = response.data.Id;
                this.texto_boton = "Actualizar"

                /////// DELIVERY -------------------- /////////////////
                if (pathname == carpeta + 'restaurant/deliverys') {
                    this.getListadoUsuariosRepartidores();
                }
                /////// USUARIOS -------------------- /////////////////
                else if (pathname == carpeta + 'restaurant/usuarios') {
                    this.getListadoUsuarios(1);
                }

                /////// USUARIOS -------------------- /////////////////
                else if (pathname == carpeta + 'usuarios') {
                    this.getListadoUsuarios(1);
                }

            }).catch(error => {
                console.log(response.data)
                
            });
        },

        //// ACTIVAR/DESACTIVAR USUARIOS    
        activarUsuario: function (usuario) {
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


        //// COMANDAS | CREAR COMANDA
        crearComanda: function () {
            var url = base_url + 'restaurant/crear_comanda'; // url donde voy a mandar los datos

            axios.post(url, {
                comandaData: this.comanda
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Comandas')

                this.texto_boton = "Actualizar"
                this.comanda.Id = response.data.Id;

                //console.log(response.data.Id)

                this.getListadoComandas();

            }).catch(error => {
                console.log(error.response.data)
            });
        },

        //// COMANDAS | LIMPIAR FORMULARIO
        limpiarFormularioComandas: function () {
            this.comanda = {};

            this.texto_boton = "Cargar";
        },


        //// Carga el formulario comanda para editar
        editComanda(item) {
            this.comanda = item;
            console.log(item)
            this.texto_boton = "Actualizar";
        },

        //// MOSTRAR LISTADO DE COMANDAS ABIERTAS DE LA FECHA
        getListadoComandas: function () {
            var url = base_url + 'restaurant/obtener_listado_comandas_abiertas'; // url donde voy a mandar los datos

            axios.get(url).then(response => {
                this.listaComandas = response.data

            }).catch(error => {
                console.log(error.response.data)
            });
        },

        //// ACTUALIZAR LISTADO COMANDAS
        actualizarListadoComandas: function () {
            this.getListadoComandas();

            toastr.success('Información actualizada', 'Comandas')


        },


        //// MOSTRAR LISTADO DE CERRADAS
        comandasEntreFechas: function () {
            var url = base_url + 'restaurant/obtener_listado_comandas_cerradas'; // url donde voy a mandar los datos

            axios.post(url, {
                Desde: this.fecha_desde,
                Hasta: this.fecha_hasta,
                Moso_id: this.Filtro_mozo,
                Jornada_id: this.filtro_jornada,
            }).then(response => {

                this.listaComandasCerradas = response.data
            });
        },

        //// USUARIOS | MOSTRAR LISTADO DE MOZOS
        getMozos: function () {
            var url = base_url + 'usuarios/obtener_listado_mozos'; // url donde voy a mandar los datos

            axios.post(url).then(response => {

                this.listaMozos = response.data
            });
        },

        //// FORMATO FECHA
        formatoFecha: function (fecha) {
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

        //// SUMAR Deliverys   
        sumarDeliverys: function (items) {

            /// SUMAR LOS ENTREGADOS
            var Total = 0;

            for (var i = 0; i < items.length; i++) {
                var item = 0;

                if ((items[i].Valor_delivery !== null)) {
                    item = parseInt(items[i].Valor_delivery);
                }

                Total = Total + item;
            }
            return Total
        },

        //// SUMAR DESCUENTOS   
        sumarDescuentos: function (items) {

            /// SUMAR LOS ENTREGADOS
            var Total = 0;

            for (var i = 0; i < items.length; i++) {
                var item = 0;

                if ((items[i].Valor_descuento !== null)) {
                    item = parseInt(items[i].Valor_descuento);
                }

                Total = Total + item;
            }
            return Total
        },

        //// SUMAR COMENSALES   
        sumarComensales: function (items) {

            /// SUMAR LOS ENTREGADOS
            var Total = 0;

            for (var i = 0; i < items.length; i++) {
                var item = 0;

                if (isFinite(items[i].Cant_personas)) {
                    item = parseInt(items[i].Cant_personas);
                }

                Total = Total + item;
            }
            return Total
        },

        //// MOSTRAR LISTADO DE USUSARIOS PARA CONTROL PRESENCIA 
        getListadoControlPrecencia: function () {
            var url = base_url + 'restaurant/obtener_Usuarios_control_presencia'; // url donde voy a mandar los datos

            axios.get(url).then(response => {
                this.listaControlPresencia = response.data
            });
            this.jornadaDatos.Fecha_inicio = hoy_php;
            this.jornadaDatos.Fecha_fin = hoy_php;
            this.jornadaDatos.Hora_inicio = horaHoy_php;
            this.jornadaDatos.Hora_fin = horaHoy_php;
        },

        //// ACTIVAR/DESACTIVAR USUARIOS    
        jornada: function (Id, presencia) {
            var url = base_url + 'restaurant/jornada_activar_desactivar'; // url donde voy a mandar los datos


            axios.post(url, {
                Usuario_id: Id, Presencia: presencia
            }).then(response => {

                toastr.success('Proceso realizado con éxito', 'Control Presencia')

                //this.usuario.Id = response.data.Id;
                //this.texto_boton = "Actualizar"

                this.getListadoControlPrecencia();

            }).catch(error => {
                //toastr.error('Error al cargar el item'); // hacer funcionar esto, esta bueno

                alert("mal");
            });
        },


        //// SCRITPS PARA SUBIR FOTOS USUARIOS
        archivoSeleccionado(event) {
            this.Archivo = event.target.files[0]
            //this.texto_boton = "Actualizar"
        },

        upload(Id) {
            //this.texto_boton = "Actualizar"
            var url = base_url + 'restaurant/subirFotoUsuario/?Id=' + Id; // url donde voy a mandar los datos

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

        //// CARTA | Carga el formulario Items para editar FOTO
        editarFormularioitemCarta(item) {
            this.itemSeleccionado = item;
            this.texto_boton = "Actualizar";
        },

        //// CARTA |  SCRITPS PARA SUBIR FOTOS DE ITEM CARTA
        uploadItem(Id) {
            //this.texto_boton = "Actualizar"
            var url = base_url + 'restaurant/subirFotoItemCarta/?Id=' + Id; // url donde voy a mandar los datos

            //const formData = event.target.files[0];
            const formData = new FormData();
            formData.append("Archivo", this.Archivo);

            formData.append('_method', 'PUT');

            //Enviamos la petición
            axios.post(url, formData)
                .then(response => {

                    ////DEBO HACER FUNCIONAR BIEN ESTO PARA QUE SE ACTUALICE LA FOTO QUE CARGO EN EL MOMENTO, SI NO PARECE Q NO SE CARGARA NADA
                    this.itemSeleccionado.Imagen = response.data.Imagen;
                    this.getListadoItems();
                    toastr.success('Proceso realizado correctamente', 'Carta')


                }).catch(error => {
                    alert("mal");
                    //console.log(response.data.status);
                });
        },


        //// DELIVERYS | MOSTRAR LISTADO DE DELIVERYS A LA FECHA
        getListadoDeliverys: function () {
            var url = base_url + 'restaurant/obtener_listado_deliverys_abiertos'; // url donde voy a mandar los datos

            axios.get(url).then(response => {
                this.listaDeliverys = response.data

            });
        },

        //// DELIVERYS | LIMPIAR EL FORMULARIO DE CREAR ITEMS CARTA    
        limpiarFormularioDelivery() {
            this.delivery = {
                'Repartidor_id': 0,
                
                
                'Direccion': '',
                'Telefono': '',
                'Observaciones': '',
                'Observaciones_delivery': '',
                'Observaciones_cocina': '',
                'Modo_pago': '0',
                'Valor_delivery': '0',
            };
            this.texto_boton = "Cargar";
        },

        datoCliente: function (Id) {
            var url = base_url + 'restaurant/datoCliente/?Id='+Id; // url donde voy a mandar los datos

            axios.get(url).then(response => {
                this.datoClienteSeleccionado = response.data[0]
                //console.log(response.data)
            });
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

        //// Carga el formulario comanda para editar
        editDelivery(item) {
            this.delivery = item;
            console.log(item)
            this.texto_boton = "Actualizar";
        },

        //// DELIVERYS | CREAR DELIVERY
        crearDelivery: function () {

            var url = base_url + 'restaurant/crear_delivery'; // url donde voy a mandar los datos
            axios.post(url, {
                Datos: this.delivery,
                Repartidor_id: this.delivery.Repartidor_id,
            }).then(response => {

                this.texto_boton = "Actualizar"
                this.delivery.Id = response.data.Id;

                //console.log(response.data.Id)
                this.getListadoDeliverys();
                toastr.success('Proceso realizado correctamente', 'Delivery')

            }).catch(error => {
                console.log(error)
            });
        },

        //// DELIVERYS |  MOSTRAR LISTADO DE CERRADAS
        deliveryEntreFechas: function () {
            var url = base_url + 'restaurant/obtener_listado_delivery_cerrados'; // url donde voy a mandar los datos

            axios.post(url, {
                Desde: this.fecha_desde,
                Hasta: this.fecha_hasta,
                Repartidor_id: this.Filtro_repartidor,
                Jornada_id: this.filtro_jornada,
            }).then(response => {

                this.listaDeliverysCerrados = response.data
            });
        },

        //// DELIVERYS REPARTOS| MOSTRAR LISTADO DE Usuarios Repartidores
        getListadoUsuariosRepartidores: function () {
            var url = base_url + 'restaurant/obtener_Usuarios_repartidores/'; // url donde voy a mandar los datos

            axios.get(url).then(response => {
                this.listaUsuariosRepartidores = response.data
            });
        },

        //// DELIVERYS REPARTOS | MOSTRAR LISTADO DE DELIVERYS A LA FECHA
        obtener_repartos: function () {
            var url = base_url + 'restaurant/obtener_repartos_abiertos'; // url donde voy a mandar los datos

            axios.get(url).then(response => {
                this.listaRepartos = response.data

            }).catch(error => {
                console.log(error.response.data)
                
            });
        },

        ////  DELIVERY REPARTOS| REPORTA QUE EL PEDIDO ACABA DE SER RETIRADO DE LA COCINA
        reportarTomado: function (Delivery_id) {
            var url = base_url + 'restaurant/reportarTomado'; // url donde voy a mandar los datos

            axios.post(url, {
                Id: Delivery_id
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Delivery')

                this.deliveryData.Id = response.data.Id;

                if (pathname == carpeta + 'restaurant/deliverys') 
                { 
                    this.getListadoDeliverys();
                }
                else if (pathname == carpeta + 'restaurant/repartos') 
                { 
                    this.obtener_repartos();
                }
                    
            }).catch(error => {
                console.log(error.message)
                if (pathname == carpeta + 'restaurant/deliverys') 
                { 
                    this.getListadoDeliverys();
                }
                else if (pathname == carpeta + 'restaurant/repartos') 
                { 
                    this.obtener_repartos();
                }
            });
        },


        ////  DELIVERY REPARTOS| REPORTA QUE EL PEDIDO YA LO RECIBIÓ EL CLIENTE
        reportarEntrega: function (Delivery_id) {
            var url = base_url + 'restaurant/reportarEntrega'; // url donde voy a mandar los datos

            axios.post(url, {
                Id: Delivery_id
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Delivery')

                this.deliveryData.Id = response.data.Id;

                this.deliveryData.Id = response.data.Id;

                if (pathname == carpeta + 'restaurant/deliverys') 
                { 
                    this.getListadoDeliverys();
                }
                else if (pathname == carpeta + 'restaurant/repartos') 
                { 
                    this.obtener_repartos();
                }

            }).catch(error => {
                console.log(error.message)
                if (pathname == carpeta + 'restaurant/deliverys') 
                { 
                    this.getListadoDeliverys();
                }
                else if (pathname == carpeta + 'restaurant/repartos') 
                { 
                    this.obtener_repartos();
                }
            });
        },

        ////  DELIVERY | REPORTA QUE EL PEDIDO YA ESTA LISTO EN COCINA
        reportarCocina: function (Pedido_id) {
            var url = base_url + 'restaurant/reportarCocina'; // url donde voy a mandar los datos

            axios.post(url, {
                Id: Pedido_id
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Delivery')

                this.deliveryData.Id = response.data.Id;

                if (pathname == carpeta + 'restaurant/deliverys') 
                { 
                    this.getListadoDeliverys();
                }
                else if (pathname == carpeta + 'restaurant/cocina') 
                { 
                    this.getListadoCocina();
                }

            }).catch(error => {
                console.log(error.message)
                if (pathname == carpeta + 'restaurant/deliverys') 
                { 
                    this.getListadoDeliverys();
                }
                else if (pathname == carpeta + 'restaurant/cocina') 
                { 
                    this.getListadoCocina();
                }
            });
        },

        //// DELIVERYS REPARTOS | MOSTRAR LISTADO DE REPARTOS PASADOS
        obtener_listado_repartos: function () {
            var url = base_url + 'restaurant/obtener_listado_repartos'; // url donde voy a mandar los datos

            axios.post(url, {
                Desde: this.fecha_desde,
                Hasta: this.fecha_hasta,
                Repartidor_id: this.Filtro_repartidor,
                Jornada_id: this.filtro_jornada,
            }).then(response => {
                this.listaRepartos = response.data

            });
        },

        //// DELIVERYS REPARTOS | MOSTRAR LISTADO DE REPARTOS PASADOS
        sumarRepartos: function (items) {
            Total = 0;
            for (var i = 0; i < items.length; i++) {
                var item = 0;

                if (isFinite(items[i].Valor_delivery)) {
                    item = parseInt(items[i].Valor_delivery);
                }
                Total = Total + item;
            }
            return Total;
        },

        /// Cuenta final del delivery
        sumarMontos: function (pedido, delivery, descuento) {


            var Total = parseInt(pedido) + parseInt(delivery) - parseInt(descuento);

            return Total
        },

        //// MOSTRAR LISTADO DE COMANDAS ABIERTAS DE LA FECHA
        getListadoCocina: function () {
            var url = base_url + 'restaurant/obtener_listado_cocina'; // url donde voy a mandar los datos

            axios.get(url).then(response => {
                this.listaComandas = response.data.Comandas;
                this.listaDeliverys = response.data.Delivery;
            });

        },

        //// JORNADA    | Crear jornada
        crearJornada: function () {
            var url = base_url + 'restaurant/crearJornada'; // url donde voy a mandar los datos
            toastr.success('Proceso realizado correctamente', 'Jornadas')
            axios.post(url, {
                jornadaDatos: this.jornadaDatos
            }).then(response => {



                if (response.data.Id > 0) {
                    /// recargar esta página
                    location.reload();
                }

            }).catch(error => {
                alert("mal");
            });
        },

        //// JORNADA    | CERRAR  jornada
        cerrarJornada: function () {
            var url = base_url + 'restaurant/cerrarJornada'; // url donde voy a mandar los datos
            toastr.success('Proceso realizado correctamente', 'Jornadas')
            axios.post(url, {
                jornadaDatos: this.jornadaDatos
            }).then(response => {


                location.reload();


            }).catch(error => {
                alert("mal");
            });
        },

        //// JORNADA    | MOSTRAR LISTADO 
        getJornadas: function () {
            var url = base_url + 'restaurant/obtener_jornadas';  //// averiguar como tomar el Id que viene por URL aca

            axios.get(url).then(response => {
                this.listaJornadas = response.data

                //console.log(response.data)
            });
        },

        //// JORNADA    | MOSTRAR RESUMEN 
        getJornadasResumen: function (fecha_inicial, fecha_final) {
            var url = base_url + 'restaurant/obtener_resumen_jornadas';  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                Desde: fecha_inicial, Hasta: fecha_final
            }).then(response => {
                this.listaResumenJornadas = response.data

                //console.log(response.data)
            });
        },

        //// JORNADA    | MOSTRAR LISTADO 
        obtener_contabilidad_jornada_actual: function () {
            var url = base_url + 'restaurant/obtener_contabilidad_jornada_actual';  //// averiguar como tomar el Id que viene por URL aca

            axios.get(url).then(response => {
                this.result_jornada = response.data

                console.log(response.data)
            });
        },



        //// CAJA       | CREAR O EDITAR MOVIMIENTO CAJA
        crearMovimiento: function () {
            var url = base_url + 'restaurant/cargar_movimientos_caja'; // url donde voy a mandar los datos

            axios.post(url, {
                Data: this.cajaData
            }).then(response => {

                toastr.success('Datos actualizados correctamente', 'Caja')

                this.cajaData.Id = response.data.Id;
                this.texto_boton = "Actualizar"
                this.getMovimientosCaja();

            }).catch(error => {
                alert(response.data);
            });
        },

        //// CAJA       | MOSTRAR LISTADO DE MOVIMIENTOS DE CAJA
        getMovimientosCaja: function (Jornada_id) {
            var url = base_url + 'finanzas/obtener_movimientos_caja?Id=' + Jornada_id;  //// averiguar como tomar el Id que viene por URL aca

            axios.get(url).then(response => {
                this.listaMovCaja = response.data

                //console.log(response.data)
            });
        },

        //// CAJA       | Editar Formulario
        editarFormularioCaja: function (item) {
            this.cajaData = item;
        },

        //// CAJA       | Limpiar Formulario
        limpiarFormulariocaja: function () {
            this.cajaData = { Id: '', Jornada_id: '', Valor_ingreso: '0', Valor_egreso: '0', Observaciones: '', Usuario_id: '' };
        },

        //// CAJA       | SUMAS
        sumarIngresos: function (items) {
            Total_Valor_ingreso = 0;
            for (var i = 0; i < items.length; i++) {
                var item = 0;

                if (isFinite(items[i].Valor_ingreso)) {
                    item = parseInt(items[i].Valor_ingreso);
                }
                Total_Valor_ingreso = Total_Valor_ingreso + item;
            }
            return Total_Valor_ingreso;
        },

        sumarEgresos: function (items) {
            Total_Valor_egreso = 0;
            for (var i = 0; i < items.length; i++) {
                var item = 0;

                if (isFinite(items[i].Valor_egreso)) {
                    item = parseInt(items[i].Valor_egreso);
                }
                Total_Valor_egreso = Total_Valor_egreso + item;
            }
            return Total_Valor_egreso;
        },

        /////------- STOCK --------
        //// STOCK |  MOSTRAR LISTADO DE CATEGORIAS
        getListadoCategoriasStock: function () {
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
        crearCategoriaStock: function () {
            var url = base_url + 'stock/cargar_categoria'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Data: this.categoriaDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'S    tock')

                this.categoriaDatos.Id = response.data.Id;
                //this.texto_boton = "Actualizar"
                this.categoriaDatos = {}

                this.getListadoCategoriasStock();

            }).catch(error => {
                alert("mal");
                console.log(error)

            });
        },

        //// STOCK |  MOSTRAR LISTADO
        getListadoStock: function (categoria) {
            var url = base_url + 'stock/obtener_listado_de_stock?categoria=' + categoria; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaStock = response.data

                //console.log(response.data)
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
        movimientoStock_v2: function (tipo_movimiento) {
            var url = base_url + 'stock/cargar_movimiento'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Id: this.egresoDato.Id,
                Cantidad: this.egresoDato.Cantidad,
                Descripcion: this.egresoDato.Descripcion_egreso,
                Jornada_id: this.egresoDato.Jornada_id,
                Tipo_movimiento: tipo_movimiento
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

        //// STOCK |  LISTADO PRODUCTOS EN FALTA
        getProductosFalta: function () {
            var url = base_url + 'stock/obtener_productos_falta?categoria=' + this.filtro_categoria; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaProductosFalta = response.data

                console.log(response.data)
            }).catch(error => {
                alert("mal");
                console.log(error)

            });
        },

        /// FIN STOCK -------------------------------


        //// CLIENTES |  MOSTRAR LISTADO DE CATEGORIAS
        getListadoClientes: function () {
            var url = base_url + 'clientes/obtener_listado_de_clientes'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaClientes = response.data
                console.log(response.data)
            }).catch(error => {
                //alert("mal");
                console.log(error.response.data)

            });
        },

        //// CLIENTES |  LISTADO COMPLETO AVANZADO
        getListadoClientesAvanzado: function () {
            var url = base_url + 'clientes/obtener_listado_de_clientes_avanzado'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaClientes = response.data
                console.log(response.data)
            }).catch(error => {
                //alert("mal");
                console.log(error.response.data)

            });
        },

        //// CLIENTES |  EDITAR
        editarCliente: function (item) {
            this.clienteDato = item;
        },

        //// CLIENTES |  LIMPIAR FORMULARIO
        limpiarFormCliente: function () {
            this.clienteDato = {};
        },

        //// CLIENTES | CREAR O EDITAR
        crear_cliente: function () {
            var url = base_url + 'clientes/crear_cliente'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.clienteDato
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Clientes')

                this.clienteDato.Id = response.data.Id;
                //this.texto_boton = "Actualizar"
                this.clienteDato = {}

                this.getListadoClientes();

            }).catch(error => {
                //alert("mal");
                console.log(error.response.data)

            });
        },



        //// PROVEEDORES  | MOSTRAR LISTADO
        getListadoProveedores: function (Rubro_id) {
            var url = base_url + 'proveedores/obtener_proveedores?Rubro_id=' + Rubro_id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaProveedores = response.data

                console.log(this.listaProveedores)
            });
        },

        //// PROVEEDORES  | MOSTRAR LISTADO
        getListadoProveedores_select: function (Rubro_id) {
            var url = base_url + 'proveedores/obtener_proveedores_select'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaProveedores_select = response.data
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

                this.getListadoProveedores(0);

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

                    this.getListadoProveedores(0);

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
                    this.getListadoProveedores(0);
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

        //// PROVEEDORES RUBROS  | MOSTRAR LISTADO
        getListadoRubro: function (estado) {
            var url = base_url + 'proveedores/obtener_rubros'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaRubros = response.data
            });
        },

        //// PROVEEDORES RUBROS  | LIMPIAR EL FORMULARIO DE CREAR
        limpiarFormularioRubros() {
            this.rubroDato = {}
            this.texto_boton = "Cargar";
        },

        //// PROVEEDORES RUBROS  | Carga el formulario  para editar
        editarFormularioRubros(rubroDato) {
            //this.usuario = {};
            this.rubroDato = rubroDato;
            this.texto_boton = "Actualizar";
        },

        //// PROVEEDORES RUBROS  | CREAR O EDITAR 
        crearRubro: function () {
            var url = base_url + 'proveedores/cargar_rubro'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.rubroDato
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Proveedores')

                this.rubroDato.Id = response.data.Id;
                this.texto_boton = "Actualizar"

                this.getListadoRubro();

            }).catch(error => {
                alert("mal");
                console.log(error)
                toastr.success('Proceso realizado correctamente', 'Proveedores')
            });
        },


        //// COMPRAS  | MOSTRAR LISTADO
        getListadoCompras: function (rubro_id, inicio, final, periodo_id) {
            var url = base_url + 'compras/obtener_compras'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Fecha_inicio: inicio,
                Fecha_fin: final,
                Periodo_id: periodo_id,
                Rubro_id: rubro_id
            }).then(response => {
                
                this.listaCompras = response.data
                console.log(response.data)

            }).catch(error => {
                
                console.log(error)
                toastr.danger('Error al listar compras', 'Compras')
            });
        },

        //// COMPRAS  | LIMPIAR EL FORMULARIO DE CREAR
        limpiarFormularioCompras() {
            this.compraDatos = {    'Fecha_compra': hoy_php,
                                    'Fecha_vencimiento_pago': hoy_php,
                                    'Neto': 0,
                                    'No_gravado': 0,
                                    'IVA': 0,
                                    'Descripcion': "..",
                                }
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

                this.getListadoCompras(null, null, null, 0);

                

            }).catch(error => {
                alert("mal");
                console.log(error)
                toastr.success('Proceso realizado correctamente', 'Usuarios')
            });
        },

        //// COMPRAS  | Dasactivar
        desactivarCompra: function (Id) {
            var url = base_url + 'compras/desactivar_compra'; // url donde voy a mandar los datos

            var opcion = confirm("Recuerde que al eliminar una compra, no se eliminarán los registros de pagos vinculados a la misma. ¿Esta seguro de eliminar a este compra?");
            if (opcion == true) {
                axios.post(url, {
                    token: token,
                    Id: Id
                }).then(response => {

                    toastr.success('Proceso realizado correctamente', 'Compras')

                    this.getListadoCompras(null, null, null, 0);
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
                    this.getListadoCompras(null, null, null, 0);
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

        //// COMPRAS | SUMA PARA DAR TOTAL DE UNA COMPRA
        sumarComrpra: function (n1, n2, n3) {

            if (n1 != null) { var N1 = parseInt(n1); } else { var N1 = 0; }
            if (n2 != null) { var N2 = parseInt(n2); } else { var N2 = 0; }
            if (n3 != null) { var N3 = parseInt(n3); } else { var N3 = 0; }

            return N1 + N2 + N3;
        },

        //// CALCULAR TOTAL DE LAS COMPRAS PAGADAS
        totalCompras: function (datos) {

            var Total = 0;

            if (datos != null & datos.length > 0) {
                for (let index = 0; index < datos.length; index++) 
                {
                    Total = Total + parseInt(datos[index].Neto) + parseInt(datos[index].IVA) + parseInt(datos[index].No_gravado);
                }
            }
            return Total;
        },


        //// CHEQUES  | MOSTRAR LISTADO
        getListadoCheques: function (Estado) {
            var url = base_url + 'finanzas/obtener_cheques'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Estado: Estado
            }).then(response => {
                this.listaCheques = response.data
            });
        },

        //// CHEQUES  | MOSTRAR LISTADO
        getListadoChequesDiferenciados: function () {
            var url = base_url + 'finanzas/obtener_cheques_diferenciados'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
            }).then(response => {
                this.listaChequesDiferenciados = response.data
                //console.log(this.listaChequesDiferenciados)
            });
        },

        //// CHEQUES  | LIMPIAR EL FORMULARIO DE CREAR
        limpiarFormularioCheques() {
            this.chequeData = {}
            this.texto_boton = "Cargar";
        },

        //// CHEQUES  | Carga el formulario  para editar
        editarFormularioCheque(chequeData) {
            //this.usuario = {};
            this.chequeData = chequeData;
            this.texto_boton = "Actualizar";
        },

        //// CHEQUES  | Carga el formulario  para editar
        archivoSeleccionado(event) {
            this.Archivo = event.target.files[0]
            //this.texto_boton = "Actualizar"
        },

        //// CHEQUES  | CREAR O EDITAR una SEGUIMIENTO
        crearCheque: function () {
            var url = base_url + 'finanzas/cargar_cheques'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.chequeData
            }).then(response => {

                this.chequeData.Id = response.data.Id;

                /// si eso se ralizó bien, debe comprobar si hay un archivo a cargar.
                if (this.Archivo != null) {
                    var url = base_url + 'finanzas/subirImagen/?Id=' + this.chequeData.Id;
                    this.preloader = 1;

                    //const formData = event.target.files[0];
                    const formData = new FormData();
                    formData.append("Archivo", this.Archivo);

                    formData.append('_method', 'PUT');

                    //Enviamos la petición
                    axios.post(url, formData)
                        .then(response => {

                            this.chequeData.Imagen = response.data.Imagen;

                            toastr.success('El archivo se cargo correctamente', 'Finanzas')
                            this.preloader = 0;
                            this.getListadoCheques(1);

                        }).catch(error => {
                            alert("MAL LA CARGA EN FUNCIÓN DE CARGAR ARCHIVO");
                            this.preloader = 0;
                            //this.chequeData.Imagen = response.data.Imagen;
                        });
                }
                // si lo hay lo carga, si no lo hay no hace nada

                this.getListadoCheques(0);
                this.Archivo = null
                this.texto_boton = "Actualizar"
                toastr.success('Datos actualizados correctamente', 'Finanzas')

            }).catch(error => {
                alert("MAL LA CARGA EN FUNCIÓN DE CARGAR DATOS");
            });
        },

        //// CHEQUES  | Dasactivar
        desactivarCheque: function (Id) {
            var url = base_url + 'finanzas/desactivar_cheques'; // url donde voy a mandar los datos

            var opcion = confirm("¿Esta seguro de eliminar a este cheques?");
            if (opcion == true) {
                axios.post(url, {
                    token: token,
                    Id: Id
                }).then(response => {

                    toastr.success('Proceso realizado correctamente', 'Cheques')

                    this.getListadoCheques(0);

                }).catch(error => {
                    alert("mal");
                    console.log(error)

                });
            }
        },

        //// FONDO  | GENERAR CONSULTA DE MOVIMIENTOS
        consultarMovimientos: function (inicio, final, jornada_id) {

            this.getMovimientosEfectivo(inicio, final, jornada_id);
            this.getMovimientosCheques(inicio, final, jornada_id);
            this.getMovimientosTransferencias(inicio, final, jornada_id);
            this.getMovimientosMPago(inicio, final, jornada_id);
        },

        //// FONDO  | MOSTRAR LISTADO MOVIMIENTOS
        getMovimientosEfectivo: function (inicio, final, jornada_id) {
            var url = base_url + 'finanzas/obtener_movimientos_efectivo_fondo'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Fecha_inicio: inicio,
                Fecha_fin: final,
                Jornada_id: jornada_id
            }).then(response => {
                this.listaMovimientosEfectivo = response.data
            });
        },

        //// FONDO  | MOSTRAR LISTADO MOVIMIENTOS
        getMovimientosTransferencias: function (inicio, final, jornada_id) {
            var url = base_url + 'finanzas/obtener_movimientos_transferencia_fondo'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Fecha_inicio: inicio,
                Fecha_fin: final,
                Jornada_id: jornada_id
            }).then(response => {
                this.listaMovimientosTransferencias = response.data
            });
        },

        //// FONDO  | MOSTRAR LISTADO MOVIMIENTOS
        getMovimientosMPago: function (inicio, final, jornada_id) {
            var url = base_url + 'finanzas/obtener_movimientos_mercadopago_fondo'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Fecha_inicio: inicio,
                Fecha_fin: final,
                Jornada_id: jornada_id
            }).then(response => {
                this.listaMovimientosMPago = response.data
            });
        },

        //// FONDO  | MOSTRAR LISTADO MOVIMIENTOS
        getMovimientosCheques: function (inicio, final, jornada_id) {
            var url = base_url + 'finanzas/obtener_movimientos_cheques_fondo'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Fecha_inicio: inicio,
                Fecha_fin: final,
                Jornada_id: jornada_id
            }).then(response => {
                this.listaMovimientosCheques = response.data
            });
        },

        limpiarFormularioMovimiento: function () {
            this.movimientoDatos = {};
        },

        

        //// FINANZAS | LISTADO DE CHEQUES
        getCheques: function (Estado) {
            var url = base_url + 'finanzas/obtener_cheques'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Estado: Estado,

            }).then(response => {
                this.listaCheques = response.data;
            }).catch(error => {
                console.log(error.response.data)
            });
        },

        //// FINANZAS | MOVIMIENTOS | CREAR O EDITAR EN EFECTIVO
        pagoConCheque: function (datos) {
            
            var url = base_url + 'finanzas/cargar_movimiento_cheques'; // url donde voy a mandar los datos
            axios.post(url, {
                token: token,
                Datos: datos,
                Origen_movimiento: 'Caja',
                Op: 0,
                Fila_movimiento: Get_Id,

            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Compras')

                
                this.texto_boton = "Actualizar"
                this.consultarMovimientos(this.Filtro_fecha_inicial, this.Filtro_fecha_final, this.filtro_jornada);
                this.getCheques(1);

            }).catch(error => {
                console.log(error.response.data)
            });
        },

        //// FINANZAS | CHEQUES  | LIMPIAR EL FORMULARIO DE CREAR
        limpiarFormularioCheques() {
            this.chequeData = { 'Tipo': 1, 'Observaciones': '', };
            this.texto_boton = "Cargar";
        },

        //// FINANZAS | CHEQUES  | Carga el formulario  para editar
        editarFormularioCheque(chequeData) {
            //this.usuario = {};
            this.chequeData = chequeData;
            this.texto_boton = "Actualizar";
        },

        //// FINANZAS | CHEQUES  | Carga el formulario  para editar
        archivoSeleccionado(event) {
            this.Archivo = event.target.files[0]
            //this.texto_boton = "Actualizar"
        },

        //// FINANZAS | MOVIMIENTOS | LIMPIAR FORMULARIO SEGUIMIENTO
        limpiarFormularioMovimiento: function () {
            this.movimientoDatos =  {'Monto_bruto': 0, "Observaciones":""}
        },

        //// FINANZAS | CHEQUES  |  CREAR O EDITAR una SEGUIMIENTO
        crearCheque: function () {
            var url = base_url + 'finanzas/cargar_cheques'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.chequeData
            
            }).then(response => {

                this.chequeData.Id = response.data.Id;

                /// si eso se ralizó bien, debe comprobar si hay un archivo a cargar.
                if (this.Archivo != null) 
                {
                    var url = base_url + 'finanzas/subirImagen/?Id=' + this.chequeData.Id;
                    this.preloader = 1;

                    //const formData = event.target.files[0];
                    const formData = new FormData();
                    formData.append("Archivo", this.Archivo);

                    formData.append('_method', 'PUT');

                    //Enviamos la petición
                    axios.post(url, formData)
                        .then(response => {

                            this.chequeData.Imagen = response.data.Imagen;

                            toastr.success('El archivo se cargo correctamente', 'Finanzas')
                            this.preloader = 0;

                        }).catch(error => {
                            this.preloader = 0;
                            console.log(error.response.data)
                        });
                }

                //// FINANZAS | CARGANDO MOVIMIENTO
                var url = base_url + 'finanzas/cargar_movimiento_cheques'; // url donde voy a mandar los datos

                axios.post(url, {
                    token: token,
                    Datos: response.data,
                    Origen_movimiento: 'Caja',
                    Tipo_movimiento : this.movimientoDatos.Tipo_movimiento,
                    Fila_movimiento: Get_Id,
                    Op: 1,

                }).then(response => {

                    toastr.success('Proceso realizado correctamente', 'Comandas')

                    this.texto_boton = "Actualizar"
                    this.consultarMovimientos(this.Filtro_fecha_inicial, this.Filtro_fecha_final, this.filtro_jornada);
                    this.getCheques(1)

                }).catch(error => {
                    alert("mal");
                    console.log(error.response.data)
                });

                // si lo hay lo carga, si no lo hay no hace nada
                this.Archivo = null
                this.texto_boton = "Actualizar"
                toastr.success('Datos actualizados correctamente', 'Finanzas')

            }).catch(error => {
                alert("MAL LA CARGA EN FUNCIÓN DE CARGAR DATOS");
            });
        },

        //// FINANZAS | MOVIMIENTOS | CREAR O EDITAR EN EFECTIVO
        crear_movimiento: function () {
            
            var url = base_url + 'finanzas/cargar_movimiento'; // url donde voy a mandar los datos
            axios.post(url, {
                token: token,
                Datos: this.movimientoDatos,
                Origen_movimiento: 'Caja',
                Op: this.movimientoDatos.Op,
                Fila_movimiento: Get_Id,

            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Comandas')

                
                this.texto_boton = "Actualizar"
                this.consultarMovimientos(this.Filtro_fecha_inicial, this.Filtro_fecha_final, this.filtro_jornada);
                this.getCheques(1);

            }).catch(error => {
                console.log(error.response.data)
            });
        },

        //// FINANZAS | MOVIMIENTOS |  MOVIMIENTOS EN EFECTIVO
        getMovimientos: function () {
            var url = base_url + 'finanzas/obtener_movimientos'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Origen_movimiento: 'Comandas',
                Fila_movimiento: Get_Id,

            }).then(response => {
                this.listaMovimientos = response.data.Datos;
                this.Total_pagado = response.data.Total_pagado;
            }).catch(error => {
                console.log(error.response.data)
            });
        },

        /// INFO PARA MODAL 
        infoEtapa: function (observaciones) {
            this.infoModal = observaciones;
        },

        //// COMUNES  | CANTIDAD DE DIAS ENTRE DOS FECHAS
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


        //// 
    },


    ////// ACCIONES COMPUTADAS     
    computed:
    {
        buscarItems: function () {
            return this.itemsCarta.filter((item) => item.Nombre_item.toLowerCase().includes(this.buscar));
        },

        classComandas: function () {

            if (this.listaComandas.Cantidad > 0 && this.listaDeliverys.Cantidad > 0) {
                return 'col-lg-6'
            }
            else if (this.listaComandas.Cantidad > 0 && this.listaDeliverys.Cantidad == 0) {
                return 'col-lg-12'
            }
        },

        classDeliverys: function () {

            if (this.listaComandas.Cantidad > 0 && this.listaDeliverys.Cantidad > 0) {
                return 'col-lg-6'
            }
            else if (this.listaComandas.Cantidad == 0 && this.listaDeliverys.Cantidad > 0) {
                return 'col-lg-12'
            }
        },

        buscarStock: function () {
            return this.listaStock.filter((item) => item.Nombre_item.toLowerCase().includes(this.buscar));
        },

        buscarProveedor: function () {
            return this.listaProveedores.filter((item) => item.Datos_proveedor.Nombre_proveedor.toLowerCase().includes(this.buscar));
        },
    }
});
///--------------


///         --------------------------------------------------------------------   ////
//// Elemento para el manejo de comandas con Id
new Vue({
    el: '#comandas',

    created: function () {
        this.getListadoItems();
        this.getListadoCategorias();

        this.getListadoItemsComanda();
        this.getComandaId();

        this.getMovimientos();

    },

    data: {

        Rol_usuario: '',
        texto_boton: "Cargar",
        buscar: '',

        itemsCarta: [],
        itemCarta: { 'Id': '', 'Categoria_id': '', 'Apto_delivery': '', 'Nombre': '', 'Imagen': '', 'Descripcion': '', 'Precio_venta': '', 'Tiempo_estimado_entrega': '', 'Activo': '1' },
        Archivo: '',
        preloader: '0',

        categoriasCarta: [],
        categoria: { 'Id': '', 'Nombre_categoria': '', 'Descripcion': '' },

        itemsComanda: [],
        Comanda_id: '',
        datoComanda: {},
        itemComanda: { 'Id': '', 'Comanda_id': '', 'Item_carga_id': '', 'Estado': '' },

        buscar_nombre: '',
        descuento: '',

        // FINANZAS
        chequeData: { 'Tipo': 1, 'Observaciones': '', },
        descripcionMovimiento: [],
        movimientoDatos: {'Monto_bruto': 0, "Observaciones":""},
        listaMovimientos : [],
        Total_pagado: 0,
        infoModal: {'Observaciones':''},

    },

    methods:
    {

        //// MOSTRAR DATOS COMANDA
        getComandaId: function () {
            var url = base_url + 'restaurant/datosComanda/?Id=' + Get_Id;  //averiguar como tomar el Id que viene por URL aca

            axios.get(url).then(response => {
                this.datoComanda = response.data[0]
                //console.log(this.datoComanda)
            });
        },
        
        //// MOSTRAR LISTADO DE TODOS LOS ITEMS ITEMS  
        getListadoItems: function () {
            var url = base_url + 'restaurant/mostrarItemsActivos';

            axios.get(url).then(response => {
                this.itemsCarta = response.data
            });
        },

        //// MOSTRAR LISTADO DE ITEMS DE LA CARTA POR CATEGORIA   
        cargarItemsbyCategoria: function (Id) {
            var url = base_url + 'restaurant/mostrarItemsCategoria/?Id=' + Id;

            axios.get(url).then(response => {
                this.itemsCarta = response.data
            });

        },

        //// MOSTRAR LISTADO DE Categorias DE LA CARTA    
        getListadoCategorias: function () {
            var url = base_url + 'restaurant/obtener_categorias_items';

            axios.get(url).then(response => {
                this.categoriasCarta = response.data
            });
        },

        //// MOSTRAR LISTADO DE ITEMS DE LA COMANDA  
        getListadoItemsComanda: function () {
            var url = base_url + 'restaurant/mostrarItemsComanda/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.get(url).then(response => {

                this.itemsComanda = response.data
            });
        },

        //// Info item comanda en modal
        infoItem: function (item) {
            this.itemCarta = item;
        },

        //// AGREGAR ITEM A LA COMANDA
        addItem: function (Id) {

            var url = base_url + 'restaurant/cargar_item_comanda'; // url donde voy a mandar los datos

            this.itemComanda.Item_carga_id = Id;
            this.itemComanda.Comanda_id = Get_Id;
            this.itemComanda.Estado = 0;

            axios.post(url, {
                itemComandaData: this.itemComanda
            }).then(response => {



                //this.itemComanda.Id = response.data.Id;
                this.texto_boton = "Actualizar"
                this.getListadoItemsComanda();
                toastr.success('Item añadido correctamente', 'Comandas')

            }).catch(error => {
                alert("mal");
            });
        },

        //// QUITAR ITEM DE LA COMANDA    
        eliminar: function (Id, tbl) {
            var url = base_url + 'restaurant/eliminar'; // url donde voy a mandar los datos

            axios.post(url, {
                Id: Id,
                tabla: tbl
            }).then(response => {

                this.getListadoItemsComanda();
                toastr.success('Item eliminado correctamente', 'Comandas')

            }).catch(error => {
                alert("mal");
            });
        },

        //// ENTREGARITEM
        entregarItem: function (Id, Apto_stock, Item_stock_id) {
            var url = base_url + 'restaurant/entregar_item'; // url donde voy a mandar los datos


            axios.post(url, {
                Id: Id,
                Apto_stock: Apto_stock,
                Item_stock_id: Item_stock_id,
                Modo_pago: this.datoComanda.Modo_pago

            }).then(response => {

                this.getListadoItemsComanda();
                toastr.success('Proceso realizado correctamente', 'Comandas')

            }).catch(error => {
                alert("mal");
            });
        },

        //// COMANDA | SETEAR MODO DE PAGO
        modoPago: function () {
            var url = base_url + 'restaurant/setear_modo_pago_comanda?Id=' + Get_Id; // url donde voy a mandar los datos


            axios.post(url, {
                Modo_pago: this.datoComanda.Modo_pago

            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Comandas')

            }).catch(error => {
                alert("mal");
            });
        },

        //// COMANDA |  Hora de entregas en mesa
        entregasEnMesa: function (comanda_id, tipo, cantCompras, cliente_id, valorCuenta) {
            var url = base_url + 'restaurant/entrega_en_mesa'; // url donde voy a mandar los datos

            axios.post(url, {
                Comanda_id: comanda_id,
                Tipo: tipo,
                ValorCuenta: valorCuenta,
                CantCompras: cantCompras,
                Cliente_id: cliente_id
            }).then(response => {

                
                toastr.success('Proceso realizado correctamente', 'Comandas')
                this.getComandaId();

                /// Descontar insumos
                    if(tipo == "cerrar") 
                    {
                        var url = base_url + 'stock/descontarInsumosStock'; // url donde voy a mandar los datos

                            axios.post(url, {
                                token: token,
                                Movimiento_id: comanda_id,
                                Tipo: "Comanda"

                            }).then(response => {

                                toastr.success('Proceso realizado correctamente', 'Stock')
                                this.texto_boton = "Actualizar"
                                
                                this.getDatosventas();

                                //console.log(response.data)


                            }).catch(error => {
                                console.log(error.response.data)
                            });
                        
                    }

            }).catch(error => {
                alert("mal");
            });
        },

        //// SUMAR CUENTA   
        sumarCuenta: function (items) {
            //// SUMAR LOS ENTREGADOS
            var Total_entregados = 0;
            for (var i = 0; i < items.Entregados.length; i++) {
                var item = 0;

                if (isFinite(items.Entregados[i].Precio_venta)) {
                    item = parseInt(items.Entregados[i].Precio_venta);
                }
                Total_entregados = Total_entregados + item;

            }

            /// SUMAR LOS PENDIENTES
            var Total_pendientes = 0;

            for (var i = 0; i < items.Pendientes.length; i++) {
                var item = 0;

                if (isFinite(items.Pendientes[i].Precio_venta)) {
                    item = parseInt(items.Pendientes[i].Precio_venta);
                }
                Total_pendientes = Total_pendientes + item;

            }


            Total = Total_entregados + Total_pendientes;
            return Total

        },


        //// FORMATO FECHA
        formatoFecha: function (fecha) {
            return fecha.replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
        },
        //// FORMATO HORA
        formatoHora: function (hora) {
            separador = ":",
                arrayHora = hora.split(separador);

            return arrayHora[0] + ':' + arrayHora[1] + 'hs'

        },

        //// CARGAR DESCUENTO
        cargarDescuento: function () {
            var url = base_url + 'restaurant/cargarDescuento'; // url donde voy a mandar los datos

            axios.post(url, {
                Comanda_id: Get_Id, Descuento: this.descuento
            }).then(response => {

                this.getComandaId();
                toastr.success('Proceso realizado correctamente', 'Comandas')

            }).catch(error => {
                alert("mal");
            });
        },

        //// CONTAR ITEMS
        contarItems: function (items) {
            var cant_entregados = items.Entregados.length;
            var cant_pendientes = items.Pendientes.length;

            return cant_entregados + cant_pendientes;
        },

        //// FINANZAS | CHEQUES  | LIMPIAR EL FORMULARIO DE CREAR
        limpiarFormularioCheques() {
            this.chequeData = { 'Tipo': 1, 'Observaciones': '', };
            this.texto_boton = "Cargar";
        },

        //// FINANZAS | CHEQUES  | Carga el formulario  para editar
        editarFormularioCheque(chequeData) {
            //this.usuario = {};
            this.chequeData = chequeData;
            this.texto_boton = "Actualizar";
        },

        //// FINANZAS | CHEQUES  | Carga el formulario  para editar
        archivoSeleccionado(event) {
            this.Archivo = event.target.files[0]
            //this.texto_boton = "Actualizar"
        },

        ///// FINANZAS | MOVIMIENTOS | LIMPIAR FORMULARIO SEGUIMIENTO
        limpiarFormularioMovimiento: function () {
            this.movimientoDatos =  {'Monto_bruto': 0, "Observaciones":""}
        },

        //// FINANZAS | CHEQUES  |  CREAR O EDITAR una SEGUIMIENTO
        crearCheque: function () {
            var url = base_url + 'finanzas/cargar_cheques'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.chequeData
            
            }).then(response => {

                this.chequeData.Id = response.data.Id;

                /// si eso se ralizó bien, debe comprobar si hay un archivo a cargar.
                if (this.Archivo != null) 
                {
                    var url = base_url + 'finanzas/subirImagen/?Id=' + this.chequeData.Id;
                    this.preloader = 1;

                    //const formData = event.target.files[0];
                    const formData = new FormData();
                    formData.append("Archivo", this.Archivo);

                    formData.append('_method', 'PUT');

                    //Enviamos la petición
                    axios.post(url, formData)
                        .then(response => {

                            this.chequeData.Imagen = response.data.Imagen;

                            toastr.success('El archivo se cargo correctamente', 'Finanzas')
                            this.preloader = 0;

                        }).catch(error => {
                            this.preloader = 0;
                            console.log(error.response.data)
                        });
                }

                //// FINANZAS | CARGANDO MOVIMIENTO
                var url = base_url + 'finanzas/cargar_movimiento_cheques'; // url donde voy a mandar los datos

                axios.post(url, {
                    token: token,
                    Datos: response.data,
                    Origen_movimiento: 'Comandas',
                    Tipo_movimiento : this.movimientoDatos.Tipo_movimiento,
                    Fila_movimiento: Get_Id,
                    Op: 1,

                }).then(response => {

                    toastr.success('Proceso realizado correctamente', 'Comandas')

                    this.texto_boton = "Actualizar"
                    this.getMovimientos()

                }).catch(error => {
                    alert("mal");
                    console.log(error.response.data)
                });

                // si lo hay lo carga, si no lo hay no hace nada
                this.Archivo = null
                this.texto_boton = "Actualizar"
                toastr.success('Datos actualizados correctamente', 'Finanzas')

            }).catch(error => {
                alert("MAL LA CARGA EN FUNCIÓN DE CARGAR DATOS");
            });
        },

        //// FINANZAS | MOVIMIENTOS | CREAR O EDITAR EN EFECTIVO
        crear_movimiento: function () {
            
            var url = base_url + 'finanzas/cargar_movimiento'; // url donde voy a mandar los datos
            axios.post(url, {
                token: token,
                Datos: this.movimientoDatos,
                Origen_movimiento: 'Comandas',
                Op: 1,
                Fila_movimiento: Get_Id,

            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Comandas')

                
                this.texto_boton = "Actualizar"
                this.getMovimientos();

            }).catch(error => {
                console.log(error.response.data)
            });
        },

        //// FINANZAS | MOVIMIENTOS |  MOVIMIENTOS EN EFECTIVO
        getMovimientos: function () {
            var url = base_url + 'finanzas/obtener_movimientos'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Origen_movimiento: 'Comandas',
                Fila_movimiento: Get_Id,

            }).then(response => {
                this.listaMovimientos = response.data.Datos;
                this.Total_pagado = response.data.Total_pagado;
            }).catch(error => {
                console.log(error.response.data)
            });
        },
    },

    ////// ACCIONES COMPUTADAS     
    computed:
    {
        buscarItems: function () {
            return this.itemsCarta.filter((item) => item.Nombre_item.toLowerCase().includes(this.buscar));
        }
    }
});


///         --------------------------------------------------------------------   ////
//// Elemento para el manejo de delivery con Id
new Vue({
    el: '#delivery',

    created: function () {
        this.getListadoItems();
        this.getListadoCategorias();

        this.getListadoItemsPedidosDelivery();
        this.getDeliveryId();
        this.getListadoUsuariosRepartidores();

        this.getMovimientos();

        //// DATOS DE USUARIO PARA EL SISTEMA
        this.setVariablesUsuario(Nombre, Id, Rol_id, Negocio_id, Tipo_suscripcion);

    },

    data: {

        Rol_usuario: '',
        texto_boton: "Cargar",
        buscar: '',
        Archivo: '',
        preloader: '0',

        itemsCarta: [],
        itemCarta: { 'Id': '', 'Categoria_id': '', 'Apto_delivery': '', 'Nombre': '', 'Imagen': '', 'Descripcion': '', 'Precio_venta': '', 'Tiempo_estimado_entrega': '', 'Activo': '1' },

        categoriasCarta: [],
        categoria: { 'Id': '', 'Nombre_categoria': '', 'Descripcion': '' },

        descuento: '',

        itemsDelivery: [],
        Delivery: '',
        datoDelivery: {

            'Repartidor_id': 0,
            'Nombre_cliente': '',
            'Direccion': '',
            'Telefono': '',
            'Modo_pago': '',
        },
        itemDelivery: { 'Id': '', 'Delibery_id': '', 'Item_id': '', 'Apto_stock': 0 },

        listaUsuariosRepartidores: [],

        // FINANZAS
        chequeData: { 'Tipo': 1, 'Observaciones': '', },
        descripcionMovimiento: [],
        movimientoDatos: {'Monto_bruto': 0, "Observaciones":""},
        listaMovimientos : [],
        Total_pagado: 0,
        infoModal: {'Observaciones':''},

        //// DATOS DE USUARIO PARA EL SISTEMA
        Nombre:'',
        Usuario_id:'',
        Rol_id:'',
        Negocio_id:'',
        Tipo_suscripcion,
    },

    methods:
    {

        //// DATOS DE USUARIO PARA EL SISTEMA
        setVariablesUsuario: function (Nombre, Id, Rol_id, Negocio_id, Tipo_suscripcion) {
            this.Nombre = Nombre;
            this.Usuario_id = Id;
            this.Rol_id = Rol_id;
            this.Negocio_id = Negocio_id;
            this.Tipo_suscripcion = Tipo_suscripcion;
        },

        //// MOSTRAR DATOS DELIVERY
        getDeliveryId: function () {
            var url = base_url + 'restaurant/datosDelivery/?Id=' + Get_Id;  //averiguar como tomar el Id que viene por URL aca

            axios.get(url).then(response => {
                this.datoDelivery = response.data[0]

            });
        },

        //// MOSTRAR LISTADO DE TODOS LOS ITEMS APTOS PARA DELIVERY 
        getListadoItems: function () {
            var url = base_url + 'restaurant/mostrarItemsAptoDelivery';

            axios.get(url).then(response => {
                this.itemsCarta = response.data
            });
        },

        //// MOSTRAR LISTADO DE TODOS LOS ITEMS APTOS PARA DELIVERY 
        getListadoTodosItems: function () {
            var url = base_url + 'restaurant/mostrarItems';

            axios.get(url).then(response => {
                this.itemsCarta = response.data
            });
        },

        //// MOSTRAR LISTADO DE Categorias DE LA CARTA    
        getListadoCategorias: function () {
            var url = base_url + 'restaurant/obtener_categorias_items';

            axios.get(url).then(response => {
                this.categoriasCarta = response.data
            });
        },

        //// MOSTRAR LISTADO DE ITEMS DE LA CARTA POR CATEGORIA   
        cargarItemsbyCategoria: function (Id) {
            var url = base_url + 'restaurant/mostrarItemsCategoria/?Id=' + Id;

            axios.get(url).then(response => {
                this.itemsCarta = response.data
            });
        },

        //// MOSTRAR LISTADO DE ITEMS Del DELIVERY
        getListadoItemsPedidosDelivery: function () {

            var url = base_url + 'restaurant/mostrarItemsDelivery/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.get(url).then(response => {

                this.itemsDelivery = response.data
            });
        },

        
        //// Info item comanda en modal
        infoItem: function (item) {
            this.itemCarta = item;
        },

        //// QUITAR ITEM DE LA COMANDA    
        eliminar: function (Id, tbl, Item_id) {
            var url = base_url + 'restaurant/eliminar'; // url donde voy a mandar los datos

            axios.post(url, {
                Id: Id, tabla: tbl
            }).then(response => {

                this.getListadoItemsPedidosDelivery();
                toastr.success('Item eliminado correctamente', 'Comandas')

                /// DEBE VOLVER A AÑADIR UN ITEM AL STOCK 
                var url = base_url + 'stock/cargar_movimiento'; // url donde voy a mandar los datos
                axios.post(url, {
                    token: token,
                    Id: Item_id,
                    Cantidad: 1,
                    Descripcion: 'Reingreso por cancelación en delivery.',
                    Jornada_id: null,
                    Tipo_movimiento: 1
                }).then(response => {

                    toastr.success('Proceso realizado correctamente', 'Stock')
                }).catch(error => {
                    alert("mal");
                    console.log(error.response.data)

                });

            }).catch(error => {
                alert("mal");
            });
        },

        //// SUMAR CUENTA   
        sumarCuenta: function (items) {
            //// SUMAR LOS ENTREGADOS
            var Total = 0;
            for (var i = 0; i < items.length; i++) {
                var item = 0;

                if (isFinite(items[i].Precio_venta)) {
                    item = parseInt(items[i].Precio_venta);
                }
                Total = Total + item;
            }
            return Total
        },

        //// AGREGAR ITEM AL DELIVERY
        addItemDelivery: function (Id, Apto_stock) {

            var url = base_url + 'restaurant/cargar_item_delivery'; // url donde voy a mandar los datos

            this.itemDelivery.Item_carga_id = Id;
            this.itemDelivery.Apto_stock = Apto_stock;
            this.itemDelivery.Delivery_id = Get_Id;
            this.itemDelivery.Estado = 0;

            axios.post(url, {
                itemDeliveryData: this.itemDelivery
            }).then(response => {

                //this.itemsDelivery.Id = response.data.Id;
                this.texto_boton = "Actualizar"
                this.getListadoItemsPedidosDelivery();
                toastr.success('Item añadido correctamente', 'Delivery')

            }).catch(error => {
                alert("mal");
            });
        },

        //// CARGAR DESCUENTO DELIVERY
        cargarDescuento: function () {

            var url = base_url + 'restaurant/cargarDescuentoDelivery'; // url donde voy a mandar los datos

            axios.post(url, {
                Delivery_id: Get_Id, Descuento: this.descuento
            }).then(response => {

                this.getDeliveryId();
                toastr.success('Proceso realizado correctamente', 'Delivery')

            }).catch(error => {
                alert("mal");
            });
        },

        //// DELIVERY | REABRIR
        reabrir_delivery: function () {

            var url = base_url + 'restaurant/reabrir_delivery'; // url donde voy a mandar los datos

            axios.post(url, {
                Delivery_id: Get_Id,
                Datos: this.datoDelivery
            }).then(response => {

                this.getDeliveryId();
                toastr.success('Proceso realizado correctamente', 'Delivery')

            }).catch(error => {
                alert("mal");
            });
        },

        //// FORMATO FECHA HORA
        formatoFecha_hora: function (fecha) {
            //separador = ":",
            fecha = fecha.split(' ');

            //var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
            var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2');

            var fecha_hora = fecha[1].split(':');
            fecha_hora = fecha_hora[0] + ':' + fecha_hora[1];

            return fecha_hora + 'hs ' + fecha_dia

        },

        //// CERRAR PEDIDO
        cerrarPedido: function (Id, valorCuenta) {


            var url = base_url + 'restaurant/cerrar_delivery'; // url donde voy a mandar los datos

            axios.post(url, {
                Id: Id,
                Valor_cuenta: valorCuenta,
                Datos: this.datoDelivery
            }).then(response => {

                
                toastr.success('Proceso realizado correctamente', 'Delivery')

                /// Descontar insumos
                var url = base_url + 'stock/descontarInsumosStock'; // url donde voy a mandar los datos

                        axios.post(url, {
                            token: token,
                            Movimiento_id: this.datoDelivery.Id,
                            Tipo: "Delivery"

                        }).then(response => {

                            toastr.success('Proceso realizado correctamente', 'Stock')
                            this.texto_boton = "Actualizar"
                            
                            this.getDeliveryId();

                            //console.log(response.data)


                        }).catch(error => {
                            console.log(error.response.data)
                        });
                    
            }).catch(error => {
                console.log(error)
            });
        },

        //// CONTAR ITEMS
        contarItems: function (items) {
            //// SUMAR LOS ENTREGADOS

            return items.length;
        },

        //// COMANDA | SETEAR MODO DE PAGO
        modoPago: function () {
            var url = base_url + 'restaurant/setear_modo_pago_delivery?Id=' + Get_Id; // url donde voy a mandar los datos


            axios.post(url, {
                Modo_pago: this.datoDelivery.Modo_pago

            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Deliverys')

            }).catch(error => {
                console.log(error.response.data)
            });
        },

        //// MOSTRAR LISTADO DE Usuarios Repartidores
        getListadoUsuariosRepartidores: function () {
            var url = base_url + 'restaurant/obtener_Usuarios_repartidores/'; // url donde voy a mandar los datos

            axios.get(url).then(response => {
                this.listaUsuariosRepartidores = response.data
            });
        },

        //// Asignar Cadete
        asignar_cadete: function (Repartidor_id) {
            var url = base_url + 'restaurant/crear_delivery'; // url donde voy a mandar los datos

            axios.post(url, {
                Repartidor_id: Repartidor_id,
                Datos: this.datoDelivery
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Delivery')

                this.texto_boton = "Actualizar"
                this.deliveryData.Id = response.data.Id;

                //console.log(response.data.Id)
                this.getDeliveryId();

            }).catch(error => {
                console.log(error.message)
                this.getDeliveryId();
            });
        },

        ////  DELIVERY | REPORTA QUE EL PEDIDO YA ESTA LISTO EN COCINA
        reportarCocina: function () {
            var url = base_url + 'restaurant/reportarCocina'; // url donde voy a mandar los datos

            axios.post(url, {
                Id: this.datoDelivery.Id
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Delivery')

                this.deliveryData.Id = response.data.Id;

                //console.log(response.data.Id)
                this.getDeliveryId();

            }).catch(error => {
                console.log(error.message)
                this.getDeliveryId();
            });
        },

        ////  DELIVERY | REPORTA QUE EL PEDIDO YA LO RECIBIÓ EL CLIENTE
        reportarEntrega: function () {
            var url = base_url + 'restaurant/reportarEntrega'; // url donde voy a mandar los datos

            axios.post(url, {
                Id: this.datoDelivery.Id
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Delivery')

                this.deliveryData.Id = response.data.Id;

                //console.log(response.data.Id)
                this.getDeliveryId();

            }).catch(error => {
                console.log(error.message)
                this.getDeliveryId();
            });
        },

        /// Cuenta final del delivery
        sumarMontos: function (pedido, delivery, descuento) {


            var Total = parseInt(pedido) + parseInt(delivery) - parseInt(descuento);

            return Total
        },


        //// FINANZAS | CHEQUES  | LIMPIAR EL FORMULARIO DE CREAR
        limpiarFormularioCheques() {
            this.chequeData = { 'Tipo': 1, 'Observaciones': '', };
            this.texto_boton = "Cargar";
        },

        //// FINANZAS | CHEQUES  | Carga el formulario  para editar
        editarFormularioCheque(chequeData) {
            //this.usuario = {};
            this.chequeData = chequeData;
            this.texto_boton = "Actualizar";
        },

        //// FINANZAS | CHEQUES  | Carga el formulario  para editar
        archivoSeleccionado(event) {
            this.Archivo = event.target.files[0]
            //this.texto_boton = "Actualizar"
        },

        //// FINANZAS | MOVIMIENTOS | LIMPIAR FORMULARIO SEGUIMIENTO
        limpiarFormularioMovimiento: function () {
            this.movimientoDatos =  {'Monto_bruto': 0, "Observaciones":""}
        },

        //// FINANZAS | CHEQUES  |  CREAR O EDITAR una SEGUIMIENTO
        crearCheque: function () {
            var url = base_url + 'finanzas/cargar_cheques'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.chequeData
            
            }).then(response => {

                this.chequeData.Id = response.data.Id;

                /// si eso se ralizó bien, debe comprobar si hay un archivo a cargar.
                if (this.Archivo != null) 
                {
                    var url = base_url + 'finanzas/subirImagen/?Id=' + this.chequeData.Id;
                    this.preloader = 1;

                    //const formData = event.target.files[0];
                    const formData = new FormData();
                    formData.append("Archivo", this.Archivo);

                    formData.append('_method', 'PUT');

                    //Enviamos la petición
                    axios.post(url, formData)
                        .then(response => {

                            this.chequeData.Imagen = response.data.Imagen;

                            toastr.success('El archivo se cargo correctamente', 'Finanzas')
                            this.preloader = 0;

                        }).catch(error => {
                            this.preloader = 0;
                            console.log(error.response.data)
                        });
                }

                //// FINANZAS | CARGANDO MOVIMIENTO
                var url = base_url + 'finanzas/cargar_movimiento_cheques'; // url donde voy a mandar los datos

                axios.post(url, {
                    token: token,
                    Datos: response.data,
                    Origen_movimiento: 'Delivery',
                    Tipo_movimiento : this.movimientoDatos.Tipo_movimiento,
                    Fila_movimiento: Get_Id,
                    Op: 1,

                }).then(response => {

                    toastr.success('Proceso realizado correctamente', 'Delivery')

                    this.texto_boton = "Actualizar"
                    this.getMovimientos()

                }).catch(error => {
                    alert("mal");
                    console.log(error.response.data)
                });

                // si lo hay lo carga, si no lo hay no hace nada
                this.Archivo = null
                this.texto_boton = "Actualizar"
                toastr.success('Datos actualizados correctamente', 'Delivery')

            }).catch(error => {
                alert("MAL LA CARGA EN FUNCIÓN DE CARGAR DATOS");
            });
        },

        //// FINANZAS | MOVIMIENTOS | CREAR O EDITAR EN EFECTIVO
        crear_movimiento: function () {
            
            var url = base_url + 'finanzas/cargar_movimiento'; // url donde voy a mandar los datos
            axios.post(url, {
                token: token,
                Datos: this.movimientoDatos,
                Origen_movimiento: 'Delivery',
                Op: 1,
                Fila_movimiento: Get_Id,

            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Delivery')

                
                this.texto_boton = "Actualizar"
                this.getMovimientos();

            }).catch(error => {
                console.log(error.response.data)
            });
        },

        //// FINANZAS | MOVIMIENTOS |  MOVIMIENTOS EN EFECTIVO
        getMovimientos: function () {
            var url = base_url + 'finanzas/obtener_movimientos'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Origen_movimiento: 'Delivery',
                Fila_movimiento: Get_Id,

            }).then(response => {
                this.listaMovimientos = response.data.Datos;
                this.Total_pagado = response.data.Total_pagado;
            }).catch(error => {
                console.log(error.response.data)
            });
        },

        /// INFO PARA MODAL 
        infoEtapa: function (observaciones) {
            this.infoModal = observaciones;
        },

        
    },

    ////// ACCIONES COMPUTADAS     
    computed:
    {
        buscarItems: function () {
            return this.itemsCarta.filter((item) => item.Nombre_item.toLowerCase().includes(this.buscar));
        }
    }
});


///         --------------------------------------------------------------------   ////
//// Elemento para del Dashboard
new Vue({
    el: '#dashboard',

    created: function () {
        this.getVentasHoy();
        this.getVentasAyer();
        this.getVentasMes();
        this.getVentasAnio();
        this.getTimeline(0, null);
        this.getListadoLogPresencia(0, null);

        setInterval(() => { this.getTimeline(0, null); }, 60000); //// funcion para actualizar automaticamente cada 1minuto

        //this.noticiasGoogle(); /// solo disponible pagando
    },

    data: {

        itemsCarta: [],
        //itemCarta: { 'Id': '', 'Categoria_id': '', 'Nombre': '', 'Imagen': '', 'Descripcion': '', 'Precio_venta': '', 'Tiempo_estimado_entrega': '', 'Activo': '1' },
        valorVentasHoy: '',
        valorVentasAyer: '',
        valorVentasMes: '',
        valorVentasAnio: '',
        listaTimeline: [],
        listaLogPresencia: [],
        listaNoticias: {}
    },

    methods:
    {
         //// NOTICIAS DE INTERES
         noticiasGoogle: function () {
            var url = 'https://newsapi.org/v2/top-headlines?' + 'country=ar&' + 'apiKey=91eb4def94be482898af7aab8fed5e4e';

            axios.get(url).then(response => {
                this.listaNoticias = response.data
                console.log(response.json())
            }).catch(error => {
                //alert("mal");
                console.log(error.response.data)

            });
        },

        /// VENTAS DE HOY  
        getVentasHoy: function () {
            var url = base_url + 'dashboard/obtener_listado_comandas_hoy';

            axios.get(url).then(response => {
                this.valorVentasHoy = response.data
            });
        },

        /// VENTAS DE AYER  
        getVentasAyer: function () {
            var url = base_url + 'dashboard/obtener_listado_comandas_de_ayer';

            axios.get(url).then(response => {
                this.valorVentasAyer = response.data
            });
        },

        /// VENTAS DE ESTE MES  
        getVentasMes: function () {
            var url = base_url + 'dashboard/obtener_listado_comandas_de_este_mes';

            axios.get(url).then(response => {
                this.valorVentasMes = response.data
            });
        },

        /// VENTAS DE ESTE AÑO  
        getVentasAnio: function () {
            var url = base_url + 'dashboard/obtener_listado_comandas_de_este_anio';

            axios.get(url).then(response => {
                this.valorVentasAnio = response.data
            });
        },

        /// MOSTRAR LISTADO DE ITEMS DE LA COMANDA  
        getTimeline: function (actual, peticion) ///debo decirle donde estamos, y si quiero ir la siguiente o a la anterior 
        {
            var url = base_url + 'dashboard/infoTimeline';  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                Actual: actual, Peticion: peticion
            }).then(response => {

                this.listaTimeline = response.data


            });
        },

        //// MOSTRAR LISTADO DE Categorias DE LA CARTA    
        getListadoLogPresencia: function (actual, peticion) {
            var url = base_url + 'dashboard/obtener_log_presencia'; // url donde voy a mandar los datos

            axios.post(url, {
                Actual: actual, Peticion: peticion
            }).then(response => {
                this.listaLogPresencia = response.data


            });
        },

        //// FORMATO FECHA HORA
        formatoFecha_hora: function (fecha) {
            //separador = ":",
            fecha = fecha.split(' ');

            //var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
            var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2');

            var fecha_hora = fecha[1].split(':');
            fecha_hora = fecha_hora[0] + ':' + fecha_hora[1];

            return fecha_hora + 'hs ' + fecha_dia

        },

        //// MENSAJE DE LOS INGRESOS A CAJA
        mensaje: function () {
            toastr.warning('Este valor refleja los ingresos en bruto a caja durante el período indicado. Surge del total de las ventas y solo se le han restado los descuentos realizados en la cuenta de cada comanda y de cada delivery.', 'IMPORTANTE')
        }
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
        this.getSueldos();
        this.getSuperiores();
        this.getJornadasTrabajadas();
    },

    data: {

        mostrar: "1",

        usuario: {},

        usuarioFoto: { 'Id': '', 'Nombre': '', 'Imagen': '' },

        listaSuperiores: [],

        listaRoles: [],
        texto_boton: "Cargar",

        listaFormaciones: [],
        formacionData: { 'Id': '', 'Titulo': '', 'Establecimiento': '', 'Anio_inicio': '', 'Anio_finalizado': '', 'Descripcion_titulo': '' },

        listaSueldos: [],
        sueldoDato: { 'Id': '', 'Sueldo_pactado': '0', 'Sueldo_abonado': '0', 'Bonificacion': '0', 'Descuento': '0', 'Fecha': '', 'Costes_impositivos_adicionales': '0', 'Observaciones': '' },

        Fecha_desde: '0',
        Fecha_hasta: '0',

        listaJornadasTrabajadas: []
    },

    methods:
    {

        //// MOSTRAR LISTADO DE ROLES  
        getListadoRoles: function () {
            var url = base_url + 'restaurant/obtener_roles'; // url donde voy a mandar los datos

            axios.get(url).then(response => {
                this.listaRoles = response.data
            });
        },

        //// OBTENER DATOS DE UN USUARIO 
        getDatosUsuario: function (estado) {
            var url = base_url + 'usuarios/obtener_Usuario/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.get(url).then(response => {
                this.usuario = response.data[0]
            });
        },

        //// OBTENER USUARIOS LIDERES
        getSuperiores: function () {
            var url = base_url + 'usuarios/obtener_lideres';  //// averiguar como tomar el Id que viene por URL aca

            axios.get(url).then(response => {
                this.listaSuperiores = response.data


            });
        },

        //// CREAR O EDITA un Usuario 
        crearUsuarios: function () {
            var url = base_url + 'restaurant/cargar_Usuarios'; // url donde voy a mandar los datos

            axios.post(url, {
                usuarioData: this.usuario
            }).then(response => {

                if (response.data.Id > 0) {
                    toastr.success('Datos actualizados correctamente', 'Usuarios')

                    this.usuario.Id = response.data.Id;
                    this.texto_boton = "Actualizar"
                }

                else {
                    toastr.error('Este usuario ya existe', 'Usuarios')
                }



            }).catch(error => {
                alert("mal");
            });
        },

        //// CREAR O EDITAR una formación
        crearFormacion: function () {
            var url = base_url + 'usuarios/cargar_formacion'; // url donde voy a mandar los datos

            axios.post(url, {
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

            axios.get(url).then(response => {
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

        //// CREAR O EDITAR UN SUELDO
        cargarLiquidacion: function () {
            var url = base_url + 'usuarios/cargar_liquidacion'; // url donde voy a mandar los datos



            axios.post(url, {
                Data: this.sueldoDato, Usuario_id: Get_Id
            }).then(response => {

                toastr.success('Datos actualizados correctamente', 'Usuarios')

                this.sueldoDato.Id = response.data.Id;
                this.texto_boton = "Actualizar";
                this.getSueldos();

            }).catch(error => {
                alert(response.data);
            });
        },


        //// MOSTRAR LISTADO DE SUELDOS
        getSueldos: function () {
            var url = base_url + 'usuarios/obtener_sueldos/?Id=' + Get_Id + '&Fecha_fin=' + this.Fecha_hasta + '&Fecha_inicio=' + this.Fecha_desde;  //// averiguar como tomar el Id que viene por URL aca

            axios.get(url).then(response => {
                this.listaSueldos = response.data
            });
        },

        //// EDITAR SUELDO
        editarFormularioSueldo: function (sueldo) {
            this.sueldoDato = sueldo;
        },

        //// LIMPIAR FORMULARIO SUELDOS
        limpiarFormularioSueldos: function () {
            this.sueldoDato = { 'Id': '', 'Sueldo_pactado': '0', 'Sueldo_abonado': '0', 'Bonificacion': '0', 'Descuento': '0', 'Fecha': '', 'Costes_impositivos_adicionales': '0', 'Observaciones': '' }
        },

        //// USUARIOS | JORNADAS TRABAJADAS
        getJornadasTrabajadas: function () {
            var url = base_url + 'usuarios/obtener_jornadas_trabajadas/?Id=' + Get_Id + '&Fecha_fin=' + this.Fecha_hasta + '&Fecha_inicio=' + this.Fecha_desde;  //// averiguar como tomar el Id que viene por URL aca

            axios.get(url).then(response => {
                this.listaJornadasTrabajadas = response.data

            }).catch(error => {
                alert(error);
            });
        },

        sumarMontos: function (items) {

            var Total = 0;

            for (var i = 0; i < items.length; i++) {
                var item = 0;

                if (isFinite(items[i].Remuneracion_jornada)) {
                    item = parseInt(items[i].Remuneracion_jornada);
                }

                Total = Total + item;
            }
            return Total
        },

    },

    ////// ACCIONES COMPUTADAS     
    computed:
    {
        buscarItems: function () {
            return this.itemsCarta.filter((item) => item.Nombre.toLowerCase().includes(this.buscar));
        }
    }
});

///         --------------------------------------------------------------------   ////
//// Elemento para el manejo de STOCK por Id
new Vue({
    el: '#itemStock',

    created: function () {
        this.getDatosItem();
        this.getListadoMovimientos();
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

                toastr.success('Datos actualizados correctamente', 'Suscripcioness')

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

                toastr.success('Datos actualizados correctamente', 'Suscripcioness')

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
//// Elemento para el manejo de la Cabecera
new Vue({
    el: '#cabecera',

    created: function () {
        this.obtenerDatosJornada();

    },

    data: {

        // JORNADA
        datos_jornada: {},

    },

    methods:
    {

        //// JORNADA    | DATOS DE LA JORNADA
        obtenerDatosJornada: function () {
            var url = base_url + 'restaurant/obtener_datos_jornada_actual';  //// averiguar como tomar el Id que viene por URL aca

            axios.get(url).then(response => {
                this.datos_jornada = response.data

                //console.log(response.data)
            });
        },


    },

    ////// ACCIONES COMPUTADAS     
    computed:
    {

    }
});


///         --------------------------------------------------------------------   ////
//// Elemento para del ANALITICAS
new Vue({
    el: '#analiticas',

    created: function () {
        this.obtenerAnalisisVentas();
        this.getListadoCategorias();
        this.getJornadas();

    },

    data: {

        listaVentas: [],
        fecha_desde: '0',
        fecha_hasta: '0',
        filtro_categoria: 0,
        filtro_jornada: 0,
        categoriasCarta: '',
        listaJornadas: '',

    },

    methods:
    {

        /// VENTAS DE HOY  
        obtenerAnalisisVentas: function () {
            var url = base_url + 'analiticas/listado_ventas?Categoria_id=' + this.filtro_categoria + '&Fecha_fin=' + this.fecha_hasta + '&Fecha_inicio=' + this.fecha_desde + '&Jornada_id=' + this.filtro_jornada;

            axios.get(url).then(response => {
                this.listaVentas = response.data

                //console.log(this.listaVentas)
            });
        },

        //// MOSTRAR LISTADO DE Categorias DE LA CARTA    
        getListadoCategorias: function () {
            var url = base_url + 'restaurant/obtener_categorias_items'; // url donde voy a mandar los datos

            axios.get(url).then(response => {
                this.categoriasCarta = response.data
            });
        },

        //// JORNADA    | MOSTRAR LISTADO 
        getJornadas: function () {
            var url = base_url + 'restaurant/obtener_jornadas';  //// averiguar como tomar el Id que viene por URL aca

            axios.get(url).then(response => {
                this.listaJornadas = response.data

                //console.log(response.data)
            });
        },

        //// MENSAJE DE LOS INGRESOS A CAJA
        mensaje: function () {
            toastr.warning('Este valor refleja los ingresos en bruto a caja durante el período indicado. Surge del total de las ventas y solo se le han restado los descuentos realizados en la cuenta de cada comanda y de cada delivery.', 'IMPORTANTE')
        }
        //////
    }
});


///         --------------------------------------------------------------------   ////
//// Elemento para el manejo de STOCK por Id
new Vue({
    el: '#jornada',

    created: function () {
        this.getJornada();
    },

    data: {

        Jornada_datos: {},
        result_jornada: 0,
    },

    methods:
    {

        //// MOSTRAR LISTADO DE MOVIMIENTOS  
        getJornada: function () {
            var url = base_url + 'restaurant/obtener_contabilidad_jornada_actual/?Id=' + Get_Id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,

            }).then(response => {
                this.Jornada_datos = response.data
            });
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
        this.getDatosProveedor();
        this.getListadoSeguimiento();
        this.obtener_listado_de_categorias_asignadas();
        this.obtener_listado_de_compras();
        this.getMovimientos();
        this.getCheques(1);
        this.getMovimientos();
    },

    data: {
        mostrar: "1",
        proveedoresDatos: { 'Id': '', 'Nombre_proveedor': '', 'Manzana_id': '', 'Imagen': '', 'Direccion': '', 'Localidad': '', 'Provincia': '', 'Pais': '', 'Telefono': '', 'Email': '', 'Web': '', 'Nombre_persona_contacto': '', 'Datos_persona_contacto': '', 'Mas_datos_proveedor': '' },
        listaSeguimiento: [],
        texto_boton: "Cargar",
        seguimientoData: {},
        listaProductos: [],
        Archivo: null,
        preloader: '0',
        comentarioDatos: {},

        lista_categoria_productos_vinculados: [],
        lista_categoria_productos_no_vinculados: [],

        listaComprasProveedor: [],

        infoModal: '',

        // FINANZAS
        chequeData: { 'Tipo': 1, 'Observaciones': '', },
        descripcionMovimiento: [],
        movimientoDatos: {'Monto_bruto': 0, "Observaciones":""},
        listaMovimientos : [],
        Total_pagado: 0,
        infoModal: {'Observaciones':''},
        listaCheques: [],
        chequeSeleccionado: {},
        Total_mov_generico: 0
    },

    methods:
    {
        //// OBTENER DATOS DE UN CLIENTE
        getDatosProveedor: function () {
            var url = base_url + 'proveedores/obtener_datos_proveedor/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.proveedoresDatos = response.data

                //console.log(this.proveedoresDatos)
            }).catch(error => {
                //alert("MAL LA CARGA EN FUNCIÓN DE CARGAR DATOS");
                console.log(error.response.data)
            });
        },
        
        //// COMPRAS | SUMA PARA DAR TOTAL DE UNA COMPRA
        sumarComrpra: function (n1, n2, n3) {

            var N1 = parseInt(n1);
            var N2 = parseInt(n2);
            var N3 = parseInt(n3);

            return N1 + N2 + N3;
        },


        //// MOSTRAR LISTADO
        obtener_listado_de_compras: function () {
            var url = base_url + 'compras/obtener_compras_proveedor/?Id=' + Get_Id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaComprasProveedor = response.data

                //console.log(this.listaComprasProveedor)
            });
        },

        //// DATOS SOBRE EL ARCHIVO SELECCIONADO EN CASO QUE QUIERA CARGAR ALGUNA ARCHIVO
        archivoSeleccionado(event) {
            this.Archivo = event.target.files[0]
            //this.texto_boton = "Actualizar"
        },

        //// SEGUIMIENTO | MOSTRAR LISTADO
        getListadoSeguimiento: function () {
            var url = base_url + 'proveedores/obtener_seguimientos/?Id=' + Get_Id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaSeguimiento = response.data
            });
        },

        //// SEGUIMIENTO | CREAR O EDITAR una SEGUIMIENTO
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

        //// SEGUIMIENTO | EDITAR UN SEGUIMIENTO
        editarFormularioSeguimiento: function (dato) {
            this.seguimientoData = dato;
        },

        //// SEGUIMIENTO | LIMPIAR FORMULARIO SEGUIMIENTO
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

                toastr.success('Datos actualizados correctamente', 'Propietarios')

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


        //// CUENTA CON PROVEEDOR  
        cuentaProveedor: function (items) {

            /// SUMAR LOS ENTREGADOS
            var Total = 0;

            for (var i = 0; i < items.length; i++) {
                var item = 0;

                item = parseInt(items[i].Total) - parseInt(items[i].Datos.Neto) - parseInt(items[i].Datos.No_gravado) - parseInt(items[i].Datos.IVA);

                Total = Total + item;
            }
            return Total
        },

        /// INFO PARA MODAL 
        infoEtapa: function (observaciones) {
            this.infoModal = observaciones;
        },

        //// FINANZAS | CHEQUES  | LIMPIAR EL FORMULARIO DE CREAR
        limpiarFormularioCheques() {
            this.chequeData = { 'Tipo': 1, 'Observaciones': '', };
            this.texto_boton = "Cargar";
        },

        //// FINANZAS | CHEQUES  | Carga el formulario  para editar
        editarFormularioCheque(chequeData) {
            //this.usuario = {};
            this.chequeData = chequeData;
            this.texto_boton = "Actualizar";
        },

        //// FINANZAS | CHEQUES  | Carga el formulario  para editar
        archivoSeleccionado(event) {
            this.Archivo = event.target.files[0]
            //this.texto_boton = "Actualizar"
        },

        //// FINANZAS | MOVIMIENTOS | LIMPIAR FORMULARIO SEGUIMIENTO
        limpiarFormularioMovimiento: function () {
            this.movimientoDatos =  {'Monto_bruto': 0, "Observaciones":""}
        },

        //// FINANZAS | MOVIMIENTOS | CREAR O EDITAR EN EFECTIVO
        crear_movimiento: function () {
                var url = base_url + 'finanzas/cargar_movimiento'; // url donde voy a mandar los datos
                axios.post(url, {
                    token: token,
                    Datos: this.movimientoDatos,
                    Origen_movimiento: 'Proveedor',
                    Op: this.movimientoDatos.Op,
                    Fila_movimiento: Get_Id,
    
                }).then(response => {
    
                    toastr.success('Proceso realizado correctamente', 'Proveedores')
    
                    this.texto_boton = "Actualizar"
                    this.getMovimientos();
    
                }).catch(error => {
                    console.log(error.response.data)
                });
    
        },

        //// FINANZAS | MOVIMIENTOS |  MOVIMIENTOS EN EFECTIVO
        getMovimientos: function () {
            var url = base_url + 'finanzas/obtener_movimientos'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Origen_movimiento: "Proveedor",
                Fila_movimiento: Get_Id,

            }).then(response => {
                this.listaMovimientos = response.data.Datos;
                this.Total_mov_generico = response.data.Total_pagado;

                //console.log(response.data)
            }).catch(error => {
                console.log(error.response.data)
            });
        },

        //// FINANZAS | LISTADO DE CHEQUES
        getCheques: function (Estado) {
            var url = base_url + 'finanzas/obtener_cheques'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Estado: Estado,

            }).then(response => {
                this.listaCheques = response.data;
            }).catch(error => {
                console.log(error.response.data)
            });
        },

        //// FINANZAS | MOVIMIENTOS | CREAR O EDITAR EN EFECTIVO
        pagoConCheque: function (datos) {
            
            var url = base_url + 'finanzas/cargar_movimiento_cheques'; // url donde voy a mandar los datos
            axios.post(url, {
                token: token,
                Datos: datos,
                Origen_movimiento: 'Proveedor',
                Op: 0,
                Fila_movimiento: Get_Id,

            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Proveedor')

                
                this.texto_boton = "Actualizar"
                this.getMovimientos();
                this.getCheques(1);

            }).catch(error => {
                console.log(error.response.data)
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
        this.getMovimientos();
        //this.obtener_listado_de_compras();
    },

    data: {
        mostrar: "3",
        clienteDatos: {},
        listaSeguimiento: [],
        texto_boton: "Cargar",
        seguimientoData: {},
        listaCompras: [],
        Archivo: null,
        preloader: '0',
        comentarioDatos: {},
        
        listaComandas: [],
        listaDeliverys: [],
    
        infoModal: '',

        // FINANZAS
        chequeData: { 'Tipo': 1, 'Observaciones': '', },
        descripcionMovimiento: [],
        movimientoDatos: {'Monto_bruto': 0, "Observaciones":""},
        listaMovimientos : [],
        Total_mov_generico: 0,
        infoModal: {'Observaciones':''},
    },

    methods:
    {

        //// OBTENER DATOS DE UN CLIENTE
        getDatosCliente: function () {
            var url = base_url + 'clientes/obtener_datos/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.clienteDatos = response.data

                //console.log(response.data)

            }).catch(error => {
                //alert("mal");
                console.log(error.response.data)

            });
        },

        //// CLIENTES | CREAR O EDITAR
        crearCliente: function () {
            var url = base_url + 'clientes/crear_cliente'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.clienteDato.Datos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Clientes')

                this.clienteDato.Id = response.data.Id;
                //this.texto_boton = "Actualizar"
                this.clienteDato = {}

                this.getListadoClientes();

            }).catch(error => {
                //alert("mal");
                console.log(error.response.data)

            });
        },

        //// COMPRAS | SUMA PARA DAR TOTAL DE UNA COMPRA
        montoDelivery: function (n1, n2, n3) {

            var N1 = parseInt(n1);
            var N2 = parseInt(n2);
            var N3 = parseInt(n3);

            return N1 + N2 - N3;
        },

        //// MOSTRAR LISTADO
        getListadoSeguimiento: function () {
            var url = base_url + 'clientes/obtener_seguimientos/?Id=' + Get_Id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaSeguimiento = response.data
            });
        },

        //// MOSTRAR LISTADO
        obtener_listado_de_compras: function () {
            var url = base_url + 'compras/obtener_compras_proveedor/?Id=' + Get_Id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaComprasProveedor = response.data

                //console.log(this.listaComprasProveedor)
            });
        },        

        //// DATOS SOBRE EL ARCHIVO SELECCIONADO EN CASO QUE QUIERA CARGAR ALGUNA ARCHIVO
        archivoSeleccionado(event) {
            this.Archivo = event.target.files[0]
            //this.texto_boton = "Actualizar"
        },

        //// CREAR O EDITAR una SEGUIMIENTO
        crearSeguimiento: function () {
            var url = base_url + 'clientes/cargar_seguimiento'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.seguimientoData, Cliente_id: Get_Id
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

                            toastr.success('El archivo se cargo correctamente', 'Clientes')
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
                toastr.success('Datos actualizados correctamente', 'Clientes')

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

        /// INFO PARA MODAL 
        infoEtapa: function (observaciones) {
            this.infoModal = observaciones;
        },

        //// FINANZAS | CHEQUES  | LIMPIAR EL FORMULARIO DE CREAR
        limpiarFormularioCheques() {
            this.chequeData = { 'Tipo': 1, 'Observaciones': '', };
            this.texto_boton = "Cargar";
        },

        //// FINANZAS | CHEQUES  | Carga el formulario  para editar
        editarFormularioCheque(chequeData) {
            //this.usuario = {};
            this.chequeData = chequeData;
            this.texto_boton = "Actualizar";
        },

        //// FINANZAS | CHEQUES  | Carga el formulario  para editar
        archivoSeleccionado(event) {
            this.Archivo = event.target.files[0]
            //this.texto_boton = "Actualizar"
        },

        ///// FINANZAS | MOVIMIENTOS | LIMPIAR FORMULARIO SEGUIMIENTO
        limpiarFormularioMovimiento: function () {
            this.movimientoDatos =  {'Monto_bruto': 0, "Observaciones":""}
        },

        //// FINANZAS | CHEQUES  |  CREAR O EDITAR una SEGUIMIENTO
        crearCheque: function () {
            var url = base_url + 'finanzas/cargar_cheques'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.chequeData
            
            }).then(response => {

                this.chequeData.Id = response.data.Id;

                /// si eso se ralizó bien, debe comprobar si hay un archivo a cargar.
                if (this.Archivo != null) 
                {
                    var url = base_url + 'finanzas/subirImagen/?Id=' + this.chequeData.Id;
                    this.preloader = 1;

                    //const formData = event.target.files[0];
                    const formData = new FormData();
                    formData.append("Archivo", this.Archivo);

                    formData.append('_method', 'PUT');

                    //Enviamos la petición
                    axios.post(url, formData)
                        .then(response => {

                            this.chequeData.Imagen = response.data.Imagen;

                            toastr.success('El archivo se cargo correctamente', 'Finanzas')
                            this.preloader = 0;

                        }).catch(error => {
                            this.preloader = 0;
                            console.log(error.response.data)
                        });
                }

                //// FINANZAS | CARGANDO MOVIMIENTO
                var url = base_url + 'finanzas/cargar_movimiento_cheques'; // url donde voy a mandar los datos

                axios.post(url, {
                    token: token,
                    Datos: response.data,
                    Origen_movimiento: 'Clientes',
                    Tipo_movimiento : 3,
                    Fila_movimiento: Get_Id,
                    Op: 1,

                }).then(response => {

                    toastr.success('Proceso realizado correctamente', 'Clientes')

                    this.texto_boton = "Actualizar"
                    this.getMovimientos()

                }).catch(error => {
                    alert("mal");
                    console.log(error.response.data)
                });

                // si lo hay lo carga, si no lo hay no hace nada
                this.Archivo = null
                this.texto_boton = "Actualizar"
                toastr.success('Datos actualizados correctamente', 'Clientes')

            }).catch(error => {
                alert("MAL LA CARGA EN FUNCIÓN DE CARGAR DATOS");
            });
        },

        //// FINANZAS | MOVIMIENTOS | CREAR O EDITAR EN EFECTIVO
        crear_movimiento: function () {
            
            var url = base_url + 'finanzas/cargar_movimiento'; // url donde voy a mandar los datos
            axios.post(url, {
                token: token,
                Datos: this.movimientoDatos,
                Origen_movimiento: 'Clientes',
                Op: this.movimientoDatos.Op,
                Fila_movimiento: Get_Id,

            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Clientes')

                
                this.texto_boton = "Actualizar"
                this.getMovimientos();

            }).catch(error => {
                console.log(error.response.data)
            });
        },

        //// FINANZAS | MOVIMIENTOS |  MOVIMIENTOS EN EFECTIVO
        getMovimientos: function () {
            var url = base_url + 'finanzas/obtener_movimientos'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Origen_movimiento: "Clientes",
                Fila_movimiento: Get_Id,

            }).then(response => {
                this.listaMovimientos = response.data.Datos;
                this.Total_mov_generico = response.data.Total_pagado;

                //console.log(response.data)
            }).catch(error => {
                console.log(error.response.data)
            });
        },

        //
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
        this.getListadoProveedores_select();
        
        this.getMovimientos();

        this.getCheques(1);
        //this.getCheques();    Revisar - REEMPLAZAR CON JORNADAS O EN CIERRES MENSUALES
        this.getListadoSeguimiento();

    },

    data: {
        mostrar: "1",
        compraDatos: {},
        texto_boton: "Cargar",
        buscar: '',
        infoModal: { 'Observaciones': '' },

        listaProveedores_select: [],
        listaComprados: [],
        listaProductos: [],

        cantMovimientoStock: [],
        filtro_rubro: 0,
        listaSeguimiento: [],
        seguimientoData: {},

        Archivo: null,
        preloader: '0',

        // FINANZAS
        chequeData: { 'Tipo': 1, 'Observaciones': '', },
        descripcionMovimiento: [],
        movimientoDatos: {'Monto_bruto': 0, "Observaciones":""},
        listaMovimientos : [],
        Total_pagado: 0,
        infoModal: {'Observaciones':''},
        listaCheques: [],
        chequeSeleccionado: {}

        
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
        movimientoStock: function (id, cantidad, descripcion, precio_costo) {
            var url = base_url + 'stock/cargar_movimiento'; // url donde voy a mandar los datos

            // 1 Equivale a compras, 2 a Ordenes de trabajo
            var Descripcion = "Compra a " + this.compraDatos.Nombre_proveedor + ", factura: " + this.compraDatos.Factura_identificador + " | " + descripcion;

            axios.post(url, {
                token: token,
                Id: id,
                Cantidad: cantidad,
                Descripcion: Descripcion,
                Proceso_id: Get_Id,
                Modulo: "Compras",
                Tipo_movimiento: 1,
                Precio_costo: precio_costo,
                Mostrar_reventas: 0,

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
                Tipo_movimiento: 1, // este puede estar al pedo
                Mostrar_reventas: 0,
                Proceso_id: Get_Id,
                Modulo: "Compras",

            }).then(response => {
                this.listaComprados = response.data

            }).catch(error => {
                alert("mal");
                console.log(error.response)

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

        //// PROVEEDORES  | MOSTRAR LISTADO
        getListadoProveedores_select: function (Rubro_id) {
            var url = base_url + 'proveedores/obtener_proveedores_select'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaProveedores_select = response.data
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
            var url = base_url + 'proveedores/obtener_proveedores/?Rubro_id=0'; // url donde voy a mandar los datos

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
            }).catch(error => {
                console.log(error.response.data)
            });
        },

        //// SEGUIMIENTO | MOSTRAR LISTADO
        getListadoSeguimiento: function () {
            var url = base_url + 'compras/obtener_seguimientos/?Id=' + Get_Id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaSeguimiento = response.data
            }).catch(error => {
                console.log(error.response.data)
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
                            console.log(error.response.data)

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
                console.log(error.response.data)
            });
        },

        //// SEGUIMIENTO | EDITAR UN SEGUIMIENTO
        editarFormularioSeguimiento: function (dato) {
            this.seguimientoData = dato;
        },

        //// SEGUIMIENTO | LIMPIAR FORMULARIO SEGUIMIENTO
        limpiarFormularioSeguimiento: function () {
            this.seguimientoData = { 'Fecha': hoy_php }
        },

        //// COMPRAS | SUMA PARA DAR TOTAL DE UNA COMPRA
        sumarComrpra: function (n1, n2, n3) {

            var N1 = parseInt(n1);
            var N2 = parseInt(n2);
            var N3 = parseInt(n3);

            return N1 + N2 + N3;
        },

        //// COMPRAS  | Dasactivar
        desactivarAlgo: function (Id, tabla) {
            var url = base_url + 'funcionescomunes/desvisualizar'; // url donde voy a mandar los datos

            var opcion = confirm("¿Esta seguro de eliminar a este pago?");
            if (opcion == true) {
                axios.post(url, {
                    token: token,
                    Id: Id,
                    tabla: tabla
                }).then(response => {

                    toastr.success('Proceso realizado correctamente', 'Compras')

                    this.getDatosEfectivo();
                    this.getDatosTransferencia();
                    this.getDatosCheques();

                }).catch(error => {
                    alert("mal");
                    console.log(error)

                });
            }
        },

        /// INFO PARA MODAL 
        infoEtapa: function (observaciones) {
            this.infoModal.Observaciones = observaciones;
        },

        //// FINANZAS | CHEQUES  | LIMPIAR EL FORMULARIO DE CREAR
        limpiarFormularioCheques() {
            this.chequeData = { 'Tipo': 1, 'Observaciones': '', };
            this.texto_boton = "Cargar";
        },

        //// FINANZAS | CHEQUES  | Carga el formulario  para editar
        editarFormularioCheque(chequeData) {
            //this.usuario = {};
            this.chequeData = chequeData;
            this.texto_boton = "Actualizar";
        },

        //// FINANZAS | CHEQUES  | Carga el formulario  para editar
        archivoSeleccionado(event) {
            this.Archivo = event.target.files[0]
            //this.texto_boton = "Actualizar"
        },

        //// FINANZAS | MOVIMIENTOS | LIMPIAR FORMULARIO SEGUIMIENTO
        limpiarFormularioMovimiento: function () {
            this.movimientoDatos =  {'Monto_bruto': 0, "Observaciones":""}
        },

        //// FINANZAS | MOVIMIENTOS | CREAR O EDITAR EN EFECTIVO
        crear_movimiento: function () {
            
            var url = base_url + 'finanzas/cargar_movimiento'; // url donde voy a mandar los datos
            axios.post(url, {
                token: token,
                Datos: this.movimientoDatos,
                Origen_movimiento: 'Compras',
                Op: 0,
                Fila_movimiento: Get_Id,

            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Compras')

                
                this.texto_boton = "Actualizar"
                this.getMovimientos();

            }).catch(error => {
                console.log(error.response.data)
            });
        },

        //// FINANZAS | MOVIMIENTOS |  MOVIMIENTOS EN EFECTIVO
        getMovimientos: function () {
            var url = base_url + 'finanzas/obtener_movimientos'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Origen_movimiento: 'Compras',
                Fila_movimiento: Get_Id,

            }).then(response => {
                this.listaMovimientos = response.data.Datos;
                this.Total_pagado = response.data.Total_pagado;
            }).catch(error => {
                console.log(error.response.data)
            });
        },

        //// FINANZAS | LISTADO DE CHEQUES
        getCheques: function (Estado) {
            var url = base_url + 'finanzas/obtener_cheques'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Estado: Estado,

            }).then(response => {
                this.listaCheques = response.data;
            }).catch(error => {
                console.log(error.response.data)
            });
        },

        //// FINANZAS | MOVIMIENTOS | CREAR O EDITAR EN EFECTIVO
        pagoConCheque: function (datos) {
            
            var url = base_url + 'finanzas/cargar_movimiento_cheques'; // url donde voy a mandar los datos
            axios.post(url, {
                token: token,
                Datos: datos,
                Origen_movimiento: 'Compras',
                Op: 0,
                Fila_movimiento: Get_Id,

            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Compras')

                
                this.texto_boton = "Actualizar"
                this.getMovimientos();
                this.getCheques(1);

            }).catch(error => {
                console.log(error.response.data)
            });
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


///         --------------------------------------------------------------------   ////
//// Elemento para el manejo de carta por Id
new Vue({
    el: '#itemcarta',

    created: function () {
        this.getDatosId();
        this.getListadoArchivos();
        this.getListadoCategoriasProductos();
        //this.getListadoVentas();
        this.getListadoStock();
        this.getListadoInsumos();
        
    },

    data: {
        mostrar: "3",
        itemDatos: {
            'Id': '',
            'Codigo_interno': '',
            'Codigo_interno': '',
        'Categoria_carta_id': '',
        'Nombre_producto': '',
        'Imagen': '',
        'Descripcion_publica_corta': '',
        'Descripcion_publica_larga': '',
        'Descripcion_tecnica_privada': '', },

        listaArchivos: [],
        texto_boton: "Cargar",
        archivoData: {},

        listaProductos: [],
        Archivo: null,
        preloader: '0',
        comentarioDatos: {},

        listaCategorias: [],
        listaVentas: [],
        filtro_vendedor: '0',
        filtro_empresa: '0',
        filtro_suscripciones_: '0',
        filtro_estado: '0',

        insumoDatos: { 'Stock_id': 0, 'Cantidad': 0.00, 'Observaciones': '', },
        listaInsumos: [],
        listaStock: [],
        listaEmpresas: [],
    },

    methods:
    {
        

        //// MOSTRAR LISTADO
        getListadoArchivos: function () {
            var url = base_url + 'carta/obtener_listado_archivos/?Id=' + Get_Id; // url donde voy a mandar los datos
            axios.post(url, {
                token: token,
            }).then(response => {
                this.listaArchivos = response.data
            });
        },

        //// OBTENER DATOS DEL ID
        getDatosId: function () {
            var url = base_url + 'carta/obtener_datos_id/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.itemDatos = response.data[0]
            });
        },

        //// PRODUCTO  | CREAR O EDITAR 
        actualizarProducto: function () {
            var url = base_url + 'carta/cargar_producto'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Data: this.itemDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Fabricación')

                this.itemDatos.Id = response.data.Id;
                this.texto_boton = "Actualizar"

            }).catch(error => {
                alert("mal");
                console.log(error)
                //toastr.success('Proceso realizado correctamente', 'Proveedores')
            });
        },

        //// carta |  MOSTRAR LISTADO DE VENTAS DONDE SE VENDIO TAL PRODUCTO
        getListadoVentas: function () {

            ///
            var url = base_url + 'carta/obtener_ventas_producto?Producto_id=' + Get_Id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaVentas = response.data

                /* console.log(response.data) */

            }).catch(error => {

                console.log(error.response.data)

            });

            // La consulta aca debe cambiar bastante
        },

        //// carta |  MOSTRAR LISTADO DE CATEGORIAS
        getListadoCategoriasProductos: function () {
            var url = base_url + 'carta/obtener_categorias'; // url donde voy a mandar los datos

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

        //// VENTAS  | CANTIDAD DE DIAS ENTRE DOS FECHAS
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
            var url = base_url + 'carta/cargar_archivo'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.archivoData, 
                ItemCarta_id: Get_Id
            }).then(response => {

                this.archivoData.Id = response.data.Id;

                /// si eso se ralizó bien, debe comprobar si hay un archivo a cargar.
                if (this.Archivo != null) {
                    var url = base_url + 'carta/subirArchivo/?Id=' + this.archivoData.Id;
                    this.preloader = 1;

                    //const formData = event.target.files[0];
                    const formData = new FormData();
                    formData.append("Archivo", this.Archivo);

                    formData.append('_method', 'PUT');

                    //Enviamos la petición
                    axios.post(url, formData)
                        .then(response => {

                            this.archivoData.Url_archivo = response.data.Url_archivo;

                            toastr.success('El archivo se cargo correctamente', 'carta')
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
                toastr.success('Datos actualizados correctamente', 'carta')

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


        //// INSUMOS FABRICACIÓN  | CREAR O EDITAR 
        cargarInsumo: function () {
            var url = base_url + 'carta/cargar_insumo_producto?ItemCarta_id=' + Get_Id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.insumoDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Carta')

                this.insumoDatos.Id = response.data.Id;
                this.texto_boton = "Actualizar"
                this.getListadoInsumos()


            }).catch(error => {
                alert("mal");
                console.log(error.response.data)
            });
        },

        //// INSUMOS FABRICACIÓN  | LISTADO DE INSUMOS 
        getListadoInsumos: function () {
            var url = base_url + 'carta/obtener_listado_insumos/?ItemCarta_id=' + Get_Id; // url donde voy a mandar los datos
            axios.post(url, {
                token: token,
            }).then(response => {
                this.listaInsumos = response.data
            });
        },

        //// INSUMOS FABRICACIÓN  | LISTADO COMPLETO DE STOCK
        getListadoStock: function () {
            var url = base_url + 'stock/obtener_listado_de_stock?categoria=0'; // url donde voy a mandar los datos
            axios.post(url, {
                token: token,
            }).then(response => {
                this.listaStock = response.data
            });
        },

        ///  INSUMOS FABRICACIÓN  | EDITAR
        editarFormularioInsumo: function (dato) {
            this.insumoDatos = dato;
        },

        ////  INSUMOS FABRICACIÓN  | LIMPIAR FORMULARIO 
        limpiarFormularioInsumo: function () {
            this.insumoDatos = { 'Stock_id': 0, 'Cantidad': 0.00, 'Observaciones': '', };
        },


        //// QUITAR ALGO
        eliminar: function (Id, tbl) {
            var url = base_url + 'restaurant/eliminar'; // url donde voy a mandar los datos
            var opcion = confirm("¿Esta seguro de eliminar a este producto?");
            if (opcion == true) 
            {
                axios.post(url, {
                    Id: Id,
                    tabla: tbl
                }).then(response => {

                    this.getListadoInsumos();
                    toastr.success('Item eliminado correctamente', 'Comandas')

                }).catch(error => {
                    alert("mal");
                });
            }
        },


        ////////////////////////////-----------------
    },

    ////// ACCIONES COMPUTADAS     
    computed:
    {

    }
});