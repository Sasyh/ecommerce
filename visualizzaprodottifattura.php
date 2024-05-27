<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica Fattura</title>
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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Gestisci l'aggiornamento della fattura e dei dettagli
        $fatturaID = $_POST['fattura_id'];

        // Esegui l'aggiornamento degli attributi della fattura
        // Modifica questa parte per aggiornare gli attributi necessari
        $data = $_POST['data'];  // Ad esempio, se hai un campo 'data' nella tua tabella
        $importoTotale = $_POST['importo_totale'];
        $pagata = isset($_POST['pagata']) ? 1 : 0;

        $updateFatturaQuery = "UPDATE fatture SET data='$data', importo_totale=$importoTotale, pagata=$pagata WHERE ID=$fatturaID";
        $conn->query($updateFatturaQuery);
    }

    // Ottieni i dettagli della fattura
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $fatturaID = $_GET['id'];
        $queryFattura = "SELECT * FROM fatture WHERE ID = $fatturaID";
        $resultFattura = $conn->query($queryFattura);

        if ($resultFattura->num_rows > 0) {
            $rowFattura = $resultFattura->fetch_assoc();

            // Ottieni i dettagli dei prodotti associati a questa fattura
            $queryDettagliFattura = "SELECT * FROM dettagli_fattura WHERE id_fattura = $fatturaID";
            $resultDettagliFattura = $conn->query($queryDettagliFattura);

            ?>
            <h3>Dettagli Fattura</h3>
                <table border="1">
                    <tr>
                        <th>ID Prodotto</th>
                        <th>Marca</th>
                        <th>Modello</th>
                        <th>Descrizione</th>
                        <th>Prezzo Iniziale</th>
                        <th>Prezzo Vendita</th>
                        <th>Fornitore P.IVA</th>
                        <th>IVA Acquisto</th>
                        <th>IVA Vendita</th>
                        <th>Quantità</th>
                        <th>Azioni</th>
                    </tr>

                    <?php
                    while ($rowDettaglio = $resultDettagliFattura->fetch_assoc()) {
                        $codiceProdotto = $rowDettaglio['codice_prodotto'];

                        // Ottieni informazioni sul prodotto associato al dettaglio
                        $queryProdotto = "SELECT * FROM prodotti WHERE codice = $codiceProdotto";
                        $resultProdotto = $conn->query($queryProdotto);

                        if ($resultProdotto->num_rows > 0) {
                            $rowProdotto = $resultProdotto->fetch_assoc();

                            echo "<tr>";
                            echo "<td>" . $rowProdotto['codice'] . "</td>";
                            echo "<td>" . $rowProdotto['marca'] . "</td>";
                            echo "<td>" . $rowProdotto['modello'] . "</td>";
                            echo "<td>" . $rowProdotto['descrizione'] . "</td>";
                            echo "<td>" . $rowProdotto['prezzo_iniziale'] . "</td>";
                            echo "<td>" . $rowProdotto['prezzo_vendita'] . "</td>";
                            echo "<td>" . $rowProdotto['fornitore_piva'] . "</td>";
                            echo "<td>" . $rowProdotto['iva_acquisto'] . "</td>";
                            echo "<td>" . $rowProdotto['iva_vendita'] . "</td>";
                            echo "<td>" . $rowDettaglio['quantita'] . "</td>";
                            echo "<td><button type='submit' name='elimina_prodotto' value='1'>Elimina</button></td>";
                            echo "<input type='hidden' name='dettaglio_id' value='" . $rowDettaglio['ID'] . "'>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </table>
                </form>
        <?php
        } else {
            echo "Fattura non trovata.";
        }
    } else {
        echo "ID fattura non valido.";
    }
    ?>
</body>

</html>