<?php 
/*
	EasyTemplate Version 1.0
	For more information visit http://www.onlinetools.org/tools/easytemplate/
*/

function setvar($doc,$var,$value){
		return preg_replace('/%%'.$var.'%%/',$value,$doc);
}

function getHTMLpara($doc,$sw){
	preg_match("/<!--\s".$sw.":(.*?)\s-->/si",$doc,$res);
	return $res[1];
}

function getHTML($doc,$elm){
	preg_match("/<!--\sstart:".$elm."\s-->(.*?)<!--\send:".$elm."\s-->/si",$doc,$res);
	return $res[0];
}

function delHTML($doc,$elm){
	foreach(explode(',',$elm) as $e){
		$doc=preg_replace('/\s<!--\sstart:'.$e.'\s-->(.*?)<!--\send:'.$e.'\s-->\s/si','',$doc);
	}
	return $doc;
}

function replaceHTML($doc,$elm,$new){
	$doc=preg_replace('/<!--\sstart:'.$elm.'\s-->(.*?)<!--\send:'.$elm.'\s-->/si',$new,$doc);
	return $doc;
}

function cleanup($doc){
	$doc=preg_replace('/\s<!--.*?:.*?-->\s/','',$doc);
	$doc=preg_replace('/\s<!--\s\#.*?\s-->\s/','',$doc);
	return $doc;
}

function load($filelocation){
	if (file_exists($filelocation)){
		$newfile = fopen($filelocation,"r");
		$file_content = fread($newfile, filesize($filelocation));
		fclose($newfile);
		return $file_content;
	}
}

function save($filelocation,$newdatas){
	$newfile = @fopen($filelocation,"w+");
	@fwrite($newfile, $newdatas);
	@fclose($newfile);
	if($newfile!=""){$fileerror=0;}
	else {$fileerror=1;}
	return $fileerror;
}

function untag($string,$tag,$mode){
	$tmpval="";
	$preg="/<".$tag.">(.*?)<\/".$tag.">/si";
	preg_match_all($preg,$string,$tags); 
	foreach ($tags[1] as $tmpcont){
		if ($mode==1){$tmpval[]=$tmpcont;}
		else {$tmpval.=$tmpcont;}
		}
	return $tmpval;
}

function directory($dir,$filters){
	$handle=opendir($dir);
	$files=array();
	if ($filters == "all"){while(($file = readdir($handle))!==false){$files[] = $file;}}
	if ($filters != "all"){
		$filters=explode(",",$filters);
		while (($file = readdir($handle))!==false) {
			for ($f=0;$f<sizeof($filters);$f++):
				$system=explode(".",$file);
				if ($system[1] == $filters[$f]){$files[] = $file;}
			endfor;
		}
	}
	closedir($handle);
	return $files;
}

?>