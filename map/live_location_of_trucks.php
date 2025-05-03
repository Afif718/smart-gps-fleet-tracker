<!DOCTYPE html>
<html>
  <head>
    <title>Realtime GPS Tracker</title>
    <link rel="stylesheet" href="map.css" />
    <link
      rel="stylesheet"
      href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
      integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
      crossorigin="anonymous"
    />
  </head>

  <body>
    <!-- <div class="bg"></div> -->
    <!-- <div class="bg-others"> -->
    <div class="container">
      <h1>Realtime GPS Tracker</h1>
      <center>
        <hr
          style="
            height: 2px;
            border: none;
            color: #ffffff;
            background-color: #ffffff;
            width: 35%;
            margin: 0 auto 0 auto;
          "
        />
      </center>
      <center>
        <div id="map-canvas" style="width: 100%; height: 500px"></div>
      </center>
    </div>
    <!-- </div> -->

    <!-- Include Firebase libraries -->
    <script src="https://www.gstatic.com/firebasejs/7.20.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.20.0/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.20.0/firebase-database.js"></script>

    <!-- Google Maps API script -->
    <script
      src="https://maps.googleapis.com/maps/api/js?key=*******=initialize"
      async
      defer
    ></script>
   
    <script>
      // Initial map coordinates set to Malaysia
      const initialLat = 4.2105; // Approximate latitude for Malaysia
      const initialLng = 101.9758; // Approximate longitude for Malaysia

      // Map and markers variables
      let map;
      let markers = {}; // Object to store markers by user ID

      // Initialize the map
      function initialize() {
        map = new google.maps.Map(document.getElementById("map-canvas"), {
          center: { lat: initialLat, lng: initialLng },
          zoom: 6,
        });

        // Create an InfoWindow instance
        const infoWindow = new google.maps.InfoWindow();

        // Firebase configuration
        const firebaseConfig = {
          apiKey: "************************************",
          authDomain: "************************************",
          databaseURL:
            "https://gps-tracker-************************************.app",
          projectId: "gps-tracker*******",
          storageBucket: "gps-tracker-********.com",
          messagingSenderId: "**********",
          appId: "1:**********:web:**********",
        };

        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);

        // Reference the root of the Firebase Realtime Database
        const ref = firebase.database().ref();

        // Listen for changes to the Firebase data
        ref.on(
          "value",
          function (snapshot) {
            // Fetch data from Firebase
            const gpsData = snapshot.val();
            console.log(gpsData);

            if (gpsData) {
              // Iterate over each data item
              for (const key in gpsData) {
                const vehicleData = gpsData[key];
                if (
                  vehicleData &&
                  typeof vehicleData.LAT === "number" &&
                  typeof vehicleData.LNG === "number"
                ) {
                  const lat = vehicleData.LAT;
                  const lng = vehicleData.LNG;
                  const details =
                    vehicleData.details || "Details not available";
                  const userId = vehicleData.userID; // Use the userID property

                  console.log(`User ID: ${userId}, Details: ${details}`);

                  // Update or create a marker for the vehicle
                  if (!markers[userId]) {
                    // Create a new marker for this vehicle
                    const marker = new google.maps.Marker({
                      position: { lat: lat, lng: lng },
                      map: map,
                      icon: {
                        url: "truck2.svg", // Specify the URL of your custom icon
                        scaledSize: new google.maps.Size(35, 35), // Adjust size if necessary
                      },
                      label: {
                        text: userId,
                        color: "#000000",
                        fontSize: "14px",
                        fontWeight: "bold",
                      },
                    });

                    // Attach a click event listener to the marker
                    marker.addListener("click", () => {
                      // Create an InfoWindow and set its content
                      infoWindow.setContent(`
                        <div style="color: black; background-color: white; padding: 10px; border-radius: 5px;">
                          <h4 style="color: black;">${userId}</h4>
                          <p style="color: black;"><strong>Details:</strong> ${details}</p>
                        </div>
                      `);
                      infoWindow.open(map, marker);
                    });

                    // Store the marker
                    markers[userId] = marker;
                  } else {
                    // Update the marker's position
                    markers[userId].setPosition({ lat, lng });

                    // Update the marker's label text
                    markers[userId].setLabel({
                      text: userId,
                      color: "#7b03fc",
                      fontSize: "14px",
                      fontWeight: "bold",
                    });
                  }
                } else {
                  // Log an error if data is not found or invalid
                  console.error(
                    `Invalid or missing GPS data for data key ${key}:`,
                    vehicleData
                  );
                }
              }
            }
          },
          function (error) {
            // Log an error if there is an issue with data retrieval
            console.error("Error retrieving data from Firebase:", error.code);
          }
        );
      }
    </script>
  </body>
</html>
