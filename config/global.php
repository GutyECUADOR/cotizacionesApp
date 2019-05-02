<?php
/* Configurar aqui todas las variables globales a utilizar*/
define("APP_NAME", "WebForms - Agricola Baquero");
define("EMPRESA_NAME", "Agricola Baquero");
define("LOGO_NAME", "./assets/img/logo.png");
define("APP_VERSION", "2.6.8");
define("ROOT_PATH","");   //Root del proyecto

define("IMAGES_UPLOAD_DIR", $_SERVER['DOCUMENT_ROOT'].'/uploadsCotizaciones');

define("VIEWS_PATH","/views");
define("CONFIG_FILE","./config/configuraciones.xml");
define("DEFAULT_DBName","AGRICOLABAQUERO_V7");
define("DEFAULT_EMAIL","soporteweb@sudcompu.net");

/*Envio de correos */
define("DEFAULT_SMTP","smtp.gmail.com");
define("DEFAULT_SENDER_EMAIL","");
define("DEFAULT_EMAILPASS","");

/*URL Body Email*/
define("LOGO_ONLINE","http://www.agricolabaquero.com/img/resources/logo.png");
define("SITIOWEB_ONLINE","http://www.agricolabaquero.com");
define("BODY_EMAIL_TEXT","Reciba un cordial saludo de quienes conformamos AGRICOLA BAQUERO, estamos atendiendo a su requerimiento por lo que encontrara el documento solicitado adjunto en este correo");
