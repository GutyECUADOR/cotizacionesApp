<?php namespace models;

class mainModel {
    
    public function actionCatcherModel($action){
        switch ($action) {
            case 'inicio':
                $contenido = "views/modulos/inicio.php";
                break;
         
            default:
                $contenido = "views/modulos/inicio.php";
                break;
        }
        
       
        return $contenido;
        
    }
}
