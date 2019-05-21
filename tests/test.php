<?php
session_start();
define("IMAGES_UPLOAD_DIR", $_SERVER['DOCUMENT_ROOT'].'/uploadsCotizaciones');

 require_once '../core/models/conexion.php';
 require_once '../core/models/ajaxModel.php';
 require_once '../core/controllers/ajaxController.php';

$ajax = new \controllers\ajaxController();

$files = glob(IMAGES_UPLOAD_DIR.'/992018PRO00014148'.'_*');
            $cont = 0;
            foreach ($files as $file) {
                $cont++;
               
                echo substr($file,36)."<br>";
            }


//$arrayProductos = $ajax->getAllProductosWithExtraDesc('992018PRO00014148');

/* 
$rowsHTML = '';
 foreach ($arrayProductos as $producto) {
    echo(trim($producto['Codigo']));
    echo(trim($producto['Nombre']));
    echo(trim($producto['PrecA']));
    echo(trim($producto['imagen']));
    echo(trim($producto['comentario']));

    $rowsHTML .= '
                    <tr>
                        <td class="customrowtable" width="30%">
                            <img src="'.$_SERVER['DOCUMENT_ROOT'].'/uploadsCotizaciones'.'/'.trim($producto['imagen']).'" class="img-thumbnail" alt="'.trim($producto['imagen']).'">
                            <span>Cod: '.trim($producto['Codigo']).'</span>
                        </td>
                        
                        <td class="customrowtable"><strong>'.trim($producto['Nombre']).'</strong>test</td> 
                    </tr>';
 }

 echo $rowsHTML; */