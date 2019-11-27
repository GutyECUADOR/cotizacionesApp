<?php
session_start();
require_once '../config/global.php';
require_once '../core/models/conexion.php';
require_once '../core/models/ajaxModel.php';
require_once '../core/controllers/ajaxController.php';

$ajax = new \models\ajaxModel();

$response = $ajax->getInfoClienteModel('1792630436001');

var_dump(json_encode($response));