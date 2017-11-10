<?php

include_once '../lib/ControlAcceso.class.php';
include_once '../modelo/Workflow.class.php';
ControlAcceso::requierePermiso(PermisosSistema::PERMISO_OPCIONES_VALORACION);
$UsuariosWorkflow = new WorkflowUsuarios();

include_once('../lib/easytemplate.php');

$contenedor = load("../gui/html/valoraciones.ver.html");


$contenedor = setVar($contenedor, 'nombre_sistema', Constantes::NOMBRE_SISTEMA);


$menu = get_include_contents("../gui/GUImenu.php");
$contenedor = replaceHTML($contenedor, 'encabezado', $menu);
/* parte en la que se obtiene el/los servicios del usuario encargado */
$res = ObjetoDatos::getInstancia()->ejecutarQuery(""
        . "SELECT s.idservicios, s.nombre as servicio "
        . "FROM " . Constantes::BD_SCHEMA . ".usuario u "
        . "join " . Constantes::BD_SCHEMA . ".servicios  s "
        . "ON u.idusuario=s.usuario_idusuario "
        . "WHERE u.idusuario = " . $_SESSION['usuario']->idusuario . " "
        . "ORDER BY s.nombre ASC ");
$cantidad = 0;
if ($res) {
    $cantidad = $res->num_rows;
}
/* si la cantidad es cero, mensaje advertencia y opcion de salir
 * si la cantidad es una, solo muestra ese servicio,
 * si la cantidad es mayor a una, se muestra el primer servicio y un combobox para los restantes
 *  */
if ($cantidad == 0) {
    //encargado sin servicio
    $titulo = "No hay servicio para administrar";
    //sacar el resto de la pagina
    $contenedor = delHTML($contenedor, 'hay_servicios');
} else {
    $primero = $res->fetch_assoc();
    $titulo = "Gestion de valoraciones para el servicio " . $primero['servicio'];

    //region de html para personalizar
    $hay_servicios = getHTML($contenedor, 'hay_servicios');

    if ($cantidad == 1) {
        $hay_servicios = delHTML($hay_servicios, 'select_servicio');
    } else {
        //encargado con mas de un servicio
        //mostrar un combobox para las demas opciones
        $select = "<select name='cambioServicio'><option value='" . $primero['idservicio'] . "'>" . $primero['servicio'] . "</option>";
        while ($option = $res->fetch_assoc()) {
            $select .= "<option value='" . $option['idservicio'] . "'>" . $option['servicio'] . "</option>";
        }
        $select .= "</select>";
        $hay_servicios = replaceHTML($contenedor, 'select_servicio', $select);
    }
    $contenedor = delHTML($contenedor, 'sin_servicio');

    //parte de la tabla principal
}
$contenedor = setvar($contenedor, 'titulo', $titulo);




/*                   idvaloraciones, nombre, tipo, recibir_notificacion_email, permite_foto, permite_descripcion,
  permite_email, habilitado, vencimiento,fk_servicios_idservicios */

$res = ObjetoDatos::getInstancia()->ejecutarQuery(""
        . "SELECT v.idvaloraciones, v.nombre, v.tipo, v.recibir_notificacion_email, v.permite_foto, v.permite_descripcion, v.permite_email, v.habilitado, v.vencimiento, s.nombre as servicio "
        . "FROM " . Constantes::BD_SCHEMA . ".valoraciones v "
        . "join " . Constantes::BD_SCHEMA . ".servicios  s "
        . "ON v.fk_servicios_idservicios = s.idservicios ");
$totalFilas = '';
while ($row = $res->fetch_assoc()) {
    $getFila = getHTML($hay_servicios, 'fila_servicio');
    $filas = setvar($getFila, 'valoracion_nombre', $row['nombre']);
    $filas = setvar($getFila, 'tipo', $row['tipo']);
    if (($row['recibir_notificacion_email']) != '') {
        $filas = setvar($getFila, 'recibir_notificacion_email', $row['recibir_notificacion_email'] . " (*)");
    } else {
        $filas = setvar($getFila, 'recibir_notificacion_email', $row['email_usuario']);
    }
    $filas = setvar($getFila, 'email_valoraciones', $row['email_valoraciones']);
    $filas = setvar($getFila, 'permite_email', $row['permite_email']);
    if ($row['habilitado'] == '1') {
        $hab = "Si";
    } else {
        $hab = "No";
    }
    $filas = setvar($getFila, 'habilitado', $hab);
    if ($row['permite_foto'] == '1') {
        $foto = "Si";
    } else {
        $foto = "No";
    }
    $filas = setvar($getFila, 'permite_foto', $foto);
    if ($row['permite_descripcion'] == '1') {
        $desc = "Si";
    } else {
        $desc = "No";
    }
    $filas = setvar($getFila, 'permite_descripcion', $descripcion);
    if ($row['permite_email'] == '1') {
        $email = "Si";
    } else {
        $email = "No";
    }
    $filas = setvar($getFila, 'permite_foto', $email);
    $filas = setvar($getFila, 'vencimiento', $row['vencimiento']);
    $filas = setvar($getFila, 'servicio', $row['servicio']);
    $filas = setvar($getFila, 'idvaloraciones', $row['idvaloraciones']);
    $totalFilas .= $filas;
}
$hay_servicios = replaceHTML($hay_servicios, 'fila_servicio', $totalFilas);
$contenedor = replaceHTML($contenedor, 'hay_servicios', $hay_servicios);

$contenedor = replaceHTML($contenedor, 'footer', get_include_contents('../gui/GUIfooter.php'));
echo cleanup($contenedor);

function get_include_contents($filename) {
    if (is_file($filename)) {
        ob_start();
        include $filename;
        return ob_get_clean();
    }
    return false;
}

?>