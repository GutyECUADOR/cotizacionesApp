<?php
date_default_timezone_set('America/Lima');
session_start();
require_once '../../../core/models/conexion.php';
require_once '../../../core/controllers/ajaxController.php';
require_once '../../../core/models/ajaxModel.php';

class ajax{
  private $ajaxController;
   
    public function __construct() {
      /*Creamos instancia general del controlador*/
      $this->ajaxController = new \controllers\ajaxController();
    }

    /*Métodos disponibles del API */

    public function getInfoCliente($RUC) {
        return $this->ajaxController->getInfoClienteController($RUC);
    }

    public function getInfoProducto($codigoProducto, $tipoPrecio) {
        return $this->ajaxController->getInfoProductoController($codigoProducto, $tipoPrecio);
    }

    public function saveCotizacion($formCotizacion){
      return $this->ajaxController->insertCotizacion($formCotizacion);
    }

}

  /* Cuerpo del API */

  try{
    $ajax = new ajax(); //Instancia que controla las acciones
    $HTTPaction = $_GET["action"];

    switch ($HTTPaction) {

        /* Obtiene array de informacion del cliente*/ 
        case 'getInfoCliente':
          if (isset($_GET['ruc'])) {
            $RUC = $_GET['ruc'];
            $respuesta = $ajax->getInfoCliente($RUC);
            $rawdata = array('status' => 'OK', 'mensaje' => 'respuesta correcta', 'data' => $respuesta);
          }else{
            $rawdata = array('status' => 'ERROR', 'mensaje' => 'No se ha indicado parámetros.');
          }
          
        
          echo json_encode($rawdata);

        break;

        /* Obtiene array de informacion del producto*/ 
        case 'getInfoProducto':

          if (isset($_GET['codigo']) && isset($_GET['tipoPrecio'])) {
            $codigoProducto = $_GET['codigo'];
            $tipoPrecio =  $_GET['tipoPrecio'];
            $respuesta = $ajax->getInfoProducto($codigoProducto, $tipoPrecio);
            $rawdata = array('status' => 'OK', 'mensaje' => 'respuesta correcta', 'data' => $respuesta);
          }else{
            $rawdata = array('status' => 'ERROR', 'mensaje' => 'No se ha indicado parámetros.');
          }
          
        
          echo json_encode($rawdata);

        break;

        case 'test':
            $rawdata = array('status' => 'OK', 'mensaje' => 'Respuesta correcta');
            echo json_encode($rawdata);

            break;

        default:
            $rawdata = array('status' => 'error', 'mensaje' =>'El API no ha podido responder la solicitud, revise el tipo de action');
            echo json_encode($rawdata);
            break;
    }
    
  } catch (Exception $ex) {
    //Return error message
    $rawdata = array();
    $rawdata['status'] = "error";
    $rawdata['mensaje'] = $ex->getMessage();
    echo json_encode($rawdata);
  }


