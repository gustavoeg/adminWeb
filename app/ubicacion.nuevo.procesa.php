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
        
        /* parte dde la creacion de la imagen qr         */
        require "../lib/generadorqr/qrlib.php";   

	// -------------------------------------------------------------------
	//RECUPERAR DATOS DEL FORMULARIO
        $nombre = $_POST['nombre'];
	$codigoQr = $_POST['qr'];
	// -------------------------------------------------------------------
	
	//Declaramos una carpeta temporal para guardar la imagenes generadas
	$dir = '../imagenes/temp/';
	
	//Si no existe la carpeta la creamos
	if (!file_exists($dir))
        mkdir($dir);
	
        //Declaramos la ruta y nombre del archivo a generar
	$filename = $dir.$nombre.'.png';
 
        //Parametros de Condiguración
	
	$tamaño = 10; //Tamaño de Pixel
	$level = 'L'; //Precisión L= Baja, M = Media, Q = Alta, H = Maxima
	$framSize = 3; //Tamaño en blanco
	$contenido = $codigoQr; //Texto Contenido
	
        //Enviamos los parametros a la Función para generar código QR 
	QRcode::png($contenido, $filename, $level, $tamaño, $framSize);   
        
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
                    <h3>Alta de Ubicacion</h3>
                    <p><?php echo $mensaje; ?></p>
                    <p>El codig QR generado es :</p>
                    <p><img src="<?php echo $dir.basename($filename);  ?>" /></p>
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
