<?php namespace controllers;

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

class ajaxController  {

    public $defaulDataBase = "MODELO";
  
    /* Devuelve array en el formato requerido para el plugin JTable */
    public function getAllEquiposSinMantenimiento($fechaInicio, $fechaFinal, $startIndex, $pageSize) {

        $ajaxModel = new \models\ajaxModel();
        //Respuesta de informacion de VEN_MOV
        $dbEmpresa = (!isset($_SESSION["empresaAUTH"])) ? $this->defaulDataBase : $_SESSION["empresaAUTH"] ;
        $arrayEquipos = $ajaxModel->getArraysMantenimientosEQ($dbEmpresa, $pageSize, $fechaInicio, $fechaFinal);
        $arrayUTF8 = array();
        foreach ($arrayEquipos as $equipo) {
           
            /* Recuperamos filas para validar UTF-8 */
            $CodigoFac= iconv("iso-8859-1", "UTF-8",  $equipo['CodigoFac']);
            $TipoDocumento= iconv("iso-8859-1", "UTF-8",  $equipo['TipoDoc']);
            $FechaCompra= $equipo['FechaCompra'];
            $FechaCompraSPAN = date("Y-m-d", strtotime($FechaCompra));
            $NombreCliente= trim(iconv("iso-8859-1", "UTF-8", $equipo['NombreCliente']));
            $Telefono = trim(iconv("iso-8859-1", "UTF-8", $equipo['Telefono']));
            $CodProducto = trim(iconv("iso-8859-1", "UTF-8", $equipo['CodProducto']));
            $Producto = trim(iconv("iso-8859-1", "UTF-8", $equipo['Producto']));
            $DiasGarantia = trim(iconv("iso-8859-1", "UTF-8", $equipo['DiasGarantiarestantes']));
            $CantitadProd = trim(iconv("iso-8859-1", "UTF-8", $equipo['CantitadProd']));
            $Direccion = trim(iconv("iso-8859-1", "UTF-8", $equipo['Direccion']));
            $Email = trim(iconv("iso-8859-1", "UTF-8", $equipo['Email']));
            $NombreBodega = trim(iconv("iso-8859-1", "UTF-8", $equipo['NombreBodega']));

            $rowdata = ([
                "CodigoFac"=>$CodigoFac, 
                "TipoDocumento"=>$TipoDocumento, 
                "FechaCompra"=>$FechaCompraSPAN, 
                "CodProducto"=>$CodProducto, 
                "Producto"=>$Producto,
                "DiasGarantia"=>$DiasGarantia,
                "CantitadProd"=>$CantitadProd,
                "NombreCliente"=>$NombreCliente, 
                "Telefono"=>$Telefono, 
                "Direccion"=>$Direccion,
                "Email"=>$Email,
                "NombreBodega"=>$NombreBodega
            ]);

            array_push($arrayUTF8, $rowdata);
        }

        return $arrayUTF8;
    }

