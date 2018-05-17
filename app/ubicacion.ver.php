<?php
include_once '../lib/ControlAcceso.class.php';
ControlAcceso::requierePermiso(PermisosSistema::PERMISO_UBICACION);
?>
<html>
    <head>
        <title><?php echo Constantes::NOMBRE_SISTEMA; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

        <!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>-->
        <link rel="stylesheet" href="../lib/jstree/style.min.css" />
<!--        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">-->
        <!--<link rel="stylesheet" href="../lib/bootstrap/css/bootstrap.min.css">-->
<!--        <script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.2.min.js"></script>-->
        <script type="text/javascript" charset="utf8" src="../lib/jQuery/jquery-3.2.1.min.js"></script>
        <script src="../lib/jstree/jstree.js"></script>

        <!--<link href="../gui/estilo.css" type="text/css" rel="stylesheet" />-->
    </head>
    <body>
        <?php include_once '../gui/GUImenu.php'; ?>
        <section id="main-content">
<!--            <article>-->
                <div class="content">
                    <h3>Gesti&oacute;n de Ubicaciones</h3>
                    <p>A continuaci&oacute;n se muestran las ubicaciones en las que se podra realizar valoraciones.</p>
                    <div style="float: left; width: auto; height: auto" id="tree-container"></div>
                    <div style="float: right; width: auto; height: auto;" id="opciones">
                        <form method="POST" action="../lib/generador_qr/phpqrcode/index.php" id="pdf" >
                        <fieldset>
                            <legend>Codigo QR</legend>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Seleccionado</label>
                                <div class="col-sm-7">
                                    <input type="text"  name="actual" size="22" maxlength="20" id="actual" title="Ubicacion Seleccionada" disabled="true" />
                                    <input type="hidden"  name="nombre" id="nombre" />
                                    <input type="hidden"  name="actualid" id="actualid" />
                                    <input type="hidden"  name="destino" id="destino" value="<?php echo Constantes::WEBROOT?>" />
                                </div>
                            </div>
<!--                            <button type="button" id="imprimir" class="btn btn-primary active">Imprimir</button>-->
<input type="submit" id="imprimir" name="imprimir" value="imprimir" class="btn btn-primary active"/>
<!--                            <button type="button" id="descargar" class="btn btn-primary active">Descargar</button>-->
<input type="submit" id="descargar" value="descargar" name="descargar" class="btn btn-primary active"/>

<input type="button" id="imp" value="imp" name="imp" class="btn btn-primary active"/>
                        </fieldset>
                        </form>
                    </div>
                    <div style="float: right; width: auto; height: auto;display: none" id="nuevoNodo">
                        <form method="post" action="ubicacion.ver.response.php" name="formulario" >
<!--                        <script type="text/javascript" language="javascript">var validador = new Validator("formulario");</script>-->
                        <fieldset>
                            <legend>Nueva Ubicacion</legend>      
                            
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Nombre (*)</label>
                                <div class="col-sm-7">
                                    <input type="text"  name="nombre" size="22" maxlength="20" id="nombre" title="Nombre de la Ubicacion" />
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Dependencia</label>
                                <div class="col-sm-7">
                                    <input type="text"  name="dependencia" size="22" maxlength="20" id="dependencia" title="Dependencia actual" disabled="true" />
                                    <input type="hidden"  name="dependenciaid" id="dependenciaid" />
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Codigo QR (*)</label>
                                <div class="col-sm-7">
                                    <input type="text"  name="codigoqr" size="22" maxlength="20" id="codigoqr" title="Codigo QR" />
                                </div>
                            </div>
                         
                        </fieldset>

                        <fieldset>
                            <legend>Opciones</legend>
                            <input type="submit" class="btn btn-success"  value="Agregar" />
                            <input type="reset" class="btn btn-default" value="Limpiar Campos" />
                        </fieldset>
                    </form>
                    </div>
                </div>
