<?php
session_start();
require_once '../../../core/controllers/ajaxController.php';
require_once '../../../core/models/ajaxModel.php';
require_once '../../../config/global.php';

class ajax{
    
    public $ruc;
    public $fecha_INI;
    public $fecha_FIN;
    public $typoDOC;
    
    function getTypeDocument($codDocument) {
        if ($codDocument=='FV')
        {
        $tdocument = "Factura";
        }
        elseif ($codDocument=='NC') 
        {
        $tdocument = "Nota de Crédito";    
        }
        elseif ($codDocument=='RT') 
        {
        $tdocument = "Retenciones";    
        }
        elseif ($codDocument=='GR') 
        {
        $tdocument = "Guía de Remisión";    
        }
        else
        {
        $tdocument = "SIN IDENTIFICAR";    
        }  
       return $tdocument;
    }
    
    
    public function ejecutaAjax() {
        
        $ajaxController = new \controllers\ajaxController();
        return $respuestaAjax = $ajaxController->actionJSONgetAllDocuments($this->ruc,$this->fecha_INI,  $this->fecha_FIN, $this->typoDOC);
        
    }
    
}

$ajax = new ajax();
$ajax->ruc = $_SESSION["usuarioRUC"];
$ajax->fecha_INI = $_POST['fecha_INI'];
$ajax->fecha_FIN = $_POST['fecha_FIN'];
$ajax->typoDOC = $_POST['typoDOC'];
$resultset = $ajax->ejecutaAjax();

?>
 
<?php  
    if (!empty($resultset)){     
    
?>

    <div class="table-responsive">
            <table class="table table-striped jambo_table bulk_action">
                <thead>
                  <tr class="headings">
                    <th>#</th>
                    <th class="column-title">Fecha </th>
                    <th class="column-title">RUC/CI </th>
                    <th class="column-title">Nombres</th>
                    <th class="column-title">Documento</th>
                    <th class="column-title">Tipo</th>
                    <th class="column-title">Descargar</th>
                  </tr>
                </thead>


                <tbody>
                 <tr class="even pointer">
                 <?php 

                   foreach ($resultset as $row) { ?>

                     <td class="">-</td>
                     <td class=""><?php echo $row['fecha']?></td>
                     <td class=""><?php echo $row['ruc']?></td>
                     <td class=""><?php echo $row['ClienteN']?></td>
                     <td class=""><?php echo $row['numero']?></td>
                     <td class=""><?php echo $ajax->getTypeDocument($row['tipo'])?></td>
                     <td class="" >
                       <a href="<?php echo ROOT_EDOCSPDF.$row['archivopdf']?>" target='_blank' class="generapdf" download="<?php echo $row['archivopdf']?>"><span class="count_top"><i class="fa fa-file-pdf-o"></i> PDF</span></a>
                       <a href="<?php echo ROOT_EDOCSXML.$row['archivoxml']?>" target='_blank' class="generaxml" download="<?php echo $row['archivoxml']?>"><span class="count_top"><i class="fa fa-file-code-o"></i> XML</span></a>
                     </td>

                 </tr>
                 <?php
                   }
                 ?>
               </tbody>
            </table>
    </div> <!-- end table responsive-->
    
    
    <?php
    }else{
     ?>   
    <script>
            new PNotify({
                  title: 'Búsqueda Vacia',
                  text: 'No existen resultados para la información indicada, reintente.',
                  delay: 5000,
                  type: 'alert',
                  styling: 'bootstrap3'
            });
    </script>
    
    <div class="table-responsive">
            <table class="table table-striped jambo_table bulk_action">
                <thead>
                  <tr class="headings">
                    <th>#</th>
                    <th class="column-title">Fecha </th>
                    <th class="column-title">RUC/CI </th>
                    <th class="column-title">Nombres</th>
                    <th class="column-title">Documento</th>
                    <th class="column-title">Tipo</th>
                    <th class="column-title">Descargar</th>
                  </tr>
                </thead>


                <tbody>
                </tbody>
            </table>
    </div> <!-- end table responsive-->
    <?php
    }
    
    ?>
    