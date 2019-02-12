<?php
session_start();
 require_once '../core/models/conexion.php';
 require_once '../core/models/ajaxModel.php';
 require_once '../core/controllers/ajaxController.php';

$ajax = new \controllers\ajaxController();


$infoSender = $ajax->getInfoUsuarioController($_SESSION["usuarioRUC"]);

var_dump(trim($infoSender['Smtp']));
var_dump(trim($infoSender['User_Mail']));
var_dump(trim($infoSender['Pwd_Mail']));
