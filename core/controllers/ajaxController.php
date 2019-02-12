<?php namespace controllers;

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use mPDF;

class ajaxController  {

    public $defaulDataBase;
    public $ajaxModel;

    public function __construct() {
        $this->defaulDataBase = (!isset($_SESSION["empresaAUTH"])) ? 'AGRICOLABAQUERO_V7' : $_SESSION["empresaAUTH"] ;
        $this->ajaxModel = new \models\ajaxModel();
        $this->ajaxModel->setDbname($this->defaulDataBase);
        $this->ajaxModel->conectarDB();
    }
  
    /* Retorna la respuesta del modelo ajax*/
    public function getInfoClienteController($RUC){
        $response = $this->ajaxModel->getInfoClienteModel($RUC);
        return $response;
    }

    /* Retorna la respuesta del modelo ajax*/
    public function getInfoEmpresaController(){
        $response = $this->ajaxModel->getAllInfoEmpresaModel();
        return $response;
    }

    /* Retorna la respuesta del modelo ajax*/
    public function getVEN_CABController($IDDocument){
        $response = $this->ajaxModel->getVENCABByID($IDDocument);
        return $response;
    }

    /* Retorna la respuesta del modelo ajax*/
    public function getInfoUsuarioController($codigoUsuario){
        $response = $this->ajaxModel->getInfoUsuarioModel($codigoUsuario);
        return $response;
    }

    /* Retorna la respuesta del modelo ajax*/
    public function getVEN_MOVController($IDDocument){
        $response = $this->ajaxModel->getVENMOVByID($IDDocument);
        return $response;
    }

    /* Retorna la respuesta del modelo ajax*/
    public function getAllClientesController($terminoBusqueda, $tipoBusqueda){
        $response = $this->ajaxModel->getAllClientesModel($terminoBusqueda, $tipoBusqueda);
        return $response;
    }

    /* Retorna la respuesta del modelo ajax*/
    public function getAllProductosController($terminoBusqueda, $tipoBusqueda){
        $response = $this->ajaxModel->getAllProductosModel($terminoBusqueda, $tipoBusqueda);
        return $response;
    }

    /* Retorna la respuesta del modelo ajax*/
    public function getInfoProductoController($codigoProducto, $clienteRUC){
        $tipoPrecio = $this->ajaxModel->getInfoClienteModel($clienteRUC)['TIPOPRECIO'];
        $response = $this->ajaxModel->getInfoProductoModel($codigoProducto, $tipoPrecio);
        return $response;
    }
    
