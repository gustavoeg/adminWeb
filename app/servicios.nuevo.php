<?php
include_once '../lib/ControlAcceso.class.php';
//include_once '../modelo/Workflow.class.php';
ControlAcceso::requierePermiso(PermisosSistema::PERMISO_SERV_HABILITAR);

?>
<html>
    <head>
        <title><?php echo Constantes::NOMBRE_SISTEMA; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <script src="../lib/validador.js" type="text/javascript"></script>
        <link href="../gui/estilo.css" type="text/css" rel="stylesheet" />
    </head>
    <body>
        <?php include_once '../gui/GUImenu.php'; ?>
        <section id="main-content">
            <article>
                <div class="content">
                    <h3>Habilitacion de un nuevo Servicio</h3>
                    <p>Por favor complete los datos a continuaci&oacute;n. Los campos marcados con (*) son obligatorios.</p>
                    <form method="post" action="workflow.usuario.nuevo.procesa.php" name="formulario" >
                        <script type="text/javascript" language="javascript">var validador = new Validator("formulario");</script>
                        <fieldset>
                            <legend>Propiedades</legend>      
                            <p>Nombre Servicio (*)<br>
                                <input type="text" name="nombre" id="nombre" title="Nombre del Servicio" />
                                <script>validador.addValidation("nombre", "obligatorio");</script>
                                <script>validador.addValidation("nombre", "solotexto");</script>
                            </p>

                            <p>Correo electr&oacute;nico Valoraciones (*)<br>
                                <input type="text" name="email" id="email" title="Correo electronico" />
                                <script>validador.addValidation("email", "obligatorio");</script>
                                <script>validador.addValidation("email", "email");</script>
                            <p/>

                            <p>Habilitado<br />
                                <input type="radio" name="estado" value="Activo" checked />Si
                                <input type="radio" name="estado" value="Inactivo" />No
                            </p>

                            <p>Encargado (*)<br />

                                <select name="idencargado" title="Encargado">
                                    <option value="0">Seleccione</option>
                                    <?php
                                    $datos = ObjetoDatos::getInstancia()->ejecutarQuery(""
                                            . "SELECT idencargado, nombre "
                                            . "FROM encargado "
                                            . "ORDER BY nombre");
                                    for ($x = 0; $x < $datos->num_rows; $x++) {
                                        $encargado = $datos->fetch_assoc();
                                        ?>
                                        <option value="<?= $encargado['idencargado']; ?>"><?= $encargado['nombre']; ?></option>
                                    <?php } ?>
                                </select>
                                <script>validador.addValidation("idencargado", "selectOptions=0");</script>
                            </p>
                        </fieldset>

                        <fieldset>
                            <legend>Opciones</legend>
                            <input type="submit" value="Guardar" />
                            <input type="reset" value="Limpiar Campos" />
                            <a href="workflow.usuarios.ver.php">
                                <input type="button" value="Salir" />
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