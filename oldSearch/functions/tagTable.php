
<?php
require_once("resources/config.php");
require_once(RESOURCES_PATH . "/db-connect.php");
$conn = db();
//mysql_select_db("filesearch");
$sql = "SELECT * FROM filesearch.files";
$files = $conn->query($sql);
$html = "<table border='1'>";
$i = 1;
$tagOccurrence = $conn->query("SELECT COUNT(`file_id`) AS `occurrence` FROM filesearch.tagging GROUP BY `file_id` ORDER BY `occurrence` DESC LIMIT 1");
        while($row = $tagOccurrence->fetch_assoc()) {
            $html .= '<tr><th>Dateiname</th><th>Hinzufügen</th>';
            while ($i <= $row['occurrence']){
                $html .= '<th>Tag' . $i . '</th>';
                $i++;
            }  
            $html .= '</tr>';
        }
    while($row = $files->fetch_assoc()) {
        $html .= '<tr><th>'.$row['filenames'].'</th><th><form action="tagger.php" method="post"><input value="+" name="'.$row['file_id'].'" type="submit"></input></form></th>';
        $sql2 = 'SELECT tag FROM filesearch.tagging where file_id = '.$row['file_id'];
        $tags = $conn->query($sql2);
        while($row = $tags->fetch_assoc()) {
            $html .= '<th>'.$row['tag'].'</th>';
        }
        $html .= '</tr>';
    }
$html .= "</table>";
//echo $html;
mysqli_close($conn);
?>