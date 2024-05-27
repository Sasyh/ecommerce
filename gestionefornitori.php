<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eCommerce - Gestione Fornitori</title>
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

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["aggiungi_fornitore"])) {
        $piva = $_POST["piva"];
        $sdi = $_POST["sdi"];
        $nome = $_POST["nome"];
        $indirizzo = $_POST["indirizzo"];
        $telefono = $_POST["telefono"];
        $cap = $_POST["cap"];
        $citta = $_POST["citta"];
        
        $insert_query = "INSERT INTO fornitori (piva, sdi, nome, indirizzo, telefono, cap, citta) 
                        VALUES ('$piva', '$sdi', '$nome', '$indirizzo', '$telefono', '$cap', '$citta')";

        if ($conn->query($insert_query) === TRUE) {
            echo "Fornitore aggiunto con successo.";
        } else {
            echo "Errore durante l'aggiunta del fornitore: " . $conn->error;
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["elimina_fornitore"])) {
        $piva = $_POST["piva"];
        $delete_query = "DELETE FROM fornitori WHERE piva='$piva'";

        if ($conn->query($delete_query) === TRUE) {
            echo "Fornitore eliminato con successo.";
        } else {
            echo "Errore durante l'eliminazione del fornitore: " . $conn->error;
        }
    }

    $query = "SELECT * FROM fornitori";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "<h2>Elenco dei fornitori</h2>";
        echo "<table border>";
        echo "<tr><th>PIVA</th><th>SDI</th><th>Nome</th><th>Indirizzo</th><th>Telefono</th><th>CAP</th><th>Citta</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["piva"] . "</td>";
            echo "<td>" . $row["sdi"] . "</td>";
            echo "<td>" . $row["nome"] . "</td>";
            echo "<td>" . $row["indirizzo"] . "</td>";
            echo "<td>" . $row["telefono"] . "</td>";
            echo "<td>" . $row["cap"] . "</td>";
            echo "<td>" . $row["citta"] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "Nessun risultato trovato.";
    }

    $conn->close();
    ?>

    <h2>Aggiungi nuovo fornitore</h2>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        PIVA: <input type="text" name="piva" required><br>
        SDI: <input type="text" name="sdi" required><br>
        Nome: <input type="text" name="nome" required><br>
        Indirizzo: <input type="text" name="indirizzo" required><br>
        Telefono: <input type="number" name="telefono" required><br>
        CAP: <input type="number" name="cap" required><br>
        citta: <input type="text" name="citta" required><br>
        <input type="submit" name="aggiungi_fornitore" value="Aggiungi Fornitore">
    </form>

    <h2>Elimina fornitore</h2>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        PIVA: <input type="text" name="piva" required><br>
        <input type="submit" name="elimina_fornitore" value="Elimina Fornitore">
    </form>
</body>
</html>
