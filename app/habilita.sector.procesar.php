<?php
include_once '../lib/ControlAcceso.class.php';
ControlAcceso::requierePermiso(PermisosSistema::PERMISO_HABILITA_EN_SECTOR);

$mensaje = "Se ha asociado la ubicacon y la valoracion con exito.";

ObjetoDatos::getInstancia()->autocommit(false);
ObjetoDatos::getInstancia()->begin_transaction();
//print_r($_POST);
$array_ubicaciones =$_POST['ubicacion'];
foreach ($array_ubicaciones as $key => $value) {
    print_r($key);//valor de id_ubicacion
}
    if (isset($_POST['idvaloracion'])){
        
    }else{
        $email = $_POST['email'];
    }
    
    try {
        ObjetoDatos::getInstancia()->ejecutarQuery(""
                . " " . Constantes::BD_USERS . ".servicios "
                . "SET email_valoraciones = '{$email}', nombre = '{$_POST['nombre']}', habilitado = {$estado},icono = {$_POST['selecticon']},usuario_idusuario = {$_POST['idencargado']} "
                . "WHERE idservicios = {$_POST['idservicio']}");
                
    } catch (Exception $exc) {
        $mensaje = "Ha ocurrido un error. "
                . "Codigo de error MYSQL: " . $exc->getCode() . ". ";
        ObjetoDatos::getInstancia()->rollback();
    }

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
                    <h3>Edicion de Servicios</h3>
                    <p><?php echo $mensaje; ?></p>
                    <fieldset>
                        <legend>Opciones</legend>
                        <a href="servicios.ver.php">
                            <input type="button" class="btn btn-primary" value="Ver Servicios" />
                        </a>
                    </fieldset>    

                </div>
            </article>
        </section>
        <?php include_once '../gui/GUIfooter.php'; ?>
    </body>
</html>
