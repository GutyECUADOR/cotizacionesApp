<?php
$cotizacion = new \controllers\CotizacionController();
$bodegas = $cotizacion->getBodegas();
$vendedores = $cotizacion->getVendedores();

?>
 <!-- CSS Propios -->
 <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>assets\css\cotizacionStyles.css">

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
                            <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-triangle-bottom" aria-hidden="true"></span></button>
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
                        <select class="form-control input-sm centertext">
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
                                    <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
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
                                <span class="input-group-addon"><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span> Telf.</span>
                                <input type="text" class="form-control centertext" placeholder="Telefono" id="inputTelefono" readonly>
                                <span class="input-group-addon">Cupo</span>
                                <input type="text" class="form-control" placeholder="Cupo" id="inputCupo" readonly>
                            </div>

                           
                                

                            <div class="input-group input-group-sm">
                                <span class="input-group-addon" id="sizing-addon3">Vendedor</span>
                                <select class="form-control input-sm">
                                    <?php
                                        foreach ($vendedores as $vendedor => $row) {

                                            $codigo = $row['CODIGO'];
                                            $texto= $row['NOMBRE'];  
            
                                            echo "<option value='$codigo'>$texto</option>";
                                           }
                                        
                                        ?>
                                    ?>
                                </select>
                            </div>

                        </div>
                </div>
            </div>


            <div class="col-lg-3 col-md-6 hidden-sm hidden-xs">
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

                           
                                <div class="input-group">
                                <span class="input-group-addon">
                                    <input type="checkbox">
                                </span>
                                <span class="input-group-addon">Documento requiere anticipo</span>
                                </div><!-- /input-group -->
                           
                        </div>
                    </div>
            </div>
            
            <div class="col-lg-2 col-md-6 hidden-sm hidden-xs">
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
                                <textarea class="form-control" rows="5" id="comment"></textarea>
                            </div>

                            <div class="input-group input-group-sm">
                                <span class="input-group-addon" id="sizing-addon3">Desc. en lineas x default</span>
                                <input type="text" class="form-control">
                            </div>

                        </div>
                    </div>
                </div>

                
        </div>
        
        <!-- fila de agregar productos-->
        
        <div class="row">
            <div class="col-md-12">
            <div class="panel panel-default">
                <!-- Default panel contents -->
            
                <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Articulos</h4>
                <div class="btn-group pull-right">
                    <button type="button" class="btn btn-primary btn-sm" id="btnAgregaFilaProducto"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Agregar Fila</button>
                </div>
                </div>

                <div class="panel-body">
                    <div id="responsibetable">        
                    <table id="tablaProductos" class="table table-bordered tableExtras">
                        <thead>
                        <tr>
                            <th style="width: 10%" class="text-center headerTablaProducto">Codigo</th>
                            <th style="width: 25%" class="text-center headerTablaProducto">Nombre del Articulo</th>
                            <th style="width: 5%"  class="text-center headerTablaProducto">Cantidad</th>
                            <th style="width: 10%" class="text-center headerTablaProducto">Precio</th>
                            <th style="width: 5%" class="text-center headerTablaProducto">Descuento</th>
                            <th style="width: 10%" class="text-center headerTablaProducto">Subtotal</th>
                            <th style="width: 5%" class="text-center headerTablaProducto">IVA</th>
                            <th style="width: 5%" class="text-center headerTablaProducto">Eliminar</th>
                        </tr>
                        </thead>
                        <tbody>
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
                        <tr>
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
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><input type="text" class="form-control text-center" id="txt_unidadesProd"></td>
                            <td><input type="text" class="form-control text-center" id="txt_ivaBienes" readonly></td>
                            <td><select class="form-control input-sm centertext"></select></td>
                            <td><input type="text" class="form-control text-center" readonly></td>
                            <td><input type="text" class="form-control text-center" id="txt_subtotal" value="0" readonly></td>
                            <td><input type="text" class="form-control text-center" readonly></td>
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
                    
                    <button type="button" class="btn btn-primary btn-lg" id="btnGuardar"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Guardar</button>

                    
                </div>
               
                </div>
            </div>
        </div>    
    </div>