<?php
require "functions.php";
check_login();

if ($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['action']) && $_POST['action'] == 'post_delete') {

    //delete your post
    $id = $_GET['id'] ?? 0;
    $user_id = $_SESSION['info']['id'];

    $query = "select * from posts where id = '$id' && user_id = '$user_id' limit 1";
    $result = mysqli_query($con, $query);
    if (mysqli_num_rows($result) > 0) {

        $row = mysqli_fetch_assoc($result);
        if (file_exists($_SESSION['info']['image'])) {
            unlink($_SESSION['info']['image']);
        }
    }
    $query = "delete from posts where id = '$id' && user_id = '$user_id' limit 1";
    $result = mysqli_query($con, $query);


    header("Location: logout.php");
    die;
} elseif ($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['action']) && $_POST['action'] == "post_edit") {

    //post edit
    $id = $_GET['id'] ?? 0;
    $user_id = $_SESSION['info']['id'];

    $image_added = false;
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] == 0 && $_FILES['image']['type'] == "image/jpeg") {
        //file was uploaded

        $folder = "uploads/";
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        $image = $folder . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $image);

        $query = "select * from posts where id = '$id' && user_id = '$user_id' limit 1";
        $result = mysqli_query($con, $query);
        if (mysqli_num_rows($result) > 0) {

            $row = mysqli_fetch_assoc($result);
            if (file_exists($_SESSION['info']['image'])) {
                unlink($_SESSION['info']['image']);
            }
        }

        $image_added = true;
    }
    $post = addslashes($_POST['post']);

    if ($image_added == true) {
        $query = "update posts set post = '$post', image = '$image' where id = '$id' && user_id = '$user_id' limit 1";
    } else {
        $query = "update posts set post = '$post' where id = '$id' && user_id = '$user_id' limit 1";
    };

    $result = mysqli_query($con, $query);


    header("Location: profile.php");
    die;
} elseif ($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['action']) && $_POST['action'] == 'delete') {
    //delete your profile
    $id = $_SESSION['info']['id'];
    $query = "delete from users where id = '$id' limit 1";
    $result = mysqli_query($con, $query);

    if (file_exists($_SESSION['info']['image'])) {
        unlink($_SESSION['info']['image']);
    }

    $query = "delete from posts where user_id = '$id'";
    $result = mysqli_query($con, $query);

    header("Location: logout.php");
    die;
} elseif ($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['username'])) {

    //profile edit
    $image_added = false;
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] == 0 && $_FILES['image']['type'] == "image/jpeg") {
        //file was uploaded

        $folder = "uploads/";
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        $image = $folder . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
        if (file_exists($_SESSION['info']['image'])) {
            unlink($_SESSION['info']['image']);
        }

        $image_added = true;
    }
    $username = addslashes($_POST['username']);
    $email = addslashes($_POST['email']);
    $password = addslashes($_POST['password']);
    $id = $_SESSION['info']['id'];

    if ($image_added == true) {
        $query = "update users set username = '$username', email = '$email', password = '$password', image = '$image' where id = '$id' limit 1";
    } else {
        $query = "update users set username = '$username', email = '$email', password = '$password' where id = '$id' limit 1";
    };

    $result = mysqli_query($con, $query);

    $query = "select * from users where id = '$id' limit 1";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {

        $_SESSION['info'] = mysqli_fetch_assoc($result);
    };

    header("Location: profile.php");
    die;
} elseif ($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['post'])) {

    //adding post
    $image = "";
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] == 0 && $_FILES['image']['type'] == "image/jpeg") {
        //file was uploaded

        $folder = "uploads/";
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        $image = $folder . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $image);


        $image_added = true;
    }

    $post = addslashes($_POST['post']);
    $user_id = $_SESSION['info']['id'];
    $date = date('Y-m-d H:i:s');

    $query = "insert into posts (user_id, post, image, date) values ('$user_id', '$post', '$image', '$date')";

    $result = mysqli_query($con, $query);

    header("Location: profile.php");
    die;
}


