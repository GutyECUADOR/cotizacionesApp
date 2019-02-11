<?php
 require_once '../core/models/conexion.php';
 require_once '../core/models/ajaxModel.php';
 require_once '../core/controllers/ajaxController.php';

$ajax = new \controllers\ajaxController();

$email = $ajax->getVEN_CABController('992018PRO00014056')['EMAIL'];

$emails = explode( ';', $email );

foreach ($emails as $correo) {
    echo $correo .'/br';   // Add a recipient
}
?>