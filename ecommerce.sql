-- CLIENTI
DROP TABLE IF EXISTS clienti;
CREATE TABLE clienti (
  cf VARCHAR(16) PRIMARY KEY,
  cognome VARCHAR(50),
  nome VARCHAR(50),
  indirizzo VARCHAR(100),
  cap INT,
  telefono VARCHAR(15),
  email VARCHAR(100),
  citta VARCHAR(50),
  sdi VARCHAR(7)
);

-- FORNITORI
DROP TABLE IF EXISTS fornitori;
CREATE TABLE fornitori (
  piva VARCHAR(11) PRIMARY KEY,
  sdi VARCHAR(7),
  nome VARCHAR(50),
  indirizzo VARCHAR(100),
  telefono VARCHAR(15),
  email VARCHAR(100),
  cap INT,
  citta VARCHAR(50)
);

-- PRODOTTI
DROP TABLE IF EXISTS prodotti;
CREATE TABLE prodotti (
  codice INT PRIMARY KEY,
  marca VARCHAR(50),
  modello VARCHAR(50),
  descrizione VARCHAR(255),
  prezzo_iniziale INT,
  prezzo_vendita INT,
  prezzo_iniziale_iva INT,
  prezzo_vendita_iva INT,  
  fornitore_piva VARCHAR(11),
  iva_acquisto INT DEFAULT 0,
  iva_vendita INT DEFAULT 22
);

-- FATTURE
DROP TABLE IF EXISTS fatture;
CREATE TABLE fatture (
  ID INT PRIMARY KEY AUTO_INCREMENT,
  fornitore_piva VARCHAR(11),
  cf_acquirente VARCHAR(16),
  data DATE,
  importo_totale INT,
  pagata boolean DEFAULT false
);

CREATE TABLE dettagli_fattura (
  ID INT PRIMARY KEY AUTO_INCREMENT,
  id_fattura INT,
  codice_prodotto INT,
  quantita INT
);

-- GESTIONE VENDITE GIORNALIERE
DROP TABLE IF EXISTS gestione_vendite_giornaliere;
CREATE TABLE gestione_vendite_giornaliere (
  ID INT PRIMARY KEY AUTO_INCREMENT,
  totale_vendite INT,
  note TEXT
);

-- VINCOLI ESTERNI
ALTER TABLE fatture ADD FOREIGN KEY (fornitore_piva) REFERENCES fornitori(piva);
ALTER TABLE fatture ADD FOREIGN KEY (cf_acquirente) REFERENCES clienti(cf);

ALTER TABLE prodotti ADD FOREIGN KEY (fornitore_piva) REFERENCES fornitori(piva);

ALTER TABLE dettagli_fattura ADD FOREIGN KEY (id_fattura) REFERENCES fatture(ID);
ALTER TABLE dettagli_fattura ADD FOREIGN KEY (codice_prodotto) REFERENCES prodotti(codice);




-- Inserisci dati casuali nella tabella "clienti"
INSERT INTO clienti (cf, cognome, nome, indirizzo, cap, telefono, email, citta, sdi)
VALUES
  ('GQRSLH40M41D921V', 'Rossi', 'Mario', 'Via Roma 123', 12345, '1234567890', 'mario.rossi@email.com', 'Roma', 'SDI123'),
  ('LLPO2H40M41D921V', 'Bianchi', 'Anna', 'Via Milano 456', 67890, '0987654321', 'anna.bianchi@email.com', 'Milano', 'SDI456');

-- Inserisci dati casuali nella tabella "fornitori"
INSERT INTO fornitori (piva, sdi, nome, indirizzo, telefono, email)
VALUES
  ('41739060501', 'SDI789', 'Fornitore1', 'Via Napoli 789', '9876543210', 'fornitore1@email.com'),
  ('96235950423', 'SDI012', 'Fornitore2', 'Via Torino 345', '5432109876', 'fornitore2@email.com');

-- Inserisci dati casuali nella tabella "prodotti"
INSERT INTO prodotti (codice, marca, modello, descrizione, prezzo_iniziale, prezzo_vendita, fornitore_piva, iva_acquisto, iva_vendita)
VALUES
  (1, 'Marca1', 'ModelloA', 'Descrizione prodotto A', 100, 120, '41739060501', 0, 22),
  (2, 'Marca2', 'ModelloB', 'Descrizione prodotto B', 150, 180, '96235950423', 0, 22);

