<?php
    
    /**
     * Persona user page, currently, only the current user can view it's own page
     */
    require_once __DIR__ . '/../includes/header.php';
    
    if (!isset($_GET['module'], $_GET['id'])) {
        die('Missing URL parameters');
    }
    
    if ((int)$_SESSION['app']['user']['id'] !== (int)$_GET['id']) {
        echo 'No puedes ver el contenido de este usuario';
        exit();
    }
    
    // There's no data to show in HTML, output all variables.
    echo '<pre>';
    var_dump($_REQUEST, $_SESSION);
    echo '</pre>';
    
    require_once __DIR__ . '/../includes/footer.php';