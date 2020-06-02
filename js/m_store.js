var vue = new Vue({
  el: '#app',
  data: {
    en_name: '',
    REF: ''    
  },
    mounted () {
        axios
          .get('m_store_data.php?Command=generate')
          .then(response => {
            this.en_name = response.data[1]
            this.REF = response.data[0]
        })
    }
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




function view_stores() {
  var url = "view_store.php";
  url = url + "?Command=" + "view";
  
  window.open(url, "_blank");
}









var directionsRenderer;
var directionsService;

var map;
function initMap() {
  var rendererOptions = {
   
    suppressMarkers: false
  };
    directionsRenderer = new google.maps.DirectionsRenderer({
      suppressMarkers: true,
    });
    directionsService = new google.maps.DirectionsService();
  map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: 6.892785, lng: 79.980789 },
    zoom: 12,
  });
  directionsRenderer.setMap(map);

  
}


function getStores(){
    
    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    var url = "m_store_data.php";
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
                map: map,
              })
            );
        }

    }
}


function getRoutes() {
  xmlHttp = GetXmlHttpObject();
  if (xmlHttp == null) {
    alert("Browser does not support HTTP Request");
    return;
  }

  var url = "m_store_data.php";
  url = url + "?Command=" + "getRoutes";
  url = url + "&REF=" + document.getElementById("order_ref").value;
  url = url + "&ls=" + "new";

  xmlHttp.onreadystatechange = got_routes;
  xmlHttp.open("GET", url, true);
  xmlHttp.send(null);
}

function got_routes() {
  var XMLAddress1;

  if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {
    var obj = JSON.parse(xmlHttp.response);
    var delivery = obj[0];
    var shop = obj[1];
    console.log(delivery);
    console.log(shop);
    var marker = [];
    var location;
    var location_lat;
    var location_lng;

   
      // console.log(delivery[i].lat);
      location1 = {
        lat: parseFloat(delivery.lat),
        lng: parseFloat(delivery.lng),
      };
      marker.push(
        new google.maps.Marker({
          position: location1,

          label: {
            text: delivery.reg_name,
            color: "black",
            fontSize: "18px",
          },
          icon: "./img/customer.png",
          map: map,
        })
      );

      location2 = {
        lat: parseFloat(shop.lat),
        lng: parseFloat(shop.lng),
      };
      marker.push(
        new google.maps.Marker({
          position: location2,

          label: {
            text: shop.shop_name,
            color: "black",
            fontSize: "18px",
          },
          icon: "./img/store_icon.png",
          map: map,
        })
      );
    

      calculateAndDisplayRoute(directionsService, directionsRenderer, location1 ,location2);

  }
}









function calculateAndDisplayRoute(directionsService, directionsRenderer, loc1, loc2) {
  directionsService.route(
    {
      origin: loc1, // Haight.
      destination: loc2, // Ocean Beach.
      // Note that Javascript allows us to access the constant
      // using square brackets and a string value as its
      // "property."
      travelMode: google.maps.TravelMode["DRIVING"],
    },
    function (response, status) {
      if (status == "OK") {
        console.log(response);
        directionsRenderer.setDirections(response);
      } else {
        window.alert("Directions request failed due to " + status);
      }
    }
  );

    
    setTimeout(function () {
      getInfo(loc1, loc2);
    }, 2000);
}






function getInfo(loc1, loc2) {
  var service = new google.maps.DistanceMatrixService();
  service.getDistanceMatrix(
    {
      origins: [loc1],
      destinations: [loc2],
      travelMode: "DRIVING",
      unitSystem: google.maps.UnitSystem.METRIC,
      avoidHighways: false,
      avoidTolls: false,
    },
    function (response, status) {
      if (status !== "OK") {
        alert("Error was: " + status);
      } else {
        var originList = response.originAddresses;
        var destinationList = response.destinationAddresses;

        for (var i = 0; i < originList.length; i++) {
          var results = response.rows[i].elements;
          console.log(results[0].distance.text);
          console.log(results[0].duration.text);
          document.getElementById("duration").innerHTML =
            "Distance : " +
            results[0].distance.text +
            ", " +
            "Duration : " +
            results[0].duration.text; 

        }
      }
    }
  );
}

























// function getdt() {
    

//     xmlHttp = GetXmlHttpObject();
//     if (xmlHttp == null) {
//         alert("Browser does not support HTTP Request");
//         return;
//     }

//     var url = "m_store_data.php";
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

//     var url = "m_store_data.php";
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

//     var url = "m_store_data.php";
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
//     var url = "m_store_data.php";
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