    /* Envia correo por PHPMailer */
    /* ATECION LOS DATOS DE CUERPO Y LOGS DEBEN NO DEBEN SER MODIFICADOS ESTAS DIRECCIONADOS PARA AJAX */
    public function sendEmail($email, $codMNT){
       
        //$correoCliente = 'gutiecuador@gmail.com';
        $correoCliente = $email;


        $mail = new PHPMailer(true);  // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = false;                                 // Enable verbose debug output 0->off 2->debug
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'mail.sudcompu.net';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'soporteweb@sudcompu.net';                 // SMTP username
            $mail->Password = '641429soporte';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 25;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('mantenimiento@kaosportcenter.com', 'Administrador KAO');
            $mail->addAddress($correoCliente, 'Cliente KAO');     // Add a recipient
            $mail->addAddress('soporteweb@sudcompu.net', 'Sistemas');
            $mail->addAddress('mantenimiento@kaosportcenter.com', 'Administrador KAO');
           
            //Content
            $mail->CharSet = "UTF-8";
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'KAO Sport - Mantenimiento de Equipos - ' . $codMNT;
            $mail->Body    = file_get_contents('../../../libs/PHPMailer/card_mantenimiento.php');
        
            $mail->send();
            $detalleMail = 'Correo ha sido enviado a : '. $correoCliente;
           
            $pcID = php_uname('n'); // Obtiene el nombre del PC


            function getIP(){
                if( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] )) $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                else if( isset( $_SERVER ['HTTP_VIA'] ))  $ip = $_SERVER['HTTP_VIA'];
                else if( isset( $_SERVER ['REMOTE_ADDR'] ))  $ip = $_SERVER['REMOTE_ADDR'];
                else $ip = null ;
                return $ip;
            }

            $ip = getIP();

                $log  = "User: ".$ip.' - '.date("F j, Y, g:i a").PHP_EOL.
                "PCid: ".$pcID.PHP_EOL.
                "Detail: ".$detalleMail.PHP_EOL.
                "-------------------------".PHP_EOL;
                //Save string to log, use FILE_APPEND to append.

                file_put_contents('../../../logs/logMailOK.txt', $log, FILE_APPEND );
            
            return array('status' => 'ok', 'mensaje' => $detalleMail ); 

        } catch (Exception $e) {

            function getIP(){
                if( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] )) $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                else if( isset( $_SERVER ['HTTP_VIA'] ))  $ip = $_SERVER['HTTP_VIA'];
                else if( isset( $_SERVER ['REMOTE_ADDR'] ))  $ip = $_SERVER['REMOTE_ADDR'];
                else $ip = null ;
                return $ip;
            }

            $ip = getIP();

                $pcID = php_uname('n'); // Obtiene el nombre del PC
                $log  = "User: ".$ip.' - '.date("F j, Y, g:i a").PHP_EOL.
                "PCid: ".$pcID.PHP_EOL.
                "Detail: ".$mail->ErrorInfo .' No se pudo enviar correo a: ' . $correoCliente . PHP_EOL.
                "-------------------------".PHP_EOL;
                //Save string to log, use FILE_APPEND to append.
                file_put_contents('../../../logs/logMailError.txt', $log, FILE_APPEND);
                $detalleMail = 'Error al enviar el correo. Mailer Error: '. $mail->ErrorInfo;
                return array('status' => 'false', 'mensaje' => $detalleMail ); 
            
        }

    }

    /* Realiza peticion al modelo para agregar registro a la tabla mantenimientosEQ*/
    public function agendarMantenimiento($data){
        $ajaxModel = new \models\ajaxModel();
        $response = $ajaxModel->insertNewMantenimiento($data);
        $mailCliente = trim($data['Email']);
        $newCod = $response['newCod']; 
        $statusOK = $response['status']; // Comprobamos que la respuesta desde el modelo de OK
        $statusMail = array('status' => 'ok', 'mensaje' => 'No se definio correo' );
        if ($statusOK == 'ok' && !empty($mailCliente)) {
            $statusMail = $this->sendEmail($mailCliente, $newCod);
        }
        
        return array('status' => 'ok', 'mensaje' => 'Registro exitoso, codigo de mantenimiento: '. $newCod.'. ', 'email' => $statusMail);
    }

    /* Realiza peticion al modelo para agregar registro a la tabla mantenimientosEQ*/
    public function agendarExtraMantenimiento($formData){
        $ajaxModel = new \models\ajaxModel();
        $mantenimiento = new \models\MantenimientosClass();
        $dataBaseName = (!isset($_SESSION["empresaAUTH"])) ? $this->defaulDataBase : $_SESSION["empresaAUTH"] ;
        $codMNT = $formData->codMantenimientoModal;
        $fechaMNT = $formData->uk_dp_proxMant;
       
        $arrayMantenimiento  = $mantenimiento->getMantenimientoByCod($dataBaseName, $codMNT);
        
        $fechaHoraINI = date('Ymd H:i:s', strtotime("$fechaMNT"));
        $fechaHoraFIN = date('Ymd H:i:s', strtotime("$fechaMNT"));
        
            $data = array(
            'CodigoFac' => $arrayMantenimiento['CodigoFac'],
            'CodProducto' => $arrayMantenimiento['CodProducto'],
            'OrdenTrabajo' => NULL,
            'CantitadProd' => 1,
            'Comentario' => 'Mantenimiento programado, segun mantenimiento: '.$codMNT,
            'fechaHoraINI' => $fechaHoraINI,
            'fechaHoraFIN' => $fechaHoraFIN,
            'TipoMantenimiento' => 'MNO',
            'Tecnico' => $arrayMantenimiento['CIEncargado']
            );

        $response = $ajaxModel->insertNewMantenimiento($data);
        return $response;
    }

    /* Verifica si existe el codigo de ordenfisica en mantenimientosEQ*/
    public function isValidOrdenFisica ($formData){
        $ajaxModel = new \models\ajaxModel();
        $codEmpresa = $_SESSION["codEmpresaAUTH"];
        $response = $ajaxModel->isDisponibleOrdenFisica($formData, $codEmpresa);

        if ($response <= 0) {
            return true;
        }else{
            return false;
        }

    }

    /* Realiza peticion al modelo para agregar registro a la tabla mantenimientosEQ*/
     public function anularMantenimiento($data){
        $ajaxModel = new \models\ajaxModel();
        $response = $ajaxModel->anulaMantenimientoByCod($data);
        return $response;
    }

    /* Realiza peticion al modelo para agregar registro a la tabla mantenimientosEQ*/
    public function aprobarMantenimiento($data){
        $ajaxModel = new \models\ajaxModel();
        $response = $ajaxModel->aprobarMantenimientoByCod($data);
        return $response;
    }


    /* Realiza peticion al modelo para setear estado 3 al registro de la tabla mantenimientosEQ*/
    public function omitirMantenimiento($data){
        $ajaxModel = new \models\ajaxModel();
        $response = $ajaxModel->omitirMantenimientoByCod($data);
        return $response;
    }

    /* Retorna la respuesta del modelo ajax*/
    public function getTiposMantenimientos(){
        $ajaxModel = new \models\ajaxModel();
        $response = $ajaxModel->getArraysTiposDOCMantenimientos();
        return $response;
    }
    
    /* Retorna la respuesta del modelo ajax*/
    public function getAllTecnicos(){
        $ajaxModel = new \models\ajaxModel();
        $response = $ajaxModel->getArraysTecnicos('SBIOKAO');
        return $response;
    }

    /* Retorna la respuesta del modelo ajax*/
    public function getAllBodegas(){
        $ajaxModel = new \models\ajaxModel();
        $dbEmpresa = (!isset($_SESSION["empresaAUTH"])) ? $this->defaulDataBase : $_SESSION["empresaAUTH"] ;
        $response = $ajaxModel->getArraysBodegas($dbEmpresa);
        return $response;
    }

    /* Retorna la respuesta del modelo ajax*/
    public function getProductoByCod($codProducto){
        $ajaxModel = new \models\ajaxModel();
        $dbEmpresa = (!isset($_SESSION["empresaAUTH"])) ? $this->defaulDataBase : $_SESSION["empresaAUTH"] ;
        $response = $ajaxModel->getArrayProducto($dbEmpresa, $codProducto);
        return $response;
    }

    /*Envia informacion al modelo para actualizar, ejecuta insert en WINFENIX, VEN_CAB y VEN_MOV */
    public function updateMantenimientoByCod($formData, $productosArray){
        date_default_timezone_set('America/Lima');
        $ajaxModel = new \models\ajaxModel();
        $VEN_CAB = new \models\venCabClass();
        $dbEmpresa = (!isset($_SESSION["empresaAUTH"])) ? $this->defaulDataBase : $_SESSION["empresaAUTH"] ;
        $tipoDOC = 'C02';
        //Actualizacion a WSSP - MantenimientosEQ
        $response_WSSP = $ajaxModel->updateMantenimientoEQ($formData);
       
        if (!empty($productosArray)) {
            
            //Obtenemos informacion de la empresa
            $datosEmpresa = $ajaxModel->getDatosEmpresaFromWINFENIX($dbEmpresa);
            $codIMPORTKAO = trim($ajaxModel->getDatosClienteWINFENIXByRUC('1790417581001', $dbEmpresa)['CODIGO']);
            $serieDocs = $ajaxModel->getDatosDocumentsWINFENIXByTypo($tipoDOC, $dbEmpresa)['Serie'];
            
            if (!$codIMPORTKAO || is_null($codIMPORTKAO)) {
                $codIMPORTKAO = '9999999999999';
            }

            //Crea mos nuevo codigo de VEN_CAB (secuencial)
            $newCodigo = $ajaxModel->getNextNumDocWINFENIX($tipoDOC, $dbEmpresa); // Recuperamos secuencial de SP de Winfenix
            $newCodigoWith0 = $ajaxModel->formatoNextNumDocWINFENIX($dbEmpresa, $newCodigo); // Asignamos formato con 0000X

            $new_cod_VENCAB = $datosEmpresa['Oficina'].$datosEmpresa['Ejercicio'].$tipoDOC.$newCodigoWith0;
            
            //Creacion y asignacion de valores a VEN_CAB
            if ($formData->product_edit_facturadoa == 1) {
                $VEN_CAB->setCliente($formData->codCliente);
                $VEN_CAB->setPorcentDescuento(0);
            }else{
                $VEN_CAB->setCliente($codIMPORTKAO);
                $VEN_CAB->setPorcentDescuento(90);
            }

            $VEN_CAB->setPcID(php_uname('n'));
            $VEN_CAB->setOficina($datosEmpresa['Oficina']);
            $VEN_CAB->setEjercicio($datosEmpresa['Ejercicio']);
            $VEN_CAB->setTipoDoc($tipoDOC);
            $VEN_CAB->setNumeroDoc($newCodigoWith0);
            $VEN_CAB->setFecha(date('Ymd'));
            
            $VEN_CAB->setBodega($formData->product_edit_bodega);
            $VEN_CAB->setDivisa('DOL');
            $VEN_CAB->setProductos($productosArray);
            $VEN_CAB->setSubtotal($VEN_CAB->calculaSubtotal());
            $VEN_CAB->setImpuesto($VEN_CAB->calculaIVA());
            $VEN_CAB->setTotal($VEN_CAB->calculaTOTAL());
            $VEN_CAB->setFormaPago('EFE');
            $VEN_CAB->setSerie($serieDocs); 
            $VEN_CAB->setSecuencia('0'.$newCodigoWith0); //Agregar 0 extra segun winfenix
            $VEN_CAB->setObservacion('MantenimientosApp #'.$formData->codMantenimiento);
            
             //Registro en VEN_CAB y MOV mantenimientosEQ
            $response_VEN_CAB = $ajaxModel->insertVEN_CAB($VEN_CAB, $dbEmpresa);

            $response_MOV_MNT = $ajaxModel->insertMOVMantenimientoEQ($formData, $new_cod_VENCAB);
            
            $arrayVEN_MOVinsets = array();

                foreach ($VEN_CAB->getProductos() as $producto) {
                    $VEN_MOV = new \models\venMovClass();
                    if ($formData->product_edit_facturadoa == 1) {
                        $VEN_MOV->setCliente($formData->codCliente);
                        
                    }else{
                        $VEN_MOV->setCliente($codIMPORTKAO);
                    }
    
                
                    $VEN_MOV->setOficina($datosEmpresa['Oficina']);
                    $VEN_MOV->setEjercicio($datosEmpresa['Ejercicio']);
                    $VEN_MOV->setTipoDoc($tipoDOC);
                    $VEN_MOV->setNumeroDoc($newCodigoWith0);
                    $VEN_MOV->setFecha(date('Ymd h:i:s'));
                    $VEN_MOV->setBodega($formData->product_edit_bodega);
                    $VEN_MOV->setCodProducto(strtoupper($producto->codigo));
                    $VEN_MOV->setCantidad($producto->cantidad);
                    $VEN_MOV->setPrecioProducto($producto->precio);
                    $VEN_MOV->setPorcentajeDescuentoProd($producto->descuento);
                    $VEN_MOV->setTipoIVA('T12');
                    $VEN_MOV->setPorcentajeIVA(12);
                    $VEN_MOV->setPrecioTOTAL($VEN_MOV->calculaPrecioTOTAL());
                    $VEN_MOV->setObservacion('');
                    
                    $response_VEN_MOV = $ajaxModel->insertVEN_MOV($VEN_MOV, $dbEmpresa);
                    
                    array_push($arrayVEN_MOVinsets, $response_VEN_MOV);
                    
                }
         
            $response_Aprobada = $this->aprobarMantenimiento($formData->codMantenimiento);
            
            return array('status' => 'OK', 
                    'mensaje'  => 'Mantenimiento Actualizado, y se registraron los repuestos.',
                    'newCodigoWith0' => $newCodigoWith0,
                    'response_WSSP' => $response_WSSP,
                    'response_VEN_CAB' => false,
                    'response_MOV_MNT' => false,
                    'arrayVEN_MOVinsets' => $arrayVEN_MOVinsets
                ); 

        }else {
            return array('status' => 'OK', 'mensaje'  => 'Actualizado, no se ingresaron repuestos, el mantenimiento continuara abierto ' ,'responses' => $response_WSSP); 
        }
       
        

        
        
    }

    /* AJAX ESTADISTICAS - Get conteo de mantenimientos */
    public function getCountMantenimientosController($codEmpresa){
        $ajaxModel = new \models\ajaxModel();
        $dbEmpresa = (!isset($_SESSION["empresaAUTH"])) ? $this->defaulDataBase : $_SESSION["empresaAUTH"] ;
        $response = $ajaxModel->getCountMantenimientos($codEmpresa);
        return $response;
    }

    /* AJAX ESTADISTICAS - Get conteo de mantenimientos */
    public function getHistoricoController($fechaINI, $fechaFIN, $codEmpresa, $tiposDocs){
        $ajaxModel = new \models\ajaxModel();
        $dbEmpresa = (!isset($_SESSION["empresaAUTH"])) ? $this->defaulDataBase : $_SESSION["empresaAUTH"] ;
       
        $response = $ajaxModel->getHistorico($dbEmpresa, $fechaINI, $fechaFIN, $codEmpresa, $tiposDocs);
        return $response;
    }
    
    
}
