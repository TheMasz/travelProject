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
    <script src="{{ asset('js/navigative.js') }}"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
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
                <div class="suggest">
                    <div class="toggle">
                        <span class="material-icons">
                            arrow_forward_ios
                        </span>
                    </div>
                    <div class="location-suggest">
                        <p>สถานที่ท่องเที่ยวใกล้เคียง</p>
                        <div class="locations-near">
                            <!-- return location -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="navigative">
                <div id="map"></div>
                <div id="result"></div>
            </div>

        </div>

    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js" defer></script>
</body>

</html>
