<?php
    $conexion_ok = false;    
        function getDataBase($cod_db) {
            switch ($cod_db) {
            
                case 001:
                $conexion_ok = odbc_connect('ODBC_IMPORKAO', '', '' ) ;//or die ("Error en conexion ODBC de Importaciones KAO");
                break;
            
                case '001':
                    $conexion_ok = odbc_connect('ODBC_IMPORKAO', '', '' ) ;//or die ("Error en conexion ODBC de Importaciones KAO");
                    break;

                case 002:
                    $conexion_ok = odbc_connect('ODBC_KINDRED', '', '' ); //or die ("Error en conexion ODBC de KINDRED");
                    break;
                
                case '002':
                    $conexion_ok = odbc_connect('ODBC_KINDRED', '', '' ); //or die ("Error en conexion ODBC de KINDRED");
                    break;

                case 003:
                    $conexion_ok = odbc_connect('ODBC_KINSMAN', '', '' ); //or die ("Error en conexion ODBC de KINSMAN");
                    break;
                
                case '003':
                    $conexion_ok = odbc_connect('ODBC_KINSMAN', '', '' ); //or die ("Error en conexion ODBC de KINSMAN");
                    break;
                
                case 004:
                    $conexion_ok = odbc_connect('ODBC_invart', '', '' ); // or die ("Error en conexion ODBC de FRANKLIN ALVAREZ");
                    break;
                
                case '004':
                    $conexion_ok = odbc_connect('ODBC_invart', '', '' ); // or die ("Error en conexion ODBC de FRANKLIN ALVAREZ");
                    break;

                case 005:
                    $conexion_ok = odbc_connect('ODBC_BODEGA_GYE', '', '' ); // or die ("Error en conexion ODBC de LYNN LEE WANG");
                    break;
                
                case '005':
                    $conexion_ok = odbc_connect('ODBC_BODEGA_GYE', '', '' ); // or die ("Error en conexion ODBC de LYNN LEE WANG");
                    break;

                case 006:
                    $conexion_ok = odbc_connect('ODBC_SCKINSMAN', '', '' );  //or die ("Error en conexion ODBC de COMERCIALIZADORA KINSMAN");
                    break;
                
                case '006':
                    $conexion_ok = odbc_connect('ODBC_SCKINSMAN', '', '' );  //or die ("Error en conexion ODBC de COMERCIALIZADORA KINSMAN");
                    break;

                case 007:
                    $conexion_ok = odbc_connect('ODBC_VJCB', '', '' );  //or die ("Error en conexion ODBC de VERONICA CARRASCO");
                    break;
                
                case '007':
                    $conexion_ok = odbc_connect('ODBC_VJCB', '', '' );  //or die ("Error en conexion ODBC de VERONICA CARRASCO");
                    break;
                
                case 008:
                    $conexion_ok = odbc_connect('Driver={SQL Server};Server=192.168.0.3;Database=MODELO;', 'sfb', 'Sud2017$' );  // Base de datos modelo, autenticacion de SQL requiere user y pass
                    break;
                
                case '008':
                    $conexion_ok = odbc_connect('Driver={SQL Server};Server=192.168.0.3;Database=MODELO;', 'sfb', 'Sud2017$' );  // Base de datos modelo, autenticacion de SQL requiere user y pass
                    break;

                case 009:
                    $conexion_ok = odbc_connect('Driver={SQL Server};Server=192.168.0.3;Database=KAO_wssp;', 'sfb', 'Sud2017$' ) or die ("Error en conexion ODBC");  // Base de datos WSSP
                    break;
                
                case '009':
                    $conexion_ok = odbc_connect('Driver={SQL Server};Server=192.168.0.3;Database=KAO_wssp;', 'sfb', 'Sud2017$' ) or die ("Error en conexion ODBC");  // Base de datos WSSP
                    break;
                    
                case 010:
                    $conexion_ok = odbc_connect('Driver={SQL Server};Server=192.168.0.3;Database=SBIOKAO;', 'sfb', 'Sud2017$' ) or die ("Error en conexion ODBC");  // Base de datos SBIO
                    break;
                
                case '010':
                    $conexion_ok = odbc_connect('Driver={SQL Server};Server=192.168.0.3;Database=SBIOKAO;', 'sfb', 'Sud2017$' ) or die ("Error en conexion ODBC");  // Base de datos SBIO
                break;	
            
                case 011:
                    $conexion_ok = odbc_connect('ODBC_Liceo', '', '');  // Base de datos SBIO
                
                break;
            
                case '011':
                    $conexion_ok = odbc_connect('Driver={SQL Server};Server=192.168.0.3;Database=Modelo;', 'sfb', 'Sud2017$' ); // Base de datos modelo, autenticacion de SQL requiere user y pass
                    echo odbc_errormsg();
                break;    
            
            default:
                $conexion_ok = odbc_connect('Driver={ODBC Driver 11 for SQL Server};Server=196.168.0.3;Database=Liceo;', 'sfb', 'Sud2017$' ); // Base de datos modelo, autenticacion de SQL requiere user y pass
                break;
            }
            
            return $conexion_ok ;
            
}