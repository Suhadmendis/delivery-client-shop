<!DOCTYPE html>
<html>
  <head>
    <title>Geolocation</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>
  <body>
    <h1 id="location">f</h1>
    <!-- <h1>f</h1> -->
    <!-- <div id="map" style="display: none;"></div> -->
    <script>
      loadDoc();
        function loadDoc() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange=function() {
            if (this.readyState == 4 && this.status == 200) {
            
                var res = JSON.parse(this.responseText);
                
                console.log(res.results[0].formatted_address);
                
                document.getElementById("location").innerHTML = res.results[0].formatted_address;
            }
        };
        xhttp.open("GET", "https://maps.googleapis.com/maps/api/geocode/json?latlng=6.8605882,79.8730261&key=AIzaSyClBKRU9iKfSLnXVTvdv11RvKwpCrfdoQI", true);
        xhttp.send();
        }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyClBKRU9iKfSLnXVTvdv11RvKwpCrfdoQI&callback=initMap">
    </script>
  </body>
</html>

