<?php
error_reporting(0);

function getDirContents($dir){
    $servername ='localhost';
    $username ='root';
    $password='';
    $database='filesearch';
$conn =  mysqli_connect($servername, $username, $password, $database);
    $files = scandir($dir);
    foreach($files as $key => $value){
        $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
        if(!is_dir($path) && strpos("$value","pdf")) {
            $sql = 'INSERT INTO filesearch.files (filenames) Select "'.basename($path).'" Where not exists(select * from filesearch.files where filenames="'.basename($path).'");';
        } else if($value != "." && $value != "..") {
            getDirContents($path);
            if(strpos($value,"pdf")) {
                $sql = 'INSERT INTO filesearch.files (filenames) Select "'.basename($path).'" Where not exists(select * from filesearch.files where filenames="'.basename($path).'");';
            }
        }
        if(mysqli_query($conn, $sql)){
            echo "Records inserted successfully.";
        } else{
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
        }
}
}
getDirContents('files');
?>