<?php
include_once '../lib/ControlAcceso.class.php';
ControlAcceso::requierePermiso(PermisosSistema::PERMISO_OPCIONES_VALORACION);

$mensaje = "El Servicios ha sido agregado con exito.";
print_r($_POST);
ObjetoDatos::getInstancia()->autocommit(false);
ObjetoDatos::getInstancia()->begin_transaction();
/* Valores de POST: 
 * nombre, tipo, recibir_notificacion, 
 * permite_foto, permite_descripcion, permite_email, 
 * habilitado, vencimiento, sinvencimiento */
if (isset($_POST['sinvencimiento'])) {
    /* coloco un valor indicativo, como negativo */
    $vencimiento = -1;
}else{
    /* el valor esta revisado por la forma en que se carga */
    $vencimiento = $_POST['vencimiento'];
}
$recibir_notificacion = isset($_POST['recibir_notificacion'])?1:0;
$permite_foto = isset($_POST['permite_foto'])?1:0;
$permite_descripcion = isset($_POST['permite_descripcion'])?1:0;
$permite_email = isset($_POST['permite_email'])?1:0;
$habilitado = isset($_POST['habilitado'])?1:0;


    try {
        ObjetoDatos::getInstancia()->ejecutarQuery(""
                . "INSERT INTO " . Constantes::BD_USERS . ".valoraciones (idvaloraciones, nombre, tipo, recibir_notificacion_email, permite_foto, permite_descripcion, permite_email, habilitado, vencimiento) "
                . "VALUES (NULL, '{$_POST['nombre']}', '{$recibir_notificacion}', {$permite_foto}, {$permite_descripcion},{$permite_email},{$habilitado},{$vencimiento})");
    } catch (Exception $exc) {
        $mensaje = "Ha ocurrido un error. "
                . "Codigo de error MYSQL: " . $exc->getCode() . ". ";
        ObjetoDatos::getInstancia()->rollback();
    }
$idusuario = ObjetoDatos::getInstancia()->insert_id;

ObjetoDatos::getInstancia()->commit();
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
                    <h3>Alta de Servicios</h3>
                    <p><?php echo $mensaje; ?></p>
                    <fieldset>
                        <legend>Opciones</legend>
                        <a href="servicios.nuevo.php">
                            <input type="button" value="Agregar Otro" />
                        </a>
                        <a href="servicios.ver.php">
                            <input type="button" value="Ver Servicios" />
                        </a>
                    </fieldset>    

                </div>
            </article>
        </section>
        <?php include_once '../gui/GUIfooter.php'; ?>
    </body>
</html>
