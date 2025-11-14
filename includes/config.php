<?php
    # Longitud mínima de la contraseña
    if (!defined('MIN_PASSWORD_LENGTH')) {
        define('MIN_PASSWORD_LENGTH', 8);
    }
    # Longitud mínima del nombre de usuario
    if (!defined('MIN_USERNAME_LENGTH')) {
        define('MIN_USERNAME_LENGTH', 5);
    }
    # Longitud máxima del nombre de usuario
    if (!defined('MAX_USERNAME_LENGTH')) {
        define('MAX_USERNAME_LENGTH', 50);
    }