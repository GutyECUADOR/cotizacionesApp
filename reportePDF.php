<?php

    $html = '
        
    <div style="width: 100%;">
       
        <div style="float: left; width: 75%;">
            <div id="informacion">
                <h4>AGRICOLA BAQUERO</h4>
                <h4>Direccion Av. Alpahuasi y Ana Paredez de Alfaro</h4>
                <h4>Telefono</h4>
                <h4>RUC</h4>
                <h4>PROFORMA # </h4>
            </div>
        </div>

        <div id="logo" style="float: right; width: 20%;">
            <img src="http://localhost/PHPProjects/cotizacionesApp/assets/img/logo.png" alt="Logo">
        </div>

    </div>

    <div id="infoCliente" class="rounded">
        <div class="cabecera">Fecha: </div>
        <div class="cabecera">Cliente: </div>
        <div class="cabecera">Direccion: </div>
        <div class="cabecera">Telefono: </div>
        <div class="cabecera">Email: </div>
    </div>

    <table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
        <thead>
            <tr>
                <td width="15%">Cod.</td>
                <td width="10%">Cant.</td>
                <td width="45%">Descripcion</td>
                <td width="15%">Precio Unit.</td>
                <td width="15%">Precio Total</td>
            </tr>
        </thead>
    <tbody>

    <!-- ITEMS HERE -->
    
        <tr>
            <td align="center">MF1234567</td>
            <td align="center">0</td>
            <td>Large pack Hoover bags</td>
            <td class="cost">0</td>
            <td class="cost">0</td>
        </tr>

    <!-- END ITEMS HERE -->
        <tr>
        <td class="blanktotal" colspan="3" rowspan="6"></td>
        <td class="totals">Imponible 0%:</td>
        <td class="totals cost">0</td>
        </tr>

        
        <tr>
        <td class="totals">Imponible 12%:</td>
        <td class="totals cost">0</td>
        </tr>

        <tr>
        <td class="totals">Subtotal:</td>
        <td class="totals cost">0</td>
        </tr>

        <tr>
        <td class="totals">Base Imponible:</b></td>
        <td class="totals cost">0</td>
        </tr>

        <tr>
        <td class="totals">IVA:</td>
        <td class="totals cost">0</td>
        </tr>

        <tr>
        <td class="totals"><b>Total Pagar:</b></td>
        <td class="totals cost"><b>0</b></td>
        </tr>

    </tbody>
    </table>


   


    ';

    //==============================================================
    //==============================================================
    //==============================================================

    require_once __DIR__ . '/vendor/autoload.php';
    $mpdf = new mPDF('c','A4');

    // LOAD a stylesheet
    $stylesheet = file_get_contents('./assets/css/reportesStyles.css');
    
    $mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

    $mpdf->WriteHTML($html);
    $mpdf->Output();
    exit;

    //==============================================================
    //==============================================================
    //==============================================================

        