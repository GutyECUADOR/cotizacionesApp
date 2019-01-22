<?php 
  
    if (isset($_SESSION["usuarioRUC"])){
        echo "Sigue Logeado";
        header('location:index.php?action=inicio');
        
    }

    $login = new controllers\loginController();
?>

<!-- CSS Paginas -->
<link rel="stylesheet" href="<?php echo ROOT_PATH; ?>assets/css/login_page.min.css" />

<body class="login_page">

<div class="login_page_wrapper">
    <div class="md-card" id="login_card">
        <div class="md-card-content large-padding" id="login_form">
            <div class="login_heading">
                <div class="user_avatar"></div>
            </div>

              <?php 
                $login->actionCatcherController();
              ?>
              

             <form action="" method="POST" autocomplete="off">
                <div class="uk-form-row">
                  <div class="md-input-wrapper md-input-filled">
                        <select id="select_empresa" name="select_empresa" class="md-input" required>
                            <option value="" disabled="" selected="" hidden="">Seleccione Empresa</option>
                            <?php $login->showAllDataBaseList() ?>
                        </select><span class="md-input-bar "></span></div>
                </div>
  
                <div class="uk-form-row">
                    <label for="login_username">Usuario</label>
                    <input class="md-input" type="text" id="login_username" name="login_username" required />
                </div>
                <div class="uk-form-row">
                    <label for="login_password">Contraseña</label>
                    <input class="md-input" type="password" id="login_password" name="login_password" required />
                </div>

                <div class="uk-margin-medium-top">
                    <button type="submit" class="md-btn md-btn-primary md-btn-block md-btn-large">Ingresar</a>
                </div>
                <div class="uk-margin-top">
                    <a href="#" id="login_help_show" class="uk-float-right">Necesitas Ayuda?</a>
                    <span class="icheck-inline">
                        <input type="checkbox" name="login_page_stay_signed" id="login_page_stay_signed" data-md-icheck />
                        <label for="login_page_stay_signed" class="inline-label">Mantener Sesión</label>
                    </span>
                </div>
            </form>
        </div>


        <div class="md-card-content large-padding uk-position-relative" id="login_help" style="display: none">
            <button type="button" class="uk-position-top-right uk-close uk-margin-right uk-margin-top back_to_login"></button>
            <h2 class="heading_b uk-text-success">No logra ingresar?</h2>
            <p>Intente primero verificar si no esta ingresando mayusculas o espacios en blanco.</p>
            <p>Si no es asi por favor contactese con sistemas para recuperar sus credenciales de acceso.</p>
            <p>Oh si tiene registrado un correo, restablezca sus credenciales ingresandolo en el siguiente enlace. <a href="#" id="password_reset_show">envíame un correo</a>.</p>
        </div>
        <div class="md-card-content large-padding" id="login_password_reset" style="display: none">
            <button type="button" class="uk-position-top-right uk-close uk-margin-right uk-margin-top back_to_login"></button>
            <h2 class="heading_a uk-margin-large-bottom">Reestablecer contraseña</h2>
            <form>
                <div class="uk-form-row">
                    <label for="login_email_reset">Tu dirección de correo</label>
                    <input class="md-input" type="text" id="login_email_reset" name="login_email_reset" />
                </div>
                <div class="uk-margin-medium-top">
                    <a href="#" class="md-btn md-btn-primary md-btn-block">Enviar Correo</a>
                </div>
            </form>
        </div>
        
    </div>
   
</div>

<!-- common functions -->
<script src="<?php echo ROOT_PATH; ?>assets/js/common.min.js"></script>
<!-- uikit functions -->
<script src="<?php echo ROOT_PATH; ?>assets/js/uikit_custom.min.js"></script>
<!-- altair core functions -->
<script src="<?php echo ROOT_PATH; ?>assets/js/altair_admin_common.min.js"></script>

<!-- altair login page functions -->
<script src="<?php echo ROOT_PATH; ?>assets/js/pages/login.min.js"></script>



</body>