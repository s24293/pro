<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LOGIN</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<?php if (!isset($_SESSION['admin']['login'])) { ?>
    <form action="login-check.php" method="post">
        <h2>LOGIN</h2>
        <?php if (isset($_GET['error'])) { ?>
            <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } ?>
        <label>email</label>
        <input type="email" name="email"><br>

        <label>Password</label>
        <input type="password" name="password"><br>

        <button type="submit">Login</button>
        <a href="singup.php" class="ca">Create an account</a>
    </form>
    <?php
} else {
    ?>
    <form method="post">
        <h4 class='text-info text-center'>Witaj</h4><br>
        <input type="submit" value="Admin Panel" name="addp" class="btn btn-info text-center">
        <input type="submit" value="Wyloguj" name="logout" class="btn btn-danger text-center">
    </form>

    <?php
    if (isset($_POST['logout'])) {
        session_destroy();
        header("Location: login.php");
        exit();
    }
    if (isset($_POST['addp'])) {
        header("Location: admin.php");
        exit();
    }
} ?>
</body>
</html>