    /*Envia informacion al modelo para actualizar, ejecuta insert en WINFENIX, VEN_CAB y VEN_MOV */
    public function insertCotizacion($formData){
        date_default_timezone_set('America/Lima');
        $VEN_CAB = new \models\venCabClass();
        $tipoDOC = 'PRO';

      
        if (!empty($formData)) {

            try {
               //Obtenemos informacion de la empresa
                $datosEmpresa =  $this->ajaxModel->getDatosEmpresaFromWINFENIX($this->defaulDataBase);
                $serieDocs =  $this->ajaxModel->getDatosDocumentsWINFENIXByTypo($tipoDOC, $this->defaulDataBase)['Serie'];
            
                // Informacion extra del cliente
                $datosCliente = $this->getInfoClienteController($formData->cliente->RUC);

                //Creamos nuevo codigo de VEN_CAB (secuencial)
                $newCodigo =  $this->ajaxModel->getNextNumDocWINFENIX($tipoDOC, $this->defaulDataBase); // Recuperamos secuencial de SP de Winfenix
                $newCodigoWith0 =  $this->ajaxModel->formatoNextNumDocWINFENIX($this->defaulDataBase, $newCodigo); // Asignamos formato con 0000X

                $new_cod_VENCAB = $datosEmpresa['Oficina'].$datosEmpresa['Ejercicio'].$tipoDOC.$newCodigoWith0;
                
                /* NOTA SE ESTABLECE DESCUENTO EN 0 TANTO PARA CABECERA COMO DETALLE */

                $VEN_CAB->setCliente($datosCliente['CODIGO']);
                $VEN_CAB->setPorcentDescuento(0);
                $VEN_CAB->setPcID(php_uname('n'));
                $VEN_CAB->setOficina($datosEmpresa['Oficina']);
                $VEN_CAB->setEjercicio($datosEmpresa['Ejercicio']);
                $VEN_CAB->setTipoDoc($tipoDOC);
                $VEN_CAB->setNumeroDoc($newCodigoWith0);
                $VEN_CAB->setFecha(date('Ymd'));
                
                $VEN_CAB->setBodega('B01');
                $VEN_CAB->setDivisa('DOL');
                $VEN_CAB->setProductos($formData->productos);
                $VEN_CAB->setSubtotal($VEN_CAB->calculaSubtotal());
                $VEN_CAB->setsubtotalBase0($VEN_CAB->calculaSubtotalOfItemsWithIVA0());
                $VEN_CAB->setImpuesto($VEN_CAB->calculaIVA());
                $VEN_CAB->setTotal($VEN_CAB->calculaTOTAL());
                $VEN_CAB->setFormaPago($formData->cliente->formaPago);
                $VEN_CAB->setSerie($serieDocs); 
                $VEN_CAB->setSecuencia('0'.$newCodigoWith0); //Agregar 0 extra segun winfenix
                $VEN_CAB->setObservacion('WebForms, ' . $formData->comentario);
                
                //Registro en VEN_CAB y MOV mantenimientosEQ
                $response_VEN_CAB =  $this->ajaxModel->insertVEN_CAB($VEN_CAB, $this->defaulDataBase);

                $arrayVEN_MOVinsets = array();

                if (!empty($VEN_CAB->getProductos())) {

                    foreach ($VEN_CAB->getProductos() as $producto) {
                        $VEN_MOV = new \models\venMovClass();
                      
                        $VEN_MOV->setCliente($datosCliente['CODIGO']);
                      
                        $VEN_MOV->setOficina($datosEmpresa['Oficina']);
                        $VEN_MOV->setEjercicio($datosEmpresa['Ejercicio']);
                        $VEN_MOV->setTipoDoc($tipoDOC);
                        $VEN_MOV->setNumeroDoc($newCodigoWith0);
                        $VEN_MOV->setFecha(date('Ymd h:i:s'));
                        $VEN_MOV->setBodega('B01');
                        $VEN_MOV->setCodProducto(strtoupper($producto->codigo));
                        $VEN_MOV->setCantidad($producto->cantidad);
                        $VEN_MOV->setPrecioProducto($producto->precio);
                        $VEN_MOV->setPorcentajeDescuentoProd(0);
                        $VEN_MOV->setTipoIVA('T00');
                        $VEN_MOV->setPorcentajeIVA(0);
                        $VEN_MOV->setPrecioTOTAL($VEN_MOV->calculaPrecioTOTAL());
                        $VEN_MOV->setObservacion('');
                        
                        $response_VEN_MOV =  $this->ajaxModel->insertVEN_MOV($VEN_MOV, $this->defaulDataBase);
                        
                        array_push($arrayVEN_MOVinsets, $response_VEN_MOV);

                    }
                }
            } catch (Exception $e) {
                return array('status' => 'ERROR', 
                    'mensaje'  => 'No se pudo completar la operacion'. $e->getMessage(),
                ); 
            }
            
            return array('status' => 'OK', 
                    'mensaje'  => 'Documento registrado.',
                    'new_cod_VENCAB' => $new_cod_VENCAB,
                    'newCodigoWith0' => $newCodigoWith0,
                    'response_VEN_CAB' => $response_VEN_CAB,
                    'arrayVEN_MOVinsets' => $arrayVEN_MOVinsets
                ); 

        }
       
        

        
        
    }

