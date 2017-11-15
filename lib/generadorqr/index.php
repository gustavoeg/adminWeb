<?php
	//Agregamos la libreria para genera códigos QR
	require "C:/xampp\htdocs/generador_QR/phpqrcode/qrlib.php";   

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
	
        //Mostramos la imagen generada
	echo '<img src="'.$dir.basename($filename).'" /><hr/>';  
?>