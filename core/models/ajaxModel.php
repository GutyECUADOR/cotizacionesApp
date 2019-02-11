<?php namespace models;

/* LOS MODELOS del MVC retornaran unicamente arrays PHP sin serializar*/

class ajaxModel extends conexion  {
    
    public function __construct() {
        parent::__construct();
    }

    public function getAllInfoEmpresaModel() {

        //Query de consulta con parametros para bindear si es necesario.
        $query = " 
            SELECT NomCia, DirCia, RucCia, TelCia, Ciudad FROM dbo.DATOSEMPRESA    
        ";  // Final del Query SQL 

        try{
            $stmt = $this->instancia->prepare($query); 
    
                if($stmt->execute()){
                    $resulset = $stmt->fetch( \PDO::FETCH_ASSOC );
                    
                }else{
                    $resulset = false;
                }
            return $resulset;  

        }catch(PDOException $exception){
            return array('status' => 'error', 'mensaje' => $exception->getMessage() );
        }
   
    }

    public function getInfoClienteModel($RUC) {

        //Query de consulta con parametros para bindear si es necesario.
        $query = " 
            
        SELECT 
            CLIENTE.CODIGO, 
            RTRIM(CLIENTE.NOMBRE) as NOMBRE, 
            RTRIM(CLIENTE.EMPRESA) as EMPRESA, 
            RTRIM(CLIENTE.RUC) as RUC, 
            RTRIM(CLIENTE.EMAIL) as EMAIL, 
            RTRIM(CLIENTE.FECHAALTA) as FECHAALTA, 
            RTRIM(CLIENTE.DIRECCION1) as DIRECCION, 
            RTRIM(CLIENTE.TELEFONO1) as TELEFONO, 
            RTRIM(VENDEDOR.CODIGO) as VENDEDOR,
            RTRIM(VENDEDOR.NOMBRE) as VENDEDORNAME,
            RTRIM(CLIENTE.LIMITECRED) as LIMITECRED, 
            RTRIM(CLIENTE.FPAGO) as FPAGO, 
            RTRIM(CLIENTE.DIASPAGO) as DIASPAGO, 
            RTRIM(CLIENTE.TIPOPRECIO) as TIPOPRECIO 
        FROM 
            dbo.COB_CLIENTES as CLIENTE INNER JOIN
            dbo.COB_VENDEDORES as VENDEDOR ON VENDEDOR.CODIGO = CLIENTE.VENDEDOR
        WHERE 
            RUC='$RUC'";  // Final del Query SQL 

        try{
            $stmt = $this->instancia->prepare($query); 
    
                if($stmt->execute()){
                    $resulset = $stmt->fetch( \PDO::FETCH_ASSOC );
                    
                }else{
                    $resulset = false;
                }
            return $resulset;  

        }catch(PDOException $exception){
            return array('status' => 'error', 'mensaje' => $exception->getMessage() );
        }
   
    }


    public function getVENCABByID($IDDocument) {

     
        $query = " 
        SELECT 
            CLIENTE.NOMBRE,
            CLIENTE.RUC,
            CLIENTE.DIRECCION1,
            CLIENTE.TELEFONO1,
            CLIENTE.EMAIL,
            VEN_CAB.*
        FROM 
            dbo.VEN_CAB 
            INNER JOIN dbo.COB_CLIENTES as CLIENTE on CLIENTE.CODIGO = VEN_CAB.CLIENTE	
        WHERE ID='$IDDocument'
        ";  // Final del Query SQL 

        try{
            $stmt = $this->instancia->prepare($query); 
    
                if($stmt->execute()){
                    $resulset = $stmt->fetch( \PDO::FETCH_ASSOC );
                    
                }else{
                    $resulset = false;
                }
            return $resulset;  

        }catch(PDOException $exception){
            return array('status' => 'error', 'mensaje' => $exception->getMessage() );
        }
   
    }

    public function getVENMOVByID($IDDocument) {

       //Query de consulta con parametros para bindear si es necesario.
       $query = "
       SELECT
            ARTICULO.Nombre,
            VEN_MOV.*
            
        FROM 
            dbo.VEN_MOV
            INNER JOIN dbo.INV_ARTICULOS as ARTICULO ON ARTICULO.Codigo = VEN_MOV.CODIGO
        WHERE 
            ID = '$IDDocument'
       ";  // Final del Query SQL 

       $stmt = $this->instancia->prepare($query); 
   
       $arrayResultados = array();

           if($stmt->execute()){
               while ($row = $stmt->fetch( \PDO::FETCH_ASSOC )) {
                   array_push($arrayResultados, $row);
               }
               return $arrayResultados;
               
           }else{
               $resulset = false;
           }
       return $resulset; 
   
    }
    