<!--            </article>-->
        </section>

        <script type="text/javascript">
            $(document).ready(function () {
                //codigo para imprimir pdf generado
                function print(url)  {
                    var _this = this,
                    iframeId = 'iframeprint',
                    $iframe = $('iframe#iframeprint');
                    $iframe.attr('src', url);

                    $iframe.load(function() {
                        _this.callPrint(iframeId);
                    });
                }

                //initiates print once content has been loaded into iframe
                function callPrint(iframeId) {
                    var PDF = document.getElementById(iframeId);
                    PDF.focus();
                    PDF.contentWindow.print();
                }
                
                //fill data to tree  with AJAX call
                $('#tree-container').jstree({
                    'core': {
                        'data': {
                            'url': 'ubicacion.ver.response.php?operation=get_node',
                            'data': function (node) {
                                return {'id': node.id};
                            },
                            "dataType": "json"
                        }
                        , 'check_callback': true,
                        'themes': {
                            'responsive': false
                        }
                    },
                    'plugins': ['state', 'contextmenu', 'wholerow']
                }).on('create_node.jstree', function (e, data) {
                    console.log("creando:");
                    $.get('ubicacion.ver.response.php?operation=create_node', {'id': data.node.parent, 'position': data.position, 'text': data.node.text})
                            .done(function (d) {
                                console.log("done al crear:"+d);                
                                d = JSON.parse(d);
                                
                                data.instance.set_id(data.node, d.id);
                                console.log("rama:"+d.id + 'padre:' + data.node.parent);
                            })
                            .fail(function () {console.log("falla al crear:");
                                data.instance.refresh();
                            });
                }).on('rename_node.jstree', function (e, data) {console.log("renombrando:");
                    $.get('ubicacion.ver.response.php?operation=rename_node', {'id': data.node.id, 'text': data.text})
                            .fail(function () {
                                data.instance.refresh();
                            });
                }).on('delete_node.jstree', function (e, data) {
                    $.get('ubicacion.ver.response.php?operation=delete_node', {'id': data.node.id})
                            .done(function (d) {
                                d = JSON.parse(d);
                                console.log('d:' + d + 'd.respuesta:' + d.respuesta);
                                if(d.respuesta=='no'){
                                    switch(d.causa) {
                                        case 1451:
                                            {
                                                //hay dependencias asociadas a la ubicacion
                                                alert('No se puede eliminar porque hay dependencias de esta ubicacion.');
                                            }
                                            break;
                                        case 1:
                                            {
                                                alert('No se puede eliminar');
                                            }
                                            break;
                                        default:
                                            {
                                                alert('No se puede eliminar');
                                            }
                                    } 
                                    data.instance.refresh();
                                }else{
                                    console.log("Eliminado correcto:" + d.respuesta);
                                }
                            })
                            .fail(function () {
                                data.instance.refresh();
                            });
                }).on('changed.jstree', function (e, data) {

            $('#nombre').val(data.instance.get_node(data.selected[0]).text);
            $('#actual').val(data.instance.get_node(data.selected[0]).text);;
            $('#actualid').val(data.instance.get_node(data.selected[0]).id);
          });
        
        //accion del boton "imprimir"
        $( "#imp" ).click(function() {
            //1 generacion del pdf con el codigo qr
            print('../lib/generador_QR/phpqrcode/temp/SECTORB.pdf');
            //2 instruccion de impresion
        });
        
                
        //accion del boton "descargar"
        $( "#descargar" ).click(function() {
            //1 generacion del pdf con el codigo qr
            //generarpdf("descargable");
            //2 enviar como descargable dicho pdf
        });
        
       
    });
    //funcion de generacion del pdf
        function generarpdf(descargable){
            var descarga = false;
            if(descargable == "descargable"){
                descarga = true;
            }console.log('nombre:' + $("#actual").val()+' codigoqr:'+ $("#actualid").val() + ' descargable:' + descarga);
            $.ajax({
                type: "POST",
                url: '../lib/generador_qr/phpqrcode/index.php',  
                data:{nombre:$("#actual").val(),codigoQr:$("#actualid").val(),descargable:descarga},
                dataType: 'json',
                success:function(data) {
                    alert(data);
                    console.log('exito de llamada de descarga');
                },
                fail:function(){
                    alerta('fallo en generador pdf');
                }
            });
        }
        </script>
        <?php include_once '../gui/GUIfooter.php'; ?>
    </body>
</html>