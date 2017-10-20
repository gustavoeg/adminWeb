<?php  include_once '../lib/ControlAcceso.class.php'; ?>

<html>
    <head>
        <title><?php echo Constantes::NOMBRE_SISTEMA; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta name="google-signin-client_id" content="356408280239-7airslbg59lt2nped9l4dtqm2rf25aii.apps.googleusercontent.com" />
        <link href="../gui/estilo.css" type="text/css" rel="stylesheet" />
        <link href="../gui/responsivo.css" type="text/css" rel="stylesheet" />
        <script type="text/javascript" src="https://apis.google.com/js/platform.js" async defer></script>
        <script type="text/javascript" src="../lib/jQuery/jquery.redirect.js"></script>
        <script type="text/javascript" src="../lib/login.js"></script>
    </head>
    <body>
        <?php include_once '../gui/GUImenuEstatico.php'; ?>
        <section id="main-content">
            <article>
                <div class="content">
                    <h3><?php echo Constantes::NOMBRE_SISTEMA; ?> - Administraci&oacute;n</h3>
                    <div>
                        <h4>Bienvenido</h4>
                        <p>Estimado agente: Bienvenido a la aplicaci&oacute;n CheckPoint, a trav&eacute;s de la cual podr&aacute; efectuar valoraciones
                            sobre los servicios prestados en el Campus Universitario de la UARG.</p>
                        <p><span style="color: red">Importante: Algun mensaje importante.</span></p>
                        <h4>Ingreso al Sistema</h4>
                        <p>Ud. puede consultar el sistema si est&aacute; conectado a su e-mail Institucional. Por favor haga click en el bot&oacute;n a 
                            continuaci&oacute;n y elija su cuenta institucional.</p>
                        <div class="botonGoogle" onclick="window.open('../Instructivo.pdf', '_blank');" title="Ver Manual de Uso">
                            <div class="abcRioButtonIcon" style="padding:8px">
                                <div style="width:18px;height:18px;" class="abcRioButtonSvgImageWithFallback abcRioButtonIconImage abcRioButtonIconImage18">
                                    <img src="../imagenes/pdf.png" style="width: 18px; height: 18px" />
                                </div>
                            </div>
                            <span style="font-size:13px;line-height:34px;" class="abcRioButtonContents">
                                <span id="not_signed_in9kbu5ybb006p">Manual</span>
                            </span>
                        </div>
                        <div id="okgoogle" class="g-signin2" onclick="ClickLogin()" data-onsuccess="onSignIn" title="Acceder al Sistema eRecibo"></div>
                    </div>
                </div>
            </article>
        </section>
        <?php include_once '../gui/GUIfooter.php'; ?>
    </body>
</html>