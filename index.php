<?php
$errors = [];

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $profile_photo = $_FILES['file'];

    // validation
    if (empty($username)) {
        $errors[] = 'Username cannot be empty';
    }

    if (empty($email)) {
        $errors[] = 'Email cannot be empty';
    }

    if (empty($password)) {
        $errors[] = 'Password cannot be empty';
    }

    if (empty($profile_photo['name'])) {
        $errors[] = 'File must be provided';
    }

    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $errors[] = 'Email must be a valid email address';
    }

    $file_info = explode('.', $profile_photo['name']);
    $file_ext = end($file_info);
    $password = password_hash($password, PASSWORD_BCRYPT);

    if (! in_array($file_ext, ['jpg', 'png'], true)) {
        $errors[] = 'File must be a valid image file';
    }

    if (empty($errors)) {
        $new_file_name = uniqid('pp_', true).'.'.$file_ext;
        // file upload
        $upload = move_uploaded_file($profile_photo['tmp_name'], 'profile_photo/'.$new_file_name);

        if ($upload) {
            include_once 'connection.php';

            $query = 'INSERT INTO `users` (`username`, `email`, `password`, `profile_photo`) VALUES (:username, :email, :password, :profile_photo)';
            $stmt = $connection->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':profile_photo', $new_file_name);

            if ($stmt->execute() === false) {
                $errors[] = 'User was not inserted.';
            } else {
                $success = 'User inserted successfully.';
            }
        } else {
            $errors[] = 'File was not uploaded. Please try again.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>LLC PHP</title>

    <!-- Bootstrap core CSS -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="text-center">
<?php
if (isset($success)) {
    ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
    <?php
}
?>
<?php
if (! empty($errors)) {
    ?>
    <div class="alert alert-warning">
        <?php
        foreach ($errors as $error) {
            ?>
            <ul>
                <li><?php echo $error; ?></li>
            </ul>
            <?php
        }
        ?>
    </div>
    <?php
}
?>
<form action="" method="post" enctype="multipart/form-data">
    <h1 class="h3 mb-3 font-weight-normal">Register</h1>
    <label for="inputUsername">Username</label>
    <input type="text" name="username" class="form-control" autofocus>
    <label for="inputEmail">Email address</label>
    <input type="text" name="email" class="form-control" autofocus>
    <label for="inputPassword">Password</label>
    <input type="password" name="password" class="form-control">
    <label for="inputFile">Profile Photo</label>
    <input type="file" name="file" class="form-control">
    <button class="btn btn-lg btn-primary btn-block" type="submit" name="register">Register</button>
    <p class="mt-5 mb-3 text-muted">&copy; 2018</p>
</form>
</body>
</html>
