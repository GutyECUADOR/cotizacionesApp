<?php
date_default_timezone_set('America/Lima');
session_start();
require_once '../../../libs/PHPMailer/src/Exception.php';
require_once '../../../libs/PHPMailer/src/PHPMailer.php';
require_once '../../../libs/PHPMailer/src/SMTP.php';
require_once '../../../core/controllers/ajaxController.php';
require_once '../../../core/models/ajaxModel.php';
require_once '../../../core/models/MantenimientosClass.php';
require_once '../../../core/models/venCabClass.php';
require_once '../../../core/models/venMovClass.php';
require_once '../../../config/global.php';

class ajax{
  private $ajaxController;

    public function __construct() {
      $this->ajaxController = new \controllers\ajaxController();
    }

    public function listAction($fechaInicial, $fechaFinal, $paginas, $registros) {
        return $this->ajaxController->getAllEquiposSinMantenimiento($fechaInicial , $fechaFinal, $paginas, $registros);
    }

    public function updateAction($data) {
        return $this->ajaxController->agendarMantenimiento($data);
    }

    public function anularAction($codMNT){
      return $this->ajaxController->anularMantenimiento($codMNT);
    }

    public function aprobarAction($codMNT){
      return $this->ajaxController->aprobarMantenimiento($codMNT);
    }

    public function omitirAction($data){
      return $this->ajaxController->omitirMantenimiento($data);
    }

    public function listTipoMantenimientosAction(){
      return $this->ajaxController->getTiposMantenimientos();
    }

    public function lisTecnicosAction(){
      return $this->ajaxController->getAllTecnicos();
    }

    public function validaProducto($codProducto){
      return $this->ajaxController->getProductoByCod($codProducto);
    }

    public function updateMantenimiento($formData, $productosArray){
      return $this->ajaxController->updateMantenimientoByCod($formData, $productosArray);
    }

    public function insertExtraMantenimiento($formData){
      return $this->ajaxController->agendarExtraMantenimiento($formData);
    }

    public function validaOrdenFisica($formData){
      return $this->ajaxController->isValidOrdenFisica($formData);
    }

