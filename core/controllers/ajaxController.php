<?php namespace controllers;

class ajaxController  {

    public $defaulDataBase = "LICEO";
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
                        $VEN_MOV->setPorcentajeDescuentoProd($producto->descuento);
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

    
}
