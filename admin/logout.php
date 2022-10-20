<?php
    session_start();
    session_destroy();
    session_start();
    $_SESSION['message'] = 'You have been logged out successfully';
    header('Location: login.php');
    return;
?>