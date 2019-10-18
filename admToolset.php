
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
  function refillDB() {
        $.ajax({
            url: 'functions/fileFunctions.php',
            data: {action: 'refill'},
            type: 'post',
            success: function(output) {
                alert(output);
              window.location.reload();
            }
          });
}
function renewTags() {
        $.ajax({
            url: 'functions/fileFunctions.php',
            data: {action: 'renewTags'},
            type: 'post',
            success: function(output) {
                alert(output);
              window.location.reload();
            }
          });
}
function scanNewFiles() {
        $.ajax({
            url: 'functions/fileFunctions.php',
            data: {action: 'scanNewFiles'},
            type: 'post',
            success: function(output) {
                alert(output);
              window.location.reload();
            }
          });
}
function recreateDB() {
        $.ajax({
            url: 'functions/fileFunctions.php',
            data: {action: 'recreateDB'},
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
        <input type="submit" class="button" name="populate" value="Datenbank neu befüllen" onclick="refillDB()" />
        <input type="submit" class="button" name="populate" value="Tags neu anlegen" onclick="renewTags()" />
        <input type="submit" class="button" name="populate" value="Auf neue Dateien scannen" onclick="scanNewFiles()" />
        <input type="submit" class="button" name="populate" value="Datenbank komplett neu anlegen" onclick="scanNewFiles()" />
        </body>
        </html>