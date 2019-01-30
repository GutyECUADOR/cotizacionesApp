class Cotizacion {
    constructor() {
        this.cliente = null,
        this.productos = []
    }

    sumarFromProductos(propiedad) {
        let total = 0;
        for ( var i = 0, _len = this.productos.length; i < _len; i++ ) {
            total += parseInt(this.productos[i][propiedad]);
        }
        return total
    }

    getTotalProductos(){
        let total = 0;
        for ( var i = 0, _len = this.productos.length; i < _len; i++ ) {
            total += parseFloat(this.productos[i].getSubtotal());
        }
        return total
    }

    getIVAProductos(){
        let total = 0;
        for ( var i = 0, _len = this.productos.length; i < _len; i++ ) {
            total += parseFloat(this.productos[i].getIVA());
        }
        return total
    }

    getDescuentoProductos(){
        let total = 0;
        for ( var i = 0, _len = this.productos.length; i < _len; i++ ) {
            total += parseFloat(this.productos[i].getDescuento());
        }
        return total
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

    getIVA(IVA = 12){
        return (this.getSubtotal() * IVA) / 100;
    }

    getDescuento(){
        return ((this.cantidad * this.precio)* this.descuento)/100;
    }

    getSubtotal(){
        return (this.cantidad * this.precio) - this.getDescuento(this.descuento);
    }
  }

$(document).ready(function() {

    
    // Documento listo
    disableEnter();
    startJSBoostrap();
    

    var limite_productos = 0;
    var cotizacion = new Cotizacion();
    var newProducto = null;
    
    /* Eventos y Acciones */
    $("#inputRUC").on("keyup", function(event) {
        validaCliente();
    });

    // Boton de envio de datos
    $("#btnGuardar").on('click', function(event) {
        event.preventDefault();
        console.log('enviar data');

        let cotizacionJSON = JSON.stringify((cotizacion));
        if (cotizacion.cliente != null && cotizacion.productos.length > 0) {
            $(this).prop("disabled", true);
            saveData(cotizacionJSON);
        }else{
            alert('El formulario esta incompleto indique cliente y al menos un producto');
        }
        
        
    });

    // Boton remover fila de tabla productos
    $("#tablaProductos").on('click', '.btnEliminaRow', function(event) {
        let codProdToDelete = $(this).data("codigo"); // Obtenemos el campo data-value custom
        deleteProductToList(codProdToDelete);
        let objectResumen = resumenProdutosInList();
        printResumen(objectResumen);
    });

    // Caja de texto de producto nuevo
    $("#inputNuevoCodProducto").on('blur', function(event) {
       
        if (cotizacion.cliente == null) {
            alert('Indique un cliente antes de agregar productos.');
            return;
        }

        let codProducto = $(this).val(); // Obtenemos el item 
        let clienteRUC = $('#inputRUC').val();

        $.ajax({
            type: 'get',
            url: 'views/modulos/ajax/API_cotizaciones.php?action=getInfoProducto', // API retorna objeto JSON de producto, false caso contrario.
            dataType: "json",

            data: { codigo: codProducto, clienteRUC: clienteRUC },

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
            let objectResumen = resumenProdutosInList();
            printResumen(objectResumen);
       }else{
           alert('No hay producto que agregar a la lista');
       }

    });

    /* Multiplica la cantidad del producto a añadir a la lista*/
    $("#inputNuevoProductoCantidad").on('change', function(event) {
        let nuevacantidad = $(this).val();
        //console.log(nuevacantidad);
        if (newProducto != null) {
            newProducto.cantidad = nuevacantidad;
            printSubtotalNewProd();
        }
 
     });

    /* Establece el valor del descuento del producto a agregar*/
    $("#inputNuevoProductoDescuento").on('change', function(event) {
        let nuevodescuento = $(this).val();
        //console.log(nuevodescuento);
        if (newProducto != null) {
            newProducto.descuento = nuevodescuento;
            //console.log(newProducto.getDescuento(nuevodescuento));
            printSubtotalNewProd();
        }
        
 
     });
    

    // Evento de calculo de productos extra
    $("#tablaProductos").on('keyup blur click', '.rowcantidad', function(event) {

       
    });

    /* Funciones */

    function saveData(formData){
       
        console.log(formData);
        $.ajax({
            type: 'get',
            url: 'views/modulos/ajax/API_cotizaciones.php?action=saveCotizacion',
            dataType: "json",
    
            data: { formData: formData },
            
            success: function(response) {
                console.log(response);
                mySwal(response.data.mensaje + 'ID de documento generado: ' + response.data.new_cod_VENCAB, "success");
            }
        });

       

    }

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

        //console.log(cotizacion.productos);
    }

    function deleteProductToList(codProdToDelete){

        let index = cotizacion.productos.findIndex(function(productoEnArray) {
            return productoEnArray.codigo === codProdToDelete;
        });
            
        //console.log('elimina el: '+ index);
        cotizacion.productos.splice(index, 1);

        //console.log(cotizacion.productos);
        printProductos(cotizacion.productos);
    }

    function multiProdCant(codProducto){

        let index = cotizacion.productos.findIndex(function(productoEnArray) {
            return productoEnArray.codigo === codProducto;
        });
            
        
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
                    <td><input type="text" class="form-control text-center" value="${producto.codigo}" disabled></td>
                    <td><input type="text" class="form-control text-center"  value="${producto.nombre}" readonly></td>
                    <td><input type="number" class="form-control text-center rowcantidad data-codigo="${producto.codigo}"" value="${producto.cantidad}" disabled></td>
                    <td>
                        <input type="text" class="form-control text-center precio_linea" value="${producto.precio}" readonly>
                    </td>
                    <td><input type="text" class="form-control text-center" placeholder="%" data-codigo="${producto.codigo}" value="${producto.descuento}" disabled></td>
                    <td><input type="text" class="form-control text-center" value="${producto.getSubtotal().toFixed(2)}" readonly></td>
                    <td><input type="text" class="form-control text-center" value="${producto.getIVA().toFixed(2)}" readonly></td>
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
                    $('#inputTipoPrecioCli').val(cliente.TIPOPRECIO.trim());
    
                } else {
                    myCliente = null;
                    cotizacion.cliente = null;
                    $('#inputCodigo').val('');
                    $('#inputNombre').val('(Sin identificar)');
                    $('#inputRSocial').val('');
                    $('#inputCorreo').val('');
                    $('#inputTelefono').val('');
                    $('#inputCupo').val('');
    
                    //console.log('No data');
    
                }
    
            }
        });
    }

    function printSubtotalNewProd (){
        $("#inputNuevoProductoSubtotal").val(newProducto.getSubtotal().toFixed(2));
    }
   
    function resumenProdutosInList() {
        
        return {
            sumaSubtotalproductos: cotizacion.getTotalProductos() + cotizacion.getIVAProductos(),
            sumaTotalItems: cotizacion.sumarFromProductos("cantidad"),
            sumaIVABienes: cotizacion.getIVAProductos(),
            sumaDescuento: cotizacion.getDescuentoProductos()
        };
    }

    function printResumen(objectResumen){
        $("#txt_unidadesProd").val(objectResumen.sumaTotalItems);
        $("#welltotal").html('$ '+ objectResumen.sumaSubtotalproductos.toFixed(2));
        $("#txt_subtotal").val(objectResumen.sumaSubtotalproductos.toFixed(2));
        $("#txt_ivaBienes").val(objectResumen.sumaIVABienes.toFixed(2));
        $("#txt_impuesto").val(objectResumen.sumaIVABienes.toFixed(2));
        $("#txt_descuentoResumen").val(objectResumen.sumaDescuento.toFixed(2));
        $("#txt_totalPagar").val(objectResumen.sumaSubtotalproductos.toFixed(2));
    }
   
});

/* FIN DOC Ready */

// Eventos Listener de los elementos
$("#formulario_registro").on("submit", function(event) {
    event.preventDefault();
    let form = $(this).serialize();
    //console.log(form);

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
