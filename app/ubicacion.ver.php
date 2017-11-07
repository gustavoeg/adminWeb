<?php
include_once '../lib/ControlAcceso.class.php';
ControlAcceso::requierePermiso(PermisosSistema::PERMISO_USUARIOS);
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
                    <h3>Gesti&oacute;n de Ubicaciones</h3>
                    <p>A continuaci&oacute;n se muestran las ubicaciones en las que se podra realizar valoraciones.</p>
                    <p>
                    <fieldset>
                        <legend>Opciones</legend>
                        <!--                        creacion del boton NUEVO-->
                        <a href="ubicacion.nuevo.php" class="btn btn-primary btn-md">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo
                        </a>
                    </fieldset>
                    </p>

                    <table id="tablaubicacion" class="display table table-bordered table-stripe" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Codigo Qr</th>
                                <th>Dependencia</th>
<!--                                <th>Habilitado</th>-->
                                <th>Acci&oacute;n</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            //$res = $mysqli->query("SELECT * FROM checkpoint.servicios");
                            $res = ObjetoDatos::getInstancia()->ejecutarQuery(""
                                    . "SELECT u.idubicacion, u.nombre, u.codigo_qr, u2.nombre as dependencia "
                                    . "FROM " . Constantes::BD_SCHEMA . ".ubicacion u "
                                    . "LEFT join " . Constantes::BD_SCHEMA . ".ubicacion u2 "
                                    . "on u.fk_ubicacion_idubicacion = u2.idubicacion ");
                            while ($row = $res->fetch_assoc()):
                                //foreach ($UsuariosWorkflow->getUsuarios() as $WorkflowUsuario) {
                                ?>
                                <tr>
                                    <td><?php echo $row['nombre'] ?></td>
                                    <td><?php echo $row['codigo_qr'] ?></td>
                                    <td><?php
                                        if (($row['dependencia']) != '') {
                                            echo $row['dependencia'];
                                        } else {
                                            echo "Sin dependencia";
                                        }
                                        ?></td>
                                    <td>
                                        <a href="ubicacion.editar.php?id=<?php echo $row['idubicacion'] ?>">
                                            <img src="../imagenes/abm_ver.png" title="Ver/Editar">
                                        </a>

                                        <a onclick="return confirm('Seguro que desea Eliminar?')" href="ubicacion.eliminar.php?id=<?php echo $row['idubicacion'] ?>">
                                            <img src="../imagenes/abm_eliminar.png" title="Eliminar">
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
        <script src="/lib/datatables/dataTables.responsive.min.js"></script>
        <script src="/lib/datatables/responsive.bootstrap.min.js"></script>

        <script type="text/javascript" charset="utf-8">
                                        $(document).ready(function () {
                                            $('#tablaubicacion').dataTable({
                                                "oLanguage": {
                                                    "sUrl": "../lib/datatables/Spanish.json"
                                                }
                                            });
                                        });
        </script>
        <?php include_once '../gui/GUIfooter.php'; ?>
    </body>
</html>