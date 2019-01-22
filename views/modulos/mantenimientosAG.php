<?php
    if (!isset($_SESSION["usuarioRUC"])){
           header("Location:index.php?&action=login");  
        }   

    /* CREACION DE INSTANCIA USADA PARA ESTA VISTA*/  
    $codEmpresa = trim($_SESSION["empresaAUTH"]);  // Nombre de la db asiganda en el login
    $mantenimientos = new models\MantenimientosClass();

    $primerDiaMes = $mantenimientos->first_month_day(); //$mantenimientos->getPrimerDiaMes()['StartOfMonth'];
    $ultimoDiaMes = $mantenimientos->last_month_day(); //$mantenimientos->getUltimoDiaMes()['EndOfMonth'];

    $primerDiaMesSPAM = new DateTime($primerDiaMes);
    $primerDiaMesSPAM = date_format($primerDiaMesSPAM, "Y-m-d");

    $ultimoDiaMesSPAM = new DateTime($ultimoDiaMes);
    $ultimoDiaMesSPAM = date_format($ultimoDiaMesSPAM, "Y-m-d");

    $arrayMantenimientos = $mantenimientos->getMantenimientosAgendados($codEmpresa, 100); //Devuelve array de mantenimientos
    $dateNow = $mantenimientos->getDateNow(); //Fecha actual determina si la tarjeta esta valida o no
   
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

            <h3 class="heading_a uk-margin-bottom">Equipos agendados del mes: <?php echo $primerDiaMesSPAM .' hasta '. $ultimoDiaMesSPAM?></h3>

            <ul id="products_sort" class="uk-subnav uk-subnav-pill">
                <li data-uk-sort="product-name:asc"><a href="#">Acendente</a></li>
                <li data-uk-sort="product-name:desc"><a href="#">Descendente</a></li>
            </ul>

            <div class="uk-grid-width-small-1-2 uk-grid-width-medium-1-3 uk-grid-width-large-1-4 hierarchical_show" data-uk-grid="{gutter: 20, controls: '#products_sort'}">
                
                <?php
                    if (is_array($arrayMantenimientos)) {
                    
                    foreach ($arrayMantenimientos as $equipo) {
                        $dateCard = new DateTime($equipo['fechaInicio']);
                        $fechaTarjeta = date_format($dateCard, "Y-m-d");
                        if ($fechaTarjeta >= $dateNow ) {
                            $isInTime = true;
                        }else{
                            $isInTime = false;
                        }
                    
                ?>
                    <!-- Inicio card -->
                    <div data-product-name="<?php echo $equipo['NombreCliente']?>">
                        <div class="md-card md-card-hover-img">
                            <div class="md-card">
                                <?php $colorCard = ($isInTime) ? 'md-bg-light-blue-600' : 'md-bg-red-800' ?>
                                <div class="md-card-head <?php echo $colorCard?>">
                                    <div class="md-card-head-menu" data-uk-dropdown="{pos:'bottom-right'}">
                                        <i class="md-icon material-icons md-icon-light"></i>
                                        <div class="uk-dropdown uk-dropdown-small">
                                            <ul class="uk-nav">
                                                <li><a href="#" class="showInforme" data-mantenimiento="<?php echo $equipo['codMantenimiento']?>">Ver hoja de informe</a></li>
                                                <li><a href="?&action=editMantenimiento&codOrden=<?php echo $equipo['codMantenimiento']?>" class="addRepuestos" data-mantenimiento="<?php echo $equipo['codMantenimiento']?>" disabled>Abrir Orden</a></li>
                                                <li><a href="#" class="uk-text-primary aprobarCita" data-mantenimiento="<?php echo $equipo['codMantenimiento']?>">Cerrar mantenimiento</a></li>
                                                <li><a href="#" class="uk-text-danger anularCita" data-mantenimiento="<?php echo $equipo['codMantenimiento']?>">Anular Cita</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="uk-text-center">
                                        <img class="md-card-head-avatar" src="assets/img/avatars/avatar_11.png" alt="">
                                    </div>
                                    <h3 class="md-card-head-text uk-text-center md-color-white">
                                        <!-- Titulo del card-->
                                        <?php 
                                            echo $equipo['codMantenimiento'];
                                            echo ' - ' . $equipo['codOrdenFisica'];
                                        ?>
                                        
                                        <span><?php echo $equipo['Producto']?></span>
                                    </h3>
                                </div>
                                <div class="md-card-content" style="min-height: 215px;">
                                    <ul class="md-list md-list-addon">
                                        <li>
                                            <div class="md-list-addon-element">
                                                <i class="md-list-addon-icon material-icons">assignment</i>
                                            </div>
                                            <div class="md-list-content">
                                                <span class="md-list-heading"><?php echo $equipo['codOrdenFisica']?></span>
                                                <span class="uk-text-small uk-text-muted">Orden Trabajo (Fisica)</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="md-list-addon-element">
                                                <i class="md-list-addon-icon uk-icon-user"></i>
                                            </div>
                                            <div class="md-list-content">
                                                <span class="md-list-heading"><?php echo substr($equipo['NombreCliente'], 0, 20)?></span>
                                                <span class="uk-text-small uk-text-muted">Cliente</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="md-list-addon-element">
                                                <i class="md-list-addon-icon material-icons"></i>
                                            </div>
                                            <div class="md-list-content">
                                                <span class="md-list-heading">
                                                    <?php 
                                                        $retVal = empty($equipo['Email']) ? '(Vacio)' : $equipo['Email'] ;
                                                        echo $retVal;
                                                    ?> </span>
                                                <span class="uk-text-small uk-text-muted">Email</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="md-list-addon-element">
                                                <i class="md-list-addon-icon material-icons"></i>
                                            </div>
                                            <div class="md-list-content">
                                                <span class="md-list-heading"><?php echo $equipo['Telefono']?></span>
                                                <span class="uk-text-small uk-text-muted">Teléfono</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="md-list-addon-element">
                                                <i class="md-list-addon-icon material-icons">build</i>
                                            </div>
                                            <div class="md-list-content">
                                                <span class="md-list-heading"><?php echo $equipo['Encargado']?></span>
                                                <span class="uk-text-small uk-text-muted">Técnico Asignado</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="md-list-addon-element">
                                                <i class="md-list-addon-icon uk-icon-calendar-check-o"></i>
                                            </div>
                                            <div class="md-list-content">
                                                <span class="md-list-heading">
                                                    <?php 
                                                     $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
                                                     $dateTime = new DateTime($equipo['fechaInicio']);
                                                     $dia =  date_format($dateTime, "w");
                                                     $hora = date_format($dateTime, "Y-m-d");
                                                     echo $dias[$dia].", ".$hora."</br>";
                                                    ?>
                                                    De 
                                                    <?php 
                                                     $dateTime = new DateTime($equipo['fechaInicio']);
                                                     $hora = date_format($dateTime, "H:i");
                                                     echo $hora;
                                                    ?>

                                                    a 
                                                    <?php 
                                                     $dateTime = new DateTime($equipo['fechaFin']);
                                                     $hora = date_format($dateTime, "H:i");
                                                     echo $hora;
                                                    ?>
                                                   
                                                </span>
                                                <span class="uk-text-small uk-text-muted">Hora de Inicio - Fin</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="md-list-addon-element">
                                                <i class="md-list-addon-icon uk-icon-map-marker"></i>
                                            </div>
                                            <div class="md-list-content">
                                                <span class="md-list-heading"><?php echo $equipo['Direccion']?></span>
                                                <span class="uk-text-small uk-text-muted">Dirección</span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- FIN card -->

                    
               <?php
                    } // FIN del ciclo for
                    }else{
                        echo '
                            <script>
                                alert("No se pudo establecer conexion con la tabla requerida o existe un error en la sintaxis SQL.");
                            </script>
                        ';
                    }
                     // FIN DEL IF
               ?>

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

    </div> <!-- Fin page content-->

  
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

<!-- Scripts de la página -->
<script src="<?php echo ROOT_PATH; ?>assets/js/pages/mantenimientosAG.js"></script>

<!--  theme color functions -->
<script src="<?php echo ROOT_PATH; ?>assets/js/configTheme.js"></script>

</body>