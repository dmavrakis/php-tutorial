<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <?php include "header.php" ?>

    <div style="margin: auto; max-width: 600px;">

        <?php
        if (!empty($error)) {
            echo "<div>.$error.</div>";
        }
        ?>

        <h2 style="text-align: center;">Login</h2>
        <form method="post" style="margin: auto; padding: 10px;">
            <input type="email" type="email" placeholder="Email" required>
            <br>
            <input type="text" type="password" placeholder="Password" required>
            <br>
            <button>Login</button>
        </form>
    </div>

    <?php include "footer.php"; ?>