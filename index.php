<?php
    date_default_timezone_set('America/Lima');
    @ob_start();
    session_start();
    require_once './config/global.php';
    require_once './core/models/conexion.php';
    require_once './core/controllers/mainController.php';
    require_once './core/models/mainModel.php';
    
    /*Controllers y Models Personalizados*/
    require_once './core/controllers/CotizacionController.php';
    require_once './core/models/CotizacionClass.php';


    $app = new controllers\mainController();
    $app->loadtemplate();
   

    