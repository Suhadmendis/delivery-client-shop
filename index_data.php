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


if ($_GET["Command"] == "generate") {
   header('Content-Type: application/json');

   
    $objArray = Array();

   
    
    $sql = "select * from m_order where status <> 'DONE' and rider_name = '" . $_SESSION["CURRENT_USER"] . "' or rider_name is null";
    $result = $conn->query($sql);
    $row = $result->fetchAll();
    
    for ($j=0; $j < sizeof($row) ; $j++) { 
        $sql = "select * from m_store where REF = '" . $row[$j]['st_ref'] . "'";
        $result = $conn->query($sql);
        $row1 = $result->fetch();
    
        $row[$j]['Shop'] = $row1['shop_name'];
        $row[$j]['Shop_add'] = $row1['address'];
        $row[$j]['Shop_lat'] = $row1['loctaion_point_lat'];
        $row[$j]['Shop_lng'] = $row1['loctaion_point_lng'];
    }
    
    array_push($objArray,$row);

    echo json_encode($objArray);
    
}




if ($_GET["Command"] == "changeRiderStatus") {
    
    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->beginTransaction();

        $sql = "select status from m_order where REF = '" . $_GET['REF'] . "'";
        $result = $conn->query($sql);
        $row1 = $result->fetch();
        
        if($row1['status'] == "PLACE"){
            $sql2 = "update m_order set status = '". $_GET['status'] ."' where REF = '" . $_GET['REF'] . "'";
            $result = $conn->query($sql2);
            $sql2 = "update m_order set rider_name = '". $_SESSION["CURRENT_USER"] ."' where REF = '" . $_GET['REF'] . "'";
            $result = $conn->query($sql2);
            echo "UPDATED";
        }
        if($row1['status'] == "PICKUP"){
            $sql2 = "update m_order set status = '". $_GET['status'] ."' where REF = '" . $_GET['REF'] . "'";
            $result = $conn->query($sql2);
            echo "UPDATED";
        }

        if($row1['status'] == "DROPOFF"){
            $sql2 = "update m_order set status = '". $_GET['status'] ."' where REF = '" . $_GET['REF'] . "'";
            $result = $conn->query($sql2);
            echo "UPDATED";
        }

        
        // if($_GET['lat'] != ""){
        //     $sql = "Insert into rider_route(rider, lat, lng)values
        //                 ('".$_SESSION["CURRENT_USER"]."' ,'". $_GET['lat'] ."' ,'". $_GET['lng'] ."')";
        //     $result = $conn->query($sql);
        
        //     $sql2 = "update user_mast_rider set lat = '" . $_GET['lat']. "' where user_name = '". $_SESSION["CURRENT_USER"] ."'";
        //     $result = $conn->query($sql2);
        //     $sql2 = "update user_mast_rider set lng = '" . $_GET['lng']. "' where user_name = '". $_SESSION["CURRENT_USER"] ."'";
        //     $result = $conn->query($sql2);
        
        // }
        
        



        $conn->commit();
    } catch (Exception $e) {
        $conn->rollBack();
        echo $e;
    }
}