    public function sendEmail($mail, $data){
        return $this->ajaxController->sendEmail($mail, $data);
      }

}

  try{
    $ajax = new ajax(); //Instancia que controla las acciones
    $HTTPaction = $_GET["action"];

    switch ($HTTPaction) {
        case 'list':
            if (isset($_POST["fechaINI"]) && isset($_POST["fechaFIN"])) {
            $fechaInicial = $_POST["fechaINI"];
            $fechaFinal = $_POST["fechaFIN"];
            $fechaInicial = date("Ymd", strtotime($fechaInicial));
            $fechaFinal = date("Ymd", strtotime($fechaFinal));
            $start = $_GET["jtStartIndex"];
            $pageSize = $_GET["jtPageSize"];
            }else{
                $fechaInicial = date('Ymd');
                $fechaFinal = date('Ymd');
                $start = 1;
                $pageSize = 10;
            }

            /* Comentar estas 2 lineas  de fechas para produccion*/
            
            /* $fechaInicial = '20180101';
            $fechaFinal = '20181130'; */ 
            
            $resultset = $ajax->listAction($fechaInicial, $fechaFinal, $start, $pageSize);
            
            $rawdata['Result'] = "OK"; // Compo obligatorio para JTable
            $rawdata['TotalRecordCount'] = 10; // Compo obligatorio para JTable
            $rawdata['fechaInicial'] = $fechaInicial;
            $rawdata['fechaFinal'] = $fechaFinal;
            $rawdata['Records'] = $resultset; // Compo obligatorio para JTable
            echo json_encode($rawdata);


            break;
        
        case 'update':
            $CodigoFac = trim($_POST['CodigoFac']);
            $CodProducto = trim($_POST['CodProducto']);
            $OrdenTrabajo = 0;
            $Comentario = trim($_POST['Comentario']);
            $CantitadProd = trim($_POST['CantitadProd']);
            $fechaINIup = trim($_POST['mantenimientoDate']);
            $horaInicio = trim($_POST['mantenimientoTimeINI']);
            $horaFin = trim($_POST['mantenimientoTimeFIN']);
            $TipoMantenimiento = trim($_POST['TipoMantenimiento']);
            $Tecnico = trim($_POST['Tecnico']);
            $Email = trim($_POST['Email']);

            // Combinacion de fecha y hora para registro en DB
            $fechaHoraINI = date('Ymd H:i:s', strtotime("$fechaINIup $horaInicio"));
            $fechaHoraFIN = date('Ymd H:i:s', strtotime("$fechaINIup $horaFin"));

            //Array que es enviado para el envio
            $data = array(
                'CodigoFac' => $CodigoFac,
                'CodProducto' => $CodProducto,
                'OrdenTrabajo' => $OrdenTrabajo,
                'CantitadProd' => $CantitadProd,
                'Comentario' => $Comentario,
                'fechaHoraINI' => $fechaHoraINI,
                'fechaHoraFIN' => $fechaHoraFIN,
                'TipoMantenimiento' => $TipoMantenimiento,
                'Tecnico' => $Tecnico,
                'Email' => $Email,
            );

            $respuesta = $ajax->updateAction($data);
            
            //Return result to jTable
            $jTableResult = array();
            $jTableResult['Result'] = "OK";
            $jTableResult['fechaHora'] = $fechaHoraINI;
            $jTableResult['arrayPost'] = $_POST;
            $jTableResult['registroAgregado'] = $respuesta;
            echo json_encode($jTableResult);
            break;
        
        case 'delete':
            $jTableResult = array();
            $jTableResult['Result'] = 'OK';
            echo json_encode($jTableResult);
            break;

        case 'omite':
            $CodigoFac = trim($_POST['CodigoFac']);
            $CodProducto = trim($_POST['CodProducto']);

            $data = array(
                'CodigoFac' => $CodigoFac,
                'CodProducto' => $CodProducto
            );

            $respuesta = $ajax->omitirAction($data);
                if($respuesta){
                $response = array('status' => 'OK'
                            , 'mensaje' => 'Realizado, equipo no requiere mantenimiento.');
                }else{
                $response = array('status' => 'FAIL'
                            , 'mensaje' => 'Ha ocurrido un problema al realizar la petición');
                }

                echo json_encode($response);
            break;
        
        case 'anular':
            /* Establece estado ANULADO (2) en la tabla mantenimientosEQ*/
            if (isset($_GET["codigoMNT"])) {
                $codigoMNT = $_GET["codigoMNT"];
            
                $respuesta = $ajax->anularAction($codigoMNT);
                if($respuesta){
                $response = array('status' => 'OK'
                            , 'mensaje' => 'Mantenimiento establecido como anulado');
                }else{
                $response = array('status' => 'FAIL'
                            , 'mensaje' => 'Ha ocurrido un problema al realizar la petición');
                }

            }else{
                $response = array('status' => 'FAIL'
                , 'mensaje' => 'No se han indicado codigo de mantenimiento');
            }
            
            echo json_encode($response);
            break;

        case 'aprobar':
        
                /* Establece estado APROBADO (1) en la tabla mantenimientosEQ*/
            if (isset($_GET["codigoMNT"])) {
                $codigoMNT = $_GET["codigoMNT"];
            
                $respuesta = $ajax->aprobarAction($codigoMNT);
                if($respuesta){
                $response = array('status' => 'OK'
                            , 'mensaje' => 'Mantenimiento establecido como finalizado');
                }else{
                $response = array('status' => 'FAIL'
                            , 'mensaje' => 'Ha ocurrido un problema al realizar la petición');
                }
            }else{
                $response = array('status' => 'FAIL'
                , 'mensaje' => 'No se han indicado codigo de mantenimiento');
            }
            
            echo json_encode($response);
            break;

        case 'listTipoMantenimientos':
            $resultset = $ajax->listTipoMantenimientosAction();

            $jTableResult['Result'] = "OK";
            $jTableResult['Options'] = $resultset;
            echo json_encode($jTableResult);
            break;

        case 'listTecnicos':
            $resultset = $ajax->lisTecnicosAction();

            $jTableResult['Result'] = "OK";
            $jTableResult['Options'] = $resultset;
            echo json_encode($jTableResult);
            break;

        case 'validaProducto':
            if (isset($_GET["codProducto"])) {
                $codProducto = $_GET["codProducto"];
            
                $respuesta = $ajax->validaProducto($codProducto);
                if($respuesta){
                $response = array('status' => 'OK', 
                                'mensaje' => 'El producto existe y esta en stock',
                                'producto' => $respuesta,
                                );
                }else{
                $response = array('status' => 'FAIL'
                            , 'mensaje' => 'El producto no existe o no posee stock');
                }
            }else{
                $response = array('status' => 'FAIL'
                , 'mensaje' => 'No se han indicado codigo de producto');
            }
            
            echo json_encode($response);        

            break;

        case 'updateOrden':
            if(isset($_GET["formData"]) && isset($_GET["productosArray"])){
                $dataDecode = json_decode($_GET["formData"]);
                $productosArray = json_decode($_GET["productosArray"]);

                $updateCorrecto = $ajax->updateMantenimiento($dataDecode, $productosArray);
            
                echo json_encode($updateCorrecto);
            
            }else{
                $rawdata = array('status' => 'FAIL', 'mensaje' =>'No existe formData.');
                echo json_encode($rawdata);
            }
            break;

        case 'extraAgendamiento':
            if(isset($_GET["formData"])){
                $formData = json_decode($_GET["formData"]);
                $respuesta = $ajax->insertExtraMantenimiento($formData);

                if ($respuesta) {
                $rawdata = array('status' => 'OK', 'mensaje' => 'Realizado, se ha registrado un nuevo mantenimiento');
                echo json_encode($rawdata);
                }else{
                $rawdata = array('status' => 'FAIL', 'mensaje' => 'El proceso no se completo, reintente; si el problema persiste informe a sistemas.');
                echo json_encode($rawdata);
                }
            

            }else{
                $rawdata = array('status' => 'FAIL', 'mensaje' =>'No existe formData requerido, informe a sistemas.');
                echo json_encode($rawdata);
            }    

            break;

        case 'validaOrdenFisica':
            
            if(isset($_GET["formData"])){
            $formData = json_decode($_GET["formData"]);
            $respuesta = $ajax->validaOrdenFisica($formData);
    
            if ($respuesta) {
                $rawdata = array('status' => 'OK', 'mensaje' => 'La orden fisica, es correcta.');
                echo json_encode($rawdata);
            }else{
                $rawdata = array('status' => 'FAIL', 'mensaje' => 'La orden fisica ya existe o no se ha ingresado, escriba una diferente.');
                echo json_encode($rawdata);
            }
            
    
            }else{
            $rawdata = array('status' => 'FAIL', 'mensaje' =>'No existe codigo de orden fisica en formDada requerida.');
            echo json_encode($rawdata);
            }

            break;

        case 'test':

            $respuesta = $ajax->sendEmail('gutiecuador@gmail.com','MNT0005656');
            
            //Return result to jTable
            $jTableResult = array();
            $jTableResult['Result'] = "OK";
            $jTableResult['registroAgregado'] = $respuesta;
            echo json_encode($jTableResult);
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


