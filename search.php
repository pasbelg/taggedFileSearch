<?php
//error_reporting(E_ALL);
$input = '%'.$_REQUEST['searchPhrase'].'%';
//$sql = 'select file from files a, tags b, tagging c where c.tagID = b.tagID AND a.fileID = c.fileID AND a.file like :input;';
$sqlFile = 'select file from files where file like :input;';
$sqlTag = 'select file from files a, tags b, tagging c where c.tagID = b.tagID AND a.fileID = c.fileID AND b.tag like :input;';
$dir = 'sqlite:search.db';
$html = '';

if (isset($_REQUEST['searchPhrase'])) {
    $conn  = new PDO($dir) or die("cannot open the database");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($sqlFile);
    $stmt->bindParam(':input', $input, PDO::PARAM_STR);
    $stmt->execute();
    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
    foreach($stmt->fetchAll() as $v) { 
        $result = implode($v);
        if (!strpos($html, $result)){
            $html .= '<a title="'.basename($result, '.pdf').'" target="_blank" href="'.$result.'"><label>'.basename($result, '.pdf').'</label></a>';
        }
        
    }
}
if (isset($_REQUEST['searchPhrase'])) {
    $conn  = new PDO($dir) or die("cannot open the database");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($sqlTag);
    $stmt->bindParam(':input', $input, PDO::PARAM_STR);
    $stmt->execute();
    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
    foreach($stmt->fetchAll() as $v) { 
        $result = implode($v);
        if (!strpos($html, $result)){
            $html .= '<a title="'.basename($result, '.pdf').'" target="_blank" href="'.$result.'"><label>'.basename($result, '.pdf').'</label></a>';
        }
}
}

if($html == '') {
    echo "<p>Leider nichts gefunden.</p>";
}
else {
    echo $html;
}
?>


<?php
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
//$conn = null; //close DB Connection

//https://werner-zenk.de/scripte/sqlite_datenbank.php

//echo '<div style="width:100px; height100px; background:black; color:white;">'.$input.'</div>';

//print_r();*/
?>