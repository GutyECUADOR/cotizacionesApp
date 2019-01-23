<?php namespace controllers;

class ajaxController  {

    public $defaulDataBase = "MODELO";
    public $dbEmpresa;
    public $ajaxModel;

    public function __construct() {
      $this->ajaxModel = new \models\ajaxModel();
    }
  
    /* Retorna la respuesta del modelo ajax*/
    public function getInfoClienteController($RUC){
        $response = $this->ajaxModel->getInfoClienteModel($RUC);
        return $response;
    }

    /* Retorna la respuesta del modelo ajax*/
    public function getInfoProductoController($codigoProducto){
        $response = $this->ajaxModel->getInfoProductoModel($codigoProducto);
        return $response;
    }
    
  
    /*Envia informacion al modelo para actualizar, ejecuta insert en WINFENIX, VEN_CAB y VEN_MOV */
    public function insertCotizacion($formData, $productosArray){
        date_default_timezone_set('America/Lima');
        $ajaxModel = new \models\ajaxModel();
        $VEN_CAB = new \models\venCabClass();
        $tipoDOC = 'C02';
       
        if (!empty($productosArray)) {
            
            //Obtenemos informacion de la empresa
            $datosEmpresa = $ajaxModel->getDatosEmpresaFromWINFENIX($dbEmpresa);
            $serieDocs = $ajaxModel->getDatosDocumentsWINFENIXByTypo($tipoDOC, $dbEmpresa)['Serie'];
           
            //Creamos nuevo codigo de VEN_CAB (secuencial)
            $newCodigo = $ajaxModel->getNextNumDocWINFENIX($tipoDOC, $dbEmpresa); // Recuperamos secuencial de SP de Winfenix
            $newCodigoWith0 = $ajaxModel->formatoNextNumDocWINFENIX($dbEmpresa, $newCodigo); // Asignamos formato con 0000X

            $new_cod_VENCAB = $datosEmpresa['Oficina'].$datosEmpresa['Ejercicio'].$tipoDOC.$newCodigoWith0;
            
            
            $VEN_CAB->setCliente($formData->codCliente);
            $VEN_CAB->setPorcentDescuento(0);
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
            $VEN_CAB->setObservacion('WebForms');
            
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
