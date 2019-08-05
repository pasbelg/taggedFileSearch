<?php
require('functions/dbFunctions.php');

//pathTagCreator();
//print("<pre>".print_r(getFiles(),true)."</pre>");
//print("<pre>".print_r(getTags(),true)."</pre>");
//pathTagger();
//scanFilesAndAdd('files')

//var_dump(existenceCheck('files', 'file', 'files/individuelle Lernförderung/Mathe/M_098_I_LF_Funktionen_Linear_Parabeln.pdf'));
?>
<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tags erstellen</title>
    <?php
    $fileList = getFiles();
    $tagList = getTags();

    foreach($fileList as $fileID => $file){
      $tagsPerFile = array();
      $db = new SQLite3('search.db');
      
      $res = $db->query('select tag from files, tags, tagging where files.fileID = tagging.fileID AND tags.tagID = tagging.tagID AND tagging.fileID = '.$fileID.';');
      while ($row = $res->fetchArray()) {
        echo $fileID;
         $tagsPerFile[$row['tag']] = $row['tag'];
      }
    $db->close();
    }
    print("<pre>".print_r($tagsPerFile,true)."</pre>");
    ?>
  </head>
  <body>

  </body>
</html>