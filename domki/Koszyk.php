<?php
session_start();

function produktwkoszyku($product) :void {
  $id=$product->getId();
    ?>
   <hr><div class='row '>
    <div class='col-md-3'>
    <img src='<?php echo $product->getimg() ?>' class="img-fluid rounded-start" alt="...">
    </div>
    <div class="col-md-9">
        <div class="card-body">
            <h5 class="card-title"><?php echo $product->getName(); ?> </h5>
            <p class="card-text">
            <form method="post">
                <label for="<?php echo $id ?>"></label>
                <input type="number" name="ilosc" id="<?php echo $id ?>" value="<?php  echo $product->getQty()?>">
                <button type="submit" name="sub" value="<?php echo $id ?>" class="btn btn-success">przelicz</button>
                <a href='Koszyk.php?action=delete&id=<?php echo $id ?>' class='btn bi-trash3-fill btn-danger'></a>
            </form>
            <?php
            if(isset($_POST["ilosc"])){
                $id=$_POST['sub'];
               $qty=$_POST["ilosc"];
                if ($qty<=$product->getStan()&&$qty>0) {
                    header("Location: Koszyk.php?action=set&id=$id&qty=$qty");
                    exit();
                }
            }
            $_SESSION["koszyk"][$product->getId()]["cena"]=$product->getCena()*$product->getQty();
            echo $product->getQty()." * ".$product->getCena()."zł = ". $_SESSION["koszyk"][$product->getId()]["cena"]."zł";
            ?>
        </div>
    </div>
</div>
<?php } ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Koszyk</title>
    <?php include 'include/nav.php';?>
    <style>
        input[type=number] {
            width: 50%;
            padding: 1px 3px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
    </style>

</head>
<body>
<?php
$db=connect();
include "include/Product.php";
if(isset($_GET['action'])&&$_GET['action']=="set") {
    $qty = $_GET['qty'];
    $id = $_GET['id'];
    $_SESSION["koszyk"][$id]["qty"]=$qty;
    header("Location: Koszyk.php");
    exit();
}
if(isset($_GET['action'])&&$_GET['action']=="delete") {
    $id = $_GET['id'];
    if (isset($_SESSION["koszyk"][$id])) {
        unset($_SESSION["koszyk"][$id]);
    }
    if (count($_SESSION["koszyk"]) < 1) {
        unset($_SESSION["koszyk"]);
    }
    header("Location: Koszyk.php");
    exit();
}
if(!isset($_SESSION['dostawa']['cena'])) {
    $result = $db->query("SELECT * FROM dostawa;");
    if ($result) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            if ($row['id'] == 1) {
                $_SESSION['dostawa']['cena'] = $row['cena'];
            }
            break;
        }
    }
}
if(isset($_SESSION["koszyk"])){
    $db=connect();
    echo "<div class='col d-flex justify-content-center'>";
    echo " <div class='card mb-3' style='max-width: 30%;'>";
    foreach ($_SESSION["koszyk"] as $pro) {
        $id = $pro["id"];
        $result = $db->query("SELECT * FROM produkty WHERE ID='$id';");
        if ($result) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $product[] = new Product($row['id'], $row['nazwa'], $row['cena'], $row['opis1'], $row['img'], $row['ilosc']);
                if(isset($product)) {
                    $product[count($product) - 1]->setQty($pro["qty"]);
                    $pro["stan"]=$product[count($product) - 1]->getStan();
                }
            }
        }
    }
    if(isset($product)) {
        foreach ($product as $product2) {
            produktwkoszyku($product2);
        }
        $cena=0;
        foreach ($_SESSION['koszyk'] as $p){
            $cena+=$p['cena'];
        }
        $_SESSION['fullprice']=$cena;
        echo "<h6 class='text-primary'>Dostawa: ".$_SESSION['dostawa']['cena']."zł</h6>";
        echo "<h5 class='text-primary'>Cena z dostawą: ". $cena+$_SESSION['dostawa']['cena']."zł</h5>";
        echo "<a href='dostawa.php?page=1' class='btn btn-primary'>Kup</a>";
        echo"</div></div>";
    }
}else
    echo "<h1 class='text-center'> Koszyk pusty</h1>";
?>
</body>
</html>