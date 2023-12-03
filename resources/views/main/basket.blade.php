<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการแผนการท่องเที่ยว</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/basket.css') }}">

</head>

<body>
    <x-loading />
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
                <form method="post" action="/api/addPlan" id="planForm">
                    @csrf
                    <input type="text" name="plan_name" placeholder="ชื่อแผนการท้องเที่ยว" required>
                    <input type="hidden" name="member_id" value="{{ session('member_id') }}">
                    <input type="hidden" name="location_id" id="location_id">
                    <button type="submit">บันทึก</button>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/basket.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

</body>

</html>