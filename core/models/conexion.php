<?php namespace models;
/**
 * @author Lic. Gutiérrez R. José
 */
/**
* Provee una conexion ODBC 
*
* Necesario colocar datos de conexion correctos en
* el archivo config_db.php
 * 
* Permite obtener datos como puerto, usuario, dbname charset, etc usados en la conexion
 * 
* Retorna object(ODBC) si hubo conexion exitosa
 * 
* Retorna FALSE si no se pudo realizar la conexión
* 
*/
class conexion {
    //Atributos
    private $driver, $host, $port, $user, $pass, $dbname, $charset ;
    public $instancia;
    //Constructor
    public function __construct() {
        
        /*CONEXION PARA DESAROLLO*/
        $this->driver = 'sqlsrv';
        $this->host = "S1-W202";
        $this->dbname = "LICEO-RES";
        $this->port = "1433";
        $this->user = "sfb";
        $this->pass = "Sud2017$";
        $this->charset = "utf8";

        

        $this->instancia = $this->getInstanciaCNX();
    }
    
    /** Retorna una instancia PDO**/
    public function conectarDB(){
        if ($this->driver=='sqlsrv' || $this->driver==NULL){ 
            try {
                $cnx = new \PDO("sqlsrv:Server=$this->host;Database=$this->dbname", $this->user, $this->pass);
                $cnx->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $this->instancia = $cnx;
                return $cnx;   
            } catch (Exception $ex) {
                return FALSE;
            }
        }else{
            return false;
        }
            
       
    }
    
    function getInstanciaCNX(){
        if ($this->instancia != NULL || $this->instancia == '' ){
            return $this->conectarDB();
        }else{
            return $this->instancia;
        }
    }

    function test(){
        return "Clase funcionando";
    }
    
    function getDriver() {
        return $this->driver;
    }

    function getHost() {
        return $this->host;
    }

    function getUser() {
        return $this->user;
    }
    function getPort() {
        return $this->port;
    }

    function setPort($port) {
        $this->port = $port;
    }

        function getPass() {
        return $this->pass;
    }

    function getDbname() {
        return $this->dbname;
    }

    function getCharset() {
        return $this->charset;
    }

    function setDriver($driver) {
        $this->driver = $driver;
    }

    function setHost($host) {
        $this->host = $host;
    }

    function setUser($user) {
        $this->user = $user;
    }

    function setPass($pass) {
        $this->pass = $pass;
    }

    function setDbname($dbname) {
        $this->dbname = $dbname;
    }

    function setCharset($charset) {
        $this->charset = $charset;
    }


    
}

