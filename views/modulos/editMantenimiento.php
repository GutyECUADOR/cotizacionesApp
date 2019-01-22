<?php
    if (!isset($_SESSION["usuarioRUC"])){
           header("Location:index.php?&action=login");  
    }
    
    if (isset($_GET["codOrden"])){
          $codMantenimiento = $_GET["codOrden"];  
    }else{
        header("Location:index.php?&action=inicio"); 
    }
        
    /* CREACION DE INSTANCIA USADA PARA ESTA VISTA*/  
    
    $codEmpresa = trim($_SESSION["empresaAUTH"]);  // Nombre de la db asiganda en el login
    $mantenimiento = new models\MantenimientosClass();
    $ajaxController = new controllers\ajaxController();

    $arrayMantenimiento = $mantenimiento->getMantenimientoByCod($codEmpresa, $codMantenimiento); //Devuelve array de mantenimientos
    //$arrayMantenimiento = $mantenimiento->getRepuestosOfMantenimientoByCod($codEmpresa, $codMantenimiento);
    $arrayRepuestos = $mantenimiento->getRepuestosOfMantenimientoByCod($codMantenimiento, $codEmpresa);
 
    $dateNow = $mantenimiento->getDateNow(); //Fecha actual determina si la tarjeta esta valida o no
    $arrayTecnicos = $ajaxController->getAllTecnicos();
    $arrayBodegas = $ajaxController->getAllBodegas();

    if (empty($arrayMantenimiento )){
        header("Location:index.php?&action=inicio"); 
    }

    if (trim($arrayMantenimiento["estado"]) != 0) {
        header("Location:index.php?&action=inicio"); 
    }

    
?>

