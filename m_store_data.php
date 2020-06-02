<?php

session_start();


require_once ("DB_connector.php");


date_default_timezone_set('Asia/Colombo');

if ($_GET["Command"] == "generate") {
   header('Content-Type: application/json');

    $ResponseXML = "";
    $ResponseXML .= "<new>";

    $sql = "SELECT store_ref FROM sys_info";
    $result = $conn->query($sql);
    $row = $result->fetch();
    $no = $row['store_ref'];
    $tmpinvno = "000000" . $row["store_ref"];
    $lenth = strlen($tmpinvno);
    $no = trim("ST/") . substr($tmpinvno, $lenth - 7);


    $en_name = "Store";

    $objArray = Array();
    array_push($objArray,$no,$en_name);

    echo json_encode($objArray);

   
}



if ($_GET["Command"] == "getStores") {
   header('Content-Type: application/json');

    $ResponseXML = "";
    $ResponseXML .= "<new>";

    $sql = "SELECT loctaion_point_lat as lat, loctaion_point_lng as lng, shop_name FROM m_store";
    $result = $conn->query($sql);
    $row = $result->fetchAll();
    
    $objArray = Array();
    array_push($objArray,$row);

    echo json_encode($objArray);
}


if ($_GET["Command"] == "getRoutes") {
   header('Content-Type: application/json');

    $ResponseXML = "";
    $ResponseXML .= "<new>";

    $objArray = Array();
    
    $sql = "SELECT * FROM m_order where REF = '" . $_GET['REF'] . "'";
    $result = $conn->query($sql);
    $row = $result->fetch();

    array_push($objArray,$row);

    $sql = "SELECT loctaion_point_lat as lat, loctaion_point_lng as lng, shop_name FROM m_store where REF = '" . $row['st_ref'] . "'";
    $result = $conn->query($sql);
    $row = $result->fetch();

    array_push($objArray,$row);

    echo json_encode($objArray);
}




// if ($_GET["Command"] == "save_item") {
 

    
//     try {
//         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//         $conn->beginTransaction();

//         $sql = "SELECT store_ref FROM sys_info";
//         $resul = $conn->query($sql);
//         $row = $resul->fetch();
//         $no = $row["store_ref"];
//         $tmpinvno = "000000" . $row["store_ref"];
//         $lenth = strlen($tmpinvno);
//         $no1 = trim("ST/") . substr($tmpinvno, $lenth - 7);

//         $sql = "Insert into m_store(REF, shop_name, tagline, listing_type, vendor_ref, vendor_name, address, loctaion_point_lat, loctaion_point_lng, phone_number_1, phone_number_2, email_address, approve, user, active)values
//                         ('" . $no1 . "' ,'" . $_GET['shop_name'] . "' ,'" . $_GET['tagline'] . "' ,'" . $_GET['listing_type'] . "' ,'" . $_GET['vendor_ref'] . "' ,'" . $_GET['vendor_name'] . "' ,'" . $_GET['address'] . "' ,'" . $_GET['loctaion_point_lat'] . "' ,'" . $_GET['loctaion_point_lng'] . "' ,'" . $_GET['phone_number_1'] . "' ,'" . $_GET['phone_number_2'] . "' ,'" . $_GET['email_address'] . "' ,'" . $_GET['approve'] . "' ,'" . $_SESSION['UserName'] . "','" . $_GET['active'] . "')";
//         $result = $conn->query($sql);
        
        
//         $no2 = $no + 1;
//         $sql = "update sys_info set store_ref = $no2 where store_ref = $no";
//         $result = $conn->query($sql);

//         $sql = "Insert into sys_log(REF, entry, operation, user, ip)values
//                         ('" . $no1 . "' ,'entry' ,'SAVE'  ,'" . $_SESSION['UserName'] . "' ,'ip')";
//         $result = $conn->query($sql);



//         $conn->commit();
//         echo "Saved";
//     } catch (Exception $e) {
//         $conn->rollBack();
//         echo $e;
//     }
// }






// if ($_GET["Command"] == "approve") {
 

    
//     try {
//         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//         $conn->beginTransaction();

//        $no1 = $_GET['REF'];
//         $sql = "update m_store set approve = '1' where REF = '" . $no1 . "'";
//         $result = $conn->query($sql);

//         $sql = "Insert into sys_log(REF, entry, operation, user, ip)values
//                         ('" . $no1 . "' ,'entry' ,'Approved'  ,'" . $_SESSION['UserName'] . "' ,'ip')";
//         $result = $conn->query($sql);



//         $conn->commit();
//         echo "Approved";
//     } catch (Exception $e) {
//         $conn->rollBack();
//         echo $e;
//     }
// }



if ($_GET["Command"] == "getForm") {
    header('Content-Type: text/xml');
    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";

    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";

    $REF = $_GET["REF"];

    $sql = "select * from m_store where REF= '" . $REF . "'";

    $sql = $conn->query($sql);
    if ($row = $sql->fetch()) {
        $ResponseXML .= "<objSup><![CDATA[" . json_encode($row) . "]]></objSup>";
    }

   $ResponseXML .= "<IDF><![CDATA[" . $_GET['IDF'] . "]]></IDF>";

    $ResponseXML .= "</salesdetails>";
    echo $ResponseXML;
}
