<?php
date_default_timezone_set('America/Lima');
session_start();
require_once '../../../core/controllers/ajaxController.php';
require_once '../../../core/models/ajaxModel.php';

class ajax{
  private $ajaxController;

    public function __construct() {
      $this->ajaxController = new \controllers\ajaxController();
    }

    public function getConteoMantenimientos($codEmpresa) {
        return $this->ajaxController->getCountMantenimientosController($codEmpresa);
    }

    public function getHistorico($fechaINI, $fechaFIN, $codEmpresa, $tiposDocs) {
      return $this->ajaxController->getHistoricoController($fechaINI, $fechaFIN, $codEmpresa, $tiposDocs);
  }

}

  try{
    $ajax = new ajax(); //Instancia que controla las acciones
    $HTTPaction = $_GET["action"];

    switch ($HTTPaction) {
        case 'getConteoMantenimientos':
        $codEmpresa = $_SESSION["codEmpresaAUTH"];
        $respuesta = $ajax->getConteoMantenimientos($codEmpresa);
        $rawdata = array('status' => 'OK', 'mensaje' => 'respuesta correcta', 'data' => $respuesta);
        echo json_encode($rawdata);

        break;

        case 'getHistorico':
        $fechaINI = $_GET["fechaInicial"];
        $fechaFIN = $_GET["fechaFinal"];
        $tiposDocs = $_GET["tiposDocs"];

        $fechaFormatINI = date('Ymd', strtotime($fechaINI));
        $fechaFormatFIN = date('Ymd', strtotime($fechaFIN));

        $codEmpresa = $_SESSION["codEmpresaAUTH"];
        $respuesta = $ajax->getHistorico($fechaFormatINI, $fechaFormatFIN, $codEmpresa, $tiposDocs);
        $rawdata = array('status' => 'OK', 
                        'mensaje' => $fechaFormatINI, 
                        'horaINI' => 'recuperado historico', 
                        'data' => $respuesta
                      );
        echo json_encode($rawdata);

        break;

        case 'test':
            $rawdata = array('status' => 'OK', 'mensaje' => 'respuesta correcta');
            echo json_encode($rawdata);

            break;

        default:
            $rawdata = array('status' => 'error', 'mensaje' =>'el API no ha podido responder la solicitud, revise el tipo de action');
            echo json_encode($rawdata);
            break;
    }
    
  } catch (Exception $ex) {
    //Return error message
    $jTableResult = array();
    $jTableResult['Result'] = "ERROR";
    $jTableResult['Message'] = $ex->getMessage();
    echo json_encode($jTableResult);
  }


