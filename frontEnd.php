<!DOCTYPE html>
<html>
<script>
        function showResult(str) {
          if (str.length==0) { 
            
            $('#searchResultBox').hide();
            return;
          }
          if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
          } else {  // code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
          }
          xmlhttp.onreadystatechange=function() {
            if (this.readyState==4 && this.status==200) {
              document.getElementById("searchResult").innerHTML=this.responseText;
              $('#searchResultBox').show();
            }
          }
          xmlhttp.open("GET","search.php?searchPhrase="+str,true);
          xmlhttp.send();
        }
    </script>
<body>
<div class="form-inline d-flex justify-content-center"><span class="fa fa-search" style="margin-right:5px;" aria-hidden="true"></span>
    <input class="form-control mr-2" id="searchForm" type="text" placeholder="Suche..." aria-label="Search" onkeyup="showResult(this.value)" autocomplete=off>
    <div class="" id="searchResultBox">
        <div id="searchResult">
        </div>
    </div>
</div>
</body>
</html>
<?php

?>