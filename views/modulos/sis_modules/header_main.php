<nav class="navbar navbar-default ">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#"><?php echo APP_NAME; ?></a>
      
    </div>

    
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav navbar-right">
        <?php
              if (isset($_SESSION["usuarioRUC"])){
                echo '
                <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Bienvenido, '.$_SESSION["usuarioNOMBRE"].'<span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="#">DB: '.$_SESSION["empresaAUTH"].' </a></li>
                    <li><a href="?action=logout">Cerrar Sesion </a></li>
                   
                  </ul>
                </li>
                ';
                
            }
        ?>

       
      </ul>
      
      
    </div><!-- /.navbar-collapse -->

  </div><!-- /.container-fluid -->
</nav>