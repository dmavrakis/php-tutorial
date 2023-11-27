<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>

<body>
    <?php include "header.php" ?>

    <div style="margin: auto; max-width: 600px;">
        <h2 style="text-align: center;">User Profile</h2>
        <table style="text-align: center;">
            <tr>
                <td><img src="https://www.cyberpunk.net/build/images/social-thumbnail-en-ddcf4d23.jpg" alt="Logo" style="width: 150px; height: 150px; object-fit: cover;"></td>
            </tr>
            <tr>
                <th>Username:</th>
                <td>John</td>
            </tr>
            <tr>
                <th>Email:</th>
                <td>John@mail.com</td>
            </tr>
        </table>
        <hr>
        <h5>Create a post</h5>
        <form method="post" style="margin: auto; padding: 10px;">
            <textarea name="post" rows="8">

        </textarea><br>
            <button>Post</button>
        </form>
    </div>


    <?php include "footer.php" ?>