<?php
require_once(__DIR__."/../resources/config.php");
require_once(FUNCTIONS_PATH . "/generalFunctions.php");

if (isset($_POST['action'])) {
    if ($_POST['action']=="neuUser") {
        addUser();
    }
    elseif ($_POST['action']=="aenderePreise") {
        savePreise();
    }
    elseif ($_POST['action']=="delFerien") {
        delFerien($_POST['ferienID']);
    }
    elseif ($_POST['action']=="addFerien") {
        addFerien($_POST['newFerienName'], $_POST['newFerienAnfang'], $_POST['newFerienEnde']);
    }
    
}

function showUsers() {
    $conn = db();
    $sql = "SELECT user_name FROM tbluser";
    $res = mysqli_query($conn, $sql);
    $users = array();
    while ($data = mysqli_fetch_assoc($res)) {
        $users[] = $data['user_name'];
    }
    return $users;
}

function addUser() {

    if ($_SESSION['userid']==1) {
        $username = $_POST['newUserFormName'];
        $pw = password_hash($_POST['newUserFormPW'], PASSWORD_DEFAULT); ;


        $conn = db();
        $stmt = $conn->prepare('INSERT INTO tbluser (user_name, passwort) VALUES (?,?)');
        if ( false===$stmt ) {
            die('prepare() failed: ' . htmlspecialchars($conn->error));
        }
        $rc = $stmt->bind_param("ss", $username, $pw);
        if ( false===$rc ) {
            die('bind_param() failed: ' . htmlspecialchars($stmt->error));
        }
        $rc = $stmt->execute();
        if ( false===$rc ) {
            die('execute() failed: ' . htmlspecialchars($stmt->error));
        }
        else {
            $stmt->close(); 
            header("Location: ../konfiguration.php");
        }
    }
    else {
        echo 'Neue Benutzer können nur vom Benutzer "Admin" erstellt werden';
    }
    
    
}

function showPreise() {
    $conn = db();
    $sql = "SELECT betrag_foerderung, betrag_lerncoaching FROM tblkonfig";
    $res = mysqli_query($conn, $sql);
    $preise = array();
    while ($data = mysqli_fetch_assoc($res)) {
        $preise['betrag_foerderung'] = str_replace(".",",",$data['betrag_foerderung']);
        $preise['betrag_lerncoaching'] = str_replace(".",",",$data['betrag_lerncoaching']);
    }
    return $preise;
}

function savePreise() {

    if ($_SESSION['userid']==1) {

        $lf = str_replace(",",".",$_POST['preisFormLF']);
        $thera = str_replace(",",".",$_POST['preisFormThera']);
        $conn = db();
        $stmt = $conn->prepare('UPDATE tblkonfig SET betrag_foerderung = ?, betrag_lerncoaching = ?');
        if ( false===$stmt ) {
            die('prepare() failed: ' . htmlspecialchars($conn->error));
        }
        $rc = $stmt->bind_param("ss", $lf, $thera);
        if ( false===$rc ) {
            die('bind_param() failed: ' . htmlspecialchars($stmt->error));
        }
        $rc = $stmt->execute();
        if ( false===$rc ) {
            die('execute() failed: ' . htmlspecialchars($stmt->error));
        }
        else {
            $stmt->close(); 
            header("Location: ../konfiguration.php");
        }
        
    }
    else {
        echo 'Unterrichtsbeträge können nur vom Benutzer "Admin" geändert werden';
    }
    
    
}

function getSchulferien($show) {
    $conn = db();
    if ($show=="all") {
        $sql = "SELECT * FROM `tblschulferien` t1 ORDER BY ferienanfang ASC";
    }
    elseif ($show=="upcoming") {
        $sql = "SELECT * FROM `tblschulferien` t1 where ferienende>curdate() ORDER BY ferienanfang ASC";
    }       
 
    $result = mysqli_query($conn, $sql);
    $ferien = array();
    $i = 0;
    while($data = mysqli_fetch_assoc($result)) {
        $ferien[$i]['id'] = $data['ferien_id'];
        $ferien[$i]['name'] = $data['ferienname'];
        $ferien[$i]['ferienanfang'] = $data['ferienanfang'];
        $ferien[$i]['ferienende'] = $data['ferienende'];
        $i++;
    }
    return $ferien;
}

function delFerien($ferienID) {
    $conn = db(); 
   
    $stmt = $conn->prepare('DELETE FROM tblschulferien WHERE ferien_id = ?');
            
    if ( false===$stmt) {
        die('prepare() failed: ' . htmlspecialchars($conn->error));
        $error = $conn->error;
    }

    $rc = $stmt->bind_param("i", $ferienID );

    if ( false===$rc ) {
        die('bind_param() failed: ' . htmlspecialchars($stmt->error));
        $error = $stmt->error;    
    }

    $rc = $stmt->execute();
    if ( false===$rc ) {
        die('execute() failed: ' . htmlspecialchars($stmt->error));
        $error = $stmt->error;        
    }
    else {
    }  
       
    $stmt->close();

   
}

function addFerien($ferienname, $ferienanfang, $ferienende) {
    $conn = db(); 
   
    $stmt = $conn->prepare('INSERT INTO tblschulferien (ferienname, ferienanfang, ferienende) VALUES (?, ?, ?) ');
            
    if ( false===$stmt) {
        die('prepare() failed: ' . htmlspecialchars($conn->error));
        $error = $conn->error;
    }

    $rc = $stmt->bind_param("sss", $ferienname, $ferienanfang, $ferienende );

    if ( false===$rc ) {
        die('bind_param() failed: ' . htmlspecialchars($stmt->error));
        $error = $stmt->error;    
    }

    $rc = $stmt->execute();
    if ( false===$rc ) {
        die('execute() failed: ' . htmlspecialchars($stmt->error));
        $error = $stmt->error;        
    }
    else {
    }  
       
    $stmt->close();

   
}
?>