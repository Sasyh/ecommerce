<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eCommerce - Gestione Clienti</title>
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

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["aggiungi_cliente"])) {
        $cf = $_POST["cf"];
        $cognome = $_POST["cognome"];
        $nome = $_POST["nome"];
        $indirizzo = $_POST["indirizzo"];
        $cap = $_POST["cap"];
        $telefono = $_POST["telefono"];
        $email = $_POST["email"];
        $citta = $_POST["citta"];
        $sdi = $_POST["sdi"];
        
        $insert_query = "INSERT INTO clienti (cf, cognome, nome, indirizzo, cap, telefono, cap_iva, telefono_iva, email, citta, sdi) 
                        VALUES ('$cf', '$cognome', '$nome', '$indirizzo', '$cap', '$telefono', '$email', '$citta', '$sdi')";

        if ($conn->query($insert_query) === TRUE) {
            echo "Cliente aggiunto con successo.";
        } else {
            echo "Errore durante l'aggiunta del cliente: " . $conn->error;
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_product"])) {
        $cf = $_POST["cf"];
        $delete_query = "DELETE FROM clienti WHERE cf='$cf'";

        if ($conn->query($delete_query) === TRUE) {
            echo "Cliente eliminato con successo.";
        } else {
            echo "Errore durante l'eliminazione del cliente: " . $conn->error;
        }
    }

    $query = "SELECT * FROM clienti";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "<h2>Elenco dei clienti</h2>";
        echo "<table border>";
        echo "<tr><th>Codice Fiscale</th><th>Cognome</th><th>Nome</th><th>Indirizzo</th><th>CAP</th><th>Telefono</th><th>Email</th><th>Citta</th><th>SDI</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["cf"] . "</td>";
            echo "<td>" . $row["cognome"] . "</td>";
            echo "<td>" . $row["nome"] . "</td>";
            echo "<td>" . $row["indirizzo"] . "</td>";
            echo "<td>" . $row["cap"] . "</td>";
            echo "<td>" . $row["telefono"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td>" . $row["citta"] . "</td>";
            echo "<td>" . $row["sdi"] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "Nessun risultato trovato.";
    }

    $conn->close();
    ?>

    <h2>Aggiungi nuovo cliente</h2>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        Codice Fiscale: <input type="text" name="cf" required><br>
        Cognome: <input type="text" name="cognome" required><br>
        Nome: <input type="text" name="nome" required><br>
        Indirizzo: <input type="text" name="indirizzo" required><br>
        <h2></h2>
        CAP: <input type="number" name="cap" required><br>
        Citta: <input type="number" name="citta" required><br>
        <h2></h2>
        Telefono: <input type="number" name="telefono" required><br>
        SDI: <input type="number" name="sdi" required><br>
        <h2></h2>
        Email: <input type="text" name="email" required><br>
        <input type="submit" name="aggiungi_cliente" value="Aggiungi Cliente">
    </form>

    <h2>Elimina cliente</h2>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        Codice Fiscale: <input type="text" name="cf" required><br>
        <input type="submit" name="delete_product" value="Elimina Cliente">
    </form>
</body>
</html>
