<?php
date_default_timezone_set('America/Lima');
session_start();
require_once '../../../config/global.php';
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

    public function getInfoVENCAB($IDDocument) {
      return $this->ajaxController->getVEN_CABController($IDDocument);
    }

    public function getInfoVENMOV($IDDocument) {
      return $this->ajaxController->getVEN_MOVController($IDDocument);
    }
    
    public function getAllClientes($terminoBusqueda,  $tipoBusqueda) {
      return $this->ajaxController->getAllClientesController($terminoBusqueda,  $tipoBusqueda);
    }

    public function getAllProductos($terminoBusqueda,  $tipoBusqueda) {
      return $this->ajaxController->getAllProductosController($terminoBusqueda,  $tipoBusqueda);
    }

    public function getAllDocumentos($fechaINI, $fechaFIN, $stringBusqueda) {
      return $this->ajaxController->getAllDocumentosController($fechaINI,  $fechaFIN, $stringBusqueda);
    }

    public function getInfoProducto($codigoProducto, $clienteRUC) {
        return $this->ajaxController->getInfoProductoController($codigoProducto, $clienteRUC);
    }

    public function generaProforma($IDDocument) {
      return $this->ajaxController->generaReporte($IDDocument, 'I');
  }

    public function saveCotizacion($formCotizacion){
      return $this->ajaxController->insertCotizacion($formCotizacion);
    }

    public function sendEmail($IDDocument){
      return $this->ajaxController->sendCotizacion($IDDocument);
    }

    public function sendEmailByCustomEmail($arrayEmails, $IDDocument, $customMessage){
      return $this->ajaxController->sendCotizacionToEmails($arrayEmails, $IDDocument, $customMessage);
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

        /* Obtiene array de informacion del cliente*/ 
        case 'getInfoVENCAB':
          if (isset($_GET['IDDocument'])) {
            $IDDocument = $_GET['IDDocument'];
            $respuesta = $ajax->getInfoVENCAB($IDDocument);
            $rawdata = array('status' => 'OK', 'mensaje' => 'respuesta correcta', 'data' => $respuesta);
          }else{
            $rawdata = array('status' => 'ERROR', 'mensaje' => 'No se ha indicado parámetros.');
          }
          
          echo json_encode($rawdata);

        break;

        /* Obtiene array de informacion de movimientos del cliente*/ 
        case 'getInfoVENMOV':
          if (isset($_GET['IDDocument'])) {
            $IDDocument = $_GET['IDDocument'];
            $respuesta = $ajax->getInfoVENMOV($IDDocument);
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

        /* Obtiene array de los documentos SP Winfenix*/ 
        case 'searchDocumentos':
          if (isset($_GET['fechaINI']) && isset($_GET['fechaFIN']) && isset($_GET['stringBusqueda']) ) {
            $fechaINI = date("Ymd", strtotime($_GET['fechaINI']));
            $fechaFIN = date("Ymd", strtotime($_GET['fechaFIN']));
            $stringBusqueda = $_GET['stringBusqueda'];

            $respuesta = $ajax->getAllDocumentos($fechaINI,  $fechaFIN, $stringBusqueda);
            $rawdata = array('status' => 'OK', 'mensaje' => 'respuesta correcta', 'data' => $respuesta);
          }else{
            $rawdata = array('status' => 'ERROR', 'mensaje' => 'No se ha indicado parámetros.');
          }
          
          echo json_encode($rawdata);

        break;

          /* Obtiene array de los documentos SP Winfenix*/ 
        case 'generaProforma':
          if (isset($_GET['IDDocument'])) {
            $IDDocument = $_GET['IDDocument'];
          
            $PDFDocument = $ajax->generaProforma($IDDocument);
            //$rawdata = array('status' => 'OK', 'mensaje' => 'respuesta correcta', 'data' => $respuesta);
            echo $PDFDocument;
          }else{
            $rawdata = array('status' => 'ERROR', 'mensaje' => 'No se ha indicado parámetros.');
            echo json_encode($rawdata);
          }
        
        

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

        /* Utiliza PHPMailer para el envio de correo, utiliza los correos del cliente indicados en la tabla*/ 
        case 'sendEmail':

          if (isset($_GET['IDDocument']) ) {
            $IDDocument = $_GET['IDDocument'];
            $respuesta = $ajax->sendEmail($IDDocument);
            $rawdata = array('status' => 'OK', 'mensaje' => 'respuesta correcta', 'data' => $respuesta);
          }else{
            $rawdata = array('status' => 'ERROR', 'mensaje' => 'No se ha indicado parámetros.' );
          }

          echo json_encode($rawdata);

        break; 

        /* Utiliza PHPMailer para el envio de correo, permite editar los emails que seran enviados*/ 
        case 'sendEmailByCustomEmail':

          if (isset($_GET['email']) && isset($_GET['IDDocument']) ) {
            $arrayEmails = $_GET['email'];
            $IDDocument = $_GET['IDDocument'];
            $customMessage = isset($_GET['message']) ? $_GET['message'] : '';
            $respuesta = $ajax->sendEmailByCustomEmail($arrayEmails, $IDDocument, $customMessage);
            $rawdata = array('status' => 'OK', 'mensaje' => 'respuesta correcta', 'data' => $respuesta);
          }else{
            $rawdata = array('status' => 'ERROR', 'mensaje' => 'No se ha indicado parámetros.' );
          }  
          
        
          echo json_encode($rawdata);

        break;

        case 'uploadFile':

          $codOrden = '992018PRO00012217';
          $contador = 0;
          $location = "uploads/";
          $newname = $codOrden."_$contador".".jpg"; // Asignamos nombre referencial
          $tempName = $_FILES["file"]["tmp_name"];
          
          if (file_exists($newname)) {
            echo "El archivo ya existe";
          } else {
              
              if (move_uploaded_file($tempName, $location.$newname)) {
                echo "Archivo cargado con nombre: ". $newname;
              }else{
                echo "No cargado";
              }
          }

          
          
          
        
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


