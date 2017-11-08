<?php /* recibo por post email, nombre, imagen, googleid */
include_once '../lib/Constantes.class.php'; ?>
<html>
    <head>
        <title><?php echo Constantes::NOMBRE_SISTEMA; ?></title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        
        <link href="../gui/estilo.css" type="text/css" rel="stylesheet" />
        <link href="../gui/responsivo.css" type="text/css" rel="stylesheet" />
    </head>
    <body>
        <?php include_once '../gui/GUImenuEstatico.php'; ?>
        <section id="main-content">
            <article>
                <div class="content">
                    <h3><?php echo Constantes::NOMBRE_SISTEMA; ?></h3>
                    <div>
                        <h4>Bienvenido</h4>
                        <p>Estimado agente: Este navegador tiene deshabilitado JavaScript, el cual es necesario para operar con este sistema.</p>
                        <p><span style="color: red">
                        JavaScript desabilitado
                                </span></p>
                        <h4>Ingreso al Sistema</h4>
                        <p>Luego de realizada la habilitación, para acceder al sistema, por favor haga click en el bot&oacute;n a 
                            continuaci&oacute;n.</p>
                                <a href="index.php">
                                    <input type="button" class="btn btn-success" value="Intentar Nuevamente" />
                                </a>
                    </div>
                </div>
            </article>
        </section>
        <?php include_once '../gui/GUIfooter.php'; ?>
    </body>
</html>