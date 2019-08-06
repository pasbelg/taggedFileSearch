<?php
error_reporting(E_ALL);
require_once('generalFunctions.php');
if(isset($_POST['action'])){
    addTag($_POST['fileID'], $_POST['action']);
}

function addTag($fileID, $input){
    $tagExists = existenceCheck('tags', 'tag', $input, NULL);
    if($tagExists){
        $tagsPerFile = array();
        /* Geht nicht, kA warum -.-
        $db = new SQLite3('../search.db');
        $res = $db->query('select tagID from tags where tag = '.$input.';');
        while ($row = $res->fetchArray()) {
           $tagsPerFile[] = $row['tag'];
        }
        */
        echo 'Tag ' . $input . ' existiert bereits und hat die ID' . $tagID;
    } else {
        echo 'Tag ' . $input . ' wird für die Datei mit der ID ' . $fileID . ' hinzugefügt';
        /*
        $sqlAddTag = 'INSERT INTO tagging (fileID, tagID) VALUES (:fileID, :tagID);';
        $conn  = new PDO('sqlite:search.db') or die("cannot open the database");
        $sqlAddTagCon = 'INSERT INTO tagging (fileID, tagID) VALUES (:fileID, :tagID);';
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    */
    }

    //$tagConExists = existenceCheck('tagging', 'fileID', $fileID, $tagID); //Später wenn tagID abgerufen wurde
}

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
    $conn=null;
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
            if(strpos($file, $tag)){
                if (!existenceCheck('tagging', 'fileID', $fileID, $tagID)){
                    //echo 'tag muss noch verknüpft werden<br>';
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
            } else {
                echo 'tag ist schon verknüpft <br>';
            }
        }
    }
    $conn=null;
} 
?>