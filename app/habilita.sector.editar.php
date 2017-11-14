<?php
include_once '../lib/ControlAcceso.class.php';
include_once '../modelo/Workflow.class.php';
ControlAcceso::requierePermiso(PermisosSistema::PERMISO_HABILITA_EN_SECTOR);

$continuar = TRUE;    //para saber si hay datos sobre los que se puede trabajar

if(isset($_GET['id'])){
    $valoracion_actual = $_GET['id'];
    $res = ObjetoDatos::getInstancia()->ejecutarQuery(""
          . "SELECT v.idvaloraciones, v.nombre, v.tipo, v.habilitado, s.nombre as servicio , u.nombre as usuario "
          . "FROM " . Constantes::BD_SCHEMA . ".valoraciones v "
          . "join " . Constantes::BD_SCHEMA . ".servicios  s ON v.fk_servicios_idservicios = s.idservicios "
          . "join " . Constantes::BD_SCHEMA . ".usuario u ON u.idusuario = s.usuario_idusuario "
          . "WHERE u.idusuario= {$_SESSION['usuario']->idusuario} and v.idvaloraciones = {$valoracion_actual} "
          . "ORDER BY servicio, v.nombre");
    if($res->num_rows == 1){
        $row = $res->fetch_assoc();
    }else{
      //no hay valoracion para el id proporcionado.
      //no hay una valoracion sobre la que se pueda trabajar
        $continuar = FALSE;
    }
}else{
    //no hay una valoracion sobre la que se pueda trabajar
    $continuar = FALSE;
}
  
  
?>

<html>
    <head>
        <title><?php echo Constantes::NOMBRE_SISTEMA; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <script src="../lib/validador.js" type="text/javascript"></script>
        <link href="../gui/estilo.css" type="text/css" rel="stylesheet" />
    </head>
    <body>
        <?php include_once '../gui/GUImenu.php'; ?>
        <section id="main-content">
            <article>
                <div class="content">
                    <?php if($continuar){ ?>
                    <h3>Ubicaciones para la Valoracion "<?= $row['nombre']; ?>"</h3>
                    <form method="post" name="formulario" enctype="multipart/form-data" action="habilita.sector.procesar.php">
                        <input type="hidden" name="idvaloracion" value="<?php echo $valoracion_actual;?>"
                        <p>Seleccione las ubicaciones en las que se aplicara la valoracion actual.</p>
                        
                        <fieldset>
                            <legend>Ubicaciones</legend>
                            <?php 
                            //hay que ver si ya tenia asociado algunas ubicaciones

                            //obtencion de las ubicaciones que ya tenia esa valoracion
                            $res = ObjetoDatos::getInstancia()->ejecutarQuery(""
                                    . "SELECT fk_ubicacion_idubicacion as idubicacion, idubicacion_valoracion "
                                    . "FROM " . Constantes::BD_SCHEMA . ".ubicacion_valoracion uv "
                                    . "WHERE uv.fk_valoraciones_idvaloraciones={$valoracion_actual} ");
                                    /*echo "SELECT fk_ubicacion_idubicacion as idubicacion, idubicacion_valoracion "
                                    . "FROM " . Constantes::BD_SCHEMA . ".ubicacion_valoracion uv "
                                    . "WHERE uv.fk_valoraciones_idvaloraciones={$valoracion_actual} ";*/
                            $pila_ubicaciones = array();
                            while($value = $res->fetch_assoc()) {
                                array_push($pila_ubicaciones, $value['idubicacion']);
                            }
                            
                            //obtencion de las ubicaciones.
                            $res = ObjetoDatos::getInstancia()->ejecutarQuery(""
                                    . "SELECT u.idubicacion, u.nombre, u.codigo_qr "
                                    . "FROM " . Constantes::BD_SCHEMA . ".ubicacion u "
                                    . "ORDER BY u.nombre ASC");
                            if($res->num_rows > 0){
                                while($row = $res->fetch_assoc()){  ?>
                            <input type="checkbox" name="ubicacion[<?php echo $row['idubicacion']?>]" <?php if(in_array($row['idubicacion'], $pila_ubicaciones)){echo "checked";}?> />
                                <label><?php echo $row['nombre'];?></label><br />
                                <?php }
                            }?>
                        </fieldset>
                        
                        <fieldset>
                            <legend>Opciones</legend>
                            <input type="submit" value="Guardar Cambios" class="btn btn-success"/>
                            <a href="habilita.sector.ver.php">
                                <input type="button" value="Salir" class="btn btn-primary"/>
                            </a>
                        </fieldset>
                        <p>&nbsp;</p>
                        
                    </form>
                    <?php }else{ ?>
                    <h3>No se ha especificado o no hay una valoracion para asignar.</h3>
                    <a href="habilita.sector.ver.php">
                        <input type="button" value="Salir" class="btn btn-primary"/>
                    </a>
                    <?php }?>
                </div>
            </article>
        </section>
        <?php include_once '../gui/GUIfooter.php'; ?>
    </body>
</html>