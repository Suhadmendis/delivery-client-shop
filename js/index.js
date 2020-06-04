var vue1 = new Vue({
  el: "#app",
  data: {
    OBJ: "",
    ORDERS: "",
    location1: "",
    location2: ""
  },
  mounted() {
    setInterval(function () {
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
                .then((response) => {
                  this.location1 = "fsdfs";
                  
                  
                });
            }
          },
          function () {}
        );
      }
    }, 10000);
    //   alert("1");
    this.orders();
  },
  methods: {
    orders: function () {
      axios.get("index_data.php?Command=generate").then((response) => {
        this.ORDERS = response.data[0];
        console.log(this.ORDERS);
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
          if (response.data == "UPDATED") {
            this.orders();
          }
        });
    },
    getDistance: function () {
      //  location1 = {
      //    lat: parseFloat("6.858355"),
      //    lng: parseFloat("79.869557"),
      //  };
      //  location2 = {
      //    lat: parseFloat("6.858355"),
      //    lng: parseFloat("79.859557"),
      //  };
      // var service = new google.maps.DistanceMatrixService();
      // service.getDistanceMatrix(
      //   {
      //     origins: [location1],
      //     destinations: [location2],
      //     travelMode: "DRIVING",
      //     unitSystem: google.maps.UnitSystem.METRIC,
      //     avoidHighways: false,
      //     avoidTolls: false,
      //   },
      //   function (response, status) {
      //     if (status !== "OK") {
      //       alert("Error was: " + status);
      //     } else {
      //       var originList = response.originAddresses;
      //       var destinationList = response.destinationAddresses;

      //       for (var i = 0; i < originList.length; i++) {
      //         var results = response.rows[i].elements;
      //         console.log(results);
      //       }
      //     }
      //   }
      // );

      return "fsa";
    },
  },
});

