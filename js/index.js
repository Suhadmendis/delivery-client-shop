var vue1 = new Vue({
  el: "#app",
  data: {
    OBJ: ""    
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
    
  },
  methods: {
    // searchAgain: function () {
    //   axios.get("server/index_data.php?Command=generate").then((response) => {
    //     this.OBJ = JSON.parse(response.data[0]);
    //     this.CAT = JSON.parse(response.data[1]);
    //   });
    // }
  }
});
