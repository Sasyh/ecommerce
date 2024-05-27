<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizza Fatture</title>
    <style>
        .filter-form {
            margin-bottom: 20px;
        }

        .filter-form label {
            margin-right: 10px;
        }
    </style>
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

    // Gestione del form di filtraggio
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $filtroFornitore = $_POST['filtro_fornitore'];
        $filtroCliente = $_POST['filtro_cliente'];
        $filtroPagata = isset($_POST['filtro_pagata']) ? 1 : 0;
        $filtroID = $_POST['filtro_id'];
        $filtroProdotto = $_POST['filtro_prodotto'];
        $dataInizio = $_POST['data_inizio'];
        $dataFine = $_POST['data_fine'];

        $queryFatture = "SELECT DISTINCT fatture.* FROM fatture
                        JOIN dettagli_fattura ON fatture.ID = dettagli_fattura.id_fattura
                        JOIN prodotti ON dettagli_fattura.codice_prodotto = prodotti.codice
                        WHERE 1=1";

        if (!empty($filtroFornitore)) {
            $queryFatture .= " AND fatture.fornitore_piva = '$filtroFornitore'";
        }

        if (!empty($filtroCliente)) {
            $queryFatture .= " AND fatture.cf_acquirente = '$filtroCliente'";
        }

        if (isset($filtroPagata)) {
            $queryFatture .= " AND fatture.pagata = $filtroPagata";
        }

        if (!empty($filtroID)) {
            $queryFatture .= " AND fatture.ID = $filtroID";
        }

        if (!empty($filtroProdotto)) {
            $queryFatture .= " AND prodotti.codice = $filtroProdotto";
        }

        if (!empty($dataInizio) && !empty($dataFine)) {
            $formattedDataInizio = date('Y-m-d', strtotime($dataInizio));
            $formattedDataFine = date('Y-m-d', strtotime($dataFine));

            $queryFatture .= " AND fatture.data BETWEEN '$formattedDataInizio' AND '$formattedDataFine'";
        }

        $resultFatture = $conn->query($queryFatture);
    } else {
        $queryFatture = "SELECT * FROM fatture";
        $resultFatture = $conn->query($queryFatture);
    }
    ?>

    <!-- Form di filtraggio -->
    <form method="post" action="" class="filter-form">
        <label for="filtro_fornitore">Fornitore P.IVA:</label>
        <input type="text" name="filtro_fornitore">

        <label for="filtro_cliente">Cliente CF:</label>
        <input type="text" name="filtro_cliente">

        <label for="filtro_pagata">Pagata:</label>
        <input type="checkbox" name="filtro_pagata">

        <label for="filtro_id">ID Fattura:</label>
        <input type="text" name="filtro_id">

        <label for="filtro_prodotto">ID Prodotto:</label>
        <input type="text" name="filtro_prodotto">

        <label for="data_inizio">Data Inizio:</label>
        <input type="date" name="data_inizio">

        <label for="data_fine">Data Fine:</label>
        <input type="date" name="data_fine">

        <button type="submit">Filtra</button>

        <button type="submit"><a href="visualizzafatture.php">Ripristina</a></button>
    </form>

    <!-- Sezione per visualizzare e gestire le fatture -->
    <h2>Fatture</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Fornitore P.IVA</th>
            <th>Cliente CF</th>
            <th>Data</th>
            <th>Importo Totale</th>
            <th>Pagata</th>
            <th>Azioni</th>
        </tr>

        <?php
        while ($rowFattura = $resultFatture->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $rowFattura['ID'] . "</td>";
            echo "<td>" . $rowFattura['fornitore_piva'] . "</td>";
            echo "<td>" . $rowFattura['cf_acquirente'] . "</td>";
            echo "<td>" . $rowFattura['data'] . "</td>";
            echo "<td>" . $rowFattura['importo_totale'] . "</td>";
            echo "<td>" . ($rowFattura['pagata'] ? 'Sì' : 'No') . "</td>";
            echo "<td><a href='visualizzaprodottifattura.php?id=" . $rowFattura['ID'] . "'>Visualizza</a> | <a href='modificafattura.php?id=" . $rowFattura['ID'] . "'>Modifica</a> | <a href='eliminafattura.php?id=" . $rowFattura['ID'] . "'>Elimina</a></td>";
            echo "</tr>";
        }
        ?>

    </table>
</body>

</html>
