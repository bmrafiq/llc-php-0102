<?php
$connection = mysqli_connect('127.0.0.1', 'root', '', 'llc_php');

if ($connection === false) {
    $errors[] = mysqli_connect_error();
} else {
    $query = 'SELECT id, email, username, profile_photo FROM users';
    $result = mysqli_query($connection, $query);
    $data = mysqli_fetch_all($result, 1);
}

$query_string = '';
if (isset($_GET['search'])) {
    $query_string = trim($_GET['query']);

    $query = "SELECT id, email, username, profile_photo FROM users WHERE username LIKE '%$query_string%' OR email LIKE '%$query_string%'";
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
<form action="" method="get" class="form form-horizontal">
    <label for="input">Search</label>
    <input type="text" name="query" class="form-control" value="<?php echo $query_string ?? ''; ?>">
    <button type="submit" class="btn btn-info btn-block" name="search">Search</button>
</form>
<?php if(!empty($query_string)) { ?>
    <div class="alert alert-info">
        You have searched for <i><?php echo $query_string; ?></i>
    </div>
<?php } ?>
<?php if (count($data) > 0) { ?>
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
                <td><?php echo !empty($query_string) ? str_replace($query_string, '<span style="color: #ff0000;">' . $query_string . '</span>', $user['username']) : $user['username']; ?></td>
                <td><?php echo !empty($query_string) ? str_replace($query_string, '<span style="color: #ff0000;">' . $query_string . '</span>', $user['email']) : $user['email'];  ?></td>
                <td><img src="profile_photo/<?php echo $user['profile_photo']; ?>" width="50"></td>
                <td>
                    <a href="edit.php?id=<?php echo $user['id']; ?>" class="label label-info">Edit</a>
                    <a href="delete.php?id=<?php echo $user['id']; ?>" class="label label-danger" onclick="confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } else { ?>
    <div class="alert alert-warning">
        Sorry! No data found.
    </div>
<?php } ?>
</body>
</html>
