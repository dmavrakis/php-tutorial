<?php
$servername = 'localhost';
$username = 'root';
$password = 'VdFx5J2EFdchbB'; // Replace with the actual password
$database = 'php_tutorial_db';

$con = mysqli_connect($servername, $username, $password, $database);

if (!$con) {
    die('Connection failed: ' . mysqli_connect_error());
}
