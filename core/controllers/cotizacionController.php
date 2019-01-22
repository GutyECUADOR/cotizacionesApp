<?php namespace controllers;

class CotizacionController  {

    public $cotizacion;

    public function __construct() {
        $this->cotizacion = new \models\CotizacionClass;
    }
    
    public function getStatusDataBase (){
        $infoEmpresa = $this->cotizacion->getInfoEmpresa();
        if ($infoEmpresa) {
            return '
                <div class="alert alert-info alertExtra" role="alert">
                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                <span class="sr-only"></span>
                    Conexion a: <strong>'. $infoEmpresa['NomCia'] .' </strong>
                </div>';
        }else {
            return '
                <div class="alert alert-danger alertExtra" role="alert">
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                <span class="sr-only">Error:</span>
                    No se ha podido establecer conexion a la base de datos.
                </div>
            ';
             
        }
    }

    public function getBodegas(){
        $bodegas =  $this->cotizacion->getBodegasWF();
        return $bodegas;
    }

    public function getVendedores(){
        $bodegas =  $this->cotizacion->getVendedoresWF();
        return $bodegas;
    }
}