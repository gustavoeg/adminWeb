<?php
include_once '../lib/ControlAcceso.class.php';
include_once '../modelo/Workflow.class.php';
ControlAcceso::requierePermiso(PermisosSistema::PERMISO_USUARIOS);
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
                    <h4>Usuario del Sistema</h4>
                    <p>A continuaci&oacute;n se muestran servicios del Sistema.</p>
                    <p>
                    <fieldset>
                        <legend>Opciones</legend>
<!--                        creacion del boton NUEVO-->
                        <a href="servicios.nuevo.php" class="btn btn-primary btn-md">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Habilitar
                        </a>
                    </fieldset>
                    </p>

                    <table id="tablaservicios" class="display table table-bordered table-stripe" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Encargado</th>
                                <th>Email Encargado</th>
                                <th>Email Valoraciones</th>
                                <th>Habilitado</th>
                                <th>Acci&oacute;n</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            //$res = $mysqli->query("SELECT * FROM checkpoint.servicios");
                            $res = ObjetoDatos::getInstancia()->ejecutarQuery(""
                    . "SELECT s.idservicios, s.nombre, s.email_valoraciones, s.habilitado, s.icono, u.nombre as encargado "
                                    . "FROM " . Constantes::BD_SCHEMA . ".servicios s "
                                    . "join " . Constantes::BD_SCHEMA . ".usuario u "
                                    . "on s.usuario_idusuario=u.idusuario ");
                            while ($row = $res->fetch_assoc()):
                            //foreach ($UsuariosWorkflow->getUsuarios() as $WorkflowUsuario) {
                                ?>
                                <tr>
                                    <td><?php echo $row['nombre'] ?></td>
                                    <td><?php echo $row['encargado'] ?></td>
                                    <td><?php echo $row['email_valoraciones'] ?></td>
                                    <td>icono <?php echo $row['icono'] ?></td>
                                    <td><?php echo $row['habilitado'] ?></td>
                                    <td>
                                        <a href="update.php?u=<?php echo $row['idservicios'] ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Editar</a>
                                        <a onclick="return confirm('Deshabilitar')" href="servicios.eliminar.php?id=<?php echo $row['idservicios'] ?>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Eliminar</a>
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
<script src="/lib/datatables/dataTables.responsive.min.js"></script>
<script src="/lib/datatables/responsive.bootstrap.min.js"></script>
	
    <script type="text/javascript" charset="utf-8">
         $(document).ready(function() {
   $('#tablaservicios').dataTable();
 });
    </script>
        <?php include_once '../gui/GUIfooter.php'; ?>
    </body>
</html>