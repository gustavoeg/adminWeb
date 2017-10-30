<?php
include_once '../lib/ControlAcceso.class.php';
//include_once '../modelo/Workflow.class.php';
ControlAcceso::requierePermiso(PermisosSistema::PERMISO_SERVICIOS);
?>
<html>
    <head>
        <title><?php echo Constantes::NOMBRE_SISTEMA; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <script src="../lib/validador.js" type="text/javascript"></script>
        <script src="../lib/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
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
                            <p>Nombre Servicio (*)<br>
                                <input type="text" name="nombre" id="nombre" title="Nombre del Servicio" />
                                <script>validador.addValidation("nombre", "obligatorio");</script>
                                <script>validador.addValidation("nombre", "solotexto");</script>
                            </p>

                            <p>Correo electr&oacute;nico Valoraciones (*)<br>
                                <input type="radio" name="valoracion" value="1" checked />Mismo que encargado
                                <input type="radio" name="valoracion" value="0" />otro 
                                <input type="text" name="email" id="email" title="Correo electronico" />
                                <script>//validador.addValidation("email", "obligatorio");</script>
                                <script>validador.addValidation("email", "email");</script>
                            <p/>

                            <p>Habilitado<br />
                                <input type="radio" name="estado" value="1" checked />Si
                                <input type="radio" name="estado" value="0" />No
                            </p>

                            <p>Encargado (*)<br />

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
                                        <option value="<?= $encargado['idusuario']; ?>"><?= $encargado['nombre']; ?></option>
                                    <?php } ?>
                                </select>
                                <script>validador.addValidation("idencargado", "selectOptions=0");</script>
                            </p>
                            <p>
                                <img id="icono" src="../imagenes/iconos/png/000.png"  />

                                <!-- Button trigger modal -->
                                <button type="button" data-toggle="modal" data-target="#exampleModal">
                                    Seleccionar Icono
                                </button>

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

                                                    echo "<option data-img-src='../imagenes/iconos/png/" . str_pad($i, 3, "0", STR_PAD_LEFT) . ".png' value='" . str_pad($i, 3, "0", STR_PAD_LEFT) . "'>Icono$i</option>";
                                                }
                                                ?>
                                            </select>
                                            <!--        fin de la parte en que van las imagenes para seleccionar-->
                                        </div>
                                        <div class="modal-footer">
<!--                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>-->
                                            <button type="button"  data-dismiss="modal">Aceptar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </p>
                        </fieldset>

                        <fieldset>
                            <legend>Opciones</legend>
                            <input type="submit" value="Guardar" />
                            <input type="reset" value="Limpiar Campos" />
                            <a href="servicios.ver.php">
                                <input type="button" value="Salir" />
                            </a>
                        </fieldset>
                        <p>&nbsp;</p>

                    </form>
                </div>
            </article>
            <script>
                $(document).ready(function(){
                     $("#email").prop('disabled', true);
                 });
                $("#selecticon").imagepicker({
                    hide_select: true,
                    show_label: false,
                    changed: function (select, newValues, oldValues, event) {
                        if (newValues !== '') {
                            //cambio la imagen cuando haga click en otra imagen
                            $("#icono").prop("src", "../imagenes/iconos/png/" + newValues.toString() + ".png");
                        } else {
                            $("#icono").prop("src", "../imagenes/iconos/png/000.png");
                        }
                    }
                });
                $('input[name="valoracion"]').on('change', function() {
                    var radioValue = $('input[name="valoracion"]:checked').val();   
                    //alert(radioValue);
                    if(radioValue == 1){
                        $("#email").prop('disabled', true);
                        $("#email").prop('value', '');
                        //validador.("email", "obligatorio");
                    }else{
                        $("#email").prop('disabled', false);
                    };
                });
                
            </script>
        </section>
        <?php include_once '../gui/GUIfooter.php'; ?>
    </body>
</html>