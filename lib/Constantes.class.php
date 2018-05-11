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
    
    const WEBROOT = "C:\\xampp\\htdocs\\checkpoint\\Codigo Fuente\\App Web";
    const APPDIR = "checkpoint";
        
    const SERVER = "http://localhost";
    const APPURL = "http://localhost/checkpoint/Codigo Fuente/App Web/";
    
    /* URL principal a la que se accede cuando se acceda (todos) */
    const HOMEURL = "http://localhost/checkpoint/Codigo Fuente/App Web/app/index.php";
    
    /* pagina a la que se accesa cuando se logro la correcta autenticacion */
    const HOMEAUTH = "http://localhost/checkpoint/Codigo Fuente/App Web/app/bienvenido.php";
    
    const BD_SCHEMA = "checkpoint";
    const BD_USERS = "checkpoint";
    
}
