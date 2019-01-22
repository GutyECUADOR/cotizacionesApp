$(function() {

    /* Evita problema de doble calendario en firefox*/
    $('input[type=date]').on('click', function(event) {
        var isFirefox = typeof InstallTrigger !== 'undefined';
        if (isFirefox) {
            event.preventDefault();
        }
    });

    var modalAgendar = UIkit.modal($('#modal_AgendarNuevo'),  {modal: false, keyboard: false, bgclose: false, center: true});
            
    config.date_range();

    $('.anularCita').on('click', function(event) {
        let htmlElement = event.currentTarget;
        let codigoMNT = htmlElement.getAttribute("data-mantenimiento");

        UIkit.modal.confirm('Está seguro que desea anular el mantenimiento ' + codigoMNT + ' ?', function() {
            $.ajax({
                url: 'views/modulos/ajax/API_mantenimientosEQ.php?action=anular',
                method: 'GET',
                data: 'codigoMNT=' + codigoMNT,

                success: function( response ) {
                    response = JSON.parse(response);
                    if (response.status == 'OK') {

                        UIkit.modal.alert(response.mensaje, {center: true, labels: {'Ok': 'Ok'}}).on('hide.uk.modal', function() {
                            modalAgendar.hide();
                            location.reload();
                        });
                       
                    }else{
                        UIkit.modal.alert(response.mensaje, {center: true, labels: {'Ok': 'Ok'}}).on('hide.uk.modal', function() {
                            modalAgendar.hide();
                            location.reload();
                        });
                    }
                    
                },
                error: function(error) {
                    alert('No se pudo completar la operación. #' + error.status + ' ' + error.statusText);
                }

            });
        },  {labels: {'Ok': 'Si', 'Cancel': 'Cancelar'}});


    });

    $('.aprobarCita').on('click', function (event) {
        let htmlElement = event.currentTarget;
        let codigoMNT = htmlElement.getAttribute("data-mantenimiento").trim();

        UIkit.modal.confirm('Dar por culminado el mantenimiento ' + codigoMNT + ' ?', function () {

            var modalBlocked = UIkit.modal.blockUI('<div class=\'uk-text-center\'>Realizando, espere por favor...<br/><img class=\'uk-margin-top\' src=\'assets/img/spinners/spinner.gif\' alt=\'\'>');
            modalBlocked.show();

            //aprobar
            $.ajax({
                url: 'views/modulos/ajax/API_mantenimientosEQ.php?action=aprobar',
                method: 'GET',
                data: 'codigoMNT=' + codigoMNT,

                success: function (response) {
                    modalBlocked.hide();
                    response = JSON.parse(response);
                    if (response.status == 'OK') {
                        UIkit.modal.alert(response.mensaje + ' :' + codigoMNT, { labels: { 'Ok': 'Listo' } });
                    } else {
                        UIkit.modal.alert(response.mensaje + codigoMNT, { labels: { 'Ok': 'Listo' } });
                    }

                    console.log('finalizado: ' + codigoMNT);
                    console.log(response);

                    UIkit.modal.confirm('Desea agendar proximo mantenimiento al equipo?', function () {

                        $('#codMantenimientoModal').val(codigoMNT);
                        modalAgendar.show();

                    }, function () {
                        console.log('Rejected.');
                        location.reload();
                    }, { labels: { 'Ok': 'Si, agendar.', 'Cancel': 'No, ya no requiere.' } });

                },
                error: function (error) {
                    alert('No se pudo completar la operación, informe a sistemas. #' + error.status + ' ' + error.statusText);
                }

            });
        }, { labels: { 'Ok': 'Si', 'Cancel': 'Cancelar' } });


    });

    /*Funcion para generar el reporte segun codigo de mantenimiento*/
    $('.showInforme').on('click', function(event) {
        let htmlElement = event.currentTarget;
        let codigoMNT = htmlElement.getAttribute("data-mantenimiento");

        $.ajax({
            url: 'reportes/hojaMantenimientoByID.php',
            method: 'GET',
            data: 'codigoMNT=' + codigoMNT,

            success: function() {
                alert('Generando reporte con ID : ' + codigoMNT);
                window.open('reportes/hojaMantenimientoByID.php?codigoMNT=' + codigoMNT);
            },error: function(error) {
                alert('No se pudo completar la operación, informe a sistemas. #' + error.status + ' ' + error.statusText);
            }

        });
    });

    /* Funcion genera insert a tabla de mantenimientos con el codigo de MNT actual*/
    $('#btnGeneraExtraAgendamiento').on('click', function(event) {
        let selectFecha = $("#uk_dp_proxMant").val();
        console.log(selectFecha);
        var $extraAgen_form = $('#extraAgendar_form');
        var form_serializedModal = JSON.stringify($extraAgen_form.serializeObject(), null, 2);

        //UIkit.modal.alert('<p>Form data:</p><pre>' + form_serializedModal + '</pre>');

        $.ajax({
            url: 'views/modulos/ajax/API_mantenimientosEQ.php?action=extraAgendamiento',
            method: 'GET',
            data: {formData: form_serializedModal},

            success: function(response) {
                console.log(response);
                response = JSON.parse(response);
                if (response.status == 'OK') {

                    UIkit.modal.alert(response.mensaje, {center: true, labels: {'Ok': 'Ok'}}).on('hide.uk.modal', function() {
                        modalAgendar.hide();
                        location.reload();
                    });

                }else{
                    UIkit.modal.alert(response.mensaje, {labels: {'Ok': 'Ok'}});
                }
               
            },error: function(error) {
                alert('No se pudo completar la operación, informe a sistemas. #' + error.status + ' ' + error.statusText);
            }

        });

    });

});

config = {
    // date range
    date_range: function() {
        var $dp_start = $('#uk_dp_proxMant');
          
        var start_date = UIkit.datepicker($dp_start, {
            format: 'YYYY-MM-DD',
            i18n: {
                months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                weekdays: ['DOM', 'LUN', 'MAR', 'MIE', 'JUE', 'VIE', 'SAB']
            }
        });


    }
}
