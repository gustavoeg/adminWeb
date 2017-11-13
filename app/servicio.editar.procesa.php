<?php
include_once '../lib/ControlAcceso.class.php';
ControlAcceso::requierePermiso(PermisosSistema::PERMISO_SERVICIOS);

$mensaje = "El Servicios ha sido modificaco con exito.";

ObjetoDatos::getInstancia()->autocommit(false);
ObjetoDatos::getInstancia()->begin_transaction();
//print_r($_POST);
    if (isset($_POST['valoracion'])) {
        if (isset($_POST['email'])) {
            $email = $_POST['email'];
        } else {
            $email = '';
        }
    } else {
        $email = $_POST['email'];
    }
    if(isset($_POST['habilitado'])){        //si esta seteado, esta checked (habilitado)
        $estado = 1;
    }else{
        $estado = 0;
    }
    try {
        //verificar si es que tiene valoraciones cargadas
        //no se debe modificar
        ObjetoDatos::getInstancia()->ejecutarQuery(""
                . "UPDATE " . Constantes::BD_USERS . ".servicios "
                . "SET email_valoraciones = '{$email}', habilitado = {$estado},icono = {$_POST['selecticon']},usuario_idusuario = {$_POST['idencargado']} "
                . "WHERE idservicios = {$_POST['idservicio']}");
                
    } catch (Exception $exc) {
        $mensaje = "Ha ocurrido un error. "
                . "Codigo de error MYSQL: " . $exc->getCode() . ". ";
        ObjetoDatos::getInstancia()->rollback();
    }

//$idusuario = ObjetoDatos::getInstancia()->insert_id;

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
