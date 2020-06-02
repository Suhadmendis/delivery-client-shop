var vue = new Vue({
  el: "#app",
  data: {
    en_name: "",
    ORDERS: "",
  },
  mounted() {
    axios.get("m_order_data.php?Command=generate").then((response) => {
      this.en_name = response.data[1];
      this.ORDERS = response.data[0];
      console.log(response.data[0]);
    });
  },
  methods: {
    view_stores: function (ref) {
      view_stores(ref);
      // axios
      //     .get('server/cart_data.php?Command=generate')
      //     .then(response => {
      //         this.CART = JSON.parse(response.data[0]);
      //         this.prepareCartt();
      //         vue.reloadCart();
      //     })
    },
  },
});

function GetXmlHttpObject() {
    var xmlHttp = null;
    try {
        // Firefox, Opera 8.0+, Safari
        xmlHttp = new XMLHttpRequest();
    } catch (e) {
// Internet Explorer
        try {
            xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
    }
    return xmlHttp;
}

function keyset(key, e) {

    if (e.keyCode == 13) {
        document.getElementById(key).focus();
    }
}

function got_focus(key) {
    document.getElementById(key).style.backgroundColor = "#000066";
}

function lost_focus(key) {
    document.getElementById(key).style.backgroundColor = "#000000";
}




function view_stores(ref) {
  var url = "view_routes.php";
  url = url + "?Ref=" + ref;
  
  window.open(url, "_blank");
}











var map;
function initMap() {
    
  map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: 6.892785, lng: 79.980789 },
    zoom: 12,
  });
  ;
}


function getStores(){
    
    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    var url = "m_order_data.php";
    url = url + "?Command=" + "getStores";
    url = url + "&ls=" + "new";

    xmlHttp.onreadystatechange = got_stores;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);

}

function got_stores() {
    var XMLAddress1;

    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete")
    {

        var locations = JSON.parse(xmlHttp.response);
        var marker = [];
        var location;
        var location_lat;
        var location_lng;
        
        for (var i = 0; i < locations[0].length; i++) {
            console.log(locations[0][i].lat);
            location = {
              lat: parseFloat(locations[0][i].lat),
              lng: parseFloat(locations[0][i].lng)
            };
            marker.push(
              new google.maps.Marker({
                position: location,

                label: {
                  text: locations[0][i].shop_name,
                  color: "black",
                  fontSize: "18px",
                },
                // icon: './img/shop_icon.png',
                map: map,
              })
            );
        }
// #48b94c

// var uluru2 = { lat: 6.882785, lng: 79.980789 };
// marker1.push(new google.maps.Marker({ position: uluru2, map: map }));
    //   XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("id");
    //   vue.REF = XMLAddress1[0].childNodes[0].nodeValue;

    //   XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("en_name");
    //   vue.en_name = XMLAddress1[0].childNodes[0].nodeValue;

    }
}








































// function getdt() {
    

//     xmlHttp = GetXmlHttpObject();
//     if (xmlHttp == null) {
//         alert("Browser does not support HTTP Request");
//         return;
//     }

//     var url = "m_order_data.php";
//     url = url + "?Command=" + "getdt";
//     url = url + "&ls=" + "new";

//     xmlHttp.onreadystatechange = assign_dt;
//     xmlHttp.open("GET", url, true);
//     xmlHttp.send(null);
// }

// function assign_dt() {
//     var XMLAddress1;

//     if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete")
//     {

//       XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("id");
//       vue.REF = XMLAddress1[0].childNodes[0].nodeValue;

//       XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("en_name");
//       vue.en_name = XMLAddress1[0].childNodes[0].nodeValue;

//     }
// }





// function save_info()
// {

//     xmlHttp = GetXmlHttpObject();
//     if (xmlHttp == null)
//     {
//         alert("Browser does not support HTTP Request");
//         return;
//     }
//      if (document.getElementById('REF').value == "") {
//         document.getElementById('REF').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'></span></div>";
//         return false;
//     }

//     var url = "m_order_data.php";
//     url = url + "?Command=" + "save_item";
//     url = url + "&REF=" + document.getElementById("REF").value;
//     url = url + "&shop_name=" + document.getElementById("shop_name").value;
//     url = url + "&tagline=" + document.getElementById("tagline").value;
//     url = url + "&listing_type=" + document.getElementById("listing_type").value;
//     url = url + "&vendor_ref=" + document.getElementById("vendor_ref").value;
//     url = url + "&vendor_name=" + document.getElementById("vendor_name").value;
    
