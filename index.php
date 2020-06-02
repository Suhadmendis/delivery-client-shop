<?php 
include './CheckCookie.php';
$cookie_name = "user";
$mo = "";
if (isset($_COOKIE[$cookie_name])) {

    $mo = chk_cookie($_COOKIE[$cookie_name]);

    if ($mo == "ok") {
        // header('Location: ' . "home.php");
        // exit();
    }
}else{
  header('Location: ' . "auth.php");
  exit();
}


// -----------------------
include 'head_res.php'; 
if (isset($_GET['url'])) {

  if ($_GET['url'] == "store"){
    include 'm_store.php';
  }

  if ($_GET['url'] == "order"){
    include 'm_order.php';
  }












  if ($_GET['url'] == ""){
    include 'dashboard.php';
  }

}else{
	include 'dashboard.php'; 
}

include 'foot_res.php'; ?>
