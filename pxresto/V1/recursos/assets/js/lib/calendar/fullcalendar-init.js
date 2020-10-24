var base_url = "http://pxsistemas.com/pxresto/V1/" //MODIFICAR SEGUN EL LINK DEL PROYECTO
var URLactual = window.location;
var carpeta = '/pxresto/V1/'
var defaultEvents = null;




!function($) 
{
    "use strict";

    var CalendarApp = function() {
        this.$body = $("body")
        this.$modal = $('#event-modal'),
        this.$event = ('#external-events div.external-event'),
        this.$calendar = $('#calendar'),
        this.$saveCategoryBtn = $('.save-category'),
        this.$categoryForm = $('#add-category form'),
        this.$extEvents = $('#external-events'),
        this.$calendarObj = null
    };


    /* on drop */
    CalendarApp.prototype.onDrop = function (eventObj, date) { 
        var $this = this;
            // retrieve the dropped element's stored Event Object
            var originalEventObject = eventObj.data('eventObject');
            var $categoryClass = eventObj.attr('data-class');
            // we need to copy it, so that multiple events don't have a reference to the same object
            var copiedEventObject = $.extend({}, originalEventObject);
            // assign it the date that was reported
            copiedEventObject.start = date;
            if ($categoryClass)
                copiedEventObject['className'] = [$categoryClass];
            // render the event on the calendar
            $this.$calendar.fullCalendar('renderEvent', copiedEventObject, true);
            // is the "remove after drop" checkbox checked?
            if ($('#drop-remove').is(':checked')) {
                // if so, remove the element from the "Draggable Events" list
                eventObj.remove();
            }
    },
    /* on click on event */
    CalendarApp.prototype.onEventClick =  function (calEvent, jsEvent, view) 
    {   
        
        var $this = this;
            var form = $("<form></form>");
            form.append("<label>Editar asunto</label>");
        form.append("<div class='input-group'><input class='form-control' type=text value='" + calEvent.title + "' /><span class='input-group-btn'><button type='submit' class='btn btn-success waves-effect waves-light'><i class='fa fa-check'></i> Guardar</button></span></div>");
            $this.$modal.modal({
                backdrop: 'static'
            });
            $this.$modal.find('.delete-event').show().end().find('.save-event').hide().end().find('.modal-body').empty().prepend(form).end().find('.delete-event').unbind('click').on("click", function () 
            {
                //// ACA VA EL CÓDIGO PARA ELIMINAR ---------------- 
                var datosEnviados = {
                    'Id': calEvent.Id,
                }

                $.ajax({
                    type: "POST",
                    url: "http://pxsistemas.com/pxresto/V1/planificaciones/eliminarevento", // url que me recibe los datos
                    dataType: 'json',
                    data: datosEnviados,
                    success: function (msg) {

                        //console.log(OK)
                    }
                });
                /// -------------

                $this.$calendarObj.fullCalendar('removeEvents', function (ev) 
                {
                    return (ev._id == calEvent._id);
                    
                    
                });
                $this.$modal.modal('hide');
            });

            $this.$modal.find('form').on('submit', function () 
            {
                calEvent.title = form.find("input[type=text]").val();

                /////////////////// ACA VA EL CÓDIGO PARA EDITAR------
                var datosEnviados = {
                    'title': calEvent.title,
                    'Id': calEvent.Id,
                }

                $.ajax({
                    type: "POST",
                    url: "http://pxsistemas.com/pxresto/V1/planificaciones/editarevento", // url que me recibe los datos
                    dataType: 'json',
                    data: datosEnviados,
                    success: function (msg) {

                        //console.log(OK)

                    }
                });
                /// -------------

                $this.$calendarObj.fullCalendar('updateEvent', calEvent);
                $this.$modal.modal('hide');
                return false;
                
                
                
            });

        ////console.log(calEvent)
    },
    /* on select */
    CalendarApp.prototype.onSelect = function (start, end, allDay) {
        var $this = this;
            $this.$modal.modal({
                backdrop: 'static'
            });
            var form = $("<form></form>");
            form.append("<div class='row'></div>");
            form.find(".row")
                .append("<div class='col-md-6'><div class='form-group'><label class='control-label'>Asunto</label><input class='form-control' placeholder='Escribir asunto' type='text' name='title'/></div></div>")
                .append("<div class='col-md-6'><div class='form-group'><label class='control-label'>Color marcador</label><select class='form-control' name='category'></select></div></div>")
                .find("select[name='category']")
                .append("<option value='bg-danger'>Rojo</option>")
                .append("<option value='bg-success'>Verde</option>")
                .append("<option value='bg-dark'>Negro</option>")
                .append("<option value='bg-primary'>Azul</option>")
                .append("<option value='bg-warning'>Naranja</option></div></div>");
            $this.$modal.find('.delete-event').hide().end().find('.save-event').show().end().find('.modal-body').empty().prepend(form).end().find('.save-event').unbind('click').on("click", function () {
                form.submit();
            });
            $this.$modal.find('form').on('submit', function () {
                var title = form.find("input[name='title']").val();
                var beginning = form.find("input[name='beginning']").val();
                var ending = form.find("input[name='ending']").val();
                var categoryClass = form.find("select[name='category'] option:checked").val();
                if (title !== null && title.length != 0) {
                    $this.$calendarObj.fullCalendar('renderEvent', {
                        title: title,
                        start:start,
                        end: end,
                        allDay: false,
                        className: categoryClass

                        
                    }, true);  
                    $this.$modal.modal('hide');
                    
        //// ---------------- ESTE CÓDIGO ES PARA CREAR UN NUEVO EVENTO ------------------------------////
                    
                    var carga = $this.$calendarObj.fullCalendar('renderEvent', {
                        title: title,
                        start: start,
                        end: end,
                        allDay: false,
                        className: categoryClass
                    })

                    
                    //// LE SUMO 6 HORAS PARA PARCHAR UN PROBLEMA QUE TIENE EL SISTEMA QUE NO TOMA CORRECTAMENTE LAS FECHAS
                    var start_1 = carga[0].start._d;
                    start_1 = start_1.getTime()
                    start_1 = start_1 + 21600000;
                    start_1 = new Date(start_1);
                   
                    var end_1 = carga[0].end._d;
                    end_1 = end_1.getTime();
                    end_1 = end_1 + 21600000;
                    end_1 = new Date(end_1);
                    

                    /// USANDO AJAX JQUERY DEBO ENVIAR ESTOS DATOS A LA BASE DE DATOS
                    var datosEnviados = { 
                                            'title': carga[0].title,
                                            'start': start_1,
                                            'end': end_1,
                                            'className': carga[0].className[0] 
                                        }
                    

                    //console.log(datosEnviados)

                    $.ajax({
                        type: "POST",
                        url: "http://pxsistemas.com/pxresto/V1/planificaciones/crearevento", // url que me recibe los datos
                        dataType: 'json',
                        data: datosEnviados,
                        success: function (msg) 
                        {

                            //console.log('OK')

                        }
                    });

        //// ---------------- END - ESTE CÓDIGO ES PARA CREAR UN NUEVO EVENTO ------------------------------////
                }
                else{
                    alert('Debes poner un título');
                }
                return false;
                
            });
            $this.$calendarObj.fullCalendar('unselect');
    },
    CalendarApp.prototype.enableDrag = function() {
        //init events
        $(this.$event).each(function () {
            // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
            // it doesn't need to have a start or end
            var eventObject = {
                title: $.trim($(this).text()) // use the element's text as the event title
            };
            // store the Event Object in the DOM element so we can get to it later
            $(this).data('eventObject', eventObject);
            // make the event draggable using jQuery UI
            $(this).draggable({
                zIndex: 999,
                revert: true,      // will cause the event to go back to its
                revertDuration: 0  //  original position after the drag
            });
        });
    }

    //// ---------------- aca se muestran las tareas que ya fueron armadas ------------------------------////

    /* Initializing */
    CalendarApp.prototype.init = function() 
    {
        this.enableDrag();
        /*  Initialize the calendar  */
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
        var form = '';
        var today = new Date($.now());

        var datosEnviados = null;
        
        $.ajax({
            type: "GET",
            // Formato de datos que se espera en la respuesta
            dataType: "json",
            url: "http://pxsistemas.com/pxresto/V1/planificaciones/obtener_eventos", // url que me recibe los datos
            data: datosEnviados,
            success: function (response) {
                
                defaultEvents = response;

            },
            async: false // <- this turns it into synchronous
        });
        
        //console.log(defaultEvents)

        // ---------------------------------------------------------------------------------

        var $this = this;
        $this.$calendarObj = $this.$calendar.fullCalendar({
            slotDuration: '00:30:00', /* If we want to split day time each 15minutes */
            minTime: '00:00:00',
            maxTime: '24:00:00',  
            defaultView: 'month',  /// month,agendaWeek,agendaDay
            handleWindowResize: true,   
            height: $(window).height() - 200,   
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            events: defaultEvents,
            editable: true,
            droppable: true, // this allows things to be dropped onto the calendar !!!
            eventLimit: true, // allow "more" link when too many events
            selectable: true,
            drop: function(date) { $this.onDrop($(this), date); },
            select: function (start, end, allDay) { $this.onSelect(start, end, allDay); },
            eventClick: function(calEvent, jsEvent, view) { $this.onEventClick(calEvent, jsEvent, view); }

        });

        //// TIPO DE CATEGORIA DE EVENTOS, PARA UNO NUEVO

        //on new event
        this.$saveCategoryBtn.on('click', function(){
            var categoryName = $this.$categoryForm.find("input[name='category-name']").val();
            var categoryColor = $this.$categoryForm.find("select[name='category-color']").val();
            if (categoryName !== null && categoryName.length != 0) {
                $this.$extEvents.append('<div class="external-event bg-' + categoryColor + '" data-class="bg-' + categoryColor + '" style="position: relative;"><i class="fa fa-move"></i>' + categoryName + '</div>')
                $this.enableDrag();
            }

        });
    },

   //init CalendarApp
    $.CalendarApp = new CalendarApp, $.CalendarApp.Constructor = CalendarApp
    
}(window.jQuery),

//initializing CalendarApp
function($) {
    "use strict";
    $.CalendarApp.init()
}(window.jQuery);
