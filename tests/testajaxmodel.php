<?php
session_start();
 require_once '../core/models/conexion.php';
 require_once '../core/models/ajaxModel.php';
 require_once '../core/controllers/ajaxController.php';

$ajax = new \models\ajaxModel();

$response = $ajax->getVENCABByID('992018PRO00014043');

var_dump(json_encode($response));