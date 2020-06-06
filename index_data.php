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


if ($_GET["Command"] == "doneOrders") {
   header('Content-Type: application/json');

   
    $objArray = Array();

   
    
    $sql = "select * from m_order where status = 'DONE' and rider_name = '" . $_SESSION["CURRENT_USER"] . "'";
    $result = $conn->query($sql);
    $row = $result->fetchAll();
    
    
    
    array_push($objArray,$row);

    echo json_encode($objArray);
    
}

if ($_GET["Command"] == "generate") {
   header('Content-Type: application/json');

    $objArray = Array();

    $my_lat = "6.860507";
    $my_lng = "79.872998";

    $sql = "select * from m_order where status <> 'DONE' and rider_name = '" . $_SESSION["CURRENT_USER"] . "' or rider_name is null";
    $result = $conn->query($sql);
    $row = $result->fetchAll();
    
    $shortest_trip_check = 1000000;
    $shortest_trip = "";
    for ($j=0; $j < sizeof($row) ; $j++) { 
        $sqlshop = "select * from m_order_store where REF = '" . $row[$j]['REF'] . "'";
        $result = $conn->query($sqlshop);
        $rowshop = $result->fetchAll();


        

        $des_sritng = $row[$j]['lat'].'%2C'.$row[$j]['lng'];

        for ($i=0; $i < sizeof($rowshop) ; $i++) { 
           
                $des_sritng .= '%7C';
                $des_sritng .= $rowshop[$i]['lat'].'%2C'.$rowshop[$i]['lng'];
            
        }

        $response = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=' . $my_lat . ',' . $my_lng . '&destinations=' . $des_sritng . '&key=AIzaSyClBKRU9iKfSLnXVTvdv11RvKwpCrfdoQI');
        $response = json_decode($response);

        $elements = $response->rows[0]->elements;
        $full_distance = 0;
        for ($x=0; $x < sizeof($elements) ; $x++) { 
            $full_distance = $full_distance + $elements[$x]->distance->value;
        }
        

        if($shortest_trip_check > $full_distance){
            $shortest_trip_check = $full_distance;
            $shortest_trip = $row[$j]['REF'];
        }
        
    }

    


    $sql = "select * from m_order where REF = '" . $shortest_trip . "'";
    $result = $conn->query($sql);
    $row = $result->fetch();







    array_push($objArray,$row);

    echo json_encode($objArray);


        

    



        
    // print_r($shortest_trip);
}

if ($_GET["Command"] == "updateOrder") {
   header('Content-Type: application/json');

    $objArray = Array();

    $sql = "select * from m_order where REF = '" . $_GET['ref'] . "' and status <> 'DONE' and rider_name = '" . $_SESSION["CURRENT_USER"] . "' or rider_name is null limit 1";
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

        $response = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=' . $_GET['lat'] . ',' . $_GET['lng'] . '&destinations=' . $row1['loctaion_point_lat'] . ',' . $row1['loctaion_point_lng'] . '&key=AIzaSyClBKRU9iKfSLnXVTvdv11RvKwpCrfdoQI');
        $response = json_decode($response);

        $distance = $response->rows[0]->elements[0];
        $duration = $response->rows[0]->elements[0];

        // $row[$j]['distance'] = $distance->distance->text;
        // $row[$j]['duration'] = $duration->duration->text;
        $row[$j]['distance'] = 90;
        $row[$j]['duration'] = 100;

    }
    
    array_push($objArray,$row);

    echo json_encode($objArray);
    // print_r($objArray);
}


if ($_GET["Command"] == "getLoc") {
   header('Content-Type: application/json');

    $objArray = Array();

    
        $response = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=40.6655101,-73.89188969999998&destinations=40.6905615%2C-73.9976592%7C40.6905615%2C-73.9976592%7C40.6905615%2C-73.9976592%7C40.6905615%2C-73.9976592%7C40.6905615%2C-73.9976592%7C40.6905615%2C-73.9976592%7C40.659569%2C-73.933783%7C40.729029%2C-73.851524%7C40.6860072%2C-73.6334271%7C40.598566%2C-73.7527626%7C40.659569%2C-73.933783%7C40.729029%2C-73.851524%7C40.6860072%2C-73.6334271%7C40.598566%2C-73.7527626&key=AIzaSyClBKRU9iKfSLnXVTvdv11RvKwpCrfdoQI');
        $response = json_decode($response);
     
    
    array_push($objArray,$response);

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
            $sql2 = "update m_order set delivery_type = 'SIN' where REF = '" . $_GET['REF'] . "'";
            $result = $conn->query($sql2);
        }
        if($row1['status'] == "PICKUP"){
            $sql2 = "update m_order set status = '". $_GET['status'] ."' where REF = '" . $_GET['REF'] . "'";
            $result = $conn->query($sql2);
        }

        if($row1['status'] == "DROPOFF"){
            $sql2 = "update m_order set status = '". $_GET['status'] ."' where REF = '" . $_GET['REF'] . "'";
            $result = $conn->query($sql2);
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
        


        $objArray = Array();


        $sql = "select * from m_order where REF = '" . $_GET['REF'] . "' and status <> 'DONE'";
        $result = $conn->query($sql);
        $row = $result->fetch();

        array_push($objArray,$row);

        echo json_encode($objArray);



        $conn->commit();
    } catch (Exception $e) {
        $conn->rollBack();
        echo $e;
    }
}