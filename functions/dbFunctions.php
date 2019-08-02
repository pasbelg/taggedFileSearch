<?php
error_reporting(E_ALL);
//error_reporting(0);

//General Functions
function existenceCheck($table, $column, $value){
    $sqlCheck = 'select '.$table.' from '.$column.' where file = :check;';
    $conn  = new PDO('sqlite:search.db') or die("cannot open the database");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($sqlCheck);
    $stmt->bindParam(':check', $value, PDO::PARAM_STR);
    $stmt->execute();
    if (count($stmt->fetchAll()) > 0) { 
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}


//File Functions
function addFilesToDB($file){
    $sqlAdd = 'INSERT INTO files (file) VALUEs (:filePath);';
    $conn  = new PDO('sqlite:search.db') or die("cannot open the database");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (existenceCheck('file', 'files', $file)) { 
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
}
function scanFilesAndAdd($dir){
    $files = scandir($dir);
    foreach($files as $value){
        $path = $dir.DIRECTORY_SEPARATOR.$value;
        //echo $path;
        if(!is_dir($path) && strpos("$value","pdf")) {
            addFilesToDB($path);
        } else if($value != "." && $value != "..") {
            scanAndAdd($path);
            if(strpos($value,"pdf")) {
                addFilesToDB($path);
            }
        }
    }
}

//Tag Functions
function pathAnalyser($file){
    

}

?>