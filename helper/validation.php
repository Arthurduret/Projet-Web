<?php
class Validation {

    // -----------------------------------------------
    // Redirige avec un message d'erreur en session
    // -----------------------------------------------
    public static function erreur(string $message, string $redirect): void {
        // Sécurité : redirection interne uniquement
        if (!str_starts_with($redirect, '/')) {
            $redirect = '/index.php?page=accueil';
        }
        $_SESSION['erreur'] = $message;
        header('Location: ' . $redirect);
        exit;
    }

    // -----------------------------------------------
    // Récupère l'ancienne valeur d'un champ (après erreur)
    // -----------------------------------------------
    public static function oldValue(string $champ, string $defaut = ''): string {
        if (isset($_SESSION['form_data'][$champ])) {
            return htmlspecialchars($_SESSION['form_data'][$champ]);
        }
        return $defaut;
    }

    // -----------------------------------------------
    // Vérifie qu'un champ n'est pas vide
    // -----------------------------------------------
    public static function requis(string $valeur): bool {
        return !empty(trim($valeur));
    }

    // -----------------------------------------------
    // Vérifie qu'un email est valide
    // -----------------------------------------------
    public static function email(string $email): bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    // -----------------------------------------------
    // Vérifie qu'un téléphone français est valide
    // -----------------------------------------------
    public static function telephone(string $tel): bool {
        return (bool)preg_match('/^0[1-9][0-9]{8}$/', $tel);
    }

    // -----------------------------------------------
    // Vérifie la longueur minimale d'une chaîne
    // -----------------------------------------------
    public static function longueurMin(string $valeur, int $min): bool {
        return strlen(trim($valeur)) >= $min;
    }

    // -----------------------------------------------
    // Vérifie la longueur maximale d'une chaîne
    // -----------------------------------------------
    public static function longueurMax(string $valeur, int $max): bool {
        return strlen(trim($valeur)) <= $max;
    }

    // -----------------------------------------------
    // Vérifie qu'une valeur est un entier valide
    // avec bornes optionnelles min/max
    // -----------------------------------------------
    public static function nombreEntier($valeur, ?int $min = null, ?int $max = null): bool {
        if (filter_var($valeur, FILTER_VALIDATE_INT) === false) {
            return false;
        }
        $val = (int)$valeur;
        if ($min !== null && $val < $min) return false;
        if ($max !== null && $val > $max) return false;
        return true;
    }

    // -----------------------------------------------
    // Vérifie qu'une URL est valide
    // -----------------------------------------------
    public static function url(string $url): bool {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
}
?>