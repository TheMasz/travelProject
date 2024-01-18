<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แผนท่องเที่ยวของฉัน</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/myplans.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
</head>

<body>
    <x-loading />
    <x-navbar />
    <div class="container">
        <div class="plans-wrap">
            <h3>แผนท่องเที่ยวของฉัน</h3>
            @if($myplans->count() > 0)
            @foreach($myplans as $plan)
            @php
            $locationIdsString = $plan->locations_id;
            $locationIdsArray = explode(',', $locationIdsString);
            $locationIdsArray = array_map('intval', $locationIdsArray);
            @endphp
            <div class="plan">
                <div class="gallery">
                    @foreach(app('getLocations')($locationIdsArray) as $location)
                    @php
                    $images = explode(',',$location->Images);
                    @endphp
                    <img src="{{ asset('storage/images/locations/' . $images[0]) }}" alt="{{$location->location_name}}" />
                    @endforeach
                </div>
                <div class="details">
                    <h4>{{$plan->plan_name}}</h4>
                    <ul>
                        @foreach(app('getLocations')($locationIdsArray) as $location)

                        <li> <a href="/province/{{$location->province_id}}/{{$location->location_id}}">{{$location->location_name}}</a></li>
                        @endforeach
                    </ul>
                    <div class='btns row'>
                        <button onClick='usePlan("{{ str_replace(' ', '', $locationIdsString) }}", ".plans-wrap h3")' class="btn-primary">
                            ใช้แผนท่องเที่ยวนี้
                        </button>
                        <button class="btn_del" onClick='removePlan("{{$plan->plan_name}}")'>
                            <span class="material-icons">
                                delete
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            @endforeach
            @else
            <div class='back-home'>
                <span class="material-icons" style='color:lightgray; font-size:64px; margin-bottom:5px'>
                    luggage
                </span>
                <p class='mb-2' style='color:lightgray; text-align:center; width:75%'>ยังไม่มีสถานที่ท่องเที่ยวในการเดินของคุณ
                    กรุณาเริ่มด้วยการเลือกสถานที่ที่คุณต้องการเพิ่มเข้าในแผนการท่องเที่ยวของคุณ คุณสามารถเริ่มการค้นหาและเพิ่มสถานที่ท่องเที่ยวได้ที่หน้าหลักของเรา</p>
                <a href='/' class='btn-primary mb-05'>ไปยังหน้าแรก</a>
            </div>
            @endif


        </div>
    </div>

    <x-confirm />
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/myplans.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js" defer></script>
</body>

</html>