class Cotizacion {
    constructor() {
        this.cliente = null,
        this.productos = [],
        this.comentario = 'proforma'
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
    constructor(codigo, nombre, cantidad, precio, descuento, stock, tipoIVA, valorIVA) {
      this.codigo = codigo;
      this.nombre = nombre;
      this.cantidad = cantidad;
      this.precio = precio;
      this.descuento = descuento;
      this.stock = stock;
      this.tipoIVA = tipoIVA;
      this.valorIVA = valorIVA;
    }

    getIVA(){
        return (this.getSubtotal() * this.valorIVA) / 100;
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
    var nuevoIDDocumentGenerated = null;
    
    /* Eventos y Acciones */
    $("#inputRUC").on("keyup change", function(event) {
        validaCliente();
    });

    // Boton de busqueda de clientes
    $("#searchClienteModal").on('click', function(event) {
        event.preventDefault();
        
        let terminoBusqueda = document.getElementById("terminoBusquedaModalCliente").value;
        let tipoBusqueda = document.getElementById("tipoBusquedaModalCliente").value;
        if (terminoBusqueda.length > 0) {
            buscarClientes(terminoBusqueda, tipoBusqueda);
            
        }else{
            alert('Indique un termino de busqueda');
        }
        
    });

    // Boton de busqueda de productos
    $("#searchProductoModal").on('click', function(event) {
        event.preventDefault();
        if (cotizacion.cliente == null) {
            Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'Indique un cliente antes de agregar productos.',
              })
            return;
        }

        let terminoBusqueda = document.getElementById("terminoBusquedaModalProducto").value;
        let tipoBusqueda = document.getElementById("tipoBusquedaModalProducto").value;
        console.log(terminoBusqueda);
        console.log(tipoBusqueda);
        if (terminoBusqueda.length > 0) {
           
            buscarProductos(terminoBusqueda, tipoBusqueda);
            
        }else{
            alert('Indique un termino de busqueda');
        }
        
    });

    // Boton de asignacion de inputRUC
    $("#tblResultadosBusquedaClientes").on('click', '.btnSeleccionaCliente', function(event) {
        event.preventDefault();
        let RUCCliente = $(this).data("codigo"); // Obtenemos el campo data-value custom
        $("#inputRUC").val(RUCCliente);
        validaCliente();
        $('#modalBuscarCliente').modal('toggle'); // Cerramos modal
    });


    // Boton de asignacion de inputNuevoCodProducto
    $("#tblResultadosBusquedaProductos").on('click', '.btnSeleccionaProducto', function(event) {
        event.preventDefault();
        let codProducto = $(this).data("codigo"); // Obtenemos el campo data-value custom
        $("#inputNuevoCodProducto").val(codProducto);
        validaProducto();
        $('#modalBuscarProducto').modal('toggle'); // Cerramos modal
    });
    

    // Boton de envio de datos
    $("#btnGuardar").on('click', function(event) {
        event.preventDefault();
       
        let cotizacionJSON = JSON.stringify((cotizacion));
        if (cotizacion.cliente != null && cotizacion.productos.length > 0) {
            $(this).prop("disabled", true);
            saveData(cotizacionJSON);
        }else{
            alert('El formulario esta incompleto indique cliente y al menos un producto');
        }
        
        
    });

    // Boton de envio de datos
    $("#btnCancel").on('click', function(event) {
        event.preventDefault();
        alert('No se ha registrado el documento.');
        location.reload();
    });

    // Boton remover fila de tabla productos
    $("#tablaProductos").on('click', '.btnEliminaRow', function(event) {
        let codProdToDelete = $(this).data("codigo"); // Obtenemos el campo data-value custom
        deleteProductToList(codProdToDelete);
        let objectResumen = resumenProdutosInList();
        printResumen(objectResumen);
    });

