<?php
include_once '../lib/ControlAcceso.class.php';
//include_once '../modelo/Workflow.class.php';
ControlAcceso::requierePermiso(PermisosSistema::PERMISO_OPCIONES_VALORACION);
?>
<html>
    <head>
        <title><?php echo Constantes::NOMBRE_SISTEMA; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        
        <meta name="google-signin-client_id" content="356408280239-7airslbg59lt2nped9l4dtqm2rf25aii.apps.googleusercontent.com" />
        <script type="text/javascript" src="https://apis.google.com/js/platform.js" async defer></script>
        
<script src="../lib/datatables/jquery.js"></script>

<script src="../lib/validador.js" type="text/javascript"></script>
<script src="../lib/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../lib/bootstrap/js/bootstrap-slider.min.js" type="text/javascript"></script>

        
<link type="text/css" rel="stylesheet" href="../lib/jQueryToggleSwitch/css/rcswitcher.css">
<script type="text/javascript" src="../lib/jQueryToggleSwitch/js/jquery-2.1.3.min.js"></script>
<script type="text/javascript" src="../lib/jQueryToggleSwitch/js/rcswitcher.js"></script>

        
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.9.0/css/bootstrap-slider.min.css" type="text/css" rel="stylesheet" />
        <link href="../gui/estilo.css" type="text/css" rel="stylesheet" />
        
        
    </head>
    <body>
        <?php include_once '../gui/GUImenu.php'; ?>
        <section id="main-content">
            <article>
                <div class="content">
                    <h3>Habilitacion de una nueva Valoracion</h3>
                    <p>Por favor complete los datos a continuaci&oacute;n. Los campos marcados con (*) son obligatorios.</p>
                    <form method="post" action="valoraciones.nuevo.procesa.php" name="formulario" >
                        <script type="text/javascript" language="javascript">var validador = new Validator("formulario");</script>
                        <fieldset>
                            <legend>Propiedades</legend>
                            
                            <p>Nombre Valoracion (*)<br>
                                <input type="text" name="nombre" id="nombre" title="Nombre de la Valoracion" />
                                <script>validador.addValidation("nombre", "obligatorio");</script>
                                <script>validador.addValidation("nombre", "solotexto");</script>
                            </p>

                            <p>Tipo de Valoracion (*)<br>
                               <select name="tipo" title="Tipo de valoracion">
                                    <option value="0">Seleccione</option>
                                    <?php
                                    /* consulta para obtener los tipos de valoraciones que estan 
                                     * contenido en la definicion del campo enumerado */
                                    $datos = ObjetoDatos::getInstancia()->ejecutarQuery(""
                                            . "SELECT u.idusuario, u.nombre "
                                            . "FROM " . Constantes::BD_USERS . ".usuario u "
                                            . "join " . Constantes::BD_USERS . ".usuario_rol ur on u.idusuario=ur.idusuario "
                                            . "join " . Constantes::BD_USERS . ".rol r on r.idrol=ur.idrol "
                                            . "where r.nombre='" . PermisosSistema::ROL_ENCARGADO . "' "
                                            . "order by u.nombre asc;");
                                    for ($x = 0; $x < $datos->num_rows; $x++) {
                                        $encargado = $datos->fetch_assoc();
                                        ?>
                                        <option value="<?= $encargado['idusuario']; ?>"><?= $encargado['nombre']; ?></option>
                                    <?php } ?>
                                </select>
                            <p/>

                            <p class="habilitado">Recibir Notificacion<br />
                                <input type="checkbox" name="recibir_notificacion" value="1" checked/><br />
                            </p>

                            <p class="habilitado">Permite Foto<br />
                                <input type="checkbox" name="pertmite_foto" value="1" checked/><br />
                            </p>
                            
                            <p class="habilitado">Permite Descripcion<br />
                                <input type="checkbox" name="permite_descripcion" value="1" checked/><br />
                            </p>
                            
                            <p class="habilitado">Permite Email<br />
                                <input type="checkbox" name="permite_email" value="1" checked/><br />
                            </p>
                            
                            <p class="habilitado">Habilitado<br />
                                <input type="checkbox" name="habilitado" value="1" checked/><br />
                            </p>
                            <p>Vencimiento (*)<br />
                                <input id="ex7" type="text" data-slider-min="0" data-slider-max="20" data-slider-step="1" data-slider-value="5" data-slider-enabled="false"/>
                                <input id="ex7-enabled" type="checkbox"/> Sin Vencimiento
                            </p>
                            
                        </fieldset>

                        <fieldset>
                            <legend>Opciones</legend>
                            <input type="submit" class="btn btn-success" value="Guardar" />
                            <input type="reset" class="btn btn-default" value="Limpiar Campos" />
                            <a href="valoraciones.ver.php">
                                <input type="button" class="btn btn-default" value="Salir" />
                            </a>
                        </fieldset>
                        <p>&nbsp;</p>

                    </form>
                </div>
            </article>
            <script>
                $(document).ready(function () {
                    //$("#ex7").slider();
                    var slider = new Slider("#ex7");

                    
                    $("#ex7-enabled").click(function() {
                        if(this.checked) {
                            // With JQuery
                            //$("#ex7").slider("enable");
                            
                            slider.enable();
                        }else {
                            // With JQuery
                            //$("#ex7").slider("disable");
                            
                            slider.disable();
                       	}
                    });

                    $('.habilitado :checkbox').rcSwitcher({
                        // reverse: true,
                        inputs: false,
                        // width: 70,
                        // height: 24,
                        // blobOffset: 2,
                        onText: 'Si',
                        offText: 'No',
                        theme: 'light'
                        // autoFontSize: true,
                    }).on({
                        'enable.rcSwitcher': function (e, data)
                        {
                            console.log('Enabled', data);
                        },
                        'disable.rcSwitcher': function (e, data)
                        {
                            console.log('Disabled');
                        }
                    }); 
                });
            </script>
        </section>
        <?php include_once '../gui/GUIfooter.php'; ?>
    </body>
</html>