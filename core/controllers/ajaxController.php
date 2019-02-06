<?php namespace controllers;

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use mPDF;

class ajaxController  {

    public $defaulDataBase;
    public $ajaxModel;

    public function __construct() {
        $this->defaulDataBase = (!isset($_SESSION["empresaAUTH"])) ? 'MODELO' : $_SESSION["empresaAUTH"] ;
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
                $VEN_CAB->setImpuesto($VEN_CAB->calculaIVA());
                $VEN_CAB->setTotal($VEN_CAB->calculaTOTAL());
                $VEN_CAB->setFormaPago($formData->cliente->formaPago);
                $VEN_CAB->setSerie($serieDocs); 
                $VEN_CAB->setSecuencia('0'.$newCodigoWith0); //Agregar 0 extra segun winfenix
                $VEN_CAB->setObservacion('WebForms');
                
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
                        $VEN_MOV->setTipoIVA('T12');
                        $VEN_MOV->setPorcentajeIVA(12);
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

    public function generaReporte(){
        
        $html = '
            
            <div style="width: 100%;">
            
                <div style="float: left; width: 75%;">
                    <div id="informacion">
                        <h4>AGRICOLA BAQUERO</h4>
                        <h4>Direccion Av. Alpahuasi y Ana Paredez de Alfaro</h4>
                        <h4>Telefono</h4>
                        <h4>RUC</h4>
                        <h4>PROFORMA # </h4>
                    </div>
                </div>

                <div id="logo" style="float: right; width: 20%;">
                    <img src="http://localhost/PHPProjects/cotizacionesApp/assets/img/logo.png" alt="Logo">
                </div>

            </div>

            <div id="infoCliente" class="rounded">
                <div class="cabecera">Fecha: </div>
                <div class="cabecera">Cliente: </div>
                <div class="cabecera">Direccion: </div>
                <div class="cabecera">Telefono: </div>
                <div class="cabecera">Email: </div>
            </div>

            <table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
                <thead>
                    <tr>
                        <td width="15%">Cod.</td>
                        <td width="10%">Cant.</td>
                        <td width="45%">Descripcion</td>
                        <td width="15%">Precio Unit.</td>
                        <td width="15%">Precio Total</td>
                    </tr>
                </thead>
            <tbody>

            <!-- ITEMS HERE -->
            
                <tr>
                    <td align="center">MF1234567</td>
                    <td align="center">0</td>
                    <td>Large pack Hoover bags</td>
                    <td class="cost">0</td>
                    <td class="cost">0</td>
                </tr>

            <!-- END ITEMS HERE -->
                <tr>
                <td class="blanktotal" colspan="3" rowspan="6"></td>
                <td class="totals">Imponible 0%:</td>
                <td class="totals cost">0</td>
                </tr>

                
                <tr>
                <td class="totals">Imponible 12%:</td>
                <td class="totals cost">0</td>
                </tr>

                <tr>
                <td class="totals">Subtotal:</td>
                <td class="totals cost">0</td>
                </tr>

                <tr>
                <td class="totals">Base Imponible:</b></td>
                <td class="totals cost">0</td>
                </tr>

                <tr>
                <td class="totals">IVA:</td>
                <td class="totals cost">0</td>
                </tr>

                <tr>
                <td class="totals"><b>Total Pagar:</b></td>
                <td class="totals cost"><b>0</b></td>
                </tr>

            </tbody>
            </table>

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
     public function sendEmail($email){
       
        $correoCliente = $email;


        $mail = new PHPMailer(true);  // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = false;                                 // Enable verbose debug output 0->off 2->debug
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'mail.sudcompu.net';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'soporteweb@sudcompu.net';                 // SMTP username
            $mail->Password = 'sw2019$sw$';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 25;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('soporteweb@sudcompu.net', 'Administrador');
            $mail->addAddress($correoCliente, 'Cliente KAO');     // Add a recipient
           
            //Content
            $mail->CharSet = "UTF-8";
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Pruebas de envio';
            $mail->Body    = 'Test de envio';
        
            // Adjuntos
            $mail->addStringAttachment($this->generaReporte(), 'doc.pdf');

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
