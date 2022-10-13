<?php
    function connect($username, $passwd) {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=warehouse', $username, $passwd);
            return $pdo;
        } catch (PDOException $e) {
            return false;
        }
    }
?>