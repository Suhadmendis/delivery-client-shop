var vue1 = new Vue({
  el: "#app",
  data: {
    OBJ: "",
    ORDERS: "",
    DONEORDERS: "",
    locationLat: "",
    locationLng: "",
  },
  mounted() {
    setInterval(() => {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          function (position) {
            if (position.coords.latitude) {
              axios
                .get(
                  "index_data.php?Command=route&lat=" +
                    position.coords.latitude +
                    "&lng=" +
                    position.coords.longitude
                )
                .catch(function (error) {
                  console.log(error);
                })
                .then((response) => {
                  // console.log(response);
                });
            }
          },
          function () {}
        );
      }
    }, 10000);

    //   alert("1");
    this.orders();
    this.doneOrders();
    this.updateLoc();
    setInterval(() => {
      this.updateLoc();
    }, 10000);
  },
  methods: {
    updateLoc: function () {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
          vue1.locationLat = position.coords.latitude;
          vue1.locationLng = position.coords.longitude;
        });
      }

      // setTimeout(() => {
      //   this.changeDistance();
      // }, 2000);
    },
    orders: function () {
      axios.get("index_data.php?Command=generate").then((response) => {
        this.ORDERS = response.data[0];
        console.log(this.ORDERS);
      });
    },
    doneOrders: function () {
      axios.get("index_data.php?Command=doneOrders").then((response) => {
        this.DONEORDERS = response.data[0];
        // console.log(this.DONEORDERS);
      });
    },
    changeRiderStatus: function (Ref, status) {
      axios
        .get(
          "index_data.php?Command=changeRiderStatus&REF=" +
            Ref +
            "&status=" +
            status
        )
        .then((response) => {
          if (status == "DONE") {
            this.doneOrders();
          }
          if (response.data == "UPDATED") {
            this.orders();
          }
        });
    },
    changeDistance: function () {
      
      for (var i = 0; i < this.ORDERS.length; i++) {
        this.ORDERS[i].distance = 10;
      }

      // var locat1 = {
      //   lat: parseFloat(this.locationLat),
      //   lng: parseFloat(this.locationLng),
      // };
      // var locat2 = {
      //   lat: parseFloat(this.locationLat),
      //   lng: parseFloat(this.locationLng),
      // };

      // this.getDistance(locat1, locat2);
    },
    getDistance: function (location1,location2) {
      

      var service = new google.maps.DistanceMatrixService();

      service.getDistanceMatrix(
        {
          origins: [location1],
          destinations: [location2],
          travelMode: "DRIVING",
          unitSystem: google.maps.UnitSystem.METRIC,
          avoidHighways: false,
          avoidTolls: false,
        },
        function (response, status) {
          if (status !== "OK") {
            alert("Error was: " + status);
          } else {
              var info = response.rows[0].elements[0];
          }
        }
      );

      
    },
  },
});

