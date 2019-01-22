<div class="col s12 m12 l12" name="row_productos[]">
    <h5>Producto: </h5>
    <div class="input-field col s12 m3 l2">
        <input type="text" class="center-align" name="txt_cod_product[]" value="-" readonly>
        
    </div>    

    <div class="input-field col s12 m6 l5">
        <input type="text" id="testinput1" class="autocomplete center-align uppercase rowproducto" name="txt_detalle_product[]" placeholder="Indique item" onchange="ajaxvalidacod_producto(this);calcular_total()" onfocus="autocompletadoOK()">
       
    </div>

    <div class="input-field col s12 m3 l2">
        <input type="number" class="center-align rowcantidad" name="txt_cant_product[]" value="0" onclick="extra_prod(this);calcular_total()" onkeyup="extra_prod(this);calcular_total()" min="0" max="99"  required>
       
    </div>

    <div class="input-field col s12 m12 l2">
        <input type="text" class="center-align importe_linea" name="txt_precio_product[]" value="0" onkeyup="calcular_total()">
        <input type="hidden" name="hidden_precio_product[]">
    </div>

    <div class="input-field col s12 m6 l1 center-align">
        <a class="btn-floating waves-effect waves-light red removeprod_ico" onclick="remove_extra_prod(this)"><i class="material-icons">delete_forever</i></a>
    </div>
</div>   