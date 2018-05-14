<?php
	//Agregamos la libreria para genera códigos QR
	require "./qrlib.php";   
	
	//Agregamos la libreria para generar PDF
	require "./fpdf/fpdf.php"; 

	$pdf = new FPDF('P','mm','A4');
	$pdf->AddPage();        
	$pdf->Image('./fpdf/plantillaQR.jpg',0,0,210,297);
	
	// -------------------------------------------------------------------
	//RECUPERAR DATOS DEL FORMULARIO
        $nombre = $_POST['nombre'];
	//$nombre = $_GET['nombre'];
	$codigoQr = $_POST['codigoQr'];
	//$codigoQr = $_GET['codigoQr'];
       // $destino = $_POST['destino']; 
        $descargable = $_POST['descargable']; 
        //echo $descargable;
	// -------------------------------------------------------------------

	
	//Declaramos una carpeta temporal para guardar la imagenes generadas
	$dir = 'temp/';
	
	//Si no existe la carpeta la creamos
	if (!file_exists($dir))
        mkdir($dir);
	
        //Declaramos la ruta y nombre del archivo a generar
	$filename = $dir.$nombre.'.png';
        $filenameqr = $dir.$nombre.'.pdf';
 
        //Parametros de Condiguración
	
	$tamaño = 10; //Tamaño de Pixel
	$level = 'L'; //Precisión L= Baja, M = Media, Q = Alta, H = Maxima
	$framSize = 3; //Tamaño en blanco
	$contenido = $codigoQr; //Texto Contenido
	
        //Enviamos los parametros a la Función para generar código QR 
	QRcode::png($contenido, $filename, $level, $tamaño, $framSize); 
	
	$pdf->Image('./'.$filename,10,30,80,80);
        if($descargable){
            $pdf->Output('F','./'.$dir.$nombre.'.pdf');
        }else{
            $pdf->Output();
        }
	
        $existe = is_file('./'.$dir.$nombre.'.pdf');
        if($existe){
             $data = ['respuesta' => "si", 'nombre' => ($nombre.'.pdf'),'causa' => "normal"];
        }else{
            $data = ['respuesta' => "no", 'causa' => "varias"];
        }
       
        echo(json_encode($data));
?>