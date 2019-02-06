<?php
date_default_timezone_set('America/Lima');
session_start();
require_once '../../../vendor/autoload.php';
require_once '../../../core/models/conexion.php';
require_once '../../../core/controllers/ajaxController.php';
require_once '../../../core/models/ajaxModel.php';
require_once '../../../core/models/venCabClass.php';
require_once '../../../core/models/venMovClass.php';

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

    public function getAllClientes($terminoBusqueda,  $tipoBusqueda) {
      return $this->ajaxController->getAllClientesController($terminoBusqueda,  $tipoBusqueda);
    }

    public function getAllProductos($terminoBusqueda,  $tipoBusqueda) {
      return $this->ajaxController->getAllProductosController($terminoBusqueda,  $tipoBusqueda);
    }

    public function getInfoProducto($codigoProducto, $clienteRUC) {
        return $this->ajaxController->getInfoProductoController($codigoProducto, $clienteRUC);
    }

    public function saveCotizacion($formCotizacion){
      return $this->ajaxController->insertCotizacion($formCotizacion);
    }

    public function sendEmail($email){
      return $this->ajaxController->sendEmail($email);
    }

}

  /* Cuerpo del API */

  try{
    $ajax = new ajax(); //Instancia que controla las acciones
    $HTTPaction = $_GET["action"];

    switch ($HTTPaction) {

       /* Obtiene array de informacion del cliente*/ 
        case 'saveCotizacion':
          if (isset($_GET['formData'])) {
            $formData = json_decode($_GET['formData']);
            $respuesta = $ajax->saveCotizacion($formData);
            $rawdata = array('status' => 'OK', 'mensaje' => 'Realizado', 'data' => $respuesta);
          }else{
            $rawdata = array('status' => 'ERROR', 'mensaje' => 'No se ha indicado parámetros.');
          }
        
          echo json_encode($rawdata);

        break;

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

        /* Obtiene array de los cliente*/ 
        case 'searchClientes':
          if (isset($_GET['terminoBusqueda']) && isset($_GET['tipoBusqueda'])) {
            $terminoBusqueda = $_GET['terminoBusqueda'];
            $tipoBusqueda = $_GET['tipoBusqueda'];
            $respuesta = $ajax->getAllClientes($terminoBusqueda,  $tipoBusqueda);
            $rawdata = array('status' => 'OK', 'mensaje' => 'respuesta correcta', 'data' => $respuesta);
          }else{
            $rawdata = array('status' => 'ERROR', 'mensaje' => 'No se ha indicado parámetros.');
          }
          
          echo json_encode($rawdata);

        break;

        /* Obtiene array de los productos*/ 
        case 'searchProductos':
          if (isset($_GET['terminoBusqueda']) && isset($_GET['tipoBusqueda'])) {
            $terminoBusqueda = $_GET['terminoBusqueda'];
            $tipoBusqueda = $_GET['tipoBusqueda'];
            $respuesta = $ajax->getAllProductos($terminoBusqueda,  $tipoBusqueda);
            $rawdata = array('status' => 'OK', 'mensaje' => 'respuesta correcta', 'data' => $respuesta);
          }else{
            $rawdata = array('status' => 'ERROR', 'mensaje' => 'No se ha indicado parámetros.');
          }
          
          echo json_encode($rawdata);

        break;

        /* Obtiene array de informacion del producto*/ 
        case 'getInfoProducto':

          if (isset($_GET['codigo']) && isset($_GET['clienteRUC'])) {
            $codigoProducto = $_GET['codigo'];
            $clienteRUC =  $_GET['clienteRUC'];
            $respuesta = $ajax->getInfoProducto($codigoProducto, $clienteRUC);
            $rawdata = array('status' => 'OK', 'mensaje' => 'respuesta correcta', 'data' => $respuesta);
          }else{
            $rawdata = array('status' => 'ERROR', 'mensaje' => 'No se ha indicado parámetros.');
          }
          
        
          echo json_encode($rawdata);

        break;

        /* Utiliza PHPMailer para el envio de correo*/ 
        case 'sendEmail':

          if (isset($_GET['email'])) {
            $email = $_GET['email'];
            $respuesta = $ajax->sendEmail($email);
            $rawdata = array('status' => 'OK', 'mensaje' => 'respuesta correcta', 'data' => $respuesta);
          }else{
            $rawdata = array('status' => 'ERROR', 'mensaje' => 'No se ha indicado parámetros.' );
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


