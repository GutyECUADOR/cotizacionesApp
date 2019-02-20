<?php
session_start();
require_once '../config/global.php';
require_once '../core/models/conexion.php';
require_once '../core/models/ajaxModel.php';
require_once '../core/controllers/ajaxController.php';

$ajax = new \controllers\ajaxController();

$response = $ajax->getVEN_CABController('992018PRO00014099');

var_dump(json_encode($response));