    public function generaReporte($IDDocument){

       $empresaData = $this->getInfoEmpresaController();
       $VEN_CAB = $this->getVEN_CABController($IDDocument);
       $VEN_MOV = $this->getVEN_MOVController($IDDocument);
        
       $html = '
            
            <div style="width: 100%;">
        
                <div style="float: right; width: 75%;">
                    <div id="informacion">
                        <h4>'.$empresaData["NomCia"].'</h4>
                        <h4>Direccion: '.$empresaData["DirCia"].'</h4>
                        <h4>Telefono: '.$empresaData["TelCia"].'</h4>
                        <h4>RUC: '.$empresaData["RucCia"].'</h4>
                        <h4>PROFORMA #  '.$VEN_CAB["ID"].' </h4>
                    </div>
                </div>
        
                <div id="logo" style="float: left; width: 20%;">
                    <img src="http://localhost/PHPProjects/cotizacionesApp/assets/img/logo.png" alt="Logo">
                </div>
        
            </div>
        
            <div id="infoCliente" class="rounded">
                <div class="cabecera"><b>Fecha:</b> '. date('Y-m-d').'</div>
                <div class="cabecera"><b>Cliente:</b> '.$VEN_CAB["NOMBRE"].'</div>
                <div class="cabecera"><b>Direccion: </b> '.$VEN_CAB["DIRECCION1"].' </div>
                <div class="cabecera"><b>Telefono: </b> '.$VEN_CAB["TELEFONO1"].' </div>
                <div class="cabecera"><b>Email: </b> '.$VEN_CAB["EMAIL"].' </div>
            </div>
        
            <table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
                <thead>
                    <tr>
                        <td width="5%">Item</td>
                        <td width="11%">Cod.</td>
                        <td width="7%">Cant.</td>
                        <td width="45%">Descripcion</td>
                        <td width="6%">IVA</td>
                        <td width="15%">P. Unit.</td>
                        <td width="10%">% Desc.</td>
                        <td width="10%">V. Desc.</td>
                        <td width="15%">P. Total</td>
                    </tr>
                </thead>
            <tbody>
        
            <!-- ITEMS HERE -->
            ';
                $cont = 1;
                foreach($VEN_MOV as $row){
                   
                    $html .= '
        
                    <tr>
                        <td align="center">'.$cont.'</td>
                        <td align="center">'.$row["CODIGO"].'</td>
                        <td align="center">'.$row["CANTIDAD"].'</td>
                        <td>'.$row["Nombre"].'</td>
                        <td>'.$row["tipoiva"].'</td>
                        <td>'.$row["PRECIO"].'</td>
                        <td>'.$row["DESCU"].'</td>
                        <td class="cost"> '.$row["DESCU"].' </td>
                        <td class="cost"> '.$row["PRECIOTOT"].'</td>
                    </tr>';
                    $cont++;
                    }
        
            $html .= ' 
            
        
            <!-- END ITEMS HERE -->
                <tr>
                <td class="blanktotal" colspan="6" rowspan="6"></td>
                <td class="totals" colspan="2">Imponible 0%:</td>
                <td class="totals cost">'.$VEN_CAB["BASCERO"].'</td>
                </tr>
        
            
                <tr>
                <td class="totals" colspan="2">Imponible 12%:</td>
                <td class="totals cost">'.$VEN_CAB["BASIVA"].'</td>
                </tr>
        
                <tr>
                <td class="totals" colspan="2">Subtotal:</td>
                <td class="totals cost">'.$VEN_CAB["SUBTOTAL"].'</td>
                </tr>
        
                <tr>
                <td class="totals" colspan="2">Base Imponible:</b></td>
                <td class="totals cost">0</td>
                </tr>
        
                <tr>
                <td class="totals" colspan="2">IVA:</td>
                <td class="totals cost">'.$VEN_CAB["IMPUESTO"].'</td>
                </tr>
        
                <tr>
                <td class="totals" colspan="2"><b>Total Pagar:</b></td>
                <td class="totals cost"><b>'.$VEN_CAB["TOTAL"].'</b></td>
                </tr>
        
            </tbody>
            </table>

            <div style="width: 100%;">
                <p id="observacion">Observacion: '.$VEN_CAB["OBSERVA"].'</p> 
            </div>
        
        ';

        //==============================================================
        //==============================================================
        //==============================================================

        /* require_once '../../../vendor/autoload.php'; */
        $mpdf = new mPDF('c','A4');

        // LOAD a stylesheet
        $stylesheet = file_get_contents('../../../assets/css/reportesStyles.css');
        
        $mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

        $mpdf->WriteHTML($html);
        
        return $mpdf->Output('doc.pdf', 'S');

        //==============================================================
        //==============================================================
        //==============================================================

    }

     /* ATECION LOS DATOS DE CUERPO Y LOGS DEBEN NO DEBEN SER MODIFICADOS ESTAS DIRECCIONADOS PARA AJAX */
     public function sendCotizacion($IDDocument){
       

        //$correoCliente = $email;
        $correoCliente = $this->getVEN_CABController($IDDocument)['EMAIL'];
        $arrayCorreos =  explode( ';', $correoCliente );

        //Correo de sender
        
        /* $smtpserver = 'mail.sudcompu.net';
        $userEmail = 'soporteweb@sudcompu.net';
        $pwdEmail = 'sw2019$sw$';  */

        $infoSender = $this->getInfoUsuarioController($_SESSION["usuarioRUC"]);
        $smtpserver = trim($infoSender['Smtp']);
        $userEmail = trim($infoSender['User_Mail']);
        $pwdEmail = trim($infoSender['Pwd_Mail']);

        $mail = new PHPMailer(true);  // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = false;                                 // Enable verbose debug output 0->off 2->debug
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = $smtpserver;  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = $userEmail;                 // SMTP username
            $mail->Password = $pwdEmail;                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom($userEmail, $userEmail);

            foreach ($arrayCorreos as $correo) {
                $mail->addAddress($correo, 'Cliente');     // Add a recipient
            }

           
           
            //Content
            $mail->CharSet = "UTF-8";
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Cotizacion #'.$IDDocument;
            $mail->Body    = 'Se adjunta documento requerido.';
        
            // Adjuntos
            $mail->addStringAttachment($this->generaReporte($IDDocument), 'cotizacion.pdf');

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
    
}
