<?php
session_start();
include "include/sql.php";
include "include/Product.php";
$db=connect();
if(isset($_GET['page']))
   $page=$_GET['page']
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dostawa</title>
    <?php
    include "include/bootstrap.html";
    ?>
</head>
<body>
<?php
if(isset($_SESSION["koszyk"])&&isset($_SESSION['fullprice'])&&isset($page)){
    if($page==1){?>
<div class="row justify-content-center">
<div class="col-md-5">
        <h4 class="mb-3">Address dostawy</h4>
        <form  method="post">
            <div class="row">
                <div class="col-4">
                    <label for="firstName" class="form-label">Imie</label>
                    <input type="text" class="form-control" name="firstname" id="firstName" required>
                </div>
                <div class="col-4">
                    <label for="lastname" class="form-label">Nazwisko</label>
                    <input type="text" class="form-control" name="lastname" id="lastname" required><br>
                </div>
                <div class="col-5">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email"><br>
                </div>
                <div class="col-5">
                <label for="phone" class="form-label">Numer telefonu</label>
                <input type="text" class="form-control" name="phone" id="phone">
                </div>
                <div class="col-4">
                    <label for="street" class="form-label">Ulica</label>
                    <input class="form-control" type="text" id="street" name="street" required>
                </div>
                <div class="col-3">
                   <label for="number" class="form-label">Numer domu</label>
                    <input id="number" class="form-control" type="text" name="number" required>
                </div>
                <div class="col-3">
                    <label for="number2" class="form-label">Numer lokalu <small>(opcjonalne)</small></label>
                    <input id="number2" class="form-control" type="text" name="number2">
                </div>
                <div class="col-4">
                    <br><label for="city" class="form-label">Miejscowość</label>
                <input class="form-control" type="text" id="city" name="city" required>
                </div>
                <div class="col-2">
                    <br> <label for="zip" class="form-label">Kod Pocztowy</label>
                <input id="zip" class="form-control" type="text" name="zip" required>
                </div>
            </div>
            <br><input class='btn btn-success' type='submit' value='Zapisz' name='sub1'>
        </form>
    </div>
</div>
    <?php }
    if(isset($_POST['sub1'])) {
        $flag=true;
        //imie
        if (!preg_match("/^([a-z]{2,3} [A-ZŁŻ][a-ząęóżźćńłś]{2,})|([A-ZŁŻ][a-ząęóżźćńłś]{2,})(-[A-ZŁŻ][a-ząęóżźćńłś]{2,})?$/u", $_POST['firstname'])){
            $flag = false;
            echo "<span class='text-danger'>Nie poprawne imie</span><br>";
        }
        //nazwisko
        if (!preg_match("/^([a-z]{2,3} [A-ZŁŻ][a-ząęóżźćńłś]{2,})|([A-ZŁŻ][a-ząęóżźćńłś]{2,})(-[A-ZŁŻ][a-ząęóżźćńłś]{2,})?$/u", $_POST['lastname'])){
            $flag = false;
            echo "<span class='text-danger'>Nie poprawne nazwisko</span><br>";
        }
        //email
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $flag = false;
            echo "<span class='text-danger'>Nie poprawny email</span><br>";
        }
        //phone
        if (!preg_match( "/([0-9]{3})([ .-]?)([0-9]{3})([ .-]?)([0-9]{3})/", $_POST['phone'])){
            $flag = false;
            echo "<span class='text-danger'>Nie poprawny numer telefonu</span><br>";
        }
        //dom
        if (!preg_match("/^[0-9]{1,2}[a-z]?$/i", $_POST['number'])){
            $flag = false;
            echo "<span class='text-danger'>Nie poprawny numer domu</span><br>";
        }
        //lokal
        if ($_POST['number2']!=null&&!preg_match("/^[0-9]{1,2}[a-z]?$/i", $_POST['number2'])) {
            $flag = false;
            echo "<span class='text-danger'>Nie poprawny numer lokalu</span><br>";
        }
        //zip
        if (!preg_match("/^[0-9]{2}(-?)[0-9]{3}$/", $_POST['zip'])){
            $flag = false;
            echo "<span class='text-danger'>Nie poprawny kod pocztowy</span><br>";
        }
        if($flag){
           $_SESSION['dostawa']['dane']=$_POST['firstname'].";".
               $_POST['lastname'].";".
               $_POST['email'].";".
               $_POST['phone'].";".
               $_POST['city'].";".
               $_POST['street'].";".
               $_POST['number'];
           if($_POST['number2']!=null){
               $_SESSION['dostawa']['dane']=$_SESSION['dostawa']['dane'].";".$_POST['number2'];
           }
       }
       header("Location: dostawa.php?page=2");
       exit();
    }

    if($page==2){

        echo "<div class='row justify-content-center'>";
        echo "<form method='post' class='col-lg-6 offset-lg-3'>";
        echo "<h4>Dostawa</h4>";
        $result = $db->query("SELECT * FROM dostawa;");
        if ($result) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                if ($row['id'] == 1 && !isset($_SESSION['dostawa']['cena'])) {
                    $_SESSION['dostawa']['cena'] = $row['cena'];
                }
                echo "<label for='" . $row['id'] . "'></label><input id='" . $row['id'] . "' value='" . $row['cena'] . "' name='dostawa' type='radio' required>";
                echo $row['nazwa'] . " " . $row['cena'] . "zł<br>";
            }
            echo "<br><input class='btn btn-success' type='submit' value='Wybierz' name='sub2'>";
            echo "</form></div></div>";
            if (isset($_POST['dostawa'])) {
                $_SESSION['dostawa']['cena'] = $_POST['dostawa'];
                header("Location: dostawa.php?page=3");
                exit();
            }
        }
    }

    if ($page==3) {
        echo "<div class='row justify-content-center'>";
        echo "<form method='post' class='col-lg-6 offset-lg-3'>";
        echo "<h4>Płatność</h4>";
        $resultp = $db->query("SELECT * FROM platnosc;");
        if ($resultp) {
            while ($row = $resultp->fetch(PDO::FETCH_ASSOC)) {
                echo "<label for='" . $row['id'] . "'></label><input id='" . $row['id'] . "' value='" . $row['nazwa'] . "' name='pay' type='radio' required>";
                echo $row['nazwa'] . "<br>";
            }
            echo "<br><input class='btn btn-success' type='submit' value='Wybierz' name='sub3'>";
            echo "</form></div>";
        }
    }
    if( isset($_POST['sub3'])){
        $_SESSION['dostawa']['pay']=$_POST['pay'];
        header("Location: dostawa.php?page=4");
        exit();
    }

    if($page==4){
        $dane=explode(';',$_SESSION["dostawa"]["dane"]);
        echo "<h4 class='text-center'>Dane:</h4>";
        echo "<address class='text-center'>";
        echo $dane[0]." ".$dane[1]."<br>";
        echo $dane[2]."<br> ".$dane[3]."<br>";
        echo $dane[4]." ".$dane[5]." ".$dane[6];
        if(count($dane)>7)
            echo "/" . $dane[7];
        echo "</address>";
        echo "<hr>";
        foreach ($_SESSION["koszyk"] as $pro) {
            $id = $pro["id"];
            $result = $db->query("SELECT * FROM produkty WHERE ID='$id';");
            if ($result) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $product[] = new Product($row['id'], $row['nazwa'], $row['cena'], $row['opis1'], $row['img'], $row['ilosc']);
                }
            }
        }
        echo "<p class='text-center'>";
        foreach ($product as $product2) {
            echo $product2->getName() . " dostawa.php" .$_SESSION["koszyk"][$product2->getId()]["qty"]." * ".$product2->getCena()." = ".$_SESSION["koszyk"][$product2->getId()]["qty"] * $product2->getCena()."<br>";
        }
        echo "Dostawa: ".$_SESSION['dostawa']['cena']."<br>";
        echo "Cena z dostawą: ".$_SESSION['fullprice']."zł<br>";
        echo "Płatność: ". $_SESSION['dostawa']['pay']."<br>";
        echo "<hr><div class='col-md-12 text-center'><form method='post'>";
        echo "<a href='Koszyk.php' class='btn btn-danger' >Anuluj</a>";
        echo "<input type='submit' name='kup' class='btn btn-success' value='Kup' >";
        echo "</form></div></p>";

        if(isset($_POST['kup'])){
            echo "<div class='col-md-12 text-center'>";
            echo "<h5 class='text-danger' >Napewno?</h5>";
            echo "<form method='post'>";
            echo "<a href='Koszyk.php' class='btn text-center btn-danger' >Nie</a>";
            echo "<input type='submit' name='kup2' class='btn btn-success' value='Tak' >";
            echo "</form></div>";
        }
        if(isset($_POST['kup2'])){
            foreach ($product as $product2) {
                $product2->kup($_SESSION["koszyk"][$product2->getId()]["qty"]);
            }
          unset( $_SESSION['dostawa']);
            header("Location: dostawa.php?page=5");
            exit();
        }
    }

    if($page==5){
    echo "<div class='col-md-12 text-center'>";
    echo "<h2>Gotowe</h2>";
    echo "<form method='post'>";
    echo "<input type='submit' name='end' class='btn btn-success' value='Powrót na strone główną' >";
    echo "</form></div>";
echo "</div>";
}
    if(isset($_POST['end'])){
        session_destroy();
        header("Location: index.php");
        exit();
    }
} ?>

</body>
</html>