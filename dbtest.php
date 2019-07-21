<?php
error_reporting(E_ALL);

$dir = 'sqlite:search.db';
$dbh  = new PDO($dir) or die("cannot open the database");
$sql = "SELECT file FROM files;";
foreach ($dbh->query($sql) as $row)
{
    echo $row[0];
}
$dbh = null; //This is how you close a PDO connection

//https://werner-zenk.de/scripte/sqlite_datenbank.php

?>