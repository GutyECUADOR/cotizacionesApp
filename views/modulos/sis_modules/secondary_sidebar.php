<aside id="sidebar_secondary" class="tabbed_sidebar">
        <ul class="uk-tab uk-tab-icons uk-tab-grid" data-uk-tab="{connect:'#dashboard_sidebar_tabs', animation:'slide-horizontal'}">
            
            <li class="uk-width-1-1"><a href="#"><i class="material-icons">&#xE8B9;</i></a></li>
        </ul>

        <div class="scrollbar-inner">
            <ul id="dashboard_sidebar_tabs" class="uk-switcher">
                <li>
                    <h4 class="heading_c uk-margin-small-bottom uk-margin-top">Configuraciones Generales</h4>
                    <ul class="md-list">
                        <li>
                            <div class="md-list-content">
                                <div class="uk-float-right">
                                    <input type="checkbox" data-switchery data-switchery-size="small" id="switcher_theme_sis" name="switcher_theme_sis"/>
                                </div>
                                <span class="md-list-heading">Tema Oscuro</span>
                                <span class="uk-text-muted uk-text-small">Define colores de estilo en colores oscuros.</span>
                            </div>
                            
                        
                    </ul>
                    <h4 class="heading_c uk-margin-small-bottom uk-margin-top">Datos de Sesion</h4>
                    <p>
                        <?php
                        echo 'Usuario Activo: '.$_SESSION["usuarioRUC"] . "</br>";
                        echo 'Usuario: ' . $_SESSION["usuarioNOMBRE"] . "</br>";
                        echo 'lV. Acceso: '.$_SESSION["usuarioTipo"] . "</br>";
                        echo 'Empresa DB : '.$_SESSION["empresaAUTH"] . "</br>";
                        echo 'Codigo DB : '.$_SESSION["codEmpresaAUTH"] . "</br>";
                        ?>
                    </p>
                </li>
            </ul>
        </div>

        <button type="button" class="chat_sidebar_close uk-close"></button>
        <div class="chat_submit_box">
            <div class="uk-input-group">
                <input type="text" class="md-input" name="submit_message" id="submit_message" placeholder="Send message">
                <span class="uk-input-group-addon">
                    <a href="#"><i class="material-icons md-24">&#xE163;</i></a>
                </span>
            </div>
        </div>

</aside>