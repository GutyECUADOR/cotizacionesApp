<?php namespace controllers;

class mainController {
    
    public $mainmodel;
    
    public function __construct()
    {
        require_once 'core/models/mainModel.php';
        $this->mainmodel = new \models\mainModel();
    }
    
    public function loadtemplate(){
        include 'views/baseTemplate.php';
    }
    
    public function actionCatcherController(){
        if (isset($_GET['action'])){
           $action = $_GET['action'];
           $modulo = $this->mainmodel->actionCatcherModel($action);
           
           include $modulo; 
        }else{
           $action = 'default';
           $modulo = $this->mainmodel->actionCatcherModel($action);
           include $modulo; 
        }
       
    }
}
