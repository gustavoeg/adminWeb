<?php /* recibo por post email, nombre, imagen, googleid */
include_once '../lib/ControlAcceso.class.php'; ?>

<html>
    <head>
        <title><?php echo Constantes::NOMBRE_SISTEMA; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta name="google-signin-client_id" content="356408280239-7airslbg59lt2nped9l4dtqm2rf25aii.apps.googleusercontent.com" />
        <link href="../gui/estilo.css" type="text/css" rel="stylesheet" />
        <link href="../gui/responsivo.css" type="text/css" rel="stylesheet" />
        <script type="text/javascript" src="https://apis.google.com/js/platform.js" async defer></script>
        <script type="text/javascript" src="../lib/datatables/jquery.js"></script>
        <script type="text/javascript" src="../lib/jQuery/jquery.redirect.js"></script>
        <script type="text/javascript" src="../lib/login.js"></script>
    </head>
    <body>
        <?php include_once '../gui/GUImenu.php'; ?>
        <section id="main-content">
            <article>
                <div class="content">
                    <h3><?php echo Constantes::NOMBRE_SISTEMA; ?></h3>
                    <div>
                        <h4>Bienvenido. Ha ingresado al sistema CheckPoint</h4>
                        <p>Estimado agente: Bienvenido a la aplicaci&oacute;n CheckPoint, a trav&eacute;s de la cual podr&aacute; efectuar valoraciones
                            sobre los servicios prestados en el Campus Universitario de la UARG.
                        <?php 
                        /* determinar el rol del usuario (administrador, encargado, consultor */
                        if(true){
                                /* en este caso intento loguearse pero no pertenece al sistema */
                                echo "Mensaje para el tipo de rol que corresponda.";
                            
                        }
                        ?>
                        </p>
                    </div>
                </div>
            </article>
        </section>
        <?php include_once '../gui/GUIfooter.php'; ?>
    </body>
</html>