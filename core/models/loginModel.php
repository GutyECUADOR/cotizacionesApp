<?php namespace models;

class loginModel extends conexion {
 
    public function __construct() {
        parent::__construct();
    }


    public function validaIngreso($arrayDatos, $dataBaseName='SBIOKAO'){

        $this->setDbname($dataBaseName);
        $this->conectarDB();
        
        $usuario = $arrayDatos['usuario'];
        $password = $arrayDatos['password'];

        $query = "SELECT TOP 1 * FROM dbo.USUARIOS WHERE Codigo = :cedula"; 
        $stmt = $this->instancia->prepare($query); 
        $stmt->bindParam(':cedula', $usuario); 
    
            if($stmt->execute()){
                $resulset = $stmt->fetch( \PDO::FETCH_ASSOC );
            }else{
                $resulset = false;
            }

        return $resulset;
           
        
    }


    public function validaMail($mail){
        $query = "SELECT ruc, nombre, email, password FROM tbl_cliente WHERE email = :mail"; 
        $stmt = $this->db->prepare($query); 
        $stmt->bindParam(':mail', $mail); 
        $stmt->execute(); 
       
        $resulset = $stmt->fetch();
        return $resulset;
    }


    /* Retorna el nombre array con la clave NameDatabase para el nombre de la DB, para ser usada en la conexion*/ 
    public function getDBNameByCodigo($codigoDB){
        $query = "SELECT TOP 1 NameDatabase, Codigo FROM SBIOKAO.dbo.Empresas_WF WHERE Codigo = :codigo"; 
        $stmt = $this->db->prepare($query); 
        $stmt->bindParam(':codigo', $codigoDB); 
       
            if($stmt->execute()){
                $resulset = $stmt->fetch( \PDO::FETCH_ASSOC );
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

    /* Retorna el nombre array con la clave Codigo, Nombre para el nombre de la DB, para ser usada en la conexion*/ 
    public function getAllDataBaseList(){
        $query = "SELECT Codigo, NameDataBase, Nombre FROM SBIOKAO.dbo.Empresas_WF"; 
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
    

    public function test(){
        return 'OK desde loginModel';
    }
}
