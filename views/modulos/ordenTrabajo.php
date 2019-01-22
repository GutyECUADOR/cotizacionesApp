<?php 
    if (!isset($_SESSION["usuarioActivo"])){
           header("Location:index.php?&action=inicio");  
        }  
        
    $ordentrabajo = new models\OrdentClass();
    
?>

<div class="container">
    <!-- Card de registro de productos -->
    <div class="row">
          <div class="col s12 m12">
            <div class="card z-depth-2">
              <div class="card-content">
                <span class="card-title center-align"><h5>Nueva Orden de Trabajo</h5></span>
                    <form autocomplete="off">
                        <div class="row">
                            <div class="col s12 m12 l12">
                                <h5>Información Principal</h5>
                                <div class="input-field col s12 m6 l6">
                                <select>
                                    <option value="" disabled selected>Seleccione por favor</option>
                                    <?PHP
                                        $ordentrabajo->getAsesores();
                                    ?>
                                </select>
                                <label>Indique un asesor:</label>
                                </div>

                                <div class="input-field col s12 m6 l6">
                                <select class="centrado" name="seleccion_mecanico" id="seleccion_mecanico" required>
                                    <option value="" disabled selected>Seleccione por favor</option>
                                    <?PHP
                                        $ordentrabajo->getMecanicos();
                                    ?>
                                </select>
                                <label>Indique un mecánico:</label>
                                </div>
                            </div>    


                            <div class="col">
                                <h5>Información del Propietario</h5>
                                <!-- Fila 1-->

                                <div class="input-field col s12 m6 l6">
                                    <input class="mayusculas" name="txt_ci_ruc" id="txt_ci_ruc" type="text" data-length="13" onkeyup="ajax_jsonDatosCliente();ajax_getVehiculos()"required  autofocus>
                                    <label for="txt_ci_ruc" data-error="No cumple" >RUC</label>
                                </div>

                                <div class="input-field col s12 m6 l6">
                                    <input id="txt_cliente" type="text" data-length="13" value="(Sin identificar)" disabled>
                                    <label for="txt_cliente" data-error="No cumple" >Cliente Identificado</label>
                                </div>

                                <!-- Fila 2-->

                                <div class="input-field col s12 m6 l4">
                                    <input type="text" name="txt_direccion" id="txt_direccion" value="(Sin identificar)" disabled>
                                    <label for="txt_direccion" data-error="No cumple" >Dirección</label>
                                </div>

                                <div class="input-field col s12 m6 l4">
                                    <input type="text" name="txt_telefono" id="txt_telefono" value="(Sin identificar)" disabled>
                                    <label for="txt_telefono" data-error="No cumple" >Teléfono</label>
                                </div>

                                <div class="input-field col s12 m6 l4">
                                    <input type="text" name="txt_correo" id="txt_correo" value="(Sin identificar)" disabled>
                                    <label for="txt_correo" data-error="No cumple" >E-mail</label>
                                </div>

                                <!-- Fila 3-->

                                <div class="input-field col s12 m6 l9">
                                     <select class="center-align browser-default" name="seleccion_vehiculo" id="seleccion_vehiculo" required>
                                      <option value="" >Seleccione vehiculo</option>
                                    </select>
                                   
                                </div>

                                <div class="input-field col s12 m12 l3">
                                    <input type="number" name="txt_kilometraje" id="txt_kilometraje">
                                    <label for="txt_kilometraje" data-error="No cumple" >Kilometraje</label>
                                </div>
                            </div>

                            <div class="col">
                                <h5>Extras del Vehiculo</h5>

                                <div class="range-field col-lg-12">
                                    <label for="range_combustible" class="text-center">Combustible</label>
                                    <input type="range" name="range_combustible" id="range_combustible" min="0" max="100" />
                                </div>

                                <div class="col s12 m6 l4">
                                    <input type="checkbox" class="filled-in" name="chk_radio" id="chk_radio" value="1"/>
                                    <label for="chk_radio">Radio</label>
                                </div>
                                <div class="col s12 m6 l4">
                                    <input type="checkbox" class="filled-in" name="chk_encendedor" id="chk_encendedor" value="1"/>
                                    <label for="chk_encendedor">Encendedor</label>
                                </div>
                                <div class="col s12 m6 l4">
                                    <input type="checkbox" class="filled-in" name="chk_controlAlarma" id="chk_controlAlarma" value="1"/>
                                    <label for="chk_controlAlarma">Control Alarma</label>
                                </div>


                                <div class="col s12 m6 l4">
                                    <input type="checkbox" class="filled-in" name="chk_antena" id="chk_antena" value="1"/>
                                    <label for="chk_antena">Antena</label>
                                </div>
                                <div class="col s12 m6 l4">
                                    <input type="checkbox" class="filled-in" name="chk_tuercaSeguridad" id="chk_tuercaSeguridad" value="1"/>
                                    <label for="chk_tuercaSeguridad">Tuerca de Seguridad</label>
                                </div>
                                <div class="col s12 m6 l4">
                                    <input type="checkbox" class="filled-in" name="chk_tapaGasolina" id="chk_tapaGasolina" value="1"/>
                                    <label for="chk_tapaGasolina">Tapa de Gasolina</label>
                                </div>

                                <div class="col s12 m6 l4">
                                    <input type="checkbox" class="filled-in" name="chk_llantaRepuesto" id="chk_llantaRepuesto" value="1"/>
                                    <label for="chk_llantaRepuesto">Llanta Repuesto</label>
                                </div>
                                <div class="col s12 m6 l4">
                                    <input type="checkbox" class="filled-in" name="chk_gata" id="chk_gata" value="1"/>
                                    <label for="chk_gata">Gata</label>
                                </div>
                                <div class="col s12 m6 l4">
                                    <input type="checkbox" class="filled-in" name="chk_llaveRuedas" id="chk_llaveRuedas" value="1"/>
                                    <label for="chk_llaveRuedas">Llave de Ruedas</label>
                                </div>
                                
                            </div>     
                            
                            <div class="col s12 m12 l12">
                                <h5>Evidencia de Estado</h5>
                                <div class="file-field input-field">
                                    <div class="btn">
                                      <span>Fotografia</span>
                                      <input type="file" name="input_imagenes[]" accept="image/*" multiple>
                                    </div>
                                    <div class="file-path-wrapper">
                                      <input class="file-path validate" type="text" placeholder="Maximo 10Mb">
                                    </div>
                                 </div>
                            </div>
                            
                            <div class="col s12 m12 l12">
                                <h5>Neumaticos</h5>
                                
                                <div class="input-field col s12 m6 l2">
                                    <input type="text" class="center-align" name="txt_codLlantas[]" id="txt_codLlantas" value="-" readonly>
                                    <label for="txt_codLlantas" data-error="No cumple" >Código</label>
                                </div>
                                
                                <div class="input-field col s12 m6 l5">
                                    <input type="text" name="txt_llantas[]" id="txt_llantas" placeholder="Indique item" class="center-align uppercase">
                                    <label for="txt_llantas" data-error="No cumple" >Neumaticos</label>
                                </div>

                                <div class="input-field col s12 m6 l2">
                                    <input type="number" name="txt_cantidadLlantas[]" id="txt_cantidadLlantas" class="center-align" value="0">
                                    <label for="txt_cantidadLlantas" data-error="No cumple">Cantidad</label>
                                </div>

                                <div class="input-field col s12 m6 l2">
                                    <input type="text" class="importe_linea center-align" name="txt_valorLlantas[]" id="txt_valorLlantas" value="0" onkeyup="calcular_total()">
                                    <label for="txt_valorLlantas" data-error="No cumple">Valor</label>
                                </div>
                                
                                <div class="input-field col s12 m12 l1 center-align">
                                    <a class="btn-floating waves-effect waves-light red"><i class="material-icons">delete_forever</i></a>
                                </div>
                                
                            </div>  
                            
                            
                            <div class="col s12 m12 l12">
                                <h5>Producto: </h5>
                                <div class="input-field col s12 m3 l2">
                                    <input type="text" class="center-align" name="txt_cod_product[]" value="-" readonly>
                                    <label for="txt_cod_product" class="label">Código</label>
                                </div>    
                                       
                                <div class="input-field col s12 m6 l5">
                                    <input type="text" id="testinput1" class="autocomplete center-align uppercase rowproducto" name="txt_detalle_product[]" placeholder="Indique item" onchange="ajaxvalidacod_producto(this);calcular_total()">
                                    <label for="txt_detalle_product" class="label">Producto</label>
                                </div>

                                <div class="input-field col s12 m3 l2">
                                    <input type="number" class="center-align rowcantidad" name="txt_cant_product[]" value="0" onclick="extra_prod(this);calcular_total()" onkeyup="extra_prod(this);calcular_total()" min="0" max="99"  required>
                                    <label class="label">Cantidad</label>
                                </div>

                                <div class="input-field col s12 m12 l2">
                                    <label class="label">Precio</label>
                                    <input type="text" class="center-align importe_linea" name="txt_precio_product[]" value="0" onkeyup="calcular_total()">
                                    <input type="hidden" name="hidden_precio_product[]">
                                </div>
                                
                            </div>    
                            
                            <!-- Contenedor de Controles ajax-->
                                
                            <div class="result_add"> 
                            </div>
                            
                            
                            <div class="col">
                                <h5>Valores a cancelar</h5>
                                
                                <div class="input-field col s12 l4 offset-l7">
                                    <label class="label">Subtotal</label>
                                    <input type="text" class="center-align" id="txt_subtotal" name="txt_subtotal" value="0" onkeyup="calcular_total()" readonly>
                                </div>
                                
                                <div class="input-field col s12 l4 offset-l7">
                                    <label class="label">IVA</label>
                                    <input type="text" class="center-align" id="txt_iva" name="txt_iva" value="0" onkeyup="calcular_total()" readonly>
                                </div>
                                
                                <div class="input-field col s12 l4 offset-l7">
                                    <label class="label">Total</label>
                                    <input type="text" class="center-align" id="txt_total" name="txt_total" value="0" onkeyup="calcular_total()" readonly>
                                </div>
                                
                                 <div class="input-field col s12 l4 offset-l7">
                                    <label class="label">Descuento</label>
                                    <input type="number" class="center-align subtotales" id="txt_descuento"  name="txt_descuento" min="0" max="100" value="0" onclick="calcular_total()" onchange="calcular_total()" onkeyup="calcular_total()">
                                </div>
                                
                                <div class="input-field col s12 l4 offset-l7">
                                    <label class="label">A pagar</label>
                                    <input type="text" class="center-align subtotales" id="txt_apagar"  name="txt_apagar" value="0" readonly required="true">
                                </div>
                            </div>    
                            
                            <div class="input-field col s12 m12 center-align">
                                <button class="btn waves-effect waves-light" type="button" name="action">
                                   Registrar
                                </button>
                            </div>
                            
                        </div><!-- End row -->
                        
                            

                    </form>
              </div>
            </div>
          </div>
        </div><!-- End of Sign Up Card row -->
        
     <!-- Modal Generar informe -->
        <div class="modal fade" id="Modal_Registrar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
              
              <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h5 class="modal-title" id="myModalLabel">Registrar Nuevo</h5>
              </div>
                  
                   <div class="modal-body">
                        <div class="tabbable"> <!-- Only required for left/right tabs -->
                        <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab1" data-toggle="tab">Nuevo Cliente</a></li>
                        <li><a href="#tab2" data-toggle="tab">Nuevo Vehiculo</a></li>
                        <li><a href="#tab3" data-toggle="tab">Extras</a></li>
                        </ul>
                        <div class="tab-content">
                        <div class="tab-pane active" id="tab1">
                            
                            <div class="row">
                                <!-- Resultados de AJAX-->
                                <div class="resultmodal" style="display:none;">
                                    <p>Resultados</p>
                                </div>
                                
                                
                                <form method="GET" id="registrar_ClienteModal" name="registrar_ClienteModal" target="_blank" class="form-inline">
                                <div class="col-lg-12">
                                    <div class="form-group col-lg-12">
                                        <br>
                                        <input type="text" class="form-control centertext uppercase" id="ruc_modal" maxlength="13" placeholder="Cédula o RUC" required><br>
                                        <br>
                                        <input type="text" class="form-control centertext uppercase" id="clientename_modal" maxlength="40" placeholder="Nombres y Apellidos" required><br>
                                        <br>
                                        <input type="text" class="form-control centertext uppercase" id="direccion_modal" maxlength="40" placeholder="Dirección" required><br>
                                        <br>
                                        <input type="text" class="form-control centertext uppercase" id="telefono_modal" maxlength="40" placeholder="Teléfono" required><br>
                                        <br>
                                        <input type="email" class="form-control centertext" id="correo_modal" maxlength="40" placeholder="Correo" required><br>
                                        <br>
                                        <div class="row rowspace">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info" onclick="registrarCliente()"><span class="glyphicon glyphicon-thumbs-up"></span> Registrar Cliente</button>
                                                <button type="button" class="btn btn-default" onclick="resetForm('registrar_ClienteModal')"><span class="glyphicon glyphicon-new-window"></span> Nuevo</button>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                </form>    
                            </div>
                        
                  
                        
                       
                         
                        </div>
                            
                         <!-- FIN Sección - INICIO Segundo Label -->    
                            
                        <div class="tab-pane" id="tab2">
                              <div class="row">
                                <!-- Resultados de AJAX-->
                                <div class="resultmodal_tab2" style="display:none;">
                                    <p>Resultados</p>
                                </div>
                                
                                
                                <form method="GET" id="registrar_VehiculoModal" name="registrar_VehiculoModal" target="_blank" class="form-inline">
                                <div class="col-lg-12">
                                    <div class="form-group col-lg-12">
                                        
                                        <br>
                                        <select class="form-control centertext uppercase" name="seleccion_empleado_modal_obs" id="seleccion_cliente_modal" required>
                                            <option value="">--- Seleccione Cliente ---</option>
                                            <?php $ordentrabajo->getClientes(); ?>
                                        </select><br>
                                        <br>
                                        <select class="form-control centertext uppercase" name="seleccion_marcaauto_modal" id="seleccion_marcaauto_modal" onchange="ajax_getModelos()" required>
                                            <option value="">--- Seleccione Marca ---</option>
                                             <?php $ordentrabajo->getMarcasAutos(); ?>
                                        </select>
                                        <br>
                                        <br>
                                        <select class="form-control centertext uppercase" name="seleccion_modeloauto_modal" id="seleccion_modeloauto_modal" required>
                                            <option value="">--- Seleccione Modelo ---</option>
                                        </select>
                                        <br>
                                        <br>
                                        <input type="text" class="form-control centertext uppercase" id="placas_modal" maxlength="8" placeholder="Placas" onblur="replacePLACA(this.value)" required><br>
                                        <br>
                                        <input type="text" class="form-control centertext uppercase" id="anio_modal" maxlength="4" placeholder="Año" required><br>
                                        <br>
                                        <input type="text" class="form-control centertext uppercase" id="color_modal" maxlength="15" placeholder="Color" required><br>
                                        <br>
                                        <div class="row rowspace">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info" onclick="registrarAutoCliente()()"><span class="glyphicon glyphicon-thumbs-up"></span> Registrar Vehiculo</button>
                                                <button type="button" class="btn btn-default" onclick="resetForm('registrar_VehiculoModal')"><span class="glyphicon glyphicon-new-window"></span> Nuevo</button>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                </form>    
                            </div>
                            
                            
                        </div>
                         
                         <!-- FIN Sección - INICIO Tercer Label -->    
                            
                        <div class="tab-pane" id="tab3">
                              
                            <form action="" method="GET" target="_blank" class="form-inline">
                            <div class="row">
                            <div class="rowspace">
                                <select class="form-control centertext" name="seleccion_empleado_modal_obs" id="seleccion_empleado_modal_obs" required>
                                  
                                </select>
                            </div>
                            </div>
                               
                         <div class="row rowspace">
                            <button type="submit" class="btn btn-info"><span class="glyphicon glyphicon-adjust"></span> Asignar</button>
                        </div>
                        </form> 
                            
                            
                        </div> 
                         
                        </div>
                        </div>
                   </div>
                  
              
              <div class="modal-footer">
                
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                
                
              </div>
              
            </div>
            
          </div>
        </div>
                
  
     <!-- Modal Structure -->
    <div id="modal1" class="modal">
      <div class="modal-content">
        <h4>Nuevo Cliente</h4>
        <form method="GET" id="registrar_ClienteModal" name="registrar_ClienteModal" target="_blank" class="form-inline">
                                <div class="col-lg-12">
                                    <div class="form-group col-lg-12">
                                        <br>
                                        <input type="text" class="form-control centertext uppercase" id="ruc_modal" maxlength="13" placeholder="Cédula o RUC" required><br>
                                        <br>
                                        <input type="text" class="form-control centertext uppercase" id="clientename_modal" maxlength="40" placeholder="Nombres y Apellidos" required><br>
                                        <br>
                                        <input type="text" class="form-control centertext uppercase" id="direccion_modal" maxlength="40" placeholder="Dirección" required><br>
                                        <br>
                                        <input type="text" class="form-control centertext uppercase" id="telefono_modal" maxlength="40" placeholder="Teléfono" required><br>
                                        <br>
                                        <input type="email" class="form-control centertext" id="correo_modal" maxlength="40" placeholder="Correo" required><br>
                                        <br>
                                        <div class="row rowspace">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info" onclick="registrarCliente()"><span class="glyphicon glyphicon-thumbs-up"></span> Registrar Cliente</button>
                                                <button type="button" class="btn btn-default" onclick="resetForm('registrar_ClienteModal')"><span class="glyphicon glyphicon-new-window"></span> Nuevo</button>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
        </form>
      </div>
      <div class="modal-footer">
        <a href="#!" class="btn modal-action modal-close">Cerrar</a>
      </div>
    </div>
     
    <!-- Floating Button Google-->
    <div class="fixed-action-btn">
        <a class="btn-floating btn-large red">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating teal waves-effect waves-light modal-trigger" href="#modal1" title="Registrar nuevo"><i class="material-icons">note_add</i></a></li>
            <li><a class="btn-floating green" id="btn_add_producto" onclick="add_row()" title="Agregar Producto"><i class="material-icons">playlist_add</i></a></li>
            <li><a class="btn-floating green" id="btn_add_producto" onclick="" title="Test Function"><i class="material-icons">playlist_add</i></a></li>
            
        </ul>
    </div>
        
</div>    
        