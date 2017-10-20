<?php

include_once '../gui/GUI.class.php';
setlocale(LC_TIME, 'es_AR.utf8');

/**
 * 
 * Clase para mantener las constantes de sistema
 * @author Eder dos Santos <esantos@uarg.unpa.edu.ar>
 * 
 */
class Constantes {

    
    const NOMBRE_SISTEMA = "CheckPoint";
    
    const WEBROOT = "C:\\xampp\\htdocs\\checkpoint";
    const APPDIR = "checkpoint";
        
    const SERVER = "http://localhost";
    const APPURL = "http://localhost/checkpoint";
    const HOMEURL = "http://localhost/checkpoint/app/index.php";
    const HOMEAUTH = "http://localhost/checkpoint/app/servicios.ver.php";
    
    const BD_SCHEMA = "checkpoint";
    const BD_USERS = "uargflow";
    
}
