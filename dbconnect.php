<?php
/*
$dbAutoCreation = "CREATE TABLE tags(tagID INTEGER PRIMARY KEY, tag VARCHAR);
CREATE TABLE tags(fileID INTEGER PRIMARY KEY, file VARCHAR);
CREATE TABLE tags(fileID INTEGER, tagID VARCHAR);
";
*/
// Pfad zur Datenbank
$dir = 'sqlite:./search.db';
$dbh  = new PDO($dir) or die("cannot open the database");
$query =  "SELECT * FROM combo_calcs WHERE options='easy'";
foreach ($dbh->query($query) as $row)
{
    echo $row[0];
}
$dbh = null; //This is how you close a PDO connection
//$db = "search.db";

/* Datenbank-Datei erstellen
if (!file_exists($db)) {
 $db = new PDO('sqlite:' . $db);
 $db->exec($dbAutoCreation);
}
else {
}
*/
 // Verbindung
//$db = new PDO('sqlite:' . $datenbank);
 

/*
// Schreibrechte überprüfen
if (!is_writable($datenbank)) {
 // Schreibrechte setzen
 chmod($datenbank, 0777);
}
*/
?>