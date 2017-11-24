<?php
include_once '../lib/ControlAcceso.class.php';
ControlAcceso::requierePermiso(PermisosSistema::PERMISO_OPCIONES_VALORACION);

$mensaje = "La valoracion ha sido agregada con exito.";
//print_r($_POST);
ObjetoDatos::getInstancia()->autocommit(false);
ObjetoDatos::getInstancia()->begin_transaction();
/* Valores de POST: 
 * nombre, descripcion, tipo, recibir_notificacion, 
 * permite_foto, permite_descripcion, permite_email, 
 * habilitado, vencimiento, sinvencimiento */
if (isset($_POST['sinvencimiento'])) {
    /* coloco un valor indicativo, como negativo */
    $vencimiento = -1;
}else{
    /* el valor esta revisado por la forma en que se carga */
    $vencimiento = $_POST['vencimiento'];
}
if(isset($_POST['tipo'])){
    $tipo = $_POST['tipo'];
}else{
    $tipo = 'cualificacion';
}
$recibir_notificacion = isset($_POST['recibir_notificacion'])?1:0;
$permite_foto = isset($_POST['permite_foto'])?1:0;
$permite_descripcion = isset($_POST['permite_descripcion'])?1:0;
$permite_email = isset($_POST['permite_email'])?1:0;
$habilitado = isset($_POST['habilitado'])?1:0;
$idservicio = $_POST['idservicio'];

    try {
        ObjetoDatos::getInstancia()->ejecutarQuery(""
                . "INSERT INTO " . Constantes::BD_USERS . ".valoraciones "
                . "(idvaloraciones, nombre, descripcion, tipo, recibir_notificacion_email, permite_foto, permite_descripcion, permite_email, habilitado, vencimiento, fk_servicios_idservicios ) "
                . "VALUES "
                . "(NULL, '{$_POST['nombre']}', '{$_POST['descripcion']}', '{$tipo}', {$recibir_notificacion}, {$permite_foto}, {$permite_descripcion},{$permite_email},{$habilitado},{$vencimiento}, {$idservicio})");

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
                    <h3>Alta de Valoracion <?php echo mb_strtoupper($_POST['nombre']);?></h3>
                    <p><?php echo $mensaje; ?></p>
                    <fieldset>
                        <legend>Opciones</legend>
                        
                        <form action="valoraciones.nuevo.php" method="POST">
                            <input type="hidden" name="idservicio" value="<?php echo $idservicio; ?>" />
                            <input type="hidden" name="nombreservicio" value="<?php echo $_POST['nombreservicio']; ?>" />
                            <input type="submit" class="btn btn-primary" value="Agregar Otro" name="cambio" id="nuevo" />
                        </form>
                        
                        <form action="valoraciones.ver.php" method="POST">
                            <input type="hidden" name="idservicio" value="<?php echo $idservicio; ?>" />
                            <input type="submit" class="btn btn-primary" value="Ver Valoraciones" name="ver" id="ver" />
                        </form>
                    </fieldset>    

                </div>
            </article>
        </section>
        <?php include_once '../gui/GUIfooter.php'; ?>
    </body>
</html>
