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
    // setInterval(() => {
    //   if (navigator.geolocation) {
    //     navigator.geolocation.getCurrentPosition(
    //       function (position) {
    //         if (position.coords.latitude) {
    //           axios
    //             .get(
    //               "index_data.php?Command=route&lat=" +
    //                 position.coords.latitude +
    //                 "&lng=" +
    //                 position.coords.longitude
    //             )
    //             .catch(function (error) {
    //               console.log(error);
    //             })
    //             .then((response) => {
                  
    //             });
    //         }
    //       },
    //       function () {}
    //     );
    //   }
    // }, 10000);

    //   alert("1");
    // this.updateLoc();

    
      this.orders();
    
    this.doneOrders();
    // setInterval(() => {
    //   this.updateLoc();
    // }, 10000);
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
      console.log(this.locationLat + " : " + this.locationLng);
      axios
        .get(
          "index_data.php?Command=generate&lat=" +
            this.locationLat +
            "&lng=" +
            this.locationLng
        )
        .then((response) => {
          this.ORDERS = response.data[0];
          console.log(response.data[0]);
        });
    },

    updateOrder: function (ref) {
      axios
        .get(
          "index_data.php?Command=updateOrder&ref=" +
            ref +
            "&lat=" +
            this.locationLat +
            "&lng=" +
            this.locationLng
        )
        .then((response) => {
          // this.ORDERS = response.data[0];
          // console.log(response.data[0][0].REF);

          for (var i = 0; i < this.ORDERS.length; i++) {
            
            if (this.ORDERS[i].REF == response.data[0][0].REF) {
              this.ORDERS[i].distance = response.data[0][0].distance;
              this.ORDERS[i].status = response.data[0][0].status;
              
            }
          }
          // console.log(response.data[0][0].distance);
        });
    },
    doneOrders: function () {
      axios.get("index_data.php?Command=doneOrders").then((response) => {
        this.DONEORDERS = response.data[0];
        console.log(response.data[0]);
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
            this.orders();

          }

          this.ORDERS = response.data[0];
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
    getDistance: function (location1, location2) {
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

