<?php
require_once '../../../core/models/OrdentClass.php'; // Funciones del modulo
$ordentrabajo = new \models\OrdentClass();
$action = $_GET['action'];
$dato_ci = $_GET['dato_ci'];
$modeloAuto = $_GET['modeloAuto'];

switch ($action) {
    case 'getAutos':
        $ordentrabajo->getAutosByCliente($dato_ci);
        break;
    
    
     case 'getModeloAutos':
        $ordentrabajo->getModeloAutos($modeloAuto);
        break;

    default:
        break;
}