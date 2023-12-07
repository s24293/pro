<?php
session_start();
include "include/Product.php";
if(isset($_GET['action'])=="add"){
    $id=$_GET['id'];
    if(isset($_SESSION["koszyk"][$id])) {
        $qty= $_SESSION["koszyk"][$id]["qty"];
        $_SESSION["koszyk"][$id] = array("id" => $id, "qty" => $qty+1,"cena"=>1);
    } else{
        $_SESSION["koszyk"][$id] = array("id" => $id, "qty" => 1,"cena"=>1);
    }
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Fajny sklep</title>
    <link rel="stylesheet" type="text/css" href="css/button_style.css">
    <?php
    include 'include/nav.php';
    ?>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php

        $db=connect();
        $result =  $db->query( "SELECT * FROM produkty;");
        if ($result) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $product[] = new Product($row['id'], $row['nazwa'], $row['cena'], $row['opis1'], $row['img'], $row['ilosc']);
            }
        }

        foreach ($product as $results){
            ?>
            <div class='col-2'>
                <div class='card'>
                    <form method="post">

                        <div id='image_div'>
                            <p class='img_wrapper'>
                                <img src= '<?php echo $results->getImg()?>' class='card-img-top' alt='<?php echo $results->getid()?>'/><span>
                             <button name="subp" value="<?php echo $results->getId()?>" class='btn btn-warning btn-lg bi bi-cart-plus'>Dodaj</button><br>
                             <br><a href='show.php?id=<?php echo $results->getid() ?>' class='btn btn-lg btn-info'>Szczegły</a></span>
                                <input type="hidden" value="<?php echo $results->getStan() ?>"  name="qty">
                            </p>
                        </div>
                    </form>
                    <div class='card-body'>
                        <h5 class='card-title'> <?php echo $results->getname()?></h5>
                        <p class='card-text'><?php echo $results->getopis() ?></p>
                        <?php
                        echo "<h4>".$results->getCena()."zł</h4>";
                        echo $results->getCena() + (9.99) . " zł z dostawą<br>";
                        echo "Stan: " . $results->getstan() . " sztuk";
                        ?>
                    </div>
                </div>
            </div>
            <?php
            if(isset($_POST['subp'])){
           $id= $_POST['subp'];
             $qty=$_POST['qty'];
            if($qty>0){
                 header("Location: index.php?action=add&id=$id");
                 exit();
            }
            }
        } ?>
    </div>
</div>
</body>
</html>
