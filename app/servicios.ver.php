<?php
include_once '../lib/ControlAcceso.class.php';
include_once '../modelo/Workflow.class.php';
ControlAcceso::requierePermiso(PermisosSistema::PERMISO_SERVICIOS);
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
                    <h3>Gesti&oacute;n de Servicios</h3>
                    <p>A continuaci&oacute;n se muestran servicios del Sistema. Los Email con (*) no son de encargados.</p>
                    <p>
                    <fieldset>
                        <legend>Opciones</legend>
<!--                        creacion del boton NUEVO-->
                        <a href="servicios.nuevo.php" class="btn btn-primary btn-md">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo
                        </a>
                    </fieldset>
                    </p>

                    <table id="tablaservicios" class="display table table-bordered table-stripe" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Encargado</th>
                                <th>Email Valoraciones</th>
                                <th>Icono</th>
                                <th>Habilitado</th>
                                <th>Acci&oacute;n</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            //$res = $mysqli->query("SELECT * FROM checkpoint.servicios");
                            $res = ObjetoDatos::getInstancia()->ejecutarQuery(""
                    . "SELECT s.idservicios, s.nombre, s.email_valoraciones, s.habilitado, s.icono, u.nombre as encargado, u.email as email_usuario "
                                    . "FROM " . Constantes::BD_SCHEMA . ".servicios s "
                                    . "join " . Constantes::BD_SCHEMA . ".usuario u "
                                    . "on s.usuario_idusuario=u.idusuario ");
                            while ($row = $res->fetch_assoc()):
                            //foreach ($UsuariosWorkflow->getUsuarios() as $WorkflowUsuario) {
                                ?>
                                <tr>
                                    <td><?php echo $row['nombre'] ?></td>
                                    <td><?php echo $row['encargado'] ?></td>
                                    <td><?php if(($row['email_valoraciones']) != ''){
                                        echo $row['email_valoraciones']." (*)";
                                    }else{
                                        echo $row['email_usuario'];
                                    }
                                     ?></td>
                                    <td><img src="../imagenes/iconos/png/<?php echo $row['icono'].".png";  ?>" width="32" /></td>
                                    <td><?php if($row['habilitado'] =='1'){echo "Si";}else{echo "No";} ?></td>
                                    <td>
                                        <a href="servicios.editar.php?id=<?php echo $row['idservicios'] ?>">
                                            <img src="../imagenes/abm_ver.png" title="Ver/Editar">
                                        </a>
                                       
                                    </td>
                                </tr>
                                <?php
                            endwhile;
                           // }
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
<script src="../lib/datatables/dataTables.responsive.min.js"></script>
<script src="../lib/datatables/responsive.bootstrap.min.js"></script>
	
    <script type="text/javascript" charset="utf-8">
         $(document).ready(function() {
   $('#tablaservicios').dataTable({
       "oLanguage": {
            "sUrl": "../lib/datatables/Spanish.json"
            }
   });
 });
    </script>
        <?php include_once '../gui/GUIfooter.php'; ?>
    </body>
</html>