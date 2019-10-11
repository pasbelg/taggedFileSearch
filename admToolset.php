<?php
require_once("functions/fileFunctions.php");
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
  function populateDB() {
        $.ajax({
            url: 'functions/fileFunctions.php',
            data: {action: 'populate'},
            type: 'post',
            success: function(output) {
                alert(output);
              window.location.reload();
            }
          });
}
</script>

<html>
    <body>
        <input type="submit" class="button" name="populate" value="populate" onclick="populateDB()" />
        </body>
        </html>