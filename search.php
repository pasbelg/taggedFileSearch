<?php
error_reporting(E_ALL);
//$input = $_REQUEST['searchPhrase'];
$test = 'files';
$sql = 'select file from files a, tags b, tagging c where c.tagID = b.tagID AND a.fileID = c.fileID AND b.tag like "ma";';
//$sql = 'select * from :input;';

$dir = 'sqlite:search.db';

try {
    $conn  = new PDO($dir) or die("cannot open the database");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':input', $test, PDO::PARAM_STR);
    $stmt->execute();
    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
    foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) { 
        echo $v;
    }
}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
/*
foreach ($dbh->query($sql) as $row)
{
    echo $row[0];
}
*/
$conn = null; //close DB Connection

//https://werner-zenk.de/scripte/sqlite_datenbank.php

//echo '<div style="width:100px; height100px; background:black; color:white;">'.$input.'</div>';

//print_r();
?>