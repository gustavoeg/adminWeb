<?php
include_once '../lib/ControlAcceso.class.php';
ControlAcceso::requierePermiso(PermisosSistema::PERMISO_UBICACION);
?>
<html>
    <head>
        <title><?php echo Constantes::NOMBRE_SISTEMA; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <script src="../lib/validador.js" type="text/javascript"></script>
        <link href="../gui/estilo.css" type="text/css" rel="stylesheet" />
    </head>
    <body onload="document.getElementById('nombre').focus()">
        <?php include_once '../gui/GUImenu.php'; ?>
        <section id="main-content">
            <article>
                <div class="content">
                    <h3>Nueva Ubicacion</h3>
                    <p>Por favor complete los datos a continuaci&oacute;n. Los campos marcados con (*) son obligatorios.</p>
                    <form method="post" action="ubicacion.nuevo.procesa.php" name="formulario" >
                        <script type="text/javascript" language="javascript">var validador = new Validator("formulario");</script>
                        <fieldset>
                            Nombre (*)<br>
                            <input type="text" name="nombre" id="nombre" title="Nombre de la Ubicacion" />
                            <script>validador.addValidation("nombre", "obligatorio");</script>
                            <script>validador.addValidation("nombre", "solotexto");</script>
                            <br />
                            Codigo Qr (*)<br>
                            <input type="text" name="qr" id="nombre" title="Codigo QR" />
                            <script>validador.addValidation("qr", "obligatorio");</script>
                            <script>validador.addValidation("qr", "solotexto");</script>
                            <br />
                            Dependencia (*)<br>
                             <select name="dependencia" title="Dependencia">
                                    <option value="0">Seleccione</option>
                                    <?php
                                    $datos = ObjetoDatos::getInstancia()->ejecutarQuery(""
                                            . "SELECT idubicacion, nombre "
                                            . "FROM " . Constantes::BD_USERS . ".ubicacion u "
                                            . "where 1 "
                                            . "order by u.nombre asc;");
                                    for ($x = 0; $x < $datos->num_rows; $x++) {
                                        $dependencia = $datos->fetch_assoc();
                                        ?>
                                        <option value="<?= $dependencia['idubicacion']; ?>"><?= $dependencia['nombre']; ?></option>
                                    <?php } ?>
                                </select>
                            <br />
                        </fieldset>
                        <fieldset>
                            <legend>Opciones</legend>
                            <input type="submit" class="btn btn-success" value="Guardar" />
                            <input type="reset" class="btn btn-default" value="Limpiar Campos" />
                            <a href="ubicacion.ver.php">
                                <input type="button" class="btn btn-default" value="Salir" />
                            </a>
                        </fieldset>
                        <p>&nbsp;</p>


                    </form>
                </div>
            </article>
        </section>
        <?php include_once '../gui/GUIfooter.php'; ?>
    </body>
</html>