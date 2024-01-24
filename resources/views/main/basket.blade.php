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
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
</head>

<body>
    <x-loading />
    <x-navbar />
    <div class="container">
        <div class="main-basket">
            <div class="content-basket">
                <div class="location-mark">
                    <div class="row flex-between align-center py-1">
                        <div>
                            <h3>ทริปของคุณ</h3>
                            <div>
                                <p>ตำแหน่งปัจจุบัน</p>
                                <p id="current-geo"></p>
                            </div>
                        </div>
                        <button class="btn-secondary" onclick="clearAllPlanInBasket(event)">
                            ล้างแผนทั้งหมด
                        </button>
                    </div>

                    <hr>
                </div>
                <div class="locations" id="locations">
                    <!-- return location -->

                </div>
            </div>
            <div class="tooltips-basket">
                <h5>เครื่องมือช่วยจัดทริป</h5>
                <button onclick="getCurrectGeo(event)" class="row align-center flex-center">
                    <span class="material-icons mr-05">
                        my_location
                    </span>
                    ใช้ตำแหน่งปัจจุบัน
                </button>
                <br>
                <button onclick="calcDistance(event)" class="row align-center flex-center">
                    <span class="material-icons mr-05">
                        sort
                    </span>
                    เรียงลำดับตามระยะทาง
                </button>
                <br>
                <button onclick="navigative(event)" class="row align-center flex-center">
                    <span class="material-icons mr-05">
                        navigation
                    </span>
                    หาเส้นทาง
                </button>
                <form method="post" action="/api/addPlan" id="planForm">
                    @csrf
                    <input type="text" name="plan_name" placeholder="ชื่อแผนการท้องเที่ยว" required>
                    <input type="hidden" name="member_id" value="{{ session('member_id') }}">
                    <input type="hidden" name="location_id" id="location_id">
                    <button class="btn-primary" type="submit">บันทึก</button>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/basket.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js" defer></script>

</body>

</html>
