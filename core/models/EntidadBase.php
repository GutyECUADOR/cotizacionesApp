<?php 

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * EntidadBase crea una entidad generica para crear luego los modelos especificos para cada una de las tablas
 *
 * @author Lic. Gutiérrez R. José
 */
class EntidadBase {
    private $conexion;
    private $tablaDB;
    private $dataBase;
    
    public function __construct($tablaDB) {
        $this->tablaDB = (string)$tablaDB ;
        require_once './conexion.php';
        $this->conexion = new conexion();
        $this->dataBase =  $this->conexion->conectar();
        
    }
    
    // Retorna la instancia actual a la base de datos
    public function getConexion(){
        return $this->conexion;
    }
    
    // Retorna la instancia actual a la base de datos con referencia a la tabla indicada en el constructor
    public function getDataBase(){
        return$this->dataBase;
    }
    
    // Retorna un array de la consulta total de la tabla
    public function getAllElements(){
        $query = $this->dataBase->query("SELECT * FROM $this->tablaDB ORDER BY id ASC");
        while ($row = $query->fetchObject()){
            $resultSet[] = $row;
        }
        return $resultSet;
    }
    
    // Retorna un solo elemento del id ingresado
    public function getElementByID($id){
        $query = $this->dataBase->query("SELECT * FROM $this->tablaDB WHERE id=$id");
            if ($query->fetchObject()){
                $resultSet = $query->fetchObject();
                
            }
        return $resultSet;
    }
    
    public function getByColumna($columnaDB, $valorColumna){
        $query = $this->dataBase->query("SELECT * FROM $this->tablaDB WHERE $columnaDB='$valorColumna'");
        while ($row = $query->fetchObject()){
            $resultSet[] = $row;
        }
        return $resultSet;
    }
    
    public function deleteByID($id){
        $query = $this->dataBase->query("DELETE FROM $this->tablaDB WHERE id=$id");
        return $query;
    }
    
    public function deleteByColumna($columnaDB, $valorColumna){
        $query = $this->dataBase->query("DELETE FROM $this->tablaDB WHERE $columnaDB='$valorColumna'");
        return $query;
    }
    
}
    