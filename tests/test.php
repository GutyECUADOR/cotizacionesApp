<?php
 require_once '../core/models/conexion.php';
 require_once '../core/models/ajaxModel.php';

$ajax = new \models\ajaxModel();

echo json_encode($ajax->getAllProductosModel('DE','NOMBRE'));

?>