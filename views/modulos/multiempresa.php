<?php
if (!isset($_SESSION["usuarioRUC"])){
    header("Location:index.php?&action=login");  
 }   

$cotizacion = new \controllers\CotizacionController();
$bodegas = $cotizacion->getBodegas();
$vendedores = $cotizacion->getVendedores();

?>
 <!-- CSS Propios -->
 <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>assets\css\cotizacionStyles.css">

 <?php include 'sis_modules/header_main.php'?>

    <div class="container wrap">
        <!-- Row de cabecera-->
        <div class="row">
            <div class="col">
                <div class="form-group formextra col-lg-3">
                    <div class="input-group">
                            <span class="input-group-addon" id="basic-addon2">Tipo Doc</span>
                            <select class="form-control input-sm">
                                <option>Cotizacion</option>
                            </select>
                    </div>
                </div>

                <div class="form-group formextra centertext col-lg-6">
                   <?php echo $cotizacion->getStatusDataBase(); ?>
                </div>

                <div class="form-group formextra col-lg-3 pull-right hidden-sm hidden-xs">
                    <div class="input-group">
                        <input type="text" class="form-control input-sm" placeholder="Estado">
                        <span class="input-group-addon" id="basic-addon2">Estado</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row de Total a pagar-->
        <div class="row">
            
            <div class="col">
                <div class="form-group formextra col-lg-3 hidden-sm hidden-xs">
                    <span class="input-group-addon bordederecho">Buscar</span>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" data-toggle="modal" data-target="#modalBuscarDocumento">
                                <span class="glyphicon glyphicon-triangle-bottom" aria-hidden="true">
                                </span>
                            </button>
                        </span>
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span></button>
                        </span>
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-duplicate" aria-hidden="true"></span></button>
                        </span>
                        
                    </div>
                </div>

                <div class="form-group formextra col-lg-2 hidden-sm hidden-xs">
                    <span class="input-group-addon bordederecho">Numero</span>
                        <input type="text" class="form-control centertext" value="0" readonly>
                </div>

                <div class="form-group formextra col-lg-2">
                    <span class="input-group-addon bordederecho">Fecha Emision</span>
                        <input type="text" class="form-control centertext pickyDate" value="<?php echo date('Y-m-d');?>">
                </div>

                 <div class="form-group formextra col-lg-2">
                    <span class="input-group-addon bordederecho">Almacen</span>
                        <select class="form-control input-sm centertext" disabled>
                            <?php
                              foreach ($bodegas as $bodega => $row) {

                                $codigo = $row['CODIGO'];
                                $texto= $row['NOMBRE'];  

                                echo "<option value='$codigo'>$texto</option>";
                               }
                            
                            ?>
                        </select>
                   
                </div>
                   
                
                <div class="form-group formextra col-lg-3 col-md-12">
                   
                    <div class="well centertext wellextra" >
                        <span id="welltotal">$ 0.00</span>

                    </div>
                        
                   
                </div>
            </div>
        </div>
    
        <!-- Row datos-->
        <div class="row">

            <div class="col-lg-4 col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">Datos del Cliente</div>
                        <div class="panel-body">
                            
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Cliente</span>
                                <input type="text" class="form-control" id="inputRUC">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" data-toggle="modal" data-target="#modalBuscarCliente">
                                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                    </button>
                                </span>
                                
                                <span class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></span>
                                <input type="text" class="form-control" id="inputCodigo" readonly>
                                
                            </div>

                            <div class="input-group input-group-sm">
                                <span class="input-group-addon" id="sizing-addon3">Nombre</span>
                                <input type="text" class="form-control" placeholder="Nombre Cliente" id="inputNombre" readonly>
                            </div>

                            <div class="input-group input-group-sm">
                                <span class="input-group-addon" id="sizing-addon3">Razon Social</span>
                                <input type="text" class="form-control" placeholder="Razon Social" id="inputRSocial" readonly>
                            </div>

                            <div class="input-group input-group-sm">
                                <span class="input-group-addon" id="sizing-addon3">Correo</span>
                                <input type="mail" class="form-control" placeholder="Correo" id="inputCorreo" readonly>
                            </div>

                            <div class="input-group input-group-sm">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span> Telf.</span>
                                <input type="text" class="form-control centertext" placeholder="Telefono" id="inputTelefono" readonly>
                                <span class="input-group-addon">Dias Pago</span>
                                <input type="text" class="form-control" placeholder="DiasPago" id="inputDiasPago" readonly>
                            </div>

                            <div class="input-group input-group-sm">
                                <span class="input-group-addon" id="sizing-addon3">Vendedor</span>
                                <input type="text" class="form-control" placeholder="inputVendedor" id="inputVendedor" readonly>
                            
                            </div>

                        </div>
                </div>
            </div>


            <div class="col-lg-3 col-md-6  hidden-md hidden-sm hidden-xs">
                <div class="panel panel-default">
                    <div class="panel-heading">Datos de Cotizaciones</div>
                        <div class="panel-body">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Dias de Validez</span>
                                <input type="number" min="0" max="30" class="form-control centertext" value="0">
                            </div>

                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Estado por Cliente</span>
                                <select class="form-control input-sm">
                                <option>Sin estado</option>
                                </select>
                            </div>

                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Fecha Estado</span>
                                <input type="text" class="form-control centertext pickyDate">
                            </div>

                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Motivo Negación</span>
                                <select class="form-control input-sm">
                                <option></option>
                                </select>
                            </div>
   
                           
                        </div>
                    </div>
            </div>
            
            <div class="col-lg-2 col-md-6  hidden-md hidden-sm hidden-xs">
                <div class="panel panel-default">
                    <div class="panel-heading">Datos de Tributarios</div>
                        <div class="panel-body">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon" id="sizing-addon3">Bodega</span>
                                <input type="text" class="form-control" placeholder="Bodega">
                            </div>

                            <div class="input-group input-group-sm">
                                <span class="input-group-addon" id="sizing-addon3">Serie/Sec/Aut</span>
                                <input type="text" class="form-control" placeholder="Cupo">
                            </div>
                        </div>
                    </div>
            </div>

            <div class="col-lg-3 col-md-6 hidden-sm hidden-xs">
                <div class="panel panel-default">
                    <div class="panel-heading">Detalle</div>
                        <div class="panel-body">
                            <div class="form-group">
                                <textarea class="form-control" rows="5" id="comment" name="comment" maxlength="100" placeholder="Comentario de hasta maximo 100 caracteres..."></textarea>
                            </div>

                            <div class="input-group input-group-sm">
                                <span class="input-group-addon" id="sizing-addon3">Tipo Precio Cliente</span>
                                <input type="text" id="inputTipoPrecioCli" class="form-control" disabled>
                            </div>

                        </div>
                    </div>
                </div>

                
        </div>
        
        <!-- agregar productos-->
        
        <div class="row">
            <div class="col-md-12">
            <div class="panel panel-default">
                <!-- Default panel contents -->
            
                <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Nuevo Item</h4>
                <div class="btn-group pull-right">
                    <button type="button" class="btn btn-primary btn-sm" id="btnAgregarProdToList"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Agregar item</button>
                    <!-- 
                        <button type="button" class="btn btn-success btn-sm" id="btnShowUploadExcel" data-toggle="modal" data-target="#modalLoadExcel"><span class="glyphicon glyphicon-upload" aria-hidden="true"></span> Cargar EXCEL</button>
                     -->
                </div>
                </div>

                <div class="panel-body">
                    <div id="">        
                    <table id="tablaAgregaNuevo" class="table table-bordered tableExtras">
                        <thead>
                        <tr>
                            <th style="width: 15%" class="text-center headerTablaProducto">Codigo</th>
                            <th style="width: 25%" class="text-center headerTablaProducto">Nombre del Articulo</th>
                            <th style="width: 10%"  class="text-center headerTablaProducto">Cantidad</th>
                            <th style="width: 10%" class="text-center headerTablaProducto">Precio Proveedor</th>
                            <th style="width: 10%" class="text-center headerTablaProducto">Precio C. Final</th>
                            <th style="width: 10%" class="text-center headerTablaProducto">Stock Proveedor</th>
                            <th style="width: 10%" class="text-center headerTablaProducto">Marca</th>
                            <th style="width: 10%" class="text-center headerTablaProducto">% Margen</th>
                            <th style="width: 10%" class="text-center headerTablaProducto">Subtotal</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="input-group">
                                    <input type="text" id="inputNuevoCodProducto" class="form-control text-center" placeholder="Cod Producto...">
                                    <span class="input-group-btn">
                                        <button id="btnSeachProductos" class="btn btn-default" type="button" data-toggle="modal" data-target="#modalBuscarProducto">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        </button>
                                    </span>
                                    </div><!-- /input-group -->
                                </td>
                                <td>
                                   
                                    <div class="input-group">
                                    <input type="text" id="inputNuevoProductoNombre" class="form-control text-center" readonly>
                                    <span class="input-group-btn">
                                        <button id="btnSeachProductos" class="btn btn-default" type="button" data-toggle="modal" data-target="#modalAddExtraDetail">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                        </button>
                                    </span>
                                    </div><!-- /input-group -->    
                                </td>
                                <td><input type="number" id="inputNuevoProductoCantidad" class="form-control text-center" value="0"></td>
                                <td>
                                    <input type="text" id="inputNuevoProductoPrecioUnitario" class="form-control text-center" readonly>
                                   
                                </td>
                                <td>
                                    <input type="text" id="inputNuevoProductoPrecioClienteFinal" class="form-control text-center" readonly>
                                </td>

                                <td>
                                    <input type="text" id="inputStock" class="form-control text-center" readonly>
                                </td>

                                <td>
                                    <input type="text" id="inputMarca" class="form-control text-center" readonly>
                                </td>

                                <td>
                                    <input type="text" id="inputMargen" class="form-control text-center" readonly>
                                </td>
                                
                                <td><input type="text"  id="inputNuevoProductoSubtotal" class="form-control text-center" readonly></td>
                               
                                </td>
                            </tr>

                            
                               
                        </tbody>
                    </table>

                    </div>
                </div>

            </div>
            </div>
        </div>

        <!-- items en lista-->

        <div class="row">
            <div class="col-md-12">
            <div class="panel panel-default">
                <!-- Default panel contents -->
            
                <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Items en lista</h4>
                <div class="btn-group pull-right">
                </div>
                </div>

                <div class="panel-body">
                    <div id="responsibetable">        
                    <table id="tablaProductos" class="table table-bordered tableExtras">
                        <thead>
                        <tr>
                            <th style="width: 10%" class="text-center headerTablaProducto">Codigo</th>
                            <th style="width: 20%" class="text-center headerTablaProducto">Nombre del Articulo</th>
                            <th style="width: 3%"  class="text-center headerTablaProducto">Cantidad</th>
                            <th style="width: 5%" class="text-center headerTablaProducto">Precio</th>
                            <th style="width: 5%" class="text-center headerTablaProducto">Stock</th>
                            <th style="width: 10%" class="text-center headerTablaProducto">Subtotal</th>
                            <th style="width: 5%" class="text-center headerTablaProducto">IVA</th>
                            <th style="width: 5%" class="text-center headerTablaProducto">Eliminar</th>
                        </tr>
                        </thead>
                        <tbody>
                            <!--Resultados de busqueda aqui -->
                        </tbody>
                    </table>

                    </div>
                </div>

            </div>
            </div>
        </div>


            

        <!-- fila de resumen de pago-->
        <div class="row">
            <div class="col-md-12">
            <div class="panel panel-default">
                <!-- Default panel contents -->
               
                <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Resumen</h4>
                </div>

                <div class="panel-body">
                    <div id="responsibetable">        
                        <table class="table table-bordered tableExtras">
                        <thead>
                            <th style="width: 5%" class="text-center headerTablaProducto">Unidades</th>
                            <th style="width: 10%" class="text-center headerTablaProducto">IVA Bienes</th>
                            <th style="width: 5%" class="text-center headerTablaProducto">% ICE</th>
                            <th style="width: 10%" class="text-center headerTablaProducto">Base ICE</th>
                            <th style="width: 20%" class="text-center headerTablaProducto">Subtotal</th>
                            <th style="width: 10%" class="text-center headerTablaProducto">Descuento</th>
                            <th style="width: 5%" class="text-center headerTablaProducto">ICE</th>
                            <th style="width: 10%" class="text-center headerTablaProducto">Impuesto</th>
                            <th style="width: 10%" class="text-center headerTablaProducto">Gastos</th>
                            <th style="width: 20%" class="text-center headerTablaProducto">Total</th>
                        </thead>
                        <tbody>
                        <tr>
                            <td><input type="text" class="form-control text-center" id="txt_unidadesProd"></td>
                            <td><input type="text" class="form-control text-center" id="txt_ivaBienes" readonly></td>
                            <td><select class="form-control input-sm centertext"></select></td>
                            <td><input type="text" class="form-control text-center" readonly></td>
                            <td><input type="text" class="form-control text-center" id="txt_subtotal" value="0" readonly></td>
                            <td><input type="text" class="form-control text-center" id="txt_descuentoResumen" readonly></td>
                            <td><input type="text" class="form-control text-center" readonly></td>
                            <td><input type="text" class="form-control text-center" id="txt_impuesto" readonly></td>
                            <td><input type="text" class="form-control text-center" id="txt_gastos" readonly></td>
                            <td><input type="text" class="form-control text-center" id="txt_totalPagar" readonly></td>
                            
                        </tr>
                       
                        </tbody>
                        </table>

                    </div>
                </div>

            </div>
            </div>
        </div>    

 
        <div class="row extraButton">
            <div class="col-md-12">
                <div class="btn-group btn-group-justified" role="group" aria-label="...">
                

                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-primary btn-lg" id="btnGuardar"><span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span> Guardar</button>
                    </div>

                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-danger btn-lg" id="btnCancel"><span class="glyphicon glyphicon-floppy-remove" aria-hidden="true"></span> Cancelar</button>
                    </div>
               
                </div>
            </div>
        </div>    


        <!-- Modal Cliente -->
        <div class="modal fade" id="modalBuscarCliente" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Busqueda de Clientes</h4>
                </div>

                <div class="modal-body">
                    <div class="input-group select-group">
                        <input type="text" id="terminoBusquedaModalCliente" placeholder="Termino de busqueda..." class="form-control" value="%"/>
                        <select id="tipoBusquedaModalCliente" class="form-control input-group-addon">
                            <option value="NOMBRE">Nombre</option>
                            <option value="RUC">Cedula / RUC</option>
                        </select>
                        <div class="input-group-btn">
                            <button id="searchClienteModal" type="button" class="btn btn-primary" aria-label="Help">
                                <span class="glyphicon glyphicon-search"></span> Buscar
                            </button>
                        </div> 
                    </div>

                    <div class="panel panel-default"> 
                        <div class="panel-heading">Resultados</div> 
                            <table id="tblResultadosBusquedaClientes" class="table"> 
                                <thead>
                                    <tr> 
                                        <th>#</th> 
                                        <th>RUC</th> 
                                        <th>Cliente</th> 
                                        <th>Seleccionar</th> 
                                    </tr>
                                </thead> 
                                
                                <tbody>
                                    <!-- Los resultados de la busqueda se desplegaran aqui-->
                                    <div id="loaderClientes">
                                        <div class="loader" id="loader-4">
                                        <span></span>
                                        <span></span>
                                        <span></span>        
                                    </div>
                                </tbody>
                            </table>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
                </div>
            </div>
        </div>

        <!-- Modal Producto -->
        <div class="modal fade" id="modalBuscarProducto" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Busqueda de Producto</h4>
                </div>
                <div class="modal-body">
                    
                    <div class="input-group select-group">
                        <input type="text" id="terminoBusquedaModalProducto" placeholder="Termino de busqueda..." class="form-control" value="%"/>
                        <select id="tipoBusquedaModalProducto" class="form-control input-group-addon">
                            <option value="NOMBRE">NOMBRE</option>
                        </select>
                        <div class="input-group-btn">
                            <button id="searchProductoModal" type="button" class="btn btn-primary" aria-label="Help">
                                <span class="glyphicon glyphicon-search"></span> Buscar
                            </button>
                        </div> 
                    </div>

                    <div class="panel panel-default"> 
                        <div class="panel-heading">Resultados</div> 
                            <table id="tblResultadosBusquedaProductos" class="table"> 
                                <thead>
                                    <tr> 
                                        <th>#</th> 
                                        <th>Codigo</th> 
                                        <th>Nombre</th> 
                                        <th>Precio</th>
                                        <th>Stock</th> 
                                        <th>Accion</th> 
                                    </tr>
                                </thead> 
                                
                                <tbody>
                                    <!-- Los resultados de la busqueda se desplegaran aqui-->
                                    <div id="loaderProductos">
                                        <div class="loader" id="loader-4">
                                        <span></span>
                                        <span></span>
                                        <span></span>        
                                    </div>
                                </tbody>
                            </table>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
                </div>
            </div>
        </div>

        <!-- Modal Buscar Documento -->
        <div class="modal fade" id="modalBuscarDocumento" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"> Buscar Documento </h4>
                </div>
                <div class="modal-body">
                    
                    <div class="input-group input-daterange">
                        <input type="text" id="fechaINIDoc" class="form-control" value="<?php echo date('Y-m-01');?>">
                        <div class="input-group-addon">hasta</div>
                        <input type="text" id="fechaFINDoc" class="form-control" value="<?php echo date('Y-m-d');?>">
                    </div>

                    <div class="input-group select-group">
                        <input type="text" id="terminoBusquedaModalDocument" placeholder="Termino de busqueda..." class="form-control" value="%"/>
                        <select id="tipoBusquedaModalProducto" class="form-control input-group-addon">
                            <option value="">TODOS</option>
                        </select>
                        <div class="input-group-btn">
                            <button id="searchDocumentModal" type="button" class="btn btn-primary" aria-label="Help">
                                <span class="glyphicon glyphicon-search"></span> Buscar
                            </button>
                        </div> 
                    </div>

                    <div class="panel panel-default"> 
                        <div class="panel-heading">Resultados</div> 
                            <table id="tblResultadosBusquedaDocumentos" class="table"> 
                                <thead>
                                    <tr> 
                                        <th>#</th> 
                                        <th>Tipo</th>
                                        <th>Fecha</th>
                                        <th>Cliente</th>
                                        <th>Bodega</th>
                                        <th>Total</th>
                                        <th>ID Document.</th>
                                    </tr>
                                </thead> 
                                
                                <tbody>
                                    <!-- Los resultados de la busqueda se desplegaran aqui-->
                                    <div id="loaderDocumentos">
                                        <div class="loader" id="loader-4">
                                        <span></span>
                                        <span></span>
                                        <span></span>        
                                    </div>
                                </tbody>
                            </table>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
                </div>
            </div>
        </div>


        <!-- Modal Enviar Email Personalizado -->
        <div class="modal fade" id="modalSendEmail" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"> Envio de Correo </h4>
                </div>
                <div class="modal-body">
                    
                <form>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1">De:</span>
                        <input type="text" class="form-control" placeholder="de@email.com" aria-describedby="basic-addon1" value="<?php echo $_SESSION["usuarioNOMBRE"] ?>" disabled>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1">Destinatario: </span>
                        <input type="text" class="form-control" placeholder="destinataroi@email.com" id="emailDestinatario" aria-describedby="basic-addon1">
                    </div>

                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1">CC: </span>
                        <input type="text" class="form-control" placeholder="cc@email.com" aria-describedby="basic-addon1" value="<?php echo DEFAULT_EMAIL?>" disabled>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1">Documento: </span>
                        <input type="text" class="form-control" placeholder="#992018PRO000XXXXX" id='emailIDDocument' aria-describedby="basic-addon1" disabled>
                    </div>

                    </br>

                    <div class="form-group">
                        <label for="comment">Mensaje:</label>
                        <textarea class="form-control tiny" rows="5" id="mailContent"></textarea>
                    </div>

                </form>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btnSendCustomEmail">Enviar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal" onclick="location.reload();">Cerrar</button>
                </div>
                </div>
            </div>
        </div>

        <!-- Modal agregar fotos y detalles extra -->
        <div class="modal fade" id="modalAddExtraDetail" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"> Informacion extra del producto </h4>
                </div>
                <div class="modal-body">
                    
                <form method="post" id="fileinfo" name="fileinfo">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1">Imagenes:</span>
                        <input type="file" class="form-control" name="file" id="file" accept=".jpg,.png">
                    </div>
                    
                    <div class="form-group">
                        <label for="comment">Detalle:</label>
                        <textarea class="form-control tiny" rows="5" id="extraDetailContent"></textarea>
                    </div>

                </form>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
                </div>
            </div>
        </div>


        <!-- Modal agregar fotos y detalles extra -->
        <div class="modal fade" id="modalLoadExcel" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"> Carga de archivos </h4>
                </div>
                <div class="modal-body">
                    
                <form method="post" id="fileinfo" name="fileinfo">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1">Archivo de excel:</span>
                        <input type="file" class="form-control" name="xlfile" id="xlfile" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"/>
                    </div>
                    
                </form>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
                </div>
            </div>
        </div>

        

    </div>

    <!-- USO JQUERY, y Bootstrap CDN-->
    <script src="<?php echo ROOT_PATH; ?>assets\js\jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
   
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  
     <!-- JS Propio-->
    
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>assets\js\pnotify.custom.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/locales/bootstrap-datepicker.es.min.js"></script>
    <script src="https://cloud.tinymce.com/5/tinymce.min.js?apiKey=ubmvgme7f7n7likjbniglty12b9m92um98w9m75mdtnphwqp"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>assets\js\tinymce.js"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>assets\js\datepicker.js"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>assets\js\xlsx.full.min.js"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>assets\js\multiempresa.js"></script>
