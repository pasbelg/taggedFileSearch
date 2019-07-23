<?php
require_once("../resources/config.php");
require_once(RESOURCES_PATH . "/db-connect.php");
$conn = db();

if(isset($_REQUEST['q'])){
    $sql2 = "SELECT files.filenames FROM filesearch.files JOIN filesearch.tagging ON files.file_id = tagging.file_id WHERE tagging.tag LIKE ? limit 25";
    $sql = "SELECT filenames FROM filesearch.files WHERE filenames LIKE ? limit 25";
    $res_filename = "";
    $res_tag = "";
    if($stmt = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($stmt, "s", $param_term);
        $param_term = '%' . $_REQUEST['q'] . '%';
        
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    // strip tags to avoid breaking any html
                    $displayName = strip_tags($row['filenames']);
                    if (strlen($displayName) > 40) {

                        // truncate string
                        $stringCut = substr($displayName, 0, 40);
                        $endPoint = strrpos($stringCut, ' ');

                        //if the string doesn't contain any space then it will cut without word basis.
                        $displayName = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                        $displayName .= '...';
                    }                   
                    $res_filename .= '<a href="file/'.$row['filenames'].'" class="list-group-item list-group-item-action">'.$displayName.'</a>';
                }
            } else{
            }
        } else{
            
            echo "ERROR: Could not execute $sql. " . mysqli_error($conn);
        }
    }
    

if($stmt2 = mysqli_prepare($conn, $sql2)){
    mysqli_stmt_bind_param($stmt2, "s", $param_term2);
    $param_term2 = '%' . $_REQUEST['q'] . '%';
    if(mysqli_stmt_execute($stmt2)){
        $result2 = mysqli_stmt_get_result($stmt2);
        if(mysqli_num_rows($result2) > 0){
            while($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)){
                // strip tags to avoid breaking any html
                $displayName = strip_tags($row2['filenames']);
                if (strlen($displayName) > 40) {
                    // truncate string
                    $stringCut = substr($displayName, 0, 40);
                    $endPoint = strrpos($stringCut, ' ');

                    //if the string doesn't contain any space then it will cut without word basis.
                    $displayName = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                    $displayName .= '...';
                    }
                    if(strpos($res_filename, $row2['filenames']) == false AND strpos($res_tag, $row2['filenames']) == false){
                        $res_tag .= '<a href="file/'.$row2['filenames'].'" class="list-group-item list-group-item-action">'.$displayName.'</a>';
                    }
                }
            } else{
            }
        } else{
            echo "ERROR: Could not execute $sql2. " . mysqli_error($conn);
        }
    }
    
    if($res_filename == "" and $res_tag == "") {
        echo "<a href='#' class='list-group-item list-group-item-action'>Leider nichts gefunden.</a>";
    }
    else {
        $res = $res_filename.' '.$res_tag;
        echo $res;
    }
}   
 //mysqli_stmt_close($stmt2);
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>