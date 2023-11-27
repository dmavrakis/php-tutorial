<?php

require "functions.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = addslashes($_POST['username']);
    $email = addslashes($_POST['email']);
    $password = addslashes($_POST['password']);
    $date = date('Y-m-d H:i:s');

    $query = "insert into users (username, email, password, date) values ('$username', '$email', '$password', '$date')";

    $result = mysqli_query($con, $query);

    header("Location: login.php");
    die;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
</head>

<body>
    <?php include "header.php" ?>



    <?php include "footer.php" ?>