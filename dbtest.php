<?php
/*
include("dbconnect.php");
$sql = "SELECT * FROM tagging;";

$insert = $db -> prepare($sql);
$insert->exceute();
*/



$dir = 'sqlite:search.db';
$dbh  = new PDO($dir) or die("cannot open the database");
$query =  "SELECT * FROM combo_calcs WHERE options='easy'";
foreach ($dbh->query($query) as $row)
{
    echo $row[0];
}
$dbh = null; //This is how you close a PDO connection
?>