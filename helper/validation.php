<?php
class Validation {

    // Stocke une erreur et redirige
   
    public static function erreur($message, $redirect) {
        $_SESSION['erreur'] = $message;
        header('Location: ' . $redirect);
        exit;
    }

    // Récupère l'ancienne valeur d'un champ
    
    public static function oldValue($champ, $defaut = '') {
        if (isset($_SESSION['form_data'][$champ])) {
            return htmlspecialchars($_SESSION['form_data'][$champ]);
        }
        return $defaut;
    }

    // Vérifie qu'un champ n'est pas vide
   
    public static function requis($valeur) {
        return !empty(trim($valeur));
    }

    // Vérifie le format d'un email
    
    public static function email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    // Vérifie un téléphone français (10 chiffres, commence par 0)
    
    public static function telephone($tel) {
        return preg_match('/^0[1-9][0-9]{8}$/', $tel);
    }

    // Vérifie une longueur minimale
    
    public static function longueurMin($valeur, $min) {
        return strlen(trim($valeur)) >= $min;
    }

    // Vérifie une longueur maximale

    public static function longueurMax($valeur, $max) {
        return strlen(trim($valeur)) <= $max;
    }
}
?>