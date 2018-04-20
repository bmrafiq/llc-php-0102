<?php
session_start();
$errors = [];

if (isset($_POST['login'])) {
    $identifier = trim($_POST['identifier']);
    $password = trim($_POST['password']);

    if (empty($identifier)) {
        $errors[] = 'Username/email can not be empty.';
    }

    if (empty($password)) {
        $errors[] = 'Password can not be empty';
    }

    if (empty($errors)) {
        include_once 'connection.php';

        $query = 'SELECT id, username, password FROM users WHERE username=:identifier OR email=:identifier';
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':identifier', $identifier);
        $stmt->execute();

        if ($stmt->rowCount()  === 0) {
            $errors[] = 'User not found';
        } else {
            $data = $stmt->fetch();

            if (password_verify($password, $data['password']) === true) {
                $_SESSION['id'] = $data['id'];
                $_SESSION['username'] = $data['username'];
                $_SESSION['message'] = 'Logged in successfully!';

                header('Location: dashboard.php');
            } else {
                $errors[] = 'Wrong password';
            }
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
    <h1 class="h3 mb-3 font-weight-normal">Login</h1>
    <label for="inputUsername">Username/Email</label>
    <input type="text" name="identifier" class="form-control" autofocus>
    <label for="inputPassword">Password</label>
    <input type="password" name="password" class="form-control">
    <button class="btn btn-lg btn-primary btn-block" type="submit" name="login">Login</button>
    <p class="mt-5 mb-3 text-muted">&copy; 2018</p>
</form>
</body>
</html>
