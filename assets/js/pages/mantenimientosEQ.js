$(function() {
    // crud table
    altair_crud_table.init();
    altair_form_adv.date_range();
    fechaActual = new Date().toISOString().slice(0, 10);
    console.log(fechaActual);
});


$('#btn_searchEquipos').click(function() {
    altair_crud_table.init();
});

/* Evita problema de doble calendario en firefox*/
$('input[type=date]').on('click', function(event) {
    var isFirefox = typeof InstallTrigger !== 'undefined';
    if (isFirefox) {
        event.preventDefault();
    }
});

altair_crud_table = {
    init: function() {


        var fechaInicial = $('#uk_dp_start').val();
        var fechaFinal = $('#uk_dp_end').val();

        /* Comprobacion de parametros no sean nullos y asignacion de valores si lo son*/
        if (fechaInicial == null || fechaInicial == "" || fechaFinal == null || fechaFinal == "") {
            fechaInicial = new Date().toISOString().slice(0, 10);
            fechaFinal = new Date().toISOString().slice(0, 10);

        }

        console.log(fechaInicial);
        console.log(fechaFinal);

        $('#students_crud').jtable({
            title: 'Lista de Equipos pendientes',
            paging: true, //Enable paging
            pageSize: 10, //Set page size (default: 10)
            deleteConfirmation: function(data) {
                data.deleteConfirmMessage = 'Esta seguro de que desea omitir/eliminar de la lista de mantenimientos el producto: <strong>' + data.record.Producto + ' </strong> de la factura: <strong> ' + data.record.CodigoFac + ' </strong> ?';
            },
            formCreated: function(event, data) {
                // replace click event on some clickable elements
                // to make icheck label works
                data.form.find('.jtable-option-text-clickable').each(function() {
                    var $thisTarget = $(this).prev().attr('id');
                    $(this)
                        .attr('data-click-target', $thisTarget)
                        .off('click')
                        .on('click', function(e) {
                            e.preventDefault();
                            $('#' + $(this).attr('data-click-target')).iCheck('toggle');
                        })
                });
                // create selectize
                data.form.find('select').each(function() {
                    var $this = $(this);
                    $this.after('<div class="selectize_fix"></div>')
                        .selectize({
                            dropdownParent: 'body',
                            placeholder: 'Click para seleccionar ...',
                            onDropdownOpen: function($dropdown) {
                                $dropdown
                                    .hide()
                                    .velocity('slideDown', {
                                        begin: function() {
                                            $dropdown.css({ 'margin-top': '0' })
                                        },
                                        duration: 200,
                                        easing: easing_swiftOut
                                    })
                            },
                            onDropdownClose: function($dropdown) {
                                $dropdown
                                    .show()
                                    .velocity('slideUp', {
                                        complete: function() {
                                            $dropdown.css({ 'margin-top': '' })
                                        },
                                        duration: 200,
                                        easing: easing_swiftOut
                                    })
                            }
                        });
                });
                // create icheck
                data.form
                    .find('input[type="checkbox"],input[type="radio"]')
                    .each(function() {
                        var $this = $(this);
                        $this.iCheck({
                                checkboxClass: 'icheckbox_md',
                                radioClass: 'iradio_md',
                                increaseArea: '20%'
                            })
                            .on('ifChecked', function(event) {
                                $this.parent('div.icheckbox_md').next('span').text('Active');
                            })
                            .on('ifUnchecked', function(event) {
                                $this.parent('div.icheckbox_md').next('span').text('Passive');
                            })
                    });
                // reinitialize inputs
                data.form.find('.jtable-input').children('input[type="text"],input[type="password"],textarea').not('.md-input').each(function() {
                    $(this).addClass('md-input');
                    $(this).attr("placeholder", "Ingrese texto o valor correspondiente.");
                    altair_forms.textarea_autosize();
                });
                altair_md.inputs();
            },
            actions: {
                listAction: 'views/modulos/ajax/API_mantenimientosEQ.php?action=list',
                updateAction: 'views/modulos/ajax/API_mantenimientosEQ.php?action=update',
                deleteAction: 'views/modulos/ajax/API_mantenimientosEQ.php?action=delete'
            },
            recordUpdated: function(event, data) {
                console.log(data);
                let registroinfo = data.serverResponse.registroAgregado.mensaje
                let mailinfo = data.serverResponse.registroAgregado.email.mensaje;
                console.log(mailinfo);

                UIkit.modal.alert(registroinfo + '\n'+ mailinfo, {center: true, labels: {'Ok': 'Ok'}}).on('hide.uk.modal', function() {
                    $('#students_crud').jtable('reload');
                });


            },
            recordDeleted: function(event, data) {
                console.log(data);
                let CodigoFac = data.record.CodigoFac;
                let CodProducto = data.record.CodProducto;

                $.ajax({
                    type: 'post',
                    url: 'views/modulos/ajax/API_mantenimientosEQ.php?action=omite',

                    data: { CodigoFac: CodigoFac, CodProducto: CodProducto },

                    success: function(response) {
                        let resDecode = JSON.parse(response);
                        console.log(resDecode);
                    }
                });

                setTimeout(() => {
                    $('#students_crud').jtable('reload');
                }, 3000);
            },
            fields: {
                FechaCompra: {
                    title: 'Fecha Compra',
                    width: '5%',
                    type: 'date',
                    create: false,
                    edit: false
                },
                CodigoFac: {
                    title: 'Codigo Fact.',
                    key: true,
                    create: false,
                    edit: false,
                    list: true,
                    display: function(data) {
                        if (data.record.TipoDocumento == 'D' || data.record.TipoDocumento == 'DVM') {
                            return '<span class="uk-text-danger">' + data.record.CodigoFac + '</span>'
                        } else {
                            return '<span class="">' + data.record.CodigoFac + '</span>'
                        }
                    }
                },
                CodProducto: {
                    title: 'Cod. Prod.',
                    key: true,
                    edit: false,
                    list: false,
                },
                Producto: {
                    title: 'Producto/Equipo',
                    edit: false
                },
                DiasGarantia: {
                    title: 'Garant.',
                    list: true,
                    edit: false,
                    display: function(data) {
                        if (data.record.DiasGarantia <= 0) {
                            return '<span class="uk-text-danger">' + 0 + '</span>'
                        } else if (data.record.DiasGarantia <= 8) {
                            return '<span class="uk-text-warning">' + data.record.DiasGarantia + '</span>'
                        } else {
                            return '<span class="uk-text-primary">' + data.record.DiasGarantia + '</span>'
                        }
                    }
                },
                CantitadProd: {
                    title: 'Cant.',
                    width: '25%',
                    key: true,
                    edit: false,
                    list: true
                },
                NombreBodega: {
                    title: 'Bodega',
                    edit: false
                },
                NombreCliente: {
                    title: 'Cliente',
                    width: '25%',
                    edit: false
                },
                Telefono: {
                    title: 'Telefono',
                    width: '10%',
                    edit: false
                },
                Direccion: {
                    title: 'Dirección',
                    width: '10%',
                    edit: false,
                    list: false
                },
                Email: {
                    title: 'Correo',
                    width: '10%',
                    edit: true
                },
                TipoMantenimiento: {
                    title: 'Tipo de Mantenimiento',
                    options: 'views/modulos/ajax/API_mantenimientosEQ.php?action=listTipoMantenimientos',
                    list: false,
                    edit: true
                },
                OrdenFisica: {
                    title: 'Orden de Trabajo (Fisica)',
                    list: false,
                    edit: false,
                    input: function(data) {
                        return '<input class="md-input" type="number" name="ordenTrabajo" placeholder="Ingrese el codigo sin 0 delante, ejem. 3050" required"/>';
                    }
                },
                FechaMantenimiento: {
                    title: 'Fecha de Mantenimiento',
                    width: '15%',
                    displayFormat: 'YYYY-MM-DD',
                    type: 'date',
                    list: false,
                    input: function(data) {
                       
                        return '<input class="md-input md-bg-red-100" type="date" name="mantenimientoDate" required value="' + data.value + '" data-uk-datepicker="{format:\'YYYY-MM-DD\', i18n:{ months: [\'Enero\', \'Febrero\', \'Marzo\', \'Abril\', \'Mayo\', \'Junio\', \'Julio\', \'Agosto\', \'Septiembre\', \'Octubre\', \'Noviembre\', \'Diciembre\'],  weekdays: [\'DOM\', \'LUN\', \'MAR\', \'MIE\', \'JUE\', \'VIE\', \'SAB\'] }}"/>';
                        
                    }
                },
                Tecnico: {
                    title: 'Técnico Asignado',
                    options: 'views/modulos/ajax/API_mantenimientosEQ.php?action=listTecnicos',
                    list: false,
                    edit: true
                },
                HoraInicio: {
                    title: 'Hora de Inicio',
                    list: false,
                    type: 'time',
                    input: function(data) {
                        let min = '08'
                        return '<input class="md-input" type="time" name="mantenimientoTimeINI" id="uk_tp_1" data-uk-timepicker="{start:'+min+',end:\'17\'}" autocomplete="off">';
                      
                    }
                },
                HoraFin: {
                    title: 'Hora de Culminación',
                    list: false,
                    type: 'time',
                    input: function(data) {
                        let min = '08'
                        return '<input class="md-input" type="time" name="mantenimientoTimeFIN" id="uk_tp_1" data-uk-timepicker="{start:'+min+',end:\'17\'}" autocomplete="off">';
                        
                    }
                },
                Comentario: {
                    title: 'Comentario',
                    type: 'textarea',
                    list: false
                },

            }
        }).jtable('load', { fechaINI: fechaInicial, fechaFIN: fechaFinal })

        // change buttons visual style in ui-dialog
        $('.ui-dialog-buttonset')
            .children('button')
            .attr('class', '')
            .addClass('md-btn md-btn-flat')
            .off('mouseenter focus');
        $('#AddRecordDialogSaveButton,#EditDialogSaveButton,#DeleteDialogButton').addClass('md-btn-flat-primary');

    }
};


altair_form_adv = {

    // date range
    date_range: function() {
        var $dp_start = $('#uk_dp_start'),
            $dp_end = $('#uk_dp_end');

        var start_date = UIkit.datepicker($dp_start, {
            format: 'YYYY-MM-DD',
            i18n: {
                months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                weekdays: ['DOM', 'LUN', 'MAR', 'MIE', 'JUE', 'VIE', 'SAB']
            }
        });

        var end_date = UIkit.datepicker($dp_end, {
            format: 'YYYY-MM-DD',
            i18n: {
                months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                weekdays: ['DOM', 'LUN', 'MAR', 'MIE', 'JUE', 'VIE', 'SAB']
            }
        });

        $dp_start.on('change', function() {
            end_date.options.minDate = $dp_start.val();
            setTimeout(function() {
                $dp_end.focus();
            }, 300);
        });

        $dp_end.on('change', function() {
            start_date.options.maxDate = $dp_end.val();
        });
    }
}