<?php
require_once "include/sesconf.php";
session_start();
?>
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
    <textarea id="uwagi" name="uwagi" rows="4" cols="50" maxlength="255"></textarea><br>

    <button type="button" name="sub" onclick="checkData()">Sprawdz dostępność</button>
</form>

<p id="result"></p>

<button id="sendButton" name="sub" style="display: none;" onclick="saveToDatabase()">Wyślij</button>

<?php
//to do add obsługa error i miejsce na error
if (isset($_SESSION['udata']) && isset($_POST['sub'])) {
    $dane = $_SESSION['udata'];
    echo "<p>" . $dane['name'] . "</p>";
    echo "<p>" . $dane['surname'] . "</p>";
    echo "<p>" . $dane['phone_number'] . "</p>";
    echo "<p>" . $dane['email'] . "</p>";
}
?>
<script>
    function getCottageId() {
        var url = window.location.href;
        if (url.indexOf("?") !== -1) {
            var queryString = url.split("?")[1];
            var parametry = queryString.split("&");
            for (var i = 0; i < parametry.length; i++) {
                var para = parametry[i].split("=");
                if (para[0] === "cottage") {
                    return para[1];
                }
            }
        }
        return null;
    }

    function checkData() {
        console.log("sprawdzam");
        let form = document.getElementById('myForm');
        let formData = new FormData(form);
        var CottageId = getCottageId();
        formData.append('CottageId', CottageId);
        fetch('reservation-check.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json()) // Odbierz dane w formie JSON
            .then(data => {
                document.getElementById('result').innerHTML = 'Cena: ' + data.price + ' zł';
                formData.append('price', data.price);
                document.getElementById('sendButton').style.display = 'block';
            })
            .catch(error => console.error('Błąd:', error));
    }

    function saveToDatabase() {
        let form = document.getElementById('myForm');
        let formData = new FormData(form);

        fetch('reservation-save.php', {
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
