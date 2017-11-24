<?php
include_once '../lib/ControlAcceso.class.php';

ControlAcceso::requierePermiso(PermisosSistema::PERMISO_OPCIONES_VALORACION);
$servicio = $_POST['idservicio'];        //ya esta trabajando sobre un servicio, por lo que esta definido
$nombreservicio = $_POST['nombreservicio'];
?>
<html>
    <head>
        <title><?php echo Constantes::NOMBRE_SISTEMA; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        
        <noscript>
            <meta http-equiv="refresh" content="0; URL=nojs/index.php">
        </noscript>
        
        <script src="../lib/datatables/jquery.js"></script>

        <script src="../lib/validador.js" type="text/javascript"></script>
        <script src="../lib/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../lib/bootstrap/js/bootstrap-slider.min.js" type="text/javascript"></script>
        <script src="../lib/bootstrapSwitch/js/bootstrap-switch.min.js"></script>
        
        <link href="../lib/bootstrap/css/bootstrap-slider.min.css" type="text/css" rel="stylesheet" />
        <link href="../lib/bootstrapSwitch/css/bootstrap3/bootstrap-switch.min.css" rel="stylesheet">
        <link href="../gui/estilo.css" type="text/css" rel="stylesheet" />
        
        
    </head>
    <body>
        <?php include_once '../gui/GUImenu.php'; ?>
        <section id="main-content">
            <article>
                <div class="content">
                    <h3>Nueva Valoracion para el servicio <?php echo mb_strtoupper($nombreservicio);?></h3>
                    <p>Por favor complete los datos a continuaci&oacute;n. Los campos marcados con (*) son obligatorios.</p>
                    <form method="post" action="valoraciones.nuevo.procesa.php" name="formulario" id="formulario" >
                        <script type="text/javascript" language="javascript">var validador = new Validator("formulario");</script>
                        <fieldset>
                            <legend>Propiedades</legend>
                            <div class="form-group row">
                                <label for="nombre" class="col-sm-4 col-form-label">Nombre Valoracion (*)</label>
                                <div class="col-sm-8">
                                    <input type="text" name="nombre" id="nombre" title="Nombre de la Valoracion" />
                                    <script>validador.addValidation("nombre", "obligatorio");</script>
                                    <script>validador.addValidation("nombre", "solotexto");</script>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="descripcion" class="col-sm-4 col-form-label">Descripcion</label>
                                <div class="col-sm-8">
                                    <textarea name="descripcion" rows="3" maxlength="140" id="descripcion" title="Texto descriptivo"></textarea>
                                    <script>validador.addValidation("descripcion", "obligatorio");</script>
                                    <script>validador.addValidation("descripcion", "solotexto");</script>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="tipo" class="col-sm-4 col-form-label">Tipo de Valoracion (*)</label>
                                <div class="col-sm-8">
                                    <select id="tipo" name="tipo" title="Tipo de valoracion" disabled="true">
                                    <?php
                                    /* consulta para obtener los tipos de valoraciones que estan 
                                     * contenido en la definicion del campo enumerado */
                                    $datos = ObjetoDatos::getInstancia()->ejecutarQuery(""
                                            . "SHOW COLUMNS "
                                            . "FROM " . Constantes::BD_USERS . ".valoraciones LIKE 'tipo';");
                                    if($datos->num_rows > 0) {
                                        $tipo = $datos->fetch_row();
                                        preg_match_all("/'([\w ]*)'/", $tipo[1], $values);
                                    }
                                    foreach ($values[1] as $opcion){ ?>
                                        <option value="<?= $opcion; ?>"><?= $opcion; ?></option>
                                    <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="recibirNotificacion" class="col-sm-4 col-form-label">Recibir Notificacion por Email</label>
                                <div class="col-sm-8">
                                    <input id="recibirNotificacion" type="checkbox" name="recibir_notificacion" data-label-width="5"  data-on-text="Si" data-off-text="No" data-size="mini" value="1" checked/>
                                </div>
                            </div>

                            <div class="form-group row">
                                    <label for="permite_foto" class="col-sm-4 col-form-label">Permite Foto</label>
                                    <div class="col-sm-8">
                                        <input id="permite_foto" type="checkbox" name="pertmite_foto" data-label-width="5"  data-on-text="Si" data-off-text="No" data-size="mini" value="1" checked/>
                                    </div>
                            </div>
                            
                            <div class="form-group row">
                                    <label for="permite_descripcion" class="col-sm-4 col-form-label">Permite Descripcion</label>
                                    <div class="col-sm-8">
                                        <input id="permite_descripcion" type="checkbox" name="permite_descripcion" data-label-width="5"  data-on-text="Si" data-off-text="No" data-size="mini" value="1" checked/>
                                    </div>
                            </div>
                            
                            <div class="form-group row">
                                    <label for="permite_email" class="col-sm-4 col-form-label">Permite Email</label>
                                    <div class="col-sm-8">
                                        <input id="permite_email" type="checkbox" name="permite_email" data-label-width="5"  data-on-text="Si" data-off-text="No" data-size="mini" value="1" checked/>
                                    </div>
                            </div>
                            
                            <div class="form-group row">
                                    <label for="habilitado" class="col-sm-4 col-form-label">Habilitado</label>
                                    <div class="col-sm-8">
                                        <input id="habilitado" type="checkbox" name="habilitado" data-label-width="5"  data-on-text="Si" data-off-text="No" data-size="mini" value="1" checked/>
                                    </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="ex7" class="col-sm-4 col-form-label">Vencimiento (d&iacute;as)</label>
                                <div class="col-sm-8">
                                    <input name="vencimiento" id="ex7" type="text" data-slider-ticks="[0, 100, 200, 300, 400]" data-slider-ticks-labels='["$0", "$100", "$200", "$300", "$400"]' data-slider-min="1" data-slider-max="15" data-slider-step="1" data-slider-value="1" data-slider-enabled="true"/>
                                    &nbsp;&nbsp;&nbsp;
                                    <input id="ex7-enabled"  name="sinvencimiento"type="checkbox"/> Sin Vencimiento
                                </div>
                            </div>
                            
                            <input type="hidden" name="idservicio" value="<?php echo $_POST['idservicio'];?>" />
                            <input type="hidden" name="nombreservicio" value="<?php echo $nombreservicio; ?>" />
                        </fieldset>

                        <fieldset>
                            <legend>Opciones</legend>
                            <input type="submit" class="btn btn-success" value="Guardar" />
                            <input type="reset" class="btn btn-default" value="Limpiar Campos" />
                           
                                <input type="button" id="salir" class="btn btn-default" onClick="chgAction();" value="Salir" />
                           
                        </fieldset>
                        <script language="javascript" type="text/javascript">
                            function chgAction(){
                                document.getElementById("formulario").action ="valoraciones.ver.php";
                                document.getElementById("formulario").submit();
                            }
                        </script>

                    </form>
                </div>
            </article>
            <script>
                $(document).ready(function () {
                    //$("#ex7").slider();
                    var slider = new Slider("#ex7", {
                         ticks: [1, 5, 10, 15],
                         ticks_labels: ['1', '5', '10', '15'],
                         ticks_snap_bounds: 2,
                        tooltip: 'always'
                    });

                    
                    $("#ex7-enabled").click(function() {
                        if(this.checked) {
                            // With JQuery
                            //$("#ex7").slider("enable");
                            
                            slider.disable();
                        }else {
                            // With JQuery
                            //$("#ex7").slider("disable");
                            
                            slider.enable();
                       	}
                    });
                    
                    $("[name='recibir_notificacion']").bootstrapSwitch();
                    $("[name='pertmite_foto']").bootstrapSwitch();
                    $("[name='permite_descripcion']").bootstrapSwitch();
                    $("[name='permite_email']").bootstrapSwitch();
                    $("[name='habilitado']").bootstrapSwitch();

                });
            </script>
        </section>
        <?php include_once '../gui/GUIfooter.php'; ?>
    </body>
</html>