<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <?php
    include "include/nav.php";
    ?>
</head>
<body>
<?php
session_start();
$db=connect();
if(isset($_SESSION['admin']['login'])){
?>
<div class="container-fluid">

    <div class="row justify-content-center">
    <div class="col-mb">
            <a href="admin.php?action=add" class="btn btn-warning" >Dodaj produkt</a>
            <a href="admin.php?action=opinieioceny" class="btn btn-warning" >Opinie i oceny</a>
        </div>
</div>
        <?php
        if(isset($_GET['action'])&&$_GET['action']=="add"){ ?>
        <div class="row justify-content-center">
            <div class="col-md-5">
                <form method="post">
                    <div class="col-4">
                        <label for="nazwa">Nazwa</label>
                        <input type="text" id="nazwa" name="nazwa" class="form-control" required>
                    </div>
                    <div class="col-4">
                        <label for="zdj">Link do zdjęcia</label>
                        <input type="url" id="zdj" name="zdj" class="form-control" required>
                    </div>
                    <div class="col-4">
                        <label for="cena">Cena</label>
                        <input type="number" id="cena" name="cena" class="form-control" required>
                    </div>
                    <div class="col-4">
                        <label for="ilosc">Ilość</label>
                        <input type="number" id="ilosc" name="ilosc" class="form-control" required>
                    </div>
                    <div class="col-4">
                        <label for="opis">Opis krótki</label>
                        <textarea name="opis" id="opis" class="form-control" required placeholder="max 255 znaków"></textarea>
                    </div>
                    <div class="col-4">
                        <label for="opis2">Rozszerzony opis</label>
                        <textarea name="opis2" id="opis2" class="form-control" required placeholder="max 500 znaków"></textarea>
                    </div>
                    <div class="col-4">
                        <label for="marka">Marka Produktu</label>
                        <select name="marka" id="marka" class="form-control" required>
                            <option></option>
                            <?php
                            $result = $db->query("Select * from marka");
                            if ($result) {
                                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='".$row['id']."'>".$row['nazwa']."</product>";
                                }
                            } ?>
                        </select>
                    </div>
                    <div class="col-4">
                        <label for="kategoria">Kategoria produktu</label>
                        <select name="kategoria" id="kategoria" class="form-control" required>
                            <option></option>
                            <?php
                            $result = $db->query("Select * from kategoria");
                            if ($result) {
                                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='".$row['id']."'>".$row['nazwa']."</product>";
                                }
                            } ?>
                        </select>
                    </div>
                    <div class="col-4">
                        <br><input type="submit" name="new" value="dodaj" class="form-control btn btn-success">
                    </div>
                </form>
            </div>
        </div>
        <?php
        if(isset($_POST['new'])) {
            $f = true;

            if ($_POST['ilosc'] < 1) {
                $f = false;
                echo "<h4 class='tezt-danger'>Ilość nie może być mniejsza do 1</h4>";
            }
            if ($_POST['cena'] < 0) {
                $f = false;
                echo "<h4 class='tezt-danger'>Ilość nie może być mniejsza do 0</h4>";
            }
            if (strlen($_POST['opis']) > 255) {
                $f = false;
                echo "<h4 class='tezt-danger'>Krótki opis jest za długi</h4>";
            }
            if (strlen($_POST['opis2']) > 550) {
                $f = false;
                echo "<h4 class='tezt-danger'>Rozszerzony opis jest za długi</h4>";
            }
            if ($f) {
                $db = connect();
                $id = 0;
                $result = $db->query("SELECT * FROM produkty;");
                if ($result) {
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        $id = $row['id'];
                    }
                }
                $id++;
                $stmt = $db->prepare("INSERT INTO produkty(id,nazwa,ilosc,img,opis1,opis2,cena,marka_id) VALUES (?,?, ?, ?, ?, ?, ?,?)");
                $stmt->bindParam(1, $id);
                $stmt->bindParam(2, $_POST['nazwa']);
                $stmt->bindParam(3, $_POST['ilosc']);
                $stmt->bindParam(4, $_POST['zdj']);
                $stmt->bindParam(5, $_POST['opis']);
                $stmt->bindParam(6, $_POST['opis2']);
                $stmt->bindParam(7, $_POST['cena']);
                $stmt->bindParam(8, $_POST['marka']);
                $stmt->execute();
                $k = $_POST['kategoria'];
                $db->query("Insert into kategoria_produktu (kategoria_id,produkty_id) values ('$k','$id');");
                echo "<h3 class='text-center'>Dodano produkt</h3>";
            }
        }
    }
        if(isset($_GET['action'])&&($_GET['action']=="opinieioceny"||$_GET['action']=="opinie"||$_GET['action']=="oceny")) {
        echo "<form method='post'>";
            echo "<div class='row justify-content-center'>";
            echo "<div class='col-mb'>";
        echo "<label for='pr'>Produkt:</label>";
        echo "<select class='form-control' id='pr' name='product' required>";
        echo "<option></option>";
        $result = $db->query("SELECT * FROM produkty;");
        if ($result) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='" . $row['id'] . "'>" . $row['nazwa'] . "</option>";
            }
        }
        echo "</select>";
        echo "<input name='sub1' class='btn btn-primary' type='submit' value='Oceny'>";
        echo "<input name='sub2' class='btn btn-primary' type='submit' value='Opinie'>";
        echo "</div></div>";
        echo "</form>";

        if (isset($_POST['sub1'])) {
            $id = $_POST['product'];
            header("Location: admin.php?action=oceny&id=$id");
        }
        if (isset($_POST['sub2'])) {
            $id = $_POST['product'];
            header("Location: admin.php?action=opinie&id=$id");
        }
        if ($_GET['action'] == "oceny") {
            $id = $_GET['id'];
            echo "<form method='post'>";
            echo "<div class='row justify-content-center'>";
            echo "<div class='col-mb'>";
            echo "<select name='ocena' class='form-control' required>";
            echo "<option></option>";
            $result = $db->query("SELECT * FROM oceny where produkty_id='$id' order by ocena; ");
            if ($result) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='" . $row['id'] . "'>" . $row['ocena'] . "</option>";
                }
            }
            echo "</select>";
            echo "</div><div class='col-mb'>";
            echo "<input name='ocdelete' class='btn btn-danger' type='submit' value='Usuń'>";
            echo "</div></div></form>";

            if (isset($_POST['ocdelete'])) {
                $id = $_POST['ocena'];
                $db->query("delete FROM oceny where id='$id';");
                echo("<meta http-equiv='refresh' content='0'>");
            }
        }
        if ($_GET['action'] == "opinie") {
            $id = $_GET['id'];
            $result = $db->query("SELECT * FROM opinie where produkty_id='$id';");
            if ($result) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo "<form method='post'>";
                    echo "<div class='row justify-content-center'>";
                    echo "<div class='col-mb'>";
                    echo "<hr class='my-9'>";
                    echo "<h6>" . $row['tytul'] . "</h6>";
                    echo "<p>" . $row['opinia'] . "</p>";
                    echo "<small>" . $row['autor'] . "</small>";

                    echo "<button class='btn btn-danger bi bi-trash3-fill' type='submit' value='" . $row['id'] . "' name='del'></button>";
                    echo "</div></div>";
                    echo "</form>";

                    if (isset($_POST['del'])) {
                        $oid = $_POST['del'];
                        $db->query("Delete FROM opinie WHERE id='$oid';");
                        echo("<meta http-equiv='refresh' content='0'>");
                    }
                }

            }
        }
    }
}?>

</body>
</html>