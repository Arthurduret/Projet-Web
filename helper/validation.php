<?php
class Validation {


   
    public static function erreur($message, $redirect) {
        $_SESSION['erreur'] = $message;
        header('Location: ' . $redirect);
        exit;
    }


    
    public static function oldValue($champ, $defaut = '') {
        if (isset($_SESSION['form_data'][$champ])) {
            return htmlspecialchars($_SESSION['form_data'][$champ]);
        }
        return $defaut;
    }

   
    public static function requis($valeur) {
        return !empty(trim($valeur));
    }


    
    public static function email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }


    
    public static function telephone($tel) {
        return preg_match('/^0[1-9][0-9]{8}$/', $tel);
    }


    
    public static function longueurMin($valeur, $min) {
        return strlen(trim($valeur)) >= $min;
    }



    public static function longueurMax($valeur, $max) {
        return strlen(trim($valeur)) <= $max;
    }
}
?>