<?php
include_once '../lib/ControlAcceso.class.php';
ControlAcceso::requierePermiso(PermisosSistema::PERMISO_SERVICIOS);

$mensaje = "El Servicio ha sido modificado con exito.";

//print_r($_POST);
if (isset($_POST['valoracion'])){
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
    }else{
        $email = '';
    }
}else{
    $email = $_POST['email'];
}

if(isset($_POST['habilitado'])){
    $estado = 1;
    //en este caso hay que validar si hay valoraciones
}else{
    $estado = 0;
    //se modifica directamente
}
try {
    //verificar si es que tiene valoraciones cargadas para el caso de habilitacion
    $puedeActualizar = TRUE;
    if($estado == 1){       //peticion de habilitacion
        $cantidadValoraciones = ObjetoDatos::getInstancia()->ejecutarQuery("select count(*) as cantidad from valoraciones v "
            ."join ubicacion_valoracion uv on uv.fk_valoraciones_idvaloraciones = v.idvaloraciones"
            . " where v.fk_servicios_idservicios = {$_POST['idservicio']} and v.habilitado = 1");
        $cantidad = $cantidadValoraciones->fetch_assoc();
        if($cantidad['cantidad'] > 0){
            //el servicio tiene valoraciones cargadas y habilitadas y asociadas a una ubicacion para ser elejido desde el movil
            //codigo para actualizar
            
            $puedeActualizar = TRUE;
            
        }else{
            //Mostrar mensaje indicando que no se puede habilitar porque no tiene valoraciones
            $mensaje = "No se ha podido habilitar porque no tiene valoraciones";
            $puedeActualizar = FALSE;
        }
    }
    if($puedeActualizar){
        ObjetoDatos::getInstancia()->autocommit(false);
            ObjetoDatos::getInstancia()->begin_transaction();
            $resultado = ObjetoDatos::getInstancia()->ejecutarQuery(""
                . "UPDATE " . Constantes::BD_USERS . ".servicios "
                . "SET email_valoraciones = '{$email}', nombre = '{$_POST['nombre']}', descripcion = '{$_POST['descripcion']}', habilitado = {$estado},icono = {$_POST['selecticon']},usuario_idusuario = {$_POST['idencargado']} "
                . "WHERE idservicios = {$_POST['idservicio']}");
            if(empty($resultado)){
                //echo "Incorrecto:";
                $mensaje = "No se pudieron registrar los cambios solicitados";
                ObjetoDatos::getInstancia()->rollback();
            }
            ObjetoDatos::getInstancia()->commit();
    }else{
        
    }
} catch (Exception $exc) {
$mensaje = "Ha ocurrido un error. "
. "Codigo de error MYSQL: " . $exc->getCode() . ". ";
ObjetoDatos::getInstancia()->rollback();
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
