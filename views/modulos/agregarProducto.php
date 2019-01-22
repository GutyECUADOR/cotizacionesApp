<?php


?>


<div class="container">
    <!-- Card de registro de productos -->
    <div class="row">
          <div class="col s12 m12">
            <div class="card">
              <div class="card-content">
                <span class="card-title center-align"><h5>Registro de Productos</h5></span>
                    <form class="container" autocomplete="off">
                        <div class="row">

                            <div class="input-field col s12 m6 l4">
                                <input class="mayusculas" id="txt_codReferencial" type="text" data-length="6"  required  autofocus>
                                <label for="txt_codReferencial" data-error="No cumple" >Código Referencial</label>
                            </div>
                            <div class="input-field col s12 m6 l8">
                                <input id="txt_nombreProducto" type="text" data-length="25">
                                <label for="txt_nombreProducto" data-error="No cumple">Nombre del Producto (25 caracteres)</label>
                            </div>
                            
                            <div class="input-field col s12 m6 l4">
                                <input id="txt_cantProducto" type="number">
                                <label for="txt_cantProducto" data-error="No cumple">Cantidad</label>
                            </div>
                            
                            <div class="input-field col s12 m6 l4">
                                <input id="txt_valorProducto" type="number">
                                <label for="txt_valorProducto" data-error="No cumple">Valor</label>
                            </div>
                            
                            <div class="input-field col s12 m6 l4">
                                <input id="txt_fechaIngresoProd" type="text" class="datepicker">
                                <label for="txt_fechaIngresoProd" data-error="No cumple">Fecha de Ingreso</label>
                            </div>
                            
                            <div class="input-field col s12 m12 center-align">
                                <button class="btn waves-effect waves-light" type="button" name="action">
                                   Registrar <i class="material-icons right">send</i>
                                </button>
                            </div>

                        </div>
                    </form>
              </div>
            </div>
          </div>
        </div><!-- End of Sign Up Card row -->
</div>    
        
           
          
          
        