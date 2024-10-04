<?php

$db_host = 'localhost';
$db_name = 'u_230163795_aproject';
$username = 'u-230163795';
$password = 'IlBbU2OoLLXnfiZ';

try {
    $db = new PDO("mysql:dbname=$db_name;host=$db_host", $username, $password); 
    #$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $ex) {
    echo("Failed to connect to the database.<br>");
    echo($ex->getMessage());
    exit;
}
?>
