 

$(function() {
    
    altair_form_adv.date_range();
    fechaActual = new Date().toISOString().slice(0, 10);
    console.log(fechaActual);

    /* Funcion busca que despliega resultados de busqueda*/
    $('#btn_search').on('click', function (event) {
    var modalBlocked = UIkit.modal.blockUI('<div class=\'uk-text-center\'>Realizando, espere por favor...<br/><img class=\'uk-margin-top\' src=\'assets/img/spinners/spinner.gif\' alt=\'\'>');
    modalBlocked.show();
    
        let fechaInicial = $('#uk_dp_start').val();
        let fechaFinal = $('#uk_dp_end').val();
        let tiposDocs = $('#select_tiposDoc').val();
        /* Comprobacion de parametros no sean nullos y asignacion de valores si lo son*/
        if (fechaInicial == null || fechaInicial == "" || fechaFinal == null || fechaFinal == "") {
            fechaInicial = new Date().toISOString().slice(0, 10);
            fechaFinal = new Date().toISOString().slice(0, 10);

        }

        console.log(fechaInicial);
        console.log(fechaFinal);
        console.log(tiposDocs);
   
    $.ajax({
        url: 'views/modulos/ajax/API_estadisticas.php?action=getHistorico',
        method: 'GET',
        data: { fechaInicial: fechaInicial, fechaFinal:fechaFinal, tiposDocs:tiposDocs },

        success: function (response) {
            console.log(response);
            altair_form_adv.displayData(response);
            
        }, error: function (error) {
            alert('No se pudo completar la operación, informe a sistemas. #' + error.status + ' ' + error.statusText);
        },complete: function() {
            modalBlocked.hide();
        }

    });


    });

});

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
    },
    displayData: function (arrayResultados) {
        let repsonse = JSON.parse(arrayResultados);
        let rows = repsonse.data;
        console.log(rows);

        $('#tbodyresults').html('');
        let contador = 0;
        rows.forEach(row => {
            contador++;
            let codEstado = (parseInt(row.Estado));
            let rowHTML = `
            <tr class="">
                <td class="uk-text-center"> ${ contador } </td>
                <td class="uk-text-center"> ${row.CodigoFac} </td>
                <td class="uk-text-center"> ${row.CodMNT} </td>
                <td class="uk-text-center"> ${row.Cliente} </td>
                <td class="uk-text-center"> ${row.CodProducto} </td>
                <td class="uk-text-center"> ${row.FechaINI} </td>
                <td class="uk-text-center"> <span class="uk-badge ${ altair_form_adv.getColorBadge(codEstado) }"> ${ altair_form_adv.getDescStatus(codEstado) } </span></td>
            </tr>
                    `;

            $('#tbodyresults').append(rowHTML);

        });

    },
    getColorBadge: function ($codigo) {
        
        switch ($codigo) {
            case 0:
            return 'uk-badge-primary';
            break;
            
            case 1:
            return 'uk-badge-success';
            break;

            case 2:
            return 'uk-badge-danger';
            break;
            
            case 3:
            return 'uk-badge-warning';
            break;

            default:
            return '';
            
            break;
        }
       
    },
    getDescStatus: function ($codigo) {
        
        switch ($codigo) {
            case 0:
            return 'Pendiente';
            break;
            
            case 1:
            return 'Finalizada';
            break;

            case 2:
            return 'Anulada';
            break;
            
            case 3:
            return 'Omitida';
            break;

            default:
            return 'No difinida';
            
            break;
        }
       
    }

} 