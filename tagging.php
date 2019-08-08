<?php

require_once('functions/generalFunctions.php');
require_once('functions/tagFunctions.php');
//pathTagCreator();
//print("<pre>".print_r(getFiles(),true)."</pre>");
//print("<pre>".print_r(getTags(),true)."</pre>");
//pathTagger();
//scanFilesAndAdd('files')

//var_dump(existenceCheck('files', 'file', 'files/individuelle Lernförderung/Mathe/M_098_I_LF_Funktionen_Linear_Parabeln.pdf'));
$fileList = getFiles();
$tagList = getTags();
?>
<!DOCTYPE html>
<html lang="de">
  <head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="style.css">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tags erstellen</title>
    <script>
      function showHideInput(id) {
        inputVisibility = document.getElementsByClassName("tagInput" + id)[0];
        if (window.getComputedStyle(inputVisibility).visibility === "hidden"){
          document.getElementsByClassName("tagInput" + id)[0].style.visibility = "visible";
          document.getElementsByClassName("addButton")[0].style.visibility = "hidden";
          document.getElementsByClassName("confirmButton" + id)[0].style.visibility = "visible";
        }
      }
      function addTag(id){
        input = document.getElementsByClassName("tagInput" + id)[0].value;
        $.ajax({
          url: 'functions/tagFunctions.php',
          data: {fileID: id, action: input},
         type: 'post',
         success: function(output) {
            window.location.reload();
          }
        });
      }
    </script>
  </head>
  <body>
  <div class="center">
  <table border="1" id="tagTable">
    <tr>
      <th>Datei</th>
      <th>bestehende Tags</th>
      <th>Tag hinzufügen</th>
    </tr>
  <?php
    foreach($fileList as $fileID => $file){
      echo '<tr>
              <td class="fileCell">'.basename($file, '.pdf').'</td>';
      $tagsPerFile = array();
      $db = new SQLite3('search.db');
      $res = $db->query('select tag from files, tags, tagging where files.fileID = tagging.fileID AND tags.tagID = tagging.tagID AND tagging.fileID = '.$fileID.';');
      while ($row = $res->fetchArray()) {
         $tagsPerFile[] = $row['tag'];
      }
    echo '<td>'.implode(", ", $tagsPerFile).'</td>';
    echo '<td class="editCell">
    <input class="addButton" type="button" onclick="showHideInput('.$fileID.')" value="+">
    <input class="tagInput tagInput'.$fileID.'" style="visibility:hidden;"  type="text" name="nm" >
    <input class="confirmButton confirmButton'.$fileID.'" style="visibility:hidden;" type="button" onclick="addTag('.$fileID.')" >  
    </td>';
    $db->close();
    }
    ?>
    </table>
    </div>
  </body>
</html>