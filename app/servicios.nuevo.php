<?php
include_once '../lib/ControlAcceso.class.php';
ControlAcceso::requierePermiso(PermisosSistema::PERMISO_SERVICIOS);
?>
<html>
    <head>
        <title><?php echo Constantes::NOMBRE_SISTEMA; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        
        <meta name="google-signin-client_id" content="356408280239-7airslbg59lt2nped9l4dtqm2rf25aii.apps.googleusercontent.com" />
        <script type="text/javascript" src="https://apis.google.com/js/platform.js" async defer></script>
        
        <script src="../lib/validador.js" type="text/javascript"></script>
        <script src="../lib/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

        <link type="text/css" rel="stylesheet" href="../lib/jQueryToggleSwitch/css/rcswitcher.css">
        <script type="text/javascript" src="../lib/jQueryToggleSwitch/js/jquery-2.1.3.min.js"></script>
        <script type="text/javascript" src="../lib/jQueryToggleSwitch/js/rcswitcher.js"></script>

        <script src="../lib/imagepicker/image-picker.js" type="text/javascript"></script>
        <link href="../gui/estilo.css" type="text/css" rel="stylesheet" />
        <link href="../lib/imagepicker/image-picker.css" type="text/css" rel="stylesheet" />
        
    </head>
    <body>
        <?php include_once '../gui/GUImenu.php'; ?>
        <section id="main-content">
            <article>
                <div class="content">
                    <h3>Habilitacion de un nuevo Servicio</h3>
                    <p>Por favor complete los datos a continuaci&oacute;n. Los campos marcados con (*) son obligatorios.</p>
                    <form method="post" action="servicio.nuevo.procesa.php" name="formulario" >
                        <script type="text/javascript" language="javascript">var validador = new Validator("formulario");</script>
                        <fieldset>
                            <legend>Propiedades</legend>      

                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Nombre Servicio (*)</label>
                                <div class="col-sm-7">
                                    <input type="text"  name="nombre" size="22" maxlength="20" id="nombre" title="Nombre del Servicio" />
                                    <script>validador.addValidation("nombre", "obligatorio");</script>
                                    <script>validador.addValidation("nombre", "solotexto");</script>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Descripcion (*)</label>
                                <div class="col-sm-7">
                                    <textarea name="descripcion" rows="3" maxlength="140" id="descripcion"></textarea>
                                    <script>validador.addValidation("descripcion", "obligatorio");</script>
                                    <script>validador.addValidation("descripcion", "solotexto");</script>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Correo electr&oacute;nico Valoraciones (*)</label>
                                <div class="col-sm-7">
                                    <label style="font-weight:normal;">Mismo correo Encargado <input type="checkbox" name="valoracion" value="1" checked /> </label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;o Ingresar Email
                                    <input type="text" name="email" id="email" title="Correo electronico" size="45" maxlength="320" />
                                </div>
                            </div>
                            
                            <input type="hidden" name="estado" value="0">

                            <div class="form-group row">
                                <label for="habilitado" class="col-sm-5 col-form-label">Encargado (*)</label>
                                <div class="col-sm-7">
                                    <select name="idencargado" title="Encargado">
                                        <option value="0">Seleccione</option>
                                        <?php
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
                                            <option value="<?= $encargado['idusuario']; ?>" ><?= $encargado['nombre']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <script>validador.addValidation("idencargado", "selectOptions=0");</script>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="icono" class="col-sm-5 col-form-label">Icono</label>
                                <div class="col-sm-7">
                                    <img id="icono" src="../imagenes/iconos/png/000.png" />
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                                        Seleccionar Icono
                                    </button>
                                </div>
                            </div>

                                <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="exampleModalLabel">Haga click en una imagen para seleccionar</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" style="height:300px;overflow-y:scroll">
                                            <!--        en esta parte van las imagenes para seleccionar-->
                                            <select id="selecticon" class="image-picker show-html" name="selecticon">
                                                <option value=""></option>
                                                <?php
                                                for ($i = 1; $i <= 100; $i++) {

                                                    echo "<option data-img-src='../imagenes/iconos/png/" . $i . ".png' value='" . $i . "'>Icono$i</option>";
                                                }
                                                ?>
                                            </select>
                                            <!--        fin de la parte en que van las imagenes para seleccionar-->
                                        </div>
                                        <div class="modal-footer">
                                            <!--                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>-->
                                            <button type="button" class="btn btn-success" data-dismiss="modal">Aceptar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         
                        </fieldset>

                        <fieldset>
                            <legend>Opciones</legend>
                            <input type="submit" class="btn btn-success" onclick="return validar(document.formulario);" value="Guardar" />
                            <input type="reset" class="btn btn-default" value="Limpiar Campos" />
                            <a href="servicios.ver.php">
                                <input type="button" class="btn btn-default" value="Salir" />
                            </a>
                        </fieldset>
                        <p>&nbsp;</p>

                    </form>
                </div>
            </article>
            <script>
                $(document).ready(function () {
                    $("#email").prop('disabled', true);

                    $('.habilitado :checkbox').rcSwitcher({
                        // reverse: true,
                        inputs: false,
                        // width: 70,
                        // height: 24,
                        // blobOffset: 2,
                        onText: 'Si',
                        offText: 'No',
                        theme: 'light',
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

                    $("#selecticon").imagepicker({
                        hide_select: true,
                        show_label: false,
                        changed: function (select, newValues, oldValues, event) {
                            console.log("nuevo valor: " + newValues);
                            if (newValues.toString() !== '') {
                                //cambio la imagen cuando haga click en otra imagen
                                $("#icono").prop("src", "../imagenes/iconos/png/" + newValues.toString() + ".png");
                            } else {
                                $("#icono").prop("src", "../imagenes/iconos/png/000.png");
                            }
                        }
                    });
                    $('input[name="valoracion"]').on('change', function () {
                        var radioValue = $('input[name="valoracion"]:checked').val();
                        //alert(radioValue);
                        if (radioValue == 1) {
                            $("#email").prop('disabled', true);
                            $("#email").prop('value', '');
                            //validador.("email", "obligatorio");
                        } else {
                            $("#email").prop('disabled', false);
                        }
                        ;
                    });
                });

                function validar(formulario) {

                    //verificar que no esta tildado la casilla checkbox,
                    if (!formulario.valoracion.checked) {
                        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(formulario.email.value)) {
                            return (true);
                        } else {
                            alert("Formato de Email invalido!");
                            formulario.email.focus();
                            return (false);
                        }
                    } else {
                        return true;
                    }
                }

            </script>
        </section>
        <?php include_once '../gui/GUIfooter.php'; ?>
    </body>
</html>