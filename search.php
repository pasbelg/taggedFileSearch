<?php
//error_reporting(E_ALL);
$input = '%'.$_REQUEST['searchPhrase'].'%';
//$sql = 'select file from files a, tags b, tagging c where c.tagID = b.tagID AND a.fileID = c.fileID AND a.file like :input;';
$sql = 'select file from files where file like :input;';
//$sql = 'select * from files where fileID = :input;';
$dir = 'sqlite:search.db';
$html = '';

if (isset($_REQUEST['searchPhrase'])) {
    $conn  = new PDO($dir) or die("cannot open the database");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':input', $input, PDO::PARAM_STR);
    $stmt->execute();
    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
    foreach($stmt->fetchAll() as $v) { 
        $result = implode($v);
        $html .= '<p><a href="'.dirname($result).'">'.basename($result).'</a><br><br></p>';
    }
}


if($html == '') {
    echo "<p>Leider nichts gefunden.</p>";
}
else {
    echo $html;
}




/*

catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;

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