<?php
include "include/sql.php";
include "bootstrap.html";
?>
 <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
     <a class="navbar-brand" href="index.php">Home</a>
     <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
     </button>
     <div class="collapse navbar-collapse" id="navbarSupportedContent">
<!--         <ul class="navbar-nav mr-auto">-->
<!--             <li class="btn-success dropdown">-->
<!--                 <a class="nav-link text-white dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" >Kategoria</a>-->
<!--                 <div class="dropdown-menu" aria-labelledby="navbarDropdown">-->
<!--                     --><?php
//                     $db=connect();
//                     $result = $db->query("SELECT * FROM kategoria;");
//                     if ($result) {
//                         while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
//                            echo "<a class='dropdown-item' href='kategoria.php?id=".$row['id']."'>".$row['nazwa']."</a>";
//                         }
//                     } ?>
<!--                 </div>-->
<!--             </li>-->
<!--         </ul>-->
         <a href="Koszyk.php" class="btn btn-warning me-2" ><i class="bi bi-cart2"></i> Koszyk</a>
         <a href="login.php" class="btn btn-info me-2" ><i class="bi bi-person-circle"></i></a>
     </div>
 </nav>
