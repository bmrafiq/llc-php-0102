<?php
$errors = [];

$id = $_GET['id'] ?? 0;

if ((int) $_GET['id'] === 0) {
    header('Location: index.php');
}

include_once 'connection.php';

if (isset($_POST['update'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $profile_photo = $_FILES['file'];

    if (empty($username)) {
        $errors[] = 'Username cannot be empty';
    }

    if (empty($email)) {
        $errors[] = 'Email cannot be empty';
    }

    if (empty($errors)) {
        // if user selects a new file
        if (! empty($profile_photo['name'])) {
            $file_info = explode('.', $profile_photo['name']);
            $file_ext = end($file_info);

            if (! in_array($file_ext, ['jpg', 'png'], true)) {
                $errors[] = 'File must be a valid image file';
            }

            if (empty($errors)) {
                $new_file_name = uniqid('pp_', true).'.'.$file_ext;
                $upload = move_uploaded_file($profile_photo['tmp_name'], 'profile_photo/'.$new_file_name);

                $query = 'UPDATE users SET profile_photo = :profile_photo WHERE id = :id';
                $stmt = $connection->prepare($query);
                $stmt->bindParam(':profile_photo', $new_file_name);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
            }
        }

        $query = 'UPDATE users SET username = :username, email = :email WHERE id = :id';
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $success = 'User updated successfully';
    }
}

$query = 'SELECT email, username, profile_photo FROM users WHERE id = :id';
$stmt = $connection->prepare($query);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetch();
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
    <h1 class="h3 mb-3 font-weight-normal">Edit Profile</h1>
    <label for="inputUsername">Username (*)</label>
    <input type="text" name="username" class="form-control" value="<?php echo $data['username']; ?>" required>
    <label for="inputEmail">Email address (*)</label>
    <input type="text" name="email" class="form-control" value="<?php echo $data['email']; ?>" required>
    <label for="inputFile">Profile Photo</label>
    <input type="file" name="file" class="form-control">
    <p class="img"><img src="profile_photo/<?php echo $data['profile_photo']; ?>" width="50"></p>
    <button class="btn btn-lg btn-primary btn-block" type="submit" name="update">Update</button>
    <p class="mt-5 mb-3 text-muted">&copy; 2018</p>
</form>
</body>
</html>
