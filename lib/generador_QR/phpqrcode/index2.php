<?php
	//Agregamos la libreria para genera códigos QR
	require "C:/xampp/htdocs/generador_QR/phpqrcode/qrlib.php";   
	
	//Agregamos la libreria para generar PDF
	require "C:/xampp/htdocs/generador_QR/phpqrcode/fpdf/fpdf.php"; 

	$pdf = new FPDF('P','mm','A4');
	$pdf->AddPage();
	$pdf->Image('C:/xampp/htdocs/generador_QR/phpqrcode/fpdf/plantillaQR.jpg',0,0,210,297);
	
	// -------------------------------------------------------------------
	//RECUPERAR DATOS DEL FORMULARIO
    $nombre = $_POST['nombre'];
	$codigoQr = $_POST['codigoQr'];
	// -------------------------------------------------------------------

	
	//Declaramos una carpeta temporal para guardar la imagenes generadas
	$dir = 'temp/';
	
	//Si no existe la carpeta la creamos
	if (!file_exists($dir))
        mkdir($dir);
	
        //Declaramos la ruta y nombre del archivo a generar
	$filename = $dir.$nombre.'.png';
 
        //Parametros de Condiguración
	
	$tamaño = 10; //Tamaño de Pixel
	$level = 'L'; //Precisión L= Baja, M = Media, Q = Alta, H = Maxima
	$framSize = 3; //Tamaño en blanco
	$contenido = $codigoQr; //Texto Contenido
	
        //Enviamos los parametros a la Función para generar código QR 
	QRcode::png($contenido, $filename, $level, $tamaño, $framSize); 
	
	$pdf->Image('C:/xampp/htdocs/generador_QR/phpqrcode/temp/'.$nombre.'.png',45,40,120,120);
	$pdf->Output('C:/xampp/htdocs/generador_QR/phpqrcode/temp/'.$nombre.'.pdf',"F");
	
        //Mostramos la imagen generada
	$pdf->Output();
	  
?>