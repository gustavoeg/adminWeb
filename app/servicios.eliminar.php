<?php
/**
 * eliminacion de servicio
 * se utiliza solo cuando no tiene valoraciones hechas y cuando no tiene opciones de valoracion cargadas
 * se valida previamente antes de eliminar
 */
include_once '../lib/ControlAcceso.class.php';
ControlAcceso::requierePermiso(PermisosSistema::PERMISO_SERVICIOS);
?>
<html>
    <head>
        <title><?php echo Constantes::NOMBRE_SISTEMA; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <script src="../lib/validador.js" type="text/javascript"></script>
        <link href="../gui/estilo.css" type="text/css" rel="stylesheet" />
    </head>
    <body onload="document.getElementById('nombre').focus()">
        <?php include_once '../gui/GUImenu.php'; ?>
        <section id="main-content">
            <article>
                <div class="content">
                    <h3>Eliminar Servicio</h3>
                    <?php
                    $mensaje = "Servicio eliminado con exito";
                    try {

                        //implementacion porque no estaba
                        ObjetoDatos::getInstancia()->autocommit(false);
                        ObjetoDatos::getInstancia()->begin_transaction();

                        //elimino de la asociacion con usuario
                        //echo "id".$_GET['id'];
                        if(!ObjetoDatos::getInstancia()->ejecutarQuery(""
                                . "DELETE FROM " . Constantes::BD_USERS . ".servicios "
                                . "where idservicios=" . $_GET['id']))
                        {
                             $mensaje = "No se ha eliminado, asegurese de que no hay valoraciones asociadas a este servicio.";
                             ObjetoDatos::getInstancia()->rollback();
                        }else{
                            ObjetoDatos::getInstancia()->commit();
                        }
                        
                    } catch (Exception $ex) {
                        //ver si se trata del codigo de error 1451 (restriccion foranea)
                        $mensaje = "Ha ocurrido un error : {$ex->getCode()}";
                        ObjetoDatos::getInstancia()->rollback();
                    }
                    
                    ?>
                    <p><?= $mensaje; ?></p>
                    <fieldset>
                        <legend>Opciones</legend>
                        <a href="servicios.ver.php"><input type="button" value="Ir a Servicios" /></a>
                    </fieldset>
                </div>
            </article>
        </section>
        <?php include_once '../gui/GUIfooter.php'; ?>
    </body>
</html>