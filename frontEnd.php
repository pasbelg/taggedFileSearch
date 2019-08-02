<!DOCTYPE html>
<html>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<meta name="viewport" content="width=device-width, initial-scale=1">
<head>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<script>
  function showResult(str) { // Entfernt oder zeigt das Suchergebnis an indem search.php aufgerufen wird
      if (str.length == 0) {
          $('#searchResultBox').hide();
          return;
      }
      if (window.XMLHttpRequest) {
          // code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp = new XMLHttpRequest();
      } else { // code for IE6, IE5
          xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
              document.getElementById("searchResult").innerHTML = this.responseText;
              $('#searchResultBox').show();
          }
      }
      xmlhttp.open("GET", "search.php?searchPhrase=" + str, true);
      xmlhttp.send();
  }
</script>



    <input class="" id="searchForm" type="text" placeholder="Materialsuche..." aria-label="Search" onkeyup="showResult(this.value)" autocomplete=off>
      <div class="" id="searchResultBox">
            <div id="searchResult">
            </div>
        </div>
  </div>

</body>

</html>





<!--
<div class="container" id="us">
  <div class="w3-content">
    <div class="searchBox w3-center w3-text-grey">
        <input class="" id="searchForm" type="text" placeholder="Materialsuche..." aria-label="Search" onkeyup="showResult(this.value)" autocomplete=off>

    </div>
  </div>
</div>
</body>




-->