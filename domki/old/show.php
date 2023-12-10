<?php
session_start();
include "include/Product.php";

if(isset($_GET['action'])=="add"){
    $id=$_GET['id'];
    if(isset($_SESSION["koszyk"][$id])) {
        $qty= $_SESSION["koszyk"][$id]["qty"];
        $_SESSION["koszyk"][$id] = array("id" => $id, "qty" => $qty+1,"cena"=>0);
    } else{
        $_SESSION["koszyk"][$id] = array("id" => $id, "qty" => 1,"cena"=>0);
    }
    header("Location: show.php?id=$id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Fajny sklep</title>
    <link rel="stylesheet" type="text/css" href="../css/button_style.css">
    <?php include "include/nav.php"; ?>
</head>
<body>
<div class="row">
<?php
if(isset($_GET['id'])){
   $id=$_GET['id'];
   $db=connect();
    $result = $db->query("SELECT * FROM produkty WHERE ID='$id';");
    if ($result) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
            $product = new Product($row['id'], $row['nazwa'], $row['cena'], $row['opis1'], $row['img'], $row['ilosc']);
        $product->setOpis2($row['opis2']);
?>
    <div class="col">
        <div class="mx-auto" style="width: 50%;">
            <h2 class="text-center"><?php echo $product->getName()?></h2>
            <img src='<?php echo $product->getImg() ?>' class="rounded float-left" alt="img">
            <form method="post" action="<?php echo "show.php?id=$id&action=add";?>">
                <h1 class="rounded float-left"><?php echo $product->getCena() ?>zł</h1>
                <p class="float-right">
                <button name="sub" value="<?php echo $id ?>" class='btn btn-warning btn-lg bi bi-cart-plus'>Dodaj</button><br>
                </p>
            </form><br>
            <p class='text-center'><br>
            <h4>Dostawa:</h4>
            <ul>
   <?php
        $result = $db->query("SELECT * FROM dostawa;");
        if ($result) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC))
                echo "<li>".$row['nazwa']. " ". $row['cena']."zł</li>";
            echo "</ul>";
            echo "<p>".$product->getOpis2()."</p>" ;
      }
    }
 ?>

        </div>
    </div>
<div class="mx-auto" style="width: 50%;">
    <div class="col-mb">
        <h3>Średnia ocen:</h3>
        <?php

        $result =  $db->query( "SELECT avg(ocena) as ocena from oceny where produkty_id=$id;");
        if ($result) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
               echo "<h5>".$row['ocena']."/5</h5>";

            }
        }
        ?>
        <form method="post">
            <label for="oceny" class="form-label">Dodaj ocene</label>
            <select id="oceny" class="btn btn-outline-info " name="oceny" required>
                <option></option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <button type="submit" name="addoc" value="<?php echo $id ?>" class="btn btn-success">Dodaj</button>
        </form>
        <?php
        if(isset($_POST['addoc'])){
            $oc=$_POST['oceny'];
            $id=$_POST['addoc'];
        $db->query("Insert into oceny (ocena,produkty_id) values    ('$oc','$id');");
        }

        ?>
        <h3> Opinie:</h3>
        <form method="post">
            <label for="title" >Tytuł</label><br>
            <input name="title" id="title" type="text" required placeholder="max 50 znaków"><br>
            <label for="kom">Treść</label><br>
            <textarea id="kom" name="kom" style="height: 10%; width: 30%" required placeholder="max 255 znaków"></textarea><br>
            <label for="name">Autor<small>(opcionalne)</small></label><br>
            <input name="name" id="name" type="text" placeholder="max 50 znaków"><br><br>
            <input type="submit" name="new" class="btn btn-info">
        </form>
        <?php if(isset($_POST['new'])){
            $f=true;
            if(strlen($_POST['kom'])>255) {
                $f=false;
                echo "<h5 class='text-danger'>Za długa opnia</h5>";
            }
            if(strlen($_POST['title'])>50) {
                $f=false;
                echo "<h5 class='text-danger'>Za długi tytuł</h5>";
            }
            if(strlen($_POST['name'])>50) {
                $f=false;
                echo "<h5 class='text-danger'>Za długa nazwa autora</h5>";
            }
            if($f) {
            $t=$_POST['title'];
            $k=$_POST['kom'];
            $a = $_POST['name'] ?? null;
            $stmt = $db->prepare("INSERT INTO opinie(tytul,opinia,autor,produkty_id) VALUES (?, ?, ?,?)");
            $stmt->bindParam(1,$t);
            $stmt->bindParam(2,$k);
            $stmt->bindParam(3,$a);
            $stmt->bindParam(4,$id);
            $stmt->execute();
        }
        }
        ?>
        <form method="post">
            <br><input class="btn btn-primary" type="submit" value="Pokaż opinie" name="show">
        </form>
        <?php
        if(isset($_POST['show'])||isset($_POST['del'])) {
            $result = $db->query("SELECT * FROM opinie WHERE produkty_id='$id';");
            if ($result) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo "<hr class='my-9'>";
                    echo "<h6>" . $row['tytul'] . "</h6>";
                    echo "<p>" . $row['opinia'] . "</p>";
                    echo "<small>" . $row['autor'] . "</small>";

                if(isset($_SESSION['admin']['login'])) {
                   echo "<form method='post'>";
                       echo "<button class='btn btn-danger bi bi-trash3-fill' type='submit' value='". $row['id']."' name='del'></button>";
                    echo "</form>";
                    if(isset($_POST['del'])) {
                        $oid=$_POST['del'];
                        $db->query("Delete FROM opinie WHERE id='$oid';");
                        echo("<meta http-equiv='refresh' content='0'>");
                    }
                }
                    echo "<hr class='my-9'>";
                }
        }
        }
        ?>
    </div>
</div>
</div>
<?php }?>
</body>
</html>