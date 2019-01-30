<?php
    date_default_timezone_set('America/Lima');
    @ob_start();
    session_start();
    require_once './config/global.php';
    require_once './core/models/conexion.php';
    require_once './core/controllers/mainController.php';
    require_once './core/controllers/loginController.php';
    require_once './core/models/loginModel.php';
    require_once './core/models/mainModel.php';
    require_once './core/models/ajaxModel.php';
    
    /*Controllers y Models Personalizados*/
    require_once './core/controllers/CotizacionController.php';
    require_once './core/models/CotizacionClass.php';

    /* $ajax = new \models\ajaxModel();
    var_dump($ajax->getDatosDocumentsWINFENIXByTypo('PRO', 'LICEO')['Serie']);
    var_dump($ajax->getNextNumDocWINFENIX('PRO', 'LICEO'));
    */

    /* $ajax = new \models\loginModel();
    $arrayDatos = array('usuario' => 'ADMIN', 'password' => '123' );
    var_dump($ajax->validaIngreso($arrayDatos)); */
   

    $app = new controllers\mainController();
    $app->loadtemplate();
   

    