<?php 

?>
<!DOCTYPE html>
<html lang="es">

  <head>
      <meta charset="UTF-8">

      <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <!-- Remove Tap Highlight on Windows Phone IE -->
      <meta name="msapplication-tap-highlight" content="no" />

      <link rel="icon" type="image/png" href="<?php echo ROOT_PATH; ?>assets/img/favicon-16x16.png" sizes="16x16">
      <link rel="icon" type="image/png" href="<?php echo ROOT_PATH; ?>assets/img/favicon-32x32.png" sizes="32x32">

      <!--Import Google Icon Font-->
      <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        
      <!-- CSS Bootstrap -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
      
      <!-- Optional theme -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

      <!-- Librerias datepicker Boostrap3-->
      <link href="<?php echo ROOT_PATH; ?>assets\bootstrap-datepicker-1.6.4\css\bootstrap-datepicker3.css" rel="stylesheet">
      <script href="<?php echo ROOT_PATH; ?>assets\bootstrap-datepicker-1.6.4\js\bootstrap-datepicker.js"></script>
      <script href="<?php echo ROOT_PATH; ?>assets\bootstrap-datepicker-1.6.4\locales\bootstrap-datepicker.es.min.js"></script>
       
      <!-- CSS Propios -->
      <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>assets\css\styles.css">
      <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>assets\css\pnotify.custom.min.css">
      <link rel="shortcut icon" href="<?php echo ROOT_PATH; ?>assets\css\img\favicon.ico">
       
      
      <title><?php echo APP_NAME; ?></title>

  </head>

  <body>
    <div>
      <div>
        
        <?php
            $inicioController = new controllers\mainController();
            $inicioController->actionCatcherController();
        ?>
      </div>
    </div>

         
    <!-- USO JQUERY, y Bootstrap CDN-->
    <script src="<?php echo ROOT_PATH; ?>assets\js\jquery-3.3.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
    
     <!-- JS Propio-->
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>assets\js\pnotify.custom.min.js"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>assets\js\functions.js"></script>
    
  </body>
</html>


