<?php namespace models;
require_once 'conexion.php';

class EstadisticasClass extends conexion {
    
    
    public function __construct(){
        parent::__construct();
    }

     /*
        Recupera los registros de la tabla mantenimientosEQ en KAO_wssp
        - Indicar base de datos (empresa) de la cual realizar la consulta o retornara false de encontrar dicho nombre de DB
    */
    public function getCountMantenimientos($dataBaseName='KAO_wssp') {
 
        //Query de consulta con parametros para bindear si es necesario.
        $query = "
            SELECT COUNT(*) as MantPendientes 
                FROM dbo.mantenimientosEQ as Mant 
            WHERE 
                codEmpresa = '004' AND estado = 0
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
}
