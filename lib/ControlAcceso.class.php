<?php

session_start();
include_once 'Constantes.class.php';
include_once 'ObjetoDatos.class.php';

/**
 * Clase de constantes del sistema de Roles y Permisos
 * 
 * @author Eder dos Santos <esantos@uarg.unpa.edu.ar>
 */
class PermisosSistema {
/**
 * deberia ser uno por caso de uso
 * habilitar servicio
 * asignar encardado a servicio
 * editar servicio
 * deshabilitar servicio
 * añadir opciones de valoracion
 * habilita en sector
 * editar opciones de valoracion
 * eliminar opciones de valoracion
 * añadir ubicacion
 * modificar ubicacion
 * eliminar ubicacion
 * atiende valoracion
 * realiza devolucion
 * realizar valoracion
 * indicar ubicacion
 * escanear codigo QR
 * agregar descripcion 
 * agregar fotografia
 * agregar email
 * generar estadistica
 */
    const PERMISO_SALIR = "Salir";
    const PERMISO_LOGIN = "Ingresar";
    //const PERMISO_CONSULTAR = "Consultar";
    
    
    /* permiso para ABM de servicios */
    const PERMISO_SERVICIOS = "Servicios";
    
    /* Estos permisos son para el ROL de encargado. */
    const PERMISO_OPCIONES_VALORACION = "Opciones de valoracion";      
    
    /* Estos permisos son para el ROL de encargado */
    const PERMISO_HABILITA_EN_SECTOR= "Habilita en Sector";    
    const PERMISO_UBICACION = "Ubicacion";
    
    /* permisos para la generacion de reportes (directivos, autoridades) */
    const PERMISO_REPORTES = "Reportes";
    
    /* Permite la gestion de usuarios */
    const PERMISO_USUARIOS = "Usuarios";

    /**
     * Administrador del Sistema.
     */
    const ROL_ADMIN = "Administrador";

    /**
     * Usuario de las Áreas. Realiza carga de opciones de valoracion.
     */
    const ROL_ENCARGADO = "Encargado";

    /**
     * Usuario de consulta. El decano u otra autoridad que lo requiera
     */
    const ROL_CONSULTA = "Usuario Consulta";

    /**
     * Invitado. Usuario no registrado. Quiza se elimine en el futuro
     */
    const ROL_INVITADO = "Invitado";

}

class Permiso {

    public $idpermiso;
    public $nombre;

    /**
     *
     * @var mysqli_result 
     */
    protected $datos;

}

class Rol {

    public $idrol;
    public $nombre;

    /**
     *
     * @var Permiso[]
     */
    public $permisos = array();

    /**
     *
     * @var mysqli_result 
     */
    protected $datos;

    /**
     * @todo [12/07/2017] Capturar excepciones de BD para la llamada a setPermisos.
     */
    public function __construct() {
        $this->setPermisos();
    }

    public function setPermisos() {


        $this->datos = ObjetoDatos::getInstancia()->ejecutarQuery(""
                . "SELECT p.idpermiso, nombre "
                . "FROM " . Constantes::BD_USERS . ".PERMISO p "
                . "JOIN " . Constantes::BD_USERS . ".ROL_PERMISO rp ON p.idpermiso = rp.idpermiso "
                . "WHERE rp.idrol = {$this->idrol} ");

        if (!$this->datos) {
            throw new Exception(ObjetoDatos::getInstancia()->errno, ObjetoDatos::getInstancia()->error);
        }

        for ($x = 0; $x < $this->datos->num_rows; $x++) {
            $this->permisos[] = $this->datos->fetch_object("Permiso");
        }
    }

}

class Usuario {

    const METODO_MANUAL = "Manual";
    const METODO_GOOGLE = "Google";

    public $idusuario;
    public $email;
    public $nombre;
    public $metodologin;

    /**
     *
     * @var mysqli_result 
     */
    protected $datos;

    /**
     *
     * @var Rol[] 
     */
    public $roles;

