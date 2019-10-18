<?php
ini_set('error_reporting', E_ALL);
//require_once('paths.php');
require_once('functions/generalFunctions.php');
require_once('functions/tagFunctions.php');
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
    <title>Tags bearbeiten</title>
    <script>
      function showHideInput(id) {
        inputVisibility = document.getElementsByClassName("tagInput" + id)[0];
        if (window.getComputedStyle(inputVisibility).visibility === "hidden"){
          document.getElementsByClassName("tagInput" + id)[0].style.visibility = "visible";
          document.getElementsByClassName("confirmButton" + id)[0].style.visibility = "visible";
          document.getElementsByClassName("tagDropdown" + id)[0].style.visibility = "hidden";
          
        }
      }
      function showHideDropdown(id) {
        inputVisibility = document.getElementsByClassName("tagDropdown" + id)[0];
        if (window.getComputedStyle(inputVisibility).visibility === "hidden"){
          document.getElementsByClassName("tagDropdown" + id)[0].style.visibility = "visible";
          document.getElementsByClassName("confirmButton" + id)[0].style.visibility = "visible";
          document.getElementsByClassName("tagInput" + id)[0].style.visibility = "hidden";
        }
      }
      function editTag(id){
          dropDown = document.getElementsByClassName("tagDropdown" + id)[0];
          if(document.getElementsByClassName("tagInput" + id)[0].value){
          newTag =  document.getElementsByClassName("tagInput" + id)[0].value;
          $.ajax({
            url: 'functions/tagFunctions.php',
            data: {fileID: id, tag: newTag, action: 'add'},
            type: 'post',
            success: function(output) {
              window.location.reload();s
            }
          });
          }else{
            existentTag = dropDown.options[dropDown.selectedIndex].text;
            $.ajax({
            url: 'functions/tagFunctions.php',
            data: {fileID: id, tag: existentTag, action: 'rm'},
            type: 'post',
            success: function(output) {
              window.location.reload();
            }
          });
          }
      }
    </script>
  </head>
  <body>
  <div class="center">
  <table id="tagTable">
    <tr>
      <th>Datei</th>
      <th>bestehende Tags</th>
      <th>Tags bearbeiten</th>
    </tr>
  <?php
    foreach($fileList as $fileID => $file){
      $tagOption = "";
      echo '<tr>
              <td class="fileCell">'.basename($file, '.pdf').'</td>';
      $tagsPerFile = array();
      $db = new SQLite3('search.db');
      $res = $db->query('select tag from files, tags, tagging where files.fileID = tagging.fileID AND tags.tagID = tagging.tagID AND tagging.fileID = '.$fileID.';');
      while ($row = $res->fetchArray()) {
         $tagsPerFile[] = $row['tag'];
      }
    echo '<td>'.implode(", ", $tagsPerFile).'</td>';
    foreach($tagsPerFile as $tag){
      $tagOption .= '<option value="">'.$tag.'</option>';
    }
    echo '<td class="editCell">
    <input class="tagButton addButton" type="button" onclick="showHideInput('.$fileID.')" value="+">
    <input class="tagButton rmButton" type="button" onclick="showHideDropdown('.$fileID.')" value="-">
    <br>
    <br>
    <select class="tagDropdownStyle tagDropdown'.$fileID.'" style="visibility:hidden;">
    <option disabled selected value>Bitte auswählen</option>
    '.$tagOption.'
    </select>
    <input class="tagInput'.$fileID.'" style="visibility:hidden;"  type="text" name="nm">
    <br>
    <br>
    <input class="tagButton confirmButtonStyle confirmButton'.$fileID.'" style="visibility:hidden;" type="button" onclick="editTag('.$fileID.')" value="🗸">
    
    </td>';
    $db->close();
    }
    ?>
    </table>
    </div>
  </body>
</html>