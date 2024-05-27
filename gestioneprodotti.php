<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eCommerce - Gestione Prodotti</title>
</head>
<body>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ecommerce";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connessione al database fallita: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["aggiungi_prodotto"])) {
        $codice = $_POST["codice"];
        $marca = $_POST["marca"];
        $modello = $_POST["modello"];
        $descrizione = $_POST["descrizione"];
        $prezzo_iniziale = $_POST["prezzo_iniziale"];
        $prezzo_vendita = $_POST["prezzo_vendita"];
        $fornitore_piva = $_POST["fornitore_piva"];
        $iva_acquisto = $_POST["iva_acquisto"];
        $iva_vendita = $_POST["iva_vendita"];


        $prezzo_iniziale_iva = $prezzo_iniziale + ($prezzo_iniziale * $iva_acquisto / 100);
        $prezzo_vendita_iva = $prezzo_vendita + ($prezzo_vendita * $iva_vendita / 100);

        $insert_query = "INSERT INTO prodotti (codice, marca, modello, descrizione, prezzo_iniziale, prezzo_vendita, prezzo_iniziale_iva, prezzo_vendita_iva, fornitore_piva, iva_acquisto, iva_vendita) 
                        VALUES ('$codice', '$marca', '$modello', '$descrizione', '$prezzo_iniziale', '$prezzo_vendita', '$prezzo_iniziale_iva', '$prezzo_vendita_iva', '$fornitore_piva', '$iva_acquisto', '$iva_vendita')";

        if ($conn->query($insert_query) === TRUE) {
            echo "Prodotto aggiunto con successo.";
        } else {
            echo "Errore durante l'aggiunta del prodotto: " . $conn->error;
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["elimina_prodotto"])) {
        $codice = $_POST["codice"];
        $delete_query = "DELETE FROM prodotti WHERE codice='$codice'";

        if ($conn->query($delete_query) === TRUE) {
            echo "Prodotto eliminato con successo.";
        } else {
            echo "Errore durante l'eliminazione del prodotto: " . $conn->error;
        }
    }

    $query = "SELECT * FROM prodotti";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "<h2>Elenco dei prodotti</h2>";
        echo "<table border>";
        echo "<tr><th>Codice</th><th>Marca</th><th>Modello</th><th>Descrizione</th><th>Prezzo Iniziale</th><th>Prezzo Vendita</th><th>Prezzo Iniziale + IVA</th><th>Prezzo Vendita + IVA</th><th>Fornitore PIVA</th><th>IVA Acquisto</th><th>IVA Vendita</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["codice"] . "</td>";
            echo "<td>" . $row["marca"] . "</td>";
            echo "<td>" . $row["modello"] . "</td>";
            echo "<td>" . $row["descrizione"] . "</td>";
            echo "<td>" . $row["prezzo_iniziale"] . "</td>";
            echo "<td>" . $row["prezzo_vendita"] . "</td>";
            echo "<td>" . $row["prezzo_iniziale_iva"] . "</td>";
            echo "<td>" . $row["prezzo_vendita_iva"] . "</td>";
            echo "<td>" . $row["fornitore_piva"] . "</td>";
            echo "<td>" . $row["iva_acquisto"] . "</td>";
            echo "<td>" . $row["iva_vendita"] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "Nessun risultato trovato.";
    }

    $conn->close();
    ?>

    <h2>Aggiungi nuovo prodotto</h2>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        Codice: <input type="text" name="codice" required><br>
        Marca: <input type="text" name="marca" required><br>
        Modello: <input type="text" name="modello" required><br>
        Descrizione: <input type="text" name="descrizione" required><br>
        <h2></h2>
        Prezzo Iniziale: <input type="number" name="prezzo_iniziale" required><br>
        IVA Acquisto: <input type="number" name="iva_acquisto" required><br>
        <h2></h2>
        Prezzo Vendita: <input type="number" name="prezzo_vendita" required><br>
        IVA Vendita: <input type="number" name="iva_vendita" required><br>
        <h2></h2>
        Fornitore PIVA: <input type="text" name="fornitore_piva" required><br>
        <input type="submit" name="aggiungi_prodotto" value="Aggiungi Prodotto">
    </form>

    <h2>Elimina prodotto</h2>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        Codice: <input type="text" name="codice" required><br>
        <input type="submit" name="elimina_prodotto" value="Elimina Prodotto">
    </form>
</body>
</html>
