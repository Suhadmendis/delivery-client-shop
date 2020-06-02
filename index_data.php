<?php

session_start();


require_once ("DB_connector.php");


date_default_timezone_set('Asia/Colombo');

if ($_GET["Command"] == "route") {
    
    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->beginTransaction();


        if($_GET['lat'] != ""){
            $sql = "Insert into rider_route(rider, lat, lng)values
                        ('".$_SESSION["CURRENT_USER"]."' ,'". $_GET['lat'] ."' ,'". $_GET['lng'] ."')";
            $result = $conn->query($sql);
        
            $sql2 = "update user_mast_rider set lat = '" . $_GET['lat']. "' where user_name = '". $_SESSION["CURRENT_USER"] ."'";
            $result = $conn->query($sql2);
            $sql2 = "update user_mast_rider set lng = '" . $_GET['lng']. "' where user_name = '". $_SESSION["CURRENT_USER"] ."'";
            $result = $conn->query($sql2);
        
        }
        
        



        $conn->commit();
        echo "Saved";
    } catch (Exception $e) {
        $conn->rollBack();
        echo $e;
    }
}