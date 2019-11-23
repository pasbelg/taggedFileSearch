<?php
require_once ('paths.php');
function getFiles() { #Gibt eine Liste aller Dateieinträge aus der Datenbank aus.
    $fileList = array();
    $db = new SQLite3(DB_PATH);
    $res = $db->query('SELECT fileID, file FROM files;');
    while ($row = $res->fetchArray()) {
        $fileList[$row['fileID']] = $row['file'];
    }
    $db->close();
    return $fileList;
}
function getTags() { #Gibt eine Liste alle Tags aus der Datenbank aus
    $tagList = array();
    $db = new SQLite3(DB_PATH);
    $res = $db->query('SELECT tagID, tag FROM tags;');
    while ($row = $res->fetchArray()) {
        $fileList[$row['tagID']] = $row['tag'];
    }
    $db->close();
    return $fileList;
}
function existenceCheck($table, $column, $value, $value1) { #Prüft ob Tags oder Dateien schon in der Datenbank existieren.
    $conn = new PDO('sqlite:' . DB_PATH . '') or die("cannot open the database");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (is_null($value1)) { #Wenn keine Tag-Verknüpfung überprüft werden soll tritt dieser Fall ein.
        $sqlCheck = 'select ' . $column . ' from ' . $table . ' where ' . $column . ' = :check;';
        $stmt = $conn->prepare($sqlCheck);
        $stmt->bindParam(':check', $value, PDO::PARAM_STR);
        $stmt->execute();
    } else { #Falls $value1 gesetzt ist soll eine Tag-Verknüpfung untersucht werden.
        $sqlCheck = 'select ' . $column . ' from ' . $table . ' where fileID = :check1 and tagID = :check2;';
        $stmt = $conn->prepare($sqlCheck);
        $stmt->bindParam(':check1', $value, PDO::PARAM_INT);
        $stmt->bindParam(':check2', $value1, PDO::PARAM_INT);
        $stmt->execute();
    }
    if (count($stmt->fetchAll()) > 0) { #Setzt das Ergebnis zu "true" wenn bei der vorigen Datenbankabfrage etwas herausgekommen ist (Dann existiert die Datei, der Tag oder die Tag-Verknüpfung schon)
        $result = true;
    } else {
        $result = false;
    }
    $conn = null;
    return $result;
}
function recreateDB() { #Löscht und erstellt die komplette SQL-Lite Datenbank mit der nötigen Struktur.
    unlink('' . DB_PATH . '');
    $sqlCreateFiles = 'CREATE TABLE files(fileID INTEGER PRIMARY KEY, file VARCHAR NOT NULL);';
    $sqlCreateTags = 'CREATE TABLE tags(tagID INTEGER PRIMARY KEY, tag VARCHAR NOT NULL);';
    $sqlCreateTagging = 'CREATE TABLE IF NOT EXISTS "tagging" ("fileID" int not null, "tagID" int not null);';
    $sqlCreateCommands = array($sqlCreateFiles, $sqlCreateTags, $sqlCreateTagging);
    $conn = new PDO('sqlite:' . DB_PATH . '') or die("cannot open the database");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    foreach ($sqlCreateCommands as $sql) {
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }
    $conn = null;
}
?>