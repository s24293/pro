<?php
include "include/Product.php";
session_start();
if(isset($_GET['action'])=="add"){
    $id=$_GET['id'];
    $pid=$_GET['pid'];
    $url="kategoria.php?id=$id";
    if(isset($_SESSION["koszyk"][$pid])) {
        $qty= $_SESSION["koszyk"][$pid]["qty"];
        $_SESSION["koszyk"][$pid] = array("id" => $pid, "qty" => $qty+1,"cena"=>1);
    } else{
        $_SESSION["koszyk"][$pid] = array("id" => $pid, "qty" => 1,"cena"=>1);
    }
    print_r($_SESSION["koszyk"]);

    if(isset($_GET['min'])) {
    $min=$_GET['min'];
    $url.="&min=$min";
    }
 if(isset($_GET['max'])) {
     $max=$_GET['max'];
     $url.="&max=$max";
}
if(isset($_GET['sort'])) {
    $s=$_GET['sort'];
    $url.="&sort=$s";
}
    if(isset($_GET['marka'])&&$_GET['marka']!="null"){
        $m= $_GET['marka'];
        $url.="&marka=$m";
    }
    header("Location: $url");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" type="text/css" href="css/button_style.css">
    <?php
    include "include/nav.php";
    ?>
</head>
<body>
<?php
if(isset($_GET['id'])){
    $id=$_GET['id'];
$url="kategoria.php?id=$id";
$sql="SELECT p.id, p.nazwa, p.ilosc, p.img, p.opis1, p.opis2, p.cena from kategoria_produktu join kategoria k on k.id = kategoria_produktu.kategoria_id join produkty p on p.id = kategoria_produktu.produkty_id join marka m on m.id = p.marka_id where k.id='$id'";
    if(isset($_GET['min'])) {
        $min=$_GET['min'];
        if($min>0) {
            $sql .= " and p.cena>$min";
            $url.="&min=$min";
        }
    }
    if(isset($_GET['max'])) {
        $max=$_GET['max'];
        if($max>0) {
            $sql .= " and p.cena<$max";
            $url.="&max=$max";
        }
    }
if(isset($_GET['marka'])&&$_GET['marka']!="null"){
    $m= $_GET['marka'];
    $sql .= " and m.id=$m";
    $url.="&marka=$m";
}

    if(isset($_GET['sort'])) {
        $sid = $_GET['sort'];
        $url.="&sort=$sid";
        if ($sid == 0) {
            $sql .= " order by p.cena";
        }
        if ($sid == 1) {
            $sql .= " order by p.cena desc";
        }
    }
        $db = connect();
        $result = $db->query($sql);
        if ($result) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $product[] = new Product($row['id'], $row['nazwa'], $row['cena'], $row['opis1'], $row['img'], $row['ilosc']);
            }
        }

    ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-2">
            <form method="post">
                <ul class="list-group mb-3">
                 <li class="list-group-item lh-sm">
                 <label class="form-label" for="sort"> </label>
                 <select  id="sort" name="sort" class="btn btn-outline-info form-control">
                     <option value="0">Cena malejąco</option>
                     <option value="1">Cena rosnąco</option>
                 </select>
                     <h5></h5>
                 <h5 class="text-center">Cena</h5>
                 <label for="min" class="form-label">Od</label>
                 <input type="number" id="min" class="form-control" name="min">

                 <label for="max" class="form-label">Do</label>
                 <input type="number" id="max" class="form-control" name="max">

                 <label for="marka">Marka</label>
                     <select id="marka" name="marka" class="btn btn-outline-info form-control">
                         <option value="null"></option>
                         <?php
                         $result = $db->query("Select * from marka;");
                         if ($result) {
                             while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                 echo "<option value='".$row['id']."'>".$row['nazwa']."</option>";
                             }
                         }
                         ?>
                     </select>
                     <p></p><button type="submit" class="btn btn-success" name="sub" value="<?php echo $id;?>" >Zastosuj</button>
                 </li>
             </ul>
         </form>
         <?php if(isset($_POST['sub'])){
             $id=$_POST['sub'];
             $url="kategoria.php?id=$id";

             if(isset($_POST['min'])&&isset($_POST['max'])) {
                 $min=$_POST['min'];
                 $max=$_POST['max'];
                 if($min>0&&$max>0&&$min<$max){
                     $url.="&min=$min&max=$max";
                 }
             }else if(isset($_POST['min'])) {
                 $min=$_GET['min'];
                 if($min>0){
                     $url.="&min=$min";
                 }
             }else if(isset($_POST['max'])) {
                 $max=$_POST['max'];
                 if($max>0){
                     $url.="&max=$max";
                 }
             }
             if(isset($_POST['sort'])){
                 $s=$_POST['sort'];
                 $url.="&sort=$s";
             }
             if(isset($_POST['marka'])){
                 $m=$_POST['marka'];
                 $url.="&marka=$m";
             }
             header("Location: $url");
             exit();
         }?>
     </div>
 </div>
    <div class="row">
        <?php
        if(isset($product)){
        foreach ($product as $results){?>
            <div class='col-2'>
                <div class='card'>
                    <form method="post" action="<?php echo $url."&pid=".$results->getid()."&action=add" ?>">
                        <div id='image_div'>
                            <p class='img_wrapper'>
                                <img src= '<?php echo $results->getImg()?>' class='card-img-top' alt='<?php echo $results->getid()?>'/><span>
                                    <button name="add" class='btn btn-warning btn-lg bi bi-cart-plus'>Dodaj</button><br>
                                    <br><a href='show.php?id=<?php echo $results->getid() ?>' class='btn btn-lg btn-info'>Szczegóły</a></span>
                            </p>
                        </div>
                    </form>
                    <div class='card-body'>
                        <h5 class='card-title'> <?php echo$results->getname()?></h5>
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
        }
}else
    echo"<h1>Brak produktów</h1>";
}

?>
    </div>
</div>

</body>
</html>