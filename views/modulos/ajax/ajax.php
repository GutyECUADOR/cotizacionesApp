<?php
require_once '../../../core/controllers/ajaxController.php';

class ajax{
    
    public $datos;
    
    public function validaUsuario() {
        
        // Creacion de instancia de modellogin    
        
        $data = $this->datos;
        
        $ajaxController = new \controllers\ajaxController();
        $respuestaAjax = $ajaxController->actionCatcherController();
    }
    
}


$ajax = new ajax();
$ajax->datos = $_POST[''];