    public function getInfoProductoModel($codigoProducto, $tipoPrecio='A') {

        $tipoPrec = 'Prec'.$tipoPrecio; // Determina el tipo de precio que se devolvera segun el cliente
        //Query de consulta con parametros para bindear si es necesario.
        $query = " 
            SELECT 
                RTRIM(INV_ARTICULOS.CODIGO) as CODIGO, 
                RTRIM(INV_ARTICULOS.NOMBRE) as NOMBRE, 
                RTRIM(INV_ARTICULOS.$tipoPrec) as PRECIO,
                RTRIM(INV_ARTICULOS.TipoIva) as TIPOIVA,
                RTRIM(IVA.VALOR) as VALORIVA,
                (select dbo.DIMESTOCKFIS('99','$codigoProducto','','B01')) AS STOCK
            FROM 
                dbo.INV_ARTICULOS
                INNER JOIN dbo.INV_IVA AS IVA on IVA.CODIGO = INV_ARTICULOS.TipoIva
            
            WHERE INV_ARTICULOS.Codigo='$codigoProducto'";  // Final del Query SQL 

        try{
            $stmt = $this->instancia->prepare($query); 
    
                if($stmt->execute()){
                    $resulset = $stmt->fetch( \PDO::FETCH_ASSOC );
                    
                }else{
                    $resulset = false;
                }
            return $resulset;  

        }catch(PDOException $exception){
            return array('status' => 'error', 'mensaje' => $exception->getMessage() );
        }
   
    }

    public function getAllClientesModel($terminoBusqueda, $tipoBusqueda='NOMBRE') {

        //Query de consulta con parametros para bindear si es necesario.
        $query = "SELECT TOP 10 RUC, NOMBRE, CODIGO, TIPOPRECIO FROM dbo.COB_CLIENTES WHERE $tipoBusqueda LIKE '$terminoBusqueda%'";  // Final del Query SQL 

        $stmt = $this->instancia->prepare($query); 
    
        $arrayResultados = array();

            if($stmt->execute()){
                while ($row = $stmt->fetch( \PDO::FETCH_ASSOC )) {
                    array_push($arrayResultados, $row);
                }
                return $arrayResultados;
                
            }else{
                $resulset = false;
            }
        return $resulset;  

   
    }

    public function getAllProductosModel($terminoBusqueda, $tipoBusqueda='NOMBRE') {

        //Query de consulta con parametros para bindear si es necesario.
        $query = "exec Sp_INVCONARTWAN ?,'','VEN','N','B01','','100','0','0','1','99','','','','','','','','','','1'";
        //$query = "SELECT top 10 Codigo, Nombre FROM INV_ARTICULOS WHERE $tipoBusqueda LIKE '$terminoBusqueda%'";  // Final del Query SQL 

        $stmt = $this->instancia->prepare($query); 
        $stmt->bindValue(1, $terminoBusqueda); 
        $stmt->execute();

        $arrayResultados = array();

            if($stmt->execute()){
                while ($row = $stmt->fetch( \PDO::FETCH_ASSOC )) {
                    array_push($arrayResultados, $row);
                }
                return $arrayResultados;
                
            }else{
                $resulset = false;
            }
        return $resulset;  

   
    }

    public function getArraysBodegas() {

        //Query de consulta con parametros para bindear si es necesario.
        $query = "SELECT CODIGO as Value, NOMBRE as DisplayText FROM INV_BODEGAS";  // Final del Query SQL 

        $stmt = $this->instancia->prepare($query); 
    
        $arrayResultados = array();

            if($stmt->execute()){
                while ($row = $stmt->fetch( \PDO::FETCH_ASSOC )) {
                    array_push($arrayResultados, $row);
                }
                return $arrayResultados;
                
            }else{
                $resulset = false;
            }
        return $resulset;  

   
    }

    public function getArrayProducto($codProducto) {

      
        //Query de consulta con parametros para bindear si es necesario.
        $query = "SELECT TOP 1 * FROM INV_ARTICULOS WHERE Codigo = '$codProducto'";  // Final del Query SQL 

        $stmt = $this->instancia->prepare($query); 
    
        $arrayResultados = array();

            if($stmt->execute()){
                $resulset = $stmt->fetch( \PDO::FETCH_ASSOC );
                
            }else{
                $resulset = false;
            }
        return $resulset;  

   
    }
    

    /*Retorna array con informacion de la empresa que se indique*/
    public function getDatosEmpresaFromWINFENIX ($dataBaseName='KAO_wssp'){
       
        $query = "SELECT NomCia, Oficina, Ejercicio FROM dbo.DatosEmpresa";
        $stmt = $this->instancia->prepare($query); 

        try{
            $stmt->execute();
            return $stmt->fetch( \PDO::FETCH_ASSOC );
        }catch(PDOException $exception){
            return array('status' => 'error', 'mensaje' => $exception->getMessage() );
        }

    }
    
