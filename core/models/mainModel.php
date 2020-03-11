<?php namespace models;

class mainModel {
    
    public function actionCatcherModel($action){
        switch ($action) {
            case 'inicio':
                $contenido = "views/modulos/inicio.php";
                break;

            case 'multiempresa':
                $contenido = "views/modulos/multiempresa.php";
                break;    

            case 'login':
            $contenido = "views/modulos/loginView.php";
                break;    
         
            case 'logout':
            $contenido = "views/modulos/cerrarSesion.php";
                break; 

            default:
                $contenido = "views/modulos/inicio.php";
                break;
        }
        
       
        return $contenido;
        
    }
}
