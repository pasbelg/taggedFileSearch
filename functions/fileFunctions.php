<?php
if (isset($_REQUEST['action'])) {
    switch ($_POST['action']) {
        case 'populate':
            scanFilesAndAdd("files/");
            break;
    }
}


function addFilesToDB($file){
    $sqlAdd = 'INSERT INTO files (file) VALUEs (:filePath);';
    $conn  = new PDO('sqlite:search.db') or die("cannot open the database");
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
}
function scanFilesAndAdd($dir){
    $files = scandir($dir);
    
    foreach($files as $value){
        $path = $dir.DIRECTORY_SEPARATOR.$value;
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
?>