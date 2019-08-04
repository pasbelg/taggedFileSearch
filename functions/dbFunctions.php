<?php
error_reporting(E_ALL);
//error_reporting(0);

//General Functions
function getFiles(){
    $fileList = array();
    $db = new SQLite3('search.db');
    $res = $db->query('SELECT fileID, file FROM files;');
    while ($row = $res->fetchArray()) {
        $fileList[$row['fileID']] = $row['file'];
    }
    $db->close();
    return $fileList;
}
function getTags(){
    $tagList = array();
    $db = new SQLite3('search.db');
    $res = $db->query('SELECT tagID, tag FROM tags;');
    while ($row = $res->fetchArray()) {
        $fileList[$row['tagID']] = $row['tag'];
    }
    $db->close();
    return $fileList;
}

function existenceCheck($table, $column, $value, $value1){
    $conn  = new PDO('sqlite:search.db') or die("cannot open the database");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if(is_null($value2)) {
        $sqlCheck = 'select '.$column.' from '.$table.' where '.$column.' = :check;';
        $stmt = $conn->prepare($sqlCheck);
        $stmt->bindParam(':check', $value, PDO::PARAM_STR);
        $stmt->execute();

    } else {
        $sqlCheck = 'select '.$column.' from '.$table.' where fileID = :check1 and tagID = :check2;';
        $stmt = $conn->prepare($sqlCheck);
        $stmt->bindParam(':check1', $value, PDO::PARAM_INT);
        $stmt->bindParam(':check2', $value1, PDO::PARAM_INT);
        $stmt->execute();
    }
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



function pathTagCreator(){
    
    $fileList = getFiles();
    //print("<pre>".print_r($fileList,true)."</pre>");
    $sqlAdd = 'INSERT INTO tags (tag) VALUES (:tag);';
    $conn  = new PDO('sqlite:search.db') or die("cannot open the database");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    foreach($fileList as $filePath){
            $pathParts = explode('/', dirname($filePath));
            foreach($pathParts as $pathPart){
                
                if (existenceCheck('tags', 'tag', $pathPart, NULL)){
                   echo 'tag existiert bereits <br>';
                    /* if (!in_array($pathPart, $array)){
                            $array[] = $pathPart; 
                        }*/
                } else {
                    if ($pathPart !== 'files'){
                        try{
                            $stmt = $conn->prepare($sqlAdd);
                            $stmt->bindParam(':tag', $pathPart, PDO::PARAM_STR);
                            $stmt->execute();
                        }catch(PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }
                        echo $pathPart . ' wurde in die Datenbank aufgenommen<br>';
                    }
            }
        }
    }
}


//Bringt eingentlich gar nichts weil die Suche den Kompletten Sting in files durchsucht und dort auch das drinsteht was in den Tags steht... 
function pathTagger(){
    $fileList = getFiles();
    $tagList = getTags();

    $sqlAdd = 'INSERT INTO tagging (fileID, tagID) VALUES (:fileID, :tagID);';
    $conn  = new PDO('sqlite:search.db') or die("cannot open the database");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    foreach($fileList as $fileID => $file){
        foreach($tagList as $tagID => $tag){
            if(strpos($file, $tag) AND !existenceCheck('tagging', 'fileID', $fileID, $tagID)){
                //echo '<p> ' . $fileID .': ' . $file . '<br> mit der ID ' . $fileID .' <br>enthält den Tag ' . $tag . ' mit der ID ' . $tagID . '<p>';
                echo 'tag muss noch verknüpft werden<br>';
                try{
                    $stmt = $conn->prepare($sqlAdd);
                    $stmt->bindParam(':fileID', $fileID, PDO::PARAM_INT);
                    $stmt->bindParam(':tagID', $tagID, PDO::PARAM_INT);
                    $stmt->execute();
                }catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            } else {
                echo 'tag ist schon verknüpft <br>';
            }
        }
    }
}   


?>