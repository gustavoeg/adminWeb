<?php
include_once '../lib/ControlAcceso.class.php';
ControlAcceso::requierePermiso(PermisosSistema::PERMISO_UBICACION);

$mensaje = "La Ubicacion ha sido agregada con exito.";

ObjetoDatos::getInstancia()->autocommit(false);
ObjetoDatos::getInstancia()->begin_transaction();
if (isset($_POST['nombre'])) {
    if (isset($_POST['qr'])) {
        //esta en condiciones de cargar los nuevos datos
        try {
            if(isset($_POST['dependencia']) && $_POST['dependencia'] != 0){
            ObjetoDatos::getInstancia()->ejecutarQuery(""
                    . "INSERT INTO " . Constantes::BD_USERS . ".ubicacion (idubicacion, nombre, codigo_qr, fk_ubicacion_idubicacion) "
                    . "VALUES (NULL, '{$_POST['nombre']}', '{$_POST['qr']}', {$_POST['dependencia']})");
            }else{
                ObjetoDatos::getInstancia()->ejecutarQuery(""
                    . "INSERT INTO " . Constantes::BD_USERS . ".ubicacion (idubicacion, nombre, codigo_qr) "
                    . "VALUES (NULL, '{$_POST['nombre']}', '{$_POST['qr']}')");
            }
        } catch (Exception $exc) {
            $mensaje = "Ha ocurrido un error. "
                    . "Codigo de error MYSQL: " . $exc->getCode() . ". ";
            ObjetoDatos::getInstancia()->rollback();
        }
        $idubicacion = ObjetoDatos::getInstancia()->insert_id;
        ObjetoDatos::getInstancia()->commit();
        
    } else {
        $mensaje = 'No se ha definido el Codigo QR';
    }
} else {
    $mensaje = "No esta definido el nombre.";
}
?>


<html>
    <head>
        <script>function alerta() {
                alert("<?php echo $mensaje; ?>");
            }
        </script>
        <title><?php echo Constantes::NOMBRE_SISTEMA; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <link href="../gui/estilo.css" type="text/css" rel="stylesheet" />
    </head>
    <body onload="alerta();">
        <?php include_once '../gui/GUImenu.php'; ?>
        <section id="main-content">
            <article>
                <div class="content">
                    <h3>Alta de Ubicaicon</h3>
                    <p><?php echo $mensaje; ?></p>
                    <fieldset>
                        <legend>Opciones</legend>
                        <a href="ubicacion.nuevo.php">
                            <input type="button" class="btn btn-success" value="Agregar Otro" />
                        </a>
                        <a href="ubicacion.ver.php">
                            <input type="button" class="btn btn-default" value="Ver Ubicaciones" />
                        </a>
                    </fieldset>    

                </div>
            </article>
        </section>
        <?php include_once '../gui/GUIfooter.php'; ?>
    </body>
</html>
