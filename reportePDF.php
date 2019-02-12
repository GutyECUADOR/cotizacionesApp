<?php

session_start();
require_once './vendor/autoload.php';
require_once './config/global.php';
require_once './core/models/conexion.php';
require_once './core/controllers/mainController.php';
require_once './core/controllers/loginController.php';
require_once './core/controllers/ajaxController.php';
require_once './core/models/loginModel.php';
require_once './core/models/mainModel.php';
require_once './core/models/ajaxModel.php';

/*Controllers y Models Personalizados*/
require_once './core/controllers/CotizacionController.php';
require_once './core/models/CotizacionClass.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$ajax = new \controllers\ajaxController();

$empresaData = $ajax->getInfoEmpresaController();
$VEN_CAB = $ajax->getVEN_CABController('992018PRO00014075');
$VEN_MOV = $ajax->getVEN_MOVController('992018PRO00014075');

$html = '
            
    <div style="width: 100%;">

        <div style="float: right; width: 85%;">
            <div id="informacion">
                <h4>'.$empresaData["NomCia"].'</h4>
                <h4>Direccion: '.$empresaData["DirCia"].'</h4>
                <h4>Telefono: '.$empresaData["TelCia"].'</h4>
                <h4>RUC: '.$empresaData["RucCia"].'</h4>
                <h4>PROFORMA #  '.$VEN_CAB["ID"].' </h4>
            </div>
        </div>

        <div id="logo" style="float: left; width: 15%;">
            <img src="http://localhost/PHPProjects/cotizacionesApp/assets/img/logo.png" alt="Logo">
        </div>

    </div>

    <div id="infoCliente" class="rounded">
        <div class="cabecera"><b>Fecha:</b> '. date('Y-m-d').'</div>
        <div class="cabecera"><b>Cliente:</b> '.$VEN_CAB["NOMBRE"].'</div>
        <div class="cabecera"><b>Direccion: </b> '.$VEN_CAB["DIRECCION1"].' </div>
        <div class="cabecera"><b>Telefono: </b> '.$VEN_CAB["TELEFONO1"].' </div>
        <div class="cabecera"><b>Email: </b> '.$VEN_CAB["EMAIL"].' </div>
    </div>

    <table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
        <thead>
            <tr>
                <td width="5%">Item</td>
                <td width="11%">Cod.</td>
                <td width="7%">Cant.</td>
                <td width="45%">Descripcion</td>
                <td width="6%">IVA</td>
                <td width="15%">P. Unit.</td>
                <td width="10%">% Desc.</td>
                <td width="10%">V. Desc.</td>
                <td width="15%">P. Total</td>
            </tr>
        </thead>
    <tbody>

    <!-- ITEMS HERE -->
    ';

        foreach($VEN_MOV as $row){
            $cont = 1;
            $html .= '

            <tr>
                <td align="center">'.$cont.'</td>
                <td align="center">'.$row["CODIGO"].'</td>
                <td align="center">'.$row["CANTIDAD"].'</td>
                <td>'.$row["Nombre"].'</td>
                <td>'.$row["tipoiva"].'</td>
                <td>'.$row["PRECIO"].'</td>
                <td>'.$row["DESCU"].'</td>
                <td class="cost"> '.$row["DESCU"].' </td>
                <td class="cost"> '.$row["PRECIOTOT"].'</td>
            </tr>';
           
            }

    $html .= ' 
    

    <!-- END ITEMS HERE -->
        <tr>
        <td class="blanktotal" colspan="6" rowspan="6"></td>
        <td class="totals" colspan="2">Imponible 0%:</td>
        <td class="totals cost">0</td>
        </tr>

    
        <tr>
        <td class="totals" colspan="2">Imponible 12%:</td>
        <td class="totals cost">'.$VEN_CAB["BASIVA"].'</td>
        </tr>

        <tr>
        <td class="totals" colspan="2">Subtotal:</td>
        <td class="totals cost">'.$VEN_CAB["SUBTOTAL"].'</td>
        </tr>

        <tr>
        <td class="totals" colspan="2">Base Imponible:</b></td>
        <td class="totals cost">0</td>
        </tr>

        <tr>
        <td class="totals" colspan="2">IVA:</td>
        <td class="totals cost">'.$VEN_CAB["IMPUESTO"].'</td>
        </tr>

        <tr>
        <td class="totals" colspan="2"><b>Total Pagar:</b></td>
        <td class="totals cost"><b>'.$VEN_CAB["TOTAL"].'</b></td>
        </tr>

    </tbody>
    </table>

    <div style="width: 100%;">
        <p id="observacion">Observacion: Generado por WSSP</p> 
    </div>

';

//==============================================================
//==============================================================
//==============================================================

$mpdf = new mPDF('c','A4');

// LOAD a stylesheet
$stylesheet = file_get_contents('./assets/css/reportesStyles.css');

$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($html);

$mpdf->Output('doc.pdf', 'I');