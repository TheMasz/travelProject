<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/locationDetail.css') }}">
    <script src="https://api.longdo.com/map/?key=7de1ec7f25b9980ac90ec2457c427a3e"></script>
    <title>{{$location_detail->location_name}}</title>
    <script>
        var map, latitude, longitude;


        document.addEventListener('DOMContentLoaded', () => {
            latitude = document.getElementById('map').getAttribute('data-latitude');
            longitude = document.getElementById('map').getAttribute('data-longitude');
        });

        function init() {
            map = new longdo.Map({
                placeholder: document.getElementById('map')
            });
            var marker = new longdo.Marker({
                lon: parseFloat(longitude),
                lat: parseFloat(latitude)
            });
            map.Overlays.add(marker);
            map.location({
                lon: longitude,
                lat: latitude
            }, true);
            map.zoom(12, true);

        }
    </script>
</head>

<body onload="init();">
    <x-loading />
    <x-navbar />
    <div class="container">
        <div class="images-wrap">
            <div class="main-image">
                <div class="image"></div>
                <div class="credit">credit: {{$location_detail->credit}}</div>
            </div>
            <div class="image-box">
                @php
                $images = explode(', ', $location_detail->Images);
                @endphp
                @foreach($images as $img)
                <div class="image" style="background: url({{ asset('storage/images/'.$img) }});" data-img="{{$img}}">
                </div>
                @endforeach
            </div>
        </div>
        <div class="details-wrap">
            <div class="title row">
                <div class="txt">
                    <h1>{{$location_detail->location_name}}</h1>
                    <div class="row">
                        <span class="material-icons">
                            place
                        </span>
                        <p>{{$location_detail->address}}</p>
                    </div>
                </div>
                <div class="button-wrap">
                    <button class="btn-secondary" onclick="addPlan({{$location_detail->location_id}})">เพิ่มลงทริป</button>
                </div>
            </div>
            <div class="row desc-wrap">
                <div class="detail">
                    <p style="text-align: justify;">{{$location_detail->detail}}</p>
                    <div class="categories row py-1">
                        @foreach(explode(',', $location_detail->Preferences) as $pref)
                        <p>{{$pref}}</p>
                        @endforeach
                    </div>
                </div>
                <div class="map" id="map" data-latitude="{{ $location_detail->latitude }}" data-longitude="{{ $location_detail->longitude }}">
                </div>
            </div>
        </div>
        <div class="comments-wrap">
            <button class="btn-secondary">เขียนรีวิว</button>
            <div class="comments">
                <div class="comment">
                    <div class="user">
                        <h4>user1</h4>
                        <div class="star">
                            <span class="material-icons">star</span>
                            <span class="material-icons">star</span>
                            <span class="material-icons">star</span>
                            <span class="material-icons">star</span>
                            <span class="material-icons">star</span>
                        </div>
                        <p class="date">2023-05-03 13:15</p>
                    </div>
                    <div class="txt">
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Suscipit omnis perferendis vel quia iusto porro itaque enim, odio accusamus, veritatis ipsam molestias aspernatur commodi? Obcaecati delectus nostrum nobis? Dolore, distinctio.
                    </div>
                    <div class="like">
                        <button>
                            <span class="material-icons" style="color: red;">
                                favorite
                            </span>
                            <p>ถูกใจ</p>
                        </button>
                        <p>2,000 ถูกใจ</p>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/locationDetail.js') }}"></script>
</body>

</html>