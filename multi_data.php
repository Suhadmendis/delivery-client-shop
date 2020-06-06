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
    $customers = $result->fetchAll();
    
    
    for ($j=0; $j < sizeof($customers) ; $j++) { 

        $sqlSub = "select * from m_order_store where REF = '" . $customers[$j]['REF'] . "'";   
        $result = $conn->query($sqlSub);
        $customers[$j]['shops'] = $result->fetchAll();


        $response = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=' . $my_lat . ',' . $my_lng . '&destinations=' . $customers[$j]['lat'] . '%2C' . $customers[$j]['lng'] . '&key=AIzaSyClBKRU9iKfSLnXVTvdv11RvKwpCrfdoQI');
        $response = json_decode($response);

        $customers[$j]['distance'] = $response->rows[0]->elements[0]->distance->value;
        $full_distance = 0;

       
    }
    
        $myArray = Array();
        for ($x=0; $x < sizeof($customers) ; $x++) { 
            array_push($myArray,$customers[$x]['distance']);
        }
        
        sort($myArray);

        $orderArray = Array();
        for ($x=0; $x < sizeof($myArray) ; $x++) { 
            for ($k=0; $k < sizeof($customers) ; $k++) { 
                //selecting 3 stores
                if($x < 3){
                    if($myArray[$x] == $customers[$k]['distance']){
                        array_push($orderArray,$customers[$k]);
                    }
                }
            }
        }

        // print_r($orderArray);


        $shopRefArray = Array();
        for ($x=0; $x < sizeof($orderArray) ; $x++) { 
            $sql = "select * from m_order_store where REF = '" . $orderArray[$x]['REF'] . "'";   
            $result = $conn->query($sql);
            $stores = $result->fetchAll();
            
            for ($j=0; $j < sizeof($stores) ; $j++) { 
                array_push($shopRefArray, $stores[$j]['st_ref']);
            }

        }

        // print_r(array_unique($shopRefArray));




        
        array_push($objArray,$orderArray);

        $shopRefArray = array_unique($shopRefArray);

        for ($j=0; $j < sizeof($shopRefArray) ; $j++) { 

            $sqlSub = "select * from m_store where REF = '" . $shopRefArray[$j] . "'";   
            $result = $conn->query($sqlSub);
            $shopRefArray[$j] = $result->fetch();

        }

        array_push($objArray,$shopRefArray);


        





        $shopRefArray_temp = Array();

        for ($j=0; $j < sizeof($shopRefArray) ; $j++) {
            
            
            for ($X=0; $X < sizeof($orderArray) ; $X++) {
                
                $sqlSub = "select * from m_order_store where REF = '" . $orderArray[$X]['REF'] . "' and st_ref = '" . $shopRefArray[$j]['REF'] . "' and status = 'DELIVERY'"; 
                $result = $conn->query($sqlSub);
                $shop = $result->fetch();

                if($shop['st_ref'] == ""){
                    array_push($shopRefArray_temp,$shopRefArray[$j]['REF']);
                }
               
            }
            
        }

        $shopRefArray_temp = array_unique($shopRefArray_temp);


        
        $shopRefArray = array_values($shopRefArray_temp);
        // print_r(array_values($shopRefArray));

        for ($j=0; $j < sizeof($shopRefArray) ; $j++) { 

            $sqlSub = "select * from m_store where REF = '" . $shopRefArray[$j] . "'";   
            $result = $conn->query($sqlSub);
            $shopRefArray[$j] = $result->fetch();

        }



        $min_dur = 1000000;
        $shortest_shop;

        for ($j=0; $j < sizeof($shopRefArray) ; $j++) {
            
            $sqlSub = "select * from m_store where REF = '" . $shopRefArray[$j]['REF'] . "'";   
            $result = $conn->query($sqlSub);
            $shop = $result->fetch();

            $response = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=' . $my_lat . ',' . $my_lng . '&destinations=' . $shop['loctaion_point_lat'] . '%2C' . $shop['loctaion_point_lng'] . '&key=AIzaSyClBKRU9iKfSLnXVTvdv11RvKwpCrfdoQI');
            $response = json_decode($response);

           

            if($min_dur > $response->rows[0]->elements[0]->distance->value){
                $min_dur = $response->rows[0]->elements[0]->distance->value;
                $shortest_shop = $shop;
                $shortest_shop['dura'] = $min_dur;

            }
           
        }


        // print_r($shortest_shop);


        array_push($objArray,$shortest_shop);

        echo json_encode($objArray);











// https://maps.googleapis.com/maps/api/directions/json?origin=Adelaide,SA&destination=Adelaide,SA&waypoints=optimize:true|Barossa+Valley,SA|Clare,SA|Connawarra,SA|McLaren+Vale,SA&sensor=false&key=AIzaSyClBKRU9iKfSLnXVTvdv11RvKwpCrfdoQI


// https://maps.googleapis.com/maps/api/directions/json?origin=6.851235,79.866670&destination=6.853749,79.885081&waypoints=optimize:true|6.844034,79.875597|6.839688,79.874009&key=AIzaSyClBKRU9iKfSLnXVTvdv11RvKwpCrfdoQI




        

    



        
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




if ($_GET["Command"] == "startRiderStatus") {
    
    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->beginTransaction();

        // $sql = "select status from m_order where REF = '" . $_GET['REF'] . "'";
        // $result = $conn->query($sql);
        // $row1 = $result->fetch();
        
        
        // print_r($_GET);
        $orders = json_decode($_GET['REFS']);

        for ($j=0; $j < sizeof($orders) ; $j++) { 
            $sql2 = "update m_order set status = 'DELIVERY' where REF = '" . $orders[$j] . "'";
            $result = $conn->query($sql2);
            $sql2 = "update m_order set rider_name = '" . $_SESSION["CURRENT_USER"] . "' where REF = '" . $orders[$j] . "'";
            $result = $conn->query($sql2);
            $sql2 = "update m_order set delivery_type = 'MUL' where REF = '" . $orders[$j] . "'";
            $result = $conn->query($sql2);
        }
        


        // $objArray = Array();


        // $sql = "select * from m_order where REF = '" . $_GET['REF'] . "' and status <> 'DONE'";
        // $result = $conn->query($sql);
        // $row = $result->fetch();

        // array_push($objArray,$row);

        // echo json_encode($objArray);


        echo 'DONE';
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollBack();
        echo $e;
    }
}


if ($_GET["Command"] == "changeRiderStatus") {
    
    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->beginTransaction();

       
        $orders = json_decode($_GET['REFS']);

        for ($j=0; $j < sizeof($orders) ; $j++) { 
            $sql2 = "update m_order_store set status = 'DELIVERY' where REF = '" . $orders[$j] . "' and st_ref = '" . $_GET['shopRef'] . "'";
            $result = $conn->query($sql2);
            $sql2 = "update m_order_store set rider_name = '" . $_SESSION["CURRENT_USER"] . "' where REF = '" . $orders[$j] . "' and st_ref = '" . $_GET['shopRef'] . "'";
            $result = $conn->query($sql2);
            
        }
        


       


        echo 'DONE';
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollBack();
        echo $e;
    }
}





