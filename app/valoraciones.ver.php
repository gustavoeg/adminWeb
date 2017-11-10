<?php
include_once '../lib/ControlAcceso.class.php';
include_once '../modelo/Workflow.class.php';
ControlAcceso::requierePermiso(PermisosSistema::PERMISO_OPCIONES_VALORACION);
$UsuariosWorkflow = new WorkflowUsuarios();
?>
<html>
    <head>
        <title><?php echo Constantes::NOMBRE_SISTEMA; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <link href="../gui/estilo.css" type="text/css" rel="stylesheet" />
    </head>
    <body>
        <?php include_once '../gui/GUImenu.php'; ?>
        <section id="main-content">
            <article>
                <div class="content">
                    <?php 
                    /* parte en la que se obtiene el/los servicios del usuario encargado */
                    $res = ObjetoDatos::getInstancia()->ejecutarQuery(""
                            . "SELECT s.idservicios, s.nombre as servicio "
                            . "FROM " . Constantes::BD_SCHEMA . ".usuario u "
                            . "join " . Constantes::BD_SCHEMA . ".servicios  s "
                            . "ON u.idusuario=s.usuario_idusuario "
                            . "WHERE u.idusuario = 7 "
                            . "ORDER BY s.nombre ASC ");
                            while ($row = $res->fetch_assoc()):
                            
                                ?>
                                <tr>
                                    <td><?php echo $row['nombre'] ?></td>
                    ?>
                    <h3>Gesti&oacute;n de Valoraciones del servicio «<?php ?>»</h3>
                    <p>A continuaci&oacute;n se muestran las valoracioens disponibles del Sistema.</p>
                    <p>
                    <fieldset>
                        <legend>Opciones</legend>
<!--                        creacion del boton NUEVO-->
                        <a href="valoraciones.nuevo.php" class="btn btn-primary btn-md">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nueva
                        </a>
                    </fieldset>
                    </p>

                    <table id="tablavaloraciones" class="display table table-bordered table-stripe" cellspacing="0" width="100%">
                        <thead>
                            <tr>
<!--                   idvaloraciones, nombre, tipo, recibir_notificacion_email, permite_foto, permite_descripcion,
                        permite_email, habilitado, vencimiento,fk_servicios_idservicios -->
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Notificacion Email</th>
                                <th>Foto</th>
                                <th>descripcion</th>
                                <th>Email</th>
                                <th>Vencimiento</th>
                                <th>Habilitado</th>
                                <th>Servicio</th>
                                <th>Acci&oacute;n</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $res = ObjetoDatos::getInstancia()->ejecutarQuery(""
                    . "SELECT v.idvaloraciones, v.nombre, v.tipo, v.recibir_notificacion_email, v.permite_foto, v.permite_descripcion, v.permite_email, v.habilitado, v.vencimiento, s.nombre as servicio "
                                    . "FROM " . Constantes::BD_SCHEMA . ".valoraciones v "
                                    . "join " . Constantes::BD_SCHEMA . ".servicios  s "
                                    . "ON v.fk_servicios_idservicios = s.idservicios ");
                            while ($row = $res->fetch_assoc()):
                            
                                ?>
                                <tr>
                                    <td><?php echo $row['nombre'] ?></td>
                                    <td><?php echo $row['tipo'] ?></td>
                                    <td><?php if(($row['recibir_notificacion_email']) != ''){
                                        echo $row['email_valoraciones']." (*)";
                                    }else{
                                        echo $row['email_usuario'];
                                    }
                                     ?></td>
                                    <td><?php if($row['habilitado'] =='1'){echo "Si";}else{echo "No";} ?></td>
                                    <td><?php if($row['permite_foto'] =='1'){echo "Si";}else{echo "No";} ?></td>
                                    <td><?php if($row['permite_descripcion'] =='1'){echo "Si";}else{echo "No";} ?></td>
                                    <td><?php if($row['permite_email'] =='1'){echo "Si";}else{echo "No";} ?></td>
                                    
                                    <td><?php echo $row['vencimiento']; ?></td>
                                    <td><?php echo $row['servicio']; ?></td>
                                    
                                    <td>
                                        <a href="valoraciones.editar.php?id=<?php echo $row['idvaloraciones'] ?>">
                                            <img src="../imagenes/abm_ver.png" title="Ver/Editar">
                                        </a>
                                       
                                        <a onclick="return confirm('Seguro que desea Deshabilitar?')" href="valoraciones.eliminar.php?id=<?php echo $row['idvaloraciones'] ?>">
                                            <img src="../imagenes/abm_eliminar.png" title="Eliminar">
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            endwhile;
                            ?>
                        </tbody>
                    </table> 
      
                    <p>&nbsp;</p>
                </div>
            </article>
        </section>
<!--        parte en la que va para que se implemente datatables-->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="../lib/datatables/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include <span id="IL_AD8" class="IL_AD">individual</span> files as needed -->
<script src="../lib/bootstrap/js/bootstrap.min.js"></script>
<script src="../lib/datatables/jquery.dataTables.min.js"></script>
<script src="../lib/datatables/dataTables.bootstrap.min.js"></script>
<script src="/lib/datatables/dataTables.responsive.min.js"></script>
<script src="/lib/datatables/responsive.bootstrap.min.js"></script>
	
    <script type="text/javascript" charset="utf-8">
         $(document).ready(function() {
   $('#tablavaloraciones').dataTable({
       "oLanguage": {
            "sUrl": "../lib/datatables/Spanish.json"
            }
   });
 });
    </script>
        <?php include_once '../gui/GUIfooter.php'; ?>
    </body>
</html>