?>

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

        <?php if (!empty($_GET['action']) && $_GET['action'] == 'post_delete' && !empty($_GET['id'])) : ?>

            <?php

            $id = (int)$_GET['id'];
            $query = "select * from posts where id = '$id' limit 1";
            $result = mysqli_query($con, $query);

            ?>

            <?php if (mysqli_num_rows($result) > 0) : ?>
                <?php $row = mysqli_fetch_assoc($result); ?>
                <h3>Delete a post</h3>
                <form method="post" enctype="multipart/form-data" style="margin: auto; padding: 10px;">

                    <img src="<?php echo $row['image'] ?>" style="width: 100%; height: 200px; object-fit: cover;"><br>
                    image:<input type="file" name="image"><br>
                    <div>
                        <?php echo $row['post'] ?>
                    </div>
                    <br>
                    <input type="hidden" name="action" value="post_delete">

                    <button>Delete</button>
                    <a href="profile.php">
                        <button type="button">Cancel</button>
                    </a>
                </form>
            <?php endif; ?>

        <?php elseif (!empty($_GET['action']) && $_GET['action'] == 'post_edit' && !empty($_GET['id'])) : ?>

            <?php

            $id = (int)$_GET['id'];
            $query = "select * from posts where id = '$id' limit 1";
            $result = mysqli_query($con, $query);

            ?>

            <?php if (mysqli_num_rows($result) > 0) : ?>
                <?php $row = mysqli_fetch_assoc($result); ?>
                <h5>Edit a post</h5>
                <form method="post" enctype="multipart/form-data" style="margin: auto; padding: 10px;">

                    <img src="<?php echo $row['image'] ?>" style="width: 100%; height: 200px; object-fit: cover;"><br>
                    image:<input type="file" name="image"><br>
                    <textarea name="post" rows="8">
                        <?php echo $row['post'] ?>
                    </textarea>
                    <br>
                    <input type="hidden" name="action" value="post_edit">

                    <button>Save</button>
                    <a href="profile.php">
                        <button type="button">Cancel</button>
                    </a>
                </form>
            <?php endif; ?>

        <?php elseif (!empty($_GET['action']) && $_GET['action'] == 'edit') : ?>
            <h2 style="text-align: center;">Edit Profile</h2>
            <form method="post" enctype="multipart/form-data" style="margin: auto; padding: 10px;">

                <img src="<?php echo $_SESSION['info']['image'] ?>" alt="Logo" style="width: 100px; height: 100px; object-fit: cover; margin: auto; display: block;">
                image:<input type="file" name='image'>
                <br>
                <input value="<?php echo $_SESSION['info']['username'] ?>" type="text" name="username" type="username" placeholder="Username" required>
                <br>
                <input value="<?php echo $_SESSION['info']['email'] ?>" type="text" name="email" type="email" placeholder="Email" required>
                <br>
                <input value="<?php echo $_SESSION['info']['password'] ?>" type="text" name="password" type="password" placeholder="Password" required>
                <br>
                <!-- <input type="hidden" value="profile_edit" name="action"> -->
                <button>Save</button>
                <a href="profile.php">
                    <button type="button">Cancel</button>
                </a>
            </form>


        <?php elseif (!empty($_GET['action']) && $_GET['action'] == 'delete') : ?>
            <h2 style="text-align: center;">Are you sure?</h2>

            <div style="text-align: center; margin: auto; max-width: 600px;">
                <form method="post" enctype="multipart/form-data" style="margin: auto; padding: 10px;">

                    <img src="<?php echo $_SESSION['info']['image'] ?>" alt="Logo" style="width: 100px; height: 100px; object-fit: cover; margin: auto; display: block;">
                    <br>
                    <div><?php echo $_SESSION['info']['username'] ?></div>

                    <div><?php echo $_SESSION['info']['email'] ?></div>
                    <input type="hidden" name="action" value="delete">
                    <button>Delete</button>
                    <a href="profile.php">
                        <button type="button">Cancel</button>
                    </a>
                </form>
            </div>
        <?php else : ?>

            <h2 style="text-align: center;">User Profile</h2>
            <br>
            <div style="text-align: center; margin: auto; max-width: 600px;">
                <div>
                    <td><img src="<?php echo $_SESSION['info']['image'] ?>" alt="Logo" style="width: 150px; height: 150px; object-fit: cover;"></td>
                </div>
                <div>
                    <th>Username:</th>
                    <td><?php echo $_SESSION['info']['username']; ?></td>
                </div>
                <div>
                    <th>Email:</th>
                    <td><?php echo $_SESSION['info']['email']; ?></td>
                </div>
                <a href="profile.php?action=edit">
                    <button>
                        Edit profile
                    </button>
                </a>
                <a href="profile.php?action=delete">
                    <button>
                        Delete Profile
                    </button>
                </a>
            </div>
            <br>
            <hr>
            <h5>Create a post</h5>
            <form method="post" enctype="multipart/form-data" style="margin: auto; padding: 10px;">
                image:<input type="file" name="image"><br>
                <textarea name="post" rows="8">

                </textarea>
                <br>
                <button>Post</button>
            </form>

            <hr>
            <posts>
                <?php
                $id = $_SESSION['info']['id'];
                $query = "select * from posts where user_id = '$id' order by id desc limit 10";

                $result = mysqli_query($con, $query);

                ?>
                <?php if (mysqli_num_rows($result) > 0) : ?>

                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>

                        <?php

                        $user_id = $row['user_id'];
                        $query = "select username,image from users where id = '$user_id' limit 1";
                        $result2 = mysqli_query($con, $query);

                        $user_row = mysqli_fetch_assoc($result2);

                        ?>

                        <div style="display: flex; border: 1px solid red;margin-top:10px;">
                            <div style="flex:1; text-align:center;">
                                <img src="<?php echo $user_row['image']; ?>" style="margin: 10px; width: 100px; height: 100px; object-fit: cover; border-radius: 50%">
                                <br>
                                <?php echo $user_row['username']; ?>
                            </div>
                            <div style="flex:8;">
                                <?php if (file_exists($row['image'])) : ?>
                                    <div>
                                        <img src="<?php echo $row['image'] ?>" style="width: 100%; height: 200px; object-fit: cover;">
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <div>
                                        <?php echo date("jS M, Y", strtotime($row['date'])) ?>
                                    </div>
                                    <?php echo nl2br(htmlspecialchars($row['post'])) ?>

                                    <br><br>

                                    <a href="profile.php?action=post_edit&id=<?php echo $row['id'] ?>">
                                        <button>
                                            Edit
                                        </button>
                                    </a>
                                    <a href="profile.php?action=post_delete&id=<?php echo $row['id'] ?>">
                                        <button>
                                            Delete
                                        </button>
                                    </a>

                                </div>
                            </div>

                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </posts>
        <?php endif; ?>
    </div>


    <?php include "footer.php" ?>