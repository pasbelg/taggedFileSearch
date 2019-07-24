<?php
error_reporting(E_ALL);
//error_reporting(0);


function addToDB($file){
    $filename = basename($file);
    $sqlCheck = 'select file from files where file = :check;';
    $sqlAdd = 'INSERT INTO files (file) VALUEs (:filePath);';
    
    $conn  = new PDO('sqlite:search.db') or die("cannot open the database");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($sqlCheck);
    $stmt->bindParam(':check', $file, PDO::PARAM_STR);
    $stmt->execute();
    // set the resulting array to associative
    if (count($stmt->fetchAll()) > 0) { 
        echo "exisiiert schon";
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
function scanAndAdd($dir){
    $files = scandir($dir);
    foreach($files as $value){
        $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
        if(!is_dir($path) && strpos("$value","pdf")) {
            addToDB($path);
        } else if($value != "." && $value != "..") {
            scanAndAdd($path);
            if(strpos($value,"pdf")) {
                addToDB($path);
            }
        }
    }
}
scanAndAdd('files')
?>