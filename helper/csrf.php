<?php

/**
 * Génère (ou récupère) le token CSRF de la session.
 */
function generateCsrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Vérifie que le token soumis correspond à celui en session.
 */
function validateCsrfToken(string $token): bool
{
    return isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Génère le champ hidden à inclure dans chaque formulaire POST.
 */
function csrfInput(): string
{
    return '<input type="hidden" name="csrf_token" value="'
        . htmlspecialchars(generateCsrfToken())
        . '">';
}
