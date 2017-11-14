<?php
include_once '../lib/ControlAcceso.class.php';
ControlAcceso::requierePermiso(PermisosSistema::PERMISO_HABILITA_EN_SECTOR);
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
                    <h3>Gesti&oacute;n de Valoraciones en Sectores</h3>
                    <p>A continuaci&oacute;n se muestran las ubicaciones y las valoraciones correspondientes.</p>

                    <table id="tablaubicacionvalor" class="display table table-bordered table-stripe" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Valoracion</th>
                                <th>Tipo</th>
                                <th>Habilitado</th>
                                <th>Servicio</th>
                                <th>Acci&oacute;n</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $res = ObjetoDatos::getInstancia()->ejecutarQuery(""
                                    . "SELECT v.idvaloraciones, v.nombre, v.tipo, v.habilitado, s.nombre as servicio "
                                    . "FROM " . Constantes::BD_SCHEMA . ".valoraciones v "
                                    . "JOIN " . Constantes::BD_SCHEMA . ".servicios  s ON v.fk_servicios_idservicios = s.idservicios "
                                    . "JOIN " . Constantes::BD_SCHEMA . ".usuario u ON u.idusuario = s.usuario_idusuario "
                                    . "WHERE u.idusuario= {$_SESSION['usuario']->idusuario} "
                                    . "ORDER BY servicio, v.nombre");
                            
                            while ($row = $res->fetch_assoc()):

                                ?>
                                <tr>
                                    <td><?php echo $row['nombre'] ?></td>
                                    <td><?php echo $row['tipo'] ?></td>
                                    <td><?php if($row['habilitado'] == '1'){echo "Si";}else{echo "No";}  ?></td>
                                    <td><?php echo $row['servicio'] ?></td>
                                    <td>
                                        <a href="habilita.sector.editar.php?id=<?php echo $row['idvaloraciones'] ?>">
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
        <script src="/lib/datatables/dataTables.responsive.min.js"></script>
        <script src="/lib/datatables/responsive.bootstrap.min.js"></script>

        <script type="text/javascript" charset="utf-8">
                                        $(document).ready(function () {
                                            $('#tablaubicacionvalor').dataTable({
                                                "oLanguage": {
                                                    "sUrl": "../lib/datatables/Spanish.json"
                                                }
                                            });
                                        });
        </script>
        <?php include_once '../gui/GUIfooter.php'; ?>
    </body>
</html>