<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "checkpoint";

$conn = mysqli_connect($servername, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

 

if(isset($_GET['operation'])) {
	try {
		$result = null;
		switch($_GET['operation']) {
			case 'get_node':
				$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
				$sql = "SELECT idubicacion as id, nombre as text,fk_ubicacion_idubicacion as parent_id FROM ubicacion;";
				$res = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
				if($res->num_rows <=0){
				 //add condition when result is zero
				} else {
					//iterate on results row and create new index array of data
					while( $row = mysqli_fetch_assoc($res) ) { 
						$data[] = $row;
					}
					$itemsByReference = array();

				// Build array of item references:
				foreach($data as $key => &$item) {
				   $itemsByReference[$item['id']] = &$item;
				   // Children array:
				   $itemsByReference[$item['id']]['children'] = array();
				   // Empty data class (so that json_encode adds "data: {}" ) 
				   $itemsByReference[$item['id']]['data'] = new StdClass();
				}

				// Set items as children of the relevant parent item.
				foreach($data as $key => &$item)
				   if($item['parent_id'] && isset($itemsByReference[$item['parent_id']]))
					  $itemsByReference [$item['parent_id']]['children'][] = &$item;

				// Remove items that were added to parents elsewhere:
				foreach($data as $key => &$item) {
				   if($item['parent_id'] && isset($itemsByReference[$item['parent_id']]))
					  unset($data[$key]);
				}
				}
				$result = $data;
				break;
			case 'create_node':
				$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
				
				$nodeText = isset($_GET['text']) && $_GET['text'] !== '' ? $_GET['text'] : '';
                                //obtener el path de nodos padres para colocar como nombre del QR
                                $aux = $node;
                                $nombre = "";
                                $contador=0;
                                
                                // se toma como NULL el id para finalizacion porque se trata de la raiz
                                while( $aux!=NULL && $contador<10){
                                    $sql ="SELECT * FROM `ubicacion` where idubicacion=".$aux;
                                    $resulta = mysqli_query($conn, $sql);
                                    if($row = mysqli_fetch_assoc($resulta)){
                                        $aux = $row["fk_ubicacion_idubicacion"];
                                        $nombre = $row["nombre"]."@".$nombre;
                                    }
                                    //solucionar: la cadena que se va formando con el nombre, excede la cantidad de caracteres permitidos
                                    /*
                                    echo "<br>aux:".$aux;
                                    echo "<br>nombre:".$nombre;
                                    echo "<br>sql:".$sql;
                                     */
                                    $contador++;
                                }
                                $nombre = $nombre.$nodeText;
                                echo "<br>nombre asignado:".$nombre;
				$sql ="INSERT INTO `ubicacion` (`nombre`, `codigo_qr`, `fk_ubicacion_idubicacion`) VALUES('".$nodeText."', '".$nombre."', '".$node."')";
				mysqli_query($conn, $sql);
				
				$result = array('id' => mysqli_insert_id($conn));
                                echo json_encode($result);die;
				//print_r($result);die;
				break;
			case 'rename_node':
				$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
				//print_R($_GET);
				$nodeText = isset($_GET['text']) && $_GET['text'] !== '' ? $_GET['text'] : '';
                                
                                //ahora el codigo_qr se cambia teniendo en cuenta la cadena desde la raiz.
                                //ejemplo, qr actual: raiz@sactor@aula@computador1 Y nuevo qr, pc1 ==> raiz@sactor@aula@pc1 
                                //
                                //tener en cuenta al momento de cambiar el nombre
                                
                                //obtener el codigo_qr
                                $sql ="SELECT nombre,codigo_qr,fk_ubicacion_idubicacion FROM `ubicacion` where idubicacion=".$node;
                                $resulta = mysqli_query($conn, $sql);
                                if($row = mysqli_fetch_assoc($resulta)){
                                    $qr = $row["codigo_qr"];
                                }
                                echo"<br>consulta_qr:".$sql;
                                echo"<br>codigo_qr:".$qr;
                                $pos = strripos($qr,"@");
                                if($pos === false){
                                    //estado de inconsistencia
                                    echo "<br>estado de inconsistencia.";
                                }else{
                                    $nuevonombre = substr($qr, 0,$pos+1).$nodeText;
                                }
                                echo "<br>nuevo nombre editado:".$nuevonombre;
				$sql ="UPDATE `ubicacion` SET `nombre`='".$nodeText."',`codigo_qr`='".$nuevonombre."' WHERE `idubicacion`= '".$node."'";
				mysqli_query($conn, $sql);
				break;
			case 'delete_node':
				$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
				$sql ="DELETE FROM `ubicacion` WHERE `idubicacion`= '".$node."'";
                                if(! mysqli_query($conn, $sql)){
                                    
                                    $data = ['respuesta' => "no", 'causa' => (mysqli_errno($conn)),'descripcion' => mysqli_error($conn)];
                                }else{
                                    $data = ['respuesta' => "si", 'causa' => "normal"];
                                }
                                
                                echo json_encode($data);die;
				break;
			default:
				throw new Exception('Unsupported operation: ' . $_GET['operation']);
				break;
		}
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($result);
	}
	catch (Exception $e) {
		header($_SERVER["SERVER_PROTOCOL"] . ' 500 Server Error');
		header('Status:  500 Server Error');
		echo $e->getMessage();
	}
	die();
}


?>