    /**
     * 
     * @param String $email_
     * @param String $metodo_
     * 
     * @throws Exception
     * @since v2.0 2017-08-14 - Desactiva el autoregistro de usuarios.
     * 
     */
    function __construct($email_, $metodo_ = self::METODO_MANUAL) {

        $this->datos = ObjetoDatos::getInstancia()->ejecutarQuery(""
                . "SELECT idusuario, nombre "
                . "FROM " . Constantes::BD_USERS . ".USUARIO "
                . "WHERE email = '{$email_}' "
                . "AND metodologin = '{$metodo_}'");

        if (!$this->datos) {
            throw new Exception(ObjetoDatos::getInstancia()->error, ObjetoDatos::getInstancia()->errno);
        }
        if (!$this->datos->num_rows) {
            return null;
        }

        /*
         * 2. Datos Encontrados. OK
         */
        foreach ($this->datos->fetch_assoc() as $atributo => $valor) {
            $this->{$atributo} = $valor;
        }

        $this->setRoles();

        /*
         * 
         */
    }

    public function setRoles() {

        $this->datos = ObjetoDatos::getInstancia()->ejecutarQuery(""
                . "SELECT r.idrol, r.nombre "
                . "FROM " . Constantes::BD_USERS . ".USUARIO u "
                . "JOIN " . Constantes::BD_USERS . ".USUARIO_ROL ur ON (u.idusuario = ur.idusuario) "
                . "JOIN " . Constantes::BD_USERS . ".ROL r ON (r.idrol = ur.idrol) "
                . "WHERE u.idusuario = {$this->idusuario} ");

        for ($x = 0; $x < $this->datos->num_rows; $x++) {
            $this->roles[] = $this->datos->fetch_object("Rol");
        }
    }

}

class UsuarioGoogle extends Usuario {

    public $googleid;
    public $imagen;

    /**
     * 
     * @param String $email_
     * @param String $googleid_
     * @param String $imagen_
     * @param String $nombre_
     * @throws Exception
     * 
     * @since v2.0 2017-08-14
     * Desactiva el autoregistro de usuarios.
     * 
     */
    function __construct($email_, $googleid_, $imagen_, $nombre_) {

        try {
            parent::__construct($email_, Usuario::METODO_GOOGLE);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        $this->email = $email_;
        $this->googleid = $googleid_;
        $this->imagen = $imagen_;
        $this->nombre = $nombre_;
        $this->metodologin = Usuario::METODO_GOOGLE;

        if ($this->idusuario == null) {
            /* en este caso se tiene que informar que no esta registrado en el sistema (solicitud al administrador)
             * considerar si se permite la creacion de la sesion porque no existe el usuario
             *  */
            //$this->registraUsuarioGoogle();
        }
    }

    /**
     * 
     * Crear un usuario estándar a partir del login con una cuenta Google.
     * Registra los datos en la base de datos. Tablas: usuario, usuario_google, usuario_rol.
     * 
     * @return Int id del usuario creado en la base de datos.
     * 
     */
    function registraUsuarioGoogle() {

        
        ObjetoDatos::getInstancia()->ejecutarQuery(""
                . "INSERT INTO " . Constantes::BD_USERS . ".USUARIO "
                . "VALUES (NULL, '{$this->email}', '{$this->nombre}', '" . Usuario::METODO_GOOGLE . "', 'Activo' )");

        $this->idusuario = (Int) ObjetoDatos::getInstancia()->insert_id;

        ObjetoDatos::getInstancia()->ejecutarQuery(""
                . "INSERT INTO " . Constantes::BD_USERS . ".USUARIO_GOOGLE "
                . "VALUES ({$this->idusuario}, {$this->googleid}, '{$this->imagen}')");


        ObjetoDatos::getInstancia()->ejecutarQuery(""
                . "INSERT INTO " . Constantes::BD_USERS . ".USUARIO_ROL "
                . "SELECT idrol, {$this->idusuario} "
                . "FROM " . Constantes::BD_USERS . ".ROL "
                . "WHERE nombre = '" . PermisosSistema::ROL_CONSULTA . "'");
    }

}

/**
 * 
 * Clase para mantener control de acceso al sistema.
 * @author Eder dos Santos <esantos@uarg.unpa.edu.ar>
 * 
 */
class ControlAcceso {

    public $datos;
    public $ubicacion;
    static $clasesMetodo = array("manual" => "Usuario", "google" => "UsuarioGoogle");

    /**
     * 
     * Verifica si el usuario posee un permiso y en caso contrario lo redirecciona a la Home.
     * 
     * @param String $permiso_
     * @return void 
     * 
     * @author Eder dos Santos <esantos@uarg.unpa.edu.ar>
     * 
     */
    static function requierePermiso($permiso_) {
        if (!self::verificaPermiso($permiso_, $_SESSION['usuario'])) {
            header("Location: " . Constantes::HOMEURL);
        }
    }

