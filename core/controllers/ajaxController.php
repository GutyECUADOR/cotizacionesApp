<?php namespace controllers;

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use mPDF;

class ajaxController  {

    public $defaulDataBase;
    public $ajaxModel;

    public function __construct() {
        $this->defaulDataBase = (!isset($_SESSION["empresaAUTH"])) ? DEFAULT_DBName : $_SESSION["empresaAUTH"] ;
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
    public function getAllProductosWithExtraDesc($IDDocument){
        $response = $this->ajaxModel->getAllProductosWithExtraDescModel($IDDocument);
        return $response;
    }

    /* Retorna la respuesta del modelo ajax*/
    public function getVEN_CABController($IDDocument){
        $response = $this->ajaxModel->getVENCABByID($IDDocument);
        return $response;
    }

    /* Retorna la respuesta del modelo ajax*/
    public function getVEN_MOVController($IDDocument){
        $response = $this->ajaxModel->getVENMOVByID($IDDocument);
        return $response;
    }

    /* Retorna la respuesta del modelo ajax*/
    public function getInfoUsuarioController($codigoUsuario){
        $response = $this->ajaxModel->getInfoUsuarioModel($codigoUsuario);
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
    public function getAllDocumentosController($fechaINI, $fechaFIN, $stringBusqueda){
        $response = $this->ajaxModel->getAllDocumentosModel($fechaINI, $fechaFIN, $stringBusqueda);
        return $response;
    }

    /* Retorna la respuesta del modelo ajax*/
    public function getInfoProductoController($codigoProducto, $clienteRUC){
        $tipoPrecio = $this->ajaxModel->getInfoClienteModel($clienteRUC)['TIPOPRECIO'];
        $response = $this->ajaxModel->getInfoProductoModel($codigoProducto, $tipoPrecio);
        return $response;
    }


    /* Retorna la respuesta del modelo ajax*/
    public function insertExtraDataController($extraDataArray){
        $respuestas = array();
        foreach ($extraDataArray as $extraDataRow) {
            $response = $this->ajaxModel->insertExtraDataModel($extraDataRow);
            array_push($respuestas, $response);
        }
        
        return $respuestas;
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
                $VEN_CAB->setTipoPrecio($datosCliente['TIPOPRECIO']);
                $VEN_CAB->setVendedor($datosCliente['VENDEDOR']);
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
                        $VEN_MOV->setTipoPrecio($datosCliente['TIPOPRECIO']);
                        $VEN_MOV->setVendedor($datosCliente['VENDEDOR']);
                        $VEN_MOV->setNumeroDoc($newCodigoWith0);
                        $VEN_MOV->setFecha(date('Ymd h:i:s'));
                        $VEN_MOV->setBodega('B01');
                        $VEN_MOV->setCodProducto(strtoupper($producto->codigo));
                        $VEN_MOV->setCantidad($producto->cantidad);
                        $VEN_MOV->setPrecioProducto($producto->precio);
                        $VEN_MOV->setPorcentajeDescuentoProd(0);
                        $VEN_MOV->setTipoIVA('T00');
                        $VEN_MOV->setPorcentajeIVA($producto->valorIVA);
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

    public function generaReporte($IDDocument, $outputMode = 'S'){

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
                    <img src="../../../assets/img/logo.png" alt="Logo">
                </div>
        
            </div>
        
            <div id="infoCliente" class="rounded">
                <div class="cabecera"><b>Fecha:</b> '. date('Y-m-d').'</div>
                <div class="cabecera"><b>Cliente:</b> '.$VEN_CAB["NOMBRE"].'</div>
                <div class="cabecera"><b>Direccion: </b> '.$VEN_CAB["DIRECCION1"].' </div>
                <div class="cabecera"><b>Telefono: </b> '.$VEN_CAB["TELEFONO1"].' </div>
                <div class="cabecera"><b>Email: </b> '.$VEN_CAB["EMAIL"].' </div>
                <div class="cabecera"><b>Vendedor: </b> '.$VEN_CAB["VendedorName"].' </div>
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
        
            <div style="width: 100%;">
                <p>Imagenes del documento: '.$IDDocument.' </p>
                '. $this->getLinkImagesByDocument($IDDocument) .' 
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
        
        return $mpdf->Output('doc.pdf', $outputMode);

        //==============================================================
        //==============================================================
        //==============================================================

    }

    public function getLinkImagesByDocument($IDDocument){
        $files = glob(IMAGES_UPLOAD_DIR.'/'.$IDDocument.'_*');
        $file_list = '';
        foreach ($files as $file) {
            $file_list .= '<img src="'.$file.'" width="75%" alt="IMG DE PRODUCTO"></br>';      
        }
        return $file_list;
    }

    public function printRowsProductosHTML($arrayProductos){
        $rowsHTML = '';
        
        foreach ($arrayProductos as $producto) {
            
            $rowsHTML .= '
            <tr>
                <td class="customrowtable" width="30%">
                    <img src="'.trim($producto['imagen']).'" class="img-thumbnail" alt="'.trim($producto['imagen']).'">
                    <span class="text-center">'.trim($producto['Codigo']).'</span>
                </td>
                
                <td class="customrowtable text-center"><strong>'.trim($producto['Nombre']).'</strong>'.trim($producto['comentario']).'</td> 
            </tr>';

        }
        return $rowsHTML;
    }

    public function getNamesImagesByDocument($IDDocument){
        $files = glob(IMAGES_UPLOAD_DIR.'/'.$IDDocument.'_*');
        $file_list = '';
        foreach ($files as $file) {

            $row = '

            <tr>
                <td class="customrowtable" width="30%"><img src="cid:'.$file.'" class="img-thumbnail" alt="item"></td>
                
                <td class="customrowtable">Disponibles en catálogo bombas manuales, bombas hidroneumáticas, bombas modulares, grupos eléctricos de válvula manual o electroválvula, grupos hidroneumáticos o con motor a gasolina, sistemas de elevación sincronizados, grupos de salidas independientes, bombas neumáticas para pruebas hidrostáticas y grupos para llaves dinamométricas</td> 
            </tr>
            ';

            $file_list .= $row;      
        }
        return $file_list;
    }

    protected function getBodyHTMLofEmail($IDDocument, $customMesagge=''){

        $empresaData = $this->getInfoEmpresaController();
        $VEN_CAB = $this->getVEN_CABController($IDDocument);
        $arrayProductos = $this->getAllProductosWithExtraDesc($IDDocument);
        $rowsHTML = $this->printRowsProductosHTML($arrayProductos);

        if (empty($customMesagge)) {
            $customMesagge = BODY_EMAIL_TEXT;
        }

        return '

        <!doctype html>
        <html>
            <head>
            <meta name="viewport" content="width=device-width" />
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <title>Email</title>
            <style>
                /* -------------------------------------
                    GLOBAL RESETS
                ------------------------------------- */
                
                /*All the styling goes here*/
                
                img {
                border: none;
                margin-bottom: 10px;
                -ms-interpolation-mode: bicubic;
                max-width: 100%; 
                }
                
                body {
                background-color: #f6f6f6;
                font-family: sans-serif;
                -webkit-font-smoothing: antialiased;
                font-size: 14px;
                line-height: 1.4;
                margin: 0;
                padding: 0;
                -ms-text-size-adjust: 100%;
                -webkit-text-size-adjust: 100%; 
                }
                table {
                border-collapse: separate;
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                width: 100%; }
    
                table th {
                    background-color: rgba(0,0,0,.05);
                    border-top: 1px solid #dee2e6;
                    text-align: center !important;
                    padding: .50rem;
                }
    
                table td {
                    font-family: sans-serif;
                    font-size: 12px;
                    vertical-align: top;
                    text-align: justify;
                    padding: 5px;
                    
                }
    
                .customrowtable {
                    border-bottom: 1px solid #dee2e6;
                }
                /* -------------------------------------
                    BODY & CONTAINER
                ------------------------------------- */
                .body {
                background-color: #f6f6f6;
                width: 100%; 
                }
                /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
                .container {
                display: block;
                margin: 0 auto !important;
                /* makes it centered */
                max-width: 580px;
                padding: 10px;
                width: 580px; 
                }
                /* This should also be a block element, so that it will fill 100% of the .container */
                .content {
                box-sizing: border-box;
                display: block;
                margin: 0 auto;
                max-width: 580px;
                padding: 10px; 
                }
                /* -------------------------------------
                    HEADER, FOOTER, MAIN
                ------------------------------------- */
                .main {
                background: #ffffff;
                border-radius: 3px;
                width: 100%; 
                }
                .wrapper {
                box-sizing: border-box;
                padding: 20px; 
                }
                .content-block {
                padding-bottom: 10px;
                padding-top: 10px;
                }
                .footer {
                clear: both;
                margin-top: 10px;
                text-align: center;
                width: 100%; 
                }
                .footer td,
                .footer p,
                .footer span,
                .footer a {
                    color: #999999;
                    font-size: 12px;
                    text-align: center; 
                }
                /* -------------------------------------
                    TYPOGRAPHY
                ------------------------------------- */
                h1,
                h2,
                h3,
                h4 {
                color: #000000;
                font-family: sans-serif;
                font-weight: 400;
                line-height: 1.4;
                margin: 0;
                margin-bottom: 30px; 
                }
                h1 {
                font-size: 35px;
                font-weight: 300;
                text-align: center;
                text-transform: capitalize; 
                }
                p,
                ul,
                ol {
                font-family: sans-serif;
                font-size: 14px;
                font-weight: normal;
                margin: 0;
                margin-bottom: 15px; 
                }
                p li,
                ul li,
                ol li {
                    list-style-position: inside;
                    margin-left: 5px; 
                }
                a {
                color: #3498db;
                text-decoration: underline; 
                }
                /* -------------------------------------
                    BUTTONS
                ------------------------------------- */
                .btn {
                box-sizing: border-box;
                width: 100%; }
                .btn > tbody > tr > td {
                    padding-bottom: 15px; }
                .btn table {
                    width: auto; 
                }
                .btn table td {
                    background-color: #ffffff;
                    border-radius: 5px;
                    text-align: center; 
                }
                .btn a {
                    background-color: #ffffff;
                    border: solid 1px #3498db;
                    border-radius: 5px;
                    box-sizing: border-box;
                    color: #3498db;
                    cursor: pointer;
                    display: inline-block;
                    font-size: 14px;
                    font-weight: bold;
                    margin: 0;
                    padding: 12px 25px;
                    text-decoration: none;
                    text-transform: capitalize; 
                }
                .btn-primary table td {
                background-color: #3498db; 
                }
                .btn-primary a {
                background-color: #3498db;
                border-color: #3498db;
                color: #ffffff; 
                }
                /* -------------------------------------
                    OTHER STYLES THAT MIGHT BE USEFUL
                ------------------------------------- */
                .last {
                margin-bottom: 0; 
                }
                .first {
                margin-top: 0; 
                }
                .align-center {
                text-align: center; 
                }
                .align-right {
                text-align: right; 
                }
                .align-left {
                text-align: left; 
                }
                .clear {
                clear: both; 
                }
                .mt0 {
                margin-top: 0; 
                }
                .mb0 {
                margin-bottom: 0; 
                }
                .preheader {
                color: transparent;
                display: none;
                height: 0;
                max-height: 0;
                max-width: 0;
                opacity: 0;
                overflow: hidden;
                mso-hide: all;
                visibility: hidden;
                width: 0; 
                }
                .powered-by a {
                text-decoration: none; 
                }
                hr {
                border: 0;
                border-bottom: 1px solid #f6f6f6;
                margin: 20px 0; 
                }
                /* -------------------------------------
                    RESPONSIVE AND MOBILE FRIENDLY STYLES
                ------------------------------------- */
                @media only screen and (max-width: 620px) {
                table[class=body] h1 {
                    font-size: 28px !important;
                    margin-bottom: 10px !important; 
                }
                table[class=body] p,
                table[class=body] ul,
                table[class=body] ol,
                table[class=body] td,
                table[class=body] span,
                table[class=body] a {
                    font-size: 16px !important; 
                }
                table[class=body] .wrapper,
                table[class=body] .article {
                    padding: 10px !important; 
                }
                table[class=body] .content {
                    padding: 0 !important; 
                }
                table[class=body] .container {
                    padding: 0 !important;
                    width: 100% !important; 
                }
                table[class=body] .main {
                    border-left-width: 0 !important;
                    border-radius: 0 !important;
                    border-right-width: 0 !important; 
                }
                table[class=body] .btn table {
                    width: 100% !important; 
                }
                table[class=body] .btn a {
                    width: 100% !important; 
                }
                table[class=body] .img-responsive {
                    height: auto !important;
                    max-width: 100% !important;
                    width: auto !important; 
                }
                }
                /* -------------------------------------
                    PRESERVE THESE STYLES IN THE HEAD
                ------------------------------------- */
                @media all {
                .ExternalClass {
                    width: 100%; 
                }
                .ExternalClass,
                .ExternalClass p,
                .ExternalClass span,
                .ExternalClass font,
                .ExternalClass td,
                .ExternalClass div {
                    line-height: 100%; 
                }
                .apple-link a {
                    color: inherit !important;
                    font-family: inherit !important;
                    font-size: inherit !important;
                    font-weight: inherit !important;
                    line-height: inherit !important;
                    text-decoration: none !important; 
                }
                .btn-primary table td:hover {
                    background-color: #34495e !important; 
                }
                .btn-primary a:hover {
                    background-color: #34495e !important;
                    border-color: #34495e !important; 
                } 
                }
    
                .detailimg {
                    width:100%;
                    height:100%;
                }
            </style>
            </head>
            <body class="">
            <span class="preheader">Cotizacion</span>
            <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
                <tr>
                <td>&nbsp;</td>
                <td class="container">
                    <div class="content">
        
                    <!-- START CENTERED WHITE CONTAINER -->
                    <table role="presentation" class="main">
        
                        <!-- START MAIN CONTENT AREA -->
                        <tr>
                        <td class="wrapper">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td>
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align: center">
                                        <img src="http://www.agricolabaquero.com/img/resources/logo.png" alt="Logo"> </td>
                                        </tr>
                                    </tbody>
                                    </table>
                                
                                <p>Estimado, <b> '.$VEN_CAB["NOMBRE"].' </b></p>
                                <p>
                                    '. $customMesagge .'
                                </p>

                                    <table class="table table-striped" role="presentation" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <th class="text-center">Producto</th>
                                        <th class="text-center">Descripcion</th> 
                                    </tr>
    
                                   
                                    '.$rowsHTML.'
    
                                    
                                    </table>
                                
                                </br>
                                
                                <p>Muchas gracias por su confianza!</p>
                                </td>
                            </tr>
                            </table>
                        </td>
                        </tr>
        
                    <!-- END MAIN CONTENT AREA -->
                    </table>
        
                    <!-- START FOOTER -->
                    <div class="footer">
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="content-block">
                            <span class="apple-link">
                                Direccion: '.$empresaData["DirCia"].'
                            </span>
                            <br> Necesitas otro requerimiento? Conctacta con nuestro equipo de asesores. '.$empresaData["TelCia"].'</a>.
                            </td>

                           
                        </tr>
                        <tr>
                            <td class="content-block powered-by">
                            No responda a este mensaje, ha sido generado automaticamente.
                            </td>
                        </tr>
                        </table>
                    </div>
                    <!-- END FOOTER -->
        
                    <!-- END CENTERED WHITE CONTAINER -->
                    </div>
                </td>
                <td>&nbsp;</td>
                </tr>
            </table>
            
            </body>
        </html>
    
        
            ';

    }

     /* ATECION LOS DATOS DE CUERPO Y LOGS DEBEN NO DEBEN SER MODIFICADOS ESTAS DIRECCIONADOS PARA AJAX */
    public function sendCotizacion($IDDocument){
       

        //$correoCliente = $email;
        $correoCliente = $this->getVEN_CABController($IDDocument)['EMAIL'];
        $arrayCorreos =  explode( ';', $correoCliente );

        //Correo de sender
        
        $smtpserver = DEFAULT_SMTP;
        $userEmail = DEFAULT_SENDER_EMAIL;
        $pwdEmail = DEFAULT_EMAILPASS; 

        /* $infoSender = $this->getInfoUsuarioController($_SESSION["usuarioRUC"]);
        $smtpserver = trim($infoSender['Smtp']);
        $userEmail = trim($infoSender['User_Mail']);
        $pwdEmail = trim($infoSender['Pwd_Mail']); */

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

            $mail->AddCC(DEFAULT_EMAIL);
           
            //Content
            $mail->CharSet = "UTF-8";
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Cotizacion #'.$IDDocument;
            $mail->Body    = $this->getBodyHTMLofEmail($IDDocument);
        
            // Adjuntos
            $mail->addStringAttachment($this->generaReporte($IDDocument), 'cotizacion.pdf');

            $mail->send();
            $detalleMail = 'Correo ha sido enviado a : '. $correoCliente;
           
            $pcID = php_uname('n'); // Obtiene el nombre del PC



            $ip = 'ninguna';

                $log  = "User: ".$ip.' - '.date("F j, Y, g:i a").PHP_EOL.
                "PCid: ".$pcID.PHP_EOL.
                "Detail: ".$detalleMail.PHP_EOL.
                "-------------------------".PHP_EOL;
                //Save string to log, use FILE_APPEND to append.

                file_put_contents('../../../logs/logMailOK.txt', $log, FILE_APPEND );
            
            return array('status' => 'ok', 'mensaje' => $detalleMail ); 

        } catch (Exception $e) {


            $ip = 'ninguna';

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

    
     /* ATECION LOS DATOS DE CUERPO Y LOGS DEBEN NO DEBEN SER MODIFICADOS ESTAS DIRECCIONADOS PARA AJAX */
    public function sendCotizacionToEmails($arrayEmails, $IDDocument, $customMesagge){
       
        $arrayCorreos =  explode( ';', $arrayEmails );

        //Correo de sender
        
        $smtpserver = DEFAULT_SMTP;
        $userEmail = DEFAULT_SENDER_EMAIL;
        $pwdEmail = DEFAULT_EMAILPASS; 

        /* $infoSender = $this->getInfoUsuarioController($_SESSION["usuarioRUC"]);
        $smtpserver = trim($infoSender['Smtp']);
        $userEmail = trim($infoSender['User_Mail']);
        $pwdEmail = trim($infoSender['Pwd_Mail']); */

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

            $mail->AddCC(DEFAULT_EMAIL);
           
            //Content
            $mail->CharSet = "UTF-8";
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Cotizacion #'.$IDDocument;
            $mail->Body    = $this->getBodyHTMLofEmail($IDDocument, $customMesagge);
        
            //Imagenes adjuntas
           
            $files = glob(IMAGES_UPLOAD_DIR.'/'.$IDDocument.'_*');
         
            foreach ($files as $file) {
                $mail->AddEmbeddedImage($file, substr($file,36));
                
            }
           
            
            // Adjuntos
            $mail->addStringAttachment($this->generaReporte($IDDocument), 'cotizacion.pdf');

            $mail->send();
            $detalleMail = 'Correo ha sido enviado a : '. $arrayEmails;
           
            $pcID = php_uname('n'); // Obtiene el nombre del PC


            $ip = 'ninguna';

                $log  = "User: ".$ip.' - '.date("F j, Y, g:i a").PHP_EOL.
                "PCid: ".$pcID.PHP_EOL.
                "Detail: ".$detalleMail.PHP_EOL.
                "-------------------------".PHP_EOL;
                //Save string to log, use FILE_APPEND to append.

                file_put_contents('../../../logs/logMailOK.txt', $log, FILE_APPEND );
            
            return array('status' => 'ok', 'mensaje' => $detalleMail ); 

        } catch (Exception $e) {

           
            $ip = 'ninguna';

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
