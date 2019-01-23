class Cotizacion {
    constructor() {
        this.cliente = null,
        this.productos = []
    }
}

class Cliente {
    constructor(RUC, nombre, email, telefono, vendedor, tipoPrecio, diasPago, formaPago) {
      this.RUC = RUC;
      this.nombre = nombre;
      this.email = email;
      this.telefono = telefono;
      this.vendedor = vendedor;
      this.tipoPrecio = tipoPrecio;
      this.diasPago = diasPago;
      this.formaPago = formaPago;
      
    }

    getTipoPrecio() {
        return + this.tipoPrecio;
    }
}

class Producto {
    constructor(codigo, nombre, cantidad, precio, descuento) {
      this.codigo = codigo;
      this.nombre = nombre;
      this.cantidad = cantidad;
      this.precio = precio;
      this.descuento = descuento;
    }

    getSubtotal(){
        return this.cantidad * this.precio;
    }
  }

$(document).ready(function() {

    
    // Documento listo
    disableEnter();
    startJSBoostrap();

    var limite_productos = 0;
    var cotizacion = new Cotizacion();
    var newProducto = null;
    
    let iva = getiva();
    

    /* Eventos y Acciones */
    $("#inputRUC").on("keyup", function(event) {
        validaCliente();
    });

    // Boton remover fila de tabla productos
    $("#tablaProductos").on('click', '.btnEliminaRow', function(event) {
        let codProdToDelete = $(this).data("codigo"); // Obtenemos el campo data-value custom

        deleteProductToList(codProdToDelete);

    });

    // Caja de texto de producto nuevo
    $("#inputNuevoCodProducto").on('blur', function(event) {
       
        if (cotizacion.cliente == null) {
            alert('Indique un cliente antes de agregar productos.');
            return;
        }

        let codProducto = $(this).val(); // Obtenemos el item 
        let tipoPrecio = 'A';

        $.ajax({
            type: 'get',
            url: 'views/modulos/ajax/API_cotizaciones.php?action=getInfoProducto', // API retorna objeto JSON de producto, false caso contrario.
            dataType: "json",

            data: { codigo: codProducto, tipoPrecio: tipoPrecio },

            success: function(response) {
            console.log(response);
                let producto = response.data;
                if (producto) {
                    newProducto = new Producto(producto.CODIGO, producto.NOMBRE, 1, producto.PRECIO, 0);
                    printDataProducto(newProducto);

                } else {
                    new PNotify({
                        title: 'Item no disponible',
                        text: 'No se ha encontrado el producto con el codigo: ' + codProducto,
                        delay: 3000,
                        type: 'warn',
                        styling: 'bootstrap3'
                    });


                }

            }
        });

    });

    // Caja de texto de producto nuevo
    $("#btnAgregarProdToList").on('click', function(event) {
       if (newProducto != null) {
           addProductToList(newProducto);
           printProductos(cotizacion.productos);
       }else{
           alert('No hay producto que agregar a la lista');
       }

    });

    $("#inputNuevoProductoCantidad").on('change', function(event) {
        let nuevacantidad = $(this).val();
        console.log(nuevacantidad);
        if (newProducto != null) {
            newProducto.cantidad = nuevacantidad;
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

    /* Funciones */

    function addProductToList(newProducto){

        let existeInArray = cotizacion.productos.findIndex(function(productoEnArray) {
            return productoEnArray.codigo === newProducto.codigo;
        });
            
        if (existeInArray === -1){ // No existe el producto en el array
            cotizacion.productos.push(newProducto);
            resetnewProducto();
        }else{
            alert('El item ya existe en la lista');
        }

        console.log(cotizacion.productos);
    }

    function deleteProductToList(codProdToDelete){

        let index = cotizacion.productos.findIndex(function(productoEnArray) {
            return productoEnArray.codigo === codProdToDelete;
        });
            
        //console.log('elimina el: '+ index);
        cotizacion.productos.splice(index, 1);

        console.log(cotizacion.productos);
        printProductos(cotizacion.productos);
    }

    function resetnewProducto() {
        newProducto = null;
        document.getElementById("inputNuevoCodProducto").value = "";
        document.getElementById("inputNuevoProductoNombre").value = "";
        document.getElementById("inputNuevoProductoCantidad").value = "";
        document.getElementById("inputNuevoProductoPrecioUnitario").value = "";
        document.getElementById("inputNuevoProductoDescuento").value = "";
        document.getElementById("inputNuevoProductoSubtotal").value = "";
    }

    function printDataProducto(producto){
       document.getElementById("inputNuevoProductoNombre").value = producto.nombre;
       document.getElementById("inputNuevoProductoCantidad").value = producto.cantidad;
       document.getElementById("inputNuevoProductoPrecioUnitario").value = producto.precio;
       document.getElementById("inputNuevoProductoDescuento").value = producto.descuento;
       document.getElementById("inputNuevoProductoSubtotal").value = producto.getSubtotal();
    }

    function printProductos(arrayProductos){
        $('#tablaProductos').find("tr:gt(0)").remove();
        
        arrayProductos.forEach(producto => {
            let row = `
                <tr>
                    <td><input type="text" class="form-control text-center" value="${producto.codigo}"></td>
                    <td><input type="text" class="form-control text-center"  value="${producto.nombre}" readonly></td>
                    <td><input type="number" class="form-control text-center rowcantidad" value="${producto.cantidad}"></td>
                    <td>
                        <input type="text" class="form-control text-center precio_linea" value="${producto.precio}"  readonly>
                        <input type="hidden" class="hidden_precioUnitario" name="hidden_precio_product[]">
                    </td>
                    <td><input type="text" class="form-control text-center" placeholder="%" value="${producto.descuento}" ></td>
                    <td><input type="text" class="form-control text-center importe_linea" readonly></td>
                    <td><input type="text" class="form-control text-center" readonly></td>
                    <td><button type="button" class="btn btn-danger btn-sm btn-block btnEliminaRow" data-codigo="${producto.codigo}"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Eliminar</button>
                    </td>
                </tr>
                `;

                $('#tablaProductos > tbody:last-child').append(row);


        });
    }

    function validaCliente() {
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
                    const myCliente = new Cliente(cliente.RUC, cliente.NOMBRE, cliente.EMAIL, cliente.TELEFONO, cliente.VENDEDOR, cliente.TIPOPRECIO, cliente.DIASPAGO, cliente.FPAGO);
                    cotizacion.cliente = myCliente;
                    console.log(cotizacion);
    
                    $('#inputCodigo').val(cliente.CODIGO.trim());
                    $('#inputNombre').val(cliente.NOMBRE.trim());
                    $('#inputRSocial').val(cliente.EMPRESA.trim());
                    $('#inputCorreo').val(cliente.EMAIL.trim());
                    $('#inputTelefono').val(cliente.TELEFONO.trim());
                    $('#inputDiasPago').val(cliente.DIASPAGO.trim() + ' ('+cliente.FPAGO.trim() + ')');
                    $('#inputVendedor').val(cliente.VENDEDOR.trim() + ' ('+cliente.VENDEDORNAME.trim() + ')');
                    
    
                } else {
                    myCliente = null;
                    $('#inputCodigo').val('');
                    $('#inputNombre').val('(Sin identificar)');
                    $('#inputRSocial').val('');
                    $('#inputCorreo').val('');
                    $('#inputTelefono').val('');
                    $('#inputCupo').val('');
    
                    console.log('No data');
    
                }
    
            }
        });
    }
   
   
});

/* FIN DOC Ready */

// Eventos Listener de los elementos
$("#formulario_registro").on("submit", function(event) {
    event.preventDefault();
    let form = $(this).serialize();
    console.log(form);

});




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
    PNotify.prototype.options.styling = "bootstrap3";
    PNotify.prototype.options.styling = "fontawesome";
    $('[data-toggle="tooltip"]').tooltip();
}





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