    /**
     * 
     * Verifica si un usuario posee un permiso específico.
     * @static
     * 
     * @param String $permiso_
     * @param Usuario $Usuario Obtenido de la Sesion
     * @return Boolean Description
     * 
     */
    static function verificaPermiso($permiso_) {
        $Usuario = $_SESSION['usuario'];
        if ($Usuario == null) {
            return false;
        }
        foreach ($Usuario->roles as $Rol) {
            foreach ($Rol->permisos as $Permiso) {
                if ($permiso_ == $Permiso->nombre) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Verifica si el usuario está logueado en el sistema (cargado en la sesión)
     * @static
     * 
     * @return mixed Redirecciona a la Home del sistema caso el usuario no esté logueado.
     */
    static function verificaLogin() {
        if (!isset($_SESSION['usuario']) || (!is_a($_SESSION['usuario'], "Usuario"))) {
            header("Location: " . Constantes::HOMEURL);
        }
        return null;
    }

    /**
     * 
     * @param type $email_
     * @param type $metodo_
     * @static
     * 
     * @todo [15/06/2017] El método está pensado para instanciar usuarios Google. Se debe generalizar.
     * @since v. 2.0 2017-08-14 - El método deja de ser estático. Autoregistro desactivado.
     * 
     * en checkpoint se crea la sesion solo cuando el usuario esta registrado en el sistema
     * 
     * @author Eder dos Santos <esantos@uarg.unpa.edu.ar>
     * 
     */
    function creaSesion($email_, $metodo_ = Usuario::METODO_MANUAL, $googleid_ = null, $imagen_ = null, $nombre_ = null) {
        try {
            $Usuario = new UsuarioGoogle($email_, $googleid_, $imagen_, $nombre_);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        
        /* la sesion se asigna cuando el usuario existe */
        if($Usuario->idusuario!=null){
            $_SESSION['usuario'] = $Usuario;
        }else{
            session_unset();
            $_SESSION['usuario'] = null;
        }
        
    }

    /**
     * 
     * @since v2.0 2017-08-14
     * Desactiva el autoregistro de usuarios.
     * 
     * @author Eder dos Santos <esantos@uarg.unpa.edu.ar>
     * @todo [14/08/2017] Terminar el tratamiento de error caso el Usuario no exista.
     * 
     */
    function __construct() {

        $this->ubicacion = Constantes::SERVER . $_SERVER["PHP_SELF"];

        /**
         * Verificación Inicial del Usuario caso la página no sea el index.
         */
        if ($this->ubicacion != Constantes::HOMEURL) {
            unset($_SESSION["HTTP_REFERER"]);
            self::verificaLogin();
        } else {
            $_SESSION["HTTP_REFERER"] = Constantes::HOMEURL;
        }

        /**
         * Crea la sesión del Usuario caso la página de origen de los datos pasados por formulario sea el index.
         */
        if (isset($_SESSION["HTTP_REFERER"]) && $_SESSION["HTTP_REFERER"] == Constantes::HOMEURL && isset($_POST['email'])) {
            try {
                /* para crear la sesion tengo que ver que sea un usuario registrado */
                
                /* verifico que sea un usuario registrado */
                
                $this->creaSesion($_POST['email'], Usuario::METODO_GOOGLE, $_POST['googleid'], $_POST['imagen'], $_POST['nombre']);
            } catch (Exception $e) {
                echo "<script>alert('{$e->getMessage()}');</script>";
                die($e->getMessage());
            }
            
            /* verifico que la sesion se haya creado correctamente */
            if(isset($_SESSION['usuario'])){
                $this->redireccionaIndex();
            }
            
        }


        /**
         * Luego de loguear, redireccion al index correspondiente a cada usuario.
         */
        if (
                ($this->ubicacion == Constantes::HOMEURL) &&
                (isset($_SESSION['usuario'])) &&
                (is_a($_SESSION['usuario'], 'Usuario'))
        ) {
            $this->redireccionaIndex();
        }
    }

    /**
     * 
     */
    function redireccionaIndex() {
        $this->ubicacion = Constantes::HOMEAUTH;
        header("Location: {$this->ubicacion}");
    }

}

$ControlAcceso = new ControlAcceso();
