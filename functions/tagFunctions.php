<?php
require_once('generalFunctions.php');
require_once('paths.php');
if(isset($_POST['tag']) AND !empty($_POST['tag'])){ #Fängt den Klick auf + oder - in tagging.php ab um Tags hinzuzufügen oder zu entfernen
    if($_POST['action'] == "add"){
        addTag($_POST['fileID'], $_POST['tag']);
    }else{
        rmTag($_POST['fileID'], $_POST['tag']);
    }
}
function addTag($fileID, $input){ #Erstellt Tags auf Basis des eingegebenen Textes
    $db = new SQLite3('../search.db');
    $res = $db->query('select tagID from tags where tag = "'.$input.'";');
        while ($row = $res->fetchArray()) {
           $tagID = $row['tagID'];
        }
    $db->close();
    $tagExists = existenceCheck('tags', 'tag', $input, NULL); #Überprüft ob Tag bereits existiert
    $tagConExists = existenceCheck('tagging', 'fileID', $fileID, $tagID); #Überprüft ob Tag-Datei-Verbindung bereits besteht.
    if($tagExists AND $tagConExists){
        echo 'Tag ' . $input . ' ist bereits für die Datei angelegt';
    } else if ($tagExists and !$tagConExists){
        //Stellt eine Verbindung zwischen Tags und Dateien her wenn Tag schon besteht.
        $conn  = new PDO('sqlite:../search.db') or die("cannot open the database");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    
        $sqlAddCon = 'INSERT INTO tagging (fileID, tagID) VALUES (:fileID, :tagID);';
            try{
                $stmt = $conn->prepare($sqlAddCon);
                $stmt->bindParam(':fileID', $fileID, PDO::PARAM_INT);
                $stmt->bindParam(':tagID', $tagID, PDO::PARAM_INT);
                $stmt->execute();
            }catch(PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            $conn = NULL;
    } else {
        //Erstellt einen Tag wenn er noch nicht existiert.
        $sqlAddTag = 'INSERT INTO tags (tag) VALUES (:tag);';
        $conn  = new PDO('sqlite:../search.db') or die("cannot open the database");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try{
            $stmt = $conn->prepare($sqlAddTag);
            $stmt->bindParam(':tag', $input, PDO::PARAM_STR);
            $stmt->execute();
        }catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        //Stellt eine Verbindung zwischen Tags und Dateien her.
        if (existenceCheck('tags', 'tag', $input, NULL)){
            $db = new SQLite3('../search.db');
            $res = $db->query('SELECT tagID FROM tags WHERE tag = "'.$input.'";');
            while ($row = $res->fetchArray()) {
               $tagID = $row['tagID'];
            }
            $db->close();
            $sqlAddCon = 'INSERT INTO tagging (fileID, tagID) VALUES (:fileID, :tagID);';
            try{
                $stmt = $conn->prepare($sqlAddCon);
                $stmt->bindParam(':fileID', $fileID, PDO::PARAM_INT);
                $stmt->bindParam(':tagID', $tagID, PDO::PARAM_INT);
                $stmt->execute();
            }catch(PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            $conn = NULL;
        }
    }
}
function rmTag($fileID, $input){ #Löscht die Tag-Datei-Verbindung (Auf das Löschen des Tags wird verzichtet weil er noch an anderer Stelle gebraucht werden könnte).
    $db = new SQLite3('../search.db');
    $res = $db->query('SELECT tagID FROM tags WHERE tag = "'.$input.'";');
    while ($row = $res->fetchArray()) {
       $tagID = $row['tagID'];
    }
    $db->close();
    $sqlAddCon = 'DELETE FROM tagging WHERE fileID = :fileID AND tagID = :tagID;';
    $conn  = new PDO('sqlite:../search.db') or die("cannot open the database");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    
    try{
        $stmt = $conn->prepare($sqlAddCon);
        $stmt->bindParam(':fileID', $fileID, PDO::PARAM_INT);
        $stmt->bindParam(':tagID', $tagID, PDO::PARAM_INT);
        $stmt->execute();
    }catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = NULL;
    echo 'Tag ' . $input . ' wurde erfolgreich gelöscht';
}

function pathTagCreator(){ #Erstellt Tags auf Basis des Pfades in dem sich die Datei befindet.
    $fileList = getFiles();
    $sqlAdd = 'INSERT INTO tags (tag) VALUES (:tag);';
    $conn  = new PDO('sqlite:'.DB_PATH.'') or die("cannot open the database");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    foreach($fileList as $filePath){
            $pathParts = explode('/', dirname($filePath));
            foreach($pathParts as $pathPart){
                if (existenceCheck('tags', 'tag', $pathPart, NULL)){
                   echo 'tag existiert bereits <br>';
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
function pathTagger(){ #Erstellt Tag-Datei-Verbindungen auf Basis des Pfades in dem sich die Datei befindet.
    $fileList = getFiles();
    $tagList = getTags();
    $sqlAdd = 'INSERT INTO tagging (fileID, tagID) VALUES (:fileID, :tagID);';
    $conn  = new PDO('sqlite:'.DB_PATH.'') or die("cannot open the database");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    foreach($fileList as $fileID => $file){
        foreach($tagList as $tagID => $tag){
            if(strpos($file, $tag)){
                if (!existenceCheck('tagging', 'fileID', $fileID, $tagID)){
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