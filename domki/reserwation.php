<?php
require_once "include/sesconf.php";
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formularz Rezerwacji Domków Letniskowych</title>
</head>
<body>

<h1>Rezerwacja Domków Letniskowych</h1>

<form id="myForm" method="post">
    <label for="data_przyjazdu">Data przyjazdu:</label>
    <input type="date" id="data_przyjazdu" name="Start_data" required><br>

    <label for="data_wyjazdu">Data wyjazdu:</label>
    <input type="date" id="data_wyjazdu" name="End_data" required><br>

    <label for="liczba_osob">Liczba osób:</label>
    <input type="number" id="liczba_osob" name="people" required><br>

    <label for="uwagi">Dodatkowe uwagi:</label>
    <textarea id="uwagi" name="uwagi" rows="4" cols="50"></textarea><br>

    <input type="submit" name="sub" onclick="checkdata()" value="Sprawdz dostępność">
</form>

<p id="result"></p>

<button id="sendButton" style="display: none;" onclick="saveToDatabase()">Wyślij</button>

<?php
if($_SESSION['udata'] && isset($_POST['sub']) !== null){
    $dane=$_SESSION['udata'];
    echo "<p>".$dane['name']."</p>";
    echo "<p>".$dane['surname']."</p>";
    echo "<p>".$dane['phone_number']."</p>";
    echo "<p>".$dane['email']."</p>";
}
else{
    echo "<p id='nologin'></p>";
}
?>
<script>
    function checkdata() {
        var form = document.getElementById('myForm');
        var formData = new FormData(form);

        fetch('process_data.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json()) // Odbierz dane w formie JSON
            .then(data => {
                document.getElementById('result').innerHTML = 'Cena: ' + data.price + ' PLN';
                formData.append('price', data.price);
                document.getElementById('sendButton').style.display = 'block';
            })
            .catch(error => console.error('Błąd:', error));
    }

    function saveToDatabase() {
        var form = document.getElementById('myForm');
        var formData = new FormData(form);

        fetch('save_to_database.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(data => {
                // Obsługa odpowiedzi zapisu do bazy danych (możesz dodać komunikat lub inne działania)
                console.log(data);
            })
            .catch(error => console.error('Błąd:', error));
    }
</script>
</body>
</html>
