<?php 
    if (isset($_SESSION["usuarioRUC"])){
        echo "Sigue Logeado";
        header('location:index.php?action=inicio');  
    }
    
    $login = new controllers\loginController();
?>

    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>assets\css\estilos_login.css" />

    <?php include 'sis_modules/header_main.php'?>

    <div class="contenedor-formulario">
        
		<div class="wrap">

            <?php
                $login->checkXMLFile();
            ?>

			<form action=""  autocomplete="off"  class="formulario" name="formulario_registro" method="POST">
               			<div>
	           		   	<img class="logo" src="<?php echo LOGO_NAME?>" alt="Logo">
                    		</div>
                    
                                <div  id="bloque">
                                        <div class="input-group">

                                        <?php 
                                            $login->actionCatcherController();
                                        ?>
                                            <label class="label" for="nombre">Indique empresa:</label>
                                            <select name="select_empresa" id="select_empresa" required="true">
                                                <option value=''>---Seleccione Empresa---</option>
                                                <?php
                                                    $login->printOpcionsFromXMLFile();
                                                ?>
                                                <!-- <option value='LICEO'>Agricola Baquero</option> 
                                                <option value='MODELO'>Modelo</option>   --> 
                                             </select>
                                            
                                            <input type="text" name="login_username" id="inputuser" maxlength="30" placeholder="Usuario del Sistema o RUC" required >
                                            <input type="password" name="login_password" id="inputpass" placeholder="Contraseña" maxlength="50" required >
                                            
                                        </div>
                                </div>
	
		                
		                <div>
					<input name="guardar" type="submit" id="btn-submit" value="Ingresar">
				</div>
				
			</form>
			<div class="footer">Todos los derechos reservados © 2017 - <?php echo date("Y")?>, Ver <?php echo APP_VERSION ?></div>
		</div>
		
	</div>

