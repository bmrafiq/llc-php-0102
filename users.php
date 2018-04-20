<?php

session_start();

if (! isset($_SESSION['id'], $_SESSION['username'])) {
    header('Location: login.php');
}

include_once 'connection.php';

$query = 'SELECT id, email, username, profile_photo FROM users';
$stmt = $connection->prepare($query);
$stmt->execute();
$data = $stmt->fetchAll();

$query_string = '';
if (isset($_GET['search'])) {
    $query_string = trim($_GET['query']);

    $query = 'SELECT id, email, username, profile_photo FROM users WHERE username LIKE :query_string OR email LIKE :query_string';
    $stmt = $connection->prepare($query);
    $stmt->bindValue(':query_string', '%'.$query_string.'%');
    $stmt->execute();
    $data = $stmt->fetchAll();
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
<?php if (! empty($query_string)) { ?>
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
                <td><?php echo ! empty($query_string) ? str_replace($query_string, '<span style="color: #ff0000;">'.$query_string.'</span>', $user['username']) : $user['username']; ?></td>
                <td><?php echo ! empty($query_string) ? str_replace($query_string, '<span style="color: #ff0000;">'.$query_string.'</span>', $user['email']) : $user['email']; ?></td>
                <td><img src="profile_photo/<?php echo $user['profile_photo']; ?>" width="50"></td>
                <td>
                    <a href="edit.php?id=<?php echo $user['id']; ?>" class="label label-info">Edit</a>
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
