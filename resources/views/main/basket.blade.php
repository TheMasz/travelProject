<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basket</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/basket.css') }}">

</head>

<body>

    <x-navbar />
    <!-- <div id="map"></div> -->
    <div class="container">
        <div class="main-basket">
            <div class="content-basket">
                <div class="location-mark">

                    <h3>ทริปของคุณ</h3>
                    <div>
                        <p>ตำแหน่งปัจจุบัน</p>
                        <p id="current-geo"></p>
                    </div>
                    <hr>
                </div>
                <div class="locations" id="locations">
                    <!-- return location -->
       
                </div>
            </div>
            <div class="tooltips-basket">
                <h3>เครื่องมือช่วยจัดทริป</h3>
                <button onclick="getCurrectGeo()">ใช้ตำแหน่งปัจจุบัน</button>
                <br>
                <button onclick="calcDistance()">เรียงลำดับตามระยะทาง</button>
                <br>
                <button onclick="navigative()">หาเส้นทาง</button>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let basket = JSON.parse(localStorage.getItem("basket")) || [];
            if (basket.length == 0) {
                const locations = document.querySelector("#locations");
                locations.innerHTML = ``;
                let html = `
                <div class='back-home'>
                    <span class="material-icons" style='color:lightgray; font-size:64px; margin-bottom:5px'>
                        luggage
                    </span>
                    <p class='mb-05' style='color:lightgray; text-align:center; width:75%'>ยังไม่มีสถานที่ท่องเที่ยวในการเดินของคุณ 
                    กรุณาเริ่มด้วยการเลือกสถานที่ที่คุณต้องการเพิ่มเข้าในแผนการท่องเที่ยวของคุณ คุณสามารถเริ่มการค้นหาและเพิ่มสถานที่ท่องเที่ยวได้ที่หน้าหลักของเรา</p>
                    <a href='/' class='btn-primary'>ไปยังหน้าแรก</a>
                </div>
            `;
                locations.insertAdjacentHTML("beforeend", html);
            } else {
                axios
                    .post("/api/getLocations", basket)
                    .then((res) => {
                        if (res.data) {
                            displayBasket(res, "button");
                        }
                    })
                    .catch((err) => {
                        throw err;
                    });
            }
        });
    </script>
</body>

</html>