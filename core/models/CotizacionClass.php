<?php namespace models;

class CotizacionClass extends conexion {
    
    public function __construct(){
        parent::__construct();
    }

    public function getTop100InvArticulos() {
 
        //Query de consulta con parametros para bindear si es necesario.
        $query = "
            SELECT top 1* FROM INV_ARTICULOS
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
        
    }


    public function getInfoEmpresa(){

        $query = "SELECT TOP 1 * FROM dbo.DatosEmpresa";  // Final del Query SQL 

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

    public function getBodegasWF(){

        $query  = "SELECT CODIGO, NOMBRE FROM dbo.INV_BODEGAS";
      
        try{
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
        

        }catch(PDOException $exception){
            return array('status' => 'error', 'mensaje' => $exception->getMessage() );
        }
           

    }

    public function getVendedoresWF(){

        $query  = "SELECT CODIGO, NOMBRE FROM dbo.COB_VENDEDORES";
      
        try{
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
        

        }catch(PDOException $exception){
            return array('status' => 'error', 'mensaje' => $exception->getMessage() );
        }
           

    }
}
