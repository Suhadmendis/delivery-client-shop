var vue1 = new Vue({
  el: "#app",
  data: {
    OBJ: "" ,
    ORDERS: ""   
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
                        console.log(response.data);
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
    }
  }
});
