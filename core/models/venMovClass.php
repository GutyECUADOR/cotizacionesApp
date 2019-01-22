<?php namespace models;

class venMovClass {
    public $oficina;
    public $ejercicio;
    public $tipoDoc;
    public $numeroDoc;
    public $fecha;
    public $cliente;
    public $bodega;
    public $codProducto;
    public $cantidad;
    public $precioProducto;
    public $porcentajeDescuentoProd;
    public $tipoIVA;
    public $porcentajeIVA;
    public $precioTOTAL;
    public $observacion;
            
    function __construct() {
        
    }
    
    function calculaPrecioTOTAL(){
        $precioTotal = $this->cantidad * $this->precioProducto;
        $descuento = ($precioTotal * $this->porcentajeDescuentoProd)/100;
        $precioTotal = $precioTotal - $descuento;
        
        $this->precioTOTAL = $precioTotal;
        return $precioTotal;
    }
    
    function getOficina() {
        return $this->oficina;
    }

    function getEjercicio() {
        return $this->ejercicio;
    }

    function getTipoDoc() {
        return $this->tipoDoc;
    }

    function getNumeroDoc() {
        return $this->numeroDoc;
    }

    function getFecha() {
        return $this->fecha;
    }

    function getCliente() {
        return $this->cliente;
    }

    function getBodega() {
        return $this->bodega;
    }

    function getCodProducto() {
        return $this->codProducto;
    }

    function getPrecioProducto() {
        return $this->precioProducto;
    }

    function getPorcentajeDescuentoProd() {
        return $this->porcentajeDescuentoProd;
    }

    function getTipoIVA() {
        return $this->tipoIVA;
    }

    function getPorcentajeIVA() {
        return $this->porcentajeIVA;
    }

    function getPrecioTOTAL() {
        return $this->precioTOTAL;
    }

    function setOficina($oficina) {
        $this->oficina = $oficina;
    }

    function setEjercicio($ejercicio) {
        $this->ejercicio = $ejercicio;
    }

    function setTipoDoc($tipoDoc) {
        $this->tipoDoc = $tipoDoc;
    }

    function setNumeroDoc($numeroDoc) {
        $this->numeroDoc = $numeroDoc;
    }

    function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    function setCliente($cliente) {
        $this->cliente = $cliente;
    }

    function setBodega($bodega) {
        $this->bodega = $bodega;
    }

    function setCodProducto($codProducto) {
        $this->codProducto = $codProducto;
    }

    function setPrecioProducto($precioProducto) {
        $this->precioProducto = $precioProducto;
    }

    function setPorcentajeDescuentoProd($porcentajeDescuentoProd) {
        $this->porcentajeDescuentoProd = $porcentajeDescuentoProd;
    }

    function setTipoIVA($tipoIVA) {
        $this->tipoIVA = $tipoIVA;
    }

    function setPorcentajeIVA($porcentajeIVA) {
        $this->porcentajeIVA = $porcentajeIVA;
    }

    function setPrecioTOTAL($precioTOTAL) {
        $this->precioTOTAL = $precioTOTAL;
    }

    function getObservacion() {
        return $this->observacion;
    }

    function setObservacion($observacion) {
        $this->observacion = $observacion;
    }

    function getCantidad() {
        return $this->cantidad;
    }

    function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }


    
    
}
