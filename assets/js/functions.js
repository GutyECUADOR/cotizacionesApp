
$(document).ready(function() {
    // Documento listo
    PNotify.prototype.options.styling = "bootstrap3";
    PNotify.prototype.options.styling = "fontawesome";
    var limite_productos = 0;

    let iva = getiva();
    disableEnter();
    startJSBoostrap();

   
});

// Boton de registro
$("#btnGuardar").on("click", function(event) {



});

// Eventos Listener de los elementos
$("#formulario_registro").on("submit", function(event) {
    event.preventDefault();
    let form = $(this).serialize();
    console.log(form);

});

$("#inputRUC").on("keyup", function(event) {
    ajaxvalidacod_json();
});

$("#btnAgregaFilaProducto").on("click", function(event) {
    add_row_producto();
});


//Ajax a informacion del producto
$("#tablaProductos").on('blur', '.rowproducto', function(event) {

    let clickedelement = $(this)[0]; // Obtenemos el item clickeado
    let grupoElements = $(".rowproducto"); // Array de text codigo
    let indice = grupoElements.index(clickedelement); // Obtenemos el indice del item dentro del grupo
    let codProducto = clickedelement.value; // Obtenemos el valor del elemento clieckedo
    let grupoElementsDetalle = $(".row_deproducto"); //Array de text del detalle
    let grupoElementsPrecio = $(".precio_linea"); //Array de text del precio
    let grupoElementsTotal = $(".importe_linea"); //Array de text del precio
    let grupoElementsHiddenPrecio = $(".hidden_precioUnitario") // Array de objetos de precio unitario de productos
    let grupoElementsCantidad = $(".rowcantidad"); //Array de text de la cantidad

    $.ajax({
        type: 'get',
        url: 'views/modulos/ajax/API_cotizaciones.php?action=getInfoProducto', // API retorna objeto JSON de producto, false caso contrario.
        dataType: "json",

        data: { codigo: codProducto },

        success: function(response) {
        console.log(response);
            let producto = response.data;
            if (producto) {

                let valorUnitario = producto.PrecA.trim();
                grupoElementsDetalle[indice].value = producto.Nombre.trim();
                grupoElementsPrecio[indice].value = (Math.round(valorUnitario * 100) / 100).toFixed(2);
                grupoElementsHiddenPrecio[indice].value = (Math.round(valorUnitario * 100) / 100).toFixed(2);
                grupoElementsTotal[indice].value = (Math.round(valorUnitario * 100) / 100).toFixed(2);
                grupoElementsCantidad[indice].value = 1;

                calcular_total();

                let cantProd = Number(grupoElementsCantidad[indice].value);

            } else {

                new PNotify({
                    title: 'Item no disponible',
                    text: 'No se ha encontrado el producto con el codigo: ' + codProducto,
                    delay: 3000,
                    type: 'warn',
                    styling: 'bootstrap3'
                });


                calcular_total()
                grupoElementsDetalle[indice].value = 'No identificado';
                grupoElementsPrecio[indice].value = 0;
                grupoElementsHiddenPrecio[indice].value = 0;
                grupoElementsTotal[indice].value = 0;
                grupoElementsCantidad[indice].value = 0;
                console.log(response);

            }

        }
    });

});


// Remover fila de tabla productos
$("#tablaProductos").on('click', '.btnEliminaRow', function(event) {
    let grupoElementsPrecio = $(".importe_linea"); //Array de text del precio
    let element = $(this)[0];

    if (grupoElementsPrecio.length > 1) {
        element.parentElement.parentElement.remove();
        calcular_total();
    } else {
        mySwal("Imposible Eliminar, debe existir un producto en lista");
    }


});


// Evento de calculo de productos extra
$("#tablaProductos").on('keyup blur click', '.rowcantidad', function(event) {

    let clickedelement = $(this)[0]; // Obtenemos el item clickeado
    let grupoElementsPrecio = $(".importe_linea"); //Array de text del precio
    let grupoElementsHiddenPrecio = $(".hidden_precioUnitario"); // Array de objetos de precio unitario de productos
    
    let grupoElements = $(".rowcantidad"); // Array de text codigo
    let indice = grupoElements.index(clickedelement); // Obtenemos el indice del item dentro del grupo
    let codProducto = clickedelement.value; // Obtenemos el valor del elemento clieckedo

    if (Number(clickedelement.value) > 0) {
        if (Number(grupoElementsHiddenPrecio[indice].value) > 0) {
            let calculo = Number(grupoElementsHiddenPrecio[indice].value) * Number(clickedelement.value);
            grupoElementsPrecio[indice].value = (Math.round(calculo * 100) / 100).toFixed(2);

        } else {
            new PNotify({
                title: 'Dato incorrecto',
                text: 'La cantidad es nula o no vàlida',
                delay: 3000,
                type: 'warn',
                styling: 'bootstrap3'
            });
            console.log("La cantidad es nula o no válida");
        }

    } else {


        grupoElementsPrecio[indice].value = 0;
        console.warn("Cantidad no permitida");
        new PNotify({
            title: 'Dato incorrecto',
            text: 'Cantidad no permitida',
            delay: 3000,
            type: 'warn',
            styling: 'bootstrap3'
        });
    }

    calcular_total()
});