//     url = url + "&address=" + document.getElementById("address").value;
//     url = url + "&loctaion_point_lat=" + document.getElementById("loctaion_point_lat").value;
//     url = url + "&loctaion_point_lng=" + document.getElementById("loctaion_point_lng").value;
//     url = url + "&phone_number_1=" + document.getElementById("phone_number_1").value;
//     url = url + "&phone_number_2=" + document.getElementById("phone_number_2").value;
//     url = url + "&email_address=" + document.getElementById("email_address").value;
//     url = url + "&approve=" + document.getElementById("approve").value;
//     url = url + "&active=" + document.getElementById("active").value;
    

//     if (document.getElementById("approve").checked) {
//         url = url + "&approve=" + "1";
//     } else {
//         url = url + "&approve=" + "0";
//     }
//     if (document.getElementById("active").checked) {
//         url = url + "&active=" + "1";
//     } else {
//         url = url + "&active=" + "0";
//     }
    
//     xmlHttp.onreadystatechange = salessaveresult;
//     xmlHttp.open("GET", url, true);
//     xmlHttp.send(null);

// }


// function salessaveresult() {
//     var XMLAddress1;

//     if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {

//         if (xmlHttp.responseText == "Saved") {
//             alert(xmlHttp.responseText);
//         } else {
//             alert(xmlHttp.responseText);
//         }
//     }
// }


// function approve() {

//     xmlHttp = GetXmlHttpObject();
//     if (xmlHttp == null) {
//         alert("Browser does not support HTTP Request");
//         return;
//     }
//     if (document.getElementById('REF').value == "") {
//         document.getElementById('REF').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'></span></div>";
//         return false;
//     }

//     var url = "m_order_data.php";
//     url = url + "?Command=" + "approve";
//     url = url + "&REF=" + document.getElementById("REF").value;


//     xmlHttp.onreadystatechange = approvesaveresult;
//     xmlHttp.open("GET", url, true);
//     xmlHttp.send(null);

// }


// function approvesaveresult() {
//     var XMLAddress1;

//     if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {

//         if (xmlHttp.responseText == "Approved") {
//             alert(xmlHttp.responseText);
//         } else {
//             alert(xmlHttp.responseText);
//         }
//     }
// }


// function getForm(REF, IDF)
// {
   
//     xmlHttp = GetXmlHttpObject();
//     if (xmlHttp == null)
//     {
//         alert("Browser does not support HTTP Request");
//         return;
//     }
//     var url = "m_order_data.php";
//     url = url + "?Command=" + "getForm";
//     url = url + "&REF=" + REF;
//     url = url + "&IDF=" + IDF;

//     xmlHttp.onreadystatechange = getFromValues;
//     xmlHttp.open("GET", url, true);
//     xmlHttp.send(null);
// }

// function getFromValues()
// {
//     var XMLAddress1;

//     if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete")
//     {

//         XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("IDF");
//         var IDF = XMLAddress1[0].childNodes[0].nodeValue;

//         XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("objSup");
//         var objSup = JSON.parse(XMLAddress1[0].childNodes[0].nodeValue);

//         if (IDF === "Master") {
//             opener.document.getElementById('REF').value = objSup.REF;
//             opener.document.getElementById('shop_name').value = objSup.shop_name;
//             opener.document.getElementById('tagline').value = objSup.tagline;
//             opener.document.getElementById('listing_type').value = objSup.listing_type;
//             opener.document.getElementById('vendor_ref').value = objSup.vendor_ref;
//             opener.document.getElementById('vendor_name').value = objSup.vendor_name;
//             opener.document.getElementById('address').value = objSup.address;
//             opener.document.getElementById('loctaion_point_lat').value = objSup.loctaion_point_lat;
//             opener.document.getElementById('loctaion_point_lng').value = objSup.loctaion_point_lng;
//             opener.document.getElementById('phone_number_1').value = objSup.phone_number_1;
//             opener.document.getElementById('phone_number_2').value = objSup.phone_number_2;
//             opener.document.getElementById('email_address').value = objSup.email_address;

//             if (objSup.active == "1") {
//                 opener.document.getElementById('active').checked = true;
//             } else {
//                 opener.document.getElementById('active').checked = false;
//             }

//             if (objSup.approve == "1") {
//                 opener.document.getElementById('approve').checked = true;
//             } else {
//                 opener.document.getElementById('approve').checked = false;
//             }


//             if (objSup.approve == "1") {
//                 window.opener.document.getElementById('app_status').innerHTML = "Approved";
//             } else {
//                 window.opener.document.getElementById('app_status').innerHTML = "Not Approved";
//             }


//         }


//         if (IDF === "item") {
//             opener.document.getElementById('store_ref').value = objSup.REF;
//             opener.document.getElementById('store_name').value = objSup.shop_name;
     

//         }

      
//         self.close();
    
//     }
    
// }
