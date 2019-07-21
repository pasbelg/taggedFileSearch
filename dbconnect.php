<?php
$dbAutoCreation = "CREATE TABLE tags(tagID INTEGER PRIMARY KEY, tag VARCHAR);
CREATE TABLE tags(fileID INTEGER PRIMARY KEY, file VARCHAR);
CREATE TABLE tags(fileID INTEGER, tagID VARCHAR);
";
// Pfad zur Datenbank
$db = "search.db";

// Datenbank-Datei erstellen
if (!file_exists($db)) {
 $db = new PDO('sqlite:' . $db);
 $db->exec($dbAutoCreation);
}
else {
 // Verbindung
 $db = new PDO('sqlite:' . $datenbank);
}

// Schreibrechte überprüfen
if (!is_writable($datenbank)) {
 // Schreibrechte setzen
 chmod($datenbank, 0777);
}
?>