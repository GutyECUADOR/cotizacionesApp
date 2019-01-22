<?php namespace models;
require_once 'conexion.php';

class MantenimientosClass {

    private $instanciaDB;
    private $db;
    
    public function __construct() {
        $this->instanciaDB = new \models\conexion();
        $this->db = $this->instanciaDB->getInstanciaCNX();
    }

    /*
        Recupera los registros de la tabla mantenimientosEQ en KAO_wssp
        - Indicar base de datos (empresa) de la cual realizar la consulta o retornara false de encontrar dicho nombre de DB
    */
    public function getMantenimientosAgendados($dataBaseName='KAO_wssp', $cantidad=1) {

        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX(); // Devolvemos instancia con la nueva DB seteada
        
        $codEmpresa = $this->getCodeDBByName($dataBaseName)['Codigo']; // Usado para filtro de resultados. codigo de la DB

        $primerDia = $this->first_month_day(); //$this->getPrimerDiaMes()['StartOfMonth'];
        $ultimoDia = $this->last_month_day(); //$this->getUltimoDiaMes()['EndOfMonth'];
        //Query de consulta con parametros para bindear si es necesario.
        $query = "
        SELECT TOP $cantidad
			Compra.ID as CodigoFac,
            Compra.FECHA as FechaCompra,
            Producto.Codigo as CodProducto,
            Producto.NOMBRE as Producto,
            Compra.CLIENTE as CodCliente,
            Cliente.NOMBRE as NombreCliente,
            Cliente.TELEFONO1 as Telefono,
            Cliente.DIRECCION1 as Direccion,
            Cliente.EMAIL as Email,
            Bodega.NOMBRE as Bodega,
            SBIO.Apellido + SBIO.Nombre as Encargado,
            WSSP.codMantenimiento,
            WSSP.codOrdenFisica,
            WSSP.fechaInicio,
            WSSP.fechaFin
            
        FROM dbo.VEN_MOV as Compra 
            INNER JOIN dbo.COB_CLIENTES as Cliente on Compra.CLIENTE COLLATE DATABASE_DEFAULT = Cliente.CODIGO 
            INNER JOIN dbo.INV_ARTICULOS as Producto on Compra.CODIGO COLLATE DATABASE_DEFAULT = Producto.CODIGO 
            INNER JOIN dbo.INV_BODEGAS as Bodega on Compra.BODEGA COLLATE DATABASE_DEFAULT = Bodega.CODIGO
            INNER JOIN KAO_wssp.dbo.mantenimientosEQ as WSSP on Compra.ID COLLATE DATABASE_DEFAULT = WSSP.codFactura 
            INNER JOIN KAO_wssp.dbo.mantenimientosEQ as WSSP2 on Compra.CODIGO COLLATE DATABASE_DEFAULT  = WSSP.codEquipo  
            INNER JOIN SBIOKAO.dbo.Empleados as SBIO on WSSP.responsable COLLATE DATABASE_DEFAULT = SBIO.Cedula


        WHERE 
            WSSP.codEmpresa = '$codEmpresa'
            AND WSSP.estado = '0'
            AND WSSP.fechaInicio BETWEEN '$primerDia' AND '$ultimoDia'
        GROUP BY 
            Producto.Codigo,
            Compra.ID,
            Compra.FECHA,
            Producto.NOMBRE,
            Compra.CLIENTE,
            Cliente.NOMBRE,
            Cliente.TELEFONO1,
            Cliente.DIRECCION1,
            Cliente.EMAIL,
            Bodega.NOMBRE,
            SBIO.Apellido,
            SBIO.Nombre,
            WSSP.codMantenimiento,
            WSSP.codOrdenFisica,
            WSSP.fechaInicio,
	        WSSP.fechaFin
            
        ORDER BY NombreCliente ASC 

        ";  // Final del Query SQL 

        $stmt = $this->db->prepare($query); 
    
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

    /*
        Recupera los registros de la tabla mantenimientosEQ en KAO_wssp
        - Indicar base de datos (empresa) de la cual realizar la consulta o retornara false de encontrar dicho nombre de DB
    */
    public function getMantenimientosHistorico($cantidad=100, $dataBaseName='KAO_wssp') {

        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX(); // Devolvemos instancia con la nueva DB seteada
        
        $codEmpresa = $this->getCodeDBByName($dataBaseName)['Codigo']; // Usado para filtro de resultados. codigo de la DB

        //Query de consulta con parametros para bindear si es necesario.
        $query = "
            SELECT 
            Compra.ID as CodigoFac,
            Mant.codMantenimiento as CodMNT,
            Mant.codOrdenFisica as CodOrdenFisica,
            Mant.codEquipo as CodProducto,
            Cliente.NOMBRE as Cliente,
            Mant.tipo as TipoMant,
            Mant.fechaInicio as FechaINI,
            CAB.NUMREL as NUMREL,
            Mant.estado as Estado
            
                    
        FROM
            dbo.VEN_CAB as Compra
            INNER JOIN KAO_wssp.dbo.mantenimientosEQ as Mant ON Mant.codFactura COLLATE Modern_Spanish_CI_AS = Compra.ID
            INNER JOIN dbo.COB_CLIENTES as Cliente on Compra.CLIENTE = Cliente.CODIGO 
            LEFT JOIN dbo.VEN_CAB as CAB on CAB.ID = Compra.ID
                        
            WHERE codEmpresa = '$codEmpresa'
                    
        ORDER BY CodMNT ASC
        ";  // Final del Query SQL 

        $stmt = $this->db->prepare($query); 
    
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

    /*
        Recupera el registro con el codigo indicado tabla mantenimientosEQ en KAO_wssp
    */
    public function getMantenimientoByCod($dataBaseName='KAO_wssp', $codMantenimiento) {

        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX(); // Devolvemos instancia con la nueva DB seteada
        
        $codEmpresa = $this->getCodeDBByName($dataBaseName)['Codigo']; // Usado para filtro de resultados. codigo de la DB

        //Query de consulta con parametros para bindear si es necesario.
        $query = "
        SELECT TOP 1
            Compra.ID as CodigoFac,
            Compra.FECHA as FechaCompra,
            Producto.Codigo as CodProducto,
            Producto.NOMBRE as Producto,
            Compra.CLIENTE as CodCliente,
            Cliente.NOMBRE as NombreCliente,
            Cliente.TELEFONO1 as Telefono,
            Cliente.DIRECCION1 as Direccion,
            Cliente.EMAIL as Email,
            Bodega.NOMBRE as Bodega,
            SBIO.Cedula as CIEncargado,
            SBIO.Nombre + SBIO.Apellido as Encargado,
            WSSP.codMantenimiento,
            WSSP.codOrdenFisica,
            WSSP.fechaInicio,
            WSSP.fechaFin,
            WSSP.comentario,
            WSSP.estado
            
        FROM dbo.VEN_MOV as Compra 
            INNER JOIN dbo.COB_CLIENTES as Cliente on Compra.CLIENTE COLLATE DATABASE_DEFAULT = Cliente.CODIGO 
            INNER JOIN dbo.INV_ARTICULOS as Producto on Compra.CODIGO COLLATE DATABASE_DEFAULT = Producto.CODIGO 
            INNER JOIN dbo.INV_BODEGAS as Bodega on Compra.BODEGA COLLATE DATABASE_DEFAULT = Bodega.CODIGO
            INNER JOIN KAO_wssp.dbo.mantenimientosEQ as WSSP on Compra.ID COLLATE DATABASE_DEFAULT = WSSP.codFactura
            INNER JOIN KAO_wssp.dbo.mantenimientosEQ as WSSP2 on Compra.CODIGO COLLATE DATABASE_DEFAULT  = WSSP.codEquipo  
            INNER JOIN SBIOKAO.dbo.Empleados as SBIO on WSSP.responsable COLLATE DATABASE_DEFAULT = SBIO.Cedula

        WHERE 
            WSSP.codEmpresa = '$codEmpresa'
            AND WSSP.codMantenimiento = '$codMantenimiento'

        GROUP BY 
            Producto.Codigo,
            Compra.ID,
            Compra.FECHA,
            Producto.NOMBRE,
            Compra.CLIENTE,
            Cliente.NOMBRE,
            Cliente.TELEFONO1,
            Cliente.DIRECCION1,
            Cliente.EMAIL,
            Bodega.NOMBRE,
            SBIO.Cedula,
            SBIO.Apellido,
            SBIO.Nombre,
            WSSP.codMantenimiento,
            WSSP.codOrdenFisica,
            WSSP.fechaInicio,
            WSSP.fechaFin,
            WSSP.comentario,
            WSSP.estado
        ORDER BY NombreCliente ASC 

        ";  // Final del Query SQL 

        $stmt = $this->db->prepare($query); 
    
            if($stmt->execute()){
                $resulset = $stmt->fetch( \PDO::FETCH_ASSOC );
            }else{
                $resulset = false;
            }
        return $resulset;  

   
    }

    /*
        Recupera el registro en VEN_MOD de los productos asignados al ID de factura y eligo de mantenimiento
    */
    public function getRepuestosOfMantenimientoByCod($codMantenimiento, $dataBaseName='KAO_wssp') {

        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX(); // Devolvemos instancia con la nueva DB seteada
        
        $codEmpresa = $this->getCodeDBByName($dataBaseName)['Codigo']; // Usado para filtro de resultados. codigo de la DB

        //Query de consulta con parametros para bindear si es necesario.
        $query = "
        
        SELECT
            VEN_CAB.ID,
            VEN_CAB.FECHA,
            VEN_CAB.CLIENTE as codCliente,
            CLIENTE.NOMBRE as facturadoA,
            CLIENTE.RUC,
            VEN_CAB.BODEGA as codBodega,
            BODEGA.NOMBRE  as bodegaName,
            VEN_CAB.TOTAL 
        FROM 
            dbo.VEN_CAB as VEN_CAB
            INNER JOIN dbo.COB_CLIENTES as CLIENTE on CLIENTE.CODIGO = VEN_CAB.CLIENTE
            INNER JOIN dbo.INV_BODEGAS as BODEGA on BODEGA.CODIGO = VEN_CAB.BODEGA

        WHERE 
            ID COLLATE Modern_Spanish_CI_AS IN (SELECT codVENCAB FROM KAO_wssp.dbo.mov_mantenimientosEQ WHERE codMantenimiento = '$codMantenimiento')
            ORDER BY VEN_CAB.ID
        ";  // Final del Query SQL 

        $stmt = $this->db->prepare($query); 
    
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

    
    /* Retorna el nombre array con la clave NameDatabase y Codigo para el nombre de la DB, para ser usada en la conexion*/ 
    public function getCodeDBByName($nombreDB){
        $query = "SELECT TOP 1 NameDatabase, Codigo FROM SBIOKAO.dbo.Empresas_WF WHERE NameDatabase = :NameDatabase"; 
        $stmt = $this->db->prepare($query); 
        $stmt->bindParam(':NameDatabase', $nombreDB); 
    
            if($stmt->execute()){
                $resulset = $stmt->fetch( \PDO::FETCH_ASSOC );
            }else{
                $resulset = false;
            }
        return $resulset;  
    }

    public function last_month_day() { 
        $month = date('m');
        $year = date('Y');
        $day = date("d", mktime(0,0,0, $month+1, 0, $year));
   
        return date('Ymd', mktime(0,0,0, $month, $day, $year));
    }
   
    /** Actual month first day **/
    public function first_month_day() {
        $month = date('m');
        $year = date('Y');
        return date('Ymd', mktime(0,0,0, $month, 1, $year));
    }

    public function getDescStatus($codigo) {
        
        switch ($codigo) {
            case 0:
            return 'Pendiente';
            break;
            
            case 1:
            return 'Finalizada';
            break;

            case 2:
            return 'Anulada';
            break;
            
            case 3:
            return 'Omitida';
            break;

            default:
            return 'No difinida';
            
            break;
        }
       
    }

    public function getColorBadge($codigo) {
        
        switch ($codigo) {
            case 0:
            return 'uk-badge-primary';
            break;
            
            case 1:
            return 'uk-badge-success';
            break;

            case 2:
            return 'uk-badge-danger';
            break;
            
            case 3:
            return 'uk-badge-warning';
            break;

            default:
            return '';
            
            break;
        }
       
    }

    /* public function getPrimerDiaMes(){
        $query = "SELECT FORMAT( DATEADD(month, DATEDIFF(month, 0, GETDATE()), 0), 'yyyyMMdd')  AS StartOfMonth"; 
        $stmt = $this->db->prepare($query); 
       
            if($stmt->execute()){
                $resulset = $stmt->fetch( \PDO::FETCH_ASSOC );
            }else{
                $resulset = false;
            }
        return $resulset;  
    }

    public function getUltimoDiaMes(){
        $query = "SELECT FORMAT( DATEADD(month, ((YEAR(GETDATE()) - 1900) * 12) + MONTH(GETDATE()), -1), 'yyyyMMdd', 'en-US' ) AS EndOfMonth"; 
        $stmt = $this->db->prepare($query); 
       
            if($stmt->execute()){
                $resulset = $stmt->fetch( \PDO::FETCH_ASSOC );
            }else{
                $resulset = false;
            }
        return $resulset;  
    } */


    public function getDataMantenimiento($dataBaseName='KAO_wssp', $codMantenimiento=null) {

        $this->instanciaDB->setDbname($dataBaseName); // Indicamos a que DB se realizará la consulta por defecto sera KAO_wssp
        $this->db = $this->instanciaDB->getInstanciaCNX(); // Devolvemos instancia con la nueva DB seteada
        
        $codEmpresa = $this->getCodeDBByName($dataBaseName)['Codigo']; // Usado para filtro de resultados. codigo de la DB

        //Query de consulta con parametros para bindear si es necesario.
        $query = "
        SELECT TOP 1
            Compra.ID as CodigoFac,
            WSSP.codMantenimiento as CodMNT,
            Compra.FECHA as FechaCompra,
            Producto.Codigo as CodProducto,
            Producto.NOMBRE as Producto,
            Compra.CLIENTE as CodCliente,
            Cliente.NOMBRE as NombreCliente,
            Cliente.TELEFONO1 as Telefono,
            Cliente.DIRECCION1 as Direccion,
            Cliente.EMAIL as Email,
            Bodega.NOMBRE as Bodega,
            SBIO.Apellido + SBIO.Nombre as Encargado,
            WSSP.fechaInicio,
            WSSP.fechaFin
            
        FROM dbo.VEN_MOV as Compra 
            INNER JOIN dbo.COB_CLIENTES as Cliente on Compra.CLIENTE = Cliente.CODIGO 
            INNER JOIN dbo.INV_ARTICULOS as Producto on Compra.CODIGO = Producto.CODIGO 
            INNER JOIN dbo.INV_BODEGAS as Bodega on Compra.BODEGA = Bodega.CODIGO
            INNER JOIN KAO_wssp.dbo.mantenimientosEQ as WSSP on Compra.ID COLLATE Modern_Spanish_CI_AS = WSSP.codFactura
            INNER JOIN SBIOKAO.dbo.Empleados as SBIO on WSSP.responsable = SBIO.Cedula
        
        WHERE 
            WSSP.estado = '0'
            AND WSSP.codMantenimiento = '$codMantenimiento'
        GROUP BY 
            Producto.Codigo,
            WSSP.codMantenimiento,
            Compra.ID,
            Compra.FECHA,
            Producto.NOMBRE,
            Compra.CLIENTE,
            Cliente.NOMBRE,
            Cliente.TELEFONO1,
            Cliente.DIRECCION1,
            Cliente.EMAIL,
            Bodega.NOMBRE,
            SBIO.Apellido,
            SBIO.Nombre,
            WSSP.fechaInicio,
            WSSP.fechaFin
	


        ";  // Final del Query SQL 

        $stmt = $this->db->prepare($query); 
    
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


    function getDateNow() { 
      return date('Y-m-d');
    }

    
}