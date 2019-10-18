<?php
require_once('generalFunctions.php');
require_once('tagFunctions.php');
if (isset($_REQUEST['action'])) {
    switch ($_POST['action']) {
        case 'refill':
            $dimensions = array('tags', 'files', 'tagging');
            clearDB($dimensions);
            scanFilesAndAdd('../files');
            pathTagCreator();
            pathTagger();
        break;
        case 'renewTags':
            $dimensions = array('tags', 'tagging');
            clearDB($dimensions);
            pathTagCreator();
            pathTagger();
        break;
        case 'scanNewFiles':
            scanFilesAndAdd('../files');
            pathTagCreator();
            pathTagger();
        break;
        case 'recreateDB':
            recreateDB();
            $dimensions = array('tags', 'files', 'tagging');
            clearDB($dimensions);
            scanFilesAndAdd('../files');
            pathTagCreator();
            pathTagger();
        break;
    }
}


function addFilesToDB($file){
    $sqlAdd = 'INSERT INTO files (file) VALUES (:filePath);';
    $conn  = new PDO('sqlite:'.DB_PATH.'') or die("cannot open the database");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (existenceCheck('files', 'file', $file, NULL)) { 
        echo $file . " exisiert schon in der Datenbank<br>";
    } else{
        try{
            $stmt = $conn->prepare($sqlAdd);
            $stmt->bindParam(':filePath', $file, PDO::PARAM_STR);
            $stmt->execute();
        }catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    $conn = null;
}
function scanFilesAndAdd($dir){
    $files = scandir($dir);
    foreach($files as $value){
        $path = $dir.DIRECTORY_SEPARATOR.$value;
        if(!is_dir($path) && strpos("$value","pdf")) {
            addFilesToDB($path);
        } else if($value != "." && $value != "..") {
            scanFilesAndAdd($path);
            if(strpos($value,"pdf")) {
                addFilesToDB($path);
            }
        }
    }
}

function clearDB(array $dimensions){
    $conn  = new PDO('sqlite:'.DB_PATH.'') or die("cannot open the database");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $tables = $dimensions;
    foreach($tables as $tableName){
        $sqlDel = 'DELETE FROM '.$tableName.';';
        try{
            $stmt = $conn->prepare($sqlDel);
            $stmt->execute();
        }catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    $conn = null;
}
?>