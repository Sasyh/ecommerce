<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserisci Fatture</title>
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

    function getClientiOptions()
    {
        global $conn;
        $query = "SELECT cf, cognome, nome FROM clienti";
        $result = $conn->query($query);

        $options = "";
        while ($row = $result->fetch_assoc()) {
            $options .= '<option value="' . $row['cf'] . '">' . $row['cognome'] . ' ' . $row['nome'] . ' (' . $row['cf'] . ')</option>';
        }

        return $options;
    }

    function getFornitoriOptions()
    {
        global $conn;
        $query = "SELECT piva, nome FROM fornitori";
        $result = $conn->query($query);

        $options = "";
        while ($row = $result->fetch_assoc()) {
            $options .= '<option value="' . $row['piva'] . '">' . $row['nome'] . ' (' . $row['piva'] . ')</option>';
        }

        return $options;
    }

    function getMetodiPagamentoOptions()
    {
        return '<option value="carta_di_credito">Carta di Credito</option>' .
            '<option value="bonifico_bancario">Bonifico Bancario</option>' .
            '<option value="pagamento_sospeso">Pagamento Sospeso</option>' .
            '<option value="contanti">Contanti</option>' .
            '<option value="pos">POS</option>' .
            '<option value="assegno">Assegno</option>';
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["submit"])) {
            $cfAcquirente = $_POST["cf_acquirente"];
            $fornitorePiva = $_POST["fornitore_piva"];
            $idPagamento = $_POST["id_pagamento"];
            $dataFattura = $_POST["data_fattura"];

            $queryFattura = "INSERT INTO fatture (cf_acquirente, fornitore_piva, id_pagamento, data) VALUES ('$cfAcquirente', '$fornitorePiva', '$idPagamento', '$dataFattura')";
            if ($conn->query($queryFattura) === TRUE) {
                $idFattura = $conn->insert_id;

                header("Location: inserisciprodottifatture.php?idFattura=$idFattura");
                exit();
            } else {
                echo "Errore nell'inserimento della fattura: " . $conn->error;
            }
        }
    }
    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Cliente CF: <select name="cf_acquirente" required>
            <?php echo getClientiOptions(); ?>
        </select><br>
        Fornitore P.IVA: <select name="fornitore_piva" required>
            <?php echo getFornitoriOptions(); ?>
        </select><br>
        Metodo di Pagamento: <select name="id_pagamento" required>
            <?php echo getMetodiPagamentoOptions(); ?>
        </select><br>
        Data Fattura: <input type="date" name="data_fattura" required><br>

        <input type="submit" name="submit" value="Conferma Fattura">
    </form>

</body>

</html>
