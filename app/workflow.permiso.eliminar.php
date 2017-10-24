<?php
include_once '../lib/ControlAcceso.class.php';
//include_once '../modelo/Workflow.class.php';
ControlAcceso::requierePermiso(PermisosSistema::PERMISO_USUARIOS);
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
                    <h3>Eliminar Permiso</h3>
                    <?php
                    $mensaje = "Registro eliminado con exito";
                    //$PermisoWorkflow = new WorkflowPermiso($_GET['id']);
                    try {
                          //implementacion porque no estaba
                        ObjetoDatos::getInstancia()->autocommit(false);
                        ObjetoDatos::getInstancia()->begin_transaction();

                        //elimino de la asociacion con usuario
                        ObjetoDatos::getInstancia()->ejecutarQuery(""
                                . "DELETE FROM " . Constantes::BD_USERS . ".ROL_PERMISO "
                                . "where idpermiso='" . $_GET['id'] . "'");

                        //elimino el PERMISO en si
                        ObjetoDatos::getInstancia()->ejecutarQuery(""
                                . "DELETE FROM " . Constantes::BD_USERS . ".PERMISO "
                                . "where idpermiso='" . $_GET['id'] . "'");
                       
                    } catch (Exception $ex) {
                        $mensaje = "Ha ocurrido un error : {$ex->getCode()}";
                    }
                     ObjetoDatos::getInstancia()->commit();
                    ?>
                    <p><?= $mensaje; ?></p>
                    <fieldset>
                        <legend>Opciones</legend>
                        <a href="workflow.permisos.ver.php"><input type="button" value="Ir a Permisos" /></a>
                    </fieldset>
                </div>
            </article>
        </section>
        <?php include_once '../gui/GUIfooter.php'; ?>
    </body>
</html>