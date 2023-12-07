<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <?php include "include/nav.php"; ?>
</head>
<body>
<div class="row justify-content-center">
    <?php
    if (!isset($_SESSION['admin']['login'])){
    $db = connect(); ?>
    <div class="col-md-5">
        <h4 class="mb-3">Logowanie</h4>
        <form method="post">
            <div class="row">
                <div class="col-4">
                    <label for="email" class="form-label">Login</label>
                    <input id="email" type="email" name="email" class="form-control" required>
                </div>
                <div class="col-4">
                    <label for="password" class="form-label">Hasło</label>
                    <input id="password" type="password" name="pas" class="form-control" required><br>
                </div>
                <div class="col-8">
                    <input type="submit" value="Zaloguj" name="sub" class="form-control btn btn-info">
                </div>
        </form>
        <div class="col-8">
            <?php
            if (isset($_POST['sub'])) {
                $stmt = $db->prepare("SELECT * FROM user where email = ?");
                $stmt->bindParam(1, $_POST['email']);
                $stmt->execute();
                if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    if (password_verify($_POST['pas'], $row['password'])) {
                        $_SESSION['admin']['login'] = $_POST['email'];
                        header("Location: login.php");
                    } else
                        echo "<h4 class='text-danger'>Nie poprawny login lub hasło</h4>";
                } else
                    echo "<br><h4 class='text-danger'>Nie poprawny login lub hasło</h4>";
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                print_r($row);
                echo "</div></div></div>";
            }
            } else {
                ?>
                <form method="post">
                    <h4 class='text-info text-center'>Witaj</h4><br>
                    <input type="submit" value="Admin Panel" name="addp" class="btn btn-info text-center">
                    <input type="submit" value="Wyloguj" name="logout" class="btn btn-danger text-center">
                </form>

                <?php
                if (isset($_POST['logout'])) {
                    unset($_SESSION['admin']);
                    header("Location: login.php");
                    exit();
                }
                if (isset($_POST['addp'])) {
                    header("Location: admin.php");
                    exit();
                }
            }
            ?>
        </div>

</body>
</html>