// Funcion de carga de modal window
function loadmodal() {
    $("#modalcodigo").modal();
    //Obtener valor seleccionado y mostrarlo en el modal
    var val_evalua = document.getElementById("txt_empleadoIdentificado").value;
    $("#myModalLabel_usuario").text(val_evalua);

}

function mySwal(mensajem, tipoAlerta = 'warning') {
    swal({
        title: 'Atención',
        text: mensajem,
        type: tipoAlerta,
        showCancelButton: false,
        closeOnConfirm: false,
        confirmButtonText: 'Aceptar',
        showLoaderOnConfirm: true
    });
}

function disableEnter() {
    $("form").keypress(function(e) {
        if (e.which == 13) {
            return false;
        }
    });
}

function startJSBoostrap() {
    $('[data-toggle="tooltip"]').tooltip();
}
// Funcion de valdacion de codigo de seguridad

function ajaxvalidacod_seguridad() {

    var cod_usu_ing = document.getElementById("txt_cajacod").value;
    var ci_usu = document.getElementById("txt_CIRUC").value;

    $.ajax({
        type: 'post',
        url: 'valida_cod_seguridad.php',

        data: { post_cod_usr: cod_usu_ing, ci_usu: ci_usu },

        success: function(r) {
            $('#mymodal').show(); // put your modal id 
            $('.resultmodal').show().html(r);

            var verificacion_cod = document.getElementById("cod_veri").value;
            if (verificacion_cod !== "") {
                document.getElementById("aceptar_modal1").removeAttribute('disabled');
                $("#txt_cisolicitante").attr("readonly", "readonly");
                //showselectChkListRealizados(); Funcion carga ultimo check del usuario ci ingresado
            }
        }
    });

};

function ajaxvalidacod_json() {
    let CI_RUC = document.getElementById("inputRUC").value;

    $.ajax({
        type: 'get',
        url: 'views/modulos/ajax/API_cotizaciones.php?action=getInfoCliente',
        dataType: "json",

        data: { ruc: CI_RUC },
        
        success: function(response) {
            console.log(response);
            let cliente = response.data;
            if (response.data) {
                $('#inputCodigo').val(cliente.CODIGO.trim());
                $('#inputNombre').val(cliente.NombreN.trim());
                $('#inputRSocial').val(cliente.EmpresaN.trim());
                $('#inputTelefono').val(cliente.TELEFONO1.trim());
                $('#inputCupo').val(cliente.LIMITECRED.trim());
                console.log(response);
            } else {

                $('#inputCodigo').val('');
                $('#inputNombre').val('(Sin identificar)');
                $('#inputRSocial').val('');
                $('#inputTelefono').val('');
                $('#inputCupo').val('');

                console.log('No data');

            }

        }
    });
}

function add_row_producto() {
    let row = `
            <tr>
                <td><input type="text" class="form-control text-center rowproducto"></td>
                <td><input type="text" class="form-control text-center row_deproducto" readonly></td>
                <td><input type="number" class="form-control text-center rowcantidad" value="0"></td>
                <td>
                    <input type="text" class="form-control text-center precio_linea" readonly>
                    <input type="hidden" class="hidden_precioUnitario" name="hidden_precio_product[]">
                </td>
                <td><input type="text" class="form-control text-center" placeholder="%"></td>
                <td><input type="text" class="form-control text-center importe_linea" readonly></td>
                <td><input type="text" class="form-control text-center" readonly></td>
                <td><button type="button" class="btn btn-danger btn-sm btn-block btnEliminaRow"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Eliminar</button>
                </td>
            </tr>
            `;

    if (limite_productos < 25) {
        $('#tablaProductos > tbody:last-child').append(row);
        limite_productos++;
    } else {
        swal({
            title: 'Límite alcanzado',
            text: 'Se pueden registrar hasta 25 items',
            type: 'warning',
            showCancelButton: false,
            closeOnConfirm: false,
            confirmButtonText: 'Aceptar',
            showLoaderOnConfirm: true
        });
    }


};

function calculaIVA(IVA) {
    let totalIVA = 0;
    $(".importe_linea").each(
        function(index, value) {
            let itemIVA = eval(value.value) * IVA / 100;
            totalIVA += itemIVA;
        }
    );
    return totalIVA;
}

function calcular_total() {
    //Suma de columna de valores
    var importe_total = 0;
    $(".importe_linea").each(
        function(index, value) {
            importe_total = importe_total + eval($(this).val());
        }
    );

    //Despliege de resultados
    $("#txt_subtotal").val(importe_total.toFixed(2));
    $("#txt_ivaBienes").val(calculaIVA(getiva()).toFixed(2));



    var total_unidades = 0;
    $(".rowcantidad").each(
        function(index, value) {
            total_unidades = total_unidades + eval($(this).val());
        }
    );

    $("#txt_unidadesProd").val(total_unidades);

    let iva_db = getiva();
    let iva_total = calculaIVA(iva_db);

    $("#txt_iva").val(iva_total.toFixed(2));

    let total_factura = importe_total + iva_total;
    $("#welltotal").text("$ " + total_factura.toFixed(2));
    $("#txt_totalPagar").val(total_factura.toFixed(2));


    return total_factura.toFixed(2);
};

function getiva() {
    
    let iva = 12;
   
    return iva;
}