    /*Retorna array asociativo con informacion del cliente que se indique*/
    public function getDatosClienteWINFENIXByRUC ($clienteRUC, $dataBaseName='KAO_wssp'){
        
        $query = "SELECT * FROM COB_CLIENTES WHERE RUC = '$clienteRUC'";
        $stmt = $this->instancia->prepare($query); 

        try{
            $stmt->execute();
            return $stmt->fetch( \PDO::FETCH_ASSOC );
        }catch(PDOException $exception){
            return array('status' => 'error', 'mensaje' => $exception->getMessage() );
        }
    }

    /*Retorna array asociativo con informacion del cliente que se indique*/
    public function getDatosDocumentsWINFENIXByTypo ($tipoDOC){
        
        $query = "SELECT CODIGO, NOMBRE, Serie FROM dbo.VEN_TIPOS WHERE CODIGO = '$tipoDOC'";
        $stmt = $this->instancia->prepare($query); 

        if($stmt->execute()){
            return $stmt->fetch( \PDO::FETCH_ASSOC );
        }else{
            return false;
        }
    }

    /*Retorna el siguiente secuencial del tipo de documento que se le indiqie - Winfenix*/
    public function getNextNumDocWINFENIX ($tipoDoc){
        
        $gestion = 'VEN';
        $ofi = '99';
        $eje = '';
        $tipo = $tipoDoc;
        $codigo = '';

        try{
            $stmt = $this->instancia->prepare("exec SP_CONTADOR ?, ?, ?, ?, ?"); 
            $stmt->bindValue(1, $gestion); 
            $stmt->bindValue(2, $ofi); 
            $stmt->bindValue(3, $eje); 
            $stmt->bindValue(4, $tipo); 
            $stmt->bindValue(5, $codigo); 
            $stmt->execute();
            $stmt->nextRowset();
            
            $newCodLimpio = $stmt->fetch(\PDO::FETCH_ASSOC);
            $newCodLimpio =  $newCodLimpio['NExtID'];
            
            return $newCodLimpio;

        }catch(PDOException $exception){
            return array('status' => 'error', 'mensaje' => $exception->getMessage() );
        }

        
    }

    /*Retorna el secuencial de WinFenix en formato 0000XXXX - Winfenix*/
    public function formatoNextNumDocWINFENIX ($dataBaseName='KAO_wssp', $secuencialWinfenix){
        
        $newCod = $this->instancia->query("select RIGHT('00000000' + Ltrim(Rtrim('$secuencialWinfenix')),8) as newcod");
        $codigoConFormato = $newCod->fetch(\PDO::FETCH_ASSOC);
        $codigoConFormato = $codigoConFormato['newcod'];
        return $codigoConFormato;
    }

    public function insertVEN_CAB($VEN_CAB_obj, $dataBaseName='KAO_wssp'){
       
        //$queryExample = "exec dbo.SP_VENGRACAB 'I','ADMINWSSP','TESTOK','99', '2014', 'C02', '00001721','','20181126','00054818','FAL','DOL','1.00','0.00','10','0.00','0.00','0.00','0.00','0.00','10','0.00','2','0.00','12','CON','0','1','0','S','0','1','0','0','','','999',' ',' ','PRUEBAS','001005','00002050','','','','','0.00','0.00','0.00','','','','','','','','','','0','P','','','','','','0','','','','','0','2','0.00','0.00','0.00','0','999999999 ','0','','','','','','EFE','','','','','20181126','',''";
        $VEN_CAB = new \models\venCabClass();
        $VEN_CAB = $VEN_CAB_obj;
        
        $query = "exec dbo.SP_VENGRACAB 'I','ADMINWSSP','$VEN_CAB->pcID','$VEN_CAB->oficina', '$VEN_CAB->ejercicio', '$VEN_CAB->tipoDoc', '$VEN_CAB->numeroDoc','','$VEN_CAB->fecha','$VEN_CAB->cliente','$VEN_CAB->bodega','$VEN_CAB->divisa','1.00','0.00','$VEN_CAB->subtotal','0.00','0.00','0.00','0.00','0.00','$VEN_CAB->subtotal','0.00','$VEN_CAB->impuesto','0.00','$VEN_CAB->total','CON','0','1','0','S','0','1','0','0','','','999',' ',' ','$VEN_CAB->observacion','$VEN_CAB->serie','$VEN_CAB->secuencia','','','','','0.00','0.00','0.00','','','','','','','','','','0','P','','','','','','0','','','','','0','2','0.00','0.00','0.00','0','999999999 ','0','','','','','','$VEN_CAB->formaPago','','','','','$VEN_CAB->fecha','',''";
        
        try{
            $rowsAfected = $this->instancia->exec($query);
           return array('status' => 'ok', 'mensaje' => $rowsAfected. ' fila afectada(s)' ); //true;
           
        }catch(PDOException $exception){
            return array('status' => 'error', 'mensaje' => $exception->getMessage() );
        }

        
    }

