<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

if (isset($_GET["idFattura"])) {
    $idFattura = $_GET["idFattura"];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["submit"])) {
            $codiceProdotto = $_POST["codice_prodotto"];
            $quantita = $_POST["quantita"];

            // Verifica se il prodotto è già associato a questa fattura
            $queryCheckProdotto = "SELECT * FROM dettagli_fattura WHERE id_fattura = $idFattura AND codice_prodotto = $codiceProdotto";
            $resultCheckProdotto = $conn->query($queryCheckProdotto);

            if ($resultCheckProdotto->num_rows > 0) {
                echo "Il prodotto è già associato a questa fattura.";
            } else {
                // Inserisci il nuovo prodotto nella tabella dettagli_fattura
                $queryInserisciProdotto = "INSERT INTO dettagli_fattura (id_fattura, codice_prodotto, quantita) VALUES ($idFattura, $codiceProdotto, $quantita)";
                if ($conn->query($queryInserisciProdotto) === TRUE) {
                    echo "Prodotto aggiunto con successo alla fattura.";
                } else {
                    echo "Errore nell'aggiunta del prodotto: " . $conn->error;
                }
            }
        }
    }

    // Ottieni i dettagli della fattura
    $queryDettagliFattura = "SELECT prodotti.descrizione, dettagli_fattura.quantita, prodotti.prezzo_unitario
                             FROM dettagli_fattura
                             INNER JOIN prodotti ON dettagli_fattura.codice_prodotto = prodotti.codice
                             WHERE dettagli_fattura.id_fattura = $idFattura";
    $resultDettagliFattura = $conn->query($queryDettagliFattura);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prodotti Fattura</title>
</head>

<body>

    <h2>Prodotti della Fattura</h2>

    <?php
    if ($resultDettagliFattura && $resultDettagliFattura->num_rows > 0) {
        echo "<table border='1'>
            <tr>
            <th>Descrizione Prodotto</th>
            <th>Quantità</th>
            <th>Prezzo Unitario</th>
            </tr>";

        while ($row = $resultDettagliFattura->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['descrizione'] . "</td>";
            echo "<td>" . $row['quantita'] . "</td>";
            echo "<td>" . $row['prezzo_unitario'] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "Nessun prodotto associato a questa fattura.";
    }

    // Modulo per aggiungere nuovi prodotti
    ?>
    <h2>Aggiungi Nuovi Prodotti</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Codice Prodotto: <select name="codice_prodotto" required>
            <?php
            // Ottieni opzioni prodotti
            $queryProdottiOptions = "SELECT codice, descrizione FROM prodotti";
            $resultProdottiOptions = $conn->query($queryProdottiOptions);

            while ($row = $resultProdottiOptions->fetch_assoc()) {
                echo '<option value="' . $row['codice'] . '">' . $row['descrizione'] . '</option>';
            }
            ?>
        </select>
        Quantità: <input type="number" name="quantita" required>
        <input type="submit" name="submit" value="Aggiungi Prodotto">
    </form>

</body>

</html>

<?php
} else {
    echo "ID Fattura non specificato.";
}
?>
