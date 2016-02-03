<?php
$hostname = "AmigosDeUsuarios.db.11535283.hostedresource.com";
$hostname2000 = "AmigosDeUsuarios.db.11535283.hostedresource.com";
$username = "AmigosDeUsuarios";
$dbname = "AmigosDeUsuarios";
$password = "Amigos1234!";

$mysqli = new mysqli($hostname, $username, $password,$dbname);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}