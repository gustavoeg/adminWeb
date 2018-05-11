<script type="text/javascript" src="../lib/jQuery/jquery-3.2.1.min.js"></script>
<?php

/**
 * Clase con métodos estáticos para la interfaz GUI.
 *
 * @author esantos
 * @since 26/06/2017
 */
class GUI {

    /**
     * 
     * @param file $url
     * @return String
     */
    static function generaMenuActive($url) {
        return ("/" . Constantes::APPDIR . "/" . $url == $_SERVER["PHP_SELF"]) ? "class = 'active' " : null;
    }

}
?>
