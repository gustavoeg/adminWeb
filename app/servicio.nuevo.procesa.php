<?php
include_once '../lib/ControlAcceso.class.php';
ControlAcceso::requierePermiso(PermisosSistema::PERMISO_SERVICIOS);

$mensaje = "El Servicios ha sido agregado con exito.";

if (isset($_POST['nombre'])) {
    if (!isset($_POST['valoracion']) ) {
        if (isset($_POST['email'])) {
            $email = $_POST['email'];
        } else {
            $email = '';
        }
    } else {
        $email = '';
    }
    
    $estado = 0;       //cuando es nuevo, tiene que estar deshabilitado
    
    try {
        ObjetoDatos::getInstancia()->autocommit(false);
        ObjetoDatos::getInstancia()->begin_transaction();

        $resultado = ObjetoDatos::getInstancia()->ejecutarQuery(""
                . "INSERT INTO " . Constantes::BD_USERS . ".servicios (idservicios,nombre,descripcion,email_valoraciones,habilitado,icono,usuario_idusuario) "
                . "VALUES (NULL, '{$_POST['nombre']}', '{$_POST['descripcion']}', '{$email}', {$estado}, {$_POST['selecticon']},{$_POST['idencargado']})");

        if(!empty($resultado)){
            ObjetoDatos::getInstancia()->commit();
        }else{
            ObjetoDatos::getInstancia()->rollback();
            $mensaje = "No se pudo registrar el Nuevo Servicio";
        }
        
    } catch (Exception $exc) {
        $mensaje = "Ha ocurrido un error. "
                . "Codigo de error MYSQL: " . $exc->getCode() . ". ";
        ObjetoDatos::getInstancia()->rollback();
    }
}

?>

<html>
    <head>
        <script>function alerta() {
                alert("<?php echo $mensaje; ?>");
            }
        </script>
        <title><?php echo Constantes::NOMBRE_SISTEMA; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
