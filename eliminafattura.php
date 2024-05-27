<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    $idFattura = $_GET["id"];

    // Elimina dettagli dalla tabella dettagli_fattura
    $queryEliminaDettagli = "DELETE FROM dettagli_fattura WHERE id_fattura = $idFattura";
    if ($conn->query($queryEliminaDettagli) === TRUE) {
        // Elimina dalla tabella fatture
        $queryEliminaFattura = "DELETE FROM fatture WHERE ID = $idFattura";
        if ($conn->query($queryEliminaFattura) === TRUE) {
            echo "Fattura eliminata con successo.";
        } else {
            echo "Errore nell'eliminazione della fattura dalla tabella fatture: " . $conn->error;
        }
    } else {
        echo "Errore nell'eliminazione dei dettagli dalla tabella dettagli_fattura: " . $conn->error;
    }
} else {
    echo "Errore nell'eliminazione della fattura. Parametro ID non fornito.";
}

$conn->close();
?>
