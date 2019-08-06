<?php
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
    $conn  = new PDO('sqlite:../search.db') or die("cannot open the database");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if(is_null($value1)) {
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
    $conn = null;
    return $result;
}
?>