<body class="disable_transitions sidebar_main_open sidebar_main_swipe">
    <!-- header main -->
      <?php include 'sis_modules/header_main.php'?>
    <!-- header main end -->

    <!-- main sidebar -->
      <?php include 'sis_modules/sidebar_main.php'?>
    <!-- main sidebar end -->

    <!-- CONTENIDO DE LA SECCION -->    
    <div id="page_content">
        <div id="page_heading" data-uk-sticky="{ top: 48, media: 960 }">
            <h1 id="product_edit_name"><?php echo $arrayMantenimiento["Producto"]?></h1>
            <span class="uk-text-muted uk-text-upper uk-text-small" id="product_edit_sn">
                <strong id="codMantenimiento"><?php echo $arrayMantenimiento["codMantenimiento"]?></strong>
                , técnico asignado <strong><?php echo $arrayMantenimiento["Encargado"]?></strong>, orden fisica <strong><?php echo $arrayMantenimiento["codOrdenFisica"]?></strong>
            </span>
        </div>
        <div id="page_content_inner">
            <form action="" class="uk-form-stacked" id="product_edit_form">
                <div class="uk-grid uk-grid-medium" data-uk-grid-margin>
                    <div class="uk-width-xLarge-2-10 uk-width-large-3-10">
                        
                        <div class="md-card">
                            <div class="md-card-toolbar">
                                <div class="md-card-toolbar-actions">
                                    <i class="md-icon material-icons">&#xE146;</i>
                                </div>
                                <h3 class="md-card-toolbar-heading-text">
                                    Imagen
                                </h3>
                            </div>
                            <div class="md-card-content">
                                <div class="uk-margin-bottom uk-text-center uk-position-relative">
                                    <button type="button" class="uk-modal-close uk-close uk-close-alt uk-position-absolute"></button>
                                    <img src="assets/img/ecommerce/s6_edge.jpg" alt="" class="img_medium"/>
                                </div>
                               
                            </div>
                        </div>

                        <!-- <div class="md-card">
                            <div class="md-card-toolbar">
                                <h3 class="md-card-toolbar-heading-text">
                                    Información del producto
                                </h3>
                            </div>
                            <div class="md-card-content">
                                <div class="uk-form-row">
                                    <div class="uk-input-group">
                                        <span class="uk-input-group-addon">
                                            <i class="uk-icon-usd"></i>
                                        </span>
                                        <label for="product_edit_price_control">Precio</label>
                                        <input type="text" class="md-input" name="product_edit_price_control" id="product_edit_price_control" value="0" />
                                    </div>
                                </div>
                                <div class="uk-form-row">
                                    <div class="uk-input-group">
                                        <span class="uk-input-group-addon">%</span>
                                        <label for="product_edit_tax_control">Impuesto</label>
                                        <input type="text" class="md-input" name="product_edit_tax_control" id="product_edit_tax_control" value="0" />
                                    </div>
                                </div>
                                <div class="uk-form-row">
                                    <div class="uk-input-group">
                                        <span class="uk-input-group-addon">
                                            <i class="uk-icon-cubes"></i>
                                        </span>
                                        <label for="product_edit_quantity_control">Cantidad</label>
                                        <input type="text" class="md-input" name="product_edit_quantity_control" id="product_edit_quantity_control" value="0" />
                                    </div>
                                </div>
                                <div class="uk-form-row">
                                    <div class="uk-input-group">
                                        <span class="uk-input-group-addon">
                                            <i class="uk-icon-barcode"></i>
                                        </span>
                                        <label for="product_edit_sku_control">Serie</label>
                                        <input type="text" class="md-input" name="product_edit_sku_control" id="product_edit_sku_control" value="0" />
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </div>
                    <div class="uk-width-xLarge-8-10  uk-width-large-7-10">
                        <div class="md-card">
                            <div class="md-card-toolbar">
                                <h3 class="md-card-toolbar-heading-text">
                                    Detalle
                                </h3>
                            </div>
                            <div class="md-card-content large-padding">
                                <div class="uk-grid uk-grid-divider uk-grid-medium" data-uk-grid-margin>
                                    <div class="uk-width-large-1-2">
                                        <div class="uk-form-row">
                                            <input type="hidden" class="md-input" id="codMantenimiento" name="codMantenimiento" value="<?php echo trim($arrayMantenimiento["codMantenimiento"])?>" readonly/>
                                            <input type="hidden" class="md-input" id="codCliente" name="codCliente" value="<?php echo trim($arrayMantenimiento["CodCliente"])?>" readonly/>
                                        </div>
                                   
                                        <div class="uk-form-row">
                                            <label for="product_edit_name_control">Cliente</label>
                                            <input type="text" class="md-input" id="product_cliente_name" name="product_cliente_name" value="<?php echo trim($arrayMantenimiento["NombreCliente"])?>" readonly/>
                                        </div>
                                        <div class="uk-form-row">
                                            <label for="product_edit_manufacturer_control">Teléfono</label>
                                            <input type="text" class="md-input" id="product_cliente_telf" name="product_cliente_telf" value="<?php echo trim($arrayMantenimiento["Telefono"])?>" readonly/>
                                        </div>
                                        <div class="uk-form-row">
                                            <label for="product_edit_sn_control">Dirección</label>
                                            <input type="text" class="md-input" id="product_cliente_direccion" name="product_cliente_direccion" value="<?php echo trim($arrayMantenimiento["Direccion"])?>" readonly/>
                                        </div>
                                         <div class="uk-form-row">
                                            <label for="product_edit_sn_control">Orden Fisica <span class="uk-badge uk-badge-danger uk-badge-notification">Obligatorio</span></label>
                                            <input type="number" class="md-input" id="product_ordenFisica" name="product_ordenFisica" min="1" max="99999999" maxlength="8" value="<?php echo trim($arrayMantenimiento["codOrdenFisica"])?>"/>
                                        </div>
                                        <div class="uk-form-row">
                                            <label for="uk_dp_start">Fecha del Mantenimiento</label>
                                            <input class="md-input label-fixed" type="date" id="uk_dp_start" NAME="uk_dp_fecha"  value="<?php echo date("Y-m-d", strtotime($arrayMantenimiento["fechaInicio"]))?>">
                                        </div>
                                        <div class="uk-form-row">
                                            <label for="product_edit_tecnico" class="uk-form-label">Técnico Asignado</label>
                                            <select id="product_edit_tecnico" name="product_edit_tecnico" data-md-selectize>
                                                <?php
                                                     foreach ($arrayTecnicos as $opcion) {
                                                        echo' <option value="'.trim($opcion['Value']).'"> '.$opcion['DisplayText'].' </option>';
                                                     }
                                                ?>
                                                
                                            </select>
                                        </div>

                                         <div class="uk-form-row">
                                            <label for="product_edit_bodega" class="uk-form-label">Bodega</label>
                                            <select id="product_edit_bodega" name="product_edit_bodega" data-md-selectize>
                                                <?php
                                                     foreach ($arrayBodegas as $opcion) {
                                                        echo' <option value="'.trim($opcion['Value']).'"> '.$opcion['DisplayText'].' </option>';
                                                     }
                                                ?>
                                                
                                            </select>
                                        </div>               

                                        <div class="uk-form-row">
                                            <label for="product_edit_tecnico" class="uk-form-label">Pago mantenimiento</label>
                                            <select id="product_edit_facturadoa" name="product_edit_facturadoa" data-md-selectize>
                                                <option value="1">Facturado a cliente</option>
                                                <option value="0">Facturado a KAO Importaciones</option>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="uk-width-large-1-2">
                                        <div class="uk-form-row">
                                            <label for="product_edit_description_control">Observaciones</label>
                                            <textarea class="md-input" name="product_edit_description_control" id="product_edit_description_control" cols="30" rows="4"><?php echo trim($arrayMantenimiento['comentario']);?></textarea>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card de repuestos ya ingresados -->
                        <div class="md-card">
                            <div class="md-card-toolbar">
                                <h3 class="md-card-toolbar-heading-text">
                                    Ordenes ya registradas
                                </h3>
                            </div>
                            <div class="md-card-content">
                                <div class="uk-overflow-container">
                                    <table class="uk-table uk-table-nowrap uk-table-hover table_check">
                                        <thead>
                                        <tr>
                                            <th class="uk-width-1-10 uk-text-center">ID</th>
                                            <th class="uk-width-2-10">Facturado A</th>
                                            <th class="uk-width-2-10 uk-text-center">Fecha</th>
                                            <th class="uk-width-1-10 uk-text-center">Bodega</th>
                                            <th class="uk-width-1-10 uk-text-center">Monto</th>
                                            <th class="uk-width-2-10 uk-text-center">Acciones</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                foreach ($arrayRepuestos as $repuesto) {
                                                    $fecha = date_create($repuesto['FECHA']);
                                                    $bodegaName = $repuesto['bodegaName'];
                                                    $RUCCliente = trim($repuesto['RUC']);
                                                    if ( $RUCCliente == '1790417581001' ) {
                                                        $style = 'uk-badge uk-badge-primary';
                                                    }else{
                                                        $style = 'uk-badge uk-badge-success';
                                                    }
                                            ?>      
                                                    <tr>
                                                        <td class="uk-text-center"><?php echo $repuesto['ID']?></td>
                                                        <td class="uk-text-center">
                                                            <span class="<?php echo $style ?>">
                                                                <?php echo $repuesto['facturadoA'] ?>
                                                            </span>
                                                        </td>
                                                        <td class="uk-text-center"><?php echo date_format($fecha, 'Y-m-d H:i:s')?></td>
                                                        <td class="uk-text-center"><?php echo $bodegaName ?></td>
                                                        <td><?php echo number_format($repuesto['TOTAL'], 2)?></td>
                                                        <td class="uk-text-center">
                                                            <a><i class="md-icon material-icons" data-uk-modal="{target:'#modal_full'}" data-mantenimiento="<?php echo $repuesto['ID']?> ">&#xE88F;</i></a>
                                                        </td>
                                                    </tr>
                                               
                                                <?php
                                                 }
                                            ?>
                                            
                                            <div class="uk-modal uk-modal-card-fullscreen" id="modal_full">
                                                <div class="uk-modal-dialog uk-modal-dialog-blank">
                                                    <div class="md-card uk-height-viewport">
                                                        <div class="md-card-toolbar">
                                                            
                                                            <span class="md-icon material-icons uk-modal-close">&#xE5C4;</span>
                                                            <h3 class="md-card-toolbar-heading-text">
                                                                Detalle del documento: 
                                                            </h3>
                                                        </div>
                                                        <div class="md-card-content">
                                                        Sin contenido
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Card de nuevos repuestos -->
                        <div class="md-card">
                            <div class="md-card-toolbar">
                                <h3 class="md-card-toolbar-heading-text">
                                    Nuevos Repuestos
                                </h3>
                            </div>
                            <div class="md-card-content">
                                <div class="uk-grid" data-uk-grid-margin="">
                                    
                                    <div class="uk-width-medium-10-10">
                                        <div class="uk-overflow-container">
                                            
                                                <table class="uk-table repuestos_table" data-dynamic-fields="field_template_a" dynamic-fields-counter="3">
                                                </table>
                                                      
                                            <script id="field_template_a" type="text/x-handlebars-template">
                                                <tr class="form_section">
                                                    <td class="uk-width-2-10"><input type="text" class="md-input codigos_prod" name="codigos_prod" placeholder="Codigo" /></td>
                                                    <td class="uk-width-5-10"><input type="text" class="md-input" name="nombres_prod" placeholder="Producto" readonly/></td>
                                                    <td class="uk-width-1-10"><input type="number" class="md-input"  name="cants_prod" placeholder="Cant." /></td>
                                                    <td class="uk-width-1-10"><input type="number" class="md-input"  name="desc_prod" placeholder="Desc." readonly /></td>
                                                    <td class="uk-width-2-10"><input type="text" class="md-input" name="precios_prod" placeholder="Precio" readonly/></td>
                                                    
                                                    <td class="uk-width-1-10 uk-text-right uk-text-middle">
                                                        <a href="#" class="btnSectionClone"><i class="material-icons md-24">&#xE145;</i></a>
                                                    </td>
                                                </tr>
                                            </script>
                                            
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="uk-modal" id="modal_facturadoA">
        <div class="uk-modal-dialog ">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title">Indique a quien será facturado. <i class="material-icons">&#xE8FD;</i></h3>
            </div>
            <p>Los productos que registre serán facturados a Importaciones KAO?</p>
            
            <div class="uk-modal-footer uk-text-right">
                <button type="button" id="modal_facturadoA_cancel" class="md-btn md-btn-flat uk-modal-close">No, AL CLIENTE</button>
                <button type="button" id="modal_facturadoA_confirm" class="md-btn md-btn-flat md-btn-flat-primary uk-modal-close">Si, a IMPORTACIONES KAO</button>
            </div>
        </div>
    </div>

    <div id="modal_AgendarNuevo" class="uk-modal">
            <div class="uk-modal-dialog">
                <h2 class="heading_a">Agendar mantenimiento al equipo <i class="material-icons">&#xE8FD;</i></h2>
                <p>Indique la fecha estimada para el proximo mantenimiento.</p>
                <div class="uk-overflow-container">
                    <form action="" class="uk-form-stacked" id="extraAgendar_form">
                        <input type="hidden" class="md-input" id="codMantenimientoModal" name="codMantenimientoModal" readonly/>
                        <div class="uk-form-row">
                            <label for="uk_dp_proxMant">Fecha del Mantenimiento</label>
                            <input class="md-input label-fixed" type="date" id="uk_dp_proxMant" name="uk_dp_proxMant" value="<?php echo date("Y-m-d")?>">
                        </div>
                    </form>
                    
                   
                </div>

                <div class="uk-modal-footer uk-text-right">
                    <button type="button" id="btnGeneraExtraAgendamiento" class="md-btn md-btn-flat md-btn-flat-primary">Registrar</button>
                </div>
            </div>
    </div>

    
    <!-- Boton de submit -->    
    <div class="md-fab-wrapper">
            <a class="md-fab md-fab-primary" href="#" id="product_edit_submit">
                <i class="material-icons">&#xE161;</i>
            </a>
    </div>
  
<!-- google web fonts -->
<script>
    WebFontConfig = {
        google: {
            families: [
                'Source+Code+Pro:400,700:latin',
                'Roboto:400,300,500,700,400italic:latin'
            ]
        }
    };
    (function() {
        var wf = document.createElement('script');
        wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
        '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
        wf.type = 'text/javascript';
        wf.async = 'true';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(wf, s);
    })();
</script>

<!-- common functions -->
<script src="<?php echo ROOT_PATH; ?>assets/js/common.min.js"></script>
<!-- uikit functions -->
<script src="<?php echo ROOT_PATH; ?>assets/js/uikit_custom.js"></script>
<!-- altair common functions/helpers -->
<script src="<?php echo ROOT_PATH; ?>assets/js/altair_admin_common.min.js"></script>

<!-- scripts de la pagina -->
<script src="<?php echo ROOT_PATH; ?>assets/js/pages/editMantenimiento.js"></script>

<!--  theme color functions -->
<script src="<?php echo ROOT_PATH; ?>assets/js/configTheme.js"></script>

</body>