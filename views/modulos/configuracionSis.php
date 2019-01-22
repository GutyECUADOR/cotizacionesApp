<?php
    if (!isset($_SESSION["usuarioRUC"])){
           header("Location:index.php?&action=login");  
        }   

      if(file_exists(constant("CONFIG_FILE")) && $configXML = simplexml_load_file(constant("CONFIG_FILE"))){
        $omitidosMantenimiento = $configXML->arrayRUCOmitidosMantenimientos;
      }else{
        die('El archivo de configuracion no se encuentra, contacte a sistemas.');
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
        <div id="page_content_inner">

            <h4 class="heading_a uk-margin-bottom">Configuracion de Mantenimientos</h4>
            <form action="" class="uk-form-stacked" id="page_settings">
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-large-3-3 uk-width-medium-1-1">
                        <div class="md-card">
                            <div class="md-card-content">
                                <div class="uk-form-row">
                                    <label for="settings_site_name">Los RUCs indicados en el array no seran buscados en el mantenimiento de equipos</label>
                                    <input class="md-input" type="text" id="settings_arrayMantOmitidos" name="settings_arrayMantOmitidos" value="<?php echo  $omitidosMantenimiento ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                  
                </div>

                <div class="md-fab-wrapper">
                    <button type="submit" class="md-fab md-fab-primary" href="#" id="page_settings_submit">
                        <i class="material-icons">&#xE161;</i>
                    </button>
                </div>

            </form>

        </div>
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


</body>