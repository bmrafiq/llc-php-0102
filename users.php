<?php
$connection = mysqli_connect('127.0.0.1', 'root', '', 'llc_php');

if ($connection === false) {
    $errors[] = mysqli_connect_error();
} else {
    $query = 'SELECT id, email, username, profile_photo FROM users';
    $result = mysqli_query($connection, $query);
    $data = mysqli_fetch_all($result, 1);
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
<table class="table table-bordered">
    <thead>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Profile Photo</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($data as $user) { ?>
        <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo $user['username']; ?></td>
            <td><?php echo $user['email']; ?></td>
            <td><img src="profile_photo/<?php echo $user['profile_photo']; ?>" width="50"></td>
            <td>
                <a href="edit.php?id=<?php echo $user['id']; ?>" class="label label-info">Edit</a>
                <a href="delete.php?id=<?php echo $user['id']; ?>" class="label label-danger" onclick="confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
</body>
</html>
