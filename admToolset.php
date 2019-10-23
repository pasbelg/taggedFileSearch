<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Datenbankverwaltung</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script>
        function refillDB() {
            secCheck = confirm('Durch diese Funktion wird die Suche auf Werkseinstellungen zurückgesetzt (Alle manuellen Änderungen gehen verloren). Sind sie Sicher?')
            if (secCheck) {
                document.getElementById("loadingScreen").style.display = "block";
                $.ajax({
                    url: 'functions/fileFunctions.php',
                    data: {
                        action: 'refill'
                    },
                    type: 'post',
                    success: function(output) {
                        window.location.reload();
                    }
                });
            }

        }

        function renewTags() {
            secCheck = confirm('Es werden alle manuell angelegten Tags gelöscht. Sind sie Sicher?')
            if (secCheck) {
                document.getElementById("loadingScreen").style.display = "block";
                $.ajax({
                    url: 'functions/fileFunctions.php',
                    data: {
                        action: 'renewTags'
                    },
                    type: 'post',
                    success: function(output) {
                        window.location.reload();
                    }
                });
            }

        }

        function scanNewFiles() {
                document.getElementById("loadingScreen").style.display = "block";
                $.ajax({
                    url: 'functions/fileFunctions.php',
                    data: {
                        action: 'scanNewFiles'
                    },
                    type: 'post',
                    success: function(output) {
                        window.location.reload();
                    }
                });
        }

        function recreateDB() {
          secCheck = confirm('Durch diese Funktion wird die Suche auf Werkseinstellungen zurückgesetzt (Alle manuellen Änderungen gehen verloren). Sind sie Sicher?')
            if (secCheck) {
                document.getElementById("loadingScreen").style.display = "block";
                $.ajax({
                    url: 'functions/fileFunctions.php',
                    data: {
                        action: 'recreateDB'
                    },
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
        <div id="loadingScreen" style="display:none;">
            <div id="loader"></div>
        </div>
        <div id="admButtons">
          <input type="submit" class="button" name="populate" value="Datenbank neu befüllen" onclick="refillDB()" />
          <input type="submit" class="button" name="populate" value="Tags neu anlegen" onclick="renewTags()" />
          <input type="submit" class="button" name="populate" value="Auf neue Dateien scannen" onclick="scanNewFiles()" />
          <input type="submit" class="button" name="populate" value="Datenbank neu anlegen" onclick="recreateDB()" />
        </div>
    </body>

</html>