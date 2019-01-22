<?php
    if (!isset($_SESSION["usuarioRUC"])){
           header("Location:index.php?&action=login");  
        } 
        
  
    $edocs = new models\EDocsClass();    
    $userData = $edocs->getInfoUserActive($_SESSION["usuarioRUC"]); 
    
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link rel="icon" href="images/favicon.ico" type="image/ico" />

    <!-- Bootstrap -->
    <link href="<?php echo ROOT_PATH; ?>assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo ROOT_PATH; ?>assets/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php echo ROOT_PATH; ?>assets/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="<?php echo ROOT_PATH; ?>assets/iCheck/skins/flat/green.css" rel="stylesheet">
	
    <!-- bootstrap-progressbar -->
    <link href="<?php echo ROOT_PATH; ?>assets/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="<?php echo ROOT_PATH; ?>assets/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
    
    <!-- bootstrap-daterangepicker -->
    <link href="<?php echo ROOT_PATH; ?>assets/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <!-- bootstrap-datetimepicker -->
    <link href="<?php echo ROOT_PATH; ?>assets/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="<?php echo ROOT_PATH; ?>assets/build/css/custom.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="#" class="site_title"><i class="fa fa-user"></i> <span>KAO Sport</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile clearfix">
              <div class="profile_pic">
                <img src="<?php echo ROOT_PATH; ?>assets/images/user.png" alt="..." class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>Bienvenido,</span>
                <h2><?php echo ucwords(strtolower($_SESSION['usuarioNOMBRE'])) ?></h2>
              </div>
            </div>
            <!-- /menu profile quick info -->

            <br />

            <!-- sidebar menu -->
            <?php include_once('sis_modules/sidebar.php')?>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <?php include_once('sis_modules/menu_footer.php')?>
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
        <?php include 'sis_modules/top_navigation.php'?>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <!-- top tiles -->
          
          <!-- /top tiles -->

          <div class="row">
           
            <div class="col-md-12 col-sm-12 col-xs-12">

                <div class="x_panel">
                  <div class="x_title">
                    <h2>Informacion de Usuario <small></small></h2>

                    <?php
                    
                        
                     
                    ?>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br>
                    <form action="" method="POST" id="userdata_form"  name="userdata_form" class="form-horizontal form-label-left">

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">RUC <span class="required"></span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="txt_ruc" name="txt_ruc" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $userData['ruc'] ?>" readonly>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">CLIENTE<span class="required"></span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="txt_empresa" name="txt_empresa" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $userData['nombre'] ?>" readonly>
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Contraseña antigua</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="last_password" name="last_password" class="form-control col-md-7 col-xs-12" type="password" name="middle-name">
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Nueva Contraseña</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="new_password" name="new_password" class="form-control col-md-7 col-xs-12" type="password" name="middle-name">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Confirme Contraseña</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="new_password_confirm" name="new_password_confirm" class="form-control col-md-7 col-xs-12" type="password" name="middle-name">
                        </div>
                      </div>
                      
                     
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                          <a class="btn btn-primary" href="?action=inicio" type="button">Cancelar</a>
                          <button type="submit" class="btn btn-success">Actualizar</button>
                        </div>
                      </div>

                    </form>
                  </div>
                </div>
              
            </div>
          </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
          <div class="pull-right">
            Todos los derechos reservados @2018.
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    
     <!-- jQuery -->
    <script src="<?php echo ROOT_PATH; ?>assets/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="<?php echo ROOT_PATH; ?>assets/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="<?php echo ROOT_PATH; ?>assets/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="<?php echo ROOT_PATH; ?>assets/nprogress/nprogress.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="<?php echo ROOT_PATH; ?>assets/moment/min/moment.min.js"></script>
    <script src="<?php echo ROOT_PATH; ?>assets/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap-datetimepicker -->    
    <script src="<?php echo ROOT_PATH; ?>assets/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
    <!-- Ion.RangeSlider -->
    <script src="<?php echo ROOT_PATH; ?>assets/ion.rangeSlider/js/ion.rangeSlider.min.js"></script>
    <!-- Bootstrap Colorpicker -->
    <script src="<?php echo ROOT_PATH; ?>assets/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
    <!-- jquery.inputmask -->
    <script src="<?php echo ROOT_PATH; ?>assets/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
    <!-- jQuery Knob -->
    <script src="<?php echo ROOT_PATH; ?>assets/jquery-knob/dist/jquery.knob.min.js"></script>
    <!-- Cropper No Supported -->

    <!-- PNotify -->
    <script src="<?php echo ROOT_PATH; ?>assets/pnotify/dist/pnotify.js"></script>
    <script src="<?php echo ROOT_PATH; ?>assets/pnotify/dist/pnotify.buttons.js"></script>
    <script src="<?php echo ROOT_PATH; ?>assets/pnotify/dist/pnotify.nonblock.js"></script>
    
    <!-- Custom Theme Scripts -->
    <script src="<?php echo ROOT_PATH; ?>assets/build/js/custom.js"></script>
     
    <!-- Estilos Personalizados extra -->
    <script src="<?php echo ROOT_PATH; ?>assets/functions.js"></script>
    
    <?php
   if (isset($_POST['last_password']) && isset($_POST['new_password']) && isset($_POST['new_password_confirm'])  ) {
      $RUC = $_SESSION['usuarioRUC'];
      $oldPass = rtrim($_POST['last_password']);
      $userInfoOldPass = rtrim($userData['password']);
      $nuevaPass = $_POST['new_password'];
      $confirmPass = $_POST['new_password_confirm'];

      if ($nuevaPass === $confirmPass && $oldPass === $userInfoOldPass) {
        if($edocs->updatePassword($RUC, $nuevaPass)){
          $edocs->showCorrectUpdate('Su contraseña ha sido actualizada');
        }else{
          $edocs->showIncorrectUpdate('No se ha podido actualizar su contraseña.');
        }
      }else{
        $edocs->showIncorrectUpdate('Las contraseñas no coinciden, reintente.');
      }

    
    }
    ?>
    
  </body>
</html>
