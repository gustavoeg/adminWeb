<?php
include_once '../lib/ControlAcceso.class.php';
ControlAcceso::requierePermiso(PermisosSistema::PERMISO_HABILITA_EN_SECTOR);

$mensaje = "Se ha asociado la ubicacon y la valoracion con exito.";

$array_ubicaciones =$_POST['ubicacion'];

    if (isset($_POST['idvaloracion'])){
        ObjetoDatos::getInstancia()->autocommit(false);
        ObjetoDatos::getInstancia()->begin_transaction();
        
        //en este punto para que no haya problema de que ya esta asociado la valoracion, elimino las asociaciones y establezco las nuevas
        try {
            //eliminacion de las asociaciones previas, para que queden las nuevas.
            ObjetoDatos::getInstancia()->ejecutarQuery(""
                        . "DELETE FROM " . Constantes::BD_USERS . ".ubicacion_valoracion "
                        . "WHERE fk_valoraciones_idvaloraciones = {$_POST['idvaloracion']}");
                        
            foreach ($array_ubicaciones as $key => $value) {
                ObjetoDatos::getInstancia()->ejecutarQuery(""
                        . "INSERT INTO " . Constantes::BD_USERS . ".ubicacion_valoracion "
                        . "(idubicacion_valoracion, fk_ubicacion_idubicacion, fk_valoraciones_idvaloraciones)"
                        . "VALUES (NULL, '{$key}', '{$_POST['idvaloracion']}')"
                );
            }
            ObjetoDatos::getInstancia()->commit();
        }catch (Exception $exc) {
            $mensaje = "Ha ocurrido un error. "
                . "Codigo de error MYSQL: " . $exc->getCode() . ". ";
            ObjetoDatos::getInstancia()->rollback();
        }
        ObjetoDatos::getInstancia()->autocommit(TRUE);
    }else{
        //no hay valoracion seleccionada sobre la cual trabajar
        $mensaje = "No se ha asociado la ubicacon y la valoracion con exito. No hay valoracion seleccionada sobre la cual trabajar";
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
                    <h3>Habilitacion de valoraciones en Ubicacion</h3>
                    <p><?php echo $mensaje; ?></p>
                    <fieldset>
                        <legend>Opciones</legend>
                        <a href="habilita.sector.ver.php">
                            <input type="button" class="btn btn-primary" value="Ver Valoraciones y Ubicaciones" />
                        </a>
                    </fieldset>    

                </div>
            </article>
        </section>
        <?php include_once '../gui/GUIfooter.php'; ?>
    </body>
</html>
