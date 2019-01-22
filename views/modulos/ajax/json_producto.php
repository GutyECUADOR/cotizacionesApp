<?php
require_once '../../../core/controllers/ajaxController.php';
require_once '../../../core/models/ajaxModel.php';

class ajax{
    
    public $datos;
    
    public function ejecutaAjax() {

        $data = $this->datos;
        
        $ajaxController = new \controllers\ajaxController();
        $respuestaAjax = $ajaxController->actionJSONproducto($data);
        echo $respuestaAjax;
    }
    
}


$ajax = new ajax();
$ajax->datos =$_GET['cod_producto'];
$ajax->ejecutaAjax();