    public function insertVEN_MOV($VEN_MOV_obj, $dataBaseName='KAO_wssp'){
        
        $VEN_MOV = new \models\venMovClass();
        $VEN_MOV = $VEN_MOV_obj;

        $query = "exec dbo.SP_VENGRAMOV 'I','$VEN_MOV->oficina','$VEN_MOV->ejercicio','$VEN_MOV->tipoDoc','$VEN_MOV->numeroDoc','$VEN_MOV->fecha','$VEN_MOV->cliente','$VEN_MOV->bodega','S','0','0','$VEN_MOV->codProducto','UND','$VEN_MOV->cantidad','A','$VEN_MOV->precioProducto','$VEN_MOV->porcentajeDescuentoProd','$VEN_MOV->porcentajeIVA','$VEN_MOV->precioTOTAL','$VEN_MOV->fecha','','0.00','0.0000000','0','1.01.11','','1','1','104','0.0000','0.0000','0','','0','$VEN_MOV->tipoIVA' ";

        $stmt = $this->instancia->prepare($query); 
       
        try{
            $rowsAfected = $this->instancia->exec($query);
           return array('status' => 'ok', 'mensaje' => $rowsAfected. ' fila afectada(s)' ); //true;
           
        }catch(PDOException $exception){
            return array('status' => 'error', 'mensaje' => $exception->getMessage() );
        }


    }


    /*
       - Realiza conteo de mantenimientos pendientes 
    */
    public function getCountMantenimientos($codEmpresa, $dataBaseName='KAO_wssp') {
        
        //Query de consulta con parametros para bindear si es necesario.
        $query = "
            SELECT 
                MantPendientes = (SELECT COUNT (*) FROM dbo.mantenimientosEQ WHERE codEmpresa = '$codEmpresa' AND estado = 0),
                PorcentMantFinalizados = (SELECT COUNT( * ) FROM dbo.mantenimientosEQ  WHERE estado != 0 AND codEmpresa = '$codEmpresa') * 100 / (SELECT COUNT( * ) FROM dbo.mantenimientosEQ WHERE codEmpresa = '$codEmpresa') 
        
        ";  // Final del Query SQL 

        $stmt = $this->instancia->prepare($query); 
    
        $arrayResultados = array();

            if($stmt->execute()){

                while ($row = $stmt->fetch( \PDO::FETCH_ASSOC )) {
                    array_push($arrayResultados, $row);
                }
               
                return $arrayResultados;
            }else{
                return false;
                
            }
        return $resulset;  

   
    }


    /*
       - Retorna todos los mantenimientos de la tabla 
    */
    public function getHistorico($dataBaseName='KAO_wssp', $fechaINI, $fechaFIN, $codEmpresa, $tiposDocs) {
        
        $tiposDOC = $this->getFiltroTiposDoc($tiposDocs);
        //Query de consulta con parametros para bindear si es necesario.
        $query = "
            SELECT 
                Compra.ID as CodigoFac,
                Mant.codMantenimiento as CodMNT,
                Mant.codEquipo as CodProducto,
                Cliente.NOMBRE as Cliente,
                Mant.tipo as TipoMant,
                Mant.fechaInicio as FechaINI,
                Mant.estado as Estado
            
            FROM
                dbo.VEN_CAB as Compra
                INNER JOIN KAO_wssp.dbo.mantenimientosEQ as Mant ON Mant.codFactura COLLATE Modern_Spanish_CI_AS = Compra.ID
                INNER JOIN dbo.COB_CLIENTES as Cliente on Compra.CLIENTE = Cliente.CODIGO 
                
            WHERE 
                codEmpresa = '$codEmpresa'
                AND Mant.fechaInicio BETWEEN '$fechaINI' AND '$fechaFIN'
                ".$tiposDOC."
            ORDER BY CodMNT ASC
        ";  // Final del Query SQL 

        $stmt =$this->instancia->prepare($query); 
    
        $arrayResultados = array();

            if($stmt->execute()){

                while ($row = $stmt->fetch( \PDO::FETCH_ASSOC )) {
                    array_push($arrayResultados, $row);
                }
               
                return $arrayResultados;
            }else{
                return false;
                
            }
        return $resulset;  

   
    }

    private function getFiltroTiposDoc($tipoDOC){
        switch ($tipoDOC) {
            case 'ALL':
                return 'AND Mant.estado IN(0,1,2,3)';
                break;
            case 'PND':
                return 'AND Mant.estado IN(0)';
                break;

            case 'ANUL':
                return 'AND Mant.estado IN(2,3)';
                break;    
            
            default:
                return 'AND Mant.estado IN(0,1,2,3)';
                break;
        }
    }
}



   
    
