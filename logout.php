<?php
    session_start();
    $_SESSION = []; // Clear all session data
    session_destroy(); // Clean up the sission ID 

    $url = "logon_form.php";
    header("Location: " . $url);
    die();
?>
