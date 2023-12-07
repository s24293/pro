<!--https://github.com/codingWithElias/Login-registration-System-PHP-and-MYSQL/blob/master/db_conn.php-->
<!DOCTYPE html>
<html>
<head>
    <title>SIGN UP</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<form action="signup-check.php" method="post">
    <h2>SIGN UP</h2>
    <?php if (isset($_GET['error'])) { ?>
        <p class="error"><?php echo $_GET['error']; ?></p>
    <?php } ?>

    <?php if (isset($_GET['success'])) { ?>
        <p class="success"><?php echo $_GET['success']; ?></p>
    <?php } ?>
    <label for="firstname" class="form-label">Imię:</label>
    <input type="text" class="form-control" id="firstname" name="firstname" required>

    <label for="lastname" class="form-label">Nazwisko:</label>
    <input type="text" class="form-control" id="lastname" name="lastname" required>

    <label for="phone" class="form-label">Numer telefonu:</label>
    <input type="tel" class="form-control" id="phone" name="phone" required>

    <label for="email" class="form-label">Adres e-mail:</label>
    <input type="email" class="form-control" id="email" name="email" required>

    <label>Password</label>
    <input type="password"
           name="password"
           placeholder="Password"><br>

    <label>Re Password</label>
    <input type="password"
           name="re_password"
           placeholder="Re_Password"><br>

    <button type="submit">Sign Up</button>
    <a href="index.php" class="ca">Already have an account?</a>
</form>
</body>
</html>