    // Caja de texto de producto nuevo
    $("#inputNuevoCodProducto").on('blur change', function(event) {
       
        if (cotizacion.cliente == null) {
            alert('Indique un cliente antes de agregar productos.');
            return;
        }

        validaProducto();

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


    // Caja de comentarios y observaciones 
    $("#comment").on("keyup change", function(event) {
       cotizacion.comentario = $(this).val();
       //console.log(cotizacion.comentario);
    });


    // Boton de busqueda de documentos 
    $("#searchDocumentModal").on("click", function(event) {
        let fechaINI = document.getElementById("fechaINIDoc").value;
        let fechaFIN = document.getElementById("fechaFINDoc").value;
        let busqueda = document.getElementById("terminoBusquedaModalDocument").value;
        if (fechaINI.length > 0) {
            buscarDocumentos(fechaINI, fechaFIN, busqueda);
            
        }else{
            alert('Indique rango de fechas');
        }
     });

    // Boton de creacion de PDF en busqueda de documentos
    $("#tblResultadosBusquedaDocumentos").on("click", '.btnModalGeneraPDF', function(event) {
        let IDDocument = $(this).data("codigo");
        window.open('./views/modulos/ajax/API_cotizaciones.php?action=generaProforma&IDDocument='+IDDocument);
       
    });
     
    // Boton de envio de email en busqueda de documentos
    $("#tblResultadosBusquedaDocumentos").on("click", '.btnModalSendEmail', function(event) {
        let IDDocument = $(this).data("codigo");
        sendEmailByDocument(IDDocument);
    });

    // Boton de envio de email personalizado en busqueda de documentos
    $("#tblResultadosBusquedaDocumentos").on("click", '.btnModalSendCustomEmail', function(event) {
        let IDDocument = $(this).data("codigo");
        $('#modalBuscarDocumento').modal('hide');
        showModalEmail(IDDocument);
       
    });

    // Boton de envio de email personalizado en busqueda de documentos
    $("#btnSendCustomEmail").on("click", function(event) {
        tinyMCE.triggerSave();
        let IDDocument = $('#emailIDDocument').val();
        let emails = $('#emailDestinatario').val();
        let menssage = $('#mailContent').val();
       
        sendCustomEmailByDocument(IDDocument, emails, menssage);
    });

    // Boton de creacion de PDF en busqueda de documentos
    $("#tblResultadosBusquedaDocumentos").on("click", '.btnModalLoadData', function(event) {
        let IDDocument = $(this).data("codigo");
        loadDataByDocument(IDDocument);
    });

    



    /* Funciones */

    function saveData(formData){
       
        console.log(JSON.parse(formData));
        $.ajax({
            type: 'get',
            url: 'views/modulos/ajax/API_cotizaciones.php?action=saveCotizacion',
            dataType: "json",
    
            data: { formData: formData },
            
            success: function(response) {
                console.log(response);
                nuevoIDDocumentGenerated = response.data.new_cod_VENCAB;
                console.log (nuevoIDDocumentGenerated);
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

    function resetnewProducto() {
        newProducto = null;
        document.getElementById("inputNuevoCodProducto").value = "";
        document.getElementById("inputNuevoProductoNombre").value = "";
        document.getElementById("inputNuevoProductoCantidad").value = "";
        document.getElementById("inputNuevoProductoPrecioUnitario").value = "";
        document.getElementById("inputNuevoProductoSubtotal").value = "";
    }

    function printDataProducto(producto){
       document.getElementById("inputNuevoProductoNombre").value = producto.nombre;
       document.getElementById("inputNuevoProductoCantidad").value = producto.cantidad;
       document.getElementById("inputNuevoProductoPrecioUnitario").value = producto.precio;
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
                    <td><input type="text" class="form-control text-center" placeholder="%" data-codigo="${producto.codigo}" value="${producto.stock}" disabled></td>
                    <td><input type="text" class="form-control text-center" value="${producto.getSubtotal().toFixed(2)}" readonly></td>
                    <td><input type="text" class="form-control text-center" value="${producto.getIVA().toFixed(2)}" readonly></td>
                    <td><button type="button" class="btn btn-danger btn-sm btn-block btnEliminaRow" data-codigo="${producto.codigo}"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Eliminar</button>
                    </td>
                </tr>
                `;

                $('#tablaProductos > tbody:last-child').append(row);


        });
    }

    function printBusquedaClientes(arrayClientes){
        $('#tblResultadosBusquedaClientes').find("tr:gt(0)").remove();
        let cont = 1;
        arrayClientes.forEach(cliente => {
            let row = `
            <tr>
                <th scope="row">${cont}</th> 
                <td>${cliente.RUC}</td>
                <td>${cliente.NOMBRE.trim()}</td>
                <td><button type="button" class="btn btn-primary btn-sm btn-block btnSeleccionaCliente" data-codigo="${cliente.RUC.trim()}"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Seleccionar</button></td>
                
            </tr>
                `;

                $('#tblResultadosBusquedaClientes > tbody:last-child').append(row);
            cont++;

        });
    }

    function printBusquedaProductos(arrayProductos){
        $('#tblResultadosBusquedaProductos').find("tr:gt(0)").remove();
        let cont = 1;
        let precioDisplay = 'PRECIO_'+cotizacion.cliente.tipoPrecio;
        console.log(precioDisplay);
        arrayProductos.forEach(producto => {
            let row = `
            <tr>
                <th scope="row">${cont}</th> 
                <td>${producto.CODIGO}</td>
                <td>${producto.NOMBRE.trim()}</td>
                <td>${parseFloat(producto[precioDisplay].trim()).toFixed(2)}</td>
                <td>${producto.STOCK.trim()}</td>
                <td><button type="button" class="btn btn-primary btn-sm btn-block btnSeleccionaProducto" data-codigo="${producto.CODIGO.trim()}"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span></button></td>
                
            </tr>
                `;

                $('#tblResultadosBusquedaProductos > tbody:last-child').append(row);
            cont++;

        });
    }

    function printBusquedaDocumentos(arrayDocumentos){
        $('#tblResultadosBusquedaDocumentos').find("tr:gt(0)").remove();
        let cont = 1;
        arrayDocumentos.forEach(documento => {
            let row = `
            <tr>
                <th scope="row">${cont}</th> 
                <td>${documento.TIPO}</td>
                <td>${documento.FECHA.trim()}</td>
                <td>${documento.CLIENTE.trim()}</td>
                <td>${documento.BODEGA.trim()}</td>
                <td>${documento.total.trim()}</td>
                <td>${documento.id.trim()}</td>
                <td>
                    <div class="btn-group">
                        <button class="btn btn-primary btn-sm btn-block dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Opciones <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="#" data-codigo="${documento.id.trim()}" class="btnModalGeneraPDF"> <span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> Generar PDF</a></li>
                            <li><a href="#" data-codigo="${documento.id.trim()}" class="btnModalSendEmail"> <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> Enviar por email (default)</a></li>
                            <li><a href="#" data-codigo="${documento.id.trim()}" class="btnModalSendCustomEmail"> <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> Enviar por email (personalizado)</a></li>
                            <li><a href="#" data-codigo="${documento.id.trim()}" class="btnModalLoadData"> <span class="glyphicon glyphicon-transfer" aria-hidden="true"></span> Cargar Documento</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
                `;

                $('#tblResultadosBusquedaDocumentos > tbody:last-child').append(row);
            cont++;

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

    function validaProducto(){

        let codProducto = $('#inputNuevoCodProducto').val();
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
                    newProducto = new Producto(producto.CODIGO, producto.NOMBRE, 1, producto.PRECIO, 0, producto.STOCK, producto.TIPOIVA || 0, parseFloat(producto.VALORIVA));
                    printDataProducto(newProducto);
                    console.log(newProducto);
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
    }

    function buscarClientes(terminoBusqueda, tipoBusqueda) {
        $("#loaderClientes").css("display", "block");
        $.ajax({
            type: 'get',
            url: 'views/modulos/ajax/API_cotizaciones.php?action=searchClientes',
            dataType: "json",
    
            data: { terminoBusqueda:terminoBusqueda, tipoBusqueda:tipoBusqueda },
            
            success: function(response) {
                console.log(response);
                let clientes = response.data;
                $("#loaderClientes").css("display", "none");
                printBusquedaClientes(clientes);
            }
        });

    }


    function buscarProductos(terminoBusqueda, tipoBusqueda) {
        $("#loaderProductos").css("display", "block");
       
        $.ajax({
            type: 'get',
            url: 'views/modulos/ajax/API_cotizaciones.php?action=searchProductos',
            dataType: "json",
    
            data: { terminoBusqueda:terminoBusqueda, tipoBusqueda:tipoBusqueda },
            
            success: function(response) {
                console.log(response);
                let productos = response.data;
                $("#loaderProductos").css("display", "none");
                printBusquedaProductos(productos);
                console.log(productos);
            }
        });

    }

    function buscarDocumentos(fechaINI, fechaFIN, stringBusqueda) {
        $("#loaderDocumentos").css("display", "block");
       
        $.ajax({
            type: 'get',
            url: 'views/modulos/ajax/API_cotizaciones.php?action=searchDocumentos',
            dataType: "json",
    
            data: { fechaINI:fechaINI, fechaFIN:fechaFIN, stringBusqueda: stringBusqueda },
            
            success: function(response) {
                console.log(response);
                let arrayDocumentos = response.data;
                $("#loaderDocumentos").css("display", "none");
                printBusquedaDocumentos(arrayDocumentos);
                //console.log(arrayDocumentos);
            }
        });

    }

    function printSubtotalNewProd (){
        $("#inputNuevoProductoSubtotal").val(newProducto.getSubtotal().toFixed(2));
    }
   
    function resumenProdutosInList() {
        
        return {
            sumaSubtotalproductos: cotizacion.getTotalProductos(),
            sumatotalproductosWithIVA: cotizacion.getTotalProductos() + cotizacion.getIVAProductos(),
            sumaTotalItems: cotizacion.sumarFromProductos("cantidad"),
            sumaIVABienes: cotizacion.getIVAProductos(),
            sumaDescuento: cotizacion.getDescuentoProductos()
        };
    }

    function printResumen(objectResumen){
        $("#txt_unidadesProd").val(objectResumen.sumaTotalItems);
        $("#welltotal").html('$ '+ objectResumen.sumatotalproductosWithIVA.toFixed(2));
        $("#txt_subtotal").val(objectResumen.sumaSubtotalproductos.toFixed(2));
        $("#txt_ivaBienes").val(objectResumen.sumaIVABienes.toFixed(2));
        $("#txt_impuesto").val(objectResumen.sumaIVABienes.toFixed(2));
        $("#txt_descuentoResumen").val(objectResumen.sumaDescuento.toFixed(2));
        $("#txt_totalPagar").val(objectResumen.sumatotalproductosWithIVA.toFixed(2));
    }
   
    function mySwal(mensajem, tipoAlerta = 'info') {
        Swal.fire({
            title: 'Atención',
            text: mensajem + ', desea inviar email con la cotizacion al cliente?',
            type: tipoAlerta,
            allowOutsideClick: false,
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
          }).then((result) => {
            if (result.value) {
                
                Swal.fire({
                    title: 'Procesando',
                    html: 'Enviando correo(s), espere por favor...',
                    onBeforeOpen: () => {
                      Swal.showLoading();
                      
                    }
                })

                $.ajax({
                    type: 'get',
                    url: 'views/modulos/ajax/API_cotizaciones.php?action=sendEmail',
                    dataType: "json",
                    data: { email: '', IDDocument: nuevoIDDocumentGenerated },
                    success: function (response) {
                        console.log(response);
                        Swal.fire({
                            type: 'info',
                            title: 'Envio de email',
                            text: response.data.mensaje,
                          }).then((result) => {

                            if (result.value) {
                                location.reload();
                            }
                          })
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        console.log(xhr);
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Se ha producido un error al enviar el email.'
                          }).then((result) => {
                            if (result.value) {
                                location.reload();
                            }
                          })
                    }
                });
    
               
                
            } else if (result.dismiss === Swal.DismissReason.cancel) {
              
              location.reload();
            }
          })
    }

    function showModalEmail(IDDocument){
        fetch(`./views/modulos/ajax/API_cotizaciones.php?action=getInfoVENCAB&IDDocument=${ IDDocument }`)
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            let infoIDDocument = data.data;
            $('#modalSendEmail').modal('show');
            $('#emailDestinatario').val(infoIDDocument.EMAIL);
            $('#emailIDDocument').val(IDDocument);
            //console.log(data);
                
        }).catch(function(err) {
            console.error(err);
        });  
    }

    function sendCustomEmailByDocument(IDDocument, emails, message){

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            animation : true,
            timer: 5000
            
          });

        fetch(`./views/modulos/ajax/API_cotizaciones.php?action=sendEmailByCustomEmail&email=${ emails }&IDDocument=${ IDDocument }&message=${ message }`)
            .then(function(response) {
                return response.json();
            })
            .then(function(response) {
                console.log(response);
                Toast.fire({
                    type: 'success',
                    title: response.data.mensaje
                    })

                    if (response.data.status == 'ok') {
                        alert('Enviado...');
                        location.reload();
                    }
            })
            .catch(function(err) {
                console.error(err);

            });
        
    }

    function sendEmailByDocument(IDDocument){

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            animation : true,
            timer: 5000
            
          });

        fetch(`./views/modulos/ajax/API_cotizaciones.php?action=getInfoVENCAB&IDDocument=${ IDDocument }`)
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                console.log(data.data);
                console.log(data.data.EMAIL);

                let emails = prompt("Indique los emails a los que enviar:", data.data.EMAIL);
                    if(emails==undefined) {
                        return;
                    }else if(emails==""){
                        alert("Se requiere al menos 1 email para el envio.");
                        return;
                    }else{
                        fetch(`./views/modulos/ajax/API_cotizaciones.php?action=sendEmailByCustomEmail&email=${ emails }&IDDocument=${ IDDocument }`)
                        .then(function(response) {
                            return response.json();
                        })
                        .then(function(response) {
                            console.log(response);
                            Toast.fire({
                                type: 'success',
                                title: response.data.mensaje
                              })

                        })
                        .catch(function(err) {
                            console.error(err);

                        });
                    }
            }).catch(function(err) {
                console.error(err);
            });
    }

    function loadDataByDocument(IDDocument) {
        if (confirm('Está seguro que desea cargar la informacion del documento: ' +IDDocument + '?, esto borrara la informacion ingresada actualmente.')) {
            
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                animation : true,
                timer: 5000
                
              });
    
            fetch(`./views/modulos/ajax/API_cotizaciones.php?action=getInfoVENCAB&IDDocument=${ IDDocument }`)
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    let VEN_CAB = data.data;
                    $("#inputRUC").val(VEN_CAB.RUC);
                    validaCliente();

                    // Carga de VEN_MOV
                    fetch(`./views/modulos/ajax/API_cotizaciones.php?action=getInfoVENMOV&IDDocument=${ IDDocument }`)
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function (data){
                        let VEN_MOV = data.data;
                        console.log(VEN_MOV);
                        cotizacion.productos = [];
                        VEN_MOV.forEach(producto => {
                            let loadproducto = new Producto(producto.CODIGO.trim(), producto.Nombre.trim(), parseInt(producto.CANTIDAD), parseFloat(producto.PRECIO), 0, 0, producto.tipoiva, parseInt(producto.IVA));
                            console.log(loadproducto);
                            cotizacion.productos.push(loadproducto);
                            //console.log(cotizacion.productos);
                            printProductos(cotizacion.productos);
                            let objectResumen = resumenProdutosInList();
                            printResumen(objectResumen);
                        });

                        console.log(cotizacion);
                    })
                   
                }).catch(function(err) {
                    console.error(err);
                });

        }
    }
    

});

/* FIN DOC Ready */

// Eventos Listener de los elementos
$("#formulario_registro").on("submit", function(event) {
    event.preventDefault();
    let form = $(this).serialize();
    //console.log(form);

});



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
