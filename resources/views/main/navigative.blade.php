<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>นำทาง</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navigative.css') }}">
    <script src="https://api.longdo.com/map/?key=7de1ec7f25b9980ac90ec2457c427a3e"></script>
    <script>
        function init() {
            const urlParams = new URLSearchParams(window.location.search);
            let lat = urlParams.get('lat');
            let lon = urlParams.get('lon');

            if ("geolocation" in navigator) {
                navigator.geolocation.watchPosition(function(position) {
                    lat = position.coords.latitude;
                    lon = position.coords.longitude;

                    // Use the lat and lon values as needed
                    console.log("Latitude: " + lat + ", Longitude: " + lon);

                    // Initialize the map and add markers inside the geolocation success callback
                    initializeMap(lat, lon);
                }, function(error) {
                    console.log(error.message);
                }, {
                    enableHighAccuracy: true, // Request high accuracy
                    maximumAge: 0, // Do not use cached data
                    timeout: 5000 // Set a timeout for the request
                });
            } else {
                console.log("Geolocation is not available in this browser.");
                // You might want to handle this case, e.g., by using default coordinates.
            }

            function initializeMap(lat, lon) {
                const mapPlaceholder = document.getElementById('map');
                const map = new longdo.Map({
                    placeholder: mapPlaceholder,
                });

                map.Route.placeholder(document.getElementById("result"));
                map.Route.add(
                    new longdo.Marker({
                        lon: lon,
                        lat: lat
                    }, {
                        title: "Victory monument",
                        detail: "I'm here",
                        drag: false,
                    })
                );

                let basket = JSON.parse(localStorage.getItem("basket")) || [];
                // console.log(basket);
                axios
                    .post("/api/getLocations", basket)
                    .then((res) => {
                        if (res.data) {
                            displayBasket(res, "no button");
                            res.data.map((data) => {
                                map.Route.add({
                                    lon: data.longitude,
                                    lat: data.latitude
                                });
                            });
                        }
                        // console.log(res.data);
                    })
                    .catch((err) => {
                        console.error("Error fetching data:", err);
                    });

                map.Route.search();
            }
        }
    </script>
</head>

<body onload="init()">
    <x-loading />
    <x-navbar />
    <div class="container">
        <div class="main-navigative">
            <div class="basket-wrap">
                <div class="basket" id="locations">
                    <!-- return location -->
                </div>
                <div class="suggest"></div>
            </div>
            <div class="navigative">
                <div id="map" style="height:95vh"></div>
                <div id="result"></div>
            </div>

        </div>

    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</body>

</html>