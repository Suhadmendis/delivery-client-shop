<!DOCTYPE html>
<html>

<head>
    <title>Simple Map</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <style>
        /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
        #map {
            height: 100%;
        }

        /* Optional: Makes the sample page fill the window. */
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body>
    <h3 id="duration">Loading...</h3>
    
    <input type="hidden" id="order_ref" value="<?php echo $_GET['Ref'] ?>">
    <div id="map"></div>
    <script src="js/m_store.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyClBKRU9iKfSLnXVTvdv11RvKwpCrfdoQI&callback=initMap" async defer></script>
    <script>setTimeout(function(){ getRoutes(); }, 2000);</script>
    <script>setTimeout(function(){ getInfo(); }, 4000);</script>
</body>

</html>
